<?php

namespace App\Models\TaxItr;

use App\Helpers\LeaseHelper;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{

    protected $table = 'osrs_categories';
    protected $fillable = [
        'id', 'parent_id', 'access', 'published', 'category_name_es', 'category_alias_es', 'category_description_es', 'category_alias', 'category_description', 'ordering', 'category_image'
    ];
    protected $hidden = [
        'parent_id', 'access', 'published', 'category_name_es', 'category_alias_es', 'category_description_es', 'category_alias', 'category_description', 'ordering', 'category_image'
    ];
    public $timestamps = false;

    public static function getCategory()
    {
        try {
            //  $parentId = Category::whereIn('category_name', $type)->get(['id'])->toArray();
            $query = self::select('id AS value', 'category_name AS text', 'parent_id as parent');
//            if (Auth::check())
//                $query->whereIn('access', [1, Auth::User()->agents->agent_type]);
//            else
//                $query->whereIn('access', 1);
            $categories = $query->orderBy('ordering')->get();
        } catch (ModelNotFoundException $exe) {
            return LeaseHelper::response(false, 500, 'Catgeory not found');
        }
        if (count($categories)) {
            return LeaseHelper::response(true, 200, 'Successful result', $categories);
        } else {
            return LeaseHelper::response(false, 200, 'Catgeory not found');
        }
    }

}