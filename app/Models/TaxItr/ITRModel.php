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

class ITRModel extends Model
{
    protected $table = 'tax_itr';

    protected $fillable = [
        'id', 'user_id', 'ITR', 'form_16_amnt', 'house_income', 'interest_income', 'loss_amnt', 'jewellery_amnt', 'paintings_amnt',
        'vehicles_amnt', 'bank_amnt', 'share_amnt', 'loan_amnt', 'cash_amnt', 'other_liability_amnt', 'business_nature',
        'co_owned_prop', 'partner_firms', 'capital_gains', 'other_source_income', 'immovable_assets', 'outside_income', 'assets_partner', 'other_cntry_tax', 'audit_report', 'return_filing_date', 'turnover', 'net_profit', 'status', 'create_date'
    ];

    protected $hidden = [];

    public $timestamps = false;

    public function itrImages()
    {
        return $this->hasMany('App\Models\TaxItr\ITRModelImages', 'itr_id', 'id')->select('category', 'image', 'itr_id');
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

    public static function itr1($request)
    {
        $user = Auth::User();
        if ($request->has('id') && $request->input('id') > 0)
            $itr1 = ITRModel::find($request['id']);
        else
            $itr1 = new ITRModel();
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
        if ($request->hasFile('form_16')) {
            $path = Config::get('global.ITR_Image_Path.image_path');
            $path = $path . '/' . $itr1->id . '/' . Config('global.itr_image_category.form_16');
            $files = $request->file('form_16');
            foreach ($files as $file) {
                $imageName = $leaseHelper->fit($file, $path);
                if (!$imageName) {
                    return $leaseHelper->response(false, 200, 'failed to upload image');
                }
                $data['itr_id'] = $itr1->id;
                $data['category'] = Config('global.itr_image_category.form_16');
                $data['image'] = $imageName;
                if (!ITRModelImages::create($data)) {
                    return $leaseHelper->response(false, 500, 'Failed to Upload Image');
                }
            }
        }
        if ($request->hasFile('loan_certificate')) {
            $path = Config::get('global.ITR_Image_Path.image_path');
            $path = $path . '/' . $itr1->id . '/' . Config('global.itr_image_category.loan_certificate');
            $files = $request->file('loan_certificate');
            foreach ($files as $file) {
                $imageName = $leaseHelper->fit($file, $path);
                if (!$imageName) {
                    return $leaseHelper->response(false, 200, 'fialed to upload image');
                }
                $data['itr_id'] = $itr1->id;
                $data['category'] = Config('global.itr_image_category.loan_certificate');
                $data['image'] = $imageName;
                if (!ITRModelImages::create($data)) {
                    return $leaseHelper->response(false, 500, 'failed to Upload image');
                }
            }
        }
        if ($request->hasFile('interest_certificate')) {
            $path = Config::get('global.ITR_Image_Path.image_path');
            $path = $path . '/' . $itr1->id . '/' . Config('global.itr_image_category.interest_certificate');
            $files = $request->file('interest_certificate');
            foreach ($files as $file) {
                $imageName = $leaseHelper->fit($file, $path);
                if (!$imageName) {
                    return $leaseHelper->response(false, 200, 'fialed to upload image');
                }
                $data['itr_id'] = $itr1->id;
                $data['category'] = Config('global.itr_image_category.interest_certificate');
                $data['image'] = $imageName;
                if (!ITRModelImages::create($data)) {
                    return $leaseHelper->response(false, 500, 'failed to Upload image');
                }
            }
        }
        if ($request->hasFile('claim_80C')) {
            $path = Config::get('global.ITR_Image_Path.image_path');
            $path = $path . '/' . $itr1->id . '/' . Config('global.itr_image_category.claim_80C');
            $files = $request->file('claim_80C');
            foreach ($files as $file) {
                $imageName = $leaseHelper->fit($file, $path);
                if (!$imageName) {
                    return $leaseHelper->response(false, 200, 'fialed to upload image');
                }
                $data['itr_id'] = $itr1->id;
                $data['category'] = Config('global.itr_image_category.claim_80C');
                $data['image'] = $imageName;
                if (!ITRModelImages::create($data)) {
                    return $leaseHelper->response(false, 500, 'failed to Upload image');
                }
            }
        }
        if ($request->hasFile('claim_80D')) {
            $path = Config::get('global.ITR_Image_Path.image_path');
            $path = $path . '/' . $itr1->id . '/' . Config('global.itr_image_category.claim_80D');
            $files = $request->file('claim_80D');
            foreach ($files as $file) {
                $imageName = $leaseHelper->fit($file, $path);
                if (!$imageName) {
                    return $leaseHelper->response(false, 200, 'fialed to upload image');
                }
                $data['itr_id'] = $itr1->id;
                $data['category'] = Config('global.itr_image_category.claim_80D');
                $data['image'] = $imageName;
                if (!ITRModelImages::create($data)) {
                    return $leaseHelper->response(false, 500, 'failed to Upload image');
                }
            }
        }
        if ($request->hasFile('claim_80G')) {
            $path = Config::get('global.ITR_Image_Path.image_path');
            $path = $path . '/' . $itr1->id . '/' . Config('global.itr_image_category.claim_80G');
            $files = $request->file('claim_80G');
            foreach ($files as $file) {
                $imageName = $leaseHelper->fit($file, $path);
                if (!$imageName) {
                    return $leaseHelper->response(false, 200, 'fialed to upload image');
                }
                $data['itr_id'] = $itr1->id;
                $data['category'] = Config('global.itr_image_category.claim_80G');
                $data['image'] = $imageName;
                if (!ITRModelImages::create($data)) {
                    return $leaseHelper->response(false, 500, 'failed to Upload image');
                }
            }
        }
        if ($request->hasFile('other_deduction')) {
            $path = Config::get('global.ITR_Image_Path.image_path');
            $path = $path . '/' . $itr1->id . '/' . Config('global.itr_image_category.other_deduction');
            $files = $request->file('other_deduction');
            foreach ($files as $file) {
                $imageName = $leaseHelper->fit($file, $path);
                if (!$imageName) {
                    return $leaseHelper->response(false, 200, 'failed to upload image');
                }
                $data['itr_id'] = $itr1->id;
                $data['category'] = Config('global.itr_image_category.other_deduction');
                $data['image'] = $imageName;
                if (!ITRModelImages::create($data)) {
                    return $leaseHelper->response(false, 500, 'failed to Upload image');
                }
            }
        }
        if ($request->hasFile('tax_challan')) {
            $path = Config::get('global.ITR_Image_Path.image_path');
            $path = $path . '/' . $itr1->id . '/' . Config('global.itr_image_category.tax_challan');
            $files = $request->file('tax_challan');
            foreach ($files as $file) {
                $imageName = $leaseHelper->fit($file, $path);
                if (!$imageName) {
                    return $leaseHelper->response(false, 200, 'failed to upload image');
                }
                $data['itr_id'] = $itr1->id;
                $data['category'] = Config('global.itr_image_category.tax_challan');
                $data['image'] = $imageName;
                if (!ITRModelImages::create($data)) {
                    return $leaseHelper->response(false, 500, 'failed to Upload image');
                }
            }
        }
        if ($request->hasFile('form_26A')) {
            $path = Config::get('global.ITR_Image_Path.image_path');
            $path = $path . '/' . $itr1->id . '/' . Config('global.itr_image_category.form_26A');
            $files = $request->file('form_26A');
            foreach ($files as $file) {
                $imageName = $leaseHelper->fit($file, $path);
                if (!$imageName) {
                    return $leaseHelper->response(false, 200, 'failed to upload image');
                }
                $data['itr_id'] = $itr1->id;
                $data['category'] = Config('global.itr_image_category.form_26A');
                $data['image'] = $imageName;
                if (!ITRModelImages::create($data)) {
                    return $leaseHelper->response(false, 500, 'failed to Upload image');
                }
            }
        }
        return LeaseHelper::response(true, 200, 'ITR1 Saved Successfully', $itr1->id);
    }

    public static function itr2($request)
    {
        $user = Auth::User();
        if ($request->has('id') && $request->input('id') > 0) {
            $itr1 = ITRModel::find($request['id']);
            $itr1['create_date'] = Carbon::now();
            $leaseHelper = new LeaseHelper();
            if (!$user->hasRole(Config('auth.roles.Super_User'), false))
                $itr1->user_id = Auth::User()->agents->id;
            $itr1->fill($request->all());
            if ($request->has('co_owned_prop')) {
                $itr1->co_owned_prop = $request->input('co_owned_prop') == "true" ? 1 : 0;
                if ($request->hasFile('co_owned_prop_image')) {
                    $path = Config::get('global.ITR_Image_Path.image_path');
                    $path = $path . '/' . $itr1->id . '/' . Config('global.itr_image_category.co_owned_prop_image');
                    $files = $request->file('co_owned_prop_image');
                    foreach ($files as $file) {
                        $imageName = $leaseHelper->fit($file, $path);
                        if (!$imageName) {
                            return $leaseHelper->response(false, 200, 'failed to upload image');
                        }
                        $data['itr_id'] = $itr1->id;
                        $data['category'] = Config('global.itr_image_category.co_owned_prop_image');
                        $data['image'] = $imageName;
                        if (!ITRModelImages::create($data)) {
                            return $leaseHelper->response(false, 500, 'Failed to Upload Image');
                        }
                    }
                }
            }
            if ($request->has('partner_firms')) {
                $itr1->partner_firms = $request->input('partner_firms') == "true" ? 1 : 0;
                if ($request->hasFile('partner_firm_image')) {
                    $path = Config::get('global.ITR_Image_Path.image_path');
                    $path = $path . '/' . $itr1->id . '/' . Config('global.itr_image_category.partner_firm_image');
                    $files = $request->file('partner_firm_image');
                    foreach ($files as $file) {
                        $imageName = $leaseHelper->fit($file, $path);
                        if (!$imageName) {
                            return $leaseHelper->response(false, 200, 'failed to upload image');
                        }
                        $data['itr_id'] = $itr1->id;
                        $data['category'] = Config('global.itr_image_category.partner_firm_image');
                        $data['image'] = $imageName;
                        if (!ITRModelImages::create($data)) {
                            return $leaseHelper->response(false, 500, 'failed to Upload image');
                        }
                    }
                }
            }
            if ($request->has('capital_gains')) {
                $itr1->capital_gains = $request->input('capital_gains') == "true" ? 1 : 0;
                if ($request->hasFile('capital_gain_image')) {
                    $path = Config::get('global.ITR_Image_Path.image_path');
                    $path = $path . '/' . $itr1->id . '/' . Config('global.itr_image_category.capital_gain_image');
                    $files = $request->file('capital_gain_image');
                    foreach ($files as $file) {
                        $imageName = $leaseHelper->fit($file, $path);
                        if (!$imageName) {
                            return $leaseHelper->response(false, 200, 'failed to upload image');
                        }
                        $data['itr_id'] = $itr1->id;
                        $data['category'] = Config('global.itr_image_category.capital_gain_image');
                        $data['image'] = $imageName;
                        if (!ITRModelImages::create($data)) {
                            return $leaseHelper->response(false, 500, 'failed to Upload image');
                        }
                    }
                }
            }
            if ($request->has('other_source_income')) {
                $itr1->other_source_income = $request->input('other_source_income') == "true" ? 1 : 0;
                if ($request->hasFile('othr_source_incm_image')) {
                    $path = Config::get('global.ITR_Image_Path.image_path');
                    $path = $path . '/' . $itr1->id . '/' . Config('global.itr_image_category.othr_source_incm_image');
                    $files = $request->file('othr_source_incm_image');
                    foreach ($files as $file) {
                        $imageName = $leaseHelper->fit($file, $path);
                        if (!$imageName) {
                            return $leaseHelper->response(false, 200, 'failed to upload image');
                        }
                        $data['itr_id'] = $itr1->id;
                        $data['category'] = Config('global.itr_image_category.othr_source_incm_image');
                        $data['image'] = $imageName;
                        if (!ITRModelImages::create($data)) {
                            return $leaseHelper->response(false, 500, 'failed to Upload image');
                        }
                    }
                }
            }
            if ($request->has('immovable_assets')) {
                $itr1->immovable_assets = $request->input('immovable_assets') == "true" ? 1 : 0;
                if ($request->hasFile('immovable_asset_image')) {
                    $path = Config::get('global.ITR_Image_Path.image_path');
                    $path = $path . '/' . $itr1->id . '/' . Config('global.itr_image_category.immovable_asset_image');
                    $files = $request->file('immovable_asset_image');
                    foreach ($files as $file) {
                        $imageName = $leaseHelper->fit($file, $path);
                        if (!$imageName) {
                            return $leaseHelper->response(false, 200, 'failed to upload image');
                        }
                        $data['itr_id'] = $itr1->id;
                        $data['category'] = Config('global.itr_image_category.immovable_asset_image');
                        $data['image'] = $imageName;
                        if (!ITRModelImages::create($data)) {
                            return $leaseHelper->response(false, 500, 'failed to Upload image');
                        }
                    }
                }
            }
            if ($request->has('outside_income')) {
                $itr1->outside_income = $request->input('outside_income') == "true" ? 1 : 0;
                if ($request->hasFile('outside_income_image')) {
                    $path = Config::get('global.ITR_Image_Path.image_path');
                    $path = $path . '/' . $itr1->id . '/' . Config('global.itr_image_category.outside_income_image');
                    $files = $request->file('outside_income_image');
                    foreach ($files as $file) {
                        $imageName = $leaseHelper->fit($file, $path);
                        if (!$imageName) {
                            return $leaseHelper->response(false, 200, 'failed to upload image');
                        }
                        $data['itr_id'] = $itr1->id;
                        $data['category'] = Config('global.itr_image_category.outside_income_image');
                        $data['image'] = $imageName;
                        if (!ITRModelImages::create($data)) {
                            return $leaseHelper->response(false, 500, 'failed to Upload image');
                        }
                    }
                }
            }
            if ($request->has('assets_partner')) {
                $itr1->assets_partner = $request->input('assets_partner') == "true" ? 1 : 0;
                if ($request->hasFile('assets_partner_image')) {
                    $path = Config::get('global.ITR_Image_Path.image_path');
                    $path = $path . '/' . $itr1->id . '/' . Config('global.itr_image_category.assets_partner_image');
                    $files = $request->file('assets_partner_image');
                    foreach ($files as $file) {
                        $imageName = $leaseHelper->fit($file, $path);
                        if (!$imageName) {
                            return $leaseHelper->response(false, 200, 'failed to upload image');
                        }
                        $data['itr_id'] = $itr1->id;
                        $data['category'] = Config('global.itr_image_category.assets_partner_image');
                        $data['image'] = $imageName;
                        if (!ITRModelImages::create($data)) {
                            return $leaseHelper->response(false, 500, 'failed to Upload image');
                        }
                    }
                }
            }
            if ($request->has('other_cntry_tax')) {
                $itr1->other_cntry_tax = $request->input('other_cntry_tax') == "true" ? 1 : 0;
                if ($request->hasFile('othr_cntry_tax_paid_img')) {
                    $path = Config::get('global.ITR_Image_Path.image_path');
                    $path = $path . '/' . $itr1->id . '/' . Config('global.itr_image_category.othr_cntry_tax_paid_img');
                    $files = $request->file('othr_cntry_tax_paid_img');
                    foreach ($files as $file) {
                        $imageName = $leaseHelper->fit($file, $path);
                        if (!$imageName) {
                            return $leaseHelper->response(false, 200, 'failed to upload image');
                        }
                        $data['itr_id'] = $itr1->id;
                        $data['category'] = Config('global.itr_image_category.othr_cntry_tax_paid_img');
                        $data['image'] = $imageName;
                        if (!ITRModelImages::create($data)) {
                            return $leaseHelper->response(false, 500, 'failed to Upload image');
                        }
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
        return LeaseHelper::response(true, 200, 'ITR2 Saved Successfully', $itr1->id);
    }

    public static function itr3($request)
    {
        $user = Auth::User();
        if ($request->has('id') && $request->input('id') > 0)
            $itr1 = ITRModel::find($request['id']);
        $itr1['create_date'] = Carbon::now();
        $leaseHelper = new LeaseHelper();
        if (!$user->hasRole(Config('auth.roles.Super_User'), false))
            $itr1->user_id = Auth::User()->agents->id;
        $itr1->fill($request->all());
        if ($request->hasFile('complete_finance_image')) {
            $path = Config::get('global.ITR_Image_Path.image_path');
            $path = $path . '/' . $itr1->id . '/' . Config('global.itr_image_category.complete_finance_image');
            $files = $request->file('complete_finance_image');
            foreach ($files as $file) {
                $imageName = $leaseHelper->fit($file, $path);
                if (!$imageName) {
                    return $leaseHelper->response(false, 200, 'failed to upload image');
                }
                $data['itr_id'] = $itr1->id;
                $data['category'] = Config('global.itr_image_category.complete_finance_image');
                $data['image'] = $imageName;
                if (!ITRModelImages::create($data)) {
                    return $leaseHelper->response(false, 500, 'Failed to Upload Image');
                }
            }
        }
        if ($request->has('co_owned_prop')) {
            $itr1->co_owned_prop = $request->input('co_owned_prop') == "true" ? 1 : 0;
            if ($request->hasFile('co_owned_prop_image')) {
                $path = Config::get('global.ITR_Image_Path.image_path');
                $path = $path . '/' . $itr1->id . '/' . Config('global.itr_image_category.co_owned_prop_image');
                $files = $request->file('co_owned_prop_image');
                foreach ($files as $file) {
                    $imageName = $leaseHelper->fit($file, $path);
                    if (!$imageName) {
                        return $leaseHelper->response(false, 200, 'failed to upload image');
                    }
                    $data['itr_id'] = $itr1->id;
                    $data['category'] = Config('global.itr_image_category.co_owned_prop_image');
                    $data['image'] = $imageName;
                    if (!ITRModelImages::create($data)) {
                        return $leaseHelper->response(false, 500, 'Failed to Upload Image');
                    }
                }
            }
        }
        if ($request->has('partner_firms')) {
            $itr1->partner_firms = $request->input('partner_firms') == "true" ? 1 : 0;
            if ($request->hasFile('partner_firm_image')) {
                $path = Config::get('global.ITR_Image_Path.image_path');
                $path = $path . '/' . $itr1->id . '/' . Config('global.itr_image_category.partner_firm_image');
                $files = $request->file('partner_firm_image');
                foreach ($files as $file) {
                    $imageName = $leaseHelper->fit($file, $path);
                    if (!$imageName) {
                        return $leaseHelper->response(false, 200, 'failed to upload image');
                    }
                    $data['itr_id'] = $itr1->id;
                    $data['category'] = Config('global.itr_image_category.partner_firm_image');
                    $data['image'] = $imageName;
                    if (!ITRModelImages::create($data)) {
                        return $leaseHelper->response(false, 500, 'failed to Upload image');
                    }
                }
            }
        }
        if ($request->has('capital_gains')) {
            $itr1->capital_gains = $request->input('capital_gains') == "true" ? 1 : 0;
            if ($request->hasFile('capital_gain_image')) {
                $path = Config::get('global.ITR_Image_Path.image_path');
                $path = $path . '/' . $itr1->id . '/' . Config('global.itr_image_category.capital_gain_image');
                $files = $request->file('capital_gain_image');
                foreach ($files as $file) {
                    $imageName = $leaseHelper->fit($file, $path);
                    if (!$imageName) {
                        return $leaseHelper->response(false, 200, 'failed to upload image');
                    }
                    $data['itr_id'] = $itr1->id;
                    $data['category'] = Config('global.itr_image_category.capital_gain_image');
                    $data['image'] = $imageName;
                    if (!ITRModelImages::create($data)) {
                        return $leaseHelper->response(false, 500, 'failed to Upload image');
                    }
                }
            }
        }
        if ($request->has('other_source_income')) {
            $itr1->other_source_income = $request->input('other_source_income') == "true" ? 1 : 0;
            if ($request->hasFile('othr_source_incm_image')) {
                $path = Config::get('global.ITR_Image_Path.image_path');
                $path = $path . '/' . $itr1->id . '/' . Config('global.itr_image_category.othr_source_incm_image');
                $files = $request->file('othr_source_incm_image');
                foreach ($files as $file) {
                    $imageName = $leaseHelper->fit($file, $path);
                    if (!$imageName) {
                        return $leaseHelper->response(false, 200, 'failed to upload image');
                    }
                    $data['itr_id'] = $itr1->id;
                    $data['category'] = Config('global.itr_image_category.othr_source_incm_image');
                    $data['image'] = $imageName;
                    if (!ITRModelImages::create($data)) {
                        return $leaseHelper->response(false, 500, 'failed to Upload image');
                    }
                }
            }
        }
        if ($request->has('immovable_assets')) {
            $itr1->immovable_assets = $request->input('immovable_assets') == "true" ? 1 : 0;
            if ($request->hasFile('immovable_asset_image')) {
                $path = Config::get('global.ITR_Image_Path.image_path');
                $path = $path . '/' . $itr1->id . '/' . Config('global.itr_image_category.immovable_asset_image');
                $files = $request->file('immovable_asset_image');
                foreach ($files as $file) {
                    $imageName = $leaseHelper->fit($file, $path);
                    if (!$imageName) {
                        return $leaseHelper->response(false, 200, 'failed to upload image');
                    }
                    $data['itr_id'] = $itr1->id;
                    $data['category'] = Config('global.itr_image_category.immovable_asset_image');
                    $data['image'] = $imageName;
                    if (!ITRModelImages::create($data)) {
                        return $leaseHelper->response(false, 500, 'failed to Upload image');
                    }
                }
            }
        }
        if ($request->has('outside_income')) {
            $itr1->outside_income = $request->input('outside_income') == "true" ? 1 : 0;
            if ($request->hasFile('outside_income_image')) {
                $path = Config::get('global.ITR_Image_Path.image_path');
                $path = $path . '/' . $itr1->id . '/' . Config('global.itr_image_category.outside_income_image');
                $files = $request->file('outside_income_image');
                foreach ($files as $file) {
                    $imageName = $leaseHelper->fit($file, $path);
                    if (!$imageName) {
                        return $leaseHelper->response(false, 200, 'failed to upload image');
                    }
                    $data['itr_id'] = $itr1->id;
                    $data['category'] = Config('global.itr_image_category.outside_income_image');
                    $data['image'] = $imageName;
                    if (!ITRModelImages::create($data)) {
                        return $leaseHelper->response(false, 500, 'failed to Upload image');
                    }
                }
            }
        }
        if ($request->has('assets_partner')) {
            $itr1->assets_partner = $request->input('assets_partner') == "true" ? 1 : 0;
            if ($request->hasFile('assets_partner_image')) {
                $path = Config::get('global.ITR_Image_Path.image_path');
                $path = $path . '/' . $itr1->id . '/' . Config('global.itr_image_category.assets_partner_image');
                $files = $request->file('assets_partner_image');
                foreach ($files as $file) {
                    $imageName = $leaseHelper->fit($file, $path);
                    if (!$imageName) {
                        return $leaseHelper->response(false, 200, 'failed to upload image');
                    }
                    $data['itr_id'] = $itr1->id;
                    $data['category'] = Config('global.itr_image_category.assets_partner_image');
                    $data['image'] = $imageName;
                    if (!ITRModelImages::create($data)) {
                        return $leaseHelper->response(false, 500, 'failed to Upload image');
                    }
                }
            }
        }
        if ($request->has('other_cntry_tax')) {
            $itr1->other_cntry_tax = $request->input('other_cntry_tax') == "true" ? 1 : 0;
            if ($request->hasFile('othr_cntry_tax_paid_img')) {
                $path = Config::get('global.ITR_Image_Path.image_path');
                $path = $path . '/' . $itr1->id . '/' . Config('global.itr_image_category.othr_cntry_tax_paid_img');
                $files = $request->file('othr_cntry_tax_paid_img');
                foreach ($files as $file) {
                    $imageName = $leaseHelper->fit($file, $path);
                    if (!$imageName) {
                        return $leaseHelper->response(false, 200, 'failed to upload image');
                    }
                    $data['itr_id'] = $itr1->id;
                    $data['category'] = Config('global.itr_image_category.othr_cntry_tax_paid_img');
                    $data['image'] = $imageName;
                    if (!ITRModelImages::create($data)) {
                        return $leaseHelper->response(false, 500, 'failed to Upload image');
                    }
                }
            }
        }
        if ($request->has('audit_report')) {
            $itr1->audit_report = $request->input('audit_report') == "true" ? 1 : 0;
            if ($request->hasFile('audit_report_img')) {
                $path = Config::get('global.ITR_Image_Path.image_path');
                $path = $path . '/' . $itr1->id . '/' . Config('global.itr_image_category.audit_report_img');
                $files = $request->file('audit_report_img');
                foreach ($files as $file) {
                    $imageName = $leaseHelper->fit($file, $path);
                    if (!$imageName) {
                        return $leaseHelper->response(false, 200, 'failed to upload image');
                    }
                    $data['itr_id'] = $itr1->id;
                    $data['category'] = Config('global.itr_image_category.audit_report_img');
                    $data['image'] = $imageName;
                    if (!ITRModelImages::create($data)) {
                        return $leaseHelper->response(false, 500, 'failed to Upload image');
                    }
                }
            }
        }
        try {
            $itr1->save();
            if (!$itr1->save()) {
                return LeaseHelper::response(false, 500, 'Failed to save Data');
            }
        } catch (Exception $ex) {
            return LeaseHelper::response(false, 500, $ex->getMessage());
        }
        return LeaseHelper::response(true, 200, 'ITR3 Saved Successfully', $itr1->id);
    }

    public static function itr4($request)
    {
        $user = Auth::User();
        if ($request->has('id') && $request->input('id') > 0)
            $itr1 = ITRModel::find($request['id']);
        $itr1['create_date'] = Carbon::now();
        $leaseHelper = new LeaseHelper();
        if (!$user->hasRole(Config('auth.roles.Super_User'), false))
            $itr1->user_id = Auth::User()->agents->id;
        $itr1->fill($request->all());
        if ($request->has('other_source_income')) {
            $itr1->other_source_income = $request->input('other_source_income') == "true" ? 1 : 0;
            if ($request->hasFile('othr_source_incm_image')) {
                $path = Config::get('global.ITR_Image_Path.image_path');
                $path = $path . '/' . $itr1->id . '/' . Config('global.itr_image_category.othr_source_incm_image');
                $files = $request->file('othr_source_incm_image');
                foreach ($files as $file) {
                    $imageName = $leaseHelper->fit($file, $path);
                    if (!$imageName) {
                        return $leaseHelper->response(false, 200, 'failed to upload image');
                    }
                    $data['itr_id'] = $itr1->id;
                    $data['category'] = Config('global.itr_image_category.othr_source_incm_image');
                    $data['image'] = $imageName;
                    if (!ITRModelImages::create($data)) {
                        return $leaseHelper->response(false, 500, 'failed to Upload image');
                    }
                }
            }
        }
        if ($request->has('immovable_assets')) {
            $itr1->immovable_assets = $request->input('immovable_assets') == "true" ? 1 : 0;
            if ($request->hasFile('immovable_asset_image')) {
                $path = Config::get('global.ITR_Image_Path.image_path');
                $path = $path . '/' . $itr1->id . '/' . Config('global.itr_image_category.immovable_asset_image');
                $files = $request->file('immovable_asset_image');
                foreach ($files as $file) {
                    $imageName = $leaseHelper->fit($file, $path);
                    if (!$imageName) {
                        return $leaseHelper->response(false, 200, 'failed to upload image');
                    }
                    $data['itr_id'] = $itr1->id;
                    $data['category'] = Config('global.itr_image_category.immovable_asset_image');
                    $data['image'] = $imageName;
                    if (!ITRModelImages::create($data)) {
                        return $leaseHelper->response(false, 500, 'failed to Upload image');
                    }
                }
            }
        }
        if ($request->has('assets_partner')) {
            $itr1->assets_partner = $request->input('assets_partner') == "true" ? 1 : 0;
            if ($request->hasFile('assets_partner_image')) {
                $path = Config::get('global.ITR_Image_Path.image_path');
                $path = $path . '/' . $itr1->id . '/' . Config('global.itr_image_category.assets_partner_image');
                $files = $request->file('assets_partner_image');
                foreach ($files as $file) {
                    $imageName = $leaseHelper->fit($file, $path);
                    if (!$imageName) {
                        return $leaseHelper->response(false, 200, 'failed to upload image');
                    }
                    $data['itr_id'] = $itr1->id;
                    $data['category'] = Config('global.itr_image_category.assets_partner_image');
                    $data['image'] = $imageName;
                    if (!ITRModelImages::create($data)) {
                        return $leaseHelper->response(false, 500, 'failed to Upload image');
                    }
                }
            }
        }
        try {
            $itr1->save();
            if (!$itr1->save()) {
                return LeaseHelper::response(false, 500, 'Failed to save Data');
            }
        } catch (Exception $ex) {
            return LeaseHelper::response(false, 500, $ex->getMessage());
        }
        return LeaseHelper::response(true, 200, 'ITR4 Saved Successfully', $itr1->id);
    }

    public static function ITRList($data)
    {
        $orderby = 'id';
        $order = 'desc';
        $user = Auth::User();
        try {
            $query = ITRModel::with(['ITR', 'STATUS', 'userDetails']);
            if (!$user->hasRole(Config('auth.roles.Super_User'), false))
                $query->where('user_id', $user->agents->id);

//            $itr = $query->get();

            if (array_has($data, 'orderBy') && array_has($data, 'order')) {
                $orderby = $data['orderBy'];
                $order = $data['order'];
            }
            if(!array_has($data, 'id') && array_has($data, 'status') && $data['status'] > 0){
                $query->where('status', $data['status'])->get();
            }
            $itr = $query->orderBy($orderby, $order)->get();

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
            $itr = ITRModel::with('itrImages', 'userDetails', 'profileImages')->where('id', $id)->first();
            if (!$itr)
                return LeaseHelper::response(false, 200, 'No Data Found');
        } catch (Exception $exe) {
            return LeaseHelper::response(false, 500, $exe->getMessage());
        }
        return LeaseHelper::response(true, 200, 'Data Retrieved', $itr);
    }

    public static function itrStatus($data){
        try {
            $values = $data['id'];
            if (array_has($data, 'status')) {
                foreach ($values as $id) {
                    $itr = ITRModel::where('id', $id)
                        ->update(array('status' => $data['status']));
                }
            }
        } catch (ModelNotFoundException $exe) {
            return LeaseHelper::response(false, 500, $exe->getMessage());
        }
        $itr = self::ITRList($data);
        return LeaseHelper::response(true, 200, 'Successful', $itr['data']);
    }

    public static function deleteImage($data)
    {
        if (!ITRModelImages::where('itr_id', $data['itr_id'])->exists()) {
            return LeaseHelper::response(false, 200, 'ITR ID Not Found');
        }
        $path = Config::get('global.ITR_Image_Path.image_path');
        $path = $path . '/' . $data['itr_id'];

        try{
            ITRModelImages::where('image', $data['img_name'])->delete();
        }catch (ModelNotFoundException $exe){
            return LeaseHelper::response(false, 500, $exe->getMessage());
        }

        $originalImage = $path . '/' . $data['category']. '/' .$data['img_name'];
        try {
            $status = Storage::delete([$originalImage]);
            if (!$status) {
                return LeaseHelper::response(false, 200, 'Failed to delete image');
            }
        } catch (ModelNotFoundException $exe) {
            return LeaseHelper::response(false, 500, $exe->getMessage());
        }
        return LeaseHelper::response(true, 200, 'Image has been successfully deleted');
    }
}