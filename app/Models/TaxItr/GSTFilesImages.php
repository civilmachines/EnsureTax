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
        'agent_id', 'category', 'image', 'create_date', 'isActive'];

    protected $hidden = [];

    public $timestamps = false;


    public function userDetails()
    {
        return $this->belongsTo('App\Models\User\UserProfile', 'agent_id');
    }

    public static function GSTFiles($data)
    {
        $user = Auth::User();
        try {
            $query = GSTFilesImages::with(['userDetails']);
            if (!$user->hasRole(Config('auth.roles.Super_User'), false))
                $itr = $query->where('agent_id', $user->agents->id);

            $itr = $query->groupBy('agent_id')->get();
            /*
                        if (!$user->hasRole(Config('auth.roles.Super_User'), false))
                            $itr = GSTFilesImages::where('agent_id', $user->agents->id)->get();*/
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

}