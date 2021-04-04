$(document).ready(function(){
	
	actionCheck = false;


	$("body").on("click","#submit_btn",function(event){
		event.preventDefault();
		if(actionCheck){
			return false;
		}

		formData = new FormData($("#form_selector")[0]);

		dataToSend = {files_compiled: formData};

		fetchData("POST","create/file",dataToSend,"json",function(results){
			if(results.status){
				alert("Successfully created excel file!");
				window.location.assign(siteDir+"spreadsheets/"+results.data.spreadsheet_id);
			}else{
				alert("Error:" + result.error);
			}
			actionCheck = false;
		},function(error){
			alert("Error: " + error.responseJSON.message);
			actionCheck = false;
		});

		actionCheck = true;

	});

	$("#spreadsheet_view_iframe").on("load",function(){
		$("#loading_notice").detach();
	})

	$("body").on("change",".select-header-row",function(event){

		data = {
			file_id: $(this).attr("file"),
			sheet: $(this).attr("sheet"),
			row: $(this).val()
		};

		fetchData("POST","edit_header_row",data,"json",function(results){
			if(results.status){
				window.location.reload();
			}else{
				//alert("Failed to change header row.");
			}
		});


	});


});