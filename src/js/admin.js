$(document).ready(function(){
	var options = {};
	if($("#adminMenuBarIcon").length){
		var elem1adminMenuBarIcon = new Foundation.Tooltip($("#adminMenuBarIcon"), options);
	}
});

function validateIDCards(acceptedVal, val){
	if(acceptedVal == "Only numbers"){
		if(val % 1 === 0){
			return true;
		} else{
			return false;
		}
	}else if(acceptedVal == "Only text"){
		return val.matches("[a-zA-Z]+"); 
	} else if(acceptedVal == "Text with numbers"){
		return val.matches("[a-zA-Z0-9]*");
	}else{
		return true;
	}
}