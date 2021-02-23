<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\User;
use Validator;
use Auth;

class AuthController extends Controller
{

    use GeneralTrait;

    public function login(Request $request)
    {
        try {
            $rules = [
                "email" => "required|string|email",
                "password" => "required|string|min:8|max:190"
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            //login
            $credentials = $request->only(['email', 'password']);
            $token = Auth::guard('user-api')->attempt($credentials);  //generate token
            if (!$token)
                return $this->returnError('E001', trans('admin.credentials are not correct'));
            $user = Auth::guard('user-api')->user();
            $user ->api_token = $token;
            //return token
            return $this->returnData('user', $user,trans('admin.user data retrieved successfully'));  //return json response
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function register(Request $request)
    {
        try {
            $rules = [
                "name_ar" => "required|string|max:190",
                "name_en" => "required|string|max:190",
                "password" => "required|string|min:8|max:190",//confirmed
                "email" => "required|string|email|unique:users,email|max:190",
                "profile_image" => "required",//|image|mimes:jpeg,png,jpg,gif,svg|max:2048
                "language" => "",
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            //register
            $data = $request->all();
            $data['password'] = Hash::make($request->password);
            if ($request->file('profile_image')) {
                $imageName = time().'.'.$request->profile_image->extension();  
                $request->profile_image->move(public_path('images'), $imageName);
                $data['profile_image'] = 'images/'.$imageName;
            }else{
                $data['profile_image'] = 'images/User-icon.png';
            }

            $user = User::create($data);
            return $this->returnData('user', $user,trans('admin.user created successfully'));  //return json response

        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }


    // public function logout(Request $request)
    // {
    //      $token = $request -> header('auth-token');
    //     if($token){
    //         try {

    //             JWTAuth::setToken($token)->invalidate(); //logout
    //         }catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e){
    //             return  $this -> returnError('','some thing went wrongs');
    //         }
    //         return $this->returnSuccessMessage('Logged out successfully');
    //     }else{
    //         $this -> returnError('','some thing went wrongs');
    //     }

    // }
}
