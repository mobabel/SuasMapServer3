<?php
/**
 * 1.php
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
 * @version $Id: 1.php,v 1.2 2007/05/10 16:40:39 leelight Exp $
 * @copyright (C) 2006-2007  leelight
 * @Description: This show the copyright .
 * @contact webmaster@easywms.com
 */
require_once '../global.php';
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<head>
<title><?=$softName?> Installation</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link href="../cssjs/setup.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../cssjs/common.js"></script>
<script type="text/javascript" src="../cssjs/string.protoype.js"></script>
</head>
<body>

<table cellspacing="0" cellpadding="0" id="main">
<tr id="logo"><td colspan="2"><span class="logoprefix"><?=$softName."  ".$softVersion.$softEdition?></span></td></tr>
<tr id="top">
	<td id="left">Setting Progress</td>
	<td id="right">
	<div id="progressbar"><div id="process" style="width: 10%;"></div></div>
	</td>
</tr>

<tr>
	<td id="progress">
		<ul>
		<li class="first"><span>Start</span></li>
			<ul class="second">
				<!--li class="done">Action Selection</li-->
				<li class="done">Server Requirements</li>
				<li class="error">License Agreement</li>
			</ul>
		<li class="first"><span>Configuration</span></li>
			<ul class="second">
				<li>Database Install</li>
				<li>General Setting</li>
				<li>Data Import</li>
				<li>Style Defination</li>
				<li>Create Metadata</li>
			</ul>
		<li class="first"><span>Setting</span></li>
			<ul class="second">
				<li>Database Settings</li>
			</ul>
		</ul>
	</td>
	<td id="content">
		<h2>License Agreement </h2>
		<form name="formLicense" id="formLicense" method="post" action="2.php">
		<table class="tableContent">
			<tr>
								<td colspan="2">
		<p id="intro"><?=$softName?> is distributed under the Lesser General Public License.
		<image src="../img/help.png"  border="0" onmouseover="tooltip('GNU','Description:','Copyright (C) 2006-2007  leelight<br>'+
		'This library is free software; you can redistribute it and/or'+
'modify it under the terms of the GNU Lesser General Public'+
'License as published by the Free Software Foundation; either'+
'version 2.1 of the License, or (at your option) any later version.<br><br>'+

'This library is distributed in the hope that it will be useful,'+
'but WITHOUT ANY WARRANTY; without even the implied warranty of'+
'MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU'+
'Lesser General Public License for more details.<br><br>'+

'You should have received a copy of the GNU Lesser General Public'+
'License along with this library; if not, write to the Free Software'+
'Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA');" onmouseout="exit();">
		 <br>This can be read at <a href="http://www.gnu.org/copyleft/lesser.html">http://www.gnu.org/copyleft/lesser.html</a>. Before continuing, you must first agree to this license. </p>
		<br />
             </td>
             <tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td align="left">
								<input onclick="GoBack();" name="Back" value="Back" onmouseover="this.className='button1 backInput'" onmouseout="this.className='button backInput'" class="button backInput"/>
								</td>
								<td align="right">
								<input onclick="quitInstallation();" name="notAgree" value="I do not agree" onmouseover="this.className='button1 notagreeInput'" onmouseout="this.className='button notagreeInput'" class="button notagreeInput"/>
		 						<input type="submit" name="Agree" value="I Agree" onmouseover="this.className='button1 agreeInput'" onmouseout="this.className='button agreeInput'" class="button agreeInput"/>
	    <td>
	    </tr>
	  </form>
	</td>
</tr>
</table>

</body>
</html>