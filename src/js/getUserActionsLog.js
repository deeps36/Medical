var table;
$(document).ready(function(){
	 table = $('table.display').DataTable({
		 "bSort": true,
		 "order": [],
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
            ],
          autoWidth: false, 
		  columns : [
		    { width: '10%' },
		    { width: '10%' },
		    { width: '10%' },
		    { width: '10%' },
		    { width: '10%' },
		    { width: '10%' },
		    { width: '30%' },
		    { width: '10%' }
		  ]
	 });

	 window.setTimeout(function(){
    	loadData();
    }, 2000);
	 
});

function loadData(){
	var loop = count/limit;
	for(var i = 1; i <= loop; i++){
		$.ajax({
			url : "/Reports/getUserActionsLog",
			type: 'post',
			data: {'offset' : offset},
			dataType: 'html',
			success : function(response) {
				var data = JSON.parse(response);
				for(i=0;i<data['text'].length;i++){
					table.row.add( [
			            data['text'][i]['id'],
			            data['text'][i]['user_id'],
			            data['text'][i]['timestamp'],
			            data['text'][i]['ip_address'],
			            data['text'][i]['user_agent'],
			            data['text'][i]['action_performed'],
			            data['text'][i]['parameters_submitted'],
			            data['text'][i]['status']
			        ] ).draw( false );
				}
			},
			
		});
		offset = offset+limit;
	}
}