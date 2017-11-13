<?php
/**
* install.php
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
* @version $Id: install.php,v 1.2 2007/05/10 16:40:39 leelight Exp $
* @copyright (C) 2006-2007  leelight
* @Description : This page check the requirement for the installation.
* @contact webmaster@easywms.com
*/

require_once '../global.php';
require_once '../Models/Installation.class.php';

$database = new Database();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title><?=$softName?> Installation</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link href="../cssjs/setup.css" rel="stylesheet" type="text/css" />
</head>
<body>

<table cellspacing="0" cellpadding="0" id="main">
<tr id="logo"><td colspan="2"><span class="logoprefix"><?=$softName . "  " . $softVersion . $softEdition?></span></td></tr>
<tr id="top">
	<td id="left">Setting Progress</td>
	<td id="right">
	<div id="progressbar"><div id="process" style="width: 5%;"></div></div>
	</td>
</tr>

<tr>
	<td id="progress">
		<ul>
		<li class="first"><span>Start</span></li>
			<ul class="second">
				<li class="error">Server Requirements</li>
				<li >License Agreement</li>
			</ul>
		<li class="first"><span>Configuration</span></li>
			<ul class="second">
				<li >Database Install</li>
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
		<h2>Server Requirements </h2>
		<p id="intro"><?=$softName?> has a number of minimum requirements for installation. These requirements are checked below, and once satisfied you will be able to continue with the installation process.</p>
		<br />
		    <form name="formRequirements" id="formRequirements" method="post" action="1.php">
              <table class="tableContent">
							<tr>
								<td colspan="2">
								    <div id="options">

			<p>The following are required before you can run <?=$softName?>:</p>
			<?php
				$requirements = checkExtensionInPHP($database);
				$directoryWritbale = checkDirectoryWritabale();
    		?>
                                  </div>
								</td>

							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td align="left">
								</td>
								<td align="right">
<?
if ($requirements && $directoryWritbale) {
?>
				<input type="submit" name="Continue" value="Continue" onmouseover="this.className='button1 continueInput'" onmouseout="this.className='button continueInput'" class="button continueInput"/>
<?php
} else {
?>
				<p>You need to fix the above problem(s) before you can continue with the installation.</p>
			<?php
			}
			?>
								</td>
							</tr>
						</table>
              </form>
	</td>
</tr>
</table>

</body>
</html>