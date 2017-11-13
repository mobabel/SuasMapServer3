<?php
/**
 * 4.php
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
 * @version $Id: 4.php,v 1.2 2007/05/10 16:40:39 leelight Exp $
 * @copyright (C) 2006-2007  leelight
 * @Description: Create config file and input the metadata
 * @contact webmaster@easywms.com
 */

require_once '../global.php';
require_once '../Models/Setting.class.php';
require_once '../Models/Installation.class.php';

$database = new Database();

$tbfselect = $_POST['btbs'];
$tbfcreate = $_POST['btbc'];
$dbserver = $_POST['dbserver'];
$dbusername = $_POST['dbusername'];
$dbpassword = $_POST['dbpassword'];
$dbname = $_POST['dbname'];

$tbname = $_POST['tbname'];
$tbmetaname = $_POST['tbmetaname'];

$setmetadata = false;

if ($tbfcreate == "true") {
	$prefix = $_POST['prefix'];
	if (empty($_POST['dbserver']) || empty($_POST['dbusername']) || empty($_POST['dbpassword'])) {
	    $error = 'You must fill the server name, username and password.';
	} else if (!isset($error)) {
            require('../config.php');
            $database->databaseConfig($dbserver, $dbusername, $dbpassword, $dbname);
            $database->databaseConnect();
            $error = $database->databaseGetErrorMessage();
        }
}
if ($tbfselect == "true") {

	$prefix = $_POST['tables'];
	$tbname = $prefix . mapTableFeaturegeometry;
    $tbmetaname = $prefix . mapTableFeatureclass;

    if (!isset($error)) {
        $database->databaseConfig($dbserver, $dbusername, $dbpassword, $dbname);
        $database->databaseConnect();
        $error = $database->databaseGetErrorMessage();
        $setmetadata = true;
    }
}

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
		<?
	if(!empty($error))
		echo '<div id="progressbar"><div id="process" style="width: 45%;"></div></div>';
	else
		echo '<div id="progressbar"><div id="process" style="width: 50%;"></div></div>';
	?>
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
				<li class="done">Database Install</li>
				<li class="error">General Setting</li>
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
<?php
if ($setmetadata) {
    // Success - tables created
    $path = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
    $curpath = basename(dirname($_SERVER['PHP_SELF']));
    $path = str_replace($curpath,'',$path);

?>
			<p id="intro">One database table with Standard Field has been selected.</p>
			<div id="errormessage" class="error"></div>
			<br />

				<h2>Metadata Settings</h2>
				<p>&#8226; Please fill out the information below, which will occur in the XML document of GetCapabilities Request.<br>
				   &#8226; You can edit these metadata in <font color="red">config.php</font> file later.<br>
				   &#8226; If you are unsure of your Server Host, you should contact your web hosting company.</p>
				<form name="settings" id="settings" method="post" action="5.php" onSubmit="return chkMetadataformInput()">
				    <table class="tableContent">
                    	<tr>
								<td></td>
								<td>
								<p>Please end Server Host with "<font class="error">/</font>"</p>
					            </td>
						</tr>
						<tr>
                    		<td>Server Host : <font class="error">*</font> <image src="../img/help.png"  border="0" onmouseover="tooltip('Server Host','Description:','It is the absolute URL path where you put <?=$softName?> in. Please end the path with slash. If you are not clear, please use the default value.');" onmouseout="exit();"></td>
                    		<td><input name="ServerHost" type="text" id="ServerHost" size="35" value="<?php echo $path;?>" class="smallInput" onclick="txtfieldExtendSize(this);" onblur="txtfieldShortenSize(this);" /></td>
                   		</tr>
                   		<tr>
                    		<td>Enable Stretch Map :<image src="../img/help.png"  border="0" onmouseover="tooltip('Enable Stretch Map','Description:','In the case that a GetMap request is made where the aspect ratio of the selected BBOX and the ratio of the WIDTH/HEIGHT parameters is different, the returned map will be stretched if Stretch Map is enabled. Otherwise the returned map will be in the center of the image with nochanging WIDTH/HEIGHT.');" onmouseout="exit();"></td>
                    		<td><input type="radio" name= "enablestretchmap"  class="button3" value="1" CHECKED>yes
                    		    <input type="radio" name= "enablestretchmap"  class="button3" value="0">no
							</td>
                   		</tr>
                   		<tr>
                    		<td>Enable Cache :<image src="../img/help.png"  border="0" onmouseover="tooltip('Enable Cache','Description:','Enable Cache will speed up the server request, it will store the temperary files of outputted map, XML or SQL query in folder cache. Please make sure that the cache folder is writable.');" onmouseout="exit();"></td>
                    		<td><input type="radio" name= "enablecache"  class="button3" value="1"
							<?
								if(!isCacheDirectoryWritabale())
									echo "disabled";
							?>
							>yes
                    		    <input type="radio" name= "enablecache"  class="button3" value="0" CHECKED>no
							</td>
                   		</tr>
                   		<tr >
                    		<td></td>
                    		<td><input onclick="resetFormOfServerInfo();" name="button" value="Reset" onmouseover="this.className='button1 resetInput'" onmouseout="this.className='button resetInput'" class="button resetInput"></td>
                   		</tr>
                   		<tr class="even">
                    		<td>Server Title: <font class="error">*</font></td>
                    		<td><input name="ServerTitle" type="text" id="ServerTitle" size="35" value="Open Source <?=$softName?>" class="smallInput" onmouseover="txtfieldSelectAll(this);" onclick="txtfieldExtendSize(this);" onblur="txtfieldShortenSize(this);" /></td>
                   		</tr>
                   		<tr class="odd">
                    		<td>Server Abstract: <font class="error">*</font></td>
                    		<td><input name="ServerAbstract" type="text" id="ServerAbstract" size="35" value="Open source based WMS compliant Web Map Sever" class="smallInput" onmouseover="txtfieldSelectAll(this);" onclick="txtfieldExtendSize(this);" onblur="txtfieldShortenSize(this);" /></td>
                   		</tr>
                   		<tr class="even">
                    		<td>Layer Title: <font class="error">*</font></td>
                    		<td><input name="LayerTitle" type="text" id="LayerTitle" size="35" value="<?=$softName?> layers" class="smallInput" onmouseover="txtfieldSelectAll(this);" onclick="txtfieldExtendSize(this);" onblur="txtfieldShortenSize(this);" /></td>
                   		</tr>
                   		<tr class="odd">
                    		<td>Keyword1:</td>
                    		<td><input name="Keyword1" type="text" id="Keyword1" size="35" value="SVG" class="smallInput" onmouseover="txtfieldSelectAll(this);"  onclick="txtfieldExtendSize(this);" onblur="txtfieldShortenSize(this);" /></td>
                   		</tr>
                   		<tr class="even">
                    		<td>Keyword2:</td>
                    		<td><input name="Keyword2" type="text" id="Keyword2" size="35" value="WEB MAP SERVER" class="smallInput" onmouseover="txtfieldSelectAll(this);"  onclick="txtfieldExtendSize(this);" onblur="txtfieldShortenSize(this);" /></td>
                   		</tr>
                    	<tr class="odd">
                    		<td>ContactPerson: <font class="error">*</font></td>
                    		<td><input name="ContactPerson" type="text" id="ContactPerson" size="35" value="leelight" class="smallInput" onmouseover="txtfieldSelectAll(this);"  onclick="txtfieldExtendSize(this);" onblur="txtfieldShortenSize(this);" /></td>
                   		</tr>
                    	<tr class="even">
                    		<td>ContactOrganization:</td>
                    		<td><input name="ContactOrganization" type="text" id="ContactOrganization" size="35" value="EasyWMS" class="smallInput" onmouseover="txtfieldSelectAll(this);"  onclick="txtfieldExtendSize(this);" onblur="txtfieldShortenSize(this);" /></td>
                   		</tr>

                    	<tr class="odd">
                    		<td>ContactPosition:</td>
                    		<td><input name="ContactPosition" type="text" id="ContactPosition" size="35" value="ContactPosition" class="smallInput" onmouseover="txtfieldSelectAll(this);"  onclick="txtfieldExtendSize(this);" onblur="txtfieldShortenSize(this);" /></td>
                   		</tr>
                    	<tr class="even">
                    		<td>ContactAddress:</td>
                    		<td><input name="ContactAddress" type="text" id="ContactAddress" size="35" value="ContactAddress" class="smallInput" onmouseover="txtfieldSelectAll(this);"  onclick="txtfieldExtendSize(this);" onblur="txtfieldShortenSize(this);" /></td>
                   		</tr>
                    	<tr class="odd">
                    		<td>AddressType:</td>
                    		<td><input name="AddressType" type="text" id="AddressType" size="35" value="postal" class="smallInput" onmouseover="txtfieldSelectAll(this);"  onclick="txtfieldExtendSize(this);" onblur="txtfieldShortenSize(this);" /></td>
                   		</tr>
                    	<tr class="even">
                    		<td>Address:</td>
                    		<td><input name="Address" type="text" id="Address" size="35" value="Address" class="smallInput" onmouseover="txtfieldSelectAll(this);"  onclick="txtfieldExtendSize(this);" onblur="txtfieldShortenSize(this);" /></td>
                   		</tr>
                    	<tr class="odd">
                    		<td>City:</td>
                    		<td><input name="City" type="text" id="City" size="35" value="Stuttgart" class="smallInput" onmouseover="txtfieldSelectAll(this);"  onclick="txtfieldExtendSize(this);" onblur="txtfieldShortenSize(this);" /></td>
                   		</tr>
                    	<tr class="even">
                    		<td>StateOrProvince:</td>
                    		<td><input name="StateOrProvince" type="text" id="StateOrProvince" size="35" value="BW" class="smallInput" onmouseover="txtfieldSelectAll(this);"  onclick="txtfieldExtendSize(this);" onblur="txtfieldShortenSize(this);" /></td>
                   		</tr>
                    	<tr class="odd">
                    		<td>PostCode:</td>
                    		<td><input name="PostCode" type="text" id="PostCode" size="35" value="70437" class="smallInput" onmouseover="txtfieldSelectAll(this);"  onclick="txtfieldExtendSize(this);" onblur="txtfieldShortenSize(this);" /></td>
                   		</tr>
                    	<tr class="even">
                    		<td>Country:</td>
                    		<td><input name="Country" type="text" id="Country" size="35" value="Germany" class="smallInput" onmouseover="txtfieldSelectAll(this);"  onclick="txtfieldExtendSize(this);" onblur="txtfieldShortenSize(this);" /></td>
                   		</tr>
                    	<tr class="odd">
                    		<td>ContactVoiceTelephone:</td>
                    		<td><input name="ContactVoiceTelephone" type="text" id="ContactVoiceTelephone" size="35" value="0049" class="smallInput" onmouseover="txtfieldSelectAll(this);"  onclick="txtfieldExtendSize(this);" onblur="txtfieldShortenSize(this);" /></td>
                   		</tr>
                    	<tr class="even">
                    		<td>ContactFacsimileTelephone:</td>
                    		<td><input name="ContactFacsimileTelephone" type="text" id="ContactFacsimileTelephone" size="35" value="0049" class="smallInput" onmouseover="txtfieldSelectAll(this);"  onclick="txtfieldExtendSize(this);" onblur="txtfieldShortenSize(this);" /></td>
                   		</tr>
                    	<tr class="odd">
                    		<td>ContactElectronicMailAddress:</td>
                    		<td><input name="ContactElectronicMailAddress" type="text" id="ContactElectronicMailAddress" size="35" value="webmaster@easywms.com" class="smallInput" onmouseover="txtfieldSelectAll(this);"  onclick="txtfieldExtendSize(this);" onblur="txtfieldShortenSize(this);" /></td>
                   		</tr>
                        <tr>
                                <td align="left">
                                <input onclick="GoBack();" name="button" value="Back" onmouseover="this.className='button1 backInput'" onmouseout="this.className='button backInput'" class="button backInput">
                                </td>
							    <td ALIGN="right">
				    			<input type="submit" name="Submit" value="Continue" onmouseover="this.className='button1 continueInput'" onmouseout="this.className='button continueInput'" class="button continueInput"/>
                                </td>
                         </tr>
                   		   <div id=bdbs style=visibility:hidden>
                           <input name="btbfs" type="text" id="btbfs" value="true" />
                           <input name="dbserver" type="text" id="dbserver" value="<?=$dbserver?>" />
                           <input name="dbusername" type="text" id="dbusername" value="<?=$dbusername?>" />
                           <input name="dbpassword" type="text" id="dbpassword" value="<?=$dbpassword?>" />
                           <input name="dbname" type="text" id="dbname" value="<?=$dbname?>" />
                           <input name="tbname" type="text" id="tbname" value="<?=$tbname?>" />
                           <input name="tbmetaname" type="text" id="tbmetaname" value="<?=$tbmetaname?>" />
                           <input name="prefix" type="text" id="prefix" value="<?=$prefix?>" />
                           </div>
                    	</table>
				    <input name="setup_type" type="hidden" value="install" />
					<input name="search" type="hidden" value="<?php echo $search;?>" />
				</form>
<?php
}
if ($error!="") { // else
    // Failure
?>
<table class="tableError">
<tr>
<td>
			<h4>Failure</h4>
			    <p id="intro">You must correct the error below before installation can continue:<br/><br/>
                <span style="color:#000000"><?php echo $error; ?></span><br /><br /></p>
</td>
</tr>
<tr>
<td align="left">
               <input onclick="GoBack();" name="button" value="Back" onmouseover="this.className='button1 backInput'" onmouseout="this.className='button backInput'" class="button backInput">

</td>
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