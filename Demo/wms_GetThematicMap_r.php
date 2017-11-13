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
<script type="text/javascript" src="../cssjs/lib/prototype/prototype.js"></script>
<script type="text/javascript" src="../cssjs/common.js"></script>

<link href="../cssjs/setup.css" rel="stylesheet" type="text/css" />
<link href="../cssjs/layout.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="../cssjs/lib/windows_js_1.3/javascripts/effects.js"> </script>
<script type="text/javascript" src="../cssjs/lib/windows_js_1.3/javascripts/window.js"> </script>
<script type="text/javascript" src="../cssjs/lib/windows_js_1.3/javascripts/window_effects.js"> </script>
<script type="text/javascript" src="../cssjs/lib/windows_js_1.3/javascripts/debug.js"> </script>

<link href="../cssjs/lib/windows_js_1.3/themes/default.css" rel="stylesheet" type="text/css" >	 </link>
<link href="../cssjs/lib/windows_js_1.3/themes/spread.css" rel="stylesheet" type="text/css" >	 </link>
<link href="../cssjs/lib/windows_js_1.3/themes/alert.css" rel="stylesheet" type="text/css" >	 </link>
<link href="../cssjs/lib/windows_js_1.3/themes/alert_lite.css" rel="stylesheet" type="text/css" >	 </link>
<link href="../cssjs/lib/windows_js_1.3/themes/alphacube.css" rel="stylesheet" type="text/css" >	 </link>
<link href="../cssjs/lib/windows_js_1.3/themes/debug.css" rel="stylesheet" type="text/css" >	 </link>

<!--  Doc Part-->
<script type="text/javascript" src="../cssjs/lib/windows_js_1.3/documentation/js/application.js"> </script>
<link href="../cssjs/lib/windows_js_1.3/stylesheets/login.css" rel="stylesheet" type="text/css" >	 </link>
<link href="../cssjs/lib/windows_js_1.3/stylesheets/style.css" rel="stylesheet" type="text/css" >	 </link>
  <script>
    var contentWin;
  </script>

      <style type="text/css" media="all" title="Default">
      @import "main.css";
      #dragDiv   { -moz-user-select:none; position:absolute; border:1px double #000; background-color:buttonface; width:200px; height:100px; color:#CC0000; text-align:center;}
    </style>
</head>
<body>

<table cellspacing="0" cellpadding="0" id="main">
<tr id="logo"><td colspan="2"><span class="logoprefix"><?=$softName . "  " . $softVersion . $softEdition?></span></td></tr>
<tr id="top">
	<td id="left">Menu</td>
	<td id="right">
	<div id="progressbar"><div id="process" style="width: 0%;"></div></div>
	</td>
</tr>

<tr>
	<td id="progress">
<?php CreateDemoMenu("GetThematicMap");

?>
	</td>
	<td id="content">
		<p id="intro"></p>

      <form name="form" method="get">
 <table class="tableContent">
        <tr>
          <td width="100%" colspan=2><h2>GetThematicMap Request</h2>&nbsp;
		  </td>
		</tr>

       <tr>
          <td  class=contenttopl width=25%>Select Parameters:</td>
          <td class=contenttopr width=75% align=right><a id=xcontent1 onclick="ShowHide('content1');"><img src="../img/minimize.png" alt="minimize"></a></td>
       </tr>

       <tr>
          <td class="contenttext" colspan=2>
          <DIV id=content1>
	      <table border="0"  width="100%"   align="center">

      <!-- A FORM FOR SELECTION OF COORDINATE SYSTEM -->
<?php

