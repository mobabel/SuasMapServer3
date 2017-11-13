<?php

/**
 *
 * @version $Id$
 * @copyright 2007
 */
include_once '../config.php';
include_once '../global.php';
require_once '../Models/Cache.class.php';

$assalowarray = array("%3a", "%2f", "%40", "%2b", "%28", "%29", "%3f", "%3d", "%26");
$assaupperarray = array("%3A", "%2F", "%40", "%2B", "%28", "%29", "%3F", "%3D", "%26");
$chararray = array(":", "/", "@", "+", "(", ")", "?", "=", "&");

$QUERY_STRING = $_SERVER ['QUERY_STRING'];
/*
$QUERY_STRING = str_replace('%2A', ':', $QUERY_STRING);
$QUERY_STRING = str_replace('%2C', ',', $QUERY_STRING);
$QUERY_STRING = str_replace('%2F', '/', $QUERY_STRING);
$QUERY_STRING = str_replace('%2B', '+', $QUERY_STRING);
*/
$QUERY_STRING = str_replace($assalowarray, $chararray, $QUERY_STRING);
$QUERY_STRING = str_replace($assaupperarray, $chararray, $QUERY_STRING);

$a = explode('&', $QUERY_STRING);
$i = 0;
while ($i < count($a)) {
    $b = split('=', $a[$i]);
    // echo "Value for parameter " .$b[0].
    // "is " .$b[1]. "\n";
    $text_upper = strtoupper($b[0]);
    // $equal  = strcasecmp("REQUEST","request");
    if ($text_upper == "SERVICE") {
        $serservice = htmlspecialchars(urldecode($b[1]));
    }
    if ($text_upper == "REQUEST") {
        $request = htmlspecialchars(urldecode($b[1]));
    }
    if ($text_upper == "VERSION") { // the comma ',' in serversion is speicialchars, so must be preserved
        $serversion = $b[1];
    }
    if ($text_upper == "STYLES") {
        $style = htmlspecialchars(urldecode($b[1]));
    }
    if ($text_upper == "WIDTH") {
        $width = htmlspecialchars(urldecode($b[1]));
    }
    if ($text_upper == "HEIGHT") {
        $height = htmlspecialchars(urldecode($b[1]));
    }
    if ($text_upper == "FORMAT") { // the plus '+' in format is speicialchars, so must be preserved
        $format = strtolower($b[1]);
    }
    if ($text_upper == "SRS") {
        $srs = htmlspecialchars(urldecode($b[1]));
    }
    if ($text_upper == "BBOX") {
        $bbox = htmlspecialchars(urldecode($b[1]));
    }
    if ($text_upper == "LAYERS") {
        $layers = htmlspecialchars(urldecode($b[1]));
    }
    if ($text_upper == "TRANSPARENT") {
        $transparent = strtolower(htmlspecialchars(urldecode($b[1])));
    }
    if ($text_upper == "EXCEPTIONS") {
        $exceptions = htmlspecialchars(urldecode($b[1]));
    }
    // =============OpenLayers Parameters=============================
    if ($text_upper == "OPTEMPLATE") {
        $optemplate = htmlspecialchars(urldecode($b[1]));
    }
    if ($text_upper == "OPZOOMLEVEL") {
        $opzoomlevel = htmlspecialchars(urldecode($b[1]));
        if($opzoomlevel=="") $opzoomlevel = 1;
    }

    if ($text_upper == "OPLAYERSWITCHER") {
        $oplayerswitcher = htmlspecialchars(urldecode($b[1]));
    }
    if ($text_upper == "OPMOUSEDEFAULTS") {
        $opmousedefaults = htmlspecialchars(urldecode($b[1]));
    }
    if ($text_upper == "OPMOUSEPOSITION") {
        $opmouseposition = htmlspecialchars(urldecode($b[1]));
    }
    if ($text_upper == "OPMOUSETOOLBAR") {
        $opmousetoolbar = htmlspecialchars(urldecode($b[1]));
    }
    if ($text_upper == "OPOVERVIEWMAP") {
        $opoverviewmap = htmlspecialchars(urldecode($b[1]));
    }
    if ($text_upper == "OPPANZOOM") {
        $oppanzoom = htmlspecialchars(urldecode($b[1]));
    }
    if ($text_upper == "OPPANZOOMBAR") {
        $oppanzoombar = htmlspecialchars(urldecode($b[1]));
    }
    if ($text_upper == "OPPERMALINK") {
        $oppermalink = htmlspecialchars(urldecode($b[1]));
    }
    if ($text_upper == "OPSCALE") {
        $opscale = htmlspecialchars(urldecode($b[1]));
    }
    if ($text_upper == "OPDRAWFEATURE") {
        $opdrawfeature = htmlspecialchars(urldecode($b[1]));
    }
    if ($text_upper == "OPGETFEATUREINFO") {
        $opgetfeatureinfo = htmlspecialchars(urldecode($b[1]));
    }
    // =====================Map Servers==================
    if ($text_upper == "OPOPENLAYERSWMS") {
        $opopenlayerswms = htmlspecialchars(urldecode($b[1]));
    }
    if ($text_upper == "OPOPENPLANSWMS") {
        $opopenplanswms = htmlspecialchars(urldecode($b[1]));
    }
    if ($text_upper == "OPMULTIMAP") {
        $opmultimap = htmlspecialchars(urldecode($b[1]));
    }
    if ($text_upper == "OPNASAWORLDWIND") {
        $opnasaworldwind = htmlspecialchars(urldecode($b[1]));
    }
    if ($text_upper == "OPGOOGLEMAP") {
        $opgooglemap = htmlspecialchars(urldecode($b[1]));
    }
    if ($text_upper == "OPGOOGLESATELLITE") {
        $opgooglesatellite = htmlspecialchars(urldecode($b[1]));
    }
    if ($text_upper == "OPGOOGLEHYBRID") {
        $opgooglehybrid = htmlspecialchars(urldecode($b[1]));
    }
    if ($text_upper == "OPVIRTUALEARTH") {
        $opvirtualearth = htmlspecialchars(urldecode($b[1]));
    }
    if ($text_upper == "OPYAHOOMAP") {
        $opyahoomap = htmlspecialchars(urldecode($b[1]));
    }

    $i++;
}

