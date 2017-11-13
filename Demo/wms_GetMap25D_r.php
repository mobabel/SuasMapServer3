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
require_once '../Models/Cache.class.php';
require_once '../Models/CreateDemoMenu.php';

$database = new Database();
$database->databaseConfig($servername, $username, $password, $dbname);
$database->databaseConnect();
$error = $database->databaseGetErrorMessage();

$srs = $_GET['SRS'];

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
<link rel="stylesheet" href="../cssjs/js_color_picker_v2.css" media="screen">
<script src="../cssjs/color_functions.js"></script>
<script type="text/javascript" src="../cssjs/js_color_picker_v2.js"></script>
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
<?CreateDemoMenu("2.5D Navigation");?>
	</td>
	<td id="content">
		<p id="intro">
 <table class="tableContent">

        <tr>
          <td width="100%"><h2>GetMap25D Request</h2>&nbsp;</td>
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
    echo "<!-- BBOX -->";
    echo("\n");
    echo "if(document.form.minx.value == \"\")  {";
    echo("\n");
    echo "showErrorMessage(\"Insert minx!\");";
    echo("\n");
    echo "document.form.minx.focus();";
    echo("\n");
    echo "return false;";
    echo("\n");
    echo "}";
    echo("\n");
    echo "if(document.form.miny.value == \"\") {";
    echo("\n");
    echo "showErrorMessage(\"Insert miny!\");";
    echo("\n");
    echo "document.form.miny.focus();";
    echo("\n");
    echo "return false;";
    echo("\n");
    echo "}";
    echo("\n");

    echo "if(document.form.maxx.value == \"\") {";
    echo("\n");
    echo "showErrorMessage(\"Insert maxx!\");";
    echo("\n");
    echo "document.form.miny.focus();";
    echo("\n");
    echo "return false;";
    echo("\n");
    echo "}";
    echo("\n");

    echo "if(document.form.maxy.value == \"\") {";
    echo("\n");
    echo "showErrorMessage(\"Insert maxy!\");";
    echo("\n");
    echo "document.form.miny.focus();";
    echo("\n");
    echo "return false;";
    echo("\n");
    echo "}";
    echo("\n");

    echo "document.form.BBOX.value =document.form.minx.value + ',' + document.form.miny.value + ',' + document.form.maxx.value + ',' + document.form.maxy.value;";
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

    $rs2 = $database->getRowsBySrsGroupBy($tbmetaname, $srs, 'layer');
    echo("\n");

    echo "if(";
    $iLayers = 1;
    $num = $database->getColumnsNumber($rs2);
    while ($line2 = $database->getColumns($rs2)) {
        echo "(document.form.layer" . $iLayers . ".checked != true )";

        if ($iLayers != $num) {
            echo "&&";
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
    echo " var layerstr = \"\";";
    echo("\n");
    echo " var stylestr = \"\";";
    echo("\n");
    echo "for  (i = 1; i <= $num ; ++ i)";
    echo("\n");
    echo "{";
    echo "var chk= \"layer\" + i;";
    echo("\n");
    echo "var fila=document.getElementsByName(chk);";
    echo("\n");
    echo "<!--- var fila= \"layer\" + i;-->";
    echo("\n");
    echo "if (fila[0].checked == true){layerstr = layerstr + fila[0].value + \",\"; ";
    echo("\n");
    echo "stylestr = stylestr + document.form.style.value + \",\";";
    echo("}");
    echo("\n");
    echo "}";
    echo("\n");
    echo "layerstr = (layerstr.charAt(layerstr.length-1) == \",\") ? layerstr.slice(0,layerstr.length-1) : layerstr;";
    echo("\n");
    echo "stylestr = (stylestr.charAt(stylestr.length-1) == \",\") ? stylestr.slice(0,stylestr.length-1) : stylestr;";
    echo("\n");
    //echo "<!-- alert(layerstr.length) -->";
    //echo("\n");
    //echo "<!--alert(stylestr.length)-->";
    echo("\n");
    echo "<!--document.form.LAYERS.value =document.form.fila[0].value + \",\";-->";
    echo("\n");
    // echo	 "alert(\"you have selected LAYERS=\" + layerstr) ;";
    echo("\n");
    echo "document.form.LAYERS.value = layerstr;";
    // echo("\n");
    // echo   "alert(layerstr);";
    echo("\n");
    echo "document.form.STYLES.value = stylestr;";
    echo("\n");
    //echo "<!--alert(document.form.minx.value + document.form.miny.value);-->";
    echo("\n");
    print 'document.form.action ="../WMS/getmapcap.php";
			document.form.submit ();';
    echo "}";
    echo("\n");

    echo "</script>";
    echo("\n");
    ?>

        <!-- The form for the Get Map Request starts here -->

      <form name="form" action="../WMS/getmapcap.php" method="get">
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
                  <input type="hidden" name="LAYERS" value="LayerProblem">
                  <input type="hidden" name="STYLES" value="styleproblem">
              <tr>
                     <td width="200" id="done">REQUEST</td>
                     <td width="100">
                     <input type="text" name="REQUEST" value="GetMap25D" readonly="readonly"  class="smallInput"></td>
              </tr>
              <tr><td colspan="2" >LAYERS: <td></tr>
              <tr><td colspan="2"></td>&nbsp;</tr>
             <tr align="left">
             <td colspan="1"></td>
			 <td colspan="1">
            <table class="tableInBlock">
            <td id="done">Select All?</td><td><input type="checkbox" name="chkall" class="button1" onclick="checkall(this.form)"></td>
<?php
    $rs3 = $database->getRowsBySrsGroupBy($tbmetaname, $srs, 'layer');
    // $rs3 = mysql_query("SELECT * FROM  $tbname  WHERE srs = \"" . $srs . "\"  GROUP BY layer ", $con);
    $iLayer = 1;
    $num = $database->getColumnsNumber($rs3);
    while ($line3 = $database->getColumns($rs3)) {
        $data3 = $line3["layer"];
?>
                  <tr align="left">
                     <td id="done"><?=$data3?></td><td> <input type="checkbox" name="layer<?=$iLayer?>" value="<?=$data3?>" class="button3"></td>
                  </tr>
<?php
        ++$iLayer;
    }

    ?>
         </table>
      <td></tr>
          <tr>
                  <td width="200" id="done">STYLES</td>
                  <td width="100">
                  <input type="text" name="style" value="default" readonly="readonly"  class="smallInput"></td>
          </tr>
            <tr>
                <td width="200" id="done">SRS SELECTED IS:</td>
                <td width="100">
                <input type= "text" name="SRS" value="<?=$srs?>" readonly="readonly" class="smallInput"></td>
            </tr>


          <?php
    $rs4 = $database->getRowsMinMaxXYBySrs($tbmetaname, $srs);
    $line4 = $database->getColumns($rs4);
    $totalminx = $line4[0];
    $totalminy = $line4[1];
    $totalmaxx = $line4[2];
    $totalmaxy = $line4[3];

    ?>

              <tr>
                 <td  width="300" id="done">MIN. EASTING </td>
                 <td width="100">
                 <input type="text" name="minx" value="<?=$totalminx?>" class="smallInput"></td>
              </tr>

              <tr>
                 <td   width="300" id="done">MIN. NORTHING</td>
                 <td width="100">
                 <input type="text" name="miny" value="<?=$totalminy?>" class="smallInput"></td>
              </tr>

              <tr>
                 <td  width="300" id="done">MAX. EASTING</td>
                 <td width="100">
                 <input type="text" name="maxx" value="<?=$totalmaxx?>" class="smallInput"></td>
              </tr>

              <tr>
                 <td  width="300" id="done">MAX. NORTHING</td>
                 <td width="100">
                 <input type="text" name="maxy" value="<?=$totalmaxy?>" class="smallInput"></td>
              </tr>


              <tr>
                 <td width="170" id="done">WIDTH</td>
                 <td width="100">
                 <input type="text" name="WIDTH" value="800" class="smallInput"></td>
              </tr>

              <tr>
                 <td width="170" id="done">HEIGHT</td>
                 <td width="100">
                 <input type="text" name="HEIGHT" value="600" class="smallInput" ></td>
              </tr>

              <tr>
                 <td width="170" id="done">TRANSPARENT</td>
                 <td width="100">
                 <SELECT name="TRANSPARENT" class="button4">
				 <OPTION value=False selected>False</OPTION>
				 <OPTION value=True>True</OPTION>
				 </SELECT>
              </tr>

              <tr>
                 <td width="170" id="done">FORMAT</td>
                 <td width="100">
				 <select name="FORMAT" class="button4">
                    <optgroup label="Vector Image">
                       <option value="image/svg+xml">SVG</option>
                       <option value="image/svgt+xml">SVGT</option>
                       <option value="image/svgb+xml">SVGB</option>
                       <option value="image/svgz+xml">SVGZ</option>
                       <option value="image/svgtz+xml">SVGTZ</option>
                       <option value="image/svgbz+xml">SVGBZ</option>
                       <!--
                       <option value="image/pdf">PDF</option>
                       <option value="image/ezpdf">ezPDF</option>
                       <option value="image/swf">SWF</option>
                       <option value="image/vml">VML</option>
                       <option value="image/vrml">VRML</option>
                       -->
                    </optgroup>
                    <optgroup label="Raster Image">
                       <option value="image/png" selected>PNG</option>
                       <option value="image/jpeg">JPEG</option>
                       <option value="image/gif">GIF</option>
                       <option value="image/wbmp">WBMP</option>
                       <option value="image/bmp">BMP</option>
                       </optgroup>
                 </select>
				 </td>
              </tr>

              <tr>
                 <td width="170" id="done">EXCEPTIONS</td>
                 <td width="180">
				 <SELECT name="EXCEPTIONS" class="button4">
				 <OPTION value=application/vnd.ogc.se_xml selected>application/vnd.ogc.se_xml</OPTION>
				 <OPTION value=application/vnd.ogc.se_inimage>application/vnd.ogc.se_inimage</OPTION>
				 </SELECT>
				 </td>
              </tr>
              <tr>
                 <td  width="300" id="done">BGCOLOR</td>
                 <td width="100">
                 <div style="width:103px;height:20px;border:1px solid #7F9DB9;">
				 <input name="BGCOLOR" type="text" maxlength="7"
				 style="width:80px;font-size:12px;height:17px;float:left;border:0px;padding-top:2px;background-color: #FFFFFF;color: #000000"
				 size="10">
				 <img style="float:right;padding-right:1px;padding-top:1px" src="../img/select_arrow.gif"
				 onmouseover="this.src='../img/select_arrow_over.gif'" onmouseout="this.src='../img/select_arrow.gif'"
				 onclick="showColorPicker(this,document.form.BGCOLOR)">
				 </div>
				 <!--
				 <select id="idBGCOLOR" name="BGCOLORbackup" class="button4">
                 <option value="">None</option>
				 <option style="background-color: #FFFFFF;" value="FFFFFF">FFFFFF</option>
				 <option style="background-color: #FFBFBF;" value="FFBFBF">FFBFBF</option>
				 <option style="background-color: #F7C7C7;" value="F7C7C7">F7C7C7</option>
				 <option style="background-color: #EFCFCF;" value="EFCFCF">EFCFCF</option>
				 <option style="background-color: #E7D7D7;" value="E7D7D7">E7D7D7</option>

				 <option style="background-color: #FFDFBF;" value="FFDFBF">FFDFBF</option>
				 <option style="background_color: #F7DFC7;" value="F7DFC7" selected>F7DFC7</option>
				 <option style="background-color: #EFDFCF;" value="EFDFCF">EFDFCF</option>
				 <option style="background-color: #E7DFD7;" value="E7DFD7">E7DFD7</option>

				 <option style="background-color: #FFFFBF;" value="FFFFBF">FFFFBF</option>
				 <option style="background-color: #F7F7C7;" value="F7F7C7">F7F7C7</option>
				 <option style="background-color: #EFEFCF;" value="EFEFCF">EFEFCF</option>
				 <option style="background-color: #E7E7D7;" value="E7E7D7">E7E7D7</option>

				 <option style="background-color: #DFFFBF;" value="DFFFBF">DFFFBF</option>
				 <option style="background-color: #DFF7C7;" value="DFF7C7">DFF7C7</option>
				 <option style="background-color: #DFEFCF;" value="DFEFCF">DFEFCF</option>
				 <option style="background-color: #DFE7D7;" value="DFE7D7">DFE7D7</option>

				 <option style="background-color: #BFFFDF;" value="BFFFDF">BFFFDF</option>
				 <option style="background-color: #C7F7DF;" value="C7F7DF">C7F7DF</option>
				 <option style="background-color: #CFEFDF;" value="CFEFDF">CFEFDF</option>
				 <option style="background-color: #D7E7DF;" value="D7E7DF">D7E7DF</option>

				 <option style="background-color: #BFFFFF;" value="BFFFFF">BFFFFF</option>
				 <option style="background-color: #C7F7F7;" value="C7F7F7">C7F7F7</option>
				 <option style="background-color: #CFEFEF;" value="CFEFEF">CFEFEF</option>
				 <option style="background-color: #D7E7E7;" value="D7E7E7">D7E7E7</option>

				 <option style="background-color: #BFDFFF;" value="BFDFFF">BFDFFF</option>
				 <option style="background-color: #C7DFF7;" value="C7DFF7">C7DFF7</option>
				 <option style="background-color: #CFDFEF;" value="CFDFEF">CFDFEF</option>
				 <option style="background-color: #D7DFE7;" value="D7DFE7">D7DFE7</option>

				 <option style="background-color: #BFBFFF;" value="BFBFFF">BFBFFF</option>
				 <option style="background-color: #C7C7F7;" value="C7C7F7">C7C7F7</option>
				 <option style="background-color: #CFCFEF;" value="CFCFEF">CFCFEF</option>
				 <option style="background-color: #D7D7E7;" value="D7D7E7">D7D7E7</option>

				 <option style="background-color: #DFBFFF;" value="DFBFFF">DFBFFF</option>
				 <option style="background-color: #DFC7F7;" value="DFC7F7">DFC7F7</option>
				 <option style="background-color: #DFCFEF;" value="DFCFEF">DFCFEF</option>
				 <option style="background-color: #DFD7E7;" value="DFD7E7">DFD7E7</option>

				 <option style="background-color: #FFBFFF;" value="FFBFFF">FFBFFF</option>
				 <option style="background-color: #F7C7F7;" value="F7C7F7">F7C7F7</option>
				 <option style="background-color: #EFCFEF;" value="EFCFEF">EFCFEF</option>
				 <option style="background-color: #E7D7E7;" value="E7D7E7">E7D7E7</option>

				 <option style="background-color: #FFBFDF;" value="FFBFDF">FFBFDF</option>
				 <option style="background-color: #F7C7DF;" value="F7C7DF">F7C7DF</option>
				 <option style="background-color: #EFCFDF;" value="EFCFDF">EFCFDF</option>
				 <option style="background-color: #E7D7DF;" value="E7D7DF">E7D7DF</option>

				 <option style="background-color: #808080;" value="808080">808080</option>
				 <option style="background-color: #9F9F9F;" value="9F9F9F">9F9F9F</option>
				 <option style="background-color: #BFBFBF;" value="BFBFBF">BFBFBF</option>
				 <option style="background-color: #DFDFDF;" value="DFDFDF">DFDFDF</option>
				 </select>
				 -->
				 </td>
              </tr>
              <tr>
                 <td  width="300" id="done">SKYCOLOR</td>
                 <td width="100">
				 <select id="idSKYCOLOR" name="SKYCOLOR" class="button4">
                                 <option value="">None</option>
				 <option style="background-color: #FFFFFF;" value="FFFFFF" >FFFFFF</option>
				 <option style="background-color: #D6D7FF;" value="D6D7FF">D6D7FF</option>
				 <option style="background-color: #CECFFF;" value="CECFFF">CECFFF</option>
				 <option style="background-color: #B5AEFF;" value="B5AEFF">B5AEFF</option>
				 <option style="background-color: #A59AFF;" value="A59AFF">A59AFF</option>
				 <option style="background-color: #8475FF;" value="8475FF">8475FF</option>
				 <option style="background-color: #7262FF;" value="7262FF">7262FF</option>
				 <option style="background-color: #6453FF;" value="6453FF">6453FF</option>
				 <option style="background-color: #5846FF;" value="5846FF">5846FF</option>
				 <option style="background-color: #513EFF;" value="513EFF">513EFF</option>
				 <option style="background-color: #3620FF;" value="3620FF">3620FF</option>
				 <option style="background-color: #1F06FF;" value="1F06FF">1F06FF</option>
				 <option style="background-color: #1700EC;" value="1700EC">1700EC</option>
				 <option style="background-color: #1500D5;" value="1500D5">1500D5</option>
				 <option style="background-color: #1200B7;" value="1200B7" selected>1200B7</option>
				 <option style="background-color: #0F0097;" value="0F0097">0F0097</option>
				 <option style="background-color: #0E0088;" value="0E0088">0E0088</option>
				 <option style="background-color: #0C0073;" value="0C0073">0C0073</option>
				 <option style="background-color: #0A0060;" value="0A0060">0A0060</option>
				 <option style="background-color: #07004D;" value="07004D">07004D</option>
				 </select>
				 </td>
              </tr>

              <tr>
                 <td  width="300" id="done">Horizonal Angle</td>
                 <td width="100">
                 <input type="text" name="HANGLE" value="0" class="smallInput"></td>
              </tr>
              <tr>
                 <td  width="300" id="done">Vertical Angle</td>
                 <td width="100">
                 <input type="text" name="VANGLE" value="70" class="smallInput"></td>
              </tr>
              <!--
              <tr>
                 <td  width="300" id="done">Distance (Pixels)</td>
                 <td width="100">
                 <input type="hidden" type="text" name="DISTANCE" value="0" class="smallInput"></td>
              </tr>
              -->
              <tr>
                 <td colspan="2">&nbsp;</td>
              </tr>

              <tr>
                 <td><div class="begin"><input onclick="GoBack();" name="button" value="Back" onmouseover="this.className='button1'" onmouseout="this.className='button'" class="button">
                 </td>
                 <td align="right">
                 <input onclick="chkform();" name="button" value="GetMap25D" onmouseover="this.className='button1'" onmouseout="this.className='button'" class="button">
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