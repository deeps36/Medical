var store = "geet";
var baseurl = 'http://db2.biota.in:8080/geoserver/'+store+'/wms';
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

function loadLayer(layerName)
{
    var layer1 = new ol.layer.Tile({
        source : new ol.source.TileWMS({
            // crossOrigin : "anonymous",
            params : {
                'LAYERS' : store+":"+layerName
            },
            url : baseurl
        })
    });
    map.addLayer(layer1);
    layersByName[layerName] = layer1;
    $('#legend').attr('src',baseurl+'?Service=WMS&REQUEST=GetLegendGraphic&VERSION=1.0.0&FORMAT=image/png&WIDTH=12&HEIGHT=12&LAYER='+store+':'+layerName);
    // getBounds(layerName);
    source = layer1.getSource();
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

function getBounds(layer)
{
	// var featurePrefix = '***';
	// var featureType = '***';
	var url = 'http://db2.biota.in:9090/geoserver/'+store+'/wms?request=GetCapabilities&service=WMS&version=1.1.1';
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
            if (layerobj.Name == layer)
            {
            	extent = layerobj.BoundingBox[0].extent;
            	map.getView().fit(extent, map.getSize());
            }
        }
    });
}

function removeLayer(layerName)
{
    map.removeLayer(layersByName[layerName]);
}