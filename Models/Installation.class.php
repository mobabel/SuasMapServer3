<?php
/**
 * Installation.class.php
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
 * @contact webmaster@easywms.com
 */

/**
 * @params: $database object
 * @description : Check the extension in PHP before installation
 */
function checkExtensionInPHP($database)
{
    $requirements = true;
    // PHP Version Checking
    $phpversion = phpversion();
    if ($phpversion >= '5.0.0') {
        print('<p>&#8226; PHP 5.0.0+ <span style="color:#009900">Test Passed - You are running PHP '.$phpversion.' </span></p>');
    } else {
        print('<p>&#8226; PHP 5.0.0+ <span style="color:#CC0000">Test Failed - You are running PHP '.$phpversion.' </span></p>');
        $requirements = false;
    }
    // MySQL Version Checking
    if (extension_loaded('mysql')) {
         print('<p>&#8226; MySQL <span style="color:#009900">Test Passed - MySQL is available. (Version 5.0.16+ is mandatory required for Database operation functionality and will be auto detected).</span></p>');
        // Detect MySQL version - greater than 5.0.16
/*
        $version = $database->getDatabaseVersion();

        $explode = explode('.', $version['version']);
        $version['major'] = $explode[0];
        $version['minor'] = $explode[1];
        $version['patch'] = $explode[2];

        $explode = explode('-', $version['patch']);
        $version['patch'] = $explode[0];
        $strVersion = $version['major'] . "." . $version['minor'] . "." . $version['patch'];

        if ($version['major'] >= 5 && $version['minor'] >= 0 && $version['patch'] >= 12) {
            print('<p>&#8226; MySQL <span style="color:#009900">Test Passed - MySQL ' . $strVersion . ' is running.
			(Version 5.0.16+ is mandatory required for Database operation functionality).</span></p>');
        } else {
            print('<p>&#8226; MySQL <span style="color:#CC0000">Test Failed - Your MySQL ' . $strVersion . ' is not suitable version for PHPMyWMS.
			(Version 5.0.16+ is mandatory required for Database operation functionality)</span></p>');
            $requirements = false;
        }*/
    } else {
        print('<p>&#8226; MySQL <span style="color:#CC0000">Test Failed - MySQL is not available.</span></p>');
        $requirements = false;
    }
    // libXML Version Checking
    if (extension_loaded('libxml')) {
        print('<p>&#8226; libXML <span style="color:#009900">Test Passed - libXML is available. (Version 2.6.11 is mandatory required for parsing&generating XML file functionality).</span></p>');
    } else {
        print('<p>&#8226; libXML <span style="color:#CC0000">Test Failed - libXML is not available. It is mandatory required for parsing&generating XML file functionality</span></p>');
        $requirements = false;
    }
    // GD Version Checking
    if (extension_loaded('gd')) {
        print('<p>&#8226; GD <span style="color:#009900">Test Passed - GD is available. (Version 2.0 is mandatory required for generating raster images functionality).</span></p>');
    } else {
        print('<p>&#8226; GD <span style="color:#CC0000">Test Failed - GD is not available. It is mandatory required for generating raster images functionality</span></p>');
        $requirements = false;
    }
    // PDF Version Checking
    if (extension_loaded('pdf')) {
        print('<p>&#8226; PDF <span style="color:blue">Test Passed - PDF is available. (Version 6.0.3 is optional required for generating PDF file functionality).</span></p>');
    } else {
        print('<p>&#8226; PDF <span style="color:#CC0000">Test Failed - PDF is not available. It is optional required for generating PDF file functionality</span></p>');
    }
    // Ming Version Checking
    if (extension_loaded('ming')) {
        print('<p>&#8226; Ming <span style="color:blue">Test Passed - Ming is available. (Version 0.3.0 is optional required for generating SWF file functionality).</span></p>');
    } else {
        print('<p>&#8226; Ming <span style="color:#CC0000">Test Failed - Ming is not available. It is optional required for generating SWF file functionality</span></p>');
    }
    // Dbase Version Checking
    if (extension_loaded('Dbase')) {
        print('<p>&#8226; Dbase <span style="color:blue">Test Passed - Dbase is available. (Optional required for parsing the DBF file when importing SHP data).</span></p>');
    } else {
        print('<p>&#8226; Dbase <span style="color:#CC0000">Test Failed - Dbase is not available. It is optional required for parsing the DBF file when importing SHP data</span></p>');
    }
    return $requirements;
}

function checkDirectoryWritabale()
{
    $directoryWritbale = true;
    // Check config.php is writable
    if (is_writable('../config.php') && file_exists('../config.php')) {
        print('<p>&#8226; config.php file writable <span style="color:#009900">Test Passed (It is mandatory required for storing database access data).</span></p>');
    } else {
        $directoryWritbale = false;

        print('<p>&#8226; config.php file writable <span style="color:#CC0000">Test Failed - You need to chmod this file to 777 and/or change file permissions to allow server writing. It is mandatory required for storing database access data. Path: "suasRoot/config.php"</span></p>');
    }
    // Check cache Directory is writable
    if (is_writable('../cache')) {
        print('<p>&#8226; cache Directory writable <span style="color:#009900">Test Passed (It is mandatory required for storing the cache files in cache).</span></p>');
    } else {
        $directoryWritbale = false;
        print('<p>&#8226; cache Directory writable <span style="color:#CC0000">Test Failed - You need to chmod this directory to 777 and/or change cache Directory permissions to allow server writing. It is mandatory required for storing the cache files in cache.P ath: "suasRoot/cache"</span></p>');
    }
    // Check SLD is writable
    // if (is_writable('../SLD/Legends') && is_writable('../SLD/Styles')) {
    if (is_writable('../SLD')) {
        print('<p>&#8226; SLD Directory writable <span style="color:#009900">Test Passed (It is mandatory required for writing the style and legend files in SLD).</span></p>');
    } else {
        $directoryWritbale = false;

        print('<p>&#8226; SLD Directory writable <span style="color:#CC0000">Test Failed - You need to chmod this directory to 777 and/or change SLD Directory permissions to allow server writing. It is mandatory required for writing the style and legend files in SLD. Path: "suasRoot/SLD"</span></p>');
    }
    return $directoryWritbale;
}

