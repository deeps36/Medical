function PieChart(c_id, _txtTitle, sub_c_id, _txtSubTitle, _tooltipUnit)
{
	if(sub_c_id === "undefined")
	{
		sub_c_id = "";
	}
	if(txtSubTitle === "undefined")
	{
		txtSubTitle = "";
	}
	if(_tooltipUnit === "undefined")
	{
		_tooltipUnit = "";
	}
	var containerID = c_id;
	var cWidth = ($("#"+containerID).width() * 90)/100;
	var subGraphContainerID = sub_c_id;
	var cSubGraphWidth = 0;
	if(subGraphContainerID !== "")
		cSubGraphWidth = $("#"+subGraphContainerID).width();
	var txtTitle = _txtTitle;
	var txtSubTitle = _txtSubTitle;
	var dataObject = new Array(); 
	var subDataObject = new Array(); //[ array of push({'name':'Rural','id':'Rural','data': [ [ 'Male',".$malePer. "], [ 'Female',".$femalePer. "] ]} ]
	var chart = null;
	var subChart = null;
	var fields = null;
	var subFields = null;
	var options = null;
	var tooltipUnit = _tooltipUnit;
	var childOptions = new Array();
	
	options = {
	  'title': txtTitle,
	  'width': cWidth,
	  'pieSliceText':'percentage',
	  'pieStartAngle':90,
	  'chartArea': {'width': '70%', 'height': '75%', 'top' : '10'},
	  'is3D': true,
	  'legend': {'textStyle': {'color': 'black', 'fontSize':'11'}},
	  'backgroundColor':'transparent',
	  'pieSliceTextStyle':{'color':'#ffffff','fontSize':'11'},
	  //'colors':['#faa523', '#000000', '#2E7A9E', '#818922', '#ff0000', '#ff7e00', '#FF9797', '#f6ff00', '#90ff00', '#00ffc0', '#0090ff', '#fc00ff', '#0c00ff', '#b400ff', '#a4237b', '#8F4F2C', '#EB6060'],
	  'pieSliceBorderColor' : "transparent",
	  'sliceVisibilityThreshold': 0 // '0'  - Force to draw all slices regardless of their value size 
	  //'tooltip': { trigger: 'selection' }
	};
	
	childOptions.push(
			{
			  'title': txtSubTitle,
			  'width': cSubGraphWidth,
			  'pieSliceText':'percentage',
			  'chartArea': {'width': '77%', 'height': '87%', 'top' : '0'},
			  'is3D': true,
			  'legend': {'textStyle': {'color': 'gray', 'fontSize':'11'}},
			  'backgroundColor':'transparent',
			  'pieSliceTextStyle':{'color':'#444444','fontSize':'12'},
			  'colors':['#fac575', '#faa82d']
			}
	);
	
	childOptions.push(
			{
			  'title': txtSubTitle,
			  'width': cSubGraphWidth,
			  'pieSliceText':'percentage',
			  'chartArea': {'width': '77%', 'height': '87%', 'top' : '0'},
			  'is3D': true,
			  'legend': {'textStyle': {'color': 'gray', 'fontSize':'11'}},
			  'backgroundColor':'transparent',
			  'pieSliceTextStyle':{'color':'#eeeeee','fontSize':'12'},
			  'colors':['#121212', '#373737']
			}
	);
	
	childOptions.push(
			{
			  'title': txtSubTitle,
			  'width': cSubGraphWidth,
			  'pieSliceText':'percentage',
			  'chartArea': {'width': '77%', 'height': '87%', 'top' : '0'},
			  'is3D': true,
			  'legend': {'textStyle': {'color': 'gray', 'fontSize':'11'}},
			  'backgroundColor':'transparent',
			  'pieSliceTextStyle':{'color':'#ffffff','fontSize':'12'},
			  'colors':['#4287a7', '#81afc4']
			}
	);
	
	childOptions.push(
			{
			  'title': txtSubTitle,
			  'width': cSubGraphWidth,
			  'pieSliceText':'percentage',
			  'chartArea': {'width': '77%', 'height': '87%', 'top' : '0'},
			  'is3D': true,
			  'legend': {'textStyle': {'color': 'gray', 'fontSize':'11'}},
			  'backgroundColor':'transparent',
			  'pieSliceTextStyle':{'color':'#ffffff','fontSize':'12'},
			  'colors':['#8d9438', '#a6ac64']
			}
	);
	
	chart = new google.visualization.PieChart(document.getElementById(containerID));
	
	if(subGraphContainerID !== "")
	{
		subChart = new google.visualization.PieChart(document.getElementById(subGraphContainerID));
	}
	
	this.setFields = function(fieldsArray)
	{
		fields = fieldsArray;
		dataObject.push(fieldsArray);
	}
	
	this.setSubFields = function(subFieldsArray)
	{
		subFields = subFieldsArray;
	}
	
	this.clearData = function()
	{
		dataObject = new Array();
		subDataObject = new Array();
		dataObject.push(fields);
		if(containerID !== "")
		{
			//document.getElementById(containerID).style.display = "none";
		}
		if(subGraphContainerID !== "")
		{
			document.getElementById(subGraphContainerID).style.display = "None";
		}
	}
	
	this.addData = function(dataRow, subDataArr)
	{
		//[array of {'drilldown':'Urban','name':'Urban','y': ".$urbanPer."}]
		if(subDataArr == undefined)
		{
			subDataArr = null;
		}
		else
		{
			subDataArr.splice(0, 0, subFields);
		}
		//alert("dataRow[1]: " + dataRow[1]);
		dataObject.push(dataRow);
		subDataObject.push(subDataArr);
	}
	
	this.show = function() 
	{ 
		//alert(google.visualization.arrayToDataTable(dataObject));
		if(containerID !== "")
		{
			document.getElementById(containerID).style.display = "block";
		}
		chart.draw(google.visualization.arrayToDataTable(dataObject), options);
		if(subGraphContainerID !== "")
		{
			google.visualization.events.addListener(chart, 'select', sectionClicked);
		}
		else
			subChart = null;
	}
	
	function sectionClicked(e)
	{
		var selection = chart.getSelection();
		var item = selection[0];
				
		if(item != undefined && subChart != null && subDataObject[item.row] != null)
		{
			document.getElementById(subGraphContainerID).style.display = "block";
			subChart.draw(google.visualization.arrayToDataTable(subDataObject[item.row]), childOptions[item.row]);
		}
	}
}