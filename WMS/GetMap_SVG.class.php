<?php
/**
 * Getmap SVG Class
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
 * @copyright (C) 2006-2007  LI Hui
 * @Description : This show the copyright .
 * @contact webmaster@easywms.com
 * @version $1.0$ 2005
 * @Author Filmon Mehari and Professor Dr. Franz Josef Behr
 * @Contact filmon44@yahoo.com and franz-josef.behr@hft-stuttgart.de
 * @version $2.0$ 2006.05
 * @Author Chen Hang and LI Hui
 * @Contact unitony1980@hotmail.com
 * @version $3.0$ 2006-now
 * @Author LI Hui
 * @Contact webmaster@easywms.com
 */
// include_once '../global.php';
include_once '../Render/WKTParser.class.php';
include '../Parser/StyleReader.class.php';
include '../Models/CreateStyle4SVG.php';
include_once '../Models/CommonFunction.class.php';
require_once '../Render/SVGRender.class.php';

setConnectionTime($maxTimeOutLimit);

if (enableStreamSVG) {
    require_once '../Render/SVGStreamRender.class.php';
}

if ($enablecache) {
    $cache = new Cache($cacheLimitTime, 0);
    $cache->cacheCheck();
}

$database = new Database();

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

    if ($width == "" OR $height == "") {
        $erroroccured = false;
        $errornumber = 13;
        $errorexceptionstring = "The width OR the height cannot be empty. " ;
        $sendexceptionclass->sendexception($errornumber, $errorexceptionstring);
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
    for ($i = 0; $i < $numberofvalues; $i++) {
    }
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

    for ($i = 0; $i < $numberofvalueslayer; $i++) {
        $rs2 = $database->getRowsBySrsLayer($tbmetaname, $srs, $layersvalues[$i]);
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
            $errorexceptionstring = "LayerNotDefined.Layer " . $layersvalues[$i] . " with SRS " . $srs . " not found, Please check your Layer name and/or SRS.";
            $sendexceptionclass->sendexception($errornumber, $errorexceptionstring);
        } else if ($line2 == null AND $layers == "") {
            $erroroccured = false;
            $errornumber = 4;
            $errorexceptionstring = "Layer " . $layersvalues[$i] . " not specified. Please insert  Layer names!";
            $sendexceptionclass->sendexception($errornumber, $errorexceptionstring);
        } else if ($line2 == null AND $srs == "") {
            $erroroccured = false;
            $errornumber = 5;
            $errorexceptionstring = "SRS " . $srs . " not specified. Please insert  valid SRS!";
            $sendexceptionclass->sendexception($errornumber, $errorexceptionstring);
        }
        $data2 = $line2["style"];
        $data3 = $line2["srs"];
        $data4 = $line2["layer"];
    } // end  of for ($i=0; $i < $numberofvalueslayer; $i++)
}
if ($errornumber == 0) {
    /**
     * *Coordinate transform
     */
    $size = getStretchWidthHeight($minx, $miny, $maxx, $maxy, $width, $height, $enablestretchmap);
    $width = $size[0];
    $height = $size[1];

    header("Content-Type: image/svg+xml");
    // use DOM
    if (!enableStreamSVG) {
        if ($enableSVGPixelCoordinate) {
            $coord = getPixelXYFromRealConverse($minx, $miny, $maxx, $maxy, $width, $height, $minx, $miny, $enablestretchmap);
            $minxr = $coord[0];
            $minyr = $coord[1];
            $coord1 = getPixelXYFromRealConverse($minx, $miny, $maxx, $maxy, $width, $height, $maxx, $maxy, $enablestretchmap);
            $maxxr = $coord1[0];
            $maxyr = $coord1[1];
            $svg = &new SvgDocument($width, $height, $minxr, $minyr, $maxxr, $maxyr, "");
        } else {
            $svg = &new SvgDocument($width, $height, $minx, $miny, $maxx, $maxy, "");
        }
        $desc = new SvgDesc("Generator: $softName\n" . "Copyright(C)" . $softName . $softVersion . $softEdition, "");
        $svg->addChild($desc);
        if ($showCopyright == 1) {
            if ($enableSVGPixelCoordinate)
                $textcopyright = new SvgText("", $minxr, - $minyr, "Copyright(C)" . $softName . $softVersion . $softEdition,
                    "fill:red;font-size:10px;text-anchor:begin;font-rendering:optimizeLegibility;pointer-events:none", "");
            else
                $textcopyright = new SvgText("", $minx, - $miny, "Copyright(C)" . $softName . $softVersion . $softEdition,
                    "fill:red;font-size:10px;text-anchor:begin;font-rendering:optimizeLegibility;pointer-events:none", "");

            $svg->addChild($textcopyright);
        }
    } else { // use Stream
        $svgz = new SVGStreamRender(1);
        $svgz->setSVGStreamRender($minx, $miny, $maxx, $maxy, $width, $height, $enablestretchmap, $enableSVGPixelCoordinate,$hangle,$vangle,$distance);

        $svgz->SvgDocument();
        $svgz->SvgFragmentBegin();
        $svgz->SvgDesc("Generator: $softName\n" . "Copyright(C)" . $softName . $softVersion . $softEdition);

        if ($showCopyright == 1) {
            $svgz->SvgText("", $minx, - $miny, "Copyright(C)" . $softName . $softVersion . $softEdition,
                "fill:red;font-size:10px;text-anchor:begin;font-rendering:optimizeLegibility;pointer-events:none", "");
        }
    }
    // $g =& new SvgGroup("test", "", "", "");
    // $svg->addChild($g);
    // $svg->printElement();
    $styleparser = new StyleParser();
    $styleparser->prefix = $dbname . $tableprefix;
    $aryXmlUserLayerNode = $styleparser->createStyleNode4layer();

    $flagPoint = 0;
    $flagText = 0;
    $flagLinestring = 0;
    $flagPolygon = 0;
    $flagImage = 0;
    $flagUnknown = 0;

    for ($ii = 0; $ii < $numberofvalueslayer; $ii++) {
        $rs2_ = $database->getRowsBySrsLayer($tbmetaname, $srs, $layersvalues[$ii]);
        $line2_ = $database->getColumns($rs2_);
        $data_Layer_Type = strtoupper($line2_["layertype"]);

        $styleparser->getLayerStyleFromStyleNode($layersvalues[$ii], $data_Layer_Type, $aryXmlUserLayerNode);

        $xmlMinScaleDenominator = "";
        $xmlMaxScaleDenominator = "";
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
        // For polygon, point, image,linestring
        $xmlFillOpacity = ($styleparser->xmlFillOpacity) / 100;
        // For text
        $xmlFontStyle = $styleparser->xmlFontStyle;
        $xmlFontWeight = $styleparser->xmlFontWeight;
        // For linestring
        $xmlLineJoin = $styleparser->xmlLineJoin;
        $xmlLineCap = $styleparser->xmlLineCap;

        $style4eachlayer = createStyle4SVG($data_Layer_Type, $xmlSize, $xmlFillColor, $xmlStrokeColor, $xmlFont,
            $xmlWellKnownName, $xmlStrokeOpacity, $xmlFillOpacity, $xmlFontStyle,
            $xmlFontWeight, $xmlLineJoin, $xmlLineCap);

        $data_style = $line2_["style"];
        $data_srs = $line2_["srs"];
        $data_geomtype = strtoupper($line2_["geomtype"]);
        $data_attributes = strtoupper($line2_["attributes"]);

        /*
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
            // justify the spatial relationship
            $rs1_ = $database->getGeomAsTextInBboxBySrsLayer($tbname, $minx, $miny, $maxx, $maxy, $srs, $layersvalues[$ii]);
            if (!enableStreamSVG) {
                $g[$ii] = &new SvgGroup($layersvalues[$ii], $style4eachlayer, "", "");
                $svg->addChild($g[$ii]);
            } else {
                $svgz->SvgGroupBegin($layersvalues[$ii], $style4eachlayer, "", "");
            }
            // $g[$flagGroup]->addParent($svg);
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
                            // <circle cx="3562592.6" cy="-5519511.68" r = "1046.83" />
                            // $xml=new SVGParser($data_Geom);
                            $wktparser = new WKTParser();
                            $wktparser->parse($data_Geom);
                            // readstyle now
                            // $sld->poinStyle = "CIRCLE";
                            $data_x = null;
                            $data_y = null;

                            $data_x = $wktparser->wktPointX;
                            $data_y = $wktparser->wktPointY;
                            $Number_Point = $wktparser->wktPointNr;
                            // $rasterimagerender->createLinstring($data_x, $data_y, $Number_Point, $color_blue , 2);
                            switch ($wktparser->wktGeomType) {
                                case "POINT": {
                                        if (!enableStreamSVG) {
                                            if ($enableSVGPixelCoordinate) {
                                                $coord = getPixelXYFromRealConverse($minx, $miny, $maxx, $maxy, $width, $height, $data_x[0], $data_y[0], $enablestretchmap);
                                                $data_x[0] = $coord[0];
                                                $data_y[0] = $coord[1];
                                            }
                                            $svgpoint[$flagPoint] = new SvgPoint($data_Recid, $data_x[0], - $data_y[0], $xmlSize, $xmlWellKnownName, "", "");
                                            $g[$ii]->addChild($svgpoint[$flagPoint]);

                                            $flagPoint++;
                                        } else {
                                            $svgz->SvgPoint($data_Recid, $data_x[0], - $data_y[0], $xmlSize, "", "", $xmlWellKnownName);
                                        }
                                    }
                                    break;
                                case "MULTIPOINT": {
                                        if (!enableStreamSVG) {
                                            for($i = 0;$i < $MLine_Point;$i++) {
                                                if ($enableSVGPixelCoordinate) {
                                                    $coord = getPixelXYFromRealConverse($minx, $miny, $maxx, $maxy, $width, $height, $data_x[$i], $data_y[$i], $enablestretchmap);
                                                    $data_x[$i] = $coord[0];
                                                    $data_y[$i] = $coord[1];
                                                }
                                                $svgpoint[$flagPoint] = new SvgPoint($data_Recid, $data_x[0], - $data_y[0], $xmlSize, $xmlWellKnownName, "", "");
                                                $g[$ii]->addChild($svgpoint[$flagPoint]);
                                                $flagPoint++;
                                            }
                                        } else {
                                            $svgz->SvgMultiPoint($data_Recid, $data_x, - $data_y, $xmlSize, $Number_Point, "", "", $xmlWellKnownName);
                                        }
                                    }
                            }
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
                                        if (!enableStreamSVG) {
                                            if ($enableSVGPixelCoordinate) {
                                                $coord = getPixelXYFromRealConverse($minx, $miny, $maxx, $maxy, $width, $height, $data_x[0], $data_y[0], $enablestretchmap);
                                                $data_x[0] = $coord[0];
                                                $data_y[0] = $coord[1];
                                            }
                                            $svgtext[$flagText] = new SvgText($data_Recid, $data_x[0], -$data_y[0], $data_TextContent, "", "");
                                            $g[$ii]->addChild($svgtext[$flagText]);

                                            $flagText++;
                                        } else {
                                            $svgz->SvgText($data_Recid, $data_x[0], $data_y[0], $data_TextContent, "", "");
                                        }
                                    }
                                    break;
                                case "LINESTRING": {
                                        if (!enableStreamSVG) {
                                            $pathD = "";
                                            for($i = 0;$i < $Number_Point;$i++) {
                                                if ($enableSVGPixelCoordinate) {
                                                    $coord = getPixelXYFromRealConverse($minx, $miny, $maxx, $maxy, $width, $height, $data_x[$i], $data_y[$i], $enablestretchmap);
                                                    $data_x[$i] = $coord[0];
                                                    $data_y[$i] = $coord[1];
                                                }
                                                if ($i < $Number_Point-1) {
                                                    if ($i == 0) {
                                                        $pathD .= "M" . $data_x[$i] . " " . (- $data_y[$i]) . "L";
                                                    } else {
                                                        $pathD .= $data_x[$i] . " " . (- $data_y[$i]) . " ";
                                                    }
                                                }
                                                if ($i == $Number_Point-1) {
                                                    if ($data_x[$i] == $data_x[0] AND $data_y[$i] == $data_y[0]) {
                                                        $pathD .= "z";
                                                        // use later
                                                        // $pathD .= $data_x[$i]." ".$data_y[$i]." ".$data_x[0]." ".$data_y[0];
                                                    } else {
                                                        $pathD .= $data_x[$i] . " " . (- $data_y[$i]);
                                                    }
                                                }
                                            }

                                            $svgtextpath[$flagText] = new SvgTextPath($data_Recid, $pathD, $data_TextContent, "", "");
                                            $g[$ii]->addChild($svgtextpath[$flagText]);

                                            $flagText++;
                                        } else {
                                            $svgz->SvgTextPath($data_Recid, $data_x, $data_y, $Number_Point, $data_TextContent, "", "");
                                        }
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
                                        $data_x = $wktparser->wktPointX;
                                        $data_y = $wktparser->wktPointY;
                                        $Number_Point = $wktparser->wktPointNr;

                                        if (!enableStreamSVG) {
                                            // set the $pathD as null
                                            $pathD = "";
                                            for($i = 0;$i < $Number_Point;$i++) {
                                                if ($enableSVGPixelCoordinate) {
                                                    $coord = getPixelXYFromRealConverse($minx, $miny, $maxx, $maxy, $width, $height, $data_x[$i], $data_y[$i], $enablestretchmap);
                                                    $data_x[$i] = $coord[0];
                                                    $data_y[$i] = $coord[1];
                                                }
                                                if ($i < $Number_Point-1) {
                                                    if ($i == 0) {
                                                        $pathD .= "M" . $data_x[$i] . " " . (- $data_y[$i]) . "L";
                                                    } else {
                                                        $pathD .= $data_x[$i] . " " . (- $data_y[$i]) . " ";
                                                    }
                                                }
                                                if ($i == $Number_Point-1) {
                                                    if ($data_x[$i] == $data_x[0] AND $data_y[$i] == $data_y[0]) {
                                                        $pathD .= "z";
                                                        // use later
                                                        // $pathD .= $data_x[$i]." ".$data_y[$i]." ".$data_x[0]." ".$data_y[0];
                                                    } else {
                                                        $pathD .= $data_x[$i] . " " . (- $data_y[$i]);
                                                    }
                                                }
                                            }
                                            $svgpath[$flagLinestring] = new SvgPath($data_Recid, trim($pathD), "", "");
                                            $g[$ii]->addChild($svgpath[$flagLinestring]);
                                            // $svgpath[$flagLinestring]->addParent($g[$flagGroup]);
                                            $flagLinestring++;
                                        } else {
                                            $svgz->SvgLineString($data_Recid, $data_x, $data_y, $Number_Point, "", "");
                                        }
                                    }
                                    break;
                                case "MULTILINESTRING": {
                                        $data_x = $wktparser->wktMPointX;
                                        $data_y = $wktparser->wktMPointY;
                                        $MNumber_Point = $wktparser->wktMPointNr;
                                        $MLine_Point = $wktparser->wktMLineNr;
                                        // print_r($MLine_Point);
                                        if (!enableStreamSVG) {
                                            // set the $pathD as null
                                            $pathD = "";
                                            for($i = 0;$i < $MLine_Point;$i++) {
                                                for($j = 0;$j < $MNumber_Point[$i];$j++) {
                                                    if ($enableSVGPixelCoordinate) {
                                                        $coord = getPixelXYFromRealConverse($minx, $miny, $maxx, $maxy, $width, $height, $data_x[$i][$j], $data_y[$i][$j], $enablestretchmap);
                                                        $data_x[$i][$j] = $coord[0];
                                                        $data_y[$i][$j] = $coord[1];
                                                    }
                                                    if ($j < $MNumber_Point[$i]-1) {
                                                        if ($j == 0) {
                                                            $pathD .= "M" . $data_x[$i][$j] . " " . (- $data_y[$i][$j]) . "L";
                                                        } else {
                                                            $pathD .= $data_x[$i][$j] . " " . (- $data_y[$i][$j]) . " ";
                                                        }
                                                    }
                                                    if ($j == $MNumber_Point[$i]-1) {
                                                        if ($data_x[$i][$j] == $data_x[$i][0] AND $data_y[$i][$j] == $data_y[$i][0]) {
                                                            $pathD .= "z";
                                                        } else {
                                                            $pathD .= $data_x[$i][$j] . " " . (- $data_y[$i][$j]);
                                                        }
                                                    }
                                                }
                                            }
                                            $svgpath[$flagLinestring] = new SvgPath($data_Recid, trim($pathD), "", "");
                                            $g[$ii]->addChild($svgpath[$flagLinestring]);
                                            // $svgpath[$flagLinestring]->addParent($g[$flagGroup]);
                                            // echo $pathD;
                                            $flagLinestring++;
                                        } else {
                                            $svgz->SvgMultiLinstring($data_Recid, $data_x, $data_y, $MLine_Point, $MNumber_Point, "", "");
                                        }
                                    }
                            } //switch
                            // $flagLinestring++;
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
                                        // $data_x = $wktparser->wktPointX;
                                        // $data_y = $wktparser->wktPointY;
                                        // $Number_Point = $wktparser->wktPointNr;
                                        // $polygonpoints = "";
                                        // for($i = 0;$i < $Number_Point;$i++) {
                                        // $polygonpoints .= $data_x[$i] . " " . $data_y[$i] . " ";
                                        // }
                                        // $svgpolygon[$flagPolygon] = new SvgPolygon($data_Recid, trim($polygonpoints), "", "");
                                        // $g[$ii]->addChild($svgpolygon[$flagPolygon]);
                                        $data_x = $wktparser->wktMPointX;
                                        $data_y = $wktparser->wktMPointY;
                                        $MNumber_Point = $wktparser->wktMPointNr;
                                        $MLine_Point = $wktparser->wktMLineNr; //is 1

                                        if (!enableStreamSVG) {
                                            for($i = 0;$i < $MLine_Point;$i++) {
                                                $polygonpoints = "";
                                                for($j = 0;$j < $MNumber_Point[$i];$j++) {
                                                    if ($enableSVGPixelCoordinate) {
                                                        $coord = getPixelXYFromRealConverse($minx, $miny, $maxx, $maxy, $width, $height, $data_x[$i][$j], $data_y[$i][$j], $enablestretchmap);
                                                        $data_x[$i][$j] = $coord[0];
                                                        $data_y[$i][$j] = $coord[1];
                                                    }
                                                    $polygonpoints .= $data_x[$i][$j] . " " . (- $data_y[$i][$j]) . " ";
                                                }
                                                $svgpolygon[$flagPolygon] = new SvgPolygon($data_Recid, trim($polygonpoints), "", "");
                                                $polygonpoints = "";
                                            }
                                            $g[$ii]->addChild($svgpolygon[$flagPolygon]);
                                            $flagPolygon++;
                                        } else {
                                            $svgz->SvgSinglePolygon($data_Recid, $data_x, $data_y, $MLine_Point, $MNumber_Point, "", "");
                                        }
                                    }
                                    break;
                                case "MULTIPOLYGON": {
                                        $data_x = $wktparser->wktMPointX;
                                        $data_y = $wktparser->wktMPointY;
                                        $MNumber_Point = $wktparser->wktMPointNr;
                                        $MLine_Point = $wktparser->wktMLineNr;

                                        if (!enableStreamSVG) {
                                            for($i = 0;$i < $MLine_Point;$i++) {
                                                $polygonpoints = "";
                                                for($j = 0;$j < $MNumber_Point[$i];$j++) {
                                                    if ($enableSVGPixelCoordinate) {
                                                        $coord = getPixelXYFromRealConverse($minx, $miny, $maxx, $maxy, $width, $height, $data_x[$i][$j], $data_y[$i][$j], $enablestretchmap);
                                                        $data_x[$i][$j] = $coord[0];
                                                        $data_y[$i][$j] = $coord[1];
                                                    }
                                                    $polygonpoints .= $data_x[$i][$j] . " " . (- $data_y[$i][$j]) . " ";
                                                }
                                                $svgpolygon[$flagPolygon] = new SvgPolygon($data_Recid . "_" . $i, trim($polygonpoints), "", "");
                                                $polygonpoints = "";
                                                $g[$ii]->addChild($svgpolygon[$flagPolygon]);
                                                $flagPolygon++;
                                            }
                                        } else {
                                            $svgz->SvgSinglePolygon($data_Recid, $data_x, $data_y, $MLine_Point, $MNumber_Point, "", "");
                                        }
                                    }
                                    break;
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
                            $Number_Point = $wktparser->wktPointNr; //$Number_Point==5

                            if (!enableStreamSVG) {
                                if ($enableSVGPixelCoordinate) {
                                    $coord = getPixelXYFromRealConverse($minx, $miny, $maxx, $maxy, $width, $height, $data_x[0], $data_y[0], $enablestretchmap);
                                    $data_x[0] = $coord[0];
                                    $data_y[0] = $coord[1];
                                    $coord1 = getPixelXYFromRealConverse($minx, $miny, $maxx, $maxy, $width, $height, $data_x[2], $data_y[2], $enablestretchmap);
                                    $data_x[2] = $coord1[0];
                                    $data_y[2] = $coord1[1];
                                }
                                $data_w = $data_x[2] - $data_x[0];
                                $data_h = $data_y[2] - $data_y[0];

                                $svgimage[$flagImage] = new SvgImage($data_Recid, $data_x[0], - $data_y[0], $data_w, $data_h, $data_ImageLink, "", "");
                                $g[$ii]->addChild($svgimage[$flagImage]);
                                $flagImage++;
                            } else {
                                $svgz->SvgSingleImage($data_Recid, $data_x, $data_y, $data_ImageLink, "", "");
                            }
                        }
                        break;
                    // ======================================================================================================
                    // ======================================================================================================
                    case 'UNKNOWN': {
                            $flagUnknown++;
                        }
                        // ======================================================================================================
                        // ======================================================================================================
                } //switchs
            } //while1
            if (enableStreamSVG) {
                $svgz->SvgGroupEnd();
            }
        }
    }
    if (!enableStreamSVG) {
        $svg->printElement();
    } else {
        $svgz->SvgFragmentEnd();
    }

    $database->databaseClose();
}
if ($enablecache) {
    $cache->caching();
}
exit();

?>