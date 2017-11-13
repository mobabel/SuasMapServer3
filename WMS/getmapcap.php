<?php
/**
 * Getmapcap Class
 * Copyright (C) 2006-2007  leelight
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @version $Id$
 * @copyright (C) 2006-2007  leelight
 * @Description : This show the copyright .
 * @contact webmaster@easywms.com
 * @version $1.0$ 2005
 * @Author Filmon Mehari and Professor Dr. Franz Josef Behr
 * @Contact filmon44@yahoo.com and franz-josef.behr@hft-stuttgart.de
 * @version $2.0$ 2006.05
 * @Author Chen Hang and leelight
 * @Contact unitony1980@hotmail.com
 * @version $3.0$ 2006
 * @Author leelight
 * @Contact webmaster@easywms.com
 */

include_once '../config.php';
require_once '../global.php';
require_once '../Models/CommonFormula.class.php';
include_once 'SendException.class.php';
include_once '../Models/CommonFunction.class.php';
require_once '../Models/Setting.class.php';
require_once '../Render/RasterImagerRender.class.php';
require_once '../Render/BMPRender.class.php';
require_once '../Render/GDGradientFill.php';
require_once '../Models/Cache.class.php';
require_once '../Parser/AttributeParser.class.php';

$errornumber = 0;

/*   : = %3a   / = %2f   @ = %40
 *   + = %2b   ( = %28   ) = %29
 *   ? = %3f   = = %3d   & = %26
*/
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
//this is for suas client, in Firefox the & will be turned to "&amp;" during the viriant transfering
$QUERY_STRING = str_replace('&amp;', '&', $QUERY_STRING);

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
        $format = $b[1];
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
        $transparent = htmlspecialchars(urldecode($b[1]));
    }
    if ($text_upper == "EXCEPTIONS") {
        $exceptions = htmlspecialchars(urldecode($b[1]));

    }

    /**
     * * Set the parameters of GetFeatureInfo
     */
    if ($text_upper == "INFO_FORMAT") {
        $info_format = htmlspecialchars(urldecode($b[1]));
    }
    if ($text_upper == "QUERY_LAYERS") {
        $query_layers = htmlspecialchars(urldecode($b[1]));
    }
    // Screen coordinate
    if ($text_upper == "X") {
        $Pixel_x = htmlspecialchars(urldecode($b[1]));
    }
    if ($text_upper == "Y") {
        $Pixel_y = htmlspecialchars(urldecode($b[1]));
    }
    if ($text_upper == "RADIUS") {
        $radius = htmlspecialchars(urldecode($b[1]));
    }
    /**
     * * Set the parameters of GetMap25D
     */
    if ($text_upper == "BGCOLOR") {
        $bgcolor = htmlspecialchars(urldecode($b[1]));
        if($bgcolor!="")
            $bgcolor = "#".$bgcolor;
    }
    if ($text_upper == "SKYCOLOR") {
        $skycolor = htmlspecialchars(urldecode($b[1]));
        if($skycolor!="")
            $skycolor = "#".$skycolor;
    }
    if ($text_upper == "HANGLE") {
        $hangle = htmlspecialchars(urldecode($b[1]));
        $hangle = deg2rad($hangle);
    }
    if ($text_upper == "VANGLE") {
        $vangle = htmlspecialchars(urldecode($b[1]));
        $vangle = deg2rad($vangle);
    }
    if ($text_upper == "DISTANCE") {
        $distance = htmlspecialchars(urldecode($b[1]));
    }
    /**
     * * Set the parameters of GetMap3D
     */
    if ($text_upper == "ELEVATIONS") {
        $elevations = htmlspecialchars(urldecode($b[1]));
        $elevationsvalues = explode(",", $elevations);
    }
    if ($text_upper == "POI") {
        $poi = htmlspecialchars(urldecode($b[1]));
        $poivalues = explode(",", $poi);
        $poix = $poivalues[0];
        $poiy = $poivalues[1];
        $poiz = $poivalues[2];
    }
    if ($text_upper == "PITCH") {
        $pitch = htmlspecialchars(urldecode($b[1]));
    }
    if ($text_upper == "YAW") {
        $yaw = htmlspecialchars(urldecode($b[1]));
    }
    if ($text_upper == "ROLL") {
        $roll = htmlspecialchars(urldecode($b[1]));
    }
    if ($text_upper == "AOV") {
        $aov = htmlspecialchars(urldecode($b[1]));
    }
    if ($text_upper == "ENVIRONMENT") {
        $environment = htmlspecialchars(urldecode($b[1]));
    }
    if ($text_upper == "BGIMAGE") {
        $bgimage = htmlspecialchars(urldecode($b[1]));
    }



    $i++;
}
if($exceptions == "")
    $exceptions = "application/vnd.ogc.se_xml";
