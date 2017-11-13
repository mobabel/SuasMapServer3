<?php

/**
 *
 * @version $Id$
 * @copyright 2007
 */


define("mapTableFeaturegeometry", "featuregeometry");
define("mapTableFeatureclass", "featureclass");
define("mapTableFieldsFeaturegeometry", "id|layer|recid|geomtype|xmin|ymin|xmax|ymax|geom|svgxlink|srs|attributes|style");

define("GeometryTypePoint", "Point");
define("GeometryTypeLineString", "LineString");
define("GeometryTypePolygon", "Polygon");
define("GeometryTypeText", "Text");
define("GeometryTypeImage", "Image");
define("GeometryTypeUnknown", "Unknown");

//not use now
define("GeometryTypeMultiPoint", "MultiPoint");
define("GeometryTypeMultiLineString", "MultiLineString");
define("GeometryTypeMultiPolygon", "MultiPolygon");
define("GeometryTypeGeometryCollection", "GeometryCollection");
define("GeometryTypeMultiSurface", "MultiSurface");
define("GeometryTypeCurve", "Curve");
define("GeometryTypeMultiCurve", "MultiCurve");


define("StyleDefault", "Default");
define("SRSNotDefined", "SRS_not_defined");


$params['GetLegendGraphicWidth'] = 20;
$params['GetLegendGraphicHeight'] = 15;

$params['GetImageDefaultWidth'] = 400;
$params['GetImageDefaultHeight'] = 400;

$params['GetFeatureInfoRedius'] = 2;

/*
*The array of supportted outputted map formats
*/
$format['svg'] = 'image/svg+xml';
$format['svgt'] = 'image/svgt+xml';
$format['svgb'] = 'image/svgb+xml';
$format['svgz'] = 'image/svgz';
$format['pdf'] = 'image/pdf';
$format['ezpdf'] = 'image/ezpdf';
$format['swf'] = 'image/swf';
$format['vml'] = 'image/vml';

$format['vrml'] = 'model/vrml';
$format['vrmlz'] = 'model/vrmlz';
$format['x3d'] = 'model/x3d+xml';
$format['kml'] = 'application/vnd.google-earth.kml+xml';
$format['kmz'] = 'application/vnd.google-earth.kmz';

$format['png'] = 'image/png';
$format['jpeg'] = 'image/jpeg';
$format['gif'] = 'image/gif';
$format['wbmp'] = 'image/wbmp';
$format['bmp'] = 'image/bmp';

$format['xml'] = 'text/xml;charset=utf-8';

$elementtype["C"] = "string";
$elementtype["N"] = "double";
//$elementtype = array('C'=>"string", 'N'=>"double");

?>