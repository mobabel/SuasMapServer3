<?php
/**
 * 7.php
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
 * @version $Id: 7.php,v 1.2 2007/05/10 16:40:39 leelight Exp $
 * @copyright (C) 2006-2007  leelight
 * @Description: Input data
 * @contact webmaster@easywms.com
 */

require_once '../global.php';
require '../config.php';
require_once '../Models/Installation.class.php';

?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title><?=$softName?> Installation</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link href="../cssjs/setup.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../cssjs/common.js"></script>
<script type="text/javascript" src="../cssjs/string.protoype.js"></script>
<script type="text/javascript" src="../cssjs/menu.js"></script>
</head>
<body>

<table cellspacing="0" cellpadding="0" id="main">
<tr id="logo"><td colspan="2"><span class="logoprefix"><?=$softName."  ".$softVersion.$softEdition?></span></td></tr>
<tr id="top">
	<td id="left">Setting Progress</td>
	<td id="right">
		<?
	if(!empty($error))
		echo '<div id="progressbar"><div id="process" style="width: 70%;"></div></div>';
	else
		echo '<div id="progressbar"><div id="process" style="width: 75%;"></div></div>';
	?>
	</td>
</tr>

<tr>
	<td id="progress">
		<ul>
		<li class="first"><span>Start</span></li>
			<ul class="second">
				<li class="done">Action Selection</li>
				<li class="done">Server Requirements</li>
				<li class="done">License Agreement</li>
			</ul>
		<li class="first"><span>Configuration</span></li>
			<ul class="second">
				<li class="done">Database Install</li>
				<li class="done">General Setting</li>
				<li class="error">Data Import</li>
				<li>Style Defination</li>
			</ul>
		<li class="first"><span>Setting</span></li>
			<ul class="second">
				<li>Database Settings</li>
			</ul>
		</ul>
	</td>
	<td id="content">

			<p id="intro">You have inputed data already, now you can input more data or go to <a href="8.php">Style Defination</a>.<br>
			Please upload the data into <font class="error">data</font> directory through FTP, if you use Remote Files Import.</p>
			<div id="errormessage" class="error"></div>
			<br />

	<div id="header">
	<ul id="primary">
		<li><a href="#" onClick="show_tbl('content1_',2,1)" id="content1_1_menu" class="current">Local Files</a>
		<ul id="secondary">
		<div id="content1_1_second" align="right">
         Local Files Import&nbsp;
		<image src="../img/help.png"  border="0" onmouseover="tooltip('Local Files Import','Description:','In most case you are allowed to upload files from local(your) computer less than 2Mb because of server limitation.');" onmouseout="exit();">
		</div>
		</ul>
		</li>
		<li><a href="#" onClick="show_tbl('content1_',2,2)" id="content1_2_menu">Remote Files</a>
		<ul id="secondary">
		<div id="content1_2_second" align="right" style="display:none">
         Remote Files Import&nbsp;
		<image src="../img/help.png"  border="0" onmouseover="tooltip('Remote Files Import','Description:','If you want to input files with more than 2Mb size, please upload the files into <font class=error>data</font> (In <?=$softName?>) in Remote Server, using FTP tools such as CuteFTP. Do not make folder in data folder, just put files there.');" onmouseout="exit();">
		</div>
		</ul>
		</li>
	</ul>
	</div>
	<div id="main">
		<div id="contents">
          <table id="content1_1" class="tableContent">
          <tr>
            <td>
                <!--local file content-->
                <?include_once '../Models/LocalFileImport.php';?>
                <!--local file content-->
			  </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
        </table>

        <table id="content1_2"  style="display:none" class="tableContent">
          <tr>
            <td>
                <!--Remote file content-->
                <?include_once '../Models/RemoteFileImport.php';?>
                <!--Remote file content-->
			  </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
        </table>
		</div>
	</div>

        <br><br>
<?
$database = new Database();
$database->databaseConfig($servername, $username, $password, $dbname);
$database->databaseConnect();
if ($database->databaseGetErrorMessage() == "") {
    $layersnameslist = $database->getAllLayersNames($tbname);
    $number = $database->getColumnsNumber($layersnameslist);
    if($number>0){

?>
<table class="tableContent">
          <tr>
            <td>
                <h2>Style Setting</h2>
            </td>
		  </tr>
		  <tr>
            <td>
				<p>Records have been found.<br>
				You can create a Style (display range and symbology) for each layer that imported in the previous step.<br />
				</p>
			</td>
		  </tr>
		  <tr align="right">
            <td>
				<div class="begin"><a href="8.php">Style Defination</a></div><br />
			</td>
		  </tr>
</table>
<?
}
}
?>

	</td>
</tr>
</table>

</body>
</html>