$(document).ready( function(){
	var table = $('table.display').DataTable({
		"bSort": true,
		"aoColumnDefs" : [
		 {
			'bSortable' : false,
			'aTargets' : [ 3 ]
		 }],
		 "columns" : [
			null,
			null,
			null,
			{ "width": "30%"}
		 ]
	});
	
	
});

function editEntry(object){
	//alert(object.id);
	document.getElementById("c_id").value = object.id;
	//alert(document.getElementById("ec_id").value);
	document.getElementById("editThisEntry").submit();
}