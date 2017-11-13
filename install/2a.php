<?php
/**
 * 2a.php
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
 * @version $Id: 2a.php,v 1.2 2007/05/10 16:40:39 leelight Exp $
 * @copyright (C) 2006-2007  leelight
 * @Description: Display the database list or create database.
 * @contact webmaster@easywms.com
 */

require_once '../global.php';
require_once '../Models/Setting.class.php';

$database = new Database();

// Check all fields filled in
if (empty($_POST['server']) || empty($_POST['username'])){
	$error = 'You must fill out all the fields';
}
else{
	if (!isset($error)){
		//@$connect = mysql_connect($_POST['server'], $_POST['username'], $_POST['password']);
		$database->databaseConfig($_POST['server'],$_POST['username'],$_POST['password'],"");
        $database->databaseConnectNoDatabase();
        $error = $database->databaseGetErrorMessage();
	}
}
$dbserver=$_POST['server'];
$dbusername=$_POST['username'];
$dbpassword=$_POST['password'];
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
		echo '<div id="progressbar"><div id="process" style="width: 20%;"></div></div>';
	else
		echo '<div id="progressbar"><div id="process" style="width: 30%;"></div></div>';
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
<?
	if (empty($error)){
?>
				<p id="intro">Now you are ready to install the <?=$softName?> database.</p>
                		<div id="errormessage" class="error"></div>
				<br />

					<h2>Database Settings</h2>
					<p>Please select one database available or create one new database. </p>
					<form name="databasename" id="databasename" method="post" action="2b.php" onSubmit="return chkform()">
						<table class="tableContent">
							<tr>
								<td colspan="2"><h2>Select one Database available</h2></td>
							</tr>
<?
$db_list=$database->getDatabaseName();
if($db_list)
{
	showdb($db_list);

?>
							<tr>
								<td align="left"><input onclick="GoBack();" name="button" value="Back" onmouseover="this.className='button1 backInput'" onmouseout="this.className='button backInput'" class="button backInput">

                                                       </td>
								<td ALIGN="right"><input type="submit" name="Selectdb" value="Continue" onmouseover="this.className='button1 continueInput'" onmouseout="this.className='button continueInput'" class="button continueInput"/></td>
							</tr>
                           <div id=bdbs style=visibility:hidden>
                           <input name="bdbs" type="text" id="bdbs" value="true" />
                           <input name="dbserver" type="text" id="dbserver" value="<?echo $dbserver;?>" />
                           <input name="dbusername" type="text" id="dbusername" value="<?echo $dbusername;?>" />
                           <input name="dbpassword" type="text" id="dbpassword" value="<?echo $dbpassword;?>" />
                           </div>
<?
}
?>
						</table>
						</form>

						<form name="databasenamecreate" id="databasenamecreate" method="post" action="2b.php" onSubmit="return chkDabaseCreateInput()">
						<table class="tableContent">
							<tr>
								<td  colspan="2"><h2>Or you can create one new Database</h2></td>
							</tr>
							<tr>
								<td><p id="intro">Database Name:</p></td>
								<td>

<?
      $blnDatabase = $database->databaseCheckCreateAndDropDatabasePrivelege();
      if($blnDatabase)
          print('<input name="databasei" type="text" id="databasei" size="15"  class="smallInput" onmouseover="txtfieldSelectAll(this);" />');
      else
          print('<input name="databasei" type="text" id="databasei" value="locked" size="15"  class="smallInput" disabled/></td><tr>
		  <tr><td colspan="2"><font class="error">You have no privelege to create new database. Please ask your host supporter.</font>
		  ');
?>
                            </td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td ALIGN="right">
<?
      if($blnDatabase)
          echo "<input type=\"submit\" name=\"Createdb\" value=\"Create\" onmouseover=\"this.className='button1 newDatabaseInput'\" onmouseout=\"this.className='button newDatabaseInput'\" class=\"button newDatabaseInput\"/>";
      else
          echo "<input type=\"submit\" name=\"Createdb\" value=\"Create\" onmouseover=\"this.className='button1 newDatabaseInput'\" onmouseout=\"this.className='button newDatabaseInput'\" class=\"button newDatabaseInput\" disabled/>";
?>
                                                         </td>
							</tr>
						    <div id=bdbc style=visibility:hidden>
                           <input name="bdbc" type="text" id="bdbc" value="true" />
                           <input name="dbserver" type="text" id="dbserver" value="<?echo $dbserver;?>" />
                           <input name="dbusername" type="text" id="dbusername" value="<?echo $dbusername;?>" />
                           <input name="dbpassword" type="text" id="dbpassword" value="<?echo $dbpassword;?>" />
                           </div>
						</table>
						<p>&nbsp;</p>
					</form>

<?
}
else{
	$error =  $database->databaseGetErrorMessage();
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
<?
	}
		?>
	</td>
</tr>
</table>

</body>
</html>
<?php

function showdb($result){
	$database = new Database();
	echo "
         <TR class=\"title\">\n
         <TD><p id=\"intro\">Database ID</p></TD>\n
         <TD><p id=\"intro\">Database Name</p></TD>\n
         </TR>\n";
    $i=0;
    while($row=$database->getColumns($result)){
     //Jump the buildin table in MySQL
     //while ($row = mysql_fetch_object($result)) {
     //echo $row->Database . "\n";}
    if ($row["Database"]<> "information_schema" && $row["Database"]<> "mysql") {
         $i=$i+1;
        if($i%2==0)
     	echo "<TR class=\"odd\">";
     	else echo "<TR class=\"even\">";

        echo "\n<TD>";
        echo $i;
        echo "</TD>\n";
        echo "<TD  ALIGN=left>\n";
        echo "<input type=\"radio\" name= \"databases\" class=\"button3\"  value=\"";
        echo $row["Database"]."\">\n";
        echo $row["Database"];
        echo "</TD></TR>\n";
     }
     }
     //Must select one database
     echo "<script type=\"text/javascript\">\n";
     echo "function chkform()";
     echo "{\n";
     echo "if(";
              //$iradio = 0;
     for ($iradio=0;$iradio<$i;$iradio++){
         echo "!document.databasename.databases[" .$iradio. "].checked ";
         if ($iradio != $i - 1 ){
             echo "&&";
         }
              //++$iradio;
     }
     echo ")";
     echo "{\n";
     //echo "alert(\"Please select a database!\");\n";
	 echo "showErrorMessage(\"Please select a database!\");\n";
     echo "return false;\n";
     echo "}\n";
     echo "}\n";
     echo "</script>\n";
}

?>
