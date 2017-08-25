<?php

namespace App\Models\TaxItr;

use App\Helpers\LeaseHelper;
use App\Models\User\UserProfile;
use Carbon\Carbon;
use Config;
use Storage;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GSTFilesImages extends Model
{
    protected $table = 'tax_gst_images';

    protected $fillable = [
        'agent_id', 'category', 'image', 'status', 'create_date', 'isActive'];

    protected $hidden = [];

    public $timestamps = false;


    public function userDetails()
    {
        return $this->belongsTo('App\Models\User\UserProfile', 'agent_id');
    }

    public function status(){
        return $this->belongsTo('App\Models\TaxItr\Categories', 'status');
    }

    public static function GSTFiles($data)
    {
        $orderby = 'agent_id';
        $order = 'desc';
        $user = Auth::User();
        try {
            $query = GSTFilesImages::with(['userDetails', 'status']);
            if (!$user->hasRole(Config('auth.roles.Super_User'), false))
                $query->where('agent_id', $user->agents->id);

            if (array_has($data, 'orderBy') && array_has($data, 'order')) {
                $orderby = $data['orderBy'];
                $order = $data['order'];
            }

            if(!array_has($data, 'agent_id') && array_has($data, 'status') && $data['status'] > 0){
                $query->where('status', $data['status'])->get();
            }
//            $itr = $query->orderBy($orderby, $order)->get();

            $itr = $query->groupBy('agent_id')->orderBy($orderby, $order)->get();
            if (!$itr) {
                return LeaseHelper::response(false, 200, 'Data Not Found');
            }
        } catch (Exception $exe) {
            return LeaseHelper::response(false, 500, $exe->getMessage());
        }
        return LeaseHelper::response(true, 200, 'Data Retrieved', $itr);
    }

    public static function uploadGST($request)
    {
        $user = Auth::User();
        $leaseHelper = new LeaseHelper();
        try {
            if ($request->hasFile('gst_files')) {
                $path = Config::get('global.GST_FILES_IMAGES.image_path');
                $path = $path . '/' . $user->agents->id . '/' . Config('global.itr_image_category.gst_files');
                $files = $request->file('gst_files');
                foreach ($files as $file) {
                    $imageName = $leaseHelper->fit($file, $path);
                    if (!$imageName) {
                        return LeaseHelper::response(false, 200, 'failed to upload files');
                    }
                    $data['agent_id'] = $user->agents->id;
                    $data['image'] = $imageName;
                    $data['category'] = Config('global.itr_image_category.gst_files');
                    $data['create_date'] = Carbon::now();
                    if (!GSTFilesImages::create($data)) {
                        return LeaseHelper::response(false, 500, 'Failed to upload image');
                    }
                }
            }
        } catch (ModelNotFoundException $exe) {
            return LeaseHelper::response(false, 500, $exe->getMessage());
        }
        return LeaseHelper::response(true, 200, 'Image Uploaded Successfully');
    }

    public static function editGSTFiles($id)
    {
        try {

            $gstFiles = UserProfile::with('gstImages')->select('id')->where('id', $id)->first();
            if (!$gstFiles)
                return LeaseHelper::response(false, 200, 'No Data Found');
        } catch (Exception $exe) {
            return LeaseHelper::response(false, 500, $exe->getMessage());
        }
        return LeaseHelper::response(true, 200, 'Data Retrieved', $gstFiles);
    }

    public static function deleteImage($data)
    {
        if (!GSTFilesImages::where('agent_id', $data['agent_id'])->exists()) {
            return LeaseHelper::response(false, 200, 'Agent Id Not Found');
        }
        $path = Config::get('global.GST_FILES_IMAGES.image_path');
        $path = $path . '/' . $data['agent_id'];
        try {
            GSTFilesImages::where('image', $data['img_name'])->delete();
        } catch (ModelNotFoundException $exe) {
            return LeaseHelper::response(false, 500, $exe->getMessage());
        }

        $originalimage = $path . '/' . $data['category'] . '/' . $data['img_name'];
        try {
            $status = Storage::delete($originalimage);
            if (!$status) {
                return LeaseHelper::response(false, 200, 'Failed to Delete Image');
            }
        } catch (ModelNotFoundException $exe) {
            return LeaseHelper::response(true, 500, $exe->getMessage());
        }
        return LeaseHelper::response(true, 200, 'Image Has Benn Deleted Successfully');
    }

    public static function gstStatus($data){
        try{
            $value = $data['agent_id'];
            if(array_has($data, 'status')) {
                foreach ($value as $id) {
                    $sta = GSTFilesImages::where('agent_id', $id)
                        ->update(array('status' => $data['status']));
                }
            }
        }catch (ModelNotFoundException $exe){
            return LeaseHelper::response(false, 500, $exe->getMessage());
        }
        $sta = self::GSTFiles($data);
        return LeaseHelper::response(true, 200, 'Successful', $sta['data']);
    }

}