if ($exceptions == "")
    $exceptions = "application/vnd.ogc.se_xml";
if ($transparent == "")
    $transparent = "False";
if ($optemplate == "")
    $optemplate = 0;

$bboxvalues = explode(",", $bbox);
$minx = $bboxvalues[0];
$miny = $bboxvalues[1];
$maxx = $bboxvalues[2];
$maxy = $bboxvalues[3];

$serverhost = $wmsmetadata['ServerHost'];
$serverget = $serverhost . strtoupper($wmsservice) . "/getmapcap.php?";

if ($enablecache == 1) {
    $cache = new Cache($cacheLimitTime, 14);
    $cache->cacheCheck();
}

$tmparray = array("##BBOX##", "##LAYERS##", "##SRS##", "##MINX##", "##MINY##", "##MAXX##", "##MAXY##", "##HEIGHT##", "##WIDTH##", "##SERVERGET##", "##REQUEST##");
$paramsarray = array($bbox, $layers, $srs, $minx, $miny, $maxx, $maxy, $height, $width, $serverget, strtolower($request));
// use Template
if ($optemplate != 0) {
    $fileName = "OpenLayersTemplate/OpenlayersViewer" . $optemplate . ".tmpl";

    if (file_exists($fileName)) {
        $fp = fopen($fileName, "r");
        while (!feof($fp)) {
            $line .= fgets($fp, 1024);
        }
        $line = str_replace($tmparray, $paramsarray, $line);
        echo $line;

        fclose($fp);
    } else {
        echo "Template " . $fileName . " does not exist, please check it.";
    }
}
// use openlayers parameters
if ($optemplate == 0) {
    print '<html>' . "\n";
    print '<head>' . "\n";
    if ($opgooglemap OR $opgooglesatellite OR $opgooglehybrid) {
        print '    <!-- this gmaps key could be edited in global.php-->' . "\n";
        print '    <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=' . GoogleMapKey . '"></script>' . "\n";
    }
    print '    <!-- You can set the openlayers.js path as yours-->' . "\n";
    print '    <script src="../Plugin/OpenLayers/lib/OpenLayers.js"></script>' . "\n";
    if ($opmultimap) {
        print '    <script type="text/javascript" src="http://clients.multimap.com/API/maps/1.1/metacarta_04"></script>' . "\n";
    }
    if ($opyahoomap) {
        print '    <script src="http://api.maps.yahoo.com/ajaxymap?v=3.0&appid=euzuro-openlayers"></script>' . "\n";
    }
    if ($opvirtualearth) {
        print '    <script src="http:// dev.virtualearth.net/mapcontrol/v3/mapcontrol.js"></script>' . "\n";
    }
    print '</head>' . "\n";
    print '<body>' . "\n";

    if ($oppermalink) {
        print ' <a href="" id="permalink">Permalink</a>' . "\n";
    }
    if($opgetfeatureinfo){
	  print ' <div style="float:right;width:28%">
      <p style="font-size:.8em;">Click to get feature information.</p>
      <div id="nodeList">
      </div>
      </div>' . "\n";
	}
    print ' <div style="width:' . $width . '; height:' . $height . '" id="map"></div>' . "\n";
    print ' <script defer="defer" type="text/javascript">' . "\n";

    print 'var map;' . "\n";
    print 'var lon = (' . $minx . '+' . $maxx . ')/2;' . "\n";
    print 'var lat = (' . $miny . '+' . $maxy . ')/2;' . "\n";
    print 'var zoom = '.$opzoomlevel.';' . "\n";

    print 'map = new OpenLayers.Map( $(\'map\'), {maxExtent: new OpenLayers.Bounds(' . $minx . ',' . $miny . ',' . $maxx . ',' . $maxy . '), maxResolution: "auto", projection:"' . $srs . '" } );' . "\n";

    if ($oppanzoombar)
        print 'map.addControl(new OpenLayers.Control.PanZoomBar());' . "\n";

    if ($opmousedefaults)
        print 'map.addControl(new OpenLayers.Control.MouseDefaults());' . "\n";

    if ($opmousetoolbar)
        print 'map.addControl(new OpenLayers.Control.MouseToolbar());' . "\n";

    if ($oplayerswitcher)
        print 'map.addControl(new OpenLayers.Control.LayerSwitcher());' . "\n";

    if ($opmouseposition)
        print 'map.addControl(new OpenLayers.Control.MousePosition());' . "\n";

    if ($opoverviewmap)
        print 'map.addControl(new OpenLayers.Control.OverviewMap());' . "\n";

    if ($oppermalink)
        print 'function updateLink() {
           pl = document.getElementById("permalink");
           center = this.getCenter();
           zoom = this.getZoom();
           lat = Math.round(center.lat*1000)/1000;
           lon = Math.round(center.lon*1000)/1000;
           pl.href = "?lat="+lat+"&lon="+lon+"&zoom="+zoom;
        }' . "\n";

    if ($opscale)
        print 'map.addControl(new OpenLayers.Control.Scale());' . "\n";

    /*
    if ($opdrawfeature){
        print 'var drawControls = {
    point: new OpenLayers.Control.DrawFeature(pointLayer,
           OpenLayers.Handler.Point),
    line: new OpenLayers.Control.DrawFeature(lineLayer,
           OpenLayers.Handler.Path, options),
    polygon: new OpenLayers.Control.DrawFeature(polygonLayer,
                            OpenLayers.Handler.Polygon, options)
     };' . "\n";
         print 'map.addControl(drawControls);'. "\n";

         print 'var vectorlayer = new OpenLayers.Layer.Vector( "Editable" );
            map.addLayer(vectorlayer);
            map.addControl(new OpenLayers.Control.EditingToolbar(vectorlayer));'. "\n";
     }
*/
    // var ls = new OpenLayers.Control.LayerSwitcher();
    // map.addControl(ls);
    // ls.maximizeControl();
    $wmscount = 0;
    print 'var suaswms = new OpenLayers.Layer.WMS( "' . $softNamePrifix . '",' . "\n";
    print '"' . $serverget . '",' . "\n";
    print '{layers: \'' . $layers . '\', request: \'getmap\', transparent: "' . $transparent . '", format: "' . $format . '"} );' . "\n";
    print 'map.addLayer(suaswms);' . "\n";
    //print 'suaswms.setVisibility(false);' . "\n";

    if ($opopenlayerswms) {
        print 'var opwms' . $wmscount . ' = new OpenLayers.Layer.WMS( "OpenLayers WMS", "http://labs.metacarta.com/wms/vmap0", {layers: \'basic\'} );' . "\n";
        $wmscount++;
    }

    if ($opopenplanswms) {
        print 'var opwms' . $wmscount . ' = new OpenLayers.Layer.WMS( "OpenPlans WMS",
				"http://sigma.openplans.org:3128/geoserver/wms",
							{layers: "topp:poly_landmarks,topp:water_polygon,topp:water_shorelines,topp:roads,topp:major_roads,topp:states,topp:countries,topp:gnis_pop",
										 transparent: "false", format: "image/png", styles: "freemap_open_space,freemap_water,water_line,freemap_roads,freemap_major_roads,states_ol_sat,world_countries,gnis_pop_ol"});' . "\n";
        $wmscount++;
    }

    if ($opmultimap) {
        print 'var opwms' . $wmscount . '= new OpenLayers.Layer.MultiMap( "MultiMap", {minZoomLevel: 1});' . "\n";
        $wmscount++;
    }

    if ($opnasaworldwind) {
        print 'var opwms' . $wmscount . '= new OpenLayers.Layer.KaMap( "World Wind (NASA)","/world/index.php", {g: "satellite", map: "world"});' . "\n";
        $wmscount++;
    }

    if ($opgooglemap) {
        print 'var opwms' . $wmscount . '= new OpenLayers.Layer.Google("Google Map");' . "\n";
        $wmscount++;
    }

    if ($opgooglesatellite) {
        print 'var opwms' . $wmscount . '= new OpenLayers.Layer.Google("Google Satellite", { \'type\': G_SATELLITE_MAP });' . "\n";
        $wmscount++;
    }

    if ($opgooglehybrid) {
        print 'var opwms' . $wmscount . '= new OpenLayers.Layer.Google("Google Hybrid", { \'type\': G_HYBRID_MAP });' . "\n";
        $wmscount++;
    }

    if ($opvirtualearth) {
        print 'var opwms' . $wmscount . '= new OpenLayers.Layer.VirtualEarth("VirtualEarth", {\'minZoomLevel\': 0});' . "\n";
        $wmscount++;
    }

    if ($opyahoomap) {
        print 'var opwms' . $wmscount . '= new OpenLayers.Layer.Yahoo("Yahoo");' . "\n";
        $wmscount++;
    }


    if($wmscount!=0){
        $alllayers = "";
        for($i=0;$i<$wmscount;$i++){
            if($i!=$wmscount-1)
		        $alllayers .= "opwms".$i.",";
		    else
		        $alllayers .= "opwms".$i;
		}
	    print 'map.addLayers(['.$alllayers.']);' . "\n";
	}

    print 'map.setCenter(new OpenLayers.LonLat(lon, lat), zoom);' . "\n";

    if($opgetfeatureinfo){
    print 'map.events.register(\'click\', map, function (e) {
            $(\'nodeList\').innerHTML = "Loading... please wait...";
            var url =  suaswms.getFullRequestString({
                            REQUEST: "GetFeatureInfo",
                            EXCEPTIONS: "application/vnd.ogc.se_xml",
                            BBOX: suaswms.map.getExtent().toBBOX(),
                            X: e.xy.x,
                            Y: e.xy.y,
                            INFO_FORMAT: \'text/html\',
                            QUERY_LAYERS: suaswms.params.LAYERS,
                            REDIUS:2,
                            WIDTH: suaswms.map.size.w,
                            HEIGHT: suaswms.map.size.h});
            OpenLayers.loadURL(url, \'\', this, setHTML);
            Event.stop(e);
      });
    function setHTML(response) {
        $(\'nodeList\').innerHTML = response.responseText;
    }' . "\n";
    }

    print ' </script>' . "\n";
    print ' </body>' . "\n";
    print ' </html>' . "\n";
}

if ($enablecache == 1) {
    $cache->caching();
}

?>