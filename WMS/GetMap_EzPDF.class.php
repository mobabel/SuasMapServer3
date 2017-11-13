<?php
/**
 * Getmapcap GetCapabilities Class
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
 * @version $2.0$ 2006.05
 * @Author Chen Hang and leelight
 * @Contact unitony1980@hotmail.com
 * @version $3.0$ 2006
 * @Author leelight
 * @Contact webmaster@easywms.com
 */
//require_once '../global.php';
include '../Plugin/www.ros.co.nz/class.ezpdf.php';
require '../Render/WKTParser.class.php';
require '../Render/Path2Point.class.php';
require '../Models/RasterColor.class.php';
include '../Parser/StyleReader.class.php';
include '../Render/EzPDFRender.class.php';
include_once '../Models/CommonFunction.class.php';

if ($enablecache == 1) {
    $cache = new Cache($cacheLimitTime, 1);
    $cache->cacheCheck();
}

setConnectionTime($maxTimeOutLimit);

$database = new Database();
$ezpdfrender = new EzPDFRender();

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
        $errorexceptionstring = "The width OR the height cannot be empty. The default is HEIGHT 700 and WIDTH 700 " ;
        $sendexceptionclass->sendexception($errornumber, $errorexceptionstring);
    }
    if ($maxx <= $minx OR $maxy <= $miny) {
        $erroroccured = false;
        $errornumber = 15;
        $errorexceptionstring = "The BBox parameters are not valid." ;
        $sendexceptionclass->sendexception($errornumber, $errorexceptionstring);
    }
    // get bbox
    // $bbox = $_GET[BBOX];
    $bboxvalues = explode(",", $bbox);
    $numberofvalues = count($bboxvalues);

    $minx = $bboxvalues[0];
    $miny = $bboxvalues[1];
    $maxx = $bboxvalues[2];
    $maxy = $bboxvalues[3];

    $rs5 = $database->getRowsBySrsGroupBy($tbmetaname, $srs, "layer");
    $num = $database->getColumnsNumber($rs5);
    // $layers = $_GET[LAYERS];
    $layersvalues = explode(",", $layers);
    $numberofvalueslayer = count($layersvalues);
    // sort the layers according its priority
    $arrayPriority = $database->getPriorityArray($tbmetaname, $srs, $layersvalues);
    $layersvalues = sortLayer($layersvalues, $arrayPriority);

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
            $errorexceptionstring = "LayerNotDefined.Layer " . $layersvalues[$i0] . " with SRS " . $srs . " not found, Please check your Layer name and/or SRS."; // The layers supported by this SRS " . $srs . " are  ". $data_Geom_Type .",";
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
    } // end  of for ($i=0; $i < $numberofvalueslayer; $i++)
}
if ($errornumber == 0) {
    /**
     * *Coordinate transform
     */
    $size = getStretchWidthHeight($minx, $miny, $maxx, $maxy, $width, $height, $enablestretchmap);
    $width = $size[0];
    $height = $size[1];
    /**
     * *Begin to create PDF
     */

    $array_wh = array(0, 0, $width, $height);
    $pdf = &new Cezpdf($array_wh);
    $pdf->selectFont('www.ros.co.nz/fonts/Helvetica');

    $pdf->addInfo("Creator", $softName . $softVersion . $softEdition);
    $pdf->addInfo("Author", $softName);
    $pdf->addInfo("Title", $softName . " in PDF");
    $pdf->addInfo("Subject", $_SERVER['SERVER_NAME']);
    $pdf->addInfo("Keywords", $softName . $softVersion . $softEdition);

    $ezpdfrender->setRender($minx, $miny, $maxx, $maxy, $width, $height, $pdf, $enablestretchmap);
    if ($showCopyright == 1) {
        $pdf->setColor(1, 0, 0);
        $ezpdfrender->createTextWithScreenCoordinate(15, 15, "Copyright " . "CERGIS Company", 15, $color_copyrightinfo);
    }

    $styleparser = new StyleParser();
    $styleparser->prefix = $dbname . $tableprefix;
    $aryXmlUserLayerNode = $styleparser->createStyleNode4layer();

    for ($j = 0; $j < $numberofvalueslayer; $j++) {
        $pdf->addObject($j);

        $rs2_ = $database->getRowsBySrsLayer($tbmetaname, $srs, $layersvalues[$j]);
        $line2_ = $database->getColumns($rs2_);
        $data_Layer_Type = strtoupper($line2_["layertype"]);

        $styleparser->getLayerStyleFromStyleNode($layersvalues[$j], $data_Layer_Type, $aryXmlUserLayerNode);

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

        if ($blnFillLineString == 1) {
            $rastercolorFillColor = new RasterColor($xmlFillColor);
            $pdf->setcolor($rastercolorFillColor->setRGB_R / 255, $rastercolorFillColor->setRGB_G / 255, $rastercolorFillColor->setRGB_B / 255);
        }
        $rastercolorStrokeColor = new RasterColor($xmlStrokeColor);
        $pdf->setStrokeColor($rastercolorFillColor->setRGB_R / 255, $rastercolorFillColor->setRGB_G / 255, $rastercolorFillColor->setRGB_B / 255);
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
            $rs1_ = $database->getGeomAsTextInBboxBySrsLayer($tbname, $minx, $miny, $maxx, $maxy, $srs, $layersvalues[$j]);
            while ($line1_ = $database->getColumns($rs1_)) {
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
                            $ezpdfrender->createPoints($data_x, $data_y, $Number_Point, $xmlWellKnownName, $color_FillColor , $xmlSize);
                        }
                        break;
                    // ======================================================================================================
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
                            // $Number_Point = $wktparser->wktPointNr;
                            // only one point!!!

                            $text = getTextAngel($database->getAttributesFromRS($line1_));
                            $data_TextContent = $text[0];
                            $angle = $text[1];

                            $ezpdfrender->createText($data_x[0], $data_y[0], $data_TextContent, $xmlSize, $color_FillColor);
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
                                        $ezpdfrender->createLinstring($data_x, $data_y, $Number_Point, $color_StrokeColor , $xmlSize, $color_FillColor, $xmlLineJoin, $xmlLineCap, $blnFillLineString);
                                    }
                                    break;
                                case "MULTILINESTRING": {
                                        $data_x = $wktparser->wktMPointX;
                                        $data_y = $wktparser->wktMPointY;
                                        $MNumber_Point = $wktparser->wktMPointNr;
                                        $MLine_Point = $wktparser->wktMLineNr;
                                        $ezpdfrender->createMultiLinstring($data_x, $data_y, $MLine_Point, $MNumber_Point, $color_StrokeColor , $xmlSize, $color_FillColor, $xmlLineJoin, $xmlLineCap, $blnFillLineString);
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
                                        // $data_x = $wktparser->wktPointX;
                                        // $data_y = $wktparser->wktPointY;
                                        // $Number_Point = $wktparser->wktPointNr;
                                        $data_x = $wktparser->wktMPointX;
                                        $data_y = $wktparser->wktMPointY;
                                        $MNumber_Point = $wktparser->wktMPointNr;
                                        $MLine_Point = $wktparser->wktMLineNr;
                                        // $rasterimagerender->createPolygon($data_x, $data_y, $Number_Point, $color_blue , $xmlSize);
                                        $ezpdfrender->createMultiPolygon($data_x, $data_y, $MLine_Point, $MNumber_Point, $color_blue , $xmlSize, $xmlLineJoin, $xmlLineCap);
                                    }
                                    break;
                                case "MULTIPOLYGON": {
                                        $data_x = $wktparser->wktMPointX;
                                        $data_y = $wktparser->wktMPointY;
                                        $MNumber_Point = $wktparser->wktMPointNr;
                                        $MLine_Point = $wktparser->wktMLineNr;
                                        $ezpdfrender->createMultiPolygon($data_x, $data_y, $MLine_Point, $MNumber_Point, $color_FillColor , $xmlSize, $xmlLineJoin, $xmlLineCap);
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
                            $ezpdfrender->createImage($data_x, $data_y, $Number_Point, $data_ImageLink, $xmlFillOpacity, $color_StrokeColor);
                        }
                        break;
                    // ======================================================================================================
                    // ======================================================================================================
                    case 'UNKNOWN': {
                        }
                        // ======================================================================================================
                        // ======================================================================================================
                } //switch
            } //while
        }
        $pdf->closeObject();
    } // end of for ($j=0; $j < $numberofvalueslayer; $j++)
    $database->databaseClose();
    $pdfcode = $pdf->ezOutput();
    $fp = fopen('../cache/map.pdf', 'wb');
    fwrite($fp, $pdfcode);
    fclose($fp);
    // echo('<a href="map.pdf"><font size=10>Click to view PDF file if your brower doesnt display it automatically.</font></a>');
    $pdf->ezStream();
    // echo('<a href="map.pdf"><font size=10>Click to view PDF file if your brower doesnt display it automatically.</font></a>');
    if ($enablecache == 1) {
        $cache->caching();
    }
}
exit();

?>