if ($database->databaseConnection and $error == "") {
    echo "<script type=\"text/javascript\">";
    echo("\n");
    // ==========================submitOpenLayers=======================
    echo "function submitOpenLayers()";
    echo "{";
    echo "if(document.form.minx.value == \"\")  {";
    echo "showErrorMessage(\"Insert minx!\");";
    echo "document.form.minx.focus();";
    echo "return false;";
    echo "}";
    echo "if(document.form.miny.value == \"\") {";
    echo "showErrorMessage(\"Insert miny!\");";
    echo "document.form.miny.focus();";
    echo "return false;";
    echo "}";

    echo "if(document.form.maxx.value == \"\") {";
    echo "showErrorMessage(\"Insert maxx!\");";
    echo "document.form.miny.focus();";
    echo "return false;";
    echo "}";

    echo "if(document.form.maxy.value == \"\") {";
    echo "showErrorMessage(\"Insert maxy!\");";
    echo "document.form.miny.focus();";
    echo "return false;";
    echo "}";

    echo "document.form.BBOX.value =document.form.minx.value + ',' + document.form.miny.value + ',' + document.form.maxx.value + ',' + document.form.maxy.value;";

    echo "if(document.form.WIDTH.value == \"\") {";
    echo "showErrorMessage(\"Insert WIDTH!\");";
    echo "document.form.WIDTH.focus();";
    echo "return false;";
    echo "}";

    echo "if(document.form.HEIGHT.value == \"\") {";
    echo "showErrorMessage(\"Insert HEIGHT!\");";
    echo "document.form.HEIGHT.focus();";
    echo "return false;";
    echo "}";

    $rs2 = $database->getRowsBySrsGroupBy($tbmetaname, $srs, 'layer');
    echo("\n");

    echo "if(";
    $iLayers = 0;
    $num = $database->getColumnsNumber($rs2);
    if ($num == 1) {
        while ($line2 = $database->getColumns($rs2)) {
            echo "document.form.layer.checked != true";
        }
    } else {
        while ($line2 = $database->getColumns($rs2)) {
            echo "(document.form.layer[" . $iLayers . "].checked != true )";

            if ($iLayers != $num-1) {
                echo "&&";
            }
            ++$iLayers;
        }
    }
    echo ")";
    echo " { ";
    echo "showErrorMessage(\"please select a layer!\");";
    echo "return false;";
    echo "}";
    echo " var layerstr = \"\";";
    echo " var stylestr = \"\";";

    if ($num == 1) {
        echo "var fila=document.form.layer;";
        echo "if (fila.checked == true){";
        echo "layerstr = fila.value; ";
        echo "stylestr = document.form.style.value;";
        echo "}";
    } else {
        echo "for  (i = 0; i < $num ; ++ i)";
        echo "{";
        echo "var fila=document.form.layer;";
        echo "if (fila[i].checked == true){";
        echo "layerstr = layerstr + fila[i].value + \",\"; ";
        echo "stylestr = stylestr + document.form.style.value + \",\";";
        echo "}";
        echo "}";
    }

    echo "layerstr = (layerstr.charAt(layerstr.length-1) == \",\") ? layerstr.slice(0,layerstr.length-1) : layerstr;";
    echo "stylestr = (stylestr.charAt(stylestr.length-1) == \",\") ? stylestr.slice(0,stylestr.length-1) : stylestr;";
    echo "document.form.LAYERS.value = layerstr;";
    echo "document.form.STYLES.value = stylestr;";
    print 'document.form.action ="../Models/GetThematicMap.php";
			document.form.submit ();';
    echo "}";
    // ==========================submitOpenLayers=======================
    echo "</script>";
    echo("\n");

    ?>

        <!-- The form for the Get Map Request starts here -->

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
                     <input type="text" name="REQUEST" value="GetMap" readonly="readonly"  class="smallInput"></td>
              </tr>
              <tr><td colspan="2" >LAYERS: <td></tr>
              <tr><td colspan="2"></td>&nbsp;</tr>
             <tr align="left">
             <td colspan="1"></td>
			 <td colspan="1">
            <table class="tableInBlock">
            <td id="done"><div class="begin"><a href="javascript: checkall_s(document.form.layer);">CheckAll</a></div></td>
			<td><div class="begin"><a href="javascript: uncheckAll_s(document.form.layer);">UncheckAll</a></div></td>
<?php
    $rs3 = $database->getRowsBySrsGroupBy($tbmetaname, $srs, 'layer');
    // $rs3 = mysql_query("SELECT * FROM  $tbname  WHERE srs = \"" . $srs . "\"  GROUP BY layer ", $con);
    $iLayer = 1;
    $num = $database->getColumnsNumber($rs3);
    while ($line3 = $database->getColumns($rs3)) {
        $data3 = $line3["layer"];

        ?>
                  <tr align="left">
                     <td id="done"><?=$data3?></td><td> <input type="checkbox" name="layer" value="<?=$data3?>" class="button3"></td>
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
                 <td width="170" id="done">EXCEPTIONS</td>
                 <td width="180">
				 <SELECT name="EXCEPTIONS" class="button4">
				 <OPTION value=application/vnd.ogc.se_xml selected>application/vnd.ogc.se_xml</OPTION>
				 <OPTION value=application/vnd.ogc.se_inimage>application/vnd.ogc.se_inimage</OPTION>
				 </SELECT>
				 </td>
              </tr>

              <tr>
                 <td colspan="2">&nbsp;</td>
              </tr>

              <tr>
                 <td><div class="begin"><input onclick="GoBack();" name="button" value="Back" onmouseover="this.className='button1'" onmouseout="this.className='button'" class="button">
                 </td>
                 <td align="center">
                 </td>
              </tr>

<?php
}
$database->databaseClose();

?>
          </table> <!-- THE FIRST TABLE ENDS HERE -->
          </DIV>
          <DIV id=mcontent1>&nbsp;</DIV>
        </td>
        </tr>


         <tr>
            <td class=contenttopl width=25%><font class="error">Layout Window Configuration:</font>:</td>
            <td class=contenttopr width=75% align=right><a id=xcontent2 onclick="ShowHide('content2');"><img src="../img/minimize.png" alt="minimize"></a></td>
         </tr>
         <tr>
            <td class="contenttext" colspan=2>
			<DIV id=content2>
            <table border="0"  width="100%"   align="center">
            	<tr>
                 	<td>
                 	<p id="intro">Layout Parameters</p>
				 	</td>
                 	<td>

<input id="open_window1_click_button"  onmouseover="this.className='button1'" onmouseout="this.className='button'" class="button" onclick="Application.evalCode('open_window1', true)" value="Open Layout Window">

        <div class="listing" style="display:none" id="open_window1_codediv">
          <xmp id="open_window1" class="listing" >
if (contentWin != null) {
  	Dialog.alert("Close the Layout window before opening it again!",{width:200, height:130});
}
else {
  	contentWin = new Window({className: "alphacube",  width:600, height:450, zIndex: 100, resizable: true, title: "Layout Window", showEffect:Effect.BlindDown, hideEffect: Effect.SwitchOff, draggable:true, wiredDrag: true, destroyOnClose: true})
	contentWin.getContent().innerHTML= "<div class='container'>\
                        <div  class='drag'>\
                                <div class='title'>\
                                        Map Title\
                                </div>\
                                <div class='content'>\
                                        Map Window <br>\
                                </div>\
                        </div></div>";
	contentWin.setStatusBar("You can resize or drag the layout window");
	contentWin.showCenter();

  	// Set up a windows observer, check ou debug window to get messages
  	myObserver = {
    	onDestroy: function(eventName, win) {
      	if (win == contentWin) {
        	contentWin = null;
        	Windows.removeObserver(this);
      	}
      	debug(eventName + " on " + win.getId())
    	}
  	}
  	Windows.addObserver(myObserver);
}
          </xmp>
        </div>

                 	</td>
            	</tr>
            	<tr>
                 	<td colspan="2">


    <input value="size" name="custom" id="size" checked="checked" type="radio">
    <label for="size">resize</label>
    <input value="layout" name="custom" id="layout" type="radio">
    <label for="layout">drag</label>


                            <div style="cursor: default; width: 468px; height: 266px; left: 114px; top: 273px;" id="dragDiv">

							</div>
<script type="text/javascript" src="../cssjs/layout.js"></script>

                 	    <div class="container">
                        <table class="tablecontainer"><tr><td align="right">-</td></tr></table>
                        <div  class="drag">

                                <div class="title">

                                        Map Title

                                </div>

                                <div class="content">
                                        Map Window <br>

                                </div>
                        </div>

                		</div>
				 	</td>
            	</tr>
              	<tr>
                 	<td colspan="2" align="right">
                 	<input onclick="submitOpenLayers();" name="button" value="GetThematicMap" onmouseover="this.className='button1'" onmouseout="this.className='button'" class="button">
                 	</td>
              	</tr>
            </table>
            </DIV>
			<DIV id=mcontent2>&nbsp;</DIV>
	        </td>
	     </tr>


		 <tr>
		   <td colspan=2>&nbsp;</td>
         </tr>

</table>
</form>

	</td>
</tr>
</table>

</body>
</html>