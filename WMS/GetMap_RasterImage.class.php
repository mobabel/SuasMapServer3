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
 * @copyright (C) 2006-2007  LI Hui
 * @Description : This show the copyright .
 * @contact webmaster@easywms.com
 * @version $1.0$ 2005
 * @Author Filmon Mehari and Professor Dr. Franz Josef Behr
 * @Contact filmon44@yahoo.com and franz-josef.behr@hft-stuttgart.de
 * @version $2.0$ 2006.05
 * @Author Chen Hang and LI Hui
 * @Contact unitony1980@hotmail.com
 * @version $3.0$ 2006
 * @Author LI Hui
 * @Contact webmaster@easywms.com
 */

require '../Render/WKTParser.class.php';
require '../Render/Path2Point.class.php';
require '../Models/RasterColor.class.php';
include '../Parser/StyleReader.class.php';
// require_once '../Render/RasterImagerRender.class.php';
// require_once '../Render/BMPRender.class.php';
include_once '../Models/CommonFunction.class.php';

if ($enablecache == 1) {
    if (compareStringInsensitive($format, "image/png")) {
        $cache = new Cache($cacheLimitTime, 3);
        $cache->cacheCheck();
    } elseif (compareStringInsensitive($format, "image/gif")) {
        $cache = new Cache($cacheLimitTime, 5);
        $cache->cacheCheck();
    } elseif (compareStringInsensitive($format, "image/jpeg")) {
        $cache = new Cache($cacheLimitTime, 4);
        $cache->cacheCheck();
    } elseif (compareStringInsensitive($format, "image/wbmp")) {
        $cache = new Cache($cacheLimitTime, 6);
        $cache->cacheCheck();
    } elseif (compareStringInsensitive($format, "image/bmp")) {
        $cache = new Cache($cacheLimitTime, 7);
        $cache->cacheCheck();
    }
}

setConnectionTime($maxTimeOutLimit);

$database = new Database();
$rasterimagerender = new RasterImageRender();

$database->databaseConfig($servername, $username, $password, $dbname);
$database->databaseConnect();

