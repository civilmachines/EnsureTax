<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Helpers\LeaseHelper;
use App\Models\TaxItr\BasicDetails;
use App\Models\TaxItr\BasicDetailsImages;
use App\Models\TaxItr\Categories;
use App\Models\TaxItr\CorporateTax;
use App\Models\TaxItr\GSTFilesImages;
use App\Models\TaxItr\ITRModel;
use App\Models\TaxItr\ITRModelImages;
use App\Models\User\UserProfile;
use Auth;
use Config;
use Mail;
use App\Models\User\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Queue\SerializesModels;

class ITRController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function loadCategory()
    {
        $result = Categories::getCategory();
        return response($result);
    }

    public function postProfile(Request $request)
    {
        $user = Auth::User();
        $user->name = $request->input('name');
        if (!$user->save()) {
            $result = LeaseHelper::response(false, 500, 'failed to update user');
            return response($result, $result['status_code']);
        }
        $userProfile = $user->agents;
        $userProfile->name = $user->name;
        $userProfile->address = $request->input('address');
        $userProfile->ifsc_code = $request->input('ifsc_code');
        $userProfile->residential_status = $request->input('residential_status');
        $userProfile->employer_category = $request->input('employer_category');
        $leaseHelper = new LeaseHelper();
        try {
            $userProfile->save();
        } catch (Exception $exe) {
            $result = LeaseHelper::response(false, 500, $exe->getMessage());
            return response($result, $result['status_code']);
        }
        if ($request->hasFile('pan_image')) {
            $path = Config::get('global.Profile_Image_Path.image_path');
            $path = $path . '/' . $userProfile->id . '/' . Config('global.itr_image_category.pan_image');
            $files = $request->file('pan_image');
            foreach ($files as $file) {
                $imageName = $leaseHelper->fit($file, $path);
                if (!$imageName) {
                    return $leaseHelper->response(false, 200, 'Failed to upload image');
                }
                $data['agent_id'] = $userProfile->id;
                $data['image'] = $imageName;
                $data['category'] = Config('global.itr_image_category.pan_image');
                if (!BasicDetailsImages::create($data)) {
                    return $leaseHelper->response(false, 500, 'Failed to upload image');
                }
            }
        }
        if ($request->hasFile('previous_return_image')) {
            $path = Config::get('global.Profile_Image_Path.image_path');
            $path = $path . '/' . $userProfile->id . '/' . Config('global.itr_image_category.previous_return_image');
            $files = $request->file('previous_return_image');
            foreach ($files as $file) {
                $imageName = $leaseHelper->fit($file, $path);
                if (!$imageName) {
                    return $leaseHelper->response(false, 200, 'Failed to upload image');
                }
                $data['agent_id'] = $userProfile->id;
                $data['image'] = $imageName;
                $data['category'] = Config('global.itr_image_category.previous_return_image');
                if (!BasicDetailsImages::create($data)) {
                    return $leaseHelper->response(false, 500, 'Failed to upload image');
                }
            }
        }
        if ($request->hasFile('bank_statement')) {
            $path = Config::get('global.Profile_Image_Path.image_path');
            $path = $path . '/' . $userProfile->id . '/' . Config('global.itr_image_category.bank_statement');
            $files = $request->file('bank_statement');
            foreach ($files as $file) {
                $imageName = $leaseHelper->fit($file, $path);
                if (!$imageName) {
                    return $leaseHelper->response(false, 200, 'Failed to upload image');
                }
                $data['agent_id'] = $userProfile->id;
                $data['image'] = $imageName;
                $data['category'] = Config('global.itr_image_category.bank_statement');
                if (!BasicDetailsImages::create($data)) {
                    return $leaseHelper->response(false, 500, 'Failed to upload image');
                }
            }
        }
        $result = LeaseHelper::response(true, 200, "Profile updated successfully");
        return response($result, $result['status_code']);
    }

    public function getProfile()
    {
        $result = UserProfile::getUserProfile();
        return response($result);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $credentials = $request->only('new_password');
        $result = User::changePassword($credentials);
        return response()->json($result, $result['status_code']);
    }

    public function selectITR(Request $request)
    {
        if (!ITRModel::Where('user_id', Auth::User()->agents->id)->exists())
            $result = ITRModel::itr1($request);
        else
            $result = LeaseHelper::response(false, 200, 'This user has already filed an ITR');
        return response($result);
    }

    function itrStatus(Request $request)
    {
        $data = $request->all();
        $result = ITRModel::itrStatus($data);
        return response($result);
    }

    public function itr1(Request $request)
    {
        $result = ITRModel::itr1($request);
        return response($result);
    }

    public function itr2(Request $request)
    {
        $result = ITRModel::itr2($request);
        return response($result);
    }

    public function itr3(Request $request)
    {
        $result = ITRModel::itr3($request);
        return response($result);
    }

    public function itr4(Request $request)
    {
        $result = ITRModel::itr4($request);
        return response($result);
    }

    public function ITRList(Request $request)
    {
        $result = ITRModel::ITRList($request->all());
        return response($result);
    }

    public function editITR($id)
    {
        $edit = ITRModel::editITR($id);
        return response($edit);
    }

    public function deleteImage(Request $request)
    {
        if($request->has('agent_id')){
            $data = $request -> only('agent_id', 'category', 'img_name');
            $result = BasicDetailsImages::deleteImage($data);
        }else {
            $data = $request->only('itr_id', 'category', 'img_name');
            $result = ITRModel::deleteImage($data);
        }
        return response($result, $result['status_code']);
    }

    public function contactUS(Request $request)
    {
        $contactData = $request->only('name', 'email', 'contact', 'message');
        $mail = Mail::send('mailers.contact_us', ['contact' => $contactData], function ($m) {
            $m->from(env('MAIL_USERNAME'), 'Ensure Tax');
            $m->to(env('MAIL_USERNAME'))->subject('ensuretax.com- Contact Enquiry');
        });
        if ($mail) {
            return LeaseHelper::response(true, 200, 'success');
        }
        return LeaseHelper::response(false, 200, 'Failed');
        // 'token' => JWTAuth::fromUser($user)
    }

    public function corporateDetails(Request $request)
    {
        $result = CorporateTax::corporateDetails($request);
        return response($result);
    }

    public function itr5(Request $request)
    {
        $result = CorporateTax::itr5($request);
        return response($result);
    }

    public function corporateitrlist(Request $request)
    {
        $result = CorporateTax::ITRList($request->all());
        return response($result);
    }

    public function editCorporateITR($id)
    {
        $edit = CorporateTax::editITR($id);
        return response($edit);
    }

    public function delCorpImg(Request $request){
        $data = $request->only('itr_id', 'category', 'img_name');
        $result = CorporateTax::deleteImage($data);
        return response($result, $result['status_code']);
    }

    public function gstFiles(Request $request)
    {
        $result = GSTFilesImages::GSTFiles($request->all());
        return response($result);
    }

    public function uploadGST(Request $request)
    {
        $result = GSTFilesImages::uploadGST($request);
        return response($result);
    }

    public function editGSTFile($id)
    {
        $edit = GSTFilesImages::editGSTFiles($id);
        return response($edit);
    }

    public function delGSTImg(Request $request){
        $data = $request->only('agent_id', 'category', 'img_name');
        $result = GSTFilesImages::deleteImage($data);
        return response($result, $result['status_code']);
    }
    function corpItrStatus(Request $request)
    {
        $data = $request->all();
        $result = CorporateTax::itrStatus($data);
        return response($result);
    }
    function gstStatus(Request $request)
    {
        $data = $request->all();
        $result = GSTFilesImages::gstStatus($data);
        return response($result);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

