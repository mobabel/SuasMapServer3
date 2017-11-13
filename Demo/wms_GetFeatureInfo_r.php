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
<?CreateDemoMenu("GetFeatureInfo");?>
	</td>
	<td id="content">
		<p id="intro">
 <table class="tableContent">

        <tr>
          <td width="100%"><h2>GetFeatureInfo Request</h2>&nbsp;</td>
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

    echo "if(document.form.RADIUS.value == \"\") {";
    echo("\n");
    echo "showErrorMessage(\"Insert RADIUS!\");";
    echo("\n");
    echo "document.form.RADIUS.focus();";
    echo("\n");
    echo "return false;";
    echo("\n");
    echo "}";
    echo("\n");

    echo "if(document.form.X.value == \"\") {";
    echo("\n");
    echo "showErrorMessage(\"Insert X!\");";
    echo("\n");
    echo "document.form.X.focus();";
    echo("\n");
    echo "return false;";
    echo("\n");
    echo "}";
    echo("\n");

    echo "if(document.form.Y.value == \"\") {";
    echo("\n");
    echo "showErrorMessage(\"Insert Y!\");";
    echo("\n");
    echo "document.form.Y.focus();";
    echo("\n");
    echo "return false;";
    echo("\n");
    echo "}";
    echo("\n");

    echo "document.form.BBOX.value =document.form.minx.value + ',' + document.form.miny.value + ',' + document.form.maxx.value + ',' + document.form.maxy.value;";
    echo("\n");

    $rs2 = $database->getRowsBySrsGroupBy($tbmetaname, $srs, 'layer');
    echo("\n");
    echo "if(";
    $iLayers = 1;
    $num = $database->getColumnsNumber($rs2);
    while ($line2 = $database->getColumns($rs2)) {
        echo "(document.form.query_layer" . $iLayers . ".checked != true )";

        if ($iLayers != $num) {
            echo "&&";
        }
        ++$iLayers;
    }
    echo ")";
    echo("\n");
    echo " { ";
    echo("\n");
    echo "showErrorMessage(\"please select a query layer!\");";
    echo("\n");
    echo "return false;";
    echo("\n");
    echo "}";
    echo("\n");
    echo " var layerstr = \"\";";
    echo("\n");
    // echo	" var stylestr = \"\";";
    echo("\n");
    echo "for  (i = 1; i <= $num ; ++ i)";
    echo("\n");
    echo "{";
    echo "var chk= \"query_layer\" + i;";
    echo("\n");
    echo "var fila=document.getElementsByName(chk);";
    echo("\n");
    // echo	 "<!--- var fila= \"query_layer\" + i;-->";
    echo("\n");
    echo "if (fila[0].checked == true){layerstr = layerstr + fila[0].value + \",\"; ";
    // echo("\n");
    // echo  "stylestr = stylestr + document.form.style.value + \",\"; ";
    echo("}");
    echo("\n");
    echo "}";
    echo("\n");
    echo "layerstr = (layerstr.charAt(layerstr.length-1) == \",\") ? layerstr.slice(0,layerstr.length-1) : layerstr;";
    echo("\n");
    // echo   "stylestr = (stylestr.charAt(stylestr.length-1) == \",\") ? stylestr.slice(0,stylestr.length-1) : stylestr;";
    echo("\n");
    // echo	 "<!-- alert(layerstr.length) -->";
    echo("\n");
    // echo	 "<!--alert(stylestr.length)-->";
    echo("\n");
    // echo	 "<!--document.form.QUERY_LAYERS.value =document.form.fila[0].value + \",\";-->";
    echo("\n");
    // echo	 "alert(\"you have selected QUERY_LAYERS=\" + layerstr) ;";
    echo("\n");
    echo "document.form.QUERY_LAYERS.value = layerstr;";
    echo("\n");
    echo("\n");
    // echo	 "document.form.STYLES.value = stylestr;";
    echo("\n");
    // echo   "<!--alert(document.form.minx.value + document.form.miny.value);-->";
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
                  <input type="hidden" name="BBOX" value="3444772">
                  <input type="hidden" name="QUERY_LAYERS" value="LayerProblem">
              <tr>
                     <td width="200" id="done">SERVICE</td>
                     <td width="100">
                     <input type="text" name="SERVICE" value="<?=$wmsservice?>" readonly="readonly"  class="smallInput"></td>
              </tr>
              <tr>
                     <td width="200" id="done">REQUEST</td>
                     <td width="100">
                     <input type="text" name="REQUEST" value="GetFeatureInfo" readonly="readonly"  class="smallInput"></td>
              </tr>
              <tr><td colspan="2" >QUERY_LAYERS: <td></tr>
              <tr><td colspan="2"></td>&nbsp;</tr>
<?php
    $rs3 = $database->getRowsBySrsGroupBy($tbmetaname, $srs, 'layer');
?>
             <tr align="left">
             <td colspan="1"></td>
			 <td colspan="1">
            <table class="tableInBlock">
            <td id="done">Select All?</td><td><input type="checkbox" name="chkall" class="button1" onclick="checkall(this.form)"></td>
<?php
    $iLayer = 1;
    $num = $database->getColumnsNumber($rs3);
    while ($line3 = $database->getColumns($rs3)) {
        $data3 = $line3["layer"];
?>
                  <tr align="left">
                     <td id="done"><?=$data3?></td><td> <input type="checkbox" name="query_layer<?=$iLayer?>" value="<?=$data3?>" class="button3"></td>
                  </tr>
              <?php
        ++$iLayer;
    }

    ?>
         </table>
      <td></tr>
          <tr>
                  <td width="200" id="done">RADIUS(pixel)</td>
                  <td width="100">
                  <input type="text" name="RADIUS" value="2"  class="smallInput"></td>
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
                 <input type="text" name="HEIGHT" value="600" class="smallInput"></td>
              </tr>

             <tr>
                 <td width="170" id="done">X</td>
                 <td width="100">
                 <input type="text" name="X" value="400" class="smallInput"></td>
              </tr>

              <tr>
                 <td width="170" id="done">Y</td>
                 <td width="100">
                 <input type="text" name="Y" value="400" class="smallInput"></td>
              </tr>

              <tr>
                 <td width="170" id="done">INFO_FORMAT</td>
                 <td width="180">
				 <SELECT name="INFO_FORMAT" class="button4">
				 <OPTION value=text/xml selected>text/xml</OPTION>
				 <OPTION value=text/html>text/html</OPTION>
				 </SELECT>
				 </td>
              </tr>
              <tr>
                 <td width="100">
                 </td>
              </tr>

              <tr>
                 <td colspan="2">&nbsp;</td>
              </tr>

              <tr>
                 <td><input onclick="GoBack();" name="button" value="Back" onmouseover="this.className='button1'" onmouseout="this.className='button'" class="button">
                 </td>
                 <td align="right">
                 <input type="submit" name="button" value="GetFeatureInfo" onmouseover="this.className='button1'" onmouseout="this.className='button'" class="button">
                 </td>
              </tr>
<?php
}
$database->databaseClose();
?>
     </form>



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