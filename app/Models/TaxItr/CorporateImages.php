<?php

namespace App\Models\TaxItr;
use App\Helpers\LeaseHelper;
use Config;
use Storage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
class CorporateImages extends Model
{
    protected $table = 'tax_itr_corporate_images';

    protected $fillable = [
        'itr_id', 'category', 'image', 'create_date', 'isActive'];

    protected $hidden = [];

    public $timestamps = false;

}