$(document).ready(function(){
	var options = {};
	var elemTabs1 = new Foundation.Tabs($("#geetSSLTabPanel"), options);
	var options = {active_class: 'active', multi_expand: false};
	var options = {};
	var elemModal = new Foundation.Reveal($("#ecSearchModal"), options);
	var elemTabsEql1 = new Foundation.Equalizer($("#tabContainer"), options);
	var elemTabsEql2 = new Foundation.Equalizer($("#introRow"), options);
	var elemTabsEql3 = new Foundation.Equalizer($("#RRRElem"), options);

	if($("#captchaPuzzle").length){
		var url = $("#captchaPuzzle").attr("src").split("?");
		$("#captchaPuzzle").attr("src", url[0]);

		$("#username,#upassword").on('paste', function(e){
			e.preventDefault();
		});

		$("#username,#upassword").on("contextmenu",function(){
    	   return false;
    	}); 
	}

	$('.counter-up-fast').counterUp({
        delay: 10,
        time: 1000
    });

    $("#loginRow").css('height',$(".main-wrapper").height()+26);

});

function encryptValue(){
	$("#upassword").val(CryptoJS.AES.encrypt(JSON.stringify($("#upassword").val()), $("#passsalt").val(), {format: CryptoJSAesJson}).toString());
	$("#passsalt").remove();
	return true;
}


function submitAttributes(params){
	//alert(params);
	
	var paramList={};
	for(var i=0; i < params.elements.length; i++){
		var e = params.elements[i];
		if(e.id && e.value){
			if(e.type == 'radio'){
				if(e.checked){
					paramList[e.id] = e.value;
				}
			} else{
				paramList[e.id] = e.value;
			}
		}
	}
	console.log(paramList);
	var jsonData = JSON.stringify(paramList);
	
	var tempForm = document.createElement("form");
	tempForm.target = "_self";
	tempForm.method = "POST";
	tempForm.action = "/Scheme/getSchemeSearch";

	// Create an input for type
	var type = document.createElement("input");
	type.type = "text";
	type.name = "type";
	type.value = "gen";
	tempForm.appendChild(type);

	// Create an input to check probing 
	var probing = document.createElement("input");
	probing.type = "text";
	probing.name = "probing";
	probing.value = "false";
	tempForm.appendChild(probing);
	document.body.appendChild(tempForm);

	// Create textarea for params 
	var tempParams = document.createElement("textarea");
	tempParams.name = "params";
	tempParams.value = jsonData;
	tempForm.appendChild(tempParams);
	document.body.appendChild(tempForm);
	
	tempForm.submit();
	return false;
}

function validateEC(){
	if($("#ec_id").val().length === 12){
		if($("#ec_id").val() % 1 === 0){
			return true;
		} else{
			$("#searchByEC_error").show();
			return false;
		}
	} else{
		$("#searchByEC_error").show();
		return false;
	}
}	

function resetCaptcha(){
	$.ajax({
		url: "/Utils/refreshCaptcha",
		type: 'get',
		success: function(response){
			var data = JSON.parse(response);
			$("#captchaBox span img").attr('src', data['text']);
			$("#loginCaptcha").val('');
		},
		error: function(e){
			$("#captchaBox").append('<div class="callout alert">Error: there was an error refreshing captcha. Kindly contact site administrator.</div>');				
		}
	});
}
