<?php

namespace App\Models\TaxItr;
use App\Helpers\LeaseHelper;
use Config;
use Storage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BasicDetailsImages extends Model
{
    protected $table = 'tax_basic_details_images';

    protected $fillable = [
        'agent_id', 'category', 'image', 'create_date', 'isActive'];

    protected $hidden = [];

    public $timestamps = false;

    public static function deleteImage($data){
        if(!BasicDetailsImages::where('agent_id', $data['agent_id'])->exists()){
            return LeaseHelper::response(false, 200, 'Agent Id Not Found');
        }
        $path = Config::get('global.Profile_Image_Path.image_path');
        $path = $path .'/' .$data['agent_id'];
        try{
            BasicDetailsImages::where('image', $data['img_name'])->delete();
        }catch (ModelNotFoundException $exe){
            return LeaseHelper::response(false, 500, $exe->getMessage());
        }

        $originalimage = $path. '/' .$data['category']. '/' .$data['img_name'];
        try{
            $status = Storage::delete($originalimage);
            if(!$status){
                return LeaseHelper::response(false, 200, 'Failed to Delete Image');
            }
        }catch (ModelNotFoundException $exe){
            return LeaseHelper::response(true, 500, $exe->getMessage());
        }
        return LeaseHelper::response(true, 200, 'Image Has Benn Deleted Successfully');
    }

}