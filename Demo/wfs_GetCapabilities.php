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
 * @version $3.0$ 2006
 * @Author leelight
 * @Contact webmaster@easywms.com
 */
require_once '../global.php';
require_once '../config.php';
require_once '../Models/CreateDemoMenu.php';
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
<?CreateDemoMenu("wfs_GetCapabilities");?>
	</td>
	<td id="content">
		<p id="intro">
 <table class="tableContent">

       <tr>
          <td width="100%"><h2>GetCapabilities Request</h2>&nbsp;
		  XML metadata information of the server</td>
	   </tr>

        <tr>
          	  <td>

			<table class="tableBlock">
			<form action="../WFS/getmapcap.php" method="get">
          <tr>
             <td width="100"><b>VERSION</b></td>
             <td width="261">
             <input type="text" name="VERSION" value="<?=$wfsversion?>" readonly="readonly" class="smallInput"></td>
          </tr>

          <tr>
             <td ><b>SERVICE</b></td>
             <td >
             <input type="text" name="SERVICE" value="<?=$wfsservice?>" readonly="readonly" class="smallInput"></td>
          </tr>

          <tr>
             <td ><b>REQUEST</b></td>
             <td >
             <input type="text" name="REQUEST" value="GetCapabilities" readonly="readonly" class="smallInput"></td>
          </tr>

          <tr>&nbsp;</tr>
          <tr>&nbsp;</tr>

          <tr>
             <td colspan="2" align="right">
             <input type="submit" name="Bgetcap" value="GetCapabilities" onmouseover="this.className='button1'" onmouseout="this.className='button'" class="button">
             </td>
          </tr>
			</form>
           </table>

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