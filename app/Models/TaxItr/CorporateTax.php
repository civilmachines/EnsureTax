<?php

namespace App\Models\TaxItr;

use App\Helpers\LeaseHelper;
use Carbon\Carbon;
use Config;
use Auth;
use Storage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use MongoDB\Driver\Exception\ExecutionTimeoutException;

class CorporateTax extends Model
{
    protected $table = 'tax_itr_corporate';

    protected $fillable = [
        'id', 'user_id', 'ITR', 'company_name', 'contact', 'company_pan', 'company_adhar', 'bank_name', 'bank_ifsc', 'company_add', 'status', 'create_date'];

    protected $hidden = [];

    public $timestamps = false;

    public function itrImages()
    {
        return $this->hasMany('App\Models\TaxItr\CorporateImages', 'itr_id', 'id')->select('category', 'image', 'itr_id');
    }

    public function ITR()
    {
        return $this->belongsTo('App\Models\TaxItr\Categories', 'ITR');
    }

    public function STATUS()
    {
        return $this->belongsTo('App\Models\TaxItr\Categories', 'status');
    }

    public function userDetails()
    {
        return $this->belongsTo('App\Models\User\UserProfile', 'user_id');
    }

    public function profileImages()
    {
        return $this->hasMany('App\Models\TaxItr\BasicDetailsImages', 'agent_id', 'user_id')->select('category', 'image', 'agent_id');
    }

    public static function corporateDetails($request)
    {
        $user = Auth::User();
        if ($request->has('id') && $request->input('id') > 0)
            $itr1 = CorporateTax::find($request['id']);
        else
            $itr1 = new CorporateTax();
        if (!$user->hasRole(Config('auth.roles.Super_User'), false))
//            $itr1->where('user_id', $user->agents->id);
            $itr1->user_id = Auth::User()->agents->id;
        $itr1->fill($request->all());
        $itr1['create_date'] = Carbon::now();
        $leaseHelper = new LeaseHelper();
        try {
            if (!$itr1->save()) {
                return LeaseHelper::response(false, 500, 'Failed to save Data');
            }
        } catch (Exception $ex) {
            return LeaseHelper::response(false, 500, $ex->getMessage());
        }
        if ($request->hasFile('pan_image')) {
            $path = Config::get('global.Corporate_Image_Path.image_path');
            $path = $path . '/' . $itr1->id . '/' . Config('global.itr_image_category.pan_image');
            $files = $request->file('pan_image');
            foreach ($files as $file) {
                $imageName = $leaseHelper->fit($file, $path);
                if (!$imageName) {
                    return $leaseHelper->response(false, 200, 'failed to upload image');
                }
                $data['itr_id'] = $itr1->id;
                $data['category'] = Config('global.itr_image_category.pan_image');
                $data['image'] = $imageName;
                if (!CorporateImages::create($data)) {
                    return $leaseHelper->response(false, 500, 'Failed to Upload Image');
                }
            }
        }

        if ($request->hasFile('adhar_image')) {
            $path = Config::get('global.Corporate_Image_Path.image_path');
            $path = $path . '/' . $itr1->id . '/' . Config('global.itr_image_category.adhar_image');
            $files = $request->file('adhar_image');
            foreach ($files as $file) {
                $imageName = $leaseHelper->fit($file, $path);
                if (!$imageName) {
                    return $leaseHelper->response(false, 200, 'failed to upload image');
                }
                $data['itr_id'] = $itr1->id;
                $data['category'] = Config('global.itr_image_category.adhar_image');
                $data['image'] = $imageName;
                if (!CorporateImages::create($data)) {
                    return $leaseHelper->response(false, 500, 'Failed to Upload Image');
                }
            }
        }

        if ($request->hasFile('bank_statement')) {
            $path = Config::get('global.Corporate_Image_Path.image_path');
            $path = $path . '/' . $itr1->id . '/' . Config('global.itr_image_category.bank_statement');
            $files = $request->file('bank_statement');
            foreach ($files as $file) {
                $imageName = $leaseHelper->fit($file, $path);
                if (!$imageName) {
                    return $leaseHelper->response(false, 200, 'Failed to upload image');
                }
                $data['itr_id'] = $itr1->id;
                $data['image'] = $imageName;
                $data['category'] = Config('global.itr_image_category.bank_statement');
                if (!CorporateImages::create($data)) {
                    return $leaseHelper->response(false, 500, 'Failed to upload image');
                }
            }
        }

        return LeaseHelper::response(true, 200, 'Details Saved Successfully', $itr1->id);
    }


