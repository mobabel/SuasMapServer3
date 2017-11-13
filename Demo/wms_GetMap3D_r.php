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
<?CreateDemoMenu("3D Navigation");?>
	</td>
	<td id="content">
		<p id="intro">
 <table class="tableContent">

        <tr>
          <td width="100%"><h2>GetMap3D Request</h2>&nbsp;</td>
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

    echo "document.form.POI.value =document.form.POIX.value + ',' + document.form.POIY.value + ',' + document.form.POIZ.value;";
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
    echo " var elevationstr = \"\";";
    echo("\n");
    echo "for  (i = 1; i <= $num ; ++ i)";
    echo("\n");
    echo "{";
    echo "var chk= \"layer\" + i;";
    echo("\n");
    echo "var ele= \"elevation\" + i;";
    echo("\n");
    echo "var layerarray=document.getElementsByName(chk);";
    echo("\n");
    echo "var elearray=document.getElementsByName(ele);";
    echo("\n");
    echo "<!--- var fila= \"layer\" + i;-->";
    echo("\n");
    echo "if (layerarray[0].checked == true){layerstr = layerstr + layerarray[0].value + \",\"; ";
    echo("\n");
    echo "stylestr = stylestr + document.form.style.value + \",\";";
    echo("\n");
    echo "elevationstr = elevationstr + elearray[0].value + \",\";";
    echo("}");
    echo("\n");
    echo "}";
    echo("\n");
    echo "layerstr = (layerstr.charAt(layerstr.length-1) == \",\") ? layerstr.slice(0,layerstr.length-1) : layerstr;";
    echo("\n");
    echo "stylestr = (stylestr.charAt(stylestr.length-1) == \",\") ? stylestr.slice(0,stylestr.length-1) : stylestr;";
    echo("\n");
    echo "elevationstr = (elevationstr.charAt(elevationstr.length-1) == \",\") ? elevationstr.slice(0,elevationstr.length-1) : elevationstr;";
    echo("\n");
    // echo	 "alert(\"you have selected LAYERS=\" + layerstr) ;";
    echo("\n");
    echo "document.form.LAYERS.value = layerstr;";
    echo("\n");
    echo "document.form.STYLES.value = stylestr;";
    echo("\n");
    echo "document.form.ELEVATIONS.value = elevationstr;";

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
                  <input type="hidden" name="BBOX" value="0,0,0,0">
                  <input type="hidden" name="LAYERS" value="LayerProblem">
                  <input type="hidden" name="STYLES" value="styleproblem">
                  <input type="hidden" name="POI" value="0,0,0">
                  <input type="hidden" name="ELEVATIONS" value="elevationproblem">
              <tr>
                     <td width="200" id="done">REQUEST</td>
                     <td width="100">
                     <input type="text" name="REQUEST" value="GetMap3D" readonly="readonly"  class="smallInput"></td>
              </tr>
              <tr><td colspan="2" >LAYERS: <td></tr>
              <tr><td colspan="2"></td>&nbsp;</tr>
             <tr align="left">
             <td colspan="1"></td>
			 <td colspan="1">
            <table class="tableInBlock">
            <td id="done">Select All?</td>
			<td><input type="checkbox" name="chkall" class="button1" onclick="checkall(this.form)"></td>
			<td id="done">Elevation</td>
