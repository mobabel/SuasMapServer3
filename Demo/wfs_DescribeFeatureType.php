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
<?CreateDemoMenu("DescribeFeatureType");?>
	</td>
	<td id="content">
		<p id="intro">
 <table class="tableContent">

        <tr>
          <td width="100%"><h2>DescribeFeatureType Request</h2>&nbsp;</td>
		</tr>

        <tr>
		<td>
	<table  class="tableBlock">

      <!-- A FORM FOR SELECTION OF COORDINATE SYSTEM -->
<?php

if ($database->databaseConnection and $error == "") {
    // $rs1 = mysql_query("SELECT * FROM  $tbname  ", $con);
    // $line1 = mysql_fetch_array($rs1);
    // $data1 = $line1["style"];
    echo "<script type=\"text/javascript\">";
    echo("\n");
    echo "function chkform()";
    echo("\n");
    echo "{";
    echo("\n");

    $rs2 = $database->getRows4MetaGroupBy($tbmetaname,'layer');
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
    echo "showErrorMessage(\"please select a TYPENAME!\");";
    echo("\n");
    echo "return false;";
    echo("\n");
    echo "}";
    echo("\n");
    echo " var layerstr = \"\";";
    echo("\n");
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
    echo("}");
    echo("\n");
    echo "}";
    echo("\n");
    echo "layerstr = (layerstr.charAt(layerstr.length-1) == \",\") ? layerstr.slice(0,layerstr.length-1) : layerstr;";
    echo("\n");
    echo("\n");
    echo "<!--document.form.TYPENAME.value =document.form.fila[0].value + \",\";-->";
    echo("\n");
    // echo	 "alert(\"you have selected LAYERS=\" + layerstr) ;";
    echo("\n");
    echo "document.form.TYPENAME.value = layerstr;";
    // echo("\n");
    // echo   "alert(layerstr);";
    echo("\n");
    echo("\n");
    echo("\n");
    echo "}";
    echo("\n");
    echo "</script>";
    echo("\n");

    ?>

        <!-- The form for the Get Request starts here -->

      <form name="form" action="../WFS/getmapcap.php" method="get" onSubmit="return chkform()" >
              <tr>
                      <td >SERVICE</td>
                      <td >
                      <input type="text" name="SERVICE" value="<?=$wfsservice?>" readonly="readonly" class="smallInput"></td>
              </tr>
              <tr>
                      <td width="200" id="done">VERSION</td>
                      <td width="100">
                      <input type="text" name="VERSION" value="<?=$wfsversion?>" readonly="readonly"  class="smallInput"></td>
              </tr>
                  <input type="hidden" name="TYPENAME" value="LayerProblem">
              <tr>
                     <td width="200" id="done">REQUEST</td>
                     <td width="100">
                     <input type="text" name="REQUEST" value="DescribeFeatureType" readonly="readonly"  class="smallInput"></td>
              </tr>
              <tr>
                     <td width="200" id="done">OUTPUTFORMAT</td>
                     <td width="100">
                     <input type="text" name="OUTPUTFORMAT" value="text/xml" readonly="readonly"  class="smallInput"></td>
              </tr>
              <tr><td colspan="2" >TYPENAME: <td></tr>
              <tr><td colspan="2"></td>&nbsp;</tr>
             <tr align="left">
             <td colspan="1"></td>
			 <td colspan="1">
            <table class="tableInBlock">
            <td id="done">Select All?</td><td><input type="checkbox" name="chkall" class="button1" onclick="checkall(this.form)"></td>
<?php
    $rs3 = $database->getRows4MetaGroupBy($tbmetaname,'layer');
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
      <td>
	  </tr>
              <tr>
                 <td colspan="2">&nbsp;</td>
              </tr>

              <tr>
                 <td></td>
                 <td align="right">
                 <input type="submit" name="button" value="DescribeFeatureType" onmouseover="this.className='button1'" onmouseout="this.className='button'" class="button">
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