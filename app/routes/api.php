<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::put('files/edit/{id}', function(Request $request, $id) {

    $file = File::where('id', $id)->where('user_id', Auth::id())->first();
    if ($file->name == $request['name']) {
        return response()->json(false);
    }
  

    return response()->json($file->save());
    
});