    public static function  itr5($request)
    {
        $user = Auth::User();
        if ($request->has('id') && $request->input('id') > 0) {
            $itr1 = CorporateTax::find($request['id']);
            $itr1['create_date'] = Carbon::now();
            $leaseHelper = new LeaseHelper();
            if (!$user->hasRole(Config('auth.roles.Super_User'), false))
                $itr1->user_id = Auth::User()->agents->id;
            $itr1->fill($request->all());

            if ($request->hasFile('audit_report_img')) {
                $path = Config::get('global.Corporate_Image_Path.image_path');
                $path = $path . '/' . $itr1->id . '/' . Config('global.itr_image_category.audit_report_img');
                $files = $request->file('audit_report_img');
                foreach ($files as $file) {
                    $imageName = $leaseHelper->fit($file, $path);
                    if (!$imageName) {
                        return $leaseHelper->response(false, 200, 'Failed to upload image');
                    }
                    $data['itr_id'] = $itr1->id;
                    $data['image'] = $imageName;
                    $data['category'] = Config('global.itr_image_category.audit_report_img');
                    if (!CorporateImages::create($data)) {
                        return $leaseHelper->response(false, 500, 'Failed to upload image');
                    }
                }
            }

            if ($request->hasFile('previous_return_image')) {
                $path = Config::get('global.Corporate_Image_Path.image_path');
                $path = $path . '/' . $itr1->id . '/' . Config('global.itr_image_category.previous_return_image');
                $files = $request->file('previous_return_image');
                foreach ($files as $file) {
                    $imageName = $leaseHelper->fit($file, $path);
                    if (!$imageName) {
                        return $leaseHelper->response(false, 200, 'Failed to upload image');
                    }
                    $data['itr_id'] = $itr1->id;
                    $data['image'] = $imageName;
                    $data['category'] = Config('global.itr_image_category.previous_return_image');
                    if (!CorporateImages::create($data)) {
                        return $leaseHelper->response(false, 500, 'Failed to upload image');
                    }
                }
            }
            if ($request->hasFile('form_26A')) {
                $path = Config::get('global.Corporate_Image_Path.image_path');
                $path = $path . '/' . $itr1->id . '/' . Config('global.itr_image_category.form_26A');
                $files = $request->file('form_26A');
                foreach ($files as $file) {
                    $imageName = $leaseHelper->fit($file, $path);
                    if (!$imageName) {
                        return $leaseHelper->response(false, 200, 'Failed to upload image');
                    }
                    $data['itr_id'] = $itr1->id;
                    $data['image'] = $imageName;
                    $data['category'] = Config('global.itr_image_category.form_26A');
                    if (!CorporateImages::create($data)) {
                        return $leaseHelper->response(false, 500, 'Failed to upload image');
                    }
                }
            }
            if ($request->hasFile('financial_documents')) {
                $path = Config::get('global.Corporate_Image_Path.image_path');
                $path = $path . '/' . $itr1->id . '/' . Config('global.itr_image_category.financial_documents');
                $files = $request->file('financial_documents');
                foreach ($files as $file) {
                    $imageName = $leaseHelper->fit($file, $path);
                    if (!$imageName) {
                        return $leaseHelper->response(false, 200, 'Failed to upload image');
                    }
                    $data['itr_id'] = $itr1->id;
                    $data['image'] = $imageName;
                    $data['category'] = Config('global.itr_image_category.financial_documents');
                    if (!CorporateImages::create($data)) {
                        return $leaseHelper->response(false, 500, 'Failed to upload image');
                    }
                }
            }
            if ($request->hasFile('income_computation')) {
                $path = Config::get('global.Corporate_Image_Path.image_path');
                $path = $path . '/' . $itr1->id . '/' . Config('global.itr_image_category.income_computation');
                $files = $request->file('income_computation');
                foreach ($files as $file) {
                    $imageName = $leaseHelper->fit($file, $path);
                    if (!$imageName) {
                        return $leaseHelper->response(false, 200, 'Failed to upload image');
                    }
                    $data['itr_id'] = $itr1->id;
                    $data['image'] = $imageName;
                    $data['category'] = Config('global.itr_image_category.income_computation');
                    if (!CorporateImages::create($data)) {
                        return $leaseHelper->response(false, 500, 'Failed to upload image');
                    }
                }
            }
            if ($request->hasFile('share_holders')) {
                $path = Config::get('global.Corporate_Image_Path.image_path');
                $path = $path . '/' . $itr1->id . '/' . Config('global.itr_image_category.share_holders');
                $files = $request->file('share_holders');
                foreach ($files as $file) {
                    $imageName = $leaseHelper->fit($file, $path);
                    if (!$imageName) {
                        return $leaseHelper->response(false, 200, 'Failed to upload image');
                    }
                    $data['itr_id'] = $itr1->id;
                    $data['image'] = $imageName;
                    $data['category'] = Config('global.itr_image_category.share_holders');
                    if (!CorporateImages::create($data)) {
                        return $leaseHelper->response(false, 500, 'Failed to upload image');
                    }
                }
            }
            try {
                if (!$itr1->save()) {
                    return LeaseHelper::response(false, 500, 'Failed to save Data');
                }
            } catch (Exception $ex) {
                return LeaseHelper::response(false, 500, $ex->getMessage());
            }
        } else {
            return LeaseHelper::response(false, 200, 'No Data Found');
        }
        return LeaseHelper::response(true, 200, 'ITR5 Saved Successfully', $itr1->id);
    }

