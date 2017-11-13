<?php
/**
 * Getmap RasterImage Class
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

require '../Render/WKTParser.class.php';
require '../Render/Path2Point.class.php';
require '../Render/VRMLRender.class.php';
require '../Models/RasterColor.class.php';
include '../Parser/StyleReader.class.php';
include_once '../Render/GZip.class.php';
include_once '../Models/CommonFunction.class.php';


if (compareStringInsensitive($format, "model/vrmlz")) {
        $gz = new gzip();
        $gzip = true;
    }
if ($enablecache) {
    if (compareStringInsensitive($format, "model/vrml")) {
        $cache = new Cache($cacheLimitTime, 9);
        $cache->cacheCheck();
    }
    if (compareStringInsensitive($format, "model/vrmlz")) {
        $cache = new Cache($cacheLimitTime, 10);
        $cache->cacheCheck();
    }
}

setConnectionTime($maxTimeOutLimit);

$database = new Database();
$vrml = new VRMLRender();

$database->databaseConfig($servername, $username, $password, $dbname);
$database->databaseConnect();

if ($database->databaseGetErrorMessage() != "") {
    $errornumber = -1;
    $errorexceptionstring = $database->databaseGetErrorMessage();
    $sendexceptionclass->sendexception($errornumber, $errorexceptionstring);
}
if ($errornumber == 0) {
    /*if ( $style  != "default" )
	    {
				   $erroroccured = false;
				 	 $errornumber = 0;
					 $errorexceptionstring = "Invalid Style name " .$style." given. There is only one \"default\" style name " ;
	    }*/
    if ($width == "" OR $height == "" OR $width == 0 OR $height == 0) {
        $erroroccured = false;
        $errornumber = 13;
        $errorexceptionstring = "The width OR the height cannot be empty or zero." ;
    }
    if ($maxx <= $minx OR $maxy <= $miny) {
        $erroroccured = false;
        $errornumber = 15;
        $errorexceptionstring = "The BBox parameters are not valid." ;
        $sendexceptionclass->sendexception($errornumber, $errorexceptionstring);
    }

    // get bbox
    $bboxvalues = explode(",", $bbox);
    $numberofvalues = count($bboxvalues);
    $minx = $bboxvalues[0];
    $miny = $bboxvalues[1];
    $maxx = $bboxvalues[2];
    $maxy = $bboxvalues[3];

    $rs5 = $database->getRowsBySrsGroupBy($tbmetaname, $srs, "layer");
    $num = $database->getColumnsNumber($rs5);

    $layersvalues = explode(",", $layers);
    $numberofvalueslayer = count($layersvalues);
    // sort the layers according its priority
    $arrayPriority = $database->getPriorityArray($tbmetaname, $srs, $layersvalues);
    $layersvalues = sortLayer($layersvalues, $arrayPriority);
    $elevationsvalues = sortLayer($elevationsvalues, $arrayPriority);

    for ($i0 = 0; $i0 < $numberofvalueslayer; $i0++) {
        // $rs1 = $database->getRowsInBboxBySrsLayer($tbmetaname, $minx, $miny, $maxx, $maxy, $srs, $layersvalues[$i0]);
        $rs2 = $database->getRowsBySrsLayer($tbmetaname, $srs, $layersvalues[$i0]);
        $line2 = $database->getColumns($rs2);

        $rs7 = $database->getRowsMinMaxXYBySrs($tbmetaname, $srs);
        $line7 = $database->getColumns($rs7);
        $totalminx = $line7[0];
        $totalminy = $line7[1];
        $totalmaxx = $line7[2];
        $totalmaxy = $line7[3];
        // check BBox error
        if ($maxx < $totalminx OR $miny > $totalmaxy OR $minx > $totalmaxx OR $maxy < $totalminy) {
            $erroroccured = false;
            $errornumber = 1;
            $errorexceptionstring = "Invalid bounding box coordinates for SRS =" . $srs . ". Easting must be between " . $totalminx . " and " . $totalmaxx . " AND Northing  must be between " . $totalminy . " and " . $totalmaxy . ".";
            $sendexceptionclass->sendexception($errornumber, $errorexceptionstring);
		}
        // check layer and SRS error
        if ($line2 == null AND $layers != "" AND $srs != "") {
            $erroroccured = false;
            $errornumber = 2;
            $errorexceptionstring = "LayerNotDefined.Layer " . $layersvalues[$i0] . " with SRS " . $srs . " not found, Please check your Layer name and/or SRS.";
            $sendexceptionclass->sendexception($errornumber, $errorexceptionstring);
        }
        // }
        else if ($line2 == null AND $layers == "") {
            $erroroccured = false;
            $errornumber = 4;
            $errorexceptionstring = "Layer " . $layersvalues[$i0] . " not specified. Please insert  Layer names!";
            $sendexceptionclass->sendexception($errornumber, $errorexceptionstring);
        } else if ($line2 == null AND $srs == "") {
            $erroroccured = false;
            $errornumber = 5;
            $errorexceptionstring = "SRS " . $srs . " not specified. Please insert  valid SRS!";
            $sendexceptionclass->sendexception($errornumber, $errorexceptionstring);
        }
    } // end  of for ($i0=0; $i0 < $numberofvalueslayer; $i0++)
}
if ($errornumber == 0)  {
    /**
     * *Coordinate transform
     */
    $size = getStretchWidthHeight($minx, $miny, $maxx, $maxy, $width, $height, $enablestretchmap);
    $width = $size[0];
    $height = $size[1];

    /**
     * *Begin to create Vrml Image
     */
    if ($skycolor != "") {
        $rastercolorskycolor = new RasterColor($skycolor);
        $color_skycolor = ($rastercolorskycolor->setRGB_R / 255) . " " . ($rastercolorskycolor->setRGB_G / 255) . " " . ($rastercolorskycolor->setRGB_B / 255);
    }
    if ($bgcolor != "") {
        $rastercolorbgcolor = new RasterColor($bgcolor);
        $color_bgcolor = ($rastercolorbgcolor->setRGB_R / 255) . " " . ($rastercolorbgcolor->setRGB_G / 255) . " " . ($rastercolorbgcolor->setRGB_B / 255);
    }

    $vrml->setRender($minx, $miny, $maxx, $maxy, $width, $height,
        $poix, $poiy, $poiz, $pitch , $yaw, $roll, $distance, $aov, $environment, $color_skycolor, $color_bgcolor, $bgimage,
        $enablestretchmap,$gzip);


    $styleparser = new StyleParser();
    $styleparser->prefix = $dbname . $tableprefix;
    $aryXmlUserLayerNode = $styleparser->createStyleNode4layer();

    for ($j = 0; $j < $numberofvalueslayer; $j++) {
        $layerelevation = $elevationsvalues[$j];
        $rs2_ = $database->getRowsBySrsLayer($tbmetaname, $srs, $layersvalues[$j]);
        $line2_ = $database->getColumns($rs2_);
        $data_Layer_Type = strtoupper($line2_["layertype"]);

        $xmlFillColor == "-1";
        $xmlMinScaleDenominator = "";
        $xmlMaxScaleDenominator = "";

        $styleparser->getLayerStyleFromStyleNode($layersvalues[$j], $data_Layer_Type, $aryXmlUserLayerNode);

        $xmlMinScaleDenominator = formatScale($styleparser->xmlMinScaleDenominator);
        $xmlMaxScaleDenominator = formatScale($styleparser->xmlMaxScaleDenominator);
        // For point,linestring,polygon,,text
        $xmlSize = $styleparser->xmlSize;
        // For point,polygon,text,linestring
        $xmlFillColor = $styleparser->xmlFillColor;
        // For linestring,polygon
        $xmlStrokeColor = $styleparser->xmlStrokeColor;
        // For text
        $xmlFont = $styleparser->xmlFont;
        // For point
        $xmlWellKnownName = strtoupper($styleparser->xmlWellKnownName);
        // For linestring, point
        $xmlStrokeOpacity = ($styleparser->xmlStrokeOpacity) / 100;
        // For polygon, point, image
        $xmlFillOpacity = ($styleparser->xmlFillOpacity) / 100;
        // For text
        $xmlFontStyle = $styleparser->xmlFontStyle;
        $xmlFontWeight = $styleparser->xmlFontWeight;
        // For linestring
        $xmlLineJoin = $styleparser->xmlLineJoin;
        $xmlLineCap = $styleparser->xmlLineCap;
        // only use for linestring
        if ($xmlFillColor == "-1")
            $blnFillLineString = 0;
        else $blnFillLineString = 1;

        $rastercolorFillColor = new RasterColor($xmlFillColor);
        $color_FillColor = Round(($rastercolorFillColor->setRGB_R / 255),4) . " " . Round(($rastercolorFillColor->setRGB_G / 255),4) . " " . Round(($rastercolorFillColor->setRGB_B / 255),4);

        $rastercolorStrokeColor = new RasterColor($xmlStrokeColor);
        $color_StrokeColor = Round(($rastercolorStrokeColor->setRGB_R / 255),4) . " " . Round(($rastercolorStrokeColor->setRGB_G / 255),4) . " " . Round(($rastercolorStrokeColor->setRGB_B / 255),4);

        /**
         * Control the display range/ scale
         */
        $showlayer = false;
        if ($xmlMinScaleDenominator != "" OR $xmlMaxScaleDenominator != "") {
            if ($xmlMinScaleDenominator == "") $xmlMinScaleDenominator = 1;
            if ($xmlMaxScaleDenominator == "") $xmlMaxScaleDenominator = 900000000;

            $currentScale = getCurrentScale($maxx - $minx, $width);
            if ($xmlMinScaleDenominator <= $currentScale AND $currentScale <= $xmlMaxScaleDenominator)
                $showlayer = true;
        } elseif ($xmlMinScaleDenominator == "" AND $xmlMaxScaleDenominator == "") {
            $showlayer = true;
        }

        if ($showlayer) {
            $rs1_ = $database->getGeomAsTextInBboxBySrsLayer($tbname, $minx, $miny, $maxx, $maxy, $srs, $layersvalues[$j]);

            while ($line1_ = $database->getColumns($rs1_)) {
                $data_id = $database->getIdFromRS($line1_);
                $data_Recid = $database->getRecidFromRS($line1_);;
                if ($data_Recid == "") $data_Recid = $data_id;

                $data_Geom = $database->getGeometryTextFromRS($line1_);
                // echo $data_Geom."ha\n";
                $data_Style = $database->getStyleFromRS($line1_);
                $data_Geom_Type = $database->getGeomtypeFromRS($line1_);
                // only for image, the svgxlink has the image source path!
                $data_ImageLink = $database->getSvgxlinkFromRS($line1_);
                // ======================================================================================================
                // ======================================================================================================
                switch ($data_Geom_Type) {
                    case 'POINT': {
                            $wktparser = new WKTParser();
                            $wktparser->parse($data_Geom);
                            // readstyle now
                            // $sld->poinStyle = "CIRCLE";
                            $data_x = null;
                            $data_y = null;

                            $data_x = $wktparser->wktPointX;
                            $data_y = $wktparser->wktPointY;
                            $Number_Point = $wktparser->wktPointNr;

                            $po = new Point();
                            $poi = $po->CreatePoint($data_x, $data_y,$layerelevation, $Number_Point, $xmlWellKnownName, $color_FillColor , $xmlSize);
                            for($i = 0;$i < count($poi);$i++) {
                                $vrml->addNode($poi[$i]);
                            }
                            $poi = null;
                        }
                        break;
                    // ======================================================================================
                    // ======================================================================================
                    case 'TEXT': {
                            $wktparser = new WKTParser();
                            $wktparser->parse($data_Geom); //Point type, only one point!
                            // readstyle now
                            // $sld->poinStyle = "CIRCLE";
                            $data_x = null;
                            $data_y = null;

                            $data_x = $wktparser->wktPointX;
                            $data_y = $wktparser->wktPointY;
                            $Number_Point = $wktparser->wktPointNr;
                            $text = getTextAngel($database->getAttributesFromRS($line1_));
                            $data_TextContent = $text[0];
                            $angle = $text[1];

                            switch ($wktparser->wktGeomType) {
                                case "POINT": {
                                        // only one point!!!
                                        $ft = new FreeText();
                                        $cft = $ft->CreateFreeText($data_x[0], $data_y[0], $layerelevation, $data_TextContent, $xmlFont, $xmlSize, $angle, $color_FillColor);
                                        $vrml->addNode($cft);
                                        $cft = null;
                                    }
                                    break;
                                case "LINESTRING": {
                                        $ft = new FreeText();
                                        $cft = $ft->CreateFreeText($data_x[0], $data_y[0], $layerelevation, $data_TextContent, $xmlFont, $xmlSize, $angle, $color_FillColor);
                                        $vrml->addNode($cft);
                                        $cft = null;
                                    }
                            }
                        }
                        break;
                    // ======================================================================================================
                    // ======================================================================================================
                    case 'LINESTRING': {
                            $wktparser = new WKTParser();
                            $wktparser->parse($data_Geom);
                            // very important!!!!!!
                            $data_x = null;
                            $data_y = null;

                            switch ($wktparser->wktGeomType) {
                                case "LINESTRING": {
                                        $data_x = array(0 =>$wktparser->wktPointX);
                                        $data_y = array(0 =>$wktparser->wktPointY);
                                        $MNumber_Point = array(0 => $wktparser->wktPointNr);
                                        $MLine_Point = 1;

                                        $ml = new MultiLinstring();
                                        $ls = $ml->createMultiLinstring($data_x, $data_y, $MLine_Point, $MNumber_Point, $layerelevation, $color_StrokeColor, $xmlSize, $color_FillColor, $blnFillLineString);
                                        for($i = 0;$i < count($ls);$i++) {
                                            $vrml->addNode($ls[$i]);
                                        }
                                        $ls = null;
                                    }
                                    break;
                                case "MULTILINESTRING": {
                                        $data_x = $wktparser->wktMPointX;
                                        $data_y = $wktparser->wktMPointY;
                                        $MNumber_Point = $wktparser->wktMPointNr;
                                        $MLine_Point = $wktparser->wktMLineNr;

                                        $ml = new MultiLinstring();
                                        $mls = $ml->createMultiLinstring($data_x, $data_y, $MLine_Point, $MNumber_Point, $layerelevation, $color_StrokeColor, $xmlSize, $color_FillColor, $blnFillLineString);
                                        for($i = 0;$i < count($mls);$i++) {
                                            $vrml->addNode($mls[$i]);
                                        }
                                        $mls = null;
                                    }
                            }
                        }
                        break;
                    // ======================================================================================================
                    // ======================================================================================================
                    case 'POLYGON': {
                            $wktparser = new WKTParser();
                            $wktparser->parse($data_Geom);
                            // very important!!!!!!
                            $data_x = null;
                            $data_y = null;

                            switch ($wktparser->wktGeomType) {
                                case "POLYGON": {
                                        $data_x = $wktparser->wktMPointX;
                                        $data_y = $wktparser->wktMPointY;
                                        $MNumber_Point = $wktparser->wktMPointNr;
                                        $MLine_Point = $wktparser->wktMLineNr;
                                        $mp = new MultiPolygon();
                                        $pl = $mp->createMultiPolygon($data_x, $data_y, $MLine_Point, $MNumber_Point, $layerelevation, $color_FillColor, $xmlSize);
                                        for($i = 0;$i < count($pl);$i++) {
                                            $vrml->addNode($pl[$i]);
                                        }
                                    }
                                    break;
                                case "MULTIPOLYGON": {
                                        $data_x = $wktparser->wktMPointX;
                                        $data_y = $wktparser->wktMPointY;
                                        $MNumber_Point = $wktparser->wktMPointNr;
                                        $MLine_Point = $wktparser->wktMLineNr;
                                        $mp = new MultiPolygon();
                                        $mpl = $mp->createMultiPolygon($data_x, $data_y, $MLine_Point, $MNumber_Point, $layerelevation, $color_FillColor, $xmlSize);
                                        for($i = 0;$i < count($mpl);$i++) {
                                            $vrml->addNode($mpl[$i]);
                                        }
                                    }
                            }
                        }
                        break;
                    // ======================================================================================================
                    // ======================================================================================================
                    case 'IMAGE': {
                            $wktparser = new WKTParser();
                            $wktparser->parse($data_Geom);
                            // very important!!!!!!
                            $data_x = null;
                            $data_y = null;

                            $data_x = $wktparser->wktPointX;
                            $data_y = $wktparser->wktPointY;
                            $Number_Point = $wktparser->wktPointNr;
                            // $rasterimagerender->createImage($data_x, $data_y, $Number_Point, $data_ImageLink, $xmlFillOpacity, $color_StrokeColor);
                            $im = new Image();
                            $newim = $im->createImage($data_x, $data_y, $layerelevation, $Number_Point, $data_ImageLink, $xmlFillOpacity, $color_StrokeColor);
                            $vrml->addNode($newim);
                        }
                        break;
                    // ======================================================================================================
                    // ======================================================================================================
                    case 'UNKNOWN': {
                        }
                        // ======================================================================================================
                        // ======================================================================================================
                } //switchs
            } //while
        }
    } // end of for ($j=0; $j < $numberofvalueslayer; $j++)

    if (compareStringInsensitive($format, "model/vrml")) {
        header("Content-Type:model/vrml");
        $vrml->generate();
    }
    if (compareStringInsensitive($format, "model/vrmlz")) {
        header("Content-Type:model/vrmlz");
        $vrml->generate();
        $gz->add($vrml->getfinaloutput(), "");
        $gz->print_file();
    }
    // }
    $database->databaseClose();
    if ($enablecache == 1) {
        $cache->caching();
    }
} // $errornumber == 0
exit();
?>
