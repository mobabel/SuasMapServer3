<?php
/**
 * SLD back up.php
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
 * @Description : input username and password, begin to setting
 * @contact webmaster@easywms.com
 */

require_once '../global.php';
require_once '../config.php';
require_once '../Models/CreateDemoMenu.php';

$root = "../";
$filename = recordFileName;


?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title><?=$softName?> Settting</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link href="../cssjs/setup.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../cssjs/common.js"></script>
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
				<li><a href="setting.php" title="Go back to access database">Database Access</a></li>
				<li>Database Settings</li>
                <li>Table Settings</li>
				<li>General Settings</li>
				<li>Data Import</li>
				<li><a href="8.php" title="Set the style directly">Style Settings</a></li>
				<li>Create Metadata</li>
			</ul>
		<li class="first"><span>Install</span></li>
			<ul class="second">
				<li class="done"><a href="../<?=$installName?>/install.php" title="Create a new database or table from here">Database Installation</a></li>
			</ul>
		<? CreateToolsMenu("checklog");?>
		</ul>
	</td>
	<td id="content">
				<h1>Check or download the log file</h1>
				<p id="intro"></p>
				<br />
					<h2>Check the log file:</h2>
                    <form name="formLog">
  <table class="tableContent">
  <tr>
     <td align=right><INPUT class=button name=Button onclick="HighlightAll('formLog.textareaLog')" type=button value="Copy to Clip"></td>
  </tr>
    <tr>
      <td height=150 align=middle>
       <TEXTAREA class=editbox1 cols=100 name=textareaLog id=textareaLog rows=150 wrap=VIRTUAL>
	   <?
	   $fp = fopen($root . $filename.$ext, "r");
       while (!feof($fp)) {
           $line = fgets($fp, 1024);
           echo $line;
       }
	   ?>
	   </TEXTAREA>
  </td>
  </tr>
  </table>
  </form>
  <h2>Clear log:</h2>
  <div class="begin"><a href="clearlog.php" title="Clear the log">Clear log file</a></div>

   <br>
   <br>
					<h2>The log file can be downlaoded here, please right click to save as.</h2>
<?php
print ('<a href="' . $root . $filename. '" title="Log file">' . $filename.$ext . '</a>');
?>
                <br/>
				<br/>
	</td>
</tr>
</table>

</body>
</html>
