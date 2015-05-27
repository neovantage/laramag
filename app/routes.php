<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the Closure to execute when that URI is requested.
  |
 */

Route::get ( '/', function() {
    return View::make ( 'hello' );
} );

$apiSettings = [
    'version' => 'v1',
    'prefix' => 'api',
    'protected' => false
];

//Route::group(array('before' => 'auth.basic'), function() {});
Route::api($apiSettings, function() {
    Route::resource ( 'post', 'PostController' );
    Route::resource ( 'comment', 'CommentController' );
    Route::get ( 'post/comments/{id}', 'CommentController@commentsByPost' );
});