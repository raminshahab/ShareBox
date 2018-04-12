<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

//Route::get('/login', 'HomeController@index')->name('home')->middleware('auth');

Route::get('/files/{type}/{id?}', 'FileController@retrieve');

Route::post('files/add', 'FileController@store');
Route::post('files/edit/{id}', 'FileController@edit');
Route::post('files/delete/{id}', 'FileController@destroy');


Route::get('/upload_form', function()
{
    $data['files'] = Attachment::get();
    return View::make('uploads.form', $data);
});

Route::post('/upload_file', [
    'uses'      => 'UploadController@store',
    'as'        => 'upload.store'
]);

Route::get('/retrieve', [
    'uses'      => 'UploadController@retrieve',
    'as'        => 'upload.retrieve'
]);


Route::post('/remove', [
    'uses'      => 'UploadController@remove',
    'as'        => 'upload.remove'
]);



/*
Route::post('/upload_file', function()
{
    $rules = array(
        'file' => 'required|mimes:doc,docx,pdf',
        );

    $validator = Validator::make(Input::all(), $rules);

    if($validator->fails())
    {
        return Redirect::to('/upload_form')->withErrors($validator);
    }
    else
    {
        if(Input::hasFile('file'))
        {
      	    file_get_contents(Input::file('file')->getRealPath());
                DB::table('files')->insert(array(
                    'files' => $data
                )
      	    );
        }
    }
});
*/