<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as DB;

require("vendor/nuovo/spreadsheet-reader/SpreadsheetReader.php");

class DefaultController extends Controller
{

	public function index(Request $request) {
		$data = ['request' => $request, 'mode' => 'index'];
	    return view('index',$data);
	}

	public function upload(Request $request) {
		$data = ['request' => $request, 'mode' => 'upload'];
	    return view('upload',$data);
	}

	public function spreadSheetPage(Request $request, $fileId) {	

		$getFile = DB::table('uploaded_files')->where('id', $fileId)->get();

		if(count($getFile) === 0){
			return redirect('/');
		}

		$spreadSheetList = DB::table('uploaded_files')->where('mime','like','%sheet%')->orWhere("mime",'like','%Sheet%')->get();

		$spreadSheetData = [];

		$data = ['request' => $request, 'ssId' => $fileId, 'mode' => 'spreadsheets', 'ssList' => $spreadSheetList, 'ssData' => $spreadSheetData];

	    return view('spreadsheets',$data);

	}

	public function excelSheetViewer(Request $request,$fileId){

		$file = DB::table('uploaded_files')->where("id",$fileId)->get();

		if(count($file) > 0){

			$file = $file[0];

			$excelFetch = $file->path . "\\app\\public\\" . $file->file_name;

			$Reader = new \SpreadsheetReader($excelFetch);

			$sheets = $Reader -> Sheets();
			$sheetsCompilation = [];
			foreach($sheets as $index => $name){

				$Reader->ChangeSheet($index);

				
				$data = [];
				foreach($Reader as $row){
					$data[] = $row;
				}

				$counter = count($data);

				$file->has_headers = (bool) $file->has_headers;

				$headerRowByDefault = 0;

				$headerRowIndex = $headerRowByDefault;

				if($file->has_headers){
					$miscData = (array) json_decode($file->misc_properties);
					//first row will always be the header unless declared otherwise
					if(isset($miscData['sheet'.$index])){
						
						//check if data exists at all
						if(isset($data[$headerRowIndex])){
							$headerRowIndex = intval($miscData['sheet'.$index]);
						}
						
					}
					$headerRow = $data[$headerRowIndex];
				}

				$sheetsCompilation[] = [
					'meta' => [
						'name' => $name, 
						'sheet_index' => $index, 
						'num_rows' => $counter, 
						'header_row_index' => $headerRowIndex, 
						'header_row' => $headerRow,
					],
					'data' => $data
				];

			}

			$dataForView = ['mode' => 'iframe','request' => $request, 'fileData' => $file, 'sheets' => $sheetsCompilation];

			return view('iframe_body_for_spreadsheets',$dataForView);

		}else{
			echo "File Not Found.";
			exit;
		}

	}

}
