<?php
/**
 * 2.php
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
 * @version $Id: 2.php,v 1.2 2007/05/10 16:40:39 leelight Exp $
 * @copyright (C) 2006-2007  leelight
 * @Description: Access the database .
 * @contact webmaster@easywms.com
 */

require_once '../global.php';
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
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
	<div id="progressbar"><div id="process" style="width: 20%;"></div></div>
	</td>
</tr>
<tr>
	<td id="progress">
		<ul>
		<li class="first"><span>Start</span></li>
			<ul class="second">
				<li class="done">Server Requirements</li>
				<li class="done">License Agreement</li>
			</ul>
		<li class="first"><span>Configuration</span></li>
			<ul class="second">
				<li class="error">Database Install</li>
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
				<p id="intro">Now you are ready to access the <?=$softName?> database.</p>
				<br />

					<h2>Access Database</h2>
					<p>Please fill out the information below. If you are unsure of your access details, you should contact your web hosting company.</p>
					<form name="database" id="database" method="post" action="2a.php" onSubmit="return chkLoginformInput()">
						<table class="tableContent">
							<tr>
								<td>Database Server:</td>
								<td><input name="server" type="text" id="server" value="localhost" size="15"  class="smallInput" onmouseover="txtfieldSelectAll(this);" /></td>
							</tr>
							<tr>
								<td width="16%">Database User Name:</td>
								<td width="84%"><input name="username" type="text" id="username" value="root" size="15"  class="smallInput" onmouseover="txtfieldSelectAll(this);" /></td>
							</tr>
							<tr>
								<td>Database Password: </td>
								<td><input name="password" type="password" id="password" size="15"  class="smallInput" onmouseover="txtfieldSelectAll(this);" /></td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td align="left"><input onclick="GoBack();" name="button" value="Back" onmouseover="this.className='button1 backInput'" onmouseout="this.className='button backInput'" class="button backInput">
</td>
								<td align="right"><input onclick="chkLoginformInput();" type="submit" name="Submit" value="Continue" onmouseover="this.className='button1 continueInput'" onmouseout="this.className='button continueInput'" class="button continueInput"/>
</td>
							</tr>
						</table>
						<p>&nbsp;</p>
										</form>
	</td>
</tr>
</table>

</body>
</html>