<?php

namespace App\Models\TaxItr;
use App\Helpers\LeaseHelper;
use Illuminate\Database\Eloquent\Model;
use League\Flysystem\Config;

class ITRModelImages extends Model
{
    protected $table = 'tax_itr_images';

    protected $fillable = [
        'itr_id', 'category', 'image', 'create_date', 'isActive'];

    protected $hidden = [];

    public $timestamps = false;

}