/**
* check if the cache directory is writable
*/
function isCacheDirectoryWritabale(){
	if (is_writable('../cache')) {
        return true;
    } else {
		return false;
    }
}

/**
 *
 * @description : Check the extension Ming in PHP before installation
 */
function checkExtensionSwfInPHP()
{
    if (extension_loaded('ming')) {
        return true;
    } else {
        return false;
    }
}
/**
 *
 * @description : Check the extension Dbase in PHP before installation
 */
function checkExtensionDbaseInPHP()
{
    if (extension_loaded('Dbase')) {
        return true;
    } else {
        return false;
    }
}

/**
 *
 * @description : Check the extension PDF in PHP before installation
 */
function checkExtensionPdfInPHP()
{
    if (extension_loaded('pdf')) {
        return true;
    } else {
        return false;
    }
}

/**
 *
 * @description : create the div element for the loading Gauge in install pages
 */
function printDiv4LoadingGauge()
{
/*
    echo "<body onLoad=\"remove_loading();\">\n
         <div id=\"loader_container\">\n
         <div id=\"loader\">\n
         <div id=\"loadtext\" align=\"center\">File Uploading...</div>\n
         <div id=\"loader_bg\">\n
         <div id=\"progress\"> </div>\n
         </div>\n
         <div align=\"center\"><br><image src=\"../img/wait.gif\"></div>\n
         </div>\n
         </div>\n
         ";
*/
}

/**
 *
 * @description : print the EPSG select drop-down list
 * @params : $type
 * 0: Svg
 * 1: Shp
 * 2: Mif
 * 3: E00
 * 4: RemoteSvg
 * 5: RemoteShp
 * 6: RemoteMif
 * 7: RemoteE00
 */
function printEPSGList($typeN){
    switch($typeN){
    case 0: $type = "Svg"; break;
    case 1: $type = "Shp"; break;
    case 2: $type = "Mif"; break;
    case 3: $type = "E00"; break;
    case 4: $type = "RemoteSvg"; break;
    case 5: $type = "RemoteShp"; break;
    case 6: $type = "RemoteMif"; break;
    case 7: $type = "RemoteE00"; break;
	}
	print '<input name="name'.$type.'SRS" type="text" id="id'.$type.'SRS" size="15" value="SRS_not_defined"  class="smallInput"/>
		  <select NAME="TEMP" onchange="document.Form'.$type.'.name'.$type.'SRS.value =this.options[this.selectedIndex].value" class="button4"/>
		  <option value="SRS_not_defined">SRS_not_defined</option>
		  <option value="EPSG:NONE">EPSG:NONE</option>
		  <option value="EPSG:2163">EPSG:2163</option>
		  <option value="EPSG:2167">EPSG:2167</option>
		  <option value="EPSG:2168">EPSG:2168</option>
		  <option value="EPSG:2399">EPSG:2399</option>
		  <option value="EPSG:21483">EPSG:21483</option>
		  <option value="EPSG:23833">EPSG:23833</option>
		  <option value="EPSG:25832">EPSG:25832</option>
		  <option value="EPSG:27354">EPSG:27354</option>
		  <option value="EPSG:31466">EPSG:31466</option>
		  <option value="EPSG:31467">EPSG:31467</option>
		  <option value="EPSG:31468">EPSG:31468</option>
		  <option value="EPSG:31469">EPSG:31469</option>
		  <option value="EPSG:31492">EPSG:31492</option>
		  <option value="EPSG:4269">EPSG:4269</option>
		  <option value="EPSG:4296">EPSG:4296</option>
		  <option value="EPSG:4326">EPSG:4326</option>
		  <option value="EPSG:42101">EPSG:42101</option>
		  <option value="EPSG:42304">EPSG:42304</option>
		  <option value="EPSG:6326">EPSG:6326</option>
		  <option value="EPSG:7030">EPSG:7030</option>
		  <option value="EPSG:8901">EPSG:8901</option>
		  <option value></option>
		  </select>';
}

/**
 *
 * @description : list the source files in directory 'data'
 * @params : $type
 * 0: SVG
 * 1: CSV
 * 2: SHP
 * 3: DBF
 * 4: SHX
 * 5: MIF
 * 6: E00
 */
