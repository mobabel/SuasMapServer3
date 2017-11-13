<?php
/**
 * GetLegendGraphic Class
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
 * @version $3.0$ 2006
 * @Author leelight
 * @Contact webmaster@easywms.com
 */
// http://localhost/suas/WMS/getmapcap.php?VERSION=1.1.1&LAYERS=flughafen&REQUEST=GetLegendGraphic&WIDTH=16&HEIGHT=16&FORMAT=image/png
require_once '../global.php';
require_once '../Models/Setting.class.php';
require_once '../Render/RasterImagerRender.class.php';
require_once '../Parser/StyleReader.class.php';
require '../Models/RasterColor.class.php';

$erroroccured = false;
$errornumber = 0;
$errorexceptionstring = "";

$database = new Database();
$rasterimagerender = new RasterImageRender();

$database->databaseConfig($servername, $username, $password, $dbname);
$database->databaseConnect();

if ($database->databaseGetErrorMessage() != "") {
    $errornumber = -1;
    $errorexceptionstring = $database->databaseGetErrorMessage();
    $sendexceptionclass->sendexception($errornumber, $errorexceptionstring);
}
if ($errornumber == 0) {
    if (strcasecmp($format,"image/svg+xml")!=0 AND strcasecmp($format,"image/svgt+xml")!=0 AND strcasecmp($format,"image/svgb+xml")!=0
	AND strcasecmp($format,"image/png")!=0 AND strcasecmp($format,"image/jpeg")!=0 AND strcasecmp($format,"image/gif")!=0
	AND strcasecmp($format,"image/wbmp")!=0 AND strcasecmp($format,"image/bmp")!=0 AND $format != "") {
        $erroroccured = false;
        $errornumber = 3;
        $errorexceptionstring = "Invalid Format '" . $format
		. "' is given.The \"image/svg+xml\" , \"image/svgt+xml\",\"image/svgb+xml\", \"image/png\",\"image/jepg\", \"image/gif\" , \"image/bmp\" and \"image/wbmp\" are the only formats supported." . "The default format is image/png." ;
    } elseif ($format == "") {
        $format = "image/png";
    } elseif (($width == "" AND $height != "") OR ($width != "" AND $height == "")) {
        $erroroccured = false;
        $errornumber = 13;
        $errorexceptionstring = "The width OR height is empty. The default width x height is " . $params['GetLegendGraphicWidth'] . "x" . $params['GetLegendGraphicHeight'] . " (pixels)" ;
    } elseif ($width == "" AND $height == "") {
        $width = $params['GetLegendGraphicWidth'];
        $height = $params['GetLegendGraphicHeight'];
    }

    $layersvalues = explode(",", $layers);
    $numberofvalueslayer = count($layersvalues);

    for ($i = 0; $i < $numberofvalueslayer; $i++) {
        $rs1 = $database->getRowsByQueryableLayer($tbmetaname, $layersvalues[$i]);
        $line1 = $database->getColumns($rs1);

        $rs2 = $database->getRowsByLayer($tbmetaname, $layersvalues[$i]);
        $line2 = $database->getColumns($rs2);
        // the layer exists, but not queryable, $line1=0, $line2!=0
        if ($line2 == "" OR $layers == "") {
            $erroroccured = false;
            $errornumber = 5;
            $errorexceptionstring = "Layer " . $layersvalues[$i] . " not specified. Please use other Layer names!";
        }
    }
}
if ($errornumber != 0) {
    $sendexceptionclass->sendexception($errornumber, $errorexceptionstring);
} else {
    $styleparser = new StyleParser();
    $styleparser->prefix = $dbname . $tableprefix;
    $aryXmlUserLayerNode = $styleparser->createStyleNode4layer();

    for ($i = 0; $i < $numberofvalueslayer; $i++) {
        $rs1 = $database->getRowsByLayer($tbmetaname, $layersvalues[$i]);
        $line1 = $database->getColumns($rs1);

        $data1 = $line1["layer"];
        $data2 = $line1["description"];
        $data_Layer_Type = strtoupper($line1["layertype"]);

        $styleparser->getLayerStyleFromStyleNode($layersvalues[$i], $data_Layer_Type, $aryXmlUserLayerNode);
        // For point,linestring,polygon,,text
        $xmlSize = $styleparser->xmlSize;
        // For point,polygon,text,linestring
        $xmlFillColor = $styleparser->xmlFillColor;
        // For linestring,polygon
        $xmlStrokeColor = $styleparser->xmlStrokeColor;
        // For text
        $xmlFont = $styleparser->xmlFont;
        // For point
        $xmlWellKnownName = $styleparser->xmlWellKnownName;
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

        if (strcasecmp($format,"image/png")==0 OR strcasecmp($format,"image/jpeg")==0 OR strcasecmp($format,"image/gif")==0 OR strcasecmp($format,"image/wbmp")==0 OR strcasecmp($format,"image/bmp")==0) {
            $newImg = imagecreate($width, $height);
            // $bg = imagecolorallocate ($newImg, 251, 252, 194); //light yellow
            // set transparent
            $bg = imagecolorallocate ($newImg, 0xFF, 0xFF, 0xFF); //blank but white
            // ImageColorTransparent($newImg, $bg);
            $fg = imagecolorallocate ($newImg, 0xFF, 0xFF, 0xFF);

            $rasterimagerender->setRender(0, 0, 0, 0, $width, $height, $newImg, 0,0,0,0);
            $rastercolorFillColor = new RasterColor($xmlFillColor);
            $color_FillColor = imagecolorallocate ($newImg, $rastercolorFillColor->setRGB_R, $rastercolorFillColor->setRGB_G, $rastercolorFillColor->setRGB_B);

            $rastercolorStrokeColor = new RasterColor($xmlStrokeColor);
            $color_StrokeColor = imagecolorallocate ($newImg, $rastercolorStrokeColor->setRGB_R, $rastercolorStrokeColor->setRGB_G, $rastercolorStrokeColor->setRGB_B);
        }
        if (strcasecmp($format,"image/svg+xml")==0 OR strcasecmp($format,"image/svgt+xml")==0 OR strcasecmp($format,"image/svgb+xml")==0) {
        }

        switch ($data_Layer_Type) {
            case 'POINT': {
                    $rasterimagerender->createLegendGraphicPoint($width, $height, $xmlWellKnownName, $color_FillColor , $xmlSize);
                }
                break;
            case 'TEXT': {
                    $rasterimagerender->createLegendGraphicText($width, $height, "T", $xmlSize, $color_FillColor);
                }
                break;
            case 'LINESTRING': {
                    $rasterimagerender->createLegendGraphicLineString($width, $height, $color_StrokeColor , $xmlSize, $color_FillColor, $blnFillLineString);
                }
                break;
            case 'POLYGON': {
                    $rasterimagerender->createLegendGraphicPolygon($width, $height, $color_StrokeColor , $xmlSize, $color_FillColor, $blnFillLineString);
                }
                break;
            case 'IMAGE': {
                    $rasterimagerender->createLegendGraphicImage($width, $height);
                }
                break;
            case 'UNKNOWN': {
                    $rasterimagerender->createLegendGraphicUnknown($width, $height, $color_StrokeColor);
                }
        }
    } // end of for ($i=0; $i < $numberofvalueslayer; $i++)
    if (strcasecmp($format,"image/png")==0) {
        header("Content-Type:image/png");
        ImagePNG($newImg);
        ImageDestroy($newImg);
    } elseif (strcasecmp($format,"image/gif")==0) {
        header("Content-Type:image/gif");
        Imagegif($newImg);
        ImageDestroy($newImg);
    } elseif (strcasecmp($format,"image/jpeg")==0) {
        header("Content-Type:image/jpeg");
        Imagejpeg($newImg);
        ImageDestroy($newImg);
    } elseif (strcasecmp($format,"image/wbmp")==0) {
        header("Content-Type:image/wbmp");
        imagewbmp($newImg);
        ImageDestroy($newImg);
    }
    $database->databaseClose();
}
exit();
?>