if ($database->databaseGetErrorMessage() != "") {
    $errornumber = -1;
    $errorexceptionstring = $database->databaseGetErrorMessage();
}
if ($errornumber != 0) {
    $sendexceptionclass->sendexception($errornumber, $errorexceptionstring);
} else {
    /*if ( $style  != "default" )
	    {
				   $erroroccured = false;
				 	 $errornumber = 0;
					 $errorexceptionstring = "Invalid Style name " .$style." given. There is only one \"default\" style name " ;
            $sendexceptionclass->sendexception($errornumber, $errorexceptionstring);
	    }*/
    if ($width == "" OR $height == "" OR $width == 0 OR $height == 0) {
        $erroroccured = false;
        $errornumber = 13;
        $errorexceptionstring = "The width OR the height cannot be empty or zero." ;
        $sendexceptionclass->sendexception($errornumber, $errorexceptionstring);
    }
    if ($maxx <= $minx OR $maxy <= $miny) {
        $erroroccured = false;
        $errornumber = 15;
        $errorexceptionstring = "The BBox parameters are not valid." ;
        $sendexceptionclass->sendexception($errornumber, $errorexceptionstring);
    }

    $rs5 = $database->getRowsBySrsGroupBy($tbmetaname, $srs, "layer");
    $num = $database->getColumnsNumber($rs5);

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
        }
        // check layer and SRS error
        if ($line2 == null AND $layers != "" AND $srs != "") {
            $erroroccured = false;
            $errornumber = 2;
            $errorexceptionstring = "LayerNotDefined.Layer " . $layersvalues[$i0] . " with SRS " . $srs . " not found, Please check your Layer name and/or SRS.";
            // The layers supported by this SRS " . $srs . " are  ". $data_Geom_Type .",";
        }
        // }
        else if ($line2 == null AND $layers == "") {
            $erroroccured = false;
            $errornumber = 4;
            $errorexceptionstring = "Layer " . $layersvalues[$i0] . " not specified. Please insert  Layer names!";
        } else if ($line2 == null AND $srs == "") {
            $erroroccured = false;
            $errornumber = 5;
            $errorexceptionstring = "SRS " . $srs . " not specified. Please insert  valid SRS!";
        }
    } // end  of for ($i0=0; $i0 < $numberofvalueslayer; $i0++)
}
if ($errornumber != 0) {
    $sendexceptionclass->sendexception($errornumber, $errorexceptionstring);
} else {
    /**
     * *Coordinate transform
     */
    $size = getStretchWidthHeight($minx, $miny, $maxx, $maxy, $width, $height, $enablestretchmap);
    $width = $size[0];
    $height = $size[1];

    if (blnGetMap25D) {
        /*
        * Use the new extended bbox to query the spatial data
        */
        $newbbox = getExtendBbox($minx, $miny, $maxx, $maxy, $vangle);
        $minx1 = $newbbox[0];
        $miny1 = $newbbox[1];
        $maxx1 = $newbbox[2];
        $maxy1 = $newbbox[3];
    }

    /**
     * *Begin to create Raster Image
     */
    $newImg = imagecreate($width, $height);

    // set transparent
    $bg = imagecolorallocate ($newImg, 0xFF, 0xFF, 0xFF); //blank but white
    if (compareStringInsensitive($transparent, "true")) {
        ImageColorTransparent($newImg, $bg);
    }
    $fg = imagecolorallocate ($newImg, 0xFF, 0xFF, 0xFF);

    $color_copyrightinfo = imagecolorallocate ($newImg, 255, 0, 0);
    $color_yellow = ImageColorAllocate($newImg, 251, 252, 194);
    $color_land = imagecolorallocate ($newImg, 0xF7, 0xEF, 0xDE);
    $color_sea = imagecolorallocate ($newImg, 0xB5, 0xC7, 0xD6);
    $color_blue = imagecolorallocate ($newImg, 0, 0, 255);

    if($skycolor!=""){
    $rastercolorskycolor = new RasterColor($skycolor);
    $color_skycolor = imagecolorallocate ($newImg, $rastercolorskycolor->setRGB_R, $rastercolorskycolor->setRGB_G, $rastercolorskycolor->setRGB_B);
    }
    if($bgcolor!=""){
    $rastercolorbgcolor = new RasterColor($bgcolor);
    $color_bgcolor = imagecolorallocate ($newImg, $rastercolorbgcolor->setRGB_R, $rastercolorbgcolor->setRGB_G, $rastercolorbgcolor->setRGB_B);
    imagefilledrectangle($newImg, 0, 0, $width, $height, $color_bgcolor);
    }


    $rasterimagerender->setRender($minx, $miny, $maxx, $maxy, $width, $height, $newImg, $enablestretchmap,$hangle,$vangle,$distance);
    if ($showCopyright == 1) {
        $rasterimagerender->createTextWithScreenCoordinate(15, $height-15, "Copyright(C)" . $softName . $softVersion . $softEdition, 5, $fontangle, $color_copyrightinfo);
    }

    $styleparser = new StyleParser();
    $styleparser->prefix = $dbname . $tableprefix;
    $aryXmlUserLayerNode = $styleparser->createStyleNode4layer();

    for ($j = 0; $j < $numberofvalueslayer; $j++) {
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
        $xmlWellKnownName = strtolower($styleparser->xmlWellKnownName);
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
        // only use for linestring or polygon
        if ($xmlFillColor == "-1")
            $blnFillColor = 0;
        else $blnFillColor = 1;

        $rastercolorFillColor = new RasterColor($xmlFillColor);
        $color_FillColor = imagecolorallocate ($newImg, $rastercolorFillColor->setRGB_R, $rastercolorFillColor->setRGB_G, $rastercolorFillColor->setRGB_B);

        $rastercolorStrokeColor = new RasterColor($xmlStrokeColor);
        $color_StrokeColor = imagecolorallocate ($newImg, $rastercolorStrokeColor->setRGB_R, $rastercolorStrokeColor->setRGB_G, $rastercolorStrokeColor->setRGB_B);

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
            if (blnGetMap25D){
                //$rs1_ = $database->getGeomAsTextInBboxBySrsLayer($tbname, $minx1, $miny1, $maxx1, $maxy1, $srs, $layersvalues[$j]);
                $rs1_ = $database->getGeomAsTextInBboxBySrsLayer($tbname, $minx, $miny, $maxx, $maxy, $srs, $layersvalues[$j]);
            }
            else {
                $rs1_ = $database->getGeomAsTextInBboxBySrsLayer($tbname, $minx, $miny, $maxx, $maxy, $srs, $layersvalues[$j]);
                }

             while ($line1_ = $database->getColumns($rs1_)) {
                $data_Geom = $database->getGeometryTextFromRS($line1_); //geom
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
                            $rasterimagerender->createPoints($data_x, $data_y, $Number_Point, $xmlWellKnownName, $color_FillColor , $xmlSize);
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
                                        $rasterimagerender->createText($data_x[0], $data_y[0], $data_TextContent, $xmlFont, $xmlSize, $angle, $color_FillColor);
                                    }
                                    break;
                                case "LINESTRING": {
                                        $rasterimagerender->createText($data_x[0], $data_y[0], $data_TextContent, $xmlFont, $xmlSize, $angle, $color_FillColor);
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
                                        $rasterimagerender->createLinstring($data_x, $data_y, $Number_Point, $color_StrokeColor , $xmlSize, $color_FillColor, $blnFillColor);
                                    }
                                    break;
                                case "MULTILINESTRING": {
                                        $data_x = $wktparser->wktMPointX;
                                        $data_y = $wktparser->wktMPointY;
                                        $MNumber_Point = $wktparser->wktMPointNr;
                                        $MLine_Point = $wktparser->wktMLineNr;
                                        $rasterimagerender->createMultiLinstring($data_x, $data_y, $MLine_Point, $MNumber_Point, $color_StrokeColor, $xmlSize, $color_FillColor, $blnFillColor);
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
                                        $rasterimagerender->createMultiPolygon($data_x, $data_y, $MLine_Point, $MNumber_Point, $color_StrokeColor, $color_FillColor, $xmlSize, $blnFillColor);
                                    }
                                    break;
                                case "MULTIPOLYGON": {
                                        $data_x = $wktparser->wktMPointX;
                                        $data_y = $wktparser->wktMPointY;
                                        $MNumber_Point = $wktparser->wktMPointNr;
                                        $MLine_Point = $wktparser->wktMLineNr;
                                        $rasterimagerender->createMultiPolygon($data_x, $data_y, $MLine_Point, $MNumber_Point, $color_StrokeColor, $color_FillColor, $xmlSize, $blnFillColor);
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
                            $rasterimagerender->createImage($data_x, $data_y, $Number_Point, $data_ImageLink, $xmlFillOpacity, $color_StrokeColor);
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
    //draw the sky background
     if (blnGetMap25D){
         if($skycolor!=""){
             $skyimage = new GDGradientFill($width, $height * (cos($vangle))*1.2,'vertical',$skycolor,'#FFFFFF',0);
             imagecopymerge($newImg, $skyimage->image, 0, 0, 0, 0, $width, $height * (cos($vangle))*1.2, 90);
             //imagefilledrectangle($newImg, 0, 0, $width, $height * (cos($vangle)), $color_skycolor);
         }
	 }

    if (compareStringInsensitive($format, "image/png")) {
        header("Content-Type:image/png");
        ImagePNG($newImg);
        ImageDestroy($newImg);
    } elseif (compareStringInsensitive($format, "image/gif")) {
        header("Content-Type:image/gif");
        Imagegif($newImg);
        ImageDestroy($newImg);
    } elseif (compareStringInsensitive($format, "image/jpeg")) {
        header("Content-Type:image/jpeg");
        Imagejpeg($newImg);
        ImageDestroy($newImg);
    } elseif (compareStringInsensitive($format, "image/wbmp")) {
        header("Content-Type:image/wbmp");
        imagewbmp($newImg);
        ImageDestroy($newImg);
    } elseif (compareStringInsensitive($format, "image/bmp")) {
        header("Content-Type:image/bmp");
        imagebmp($newImg, '' , 8, 0);
        // imagebmp_($newImg, '' , 0);
        ImageDestroy($newImg);
    }
    // } // end of for ($j=0; $j < $numberofvalueslayer; $j++)
    $database->databaseClose();
    if ($enablecache == 1) {
        $cache->caching();
    }
} // $errornumber == 0
exit();

?>