if($transparent == "")
    $transparent = "False";

$bboxvalues = explode(",",$bbox);
$numberofvalues = count($bboxvalues);
$minx = $bboxvalues[0];
$miny = $bboxvalues[1];
$maxx = $bboxvalues[2];
$maxy = $bboxvalues[3];



$serverhost = $wmsmetadata['ServerHost'];
$sendexceptionclass = new SendExceptionClass($serverhost, $wmsservice,$wmsversion ,$exceptions,$format,
                    $minx, $miny, $maxx, $maxy, $width, $height, $enablestretchmap,$params['GetImageDefaultWidth'],$params['GetImageDefaultHeight']);

/**
 * *THE SEND EXCEPTION FUNCTION
 */
// include 'SendException.php';
// THE DIFFERENT REQUEST
if (!compareStringInsensitive($request,"GetFeatureInfo") AND !compareStringInsensitive($request,"GetMap") AND !compareStringInsensitive($request,"GetCapabilities") AND
!compareStringInsensitive($request,"GetStyles") AND !compareStringInsensitive($request,"DescribeLayer") AND !compareStringInsensitive($request,"GetLegendGraphic") AND
!compareStringInsensitive($request,"GetMap25D") AND !compareStringInsensitive($request,"GetMap3D") AND $request != "") {
    $erroroccured = false;
    $errornumber = 33;
    $errorexceptionstring = "The REQUEST " . $request . " is not supported by the server. The supported Requests are GetMap, GetCapabilities, GetFeatureInfo, GetStyles, GetLegendGraphic, DescribeLayer, GetMap25D and GetMap3D. " ;
}
if ($request == "") {
    $erroroccured = false;
    $errornumber = 32;
    $errorexceptionstring = "The REQUEST has not been given. The supported Requests are GetMap, GetCapabilities, GetFeatureInfo, GetStyles, GetLegendGraphic and DescribeLayer. " ;
}
// check version error
if (!compareStringInsensitive($serversion,$wmsversion) AND $serversion != "") {
    $erroroccured = false;
    $errornumber = 20;
    $errorexceptionstring = "Invalid version number " . $serversion . " given. The supported version number is VERSION = " . $wmsversion ;
}
if ($serversion == "") {
    $erroroccured = false;
    $errornumber = 21;
    $errorexceptionstring = "Version number has not been given. The supported version number is VERSION = " . $wmsversion ;
}
if (!compareStringInsensitive($serservice,$wmsservice) AND $serservice != "") {
    $erroroccured = false;
    $errornumber = 1;
    $errorexceptionstring = " " . $serservice . " is not supported by the server. The only  service available is " . $wmsservice ;
}
if ($serservice == "") {
    $erroroccured = false;
    $errornumber = 21;
    $errorexceptionstring = "SERVICE has not been given. The supported SERVICE is " . $wmsservice ;
}
if (!compareStringInsensitive($exceptions,"application/vnd.ogc.se_xml") AND !compareStringInsensitive($exceptions,"application/vnd.ogc.se_inimage")) {
    $erroroccured = false;
    $errornumber = 3;
    $errorexceptionstring = " " . $exceptions . " is not supported by the server. The only  service available is application/vnd.ogc.se_xml and application/vnd.ogc.se_inimage." ;
}
if ($errornumber != 0) {
    $sendexceptionclass->sendexception($errornumber, $errorexceptionstring);
}
else{

    if (compareStringInsensitive($request,"GetMap") OR compareStringInsensitive($request,"GetMap25D") OR compareStringInsensitive($request,"GetMap3D")) {
        if(compareStringInsensitive($request,"GetMap25D")){
		    $distance = ($maxy - $miny)*1.2;
		    define("blnGetMap25D", true);
		}
        else
		    define("blnGetMap25D", false);

        include 'GetMap.class.php';
    }

    if (compareStringInsensitive($request,"GetCapabilities")) {
        include 'GetCapabilities.class.php';
    }

    if (compareStringInsensitive($request,"GetFeatureInfo")) {
        include 'GetFeatureInfo.class.php';
    }

    if (compareStringInsensitive($request,"DescribeLayer")) {
        include 'DescribeLayer.class.php';
    }

    if (compareStringInsensitive($request,"GetLegendGraphic")) {
        include 'GetLegendGraphic.class.php';
    }

    if (compareStringInsensitive($request,"GetStyles")) {
        include 'GetStyles.class.php';
    }

}

?>