    public static function ITRList($data)
    {
        $user = Auth::User();
        try {
            $query = CorporateTax::with(['ITR']);
            if (!$user->hasRole(Config('auth.roles.Super_User'), false))
                $itr =  $query->where('user_id', $user->agents->id);

            $itr = $query->get();
            if(!$itr){
                return LeaseHelper::response(false, 200, 'Data Not Found');
            }
        }
        catch (Exception $exe) {
            return LeaseHelper::response(false, 500, $exe->getMessage());
        }
        return LeaseHelper::response(true, 200, 'Data Retrieved', $itr);
    }

    public static function editITR($id)
    {
        try {
            $itr = CorporateTax::with('itrImages')->where('id', $id)->first();
            if (!$itr)
                return LeaseHelper::response(false, 200, 'No Data Found');
        } catch (Exception $exe) {
            return LeaseHelper::response(false, 500, $exe->getMessage());
        }
        return LeaseHelper::response(true, 200, 'Data Retrieved', $itr);
    }

    public  static function deleteImage($data){
        if(!CorporateImages::where('itr_id', $data['itr_id'])->exists()){
            return LeaseHelper::response(false, 200, 'ITR ID Not Found');
        }
        $path = Config::get('global.Corporate_Image_Path.image_path');
        $imgPath = $path. '/'. $data['itr_id'];
        try{
            CorporateImages::where('image', $data['img_name'])->delete();
        }catch (ModelNotFoundException $exe){
            return LeaseHelper::response(false, 500, $exe->getMessage());
        }

        $originalImage = $imgPath . '/' . $data['category'] . '/' .$data['img_name'];
        try{
            $status = Storage:: delete([$originalImage]);
            if(!$status){
                return LeaseHelper::response(false, 200, 'Failed to Delete Image');
            }
        }catch (ModelNotFoundException $exe){
            return LeaseHelper::response(false, 200, $exe->getMessage());
        }
        return LeaseHelper::response(true, 200, 'Image Deleted Successfully');
    }

}