function listDataInDirectory($type)
{
    $directory = "../".uploadDataDirectory;
    $mydir = dir($directory);
    switch ($type) {
        case 0:
            echo '<select name="RemoteSelectSVG" class="button4"/>';
            break;
        case 1:
            echo '<select name="RemoteSelectCSV" class="button4"/>';
            break;
        case 2:
            echo '<select name="RemoteSelectSHP" class="button4"/>';
            break;
        case 3:
            echo '<select name="RemoteSelectDBF" class="button4"/>';
            break;
        case 4:
            echo '<select name="RemoteSelectSHX" class="button4"/>';
            break;
        case 5:
            echo '<select name="RemoteSelectMIF" class="button4"/>';
            break;
        case 6:
            echo '<select name="RemoteSelectE00" class="button4"/>';
            break;
        case -1:
            echo '';
            break;
    } while ($file = $mydir->read()) {
        if ((is_dir("$directory/$file")) AND ($file != ".") AND ($file != "..")) {
            echo "<optgroup label=\"$file\">";
             //$directory = $directory."/".$file;
             //listDataInDirectory(-1);
            echo "</optgroup>";
        } else {
            if (($file != ".") AND ($file != "..")) {
                $filePostfix = strtoupper(substr($file, strlen($file)-3, strlen($file)));
                switch ($type) {
                    case 0:
                        if ($filePostfix == "SVG" || $filePostfix == "TXT")
                            echo "<option value=\"$directory" . "/" . "$file\">$file</option>\n";
                        break;
                    case 1:
                        if ($filePostfix == "CSV" || $filePostfix == "TXT")
                            echo "<option value=\"$directory" . "/" . "$file\">$file</option>\n";
                        break;
                    case 2:
                        if ($filePostfix == "SHP")
                            echo "<option value=\"$directory" . "/" . "$file\">$file</option>\n";
                        break;
                    case 3:
                        if ($filePostfix == "DBF")
                            echo "<option value=\"$directory" . "/" . "$file\">$file</option>\n";
                        break;
                    case 4:
                        if ($filePostfix == "SHX")
                            echo "<option value=\"$directory" . "/" . "$file\">$file</option>\n";
                        break;
                    case 5:
                        if ($filePostfix == "MIF")
                            echo "<option value=\"$directory" . "/" . "$file\">$file</option>\n";
                        break;
                    case 6:
                        if ($filePostfix == "E00")
                            echo "<option value=\"$directory" . "/" . "$file\">$file</option>\n";
                        break;
                }
            }
        }
    }
    echo "</select>";

    switch ($type) {
        case 0:
            echo "\n";
            break;
        case 1:
            echo "\n";
            break;
        case 2:
            echo "\n";
            break;
        case 3:
            echo "\n";
            break;
        case 4:
            echo "\n";
            break;
        case 5:
            echo "\n";
            break;
        case 6:
            echo "\n";
            break;
        case -1:
            echo "\n";
            break;
    }

    $mydir->close();
}
// listDataInDirectory(0);
/**
 *
 * @function : getStyleInfoContainer
 * @description : get the style information
 * @param  $ :$strLayerName: layer name
 * @param  $ :$strLayerType: layer type
 * @param  $ :$strLayerLabel: text layer label
 */
function getStyleInfoContainer($strLayerName, $strLayerType, $strLayerLabel)
{
    // call function getLayerType to get Layer's Type
    $strStyleInfoContainer = "";
    switch (strtoupper($strLayerType)) {
        case "POLYGON": {
                $strStyleInfoContainer = " <input type=hidden name=\"layerName\" value=\"$strLayerName\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtStyleName\" value=\"Default\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtStyleTitle\" value=\"Default\">\n"
				. "<input type=hidden name=\"$strLayerName" . "layerType\" value=\"Polygon\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtFill\" value=\"-1\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtFillOpacity\" value=\"100\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtStroke\" value=\"#000000\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtStrokeWidth\" value=\"1\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtMinRange\" value=\"\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtMaxRange\" value=\"\">\n" ;
                return $strStyleInfoContainer;
            }
            break;
        case "LINESTRING": {
                $strStyleInfoContainer = "<input type=hidden name=\"layerName\" value=\"$strLayerName\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtStyleName\" value=\"Default\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtStyleTitle\" value=\"Default\">\n"
				. "<input type=hidden name=\"$strLayerName" . "layerType\" value=\"LineString\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtFill\" value=\"-1\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtFillOpacity\" value=\"100\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtStroke\" value=\"#000000\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtStrokeWidth\" value=\"1\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtStrokeOpacity\" value=\"100\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtStrokeLinejoin\" value=\"round\">\n"  // miter, round, bevel
                . "<input type=hidden name=\"$strLayerName" . "txtStrokeLinecap\" value=\"butt\">\n"  // butt, round, square
                . "<input type=hidden name=\"$strLayerName" . "txtStrokeDasharray\" value=\"\">\n"  // use later
                . "<input type=hidden name=\"$strLayerName" . "txtStrokeDashoffset\" value=\"\">\n"  // use later
                . "<input type=hidden name=\"$strLayerName" . "txtMinRange\" value=\"\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtMaxRange\" value=\"\">\n" ;
                return $strStyleInfoContainer;
            }
            break;
        case "POINT": {
                $strStyleInfoContainer = "<input type=hidden name=\"layerName\" value=\"$strLayerName\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtStyleName\" value=\"Default\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtStyleTitle\" value=\"Default\">\n"
				. "<input type=hidden name=\"$strLayerName" . "layerType\" value=\"Point\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtFill\" value=\"#808080\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtFillOpacity\" value=\"100\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtStroke\" value=\"#808080\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtStrokeOpacity\" value=\"100\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtWellknownName\" value=\"square\">\n"  // square, circle, triangle, star, cross , x
                . "<input type=hidden name=\"$strLayerName" . "txtSize\" value=\"6\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtMinRange\" value=\"\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtMaxRange\" value=\"\">\n" ;
                return $strStyleInfoContainer;
            }
            break;
        case "TEXT": {
                $strStyleInfoContainer = "<input type=hidden name=\"layerName\" value=\"$strLayerName\">\n" . "<input type=hidden name=\"$strLayerName" . "txtStyleName\" value=\"Default\">\n" . "<input type=hidden name=\"$strLayerName" . "txtStyleTitle\" value=\"Default\">\n" . "<input type=hidden name=\"$strLayerName" . "layerType\" value=\"Text\">\n" . "<input type=hidden name=\"$strLayerName" . "txtLabel\" value=\"$strLayerLabel\">\n" . "<input type=hidden name=\"$strLayerName" . "sltFontFamily\" value=\"Arial\">\n" . "<input type=hidden name=\"$strLayerName" . "txtFontSize\" value=\"10\">\n" . "<input type=hidden name=\"$strLayerName" . "txtFontStyle\" value=\"normal\">\n" . // normal, italic, oblique
                "<input type=hidden name=\"$strLayerName" . "txtFontWeight\" value=\"normal\">\n" . // normal, bold
                "<input type=hidden name=\"$strLayerName" . "txtFontColor\" value=\"#000000\">\n" . "<input type=hidden name=\"$strLayerName" . "txtFontHalo\" value=\"-1\">\n" . // #ffffff use later, in Fill
                "<input type=hidden name=\"$strLayerName" . "txtMinRange\" value=\"\">\n" . "<input type=hidden name=\"$strLayerName" . "txtMaxRange\" value=\"\">\n" ;
                return $strStyleInfoContainer;
            }
            break;
        case "IMAGE": {
                $strStyleInfoContainer = "<input type=hidden name=\"layerName\" value=\"$strLayerName\">\n" . "<input type=hidden name=\"$strLayerName" . "txtStyleName\" value=\"Default\">\n" . "<input type=hidden name=\"$strLayerName" . "txtStyleTitle\" value=\"Default\">\n" . "<input type=hidden name=\"$strLayerName" . "Title\" value=\"$strLayerName\">\n" . "<input type=hidden name=\"$strLayerName" . "layerType\" value=\"Image\">\n" . "<input type=hidden name=\"$strLayerName" . "txtOpacity\" value=\"100\">\n" . "<input type=hidden name=\"$strLayerName" . "txtMinRange\" value=\"\">\n" . "<input type=hidden name=\"$strLayerName" . "txtMaxRange\" value=\"\">\n" ;
                return $strStyleInfoContainer;
            }
            break;
        case "UNKNOWN": {
                $strStyleInfoContainer = "<input type=hidden name=\"layerName\" value=\"$strLayerName\">\n" . "<input type=hidden name=\"$strLayerName" . "txtStyleName\" value=\"Default\">\n" . "<input type=hidden name=\"$strLayerName" . "txtStyleTitle\" value=\"Default\">\n" . "<input type=hidden name=\"$strLayerName" . "layerType\" value=\"Unknown\">\n" . "<input type=hidden name=\"$strLayerName" . "txtMinRange\" value=\"\">\n" . "<input type=hidden name=\"$strLayerName" . "txtMaxRange\" value=\"\">\n" ;
                return $strStyleInfoContainer;
            }
            break;

        default: {
                $strStyleInfoContainer = "<input type=hidden name=\"layerName\" value=\"$strLayerName\">\n" . "<input type=hidden name=\"$strLayerName" . "txtStyleName\" value=\"Default\">\n" . "<input type=hidden name=\"$strLayerName" . "txtStyleTitle\" value=\"Default\">\n" . "<input type=hidden name=\"$strLayerName" . "layerType\" value=\"Unknown\">\n" . "<input type=hidden name=\"$strLayerName" . "txtMinRange\" value=\"\">\n" . "<input type=hidden name=\"$strLayerName" . "txtMaxRange\" value=\"\">\n" ;
                return $strStyleInfoContainer;
            }
    }
}


