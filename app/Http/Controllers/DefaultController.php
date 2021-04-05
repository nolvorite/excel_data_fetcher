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

		if(!\Auth::user()){
			exit;
		}

		$data = ['request' => $request, 'mode' => 'upload'];
	    return view('upload',$data);
	}

	public function spreadSheetPage(Request $request, $fileId) {	

		if(!\Auth::user()){
			exit;
		}

		$getFile = DB::table('uploaded_files')->where(['id' => $fileId, 'user_id' => \Auth()->id()])->get();

		if(count($getFile) === 0){
			return redirect('/');
		}

		$spreadSheetList = DB::table('uploaded_files')->where(['user_id' => \Auth()->id()])->where('mime','like','%sheet%')->get();

		$spreadSheetData = [];



		$data = ['request' => $request, 'ssId' => $fileId, 'mode' => 'spreadsheets', 'ssList' => $spreadSheetList, 'ssData' => $spreadSheetData];

	    return view('spreadsheets',$data);

	}

	public function excelSheetViewer(Request $request,$fileId){

		if(!\Auth::user()){
			exit;
		}

		$file = DB::table('uploaded_files')->where("id",$fileId)->get();

		if(count($file) > 0){

			$file = $file[0];

			$excelFetch = $file->path . "\\app\\public\\" . $file->file_name;

			$Reader = new \SpreadsheetReader($excelFetch);

			//upload data into the database

			//first, check to see if such a data exists

			$checkForData = DB::table('excel_data')->where(['file_id'=>$fileId])->get();

			$dataHasBeenAdded = (count($checkForData) > 0);

			$sheets = $Reader -> Sheets();
			$sheetsCompilation = [];
			foreach($sheets as $index => $name){

				$Reader->ChangeSheet($index);

				
				$data = [];

				$dataCompiledForInsertion = [];

				foreach($Reader as $rowIndex => $row){

					$data[] = $row;

					foreach($row as $colIndex => $col){

						if(!$dataHasBeenAdded){

							$dataCompiledForInsertion[] = [
								'file_id' => $fileId,
								'value' => $col,
								'col' => $colIndex,
								'row' => $rowIndex,
								'sheet_index' => $index
							];

						}

					}

				}

				DB::table('excel_data')->insert($dataCompiledForInsertion);

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
