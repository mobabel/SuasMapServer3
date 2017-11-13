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
<?CreateDemoMenu("GetThematicMap");?>
	</td>
	<td id="content">
		<p id="intro">
<?php
if ($database->databaseConnection and $error == "") {
    $rs4 = $database->getRows4MetaGroupBy($tbmetaname, 'srs');
    echo "<script type=\"text/javascript\">";
    echo("\n");
    echo "function chkform()";
    echo("\n");
    echo "{";
    echo("\n");
    // echo("alert(document.Fgetmap.SRS.length);");
    echo "if(";
    $iSRSs = 0;
    $num = $database->getColumnsNumber($rs4);
    while ($line2 = $database->getColumns($rs4)) {
        if ($num == 1) {
            echo "!document.Fgetmap.SRS.checked ";
        }
        if ($num > 1) {
            echo "!document.Fgetmap.SRS[" . $iSRSs . "].checked ";
        }

        if ($iSRSs != $num-1) {
            echo "&&";
        }
        ++$iSRSs;
    }
    echo ")";
    echo("\n");
    echo "{";
    echo "showErrorMessage(\"Please select a coordinate reference system!\");";
    echo("\n");
    echo "return false;";
    echo "}";
    echo("\n");
    echo("\n");
    echo("\n");

    echo("\n");
    echo "}";
    echo "</script>";
    echo("\n");

    ?>
 <table class="tableContent">
        <tr>
          <td width="100%"><h2>GetThematicMap Request</h2>&nbsp;</br>Select from the currently loaded SRSs</td>
		</tr>

        <tr>
		<td>
           <table class="tableBlock">
             <form name="Fgetmap" action="wms_GetThematicMap_r.php" method="get" onSubmit="return chkform()" >

<?php
    $rs4 = $database->getRows4MetaGroupBy($tbmetaname, 'srs');
    while ($line4 = $database->getColumns($rs4)) {
        $num = $database->getColumnsNumber($rs4);
        $data4 = $line4["srs"];

        ?>	    <tr>
			         <td><input type="radio" name="SRS"  value="<?=$data4?>"><?=$data4?></td>
				    </tr>
<?php
    }
    if (!$data4) {

        ?>
                    <tr>
					 <td><p>These no SRS data, please check your database.</p></td>
					</tr>
<?php
    }
    if ($data4) {

        ?>
					<tr>
				 	 <td align="right"><input type="submit" name="SQuery" value="Submit Query" onmouseover="this.className='button1'" onmouseout="this.className='button'" class="button"></td>
					</tr>
<?php
    }

    ?>
					</form>
			</table>
	       </td>
	    </tr>

		<tr>
		   <td >&nbsp;</td>
         </tr>
</table>
<?php
}

?>
	</td>
</tr>
</table>

</body>
</html>