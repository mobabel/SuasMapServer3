<?php
/**
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
require_once '../global.php';
require_once '../config.php';
require_once '../Models/Setting.class.php';
require_once '../Models/CreateDemoMenu.php';

$database = new Database();
$database->databaseConfig($servername, $username, $password, $dbname);
$database->databaseConnect();
$error = $database->databaseGetErrorMessage();

?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<head>
<title><?=$softName?> :: Open Source WMS Server :: Delivering GIS data for the WEB :: Demo</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="keywords" content="WMS,SVG, OGC, MySQL, Internet Mapping, Internet GIS" />
<meta name="description" content="Open Source WMS for Geodata in SVG format" />
<script type="text/javascript" src="../cssjs/common.js"></script>
<link href="../cssjs/setup.css" rel="stylesheet" type="text/css" />
</head>
<body>

<table cellspacing="0" cellpadding="0" id="main">
<tr id="logo"><td colspan="2"><span class="logoprefix"><?=$softName."  ".$softVersion.$softEdition?></span></td></tr>
<tr id="top">
	<td id="left">Menu</td>
	<td id="right">
	<div id="progressbar"><div id="process" style="width: 0%;"></div></div>
	</td>
</tr>
<tr>
	<td id="progress">
<?CreateDemoMenu("GetLegendGraphic");?>
	</td>
	<td id="content">
		<p id="intro">
 <table class="tableContent">

        <tr>
          <td width="100%"><h2>GetLegendGraphic Request</h2>&nbsp;</td>
		</tr>

        <tr>
		<td>
	<table class="tableBlock">

      <!-- A FORM FOR SELECTION OF COORDINATE SYSTEM -->
<?php

if ($database->databaseConnection and $error == "") {
    echo "<script type=\"text/javascript\">";
    echo("\n");
    echo "function chkform()";
    echo("\n");
    echo "{";
    echo("\n");

    echo "if(document.form.WIDTH.value == \"\") {";
    echo("\n");
    echo "showErrorMessage(\"Insert WIDTH!\");";
    echo("\n");
    echo "document.form.WIDTH.focus();";
    echo("\n");
    echo "return false;";
    echo("\n");
    echo "}";
    echo("\n");

    echo "if(document.form.HEIGHT.value == \"\") {";
    echo("\n");
    echo "showErrorMessage(\"Insert HEIGHT!\");";
    echo("\n");
    echo "document.form.HEIGHT.focus();";
    echo("\n");
    echo "return false;";
    echo("\n");
    echo "}";
    echo("\n");

    $rs2 = $database->getRows4MetaGroupBy($tbmetaname,'layer');
    echo("\n");

    echo "if(";
    $iLayers = 1;
    $num = $database->getColumnsNumber($rs2);
    while ($line2 = $database->getColumns($rs2)) {
        if ($num == 1) {
            echo "!document.form.LAYERS.checked";
        }
        if ($num > 1) {
            echo "!document.form.LAYERS[" . $iLayers . "].checked ";
        }

        if ($iLayers != $num) {
            echo "&& ";
        }
        ++$iLayers;
    }
    echo ")";
    echo("\n");
    echo " { ";
    echo("\n");
    echo "showErrorMessage(\"please select a layer!\");";
    echo("\n");
    echo "return false;";
    echo("\n");
    echo "}";
    echo("\n");
    echo "}";
    echo("\n");
    echo "</script>";
    echo("\n");

    ?>

        <!-- The form for the Get Map Request starts here -->

      <form name="form" action="../WMS/getmapcap.php" method="get" onSubmit="return chkform()" >
              <tr>
                      <td width="200" id="done">VERSION</td>
                      <td width="100">
                      <input type="text" name="VERSION" value="<?=$wmsversion?>" readonly="readonly"  class="smallInput"></td>
              </tr>
          <tr>
             <td >SERVICE</td>
             <td >
             <input type="text" name="SERVICE" value="<?=$wmsservice?>" readonly="readonly" class="smallInput"></td>
          </tr>
                  <input type="hidden" name="BBOX" value="3444772">
              <tr>
                     <td width="200" id="done">REQUEST</td>
                     <td width="100">
                     <input type="text" name="REQUEST" value="GetLegendGraphic" readonly="readonly"  class="smallInput"></td>
              </tr>
              <tr><td colspan="2" >LAYERS: <td></tr>
              <tr><td colspan="2"></td>&nbsp;</tr>
             <tr align="left">
             <td colspan="1"></td>
			 <td colspan="1">
            <table class="tableInBlock">
            <td id="done"></td>
<?php
    $rs3 = $database->getRows4MetaGroupBy($tbmetaname,'layer');
    $iLayer = 1;
    $num = $database->getColumnsNumber($rs3);
    while ($line3 = $database->getColumns($rs3)) {
        $data3 = $line3["layer"];
?>
                  <tr align="left">
                     <td id="done"><?=$data3?></td><td> <input type="radio" name="LAYERS" value="<?=$data3?>" class="button3"></td>
                  </tr>
<?php
        ++$iLayer;
    }
    ?>
         </table>
      <td>
	  </tr>
	          <tr>
                 <td width="170" id="done">WIDTH</td>
                 <td width="100">
                 <input type="text" name="WIDTH" value="<?=$params['GetLegendGraphicWidth']?>" class="smallInput"></td>
              </tr>

              <tr>
                 <td width="170" id="done">HEIGHT</td>
                 <td width="100">
                 <input type="text" name="HEIGHT" value="<?=$params['GetLegendGraphicHeight']?>" class="smallInput"></td>
              </tr>
              <tr>
                 <td width="170" id="done">FORMAT</td>
                 <td width="100">
				 <select name="FORMAT" class="button4">
                    <optgroup label="Vector Image">
                       <!--<option value="image/svg+xml" selected="selected">SVG</option>
                       <option value="image/svgt+xml">SVGT</option>
                       <option value="image/svgb+xml">SVGB</option>
                       <option value="image/vml">VML</option>-->
                    </optgroup>
                    <optgroup label="Raster Image">
                       <option value="image/png">PNG</option>
                       <option value="image/jpeg">JPEG</option>
                       <option value="image/gif">GIF</option>
                       <option value="image/wbmp">WBMP</option>
                       <option value="image/bmp">BMP</option>
                       </optgroup>
                 </select>
				 </td>
              </tr>
              <tr>
                 <td colspan="2">&nbsp;</td>
              </tr>

              <tr>
                 <td><input onclick="GoBack();" name="button" value="Back" onmouseover="this.className='button1'" onmouseout="this.className='button'" class="button">
                 </td>
                 <td align="right">
                 <input type="submit" name="button" value="GetLegendGraphic" onmouseover="this.className='button1'" onmouseout="this.className='button'" class="button">
                 </td>
              </tr>
        </form>
<?php
}
$database->databaseClose();

?>

</table> <!-- THE FIRST TABLE ENDS HERE -->
	</td>
	</tr>

		 <tr>
		   <td >&nbsp;</td>
         </tr>



</table>
	</td>
</tr>
</table>

</body>
</html>