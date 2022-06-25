$(document).ready(function(){    
    CKEDITOR.replace('answer');
});

function encryptValue(){
	if(CKEDITOR.instances["answer"])
		CKEDITOR.instances["answer"].destroy();
	$("#answer").val(CryptoJS.AES.encrypt(JSON.stringify($("#answer").val()), $("#passsalt").val(), {format: CryptoJSAesJson}).toString());
	$("#passsalt").remove();
	return true;
}