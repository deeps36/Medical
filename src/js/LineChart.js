function LineChart(c_id, _txtTitle, vAxisTitle, hAxisTitle)
{
	var containerID = c_id;
	var cWidth = $("#"+containerID).width();
	var txtTitle = _txtTitle;
	var dataObject = new Array(); 
	var chart = null;
	var fields = null;
	var options = null;
	
	options = {
	  'title': txtTitle,
	  'vAxes': { 0: {title: vAxisTitle} },
	  'hAxes': { 0: {title: hAxisTitle} },
	  'width': cWidth,
	  'height': '350',
	  'chartArea': {'width': '75%', 'height': '80%', 'top' : '15'},
	  'legend': {'textStyle': {'color': 'gray', 'fontSize':'9'}},
	  'backgroundColor':'transparent',
	  'hAxis.title': 'Year',
	  'vAxis.title': 'Area',
	  'hAxis': {'textStyle':{'color': '#000000'}},
	  'vAxis': {'textStyle':{'color': '#000000'}},
	  'pointSize': '2'
	  //'curveType':'functi
	};
	
	chart = new google.visualization.LineChart(document.getElementById(containerID));
	
	this.setFields = function(fieldsArray)
	{
		fields = fieldsArray;
		dataObject.push(fieldsArray);
	}
	
	this.clearData = function()
	{
		dataObject = new Array();
		dataObject.push(fields);
	}
	
	this.addData = function(dataRow, subDataArr)
	{	
		//alert("dataRow[1]: " + dataRow[1]);
		dataObject.push(dataRow);
	}
	
	this.show = function() 
	{ 
		//alert(google.visualization.arrayToDataTable(dataObject));
		chart.draw(google.visualization.arrayToDataTable(dataObject), options);
	}
}