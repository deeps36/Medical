$(document).ready( function(){
	var table = $('table.display').DataTable({
		"bSort": true,
		"aoColumnDefs" : [
		 {
			'bSortable' : false,
			'aTargets' : [ 4 ]
		 }]
	});
	
	
});

function editEntry(object){
	//alert(object.id);
	document.getElementById("page_id").value = object.id;
	//alert(document.getElementById("ec_id").value);
	document.getElementById("editThisEntry").submit();
}