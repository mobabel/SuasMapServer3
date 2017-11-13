<?php

/**
 *
 * @version $Id$
 * @copyright 2007
 */
include_once '../config.php';
include_once '../global.php';
require_once '../Models/Cache.class.php';
require_once '../Models/CommonFormula.class.php';

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
        //$width = htmlspecialchars(urldecode($b[1]));
        //fit for Carto SVG Viewer
        $width = 580;
    }
    if ($text_upper == "HEIGHT") {
        //$height = htmlspecialchars(urldecode($b[1]));
        //fit for Carto SVG Viewer
        $height = 700;
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

    $i++;
}

if ($exceptions == "")
    $exceptions = "application/vnd.ogc.se_inimage";
if ($transparent == "")
    $transparent = "true";

$bboxvalues = explode(",", $bbox);
$minx = $bboxvalues[0];
$miny = $bboxvalues[1];
$maxx = $bboxvalues[2];
$maxy = $bboxvalues[3];

$newwidthhieght = getStretchWidthHeight($minx, $miny, $maxx, $maxy, $width, $height, $enablestretchmap);
$width = $newwidthhieght[0];
$height = $newwidthhieght[1];

$viewbox = $minx." ".(-$maxy)." ".($maxx-$minx)." ".($maxy-$miny);

$serverhost = $wmsmetadata['ServerHost'];
$serverget = $serverhost . strtoupper($wmsservice) . "/getmapcap.php?"."VERSION=$serversion&SERVICE=$serservice&REQUEST=".$request;

if ($enablecache == 1) {
    $cache = new Cache($cacheLimitTime, 0);
    $cache->cacheCheck();
}

$tmparray = array("parent.parent.parasBbox","parent.parent.parasViewBox", "parent.parent.parasLayers", "parent.parent.parasEpsg",
                     "parent.parent.parasHeight", "parent.parent.parasWidth",
					 "parent.parent.parasFormat", "parent.parent.urlGetmap","js/");
$paramsarray = array("\"".$bbox."\"","\"".$viewbox."\"", "\"".$layers."\"", "\"".$srs."\"", $height, $width, "\"".$format."\"", "\"".$serverget."\"","../Plugin/suasclient/js/");

header("Content-Type: image/svg+xml");

    $fileName = "../Plugin/suasclient/navigation.svg";

    if (file_exists($fileName)) {
        $count = 0;
        $fp = fopen($fileName, "r");
        while (!feof($fp)) {
            $line .= fgets($fp, 1024);
            $count++;
        }
        //the line 15-30 maybe changed!
        //if($count>15 && $count<30){
        	$line = str_replace($tmparray, $paramsarray, $line);
        //}
        echo $line;

        fclose($fp);
    } else {
        echo "Template " . $fileName . " does not exist, please check it.";
    }


if ($enablecache == 1) {
    $cache->caching();
}

?>