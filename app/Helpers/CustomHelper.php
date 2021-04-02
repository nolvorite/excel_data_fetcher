<?php

if(!function_exists('asset2')){

	function asset2($pathToFile){
		return env('APP_URL')."public/".$pathToFile;
	}

}

if(!function_exists('external')){

	function externals($path){
		return asset2('external/').$path;
	}	

}

if(!function_exists('link2')){

	function link2($path){
		return env('APP_URL').$path;
	}	

}





?>