<?php
/**
 * setting.php
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
 * @version $Id: setting.php,v 1.2 2007/05/10 16:41:46 leelight Exp $
 * @copyright (C) 2006-2007  leelight
 * @Description: input username and password, begin to setting
 * @contact webmaster@easywms.com
 */

require_once '../global.php';
require_once '../Models/CreateDemoMenu.php';

?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title><?=$softName?> Settting</title>
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
	<div id="progressbar"><div id="process" style="width: 0%;"></div></div>
	</td>
</tr>

<tr>
	<td id="progress">
        <ul>
                <li class="first"><span><a href="../Demo/index.php">Home</a></span></li>
		<li class="first"><span>Configuration</span></li>
			<ul class="second">
				<li class="error">Database Access</li>
				<li>Database Settings</li>
                <li>Table Settings</li>
				<li>General Settings</li>
				<li><a href="7.php" title="Import the data directly">Data Import</a></li>
				<li><a href="8.php" title="Set the style directly">Style Settings</a></li>
				<li>Create Metadata</li>
			</ul>
		<li class="first"><span>Install</span></li>
			<ul class="second">
				<li class="unactive"><a href="../<?=$installName?>/install.php" title="Create a new database or table from here">Database Installation</a></li>
			</ul>
		<? CreateToolsMenu("default");?>
		</ul>
	</td>
	<td id="content">
				<p id="intro">Now you are ready to access the <?=$softName?> database.</p>
				<br />
                                <table class="tableContent">
                                <tr>
                                <td>
					<h2>Access Database</h2>
					<p>Please fill out the information below. If you are unsure of the access details, please contact your web hosting company.</p>
				</td>
                                </tr>
                                <tr>
                                <td>
                                       <form name="database" id="database" method="post" action="s1.php" onSubmit="return chkLoginformInput()">
					<table class="tableBlock">
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
								<td></td>
								<td align="right"><input type="submit" name="Submit" value="Continue" onmouseover="this.className='continueInput button1'" onmouseout="this.className='continueInput button'" class="continueInput button"/></td>
							</tr>

                                        </table>

					</form>
                                </td>
                                </tr>
                                 </table>

                           <br>
                           <table class="tableBlock">
<tr>
                                <td>
                                <h2>Data Import</h2>
				<p>Import the new data with your previous database configuration.<br />
				</p>
</td>
                                </tr>
<tr>
                                <td align="right">
				<div class="begin"><a href="7.php" title="Import the data directly">Import Data</a></div><br />
</td>
                                </tr>
                           </table>
                           <table class="tableBlock">
<tr>
                                <td>
                                <h2>Style Setting</h2>
				<p>Set the Style (display range and symbology) if data has been imported.<br />
				</p>
</td>
                                </tr>
<tr>
                                <td align="right">
				<div class="begin"><a href="8.php" title="Set the style directly">Style Defination</a></div><br />
</td>
                                </tr>
                           </table>

	</td>
</tr>
</table>

</body>
</html>