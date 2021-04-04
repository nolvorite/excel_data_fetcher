<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as DB;

class ActionController extends Controller
{

	public function editHeaderRow(Request $request){
		$data = $request->all();
		$result = ['status' => false, 'message' => '', 'data' => []];

		$fileData = DB::table('uploaded_files')->where("id",$data['file_id'])->get()[0];

		$miscData = (array) json_decode($fileData->misc_properties);

		$miscData['sheet'.$data['sheet']] = $data['row'];

		DB::table('uploaded_files')->update(['misc_properties' => json_encode($miscData)]);

		$result['status'] = true;

		return response()->json($result);

	}

	public function createNew(Request $request){

		$result = ['status' => false, 'message' => '', 'data' => []];

		$action = $request->segment(2);
		switch($action){
			case "file":
				$sent = $request->all();

				if(!preg_match("#sheet|spreadsheet#",$sent['files_compiled']->getMimeType())){
					$result['message'] = "Invalid data file. Must be a spreadsheet.";

				}else{
					$fileExtension = preg_replace("#^([^\.]+)#","",$sent['files_compiled']->getClientOriginalName());

					$hash = md5(microtime(true));

					$newFileName = $hash.$fileExtension;

					

					$result['status'] = true;

					$insertData = DB::table('uploaded_files')->insertGetId([
						'user_id' => '-1',
						'file_name' => $newFileName,
						'path' => storage_path(),
						'has_headers' => $sent['has_header'],
						'original_name' => $sent['files_compiled']->getClientOriginalName(),
						'mime' => $sent['files_compiled']->getMimeType()
					]);

					$sent['files_compiled']->move('storage/app/public/',$newFileName);

					$result['data'] = ['spreadsheet_id' => $insertData];
				}

			break;
			default:
				$result['message'] = "Action not found.";
			break;
		}

		return response()->json($result);

	}
}
