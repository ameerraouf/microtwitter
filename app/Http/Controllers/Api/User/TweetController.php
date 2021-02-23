<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Follower;
use App\Models\Tweet;
use App\User;
use App\Traits\GeneralTrait;
use Validator;
use Illuminate\Support\Facades\DB;
class TweetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
            // $my_tweets=Tweet::orderBy('created_at','desc')->where('user_id',auth('user-api')->user()->id)
            // ->paginate(1);
            //return response
    use GeneralTrait;

    public function index() //userTweets
    {
        try{
            
           
            $my_tweets = DB::table('tweets')
            ->join('users', 'users.id', '=', 'tweets.user_id')
            ->select('tweets.*','name_' . app()->getLocale() . ' as name')
            ->where('tweets.user_id', auth('user-api')->user()->id)
            ->orderBy('tweets.created_at','desc')
            ->paginate(2);
            return $this->returnData('my_tweets', $my_tweets,trans('admin.tweets retrieved successfully'));
            } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
            }
    }

    public function followedTweets()
    {
        try{
            //retrive  tweets of the follwed users to the follower in his timeline paginated
            $followed_users=Follower::where('follower_id',auth('user-api')->user()->id)->pluck('user_id');
            // $tweets=Tweet::orderBy('created_at','desc')->whereIn('user_id', $followed_users)->paginate(1);
            $tweets = DB::table('tweets')
            ->join('users', 'users.id', '=', 'tweets.user_id')
            ->whereIn('tweets.user_id', $followed_users)
            ->select('tweets.*','name_' . app()->getLocale() . ' as name')
            ->orderBy('tweets.created_at','desc')
            ->paginate(2);
            //return response
            return $this->returnData('tweets', $tweets,trans('admin.tweets retrieved successfully'));
            } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
            }
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
        try{
            //store tweet
            $rules = [
                "content" => "required|string|max:140",
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $tweet= $request->all();
            $tweet['user_id']= auth('user-api')->user()->id;
            Tweet::create($tweet);
            //return response
            return $this->returnSuccessMessage(trans('admin.tweet created successfully'));
            } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
            }
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
    public function destroy(Request $request)
    {
        try{
              //store tweet
              $rules = [
                "tweet_id" => "required|numeric|exists:App\Models\Tweet,id",
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $tweet=Tweet::find($request->tweet_id);
            $tweet->delete();
            //return response
            return $this->returnSuccessMessage(trans('admin.tweet deleted successfully'));
            } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
            }
    }
}
