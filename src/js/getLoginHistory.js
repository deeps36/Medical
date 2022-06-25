$(document).ready(function(){
	 $('table.display').DataTable({
		 "bSort": true,
		  dom: 'Bfrtip',
		 buttons: [
                //'print',
                {
	                extend: 'print',
	                messageBottom: '\n\nReport generated at '+$(location).attr('href')+ '\n on '+new Date()+'\n Powered by Foundation for Ecological Security (FES) - http://fes.org.in/',
	            },
                {
	                extend: 'pdf',
	                orientation:'landscape',
	                messageBottom: '\n\nReport generated at '+$(location).attr('href')+ '\n on '+new Date()+'\n Powered by Foundation for Ecological Security (FES) - http://fes.org.in/',
	                pageSize: ''
	            },
	            {
	                extend: 'excel',
	                orientation:'landscape',
	                messageBottom: '\n\nReport generated at '+$(location).attr('href')+ '\n on '+new Date()+'\n Powered by Foundation for Ecological Security (FES) - http://fes.org.in/',
	                pageSize: ''
	            }
            ]
	 });
});