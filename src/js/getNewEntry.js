var HHFormSaved = hhVerified = 0;
var elem4;
$(document).ready(function(){
	var options = {};
	var elem = new Foundation.Tabs($("#newEntryTabs"), options);
	var elem1 = new Foundation.Tooltip($("#hhnumber"), options);
	var elem2 = new Foundation.Reveal($("#createHHModal"), options);
	var elem3;
	elem4 = new Foundation.Reveal($("#HHMembersContainer"), options);
	$("#successMsgNewHH").hide();
	$("#createNewHH").click(function(){
		$.ajax({
			url : "/DataEntry/getNewHHEntry",
			type: 'get',
			data: {},
			dataType: 'html',
			success : function(html) {
				$("#errorMsgNewHH").hide();
				renderHHForm(html, elem2);
			},
			error: function() {
				$("#errorMsgNewHH").show();
			}
		});
	});
	
	$('#hhnumber').on('change textInput input', function () {
		$("#showHHMembers").removeClass("success");
        if(this.value.length == 12){
			$("#showHHMembers").removeAttr("disabled");
			$("#showHHMembersText").text("Verify");
		} else{
			$("#showHHMembers").attr('disabled', 'disabled');
		}
		/*if($("#HHMembersContainer").css('display') == "block"){
			$("#HHMembersContainer").hide();
		}*/
    });
	
	$("#idcards input, #panel_IDCardshh input").on('change', function(){
		$("#idcards_error").hide();
		var accepted = this.getAttribute('data-accepted-value');
		var value = validateIDCards(accepted, this.value);
		if(value == false){
			$("#idcards_error").show();
		} else{
			if(this.value.length != this.getAttribute('data-accepted-length')){
				$("#idcards_error").show();
			}
		}
	});
	
	$("#showHHMembers").click(function(){
		$('#searching').show();
		$('#showHHMembersText').hide();
		if($('#hhnumber').val().length == 12){
			var data = {'hh_id' : '' + $('#hhnumber').val() + ''};
			$.ajax({
				url : "/DataEntry/getHHMembers",
				type: 'post',
				data: data,
				dataType: 'html',
				success : function(response) {
					renderMemberList(response, elem4);
					$('#searching').hide();
					$('#showHHMembersText').show();
				},
				error: function() {
					alert("Error");
					$('#showHHMembersText').show();
					$('#searching').hide();
				}
			});
		}
	});
	
	$('#newEntry select, #newEntry input').on('change', function() {
		var elemId = this.id.split("_");
		var elemType = this.type;
		if(elemId[0] !== 'idcard'){
			var formObject = document.getElementById("newEntry");
			for(var i=0; i < formObject.elements.length; i++){
				var e = formObject.elements[i];
				console.log(e.id);
				compareId = e.id.split("_");
				if(elemId[1] === compareId[1] && elemId[1] !== undefined && elemType === e.type){
					if(e.type == 'radio'){
						if(elemId[2] === compareId[2] && elemId[2] !== undefined){
							e.checked = "checked";
						}
					} else{
						e.value = this.value;
					}
				}
			}
		}
	});
	
	$('#dob').fdatepicker({
		format: 'yyyy-mm-dd',
		disableDblClickSelection: true,
		leftArrow:'<<',
		rightArrow:'>>'
		//closeIcon:'X'
		//closeButton: true
	});
	
	$("#dob").on("change", function(){
		var elemId = "4"; // 4 is an id of "age" attribute
		var elemType = "number";
		var setValue = calculateAge($("#dob").val(), new Date());
		if(setValue < 0){
			setValue = "";
			alert("Birthdate cannot be grater than current date.");
			$("#dob").val("");
		}
		var formObject = document.getElementById("newEntry");
		for(var i=0; i < formObject.elements.length; i++){
			var e = formObject.elements[i];
			console.log(e.id);
			compareId = e.id.split("_");
			if(elemId === compareId[1] && elemId !== undefined && elemType === e.type){
				e.value = setValue;
			}
		}
	});
});

function calculateAge (birthDate, otherDate) {
	birthDate = new Date(birthDate);
	otherDate = new Date(otherDate);

	var years = (otherDate.getFullYear() - birthDate.getFullYear());

	if (otherDate.getMonth() < birthDate.getMonth() || 
		otherDate.getMonth() == birthDate.getMonth() && otherDate.getDate() < birthDate.getDate()) {
		years--;
	}

	return years;
}

