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

//all routes / api here must be api authenticated
        // Route::post('profile',function(){return 'Only authenticated user can reach me';});
        // Route::post('all-tweets','TweetController@index') ;//3//return all the tweets of the followed users paginated
        // Route::post('tweet/create','TweetController@store') ;//4//create new tweet
        // Route::post('tweet/destroy','TweetController@destroy') ;//5//delete a tweet
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
        // Route::post('profile',function(){return 'Only authenticated user can reach me';});
        // Route::post('all-tweets','TweetController@index') ;//3//return all the tweets of the followed users paginated
        // Route::post('tweet/store','TweetController@store') ;//4//store new tweet
        // Route::post('tweet/destroy','TweetController@destroy') ;//5//delete a tweet
// Route::get('test',function (){
//      //convert token user
//     return JWTAuth::toUser('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC92MVwvdXNlclwvbG9naW4iLCJpYXQiOjE2MTQwMTUzMTYsImV4cCI6MTYxNDAxODkxNiwibmJmIjoxNjE0MDE1MzE2LCJqdGkiOiJOYzk0TTluQzlTMEwxZlA2Iiwic3ViIjoxLCJwcnYiOiI4N2UwYWYxZWY5ZmQxNTgxMmZkZWM5NzE1M2ExNGUwYjA0NzU0NmFhIn0.MdVWZuDECjXoS7vznf-GWNjOhHmX2GBKHb0s0fj_BAo');
// });
// Route::group(['middleware' => ['api','checkPassword','changeLanguage','checkAdminToken:admin-api'], 'namespace' => 'Api'], function () {
//     Route::get('offers', 'CategoriesController@index');
// });
