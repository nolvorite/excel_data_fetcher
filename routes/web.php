<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB as DB;

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

Route::get('/', 'DefaultController@index');

Route::get('/upload', 'DefaultController@upload');

Route::get('/spreadsheets/', function (Request $request) {

	$getLast = DB::table('uploaded_files')->orderBy('id','DESC')->get();

	$id = $getLast[0]->id;

    return redirect('/spreadsheets/'.$id);
});

Route::get('/spreadsheets/{fileId}', 'DefaultController@spreadSheetPage');

Route::post('/create/file', 'ActionController@createNew');

Route::post('/edit_header_row', 'ActionController@editHeaderRow');

Route::get('/file/view/{fileId}', 'DefaultController@excelSheetViewer');
