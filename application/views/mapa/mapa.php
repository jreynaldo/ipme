<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="INE">
        <title>IPM</title>
        <script type="text/javascript" src="<?= base_url() ?>assets/jquery/jquery-1.10.1.min.js"></script>
        <script type="text/javascript" src="<?= base_url() ?>js/OpenLayers-2.13.1/OpenLayers.js"></script>
        <script src="http://maps.google.com/maps/api/js?sensor=false"></script>
        <style>
            html, body {
                width: 100%;
                height: 100%;
                margin: 0;
                padding: 0;
            }
        </style>
        <script type="text/javascript">
            function addFeature(json, proj, vectores) {
                var jsonReader = new OpenLayers.Format.GeoJSON({'internalProjection': proj,
                    'externalProjection': new OpenLayers.Projection('EPSG:4326')});
                var feature = jsonReader.read(json);
                if(feature) {
                    if(feature.constructor !== Array) {
                        feature = [feature];
                    }
                    vectores.addFeatures(feature);
                }
                return feature;
            }
            function getFeature(feature, proj) {
                var jsonWriter = new OpenLayers.Format.GeoJSON({'internalProjection': proj,
                    'externalProjection': new OpenLayers.Projection('EPSG:4326')});
                var json = jsonWriter.write(feature);
                return json;
            }
            function bounds(feature, bound) {
                if(feature) {
                    for(var i=0; i<feature.length; ++i) {
                        if (!bound) {
                            bound = feature[i].geometry.getBounds();
                        } else {
                            bound.extend(feature[i].geometry.getBounds());
                        }
                    }
                }
                return bound;
            }
            function init() {
                var map = new OpenLayers.Map("olmap", {numZoomLevels: 20});
                
                var sat = new OpenLayers.Layer.Google("Google Satellite", {type: google.maps.MapTypeId.SATELLITE, wrapDateLine: false});
                map.addLayer(sat);
                var streets = new OpenLayers.Layer.Google("Google Streets");
                map.addLayer(streets);
                
                var jupms = [<?php for ($i = 0; $i < count($json); $i++) {
                                if ($i < count($json) - 1) {
                                    echo "['".$json[$i]['json']."', '".$json[$i]['usucre']."'], ";
                                } else {
                                    echo "['".$json[$i]['json']."', '".$json[$i]['usucre']."']";
                                }
                            }?>];
                var upms = new OpenLayers.Layer.Vector("Comercializadoras", {
                    styleMap: new OpenLayers.StyleMap({
                        "default": new OpenLayers.Style({
                            strokeColor: "#ffee00",
                            strokeOpacity: 1,
                            strokeWidth: 1,
                            fillColor: "#ffee00",
                            fillOpacity: .75,
                            cursor: "pointer",
                            label: "${pre}",
                            fontColor: "#ffee00",
                            externalGraphic: "<?= base_url() ?>img/upms.png",
                            graphicHeight: 48,
                            graphicWidth: 60,
                            graphicXOffset: -47,
                            graphicYOffset: -46
                        })
                    })
                });
                map.addLayer(upms);
                for (var i = 0; i < jupms.length; i++) {
                    if (jupms[i][0] !== '') {
                        var f = addFeature(jupms[i][0], map.baseLayer.projection, upms);
                        var b = bounds(f, b);
                        f[0].attributes = {pre: jupms[i][1]};
                    }
                }
                map.zoomToExtent(b);
                map.addControl(new OpenLayers.Control.LayerSwitcher());
            }
        </script>
    </head>
    <body onload="init()">
        <div id="map" style="width: 100%; height: 100%;">
            <div id="gmap" class="fill"></div>
            <div id="olmap" class="fill"></div>
        </div>
    </body>
</html>