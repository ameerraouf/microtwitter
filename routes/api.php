<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Tymon\JWTAuth\Facades\JWTAuth;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['middleware' => ['api','checkPassword','changeLanguage'], 'namespace' => 'Api'], function () {
    Route::group(['prefix' => 'user','namespace'=>'User'],function (){
        Route::post('login','AuthController@Login') ;//1//login
        Route::post('register','AuthController@register') ;//2//register
    });
    Route::group(['prefix' => 'user','namespace'=>'User','middleware' => 'auth.guard:user-api'],function (){
        Route::post('all-followed-tweets','TweetController@followedTweets') ;//3//return all the tweets of the followed users paginated
        Route::post('my-tweets','TweetController@index') ;//4//my-tweets
        Route::post('tweet/store','TweetController@store') ;//5//store new tweet
        Route::post('tweet/destroy','TweetController@destroy') ;//6//delete a tweet
        Route::post('followUser','FollowController@followUser') ;//7//User follow another user
        // Route::resource('tweets','TweetController') ;
    });
});
