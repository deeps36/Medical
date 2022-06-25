function BarChart(c_id, vAxisTitle, hAxisTitle, isStacked = false)
{
	var containerID = c_id;
	var cWidth = ($("#"+containerID).width() * 90)/100;
	var dataObject = new Array(); 
	var chart = null;
	var fields = null;
	var options = null;
	
	options = {
	  //'width': cWidth,
	  //'height': '350',
	  'vAxes': { 0: {title: vAxisTitle} },
	  'hAxes': { 0: {title: hAxisTitle} },
	  'chartArea': {'width': '60%', 'height': '90%'},
	  'legend': {'textStyle': {'color': 'black', 'fontSize': '12'}},
	  'backgroundColor':'transparent',
	  'hAxis.title': 'Year',
	  'vAxis.title': 'Area',
	  'hAxis': {'textStyle':{'color': 'black', 'fontSize':'11'}},
	  'vAxis': {'textStyle':{'color': 'black', 'fontSize':'11'}},
	  'pointSize': '2'
	  //'curveType':'function'
	};
	
	if(isStacked == true){
		options['isStacked'] = true;
		options['legend']['position'] = 'top';
		options['legend']['maxLines'] = 3;
	}
	
	chart = new google.visualization.BarChart(document.getElementById(containerID));
	
	this.setFields = function(fieldsArray)
	{
		fields = fieldsArray;
		dataObject.push(fieldsArray);
	}
	
	this.clearData = function()
	{
		dataObject = new Array();
		dataObject.push(fields);
		if(containerID !== "")
		{
			//document.getElementById(containerID).style.display = "none";
		}
	}
	
	this.addData = function(dataRow, subDataArr)
	{	
		//alert("dataRow[1]: " + dataRow[1]);
		dataObject.push(dataRow);
		//console.log(dataRow);
	}
	
	this.show = function() 
	{ 
		//alert(google.visualization.arrayToDataTable(dataObject));
		if(containerID !== "")
		{
			document.getElementById(containerID).style.display = "block";
		}
		options['height'] = google.visualization.arrayToDataTable(dataObject).getNumberOfRows() * 30 + 20;
		//console.log(options['height']);
		chart.draw(google.visualization.arrayToDataTable(dataObject), options);
	}

	this.getImage = function() {
		//console.log(google.visualization.arrayToDataTable(dataObject));
		var container = document.getElementById("reportImage");
		var chartData = google.visualization.arrayToDataTable(dataObject);
		options['height'] = chartData.getNumberOfRows() * 30 + 20;
		google.visualization.events.addListener(chart, 'ready', function () {
			container.src = chart.getImageURI();
		});
		chart.draw(chartData, options);
	}
}