$(document).ready( function(){
	var table = $('#allDataTable').DataTable({
		"bSort": true,
		"aoColumnDefs" : [
		 {
			'bSortable' : false,
			'aTargets' : [ 0,5 ]
		 },{
			'bSortable' : false,
			'aTargets' : [ 0,5 ]
		 }]
		});
	$('#allDataTable thead th').each( function () {
		var title = $(this).text();
        
        if(title == 'Sr. No.' || title == 'Operations') return;
        
		var elem = document.createElement("input");
		elem.setAttribute("type", "text");
		elem.setAttribute("placeholder", "Search "+title);
		$(this).append(elem);
		//$(this).html( '<input type="text" placeholder="Search '+title+'" />' );
	});
	
	// Apply the search
	table.columns().every( function () {
		var that = this;
 
		$( 'input', this.header() ).on( 'keyup change', function () {
			if ( that.search() !== this.value ) {
				that
					.search( this.value )
					.draw();
			}
		} );
	} );
});