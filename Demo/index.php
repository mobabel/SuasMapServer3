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
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>SUAS MapServer :: Open Source WMS Server :: Delivering GIS data for the WEB</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="keywords" content="WMS,SVG, OGC, MySQL, Internet Mapping, Internet GIS" />
<meta name="description" content="Open Source WMS for Geodata in SVG format" />
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
		<ul>
		<li class="first"><span>Overview</span></li>
                <ul class="second">
<?
if($publishInstallAndSetting){
		print('<li class="first"><a href="../'.$installName.'/install.php">Installation</a></li>');
		print('<li class="first"><a href="../'.$settingName.'/setting.php">Configuration</a></li>');
		}
else{
		print('<li class="first"><a href="#">Installation</a>(Not available here)</li>');
		print('<li class="first"><a href="#">Configuration</a>(Not available here)</li>');
		}
?>
                </ul>
		<li class="first"><span>Demo</span></li>
			<ul class="second">
				<li class="done"><a href="wms_GetCapabilities.php">WMS</a></li>
				<li class="done"><a href="wfs_GetCapabilities.php">WFS</a></li>
				<li class="done"><a href="#">WCS</a></li>
			</ul>
		</ul>
	</td>
	<td id="content">
		<h1>Overview</h1>
		<p id="intro">
		This <em>open source</em> map server publishes geodata according to OGC's <em>WMS 1.1.1</em> specification. <br />
	  Currently, the server supports the WMS requests
		GetMap, GetCapabilities and GetFeatureInfo. <br /><br />
		The solution is based on open source tools, i.e.
		Apache web server, PHP with libraries, MySQL database together with phpMyAdmin and Ajax, XML.
		</br><br />
		The server dynamically generates vector map files in <em><a href="http://www.gis-news.de/svg/svg.htm">SVG</a></em>, <em>SVGT</em>, <em>SWF</em> and <em>PDF</em> formats
		and raster map files in <em>PNG</em>, <em>JPEG</em>, <em>GIF</em> and <em>WBMP</em> formats  from spatial data in a database
		depending on the request parameters given by the client.
		<br /><br />
		The application is developed in <em>PHP</em> scripting language and are designed for use with a <em>MySQL</em> database.
	  <br /><br />
		The files can be viewed through any browser (recommended the Internet
		Explorer web browser) with the <em>SVG Viewer</em> which can be downloaded free from Adobe's web site
							 <a href="http://www.adobe.com/svg/viewer/install/main.html" target="_blank">http://www.adobe.com/svg/viewer/install/main.html</a>.
		<br /><br />
		<?=$softName?> is distributed under the Lesser General Public License. This can be read at <a href="http://www.gnu.org/copyleft/lesser.html">http://www.gnu.org/copyleft/lesser.html</a>. Before continuing, you must first agree to this license. </p>
		<br />
		<!--div id="options"><div class="begin"><a href="2.php">I Agree</a></div-->
	  </div>
	</td>
</tr>
</table>

</body>
</html>