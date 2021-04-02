function fetchData(method, url, data, dataType = 'json', whatToDo = "",inCaseOfFailure = ""){
	    switch(method){
	        case "asObject":
	            var jsonToGet = [];
	            jQuery.ajax(siteDir + url, {
	                method: method,
	                headers: {
	                    'Access-Control-Allow-Headers': 'Content-Type, Accept, X-Requested-With, Session',
	                    'Access-Control-Allow-Methods': 'GET, POST, DELETE, PUT, OPTIONS, HEAD',
	                    'Content-Type':'application/json',
	                    'cache-control': 'no-cache' 
	                },
	                data: data,
	                async: false,
	                dataType: (method === 'get') ? 'json' : dataType
	            }).done(function(data){
	                jsonToGet = data;
	            }).fail(function(error){
	            	if(typeof inCaseOfFailure === "function"){
	            		inCaseOfFailure(error);
	            	}
	            	console.log("Error: " + error);
	            });
	            return jsonToGet;
	        break;
	        default:
	            jQuery(function($){ //all get requests are JSON here

	            	dataFinal = {
	                    method: method,
	                    data: data,
	                    dataType: (method === 'get' && dataType === "json") ? 'json' : dataType
	                };

	                var propertiesToCheckForFiles = ['img','files_compiled'];

	                for(i in propertiesToCheckForFiles){
	                	prop = propertiesToCheckForFiles[i];
	                	if(typeof data[prop] !== "undefined" && data[prop] instanceof FormData){
			            	for(let [key, value] of Object.entries(data)) {
							    if(key !== prop){
							    	data[prop].append(key,value);
							    }
							}
							for (let [key,value] of data[prop].entries()) {
							   console.log(key,value); 
							}
							dataFinal.contentType = false;
	    					dataFinal.processData = false;
	    					dataFinal.data = data[prop];
						}
	                }

	            		            

					urlFinal = /http(s)?\:\/\//.test(url) ? url : siteDir+url;    

	                $.ajax(urlFinal, dataFinal).done(function(returned){
	                    if(typeof whatToDo === "function"){
	                    	try {
		                        whatToDo(returned);
		                    } catch(error){
		                    	console.log(error);
		                    }
	                    }else{
	                        console.log(returned);
	                    }
	                }).fail(function(error){
		            	if(typeof inCaseOfFailure === "function"){
		            		inCaseOfFailure(error);
		            	}
		            	console.log("Error: " , error);
		            });
	            });
	        break;
	    }
	}	

	

	function checkUrl(url,functionToExecute = function(){},functionToExecuteWhenFailed = function(){},type = 'image'){
	
		switch(type){
			case "image":
				link = 'dynamic/urlCheck/image';
			break;
			case "embeddable":
				link = 'dynamic/urlCheck/embeddable';
			break;
		}

		fetchData('POST',link,{url: url},'html',function(results){
				hasNoJSONErrors = false;
				try {

					results = $.parseJSON(results);					
					hasNoJSONErrors = true;

				}catch (error){
					functionToExecuteWhenFailed(error);
					
				}
				

				if(typeof functionToExecute === "function" && hasNoJSONErrors){
					switch(type){
						case "image":
							functionToExecute(results);
						break;
					}
					

					hasASameOriginPolicy = (typeof results.headers.XFrameOptions === "string" && /sameorigin|deny/i.test(results.headers.XFrameOptions));

					if(type === "embeddable" && !hasASameOriginPolicy){	

						//then check to see if you can access it with a normal iframe for any more scenarios of failure

						$("<iframe src='"+url+"' id='iframetest'>").appendTo("body");

						$("#iframetest")[0].onload = function(event){
							console.log(event);
							functionToExecute(results);
							if(/text\/html/.test(results.headers.ContentType)){
								$("#test_results").append("<p><strong>Page Title:</strong> "+title+" <button class='click-opt btn btn-sm btn-primary' action='use-as-title'>Use as Title</button></p>");
							}
							$("#test_results").append("<p><strong><u>Link is valid for use, and embeddable in an IFrame.</u></strong></p>");
							$(".url_checker").removeAttr('disabled');
							suspendUrlChecker = false;
							$("#iframetest").detach();
							fetchData('GET','dynamic/approveLink',{link: url},"html",function(results){});
						}

						$("#iframetest")[0].onerror = function(error){
							functionToExecuteWhenFailed(error);
							$("#iframetest").detach();
						}

					}else{

						if(hasASameOriginPolicy){
							
							error = {message: "This webpage has a SAMEORIGIN policy, and cannot be embedded."};
							functionToExecuteWhenFailed(error);

						}
					}

				}

			},
			function(error){ console.log(error);
				if(typeof functionToExecuteWhenFailed === "function"){
					functionToExecuteWhenFailed(error);
				}
			}
		);
	};

	function embed(type, ID){
		embedHTML = {
			youtube: '<iframe class="video" src="https://www.youtube.com/embed/'+escape(ID)+'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
			streamable: '<iframe class="video" src="https://streamable.com/e/'+escape(ID)+'" frameborder="0" allowfullscreen></iframe>',
			vimeo: '<iframe class="video" src="https://player.vimeo.com/video/'+escape(ID)+'" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>'
		};
		aspectRatio = {
			youtube: [560,315],
			streamable: [560, 315],
			vimeo: [560,315]

		};
		url = {
			youtube: 'https://www.youtube.com/embed/',
			streamable: 'https://streamable.com/e/',
			vimeo: 'https://player.vimeo.com/video/'
		};
		return {type: type, html: embedHTML[type], aspectRatio: aspectRatio[type], id: ID, url: url[type]+escape(ID)};
	};

	function videoParser(url){		

		parsedURL = URLParserForVideoLinks(url);

		return embed(parsedURL.type,parsedURL.id);

	}


	function URLParserForVideoLinks(value){ 
		idPatterns = {
			youtube: /(youtu\.be\/|youtube\.com\/watch\?v\=)([A-Za-z0-9\-\_]+)[^A-Za-z0-9\-\_]{0,}/,
			streamable: /(streamable\.com\/)([A-Za-z0-9\-\_]+)[^A-Za-z0-9\-\_]{0,}/,
			vimeo: /(vimeo\.com\/)([A-Za-z0-9]+)[^A-Za-z0-9\-\_]{0,}/,
		};

		type = "";

		for(property in idPatterns){
			if(idPatterns[property].exec(value) !== null){
				type = property;
			}
		}

		return type !== "" ? {type: type, id: idPatterns[type].exec(value)[2]} : {};
	};



	function date(){
		var datee = new Date();
		return datee;
	}

	String.prototype.escape = function() {
	    var tagsToReplace = {
	        '&': '&amp;',
	        '<': '&lt;',
	        '>': '&gt;'
	    };
	    return this.replace(/[&<>]/g, function(tag) {
	        return tagsToReplace[tag] || tag;
	    });
	};

	colorCheck = function(type,hex){
		hex = "#"+hex;
		switch(type){
			case "hextorgb":
				var hex = parseInt(((hex.indexOf('#') > -1) ? hex.substring(1) : hex), 16);
				return {r: hex >> 16, g: (hex & 0x00FF00) >> 8, b: (hex & 0x0000FF)};
			break;
		}
	}

	adjustBoxPreview = function(properties){
		currentProperties = {
			pct: $("#objprops_modal .percent_viewer").text(),
			bg: $("#objprops_modal [name=bg_color]").val(),
			fg: $("#objprops_modal [name=text_color]").val(),
		};
		cssProperties = {

		};
		if(Object(properties).hasOwnProperty("bg")){
			if(Object(properties).hasOwnProperty("pct")){
				cssProperties.background = bgChange(properties.pct,currentProperties.bg);
			}else{
				cssProperties.background = bgChange(currentProperties.pct,properties.bg);
			}
		}else{
			if(Object(properties).hasOwnProperty("pct")){
				cssProperties.background = bgChange(properties.pct,currentProperties.bg);
			}
		}

		if(Object(properties).hasOwnProperty("fg")){
			cssProperties.color = "#"+currentProperties.fg;
		}

		$("#box_text_preview").css(cssProperties);
	

	}

	// adjustPreview = function(properties = {}){
	// 	if(Object(properties).hasOwnProperty("pct")){
	// 		$("#box_text_preview").css("background-color",bgChange(this,hex));
	// 		$(this).parents(".subsection").find("input[name=bg_pct]").val(pct);
	// 	}
	// 	$("input[name=bg_opacity]").removeAttr("disabled").val(ui.value+"%");
	// 	$(this).next(".percent_viewer").text(pct);
	// 	hex = $(this).parents(".subsection").find("input[name=bg_color]").val();
	// 	$(this).find(".pctbar").css("width",pct);		
	// }

	bgChange = function(pct,color){
		rgb = colorCheck("hextorgb",color);

		opacityPct = pct;
		if(opacityPct !== "100%" || opacityPct !== ""){
			rgba = opacityPct.replace("[^0-9\%]","") !== "" ? opacityPct.replace("[^0-9\%]","") : "1";
			color = "rgba("+rgb.r+","+rgb.g+","+rgb.b+","+rgba+")";

		}else{
			color = "#"+color;
		}
		return color;
	}

	function compileToDoListHTML(dt,objectName,displayType = "default"){
		listHTML = "";
		for(i in dt.data){
			dt2 = dt.data[i];
			dt2.displayType = displayType;
			listHTML += toDoComponents.fullItem(dt2);
		}
		return rawToDo(dt,listHTML,objectName,displayType);
	}

	function isInPanelPage(){
		return typeof isPanelPage !== "undefined";
	}

	String.prototype.clearSlashes = function(){
		return this.replace('\\\'','\'').replace('\\"','\"');
	}

	String.prototype.addSlashes = function(){
		return this.replace("\"","\\\"");
	}

	function pastePlaintextAtCaret(text){
		 if (document.queryCommandSupported('insertText')) {
	        document.execCommand('insertText', false, text);
	    } else {
	        // Insert text at the current position of caret
	        range = document.getSelection().getRangeAt(0);
	        range.deleteContents();

	        textNode = document.createTextNode(text);
	        range.insertNode(textNode);
	        range.selectNodeContents(textNode);
	        range.collapse(false);

	        selection = window.getSelection();
	        selection.removeAllRanges();
	        selection.addRange(range);
	    }
	}

	function pasteHtmlAtCaret(html) {
	    var sel, range;
	    if (window.getSelection) {
	        // IE9 and non-IE
	        sel = window.getSelection();
	        if (sel.getRangeAt && sel.rangeCount) {
	            range = sel.getRangeAt(0);
	            range.deleteContents();

	            // Range.createContextualFragment() would be useful here but is
	            // non-standard and not supported in all browsers (IE9, for one)
	            var el = document.createElement("div");
	            el.innerHTML = html;
	            var frag = document.createDocumentFragment(), node, lastNode;
	            while ( (node = el.firstChild) ) {
	                lastNode = frag.appendChild(node);
	            }
	            range.insertNode(frag);
	            
	            // Preserve the selection
	            if (lastNode) {
	                range = range.cloneRange();
	                range.setStartAfter(lastNode);
	                range.collapse(true);
	                sel.removeAllRanges();
	                sel.addRange(range);
	            }

	            window.pastePhase = "pasted";

	        }
	    } else if (document.selection && document.selection.type != "Control") {
	        // IE < 9
	        document.selection.createRange().pasteHTML(html);
	        window.pastePhase = "pasted";
	    }
	}

	function isInATouchDevice(){
		return /iPhone|iPad|iPod|Android/i.test(navigator.userAgent); 
	}

	function isImageOk(img) {
	    // During the onload event, IE correctly identifies any images that
	    // weren’t downloaded as not complete. Others should too. Gecko-based
	    // browsers act like NS4 in that they report this incorrectly.
	    if (!img.complete) {
	        return false;
	    }

	    // However, they do have two very useful properties: naturalWidth and
	    // naturalHeight. These give the true size of the image. If it failed
	    // to load, either of these should be zero.
	    if (img.naturalWidth === 0) {
	        return false;
	    }

	    // No other way of checking: assume it’s ok.
	    return true;
	}

	function edgeAdjustmentChecker(csid){
		var object = $(".cshape[csid='"+csid+"']");
		var width = object.width();
		var height = object.height();
		var xPos = parseInt(object.css("left").replace("px",""));
		var yPos = parseInt(object.css("top").replace("px",""));
		var bounds = {x: (xPos + object.outerWidth()), y: (yPos + object.outerHeight())};
		var currentCanvasSize = {
			width: parseInt(currentCanvasData.canvas_width),
			height: parseInt(currentCanvasData.canvas_height)
		};	

		var maxBounds = {
			width: currentCanvasSize.width - canvasBuffer,
			height: currentCanvasSize.height - canvasBuffer
		};

		var inBufferZone = {
			horizontal: bounds.x >= maxBounds.width,
			vertical: bounds.y >= maxBounds.height
		};	

		return inBufferZone.horizontal || inBufferZone.vertical;

	}