function renderHHForm(html, elem2){
	elem2.open();
	console.log(html);
	$("#createHHModal").html(html);
	$('#newEntryHH select, #newEntryHH input').on('change', function() {
		var elemId = this.id.split("_");
		var elemType = this.type;
		if(elemId[0] !== 'idhh'){
			var formObject = document.getElementById("newEntryHH");
			for(var i=0; i < formObject.elements.length; i++){
				var e = formObject.elements[i];
				console.log(e.id);
				compareId = e.id.split("_");
				if(elemId[2] === compareId[2] && elemId[2] !== undefined && elemType === e.type){
					if(e.type == 'radio'){
						if(elemId[3] === compareId[3] && elemId[3] !== undefined){
							e.checked = "checked";
						}
					} else{
						e.value = this.value;
					}
				}
			}
		}
	});
	var options = {};
	elem3 = new Foundation.Tabs($("#newEntryHHTabs"), options);
	$("#saveNewHH").click(function(){
		$("#hhnumber").prop('disabled', true);
		$("#hhnumberContainer").hide();
		$("#successMsgNewHH").show();
		HHFormSaved = 1;
		elem2.close();
	});
}

function renderMemberList(response, elem4){
	var data = JSON.parse(response);
	var html;
	if(data['responseType'] == '1'){
		html = "<h6 class='barTitle'>Member(s) list of household number "+ data['text'][0]['hh_id'] + "</h6>";
		html += "<table><thead><tr><th>#</th><th>EC number</th><th>Name</th></tr></thead><tbody>";
		console.log();
		var j=1;
		for(i=0;i<data['text'].length;i++){
			html += "<tr>";
			html += "<td>"+j+"</td>";
			html += "<td>"+data['text'][i]['ec_id']+"</td>";
			html += "<td>"+data['text'][i]['name']+"</td>";
			html += "</tr>";
			j++;
		}
		html += "</tbody></table>";
		html += "<div class='medium-12 columns'><br />";
		html += "<label style='font-weight: bold;'>Do you belong to this household?</label>";
		html += "<div class='button primary' id='verifyHHYes' style='margin-right: 5px;'>Yes</div>";
		html += "<div class='button secondary' id='verifyHHNo'>No</div>";
		html += "</div>";
	} else if(data['responseType'] == '2'){
		html = "<label style='color: #980707;margin: -0.5rem 0 10px 5px;'>Household number not exist.</label>";
	} else{
		html = "<label style='color: #980707;margin: -0.5rem 0 10px 5px;'>"+data['text']+"</label>";
	}
	$("#HHMembersContainer").html(html);
	if($("#verifyHHYes") !== 'undefined' && $("#verifyHHNo") !== 'undefined'){
		$(document).on("click", "#verifyHHYes", function(){ verifyHH('yes'); });
		$(document).on("click", "#verifyHHNo", function(){ verifyHH('no'); });
	}
	elem4.open();
}

function verifyHH(value){
	if(value === 'yes'){
		hhVerified = 1;
		$("#showHHMembersText").text("Successfully verified");
		$("#showHHMembers").addClass("success");
	} else{
		hhVerified = 0;
		$("#showHHMembersText").text("Verify");
		$("#showHHMembers").removeClass("success");
	}
	elem4.close();
}

function submitAttributes(params){
	if(HHFormSaved == 0 && hhVerified == 0){
		$('#newEntry_error').html("Invalid household. Please verify household number or create new household.");
		$("#newEntry_error").show();
		return false;
	}
	if($("#idcards_error").css('display') == 'block'){
		$('#newEntry_error').html("Invalid value in id cards. Please enter correct number.");
		$("#newEntry_error").show();
		return false;
	}
	if($("#newEntryHH") !== 'undefined' && HHFormSaved == 1){
		$('#newEntryHH :input').hide().appendTo('#newEntry');
		/*var originalSelects = $("#newEntryHH").find('select');
		$('#newEntry').find('select').each(function(index, item) {
			//set new select to value of old select
			$(item).val( originalSelects.eq(index).val() );
		
		});
		//get original textareas into a jq object
		var originalTextareas = $("#newEntryHH").find('textarea');
		 
		$('#newEntry').find('textarea').each(function(index, item) {
			//set new textareas to value of old textareas
			$(item).val(originalTextareas.eq(index).val());
		});*/
	}
	return true;
}
