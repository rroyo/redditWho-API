<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['prefix' => 'api/v1'], function()
{
    /**
     * Displays subreddits, ordered by the order_by criteria
     */
    Route::get('subreddits/{order_by?}/{order?}', 'SubredditController@index');

    /**
     * Displays the specified subreddit.
     */
    Route::get('subreddit/{id}', 'SubredditController@getSubreddit');

    /**
     * Displays all the publications of a subreddit, ordered by the order_by criteria
     */
    Route::get('posts/{idint}/{order_by?}/{order?}', 'PostController@index');

    /*
     * API Documentation
     */
    Route::get('docs', function(){
        return View::make('docs.api.v1.index');
    });

});