/**
 *
 * @function : getStyleInfoContainer
 * @description : get the style information
 * @param  $ :$strLayerName: layer name
 * @param  $ :$strLayerType: layer type
 * @params:$xmlUserLayerName,$xmlUserLayerTitle,$xmlMinScaleDenominator, $xmlMaxScaleDenominator, $xmlSize, $xmlFillColor, $xmlStrokeColor,
 *         $xmlWellKnownName, $xmlStrokeOpacity,
 *         $xmlFillOpacity, $xmlFont, $xmlFontStyle, $xmlFontWeight, $xmlLineJoin, $xmlLineCap
 */
function getStyleInfoContainerFromSLD($strLayerName, $strLayerType, $xmlUserLayerName,$xmlUserLayerTitle, $xmlMinScaleDenominator, $xmlMaxScaleDenominator,
         $xmlSize, $xmlFillColor, $xmlStrokeColor, $xmlFont, $xmlWellKnownName, $xmlStrokeOpacity,
         $xmlFillOpacity, $xmlFont, $xmlFontStyle, $xmlFontWeight, $xmlLineJoin, $xmlLineCap)
{
    // call function getLayerType to get Layer's Type
    $strStyleInfoContainer = "";

    if($xmlUserLayerName=="")$xmlUserLayerName = "Default";
    if($xmlUserLayerTitle=="")$xmlUserLayerTitle = "Default";
    if($xmlMinScaleDenominator=="")$xmlMinScaleDenominator = "";
    if($xmlMaxScaleDenominator=="")$xmlMaxScaleDenominator = "";
    if($xmlSize=="")$xmlSize = "1";
    if($xmlFillColor=="")$xmlFillColor = "-1";
    if($xmlStrokeColor=="")$xmlStrokeColor = "#000000";
    if($xmlFont=="")$xmlFont = "Arial";
    if($xmlWellKnownName=="")$xmlWellKnownName = "square";
    if($xmlStrokeOpacity=="")$xmlStrokeOpacity = "100";
    if($xmlFillOpacity=="")$xmlFillOpacity = "100";
    if($xmlFontStyle=="")$xmlFontStyle = "normal";
    if($xmlFontWeight=="")$xmlFontWeight = "normal";
    if($xmlLineJoin=="")$xmlLineJoin = "round";
    if($xmlLineCap=="")$xmlLineCap = "butt";

    switch (strtoupper($strLayerType)) {
        case "POLYGON": {
                $strStyleInfoContainer = " <input type=hidden name=\"layerName\" value=\"$strLayerName\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtStyleName\" value=\"$xmlUserLayerName\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtStyleTitle\" value=\"$xmlUserLayerTitle\">\n"
				. "<input type=hidden name=\"$strLayerName" . "layerType\" value=\"Polygon\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtFill\" value=\"$xmlFillColor\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtFillOpacity\" value=\"$xmlFillOpacity\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtStroke\" value=\"$xmlStrokeColor\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtStrokeWidth\" value=\"$xmlSize\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtMinRange\" value=\"$xmlMinScaleDenominator\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtMaxRange\" value=\"$xmlMaxScaleDenominator\">\n" ;
                return $strStyleInfoContainer;
            }
            break;
        case "LINESTRING": {
                $strStyleInfoContainer = "<input type=hidden name=\"layerName\" value=\"$strLayerName\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtStyleName\" value=\"$xmlUserLayerName\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtStyleTitle\" value=\"$xmlUserLayerTitle\">\n"
				. "<input type=hidden name=\"$strLayerName" . "layerType\" value=\"LineString\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtFill\" value=\"$xmlFillColor\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtFillOpacity\" value=\"$xmlFillOpacity\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtStroke\" value=\"$xmlStrokeColor\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtStrokeWidth\" value=\"$xmlSize\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtStrokeOpacity\" value=\"$xmlStrokeOpacity\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtStrokeLinejoin\" value=\"$xmlLineJoin\">\n" . // miter, round, bevel
                "<input type=hidden name=\"$strLayerName" . "txtStrokeLinecap\" value=\"$xmlLineCap\">\n" . // butt, round, square
                "<input type=hidden name=\"$strLayerName" . "txtStrokeDasharray\" value=\"\">\n" . // use later
                "<input type=hidden name=\"$strLayerName" . "txtStrokeDashoffset\" value=\"\">\n" . // use later
                "<input type=hidden name=\"$strLayerName" . "txtMinRange\" value=\"$xmlMinScaleDenominator\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtMaxRange\" value=\"$xmlMaxScaleDenominator\">\n" ;
                return $strStyleInfoContainer;
            }
            break;
        case "POINT": {
                $strStyleInfoContainer = "<input type=hidden name=\"layerName\" value=\"$strLayerName\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtStyleName\" value=\"$xmlUserLayerName\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtStyleTitle\" value=\"$xmlUserLayerTitle\">\n"
				. "<input type=hidden name=\"$strLayerName" . "layerType\" value=\"Point\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtFill\" value=\"$xmlFillColor\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtFillOpacity\" value=\"$xmlFillOpacity\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtStroke\" value=\"$xmlStrokeColor\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtStrokeOpacity\" value=\"$xmlStrokeOpacity\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtWellknownName\" value=\"$xmlWellKnownName\">\n"  // square, circle, triangle, star, cross , x
                . "<input type=hidden name=\"$strLayerName" . "txtSize\" value=\"$xmlSize\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtMinRange\" value=\"$xmlMinScaleDenominator\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtMaxRange\" value=\"$xmlMaxScaleDenominator\">\n" ;
                return $strStyleInfoContainer;
            }
            break;
        case "TEXT": {
                $strStyleInfoContainer = "<input type=hidden name=\"layerName\" value=\"$strLayerName\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtStyleName\" value=\"$xmlUserLayerName\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtStyleTitle\" value=\"$xmlUserLayerTitle\">\n"
				. "<input type=hidden name=\"$strLayerName" . "layerType\" value=\"Text\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtLabel\" value=\"$strLayerLabel\">\n"
				. "<input type=hidden name=\"$strLayerName" . "sltFontFamily\" value=\"$xmlFont\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtFontSize\" value=\"$xmlSize\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtFontStyle\" value=\"$xmlFontStyle\">\n"  // normal, italic, oblique
                . "<input type=hidden name=\"$strLayerName" . "txtFontWeight\" value=\"$xmlFontWeight\">\n" // normal, bold
				. "<input type=hidden name=\"$strLayerName" . "txtFontColor\" value=\"$xmlFillColor\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtFontHalo\" value=\"-1\">\n"  // #ffffff use later, in Fill
                . "<input type=hidden name=\"$strLayerName" . "txtMinRange\" value=\"$xmlMinScaleDenominator\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtMaxRange\" value=\"$xmlMaxScaleDenominator\">\n" ;
                return $strStyleInfoContainer;
            }
            break;
        case "IMAGE": {
                $strStyleInfoContainer = "<input type=hidden name=\"layerName\" value=\"$strLayerName\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtStyleName\" value=\"$xmlUserLayerName\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtStyleTitle\" value=\"$xmlUserLayerTitle\">\n"
				. "<input type=hidden name=\"$strLayerName" . "Title\" value=\"$strLayerName\">\n"
				. "<input type=hidden name=\"$strLayerName" . "layerType\" value=\"Image\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtOpacity\" value=\"$xmlFillOpacity\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtMinRange\" value=\"$xmlMinScaleDenominator\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtMaxRange\" value=\"$xmlMaxScaleDenominator\">\n" ;
				return $strStyleInfoContainer;
            }
            break;
        case "UNKNOWN": {
                $strStyleInfoContainer = "<input type=hidden name=\"layerName\" value=\"$strLayerName\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtStyleName\" value=\"$xmlUserLayerName\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtStyleTitle\" value=\"$xmlUserLayerTitle\">\n"
				. "<input type=hidden name=\"$strLayerName" . "layerType\" value=\"Unknown\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtMinRange\" value=\"$xmlMinScaleDenominator\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtMaxRange\" value=\"$xmlMaxScaleDenominator\">\n" ;
                return $strStyleInfoContainer;
            }
            break;

        default: {
                $strStyleInfoContainer = "<input type=hidden name=\"layerName\" value=\"$strLayerName\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtStyleName\" value=\"Default\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtStyleTitle\" value=\"Default\">\n"
				. "<input type=hidden name=\"$strLayerName" . "layerType\" value=\"Unknown\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtMinRange\" value=\"$xmlMinScaleDenominator\">\n"
				. "<input type=hidden name=\"$strLayerName" . "txtMaxRange\" value=\"$xmlMaxScaleDenominator\">\n" ;
                return $strStyleInfoContainer;
            }
    }
        //initialize the values
        $xmlUserLayerName = "";
        $xmlUserLayerTitle = "";
        $xmlMinScaleDenominator = "";
        $xmlMaxScaleDenominator = "";
        $xmlSize = "";
        $xmlFillColor = "";
        $xmlStrokeColor = "";
        $xmlFont = "";
        $xmlWellKnownName = "";
        $xmlStrokeOpacity = "";
        $xmlFillOpacity = "";
        $xmlFontStyle = "";
        $xmlFontWeight = "";
        $xmlLineJoin = "";
        $xmlLineCap = "";
}

