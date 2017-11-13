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
 * @version $3.0$ 2006
 * @Author leelight
 * @Contact webmaster@easywms.com
 */

include '../config.php';
include_once 'SendException.class.php';
include_once '../Models/CommonFunction.class.php';
require_once '../Models/Cache.class.php';

$errornumber = 0;
$borderWidth1 = 0;
$borderWidth2 = 0;


/*   : = %3a   / = %2f   @ = %40
 *   + = %2b   ( = %28   ) = %29
 *   ? = %3f   = = %3d   & = %26
*/
$assalowarray = array("%3a", "%2f", "%40", "%2b", "%28", "%29", "%3f", "%3d", "%26");
$assaupperarray = array("%3A", "%2F", "%40", "%2B", "%28", "%29", "%3F", "%3D", "%26");
$chararray = array(":", "/", "@", "+", "(", ")", "?", "=", "&");

$QUERY_STRING = $_SERVER ['QUERY_STRING'];
$QUERY_STRING = str_replace($assalowarray, $chararray, $QUERY_STRING);
$QUERY_STRING = str_replace($assaupperarray, $chararray, $QUERY_STRING);
$a = explode('&', $QUERY_STRING);
$i = 0;
while ($i < count($a)) {
    $b = split('=', $a[$i]);
    // echo "Value for parameter " .$b[0].
    // "is " .$b[1]. "\n";
    $text_upper = strtoupper($b[0]);
    // $equal  = strncasecmp("REQUEST","request",7);
    // change $b[0] to upper case
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
        // $format  = html_entity_decode(urldecode($b[1]));
        $format = $b[1];
        // $format  = urldecode($b[1]);
        // $format  = htmlentities(urlencode($b[1]));
        // $format = htmlspecialchars(urldecode($b[1]));
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
    if ($text_upper == "OUTPUTFORMAT") {
        $outputformat = htmlspecialchars(urldecode($b[1]));
    }
    if ($text_upper == "TYPENAME") {
        $typename = htmlspecialchars(urldecode($b[1]));
    }
    if ($text_upper == "MAXFEATURES") {
        $maxfeatures = htmlspecialchars(urldecode($b[1]));
    }

    $i++;
}
$serverhost = $wmsmetadata['ServerHost'];
$sendexceptionclass = new SendExceptionClass($serverhost, $wfsservice, $wfsversion);

/**
 * *THE SEND EXCEPTION FUNCTION
 */
// include 'SendException.php';
// THE DIFFERENT REQUEST
if (!compareStringInsensitive($request,"GetCapabilities") AND !compareStringInsensitive($request,"DescribeFeatureType") AND !compareStringInsensitive($request,"GetFeature")
AND !compareStringInsensitive($request,"GetGmlObject") AND !compareStringInsensitive($request,"Transaction") AND $request != "") {
    $erroroccured = false;
    $errornumber = 33;
    $errorexceptionstring = "The REQUEST " . $request . " is not supported by the server. The only supported Requests are GetCapabilities, DescribeFeatureType, GetFeature, GetGmlObject and Transaction. " ;
}
if ($request == "") {
    $erroroccured = false;
    $errornumber = 32;
    $errorexceptionstring = "The REQUEST has not been given. The only supported Requests are GetCapabilities, DescribeFeatureType, GetFeature, GetGmlObject and Transaction. " ;
}
// check version error
if (!compareStringInsensitive($serversion,$wfsversion)  AND $serversion != "") {
    $erroroccured = false;
    $errornumber = 20;
    $errorexceptionstring = "Invalid version number " . $serversion . " given. The only supported version number is VERSION = " . $wfsversion ;
}
if ($serversion == "") {
    $erroroccured = false;
    $errornumber = 21;
    $errorexceptionstring = "Version number has not been given. The supported version number is VERSION = " . $wfsversion ;
}
if (!compareStringInsensitive($serservice,$wfsservice) AND $serservice != "") {
    $erroroccured = false;
    $errornumber = 1;
    $errorexceptionstring = " " . $serservice . " is not supported by the server. The only  service available is " . $wfsservice ;
}
if ($serservice == "") {
    $erroroccured = false;
    $errornumber = 21;
    $errorexceptionstring = "SERVICE has not been given. The supported SERVICE is " . $wfsservice ;
}
if ($errornumber != 0) {
    $sendexceptionclass->sendexception($errornumber, $errorexceptionstring);
}
else{
    /**
     * *THE GET CAPABILITY REQUEST
     */
    if (compareStringInsensitive($request,"GetCapabilities")) {
        include 'GetCapabilities.class.php';
    }

    /**
     * *THE DescribeFeatureType REQUEST
     */
    if (compareStringInsensitive($request,"DescribeFeatureType")) {
        include 'DescribeFeatureType.class.php';
    }

    /**
     * *THE GetFeature REQUEST
     */
    if (compareStringInsensitive($request,"GetFeature")) {
        include 'GetFeature.class.php';
    }
}

?>