<?php
    $rs3 = $database->getRowsBySrsGroupBy($tbmetaname, $srs, 'layer');
    // $rs3 = mysql_query("SELECT * FROM  $tbname  WHERE srs = \"" . $srs . "\"  GROUP BY layer ", $con);
    $iLayer = 1;
    $num = $database->getColumnsNumber($rs3);
    while ($line3 = $database->getColumns($rs3)) {
        $data3 = $line3["layer"];
        $data4 = $line3["elevation"];
?>
                  <tr align="left">
                     <td id="done"><?=$data3?></td>
					 <td id="done">
					 <input type="checkbox" name="layer<?=$iLayer?>" value="<?=$data3?>" class="button3">
                     </td>
					 <td id="done">
					 <input type="text" name="elevation<?=$iLayer?>" value="<?=$data4?>"  class="smallInput">
					 </td>
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
                 <td  width="300" id="done">MIN. EASTING</td>
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
                 <input type="text" name="HEIGHT" value="600" class="smallInput"></td>
              </tr>

              <tr>
                 <td width="170" id="done">FORMAT</td>
                 <td width="100">
				 <select name="FORMAT" class="button4">
                       <option value="model/vrml" selected>VRML</option>
                       <option value="model/vrmlz">VRMLZ</option>
                       <!--<option value="model/vrml">GeoVRML</option>-->
                       <option value="model/x3d+xml">X3D</option>
                       <option value="model/x3dz">X3DZ</option>
                       <option value="application/vnd.google-earth.kml+xml">KML</option>
                       <option value="application/vnd.google-earth.kmz">KMZ</option>
                 </select>
				 </td>
              </tr>

              <tr>
                 <td width="170" id="done">EXCEPTIONS</td>
                 <td width="180">
				 <SELECT name="EXCEPTIONS" class="button4">
				 <OPTION value=application/vnd.ogc.se_xml selected>application/vnd.ogc.se_xml</OPTION>
				 <OPTION value=application/vnd.ogc.se_blank>application/vnd.ogc.se_blank</OPTION>
				 </SELECT>
				 </td>
              </tr>

              <tr>
                 <td width="170" id="done">POI(X Y Z)</td>
                 <td width="100">
                 <input type="text" name="POIX" value="<?=($totalmaxx+$totalminx)/2?>" class="smallInput">
                 <input type="text" name="POIY" value="<?=($totalmaxy+$totalminy)/2?>" class="smallInput">
                 <input type="text" name="POIZ" value="0" class="smallInput">
				 </td>
              </tr>
              <tr>
                 <td width="170" id="done">PITCH</td>
                 <td width="100">
                 <input type="text" name="PITCH" value="20" class="smallInput"></td>
              </tr>
              <tr>
                 <td width="170" id="done">YAW</td>
                 <td width="100">
                 <input type="text" name="YAW" value="180" class="smallInput"></td>
              </tr>
              <tr>
                 <td width="170" id="done">ROLL</td>
                 <td width="100">
                 <input type="text" name="ROLL" value="0" class="smallInput"></td>
              </tr>
              <tr>
                 <td width="170" id="done">DISTANCE</td>
                 <td width="100">
                 <input type="text" name="DISTANCE" value="<?=sqrt(($totalmaxx-$totalminx)*($totalmaxx-$totalminx)+($totalmaxy-$totalminy)*($totalmaxy-$totalminy))?>" class="smallInput"></td>
              </tr>
              <tr>
                 <td width="170" id="done">AOV</td>
                 <td width="100">
                 <input type="text" name="AOV" value="70" class="smallInput"></td>
              </tr>
              <tr>
                 <td width="170" id="done">ENVIRONMENT</td>
                 <td width="180">
				 <SELECT name="ENVIRONMENT" class="button4">
				 <OPTION value=on>on</OPTION>
				 <OPTION value=off selected>off</OPTION>
				 </SELECT>
				 </td>
              </tr>
              <tr>
                 <td  width="300" id="done">SKYCOLOR</td>
                 <td width="100">
                 <select id="idSKYCOLOR" name="SKYCOLOR" class="button4">
				 <option style="background-color: #FFFFFF;" value="FFFFFF">FFFFFF</option>
				 <option style="background-color: #D6D7FF;" value="D6D7FF">D6D7FF</option>
				 <option style="background-color: #CECFFF;" value="CECFFF">CECFFF</option>
				 <option style="background-color: #B5AEFF;" value="B5AEFF">B5AEFF</option>
				 <option style="background-color: #A59AFF;" value="A59AFF">A59AFF</option>
				 <option style="background-color: #8475FF;" value="8475FF">8475FF</option>
				 <option style="background-color: #7262FF;" value="7262FF" selected>7262FF</option>
				 <option style="background-color: #6453FF;" value="6453FF">6453FF</option>
				 <option style="background-color: #5846FF;" value="5846FF">5846FF</option>
				 <option style="background-color: #513EFF;" value="513EFF">513EFF</option>
				 <option style="background-color: #3620FF;" value="3620FF">3620FF</option>
				 <option style="background-color: #1F06FF;" value="1F06FF">1F06FF</option>
				 <option style="background-color: #1700EC;" value="1700EC">1700EC</option>
				 <option style="background-color: #1500D5;" value="1500D5">1500D5</option>
				 <option style="background-color: #1200B7;" value="1200B7">1200B7</option>
				 <option style="background-color: #0F0097;" value="0F0097">0F0097</option>
				 <option style="background-color: #0E0088;" value="0E0088">0E0088</option>
				 <option style="background-color: #0C0073;" value="0C0073">0C0073</option>
				 <option style="background-color: #0A0060;" value="0A0060">0A0060</option>
				 <option style="background-color: #07004D;" value="07004D">07004D</option>
				 </select>

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
				 <select id="idBGCOLOR" name="BGCOLOR" class="button4">
				 <option style="background-color: #FFFFFF;" value="FFFFFF">FFFFFF</option>

				 <option style="background-color: #800000;" value="800000">800000</option>
				 <option style="background-color: #701010;" value="701010">701010</option>
				 <option style="background-color: #602020;" value="602020">602020</option>
				 <option style="background-color: #503030;" value="503030">503030</option>

				 <option style="background-color: #804000;" value="804000">804000</option>
				 <option style="background-color: #704010;" value="704010">704010</option>
				 <option style="background-color: #604020;" value="604020">604020</option>
				 <option style="background-color: #504030;" value="504030">504030</option>

				 <option style="background-color: #808000;" value="808000">808000</option>
				 <option style="background-color: #707010;" value="707010">707010</option>
				 <option style="background-color: #606020;" value="606020">606020</option>
				 <option style="background-color: #505030;" value="505030">505030</option>

				 <option style="background-color: #408000;" value="408000" selected>408000</option>
				 <option style="background-color: #407010;" value="407010">407010</option>
				 <option style="background-color: #406020;" value="406020">406020</option>
				 <option style="background-color: #405030;" value="405030">405030</option>

				 <option style="background-color: #008000;" value="008000">008000</option>
				 <option style="background-color: #107010;" value="107010">107010</option>
				 <option style="background-color: #206020;" value="206020">206020</option>
				 <option style="background-color: #305030;" value="305030">305030</option>

				 <option style="background-color: #008040;" value="008040">008040</option>
				 <option style="background-color: #107040;" value="107040">107040</option>
				 <option style="background-color: #206040;" value="206040">206040</option>
				 <option style="background-color: #305040;" value="305040">305040</option>

				 <option style="background-color: #008080;" value="008080">008080</option>
				 <option style="background-color: #107070;" value="107070">107070</option>
				 <option style="background-color: #206060;" value="206060">206060</option>
				 <option style="background-color: #305050;" value="305050">305050</option>

				 <option style="background-color: #004080;" value="004080">004080</option>
				 <option style="background-color: #104070;" value="104070">104070</option>
				 <option style="background-color: #204060;" value="204060">204060</option>
				 <option style="background-color: #304050;" value="304050">304050</option>

				 <option style="background-color: #000080;" value="000080">000080</option>
				 <option style="background-color: #000080;" value="000080">000080</option>
				 <option style="background-color: #202060;" value="202060">202060</option>
				 <option style="background-color: #303050;" value="303050">303050</option>

				 <option style="background-color: #400080;" value="400080">400080</option>
				 <option style="background-color: #400080;" value="400080">400080</option>
				 <option style="background-color: #402060;" value="402060">402060</option>
				 <option style="background-color: #403050;" value="403050">403050</option>

				 <option style="background-color: #800080;" value="800080">800080</option>
				 <option style="background-color: #701070;" value="701070">701070</option>
				 <option style="background-color: #602060;" value="602060">602060</option>
				 <option style="background-color: #503050;" value="503050">503050</option>

				 <option style="background-color: #808080;" value="808080">202020</option>
				 <option style="background-color: #9F9F9F;" value="9F9F9F">404040</option>
				 <option style="background-color: #BFBFBF;" value="BFBFBF">606060</option>
				 <option style="background-color: #DFDFDF;" value="DFDFDF">808080</option>

				 </select>
				 -->
				 </td>
              </tr>
              <tr>
                 <td width="170" id="done">BGIMAGE</td>
                 <td width="180">
				 <SELECT name="BGIMAGE" class="button4">
				 <OPTION value="bg_icemountns.png" selected>icemountns</OPTION>
				 <OPTION value="bg_skyclouds.png">skyclouds</OPTION>
				 </SELECT>
				 </td>
              </tr>
              <tr>
                 <td colspan="2">&nbsp;</td>
              </tr>

              <tr>
                 <td>
                 <input onclick="GoBack();" name="button" value="Back" onmouseover="this.className='button1'" onmouseout="this.className='button'" class="button">
                 </td>
                 <td align="right">
                 <input onclick="chkform();" name="button" value="GetMap3D" onmouseover="this.className='button1'" onmouseout="this.className='button'" class="button">
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