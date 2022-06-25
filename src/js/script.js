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

var map = new ol.Map({
    target: 'map',
    interactions: olgm.interaction.defaults()
});
map.setView(view);
map.addLayer(googleLayer);
var olGM = new olgm.OLGoogleMaps({map: map});
olGM.activate();
var layerList = {};

function loadLayer(layerName, store, baseurl, layerId)
{
    layerList[layerId] = new ol.layer.Tile({
        source : new ol.source.TileWMS({
            // crossOrigin : "anonymous",
            params : {
                'LAYERS' : store+":"+layerName
            },
            url : baseurl
        })
    });
	layerList[layerId].set('Name', layerName);
    map.addLayer(layerList[layerId]);
    //getBounds(layer1, baseurl);
    layersByName[layerName] = layerList[layerId];
    $('#legend').attr('src',baseurl+'?Service=WMS&REQUEST=GetLegendGraphic&VERSION=1.0.0&FORMAT=image/png&WIDTH=12&HEIGHT=12&LAYER='+store+':'+layerName);
    source = layerList[layerId].getSource();
    map.on('singleclick', function(evt) {
        document.getElementById('info').innerHTML = '';
        var view = map.getView();
        var viewResolution = /** @type {number} */ (view.getResolution());
        var url = source.getGetFeatureInfoUrl(
            evt.coordinate, viewResolution, view.getProjection(),
            {'INFO_FORMAT': 'text/html', 'FEATURE_COUNT': 100});
        if (url) {
          document.getElementById('info').innerHTML =
              '<iframe width="100%" height="200px" seamless src="' + url + '"></iframe>';
        }
    });
}

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
	var temp = elemid.split("_");
	var layerId = temp[1]+"_"+temp[2];
	layerList[layerId].setOpacity(Number(value));
}

function removeLayer(layerId)
{
    map.removeLayer(layerList[layerId]);
	$("#layerRow_"+layerId).remove();
	layerList[layerId] = (function () { return; })();
	$("#layerOpacitySlider_"+layerId).foundation('destroy');
}