<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Follower;
use App\User;
use App\Traits\GeneralTrait;
use Validator;
class FollowController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    use GeneralTrait;

    public function followUser(Request $request)
    {
        try{
        //store follower
        $rules = [
            "user_id" => "required|numeric|exists:App\User,id",
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
        if (Follower::where('user_id', '=', $request->user_id)->where('follower_id', '=', auth('user-api')->user()->id)->exists()) {
            return $this->returnError('703',trans('admin.user followed already'));
         }elseif((int)$request->user_id === auth('user-api')->user()->id){

            return $this->returnError('708',trans('admin.you cant follow yourself'));
         }else{
            $follower= new Follower;
            $follower->user_id = $request->user_id;
            $follower->follower_id =auth('user-api')->user()->id;
            $follower->save();
         }
        //return response
        return $this->returnSuccessMessage(trans('admin.user followed successfully'));
        } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
        }

    }

    public function index()
    {
        //
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