/**
 *
 * @function : createWmsStyles
 * @description : wirte the style information into the hidden input field for each layer
 * @param  $ :$arrLayerName: layer name array
 * @param  $ :$arrLayerType: layer type array
 * @param  $ :$prefix: $dbname + $tableprefix
 */
function createWmsStyles($arrLayerName, $arraySrsName, $arrLayerType,$prefix)
{
    $doc = new DOMDocument('1.0', 'utf-8');

    /*
        $doc->loadXML('<StyledLayerDescriptor version="1.0.0"
							xmlns="http://www.opengis.net/sld"
							xmlns:ogc="http://www.opengis.net/ogc"
							xmlns:xlink="http://www.w3.org/1999/xlink"
							xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
							</StyledLayerDescriptor>');
*/
    // we want a nice output
    $doc->formatOutput = true;
    // create a root node and set an attribute
    $root = $doc->createElement("StyledLayerDescriptor");
    $doc->appendChild($root);
    $root->setAttribute("version", "1.0.0");
    // If this attribute is added, in StyleReader will have problem to parse the xml fragment!
    // $root->setAttribute("xmlns", "http://www.opengis.net/sld");
    $root->setAttribute("xmlns:ogc", "http://www.opengis.net/ogc");
    $root->setAttribute("xmlns:xlink", "http://www.w3.org/1999/xlink");
    $root->setAttribute("xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");
    // create style for each layer
    for($i = 0;$i < count($arrLayerName);$i++) {
        // create element NamedLayer for each layer
        $objNamedLayer = $doc->createElement("NamedLayer");
        $root->appendChild($objNamedLayer);
        // create element Name for each NamedLayer
        $objName = $doc->createElement("Name", $arrLayerName[$i]);
        $objNamedLayer->appendChild($objName);
        // create element UserStyle for each NamedLayer element
        $objUserStyle = $doc->createElement("UserStyle");
        $objNamedLayer->appendChild($objUserStyle);
        // create element Name  for element UserStyle
        $objUserStyleName = $doc->createElement("Name", $_POST[$arraySrsName[$i].$arrLayerName[$i] . 'txtStyleName']);
        $objUserStyle->appendChild($objUserStyleName);
        // create element Name  for element UserStyle
        $objUserStyleTitle = $doc->createElement("Title", $_POST[$arraySrsName[$i].$arrLayerName[$i] . 'txtStyleTitle']);
        $objUserStyle->appendChild($objUserStyleTitle);
        // create element IsDefault for UserStyle
        $objUserStyleIsDefault = $doc->createElement("IsDefault", "1");
        $objUserStyle->appendChild($objUserStyleIsDefault);
        // create element FeatureTypeStyle for UserStyle
        $objFeatureTypeStyle = $doc->createElement("FeatureTypeStyle");
        $objUserStyle->appendChild($objFeatureTypeStyle);
        // create element Rule for element FeatureTypeStyle
        $objRule = $doc->createElement("Rule");
        $objFeatureTypeStyle->appendChild($objRule);
        // create element name for element Rule
        $objRuleName = $doc->createElement("Name", $arrLayerName[$i] . "_Style_Rule");
        $objRule->appendChild($objRuleName);
        // create element title for element Rule
        $objRuleTitle = $doc->createElement("Title", $arrLayerName[$i] . " Style Rule");
        $objRule->appendChild($objRuleTitle);
        // create element MinScaleDenominator for element Rule
        if ($_POST[$arrLayerName[$i] . 'txtMinRange'] != "") {
            $objMinScaleDenominator = $doc->createElement("MinScaleDenominator", $_POST[$arraySrsName[$i].$arrLayerName[$i] . 'txtMinRange']);
            $objRule->appendChild($objMinScaleDenominator);
            $objMinScaleDenominator = null;
        }
        // create element MaxScaleDenominator for element Rule
        if ($_POST[$arrLayerName[$i] . 'txtMaxRange'] != "") {
            $objMaxScaleDenominator = $doc->createElement("MaxScaleDenominator", $_POST[$arraySrsName[$i].$arrLayerName[$i] . 'txtMaxRange']);
            $objRule->appendChild($objMaxScaleDenominator);
            $objMaxScaleDenominator = null;
        }

        switch (strtoupper($arrLayerType[$i])) {
            case "POLYGON": {
                    // create node PolygonSymbolizer for element Rule
                    $objPolygonSymbolizer = $doc->createElement("PolygonSymbolizer");
                    $objRule->appendChild($objPolygonSymbolizer);
                    // create Fill node for element PolygonSymbolizer
                    $objFill = $doc->createElement("Fill");
                    $objPolygonSymbolizer->appendChild($objFill);
                    // create CssParameter for element Fill
                    $objCssParameter = $doc->createElement("CssParameter", $_POST[$arraySrsName[$i].$arrLayerName[$i] . 'txtFill']);
                    $objCssParameter->setAttribute("name", "fill");
                    $objFill->appendChild($objCssParameter);
                    // create CssParameter for element fill-opacity
                    $objCssParameter = $doc->createElement("CssParameter", $_POST[$arraySrsName[$i].$arrLayerName[$i] . 'txtFillOpacity']);
                    $objCssParameter->setAttribute("name", "fill-opacity");
                    $objFill->appendChild($objCssParameter);
                    // create Stroke for element PolygonSymbolizer
                    $objStroke = $doc->createElement("Stroke");
                    $objPolygonSymbolizer->appendChild($objStroke);
                    // create CssParameter for element stroke
                    $objCssParameter = $doc->createElement("CssParameter", $_POST[$arraySrsName[$i].$arrLayerName[$i] . 'txtStroke']);
                    $objCssParameter->setAttribute("name", "stroke");
                    $objStroke->appendChild($objCssParameter);
                    // create CssParameter for element stroke
                    $objCssParameter = $doc->createElement("CssParameter", $_POST[$arraySrsName[$i].$arrLayerName[$i] . 'txtStrokeWidth']);
                    $objCssParameter->setAttribute("name", "stroke-width");
                    $objStroke->appendChild($objCssParameter);

                    $objCssParameter = null;
                    $objStroke = null;
                    $objFill = null;
                    $objPolygonSymbolizer = null;
                }
                break;
            case "LINESTRING": {
                    // create LinesringSymbolizer for element Rule
                    $objLineSymbolizer = $doc->createElement("LineSymbolizer");
                    $objRule->appendChild($objLineSymbolizer);
                    // create Fill node for element PolygonSymbolizer
                    $objFill = $doc->createElement("Fill");
                    $objLineSymbolizer->appendChild($objFill);
                    // create CssParameter for element Fill
                    $objCssParameter = $doc->createElement("CssParameter", $_POST[$arraySrsName[$i].$arrLayerName[$i] . 'txtFill']);
                    $objCssParameter->setAttribute("name", "fill");
                    $objFill->appendChild($objCssParameter);
                    // create CssParameter for element fill-opacity
                    $objCssParameter = $doc->createElement("CssParameter", $_POST[$arraySrsName[$i].$arrLayerName[$i] . 'txtFillOpacity']);
                    $objCssParameter->setAttribute("name", "fill-opacity");
                    $objFill->appendChild($objCssParameter);
                    // create Stroke node for element PolygonSymbolizer
                    $objStroke = $doc->createElement("Stroke");
                    $objLineSymbolizer->appendChild($objStroke);
                    // create CssParameter node for element stroke
                    $objCssParameter = $doc->createElement("CssParameter", $_POST[$arraySrsName[$i].$arrLayerName[$i] . 'txtStroke']);
                    $objCssParameter->setAttribute("name", "stroke");
                    $objStroke->appendChild($objCssParameter);
                    // create CssParameter node for element stroke
                    $objCssParameter = $doc->createElement("CssParameter", $_POST[$arraySrsName[$i].$arrLayerName[$i] . 'txtStrokeWidth']);
                    $objCssParameter->setAttribute("name", "stroke-width");
                    $objStroke->appendChild($objCssParameter);
                    // create CssParameter node for element stroke-opacity
                    $objCssParameter = $doc->createElement("CssParameter", $_POST[$arraySrsName[$i].$arrLayerName[$i] . 'txtStrokeOpacity']);
                    $objCssParameter->setAttribute("name", "stroke-opacity");
                    $objStroke->appendChild($objCssParameter);
                    // create CssParameter node for element stroke-linejoin
                    $objCssParameter = $doc->createElement("CssParameter", $_POST[$arraySrsName[$i].$arrLayerName[$i] . 'txtStrokeLinejoin']);
                    $objCssParameter->setAttribute("name", "stroke-linejoin");
                    $objStroke->appendChild($objCssParameter);
                    // create CssParameter node for element stroke-linecap
                    $objCssParameter = $doc->createElement("CssParameter", $_POST[$arraySrsName[$i].$arrLayerName[$i] . 'txtStrokeLinecap']);
                    $objCssParameter->setAttribute("name", "stroke-linecap");
                    $objStroke->appendChild($objCssParameter);

                    $objCssParameter = null;
                    $objFill = null;
                    $objStroke = null;
                    $objLineSymbolizer = null;
                }
                break;
            case "POINT": {
                    // create PointSymbolizer node for element Rule
                    $objPointSymbolizer = $doc->createElement("PointSymbolizer");
                    $objRule->appendChild($objPointSymbolizer);
                    // create Graphic node for element PointSymbolizer
                    $objGraphic = $doc->createElement("Graphic");
                    $objPointSymbolizer->appendChild($objGraphic);
                    // create Mark node for element Graphic element
                    $objMark = $doc->createElement("Mark");
                    $objGraphic->appendChild($objMark);
                    // create WellKnownName node for Mark node
                    $objWellKnownName = $doc->createElement("WellKnownName", $_POST[$arraySrsName[$i].$arrLayerName[$i] . 'txtWellknownName']);
                    $objMark->appendChild($objWellKnownName);
                    // create Stroke node for element PolygonSymbolizer
                    $objStroke = $doc->createElement("Stroke");
                    $objMark->appendChild($objStroke);
                    // create CssParameter for element stroke-opacity
                    $objCssParameter = $doc->createElement("CssParameter", $_POST[$arraySrsName[$i].$arrLayerName[$i] . 'txtStrokeOpacity']);
                    $objCssParameter->setAttribute("name", "stroke-opacity");
                    $objStroke->appendChild($objCssParameter);
                    // create Fill for element PolygonSymbolizer
                    $objFill = $doc->createElement("Fill");
                    $objMark->appendChild($objFill);
                    // create CssParameter for element Fill
                    $objCssParameter = $doc->createElement("CssParameter", $_POST[$arraySrsName[$i].$arrLayerName[$i] . 'txtFill']);
                    $objCssParameter->setAttribute("name", "fill");
                    $objFill->appendChild($objCssParameter);
                    // create CssParameter for element txtFillOpacity
                    $objCssParameter = $doc->createElement("CssParameter", $_POST[$arraySrsName[$i].$arrLayerName[$i] . 'txtFillOpacity']);
                    $objCssParameter->setAttribute("name", "fill-opacity");
                    $objFill->appendChild($objCssParameter);
                    // create Size node for element Graphic element
                    $objSize = $doc->createElement("Size", $_POST[$arraySrsName[$i].$arrLayerName[$i] . 'txtSize']);
                    $objGraphic->appendChild($objSize);

                    $objCssParameter = null;
                    $objStroke = null;
                    $objFill = null;
                    $objSize = null;
                    $objWellKnownName = null;
                    $objMark = null;
                    $objGraphic = null;
                    $objPointSymbolizer = null;
                }
                break;

            case "TEXT": {
                    // create TextSymbolizer node for element Rule
                    $objTextSymbolizer = $doc->createElement("TextSymbolizer");
                    $objRule->appendChild($objTextSymbolizer);
                    // create Label node for element textSymbolizer
                    $objLabel = $doc->createElement("Label");
                    $objTextSymbolizer->appendChild($objLabel);
                    // create PropertyName node for element Label
                    $objPropertyName = $doc->createElement("ogc:PropertyName", $_POST[$arraySrsName[$i].$arrLayerName[$i] . 'txtLabel']);
                    $objLabel->appendChild($objPropertyName);
                    // create Font node for element TextSymbolizer
                    $objFont = $doc->createElement("Font");
                    $objTextSymbolizer->appendChild($objFont);
                    // create CssParameter for element Font
                    $objCssParameter = $doc->createElement("CssParameter", $_POST[$arraySrsName[$i].$arrLayerName[$i] . 'sltFontFamily']);
                    $objCssParameter->setAttribute("name", "font-family");
                    $objFont->appendChild($objCssParameter);
                    // create CssParameter for element Font
                    $objCssParameter = $doc->createElement("CssParameter", $_POST[$arraySrsName[$i].$arrLayerName[$i] . 'txtFontSize']);
                    $objCssParameter->setAttribute("name", "font-size");
                    $objFont->appendChild($objCssParameter);
                    // create CssParameter for element font-style
                    $objCssParameter = $doc->createElement("CssParameter", $_POST[$arraySrsName[$i].$arrLayerName[$i] . 'txtFontStyle']);
                    $objCssParameter->setAttribute("name", "font-style");
                    $objFont->appendChild($objCssParameter);
                    // create CssParameter for element font-weight
                    $objCssParameter = $doc->createElement("CssParameter", $_POST[$arraySrsName[$i].$arrLayerName[$i] . 'txtFontWeight']);
                    $objCssParameter->setAttribute("name", "font-weight");
                    $objFont->appendChild($objCssParameter);
                    // create Fill node for element TextSymbolizer
                    $objFill = $doc->createElement("Fill");
                    $objTextSymbolizer->appendChild($objFill);
                    // create CssParameter for element Fill
                    $objCssParameter = $doc->createElement("CssParameter", $_POST[$arraySrsName[$i].$arrLayerName[$i] . 'txtFontColor']);
                    $objCssParameter->setAttribute("name", "fill");
                    $objFill->appendChild($objCssParameter);

                    $objCssParameter = null;
                    $objPropertyName = null;
                    $objFill = null;
                    $objLabel = null;
                    $objTextSymbolizer = null;
                }
                break;
            case "IMAGE": {
                    // create RasterSymbolizer node for element Rule
                    // webMap don't support raster sybmolization
                    $objRasterSymbolizer = $doc->createElement("RasterSymbolizer");
                    $objRule->appendChild($objRasterSymbolizer);
                    // create Opacity node for RasterSymbolizer node
                    $objOpacity = $doc->createElement("Opacity", $_POST[$arraySrsName[$i].$arrLayerName[$i] . 'txtOpacity']);
                    $objRasterSymbolizer->appendChild($objOpacity);

                    $objRasterSymbolizer = null;
                    $objOpacity = null;
                }
                break;
            case "UNKNOWN": {
                    // do nothing
                }
        }
    }

    $doc->save("../SLD/Styles/".$prefix."WmsStyles.xml");
}

function printOption4PrioritySelected($account,$index){
    if($account<$index) $index = $account;
    //create options for priority selection
    $strOption4Priority = "";
    for($j=0;$j<=$account;$j++){
        if($j==$index)
            $strOption4Priority .="<option value=\"$j\" selected>$j</option>\n";
        else
		    $strOption4Priority .="<option value=\"$j\">$j</option>\n";
	}
	return $strOption4Priority;

}

/**
 *
 * @function : getPrefixOfTablename
 * @description : get the table prefix from the table name
 * @param  $tbname table name for featuregeometry table in SUAS
 */
function getPrefixOfTablename($tbname){
	return substr($tbname, 0, strripos($tbname,mapTableFeaturegeometry));
}

?>