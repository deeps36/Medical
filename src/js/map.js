var state_layer, district_layer, tehsil_layer, village_layer;

$(document).ready(function(){
	$(".legendRow").hide();
	$("#legendContent").hide();
});

var view = new ol.View({
	center:  ol.proj.fromLonLat([84.25,23.92]),
	zoom: 12
});
var layerName;
var layersByName = {};
var osmLayer = new ol.layer.Tile({
    source: new ol.source.OSM()
});

var googleLayer = new olgm.layer.Google();

 var mousePositionControl = new ol.control.MousePosition({
	coordinateFormat: ol.coordinate.createStringXY(4),
	projection: 'EPSG:4326',
	// comment the following two lines to have the mouse position
	// be placed within the map.
	className: 'custom-mouse-position',
	target: document.getElementById('latlonInfo'),
	undefinedHTML: '&nbsp;'
});

 var scaleLine = new ol.control.ScaleLine({
	minWidth: 100
  });

var map = new ol.Map({
    target: 'map',
    interactions: olgm.interaction.defaults()
});
map.setView(view);
map.addLayer(googleLayer);
map.addControl(mousePositionControl);
map.addControl(scaleLine);
var olGM = new olgm.OLGoogleMaps({map: map});
olGM.activate();
var layerList = {};
var layerFilters = {};

function getBounds(layer, baseurl)
{
	// var featurePrefix = '***';
	// var featureType = '***';
	var url = baseurl+'?request=GetCapabilities&service=WMS&version=1.1.1';
    // var url = 'http://db2.biota.in:8080/geoserver/geet/wms?request=GetCapabilities&service=WMS&version=1.1.1';
	var parser = new ol.format.WMSCapabilities();
	$.ajax(url).then(function (response) {
        //window.alert("word");

        var result = parser.read(response);
        // console.log(result);
        var Layers = result.Capability.Layer.Layer;
        var extent;
        for (var i = 0, len = Layers.length; i < len; i++) {
            var layerobj = Layers[i];
            if (layerobj.Name == layer.get('Name'))
            {
            	extent = layerobj.BoundingBox[0].extent;
            	map.getView().fit(extent, map.getSize());
            }
        }
    });
}

function changeLayerOpacity(elemid, value){
	//elemid.split("_");
	//var layerId = temp[1]+"_"+temp[2]+"_"+temp[3]+"_"+temp[4]+"_"+temp[5];
	var layerId = elemid.replace("layerOpacitySlider_","");
	layerList[layerId].setOpacity(Number(value));
}

function updateCQL(type, code){
	for (var lyr in layerList) {
		layerList[lyr].getSource().updateParams({'cql_filter': type+'_code=' + code + ';sc_id='+ layerFilters[lyr]['cql_filter_sc_id'] + layerFilters[lyr]['cql_filter_field']}); 
	}  
}

function removeLayer(layerId)
{
	map.removeLayer(layerList[layerId]);
	$("#layerRow_"+layerId).remove();
	$("#legendRow_"+layerId).remove();
	layerList[layerId] = (function () { return; })();
	delete $("#layerOpacitySlider_"+layerId);
}