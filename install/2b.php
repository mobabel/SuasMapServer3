<?php
/**
 * 2b.php
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
 * @version $Id: 2b.php,v 1.2 2007/05/10 16:40:39 leelight Exp $
 * @copyright (C) 2006-2007  leelight
 * @Description: create the database .
 * @contact webmaster@easywms.com
 */

require_once '../global.php';
require_once '../Models/Setting.class.php';
require_once '../Models/Installation.class.php';

$database = new Database();

$dbselect = $_POST['bdbs'];
$dbcreate = $_POST['bdbc'];
$dbserver=$_POST['dbserver'];
$dbusername=$_POST['dbusername'];
$dbpassword=$_POST['dbpassword'];

if (!isset($error)){
    if ($dbselect=="true" ) {
         $dbname = $_POST['databases'];
         $database->databaseConfig($dbserver,$dbusername,$dbpassword,"");
         $database->databaseConnectNoDatabase();
    }
    else if ($dbcreate=="true") {
        $dbname = $_POST['databasei'];
        //Database Name could not be only number
	    if ( eregi("^[0-9]+$", $dbname) ) {
            $error = 'Database Name could not be only number.'.'<br>';
		    $error =$error.'Please use Database Name like '.'wms_'."$dbname".', or db_'."$dbname".'.';
        }
        else{
             $success=true;
	         $database->databaseConfig($dbserver,$dbusername,$dbpassword,"");
             	$database->databaseConnectNoDatabase();
		}

    }
    if (isset($success)) {
        	$database->databaseCreateDatabase($dbname);
		    $error =  $database->databaseGetErrorMessage();

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
		echo '<div id="progressbar"><div id="process" style="width: 30%;"></div></div>';
	else
		echo '<div id="progressbar"><div id="process" style="width: 35%;"></div></div>';
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
<?       //061130 leex add !isset($error) to display all the error before
		if ($database->databaseConnection and $error==""){
?>
				<p id="intro">Now you are ready to install the <?=$softName?> database table.</p>
				<div id="errormessage" class="error"></div>
				<br />

					<h2>Table Settings</h2>
					<p>Please select one table available or create one new table with prefix.</p>

<?
        $result1 = $database->getTableName($dbname);
        if($row1 = $database->getRows($result1)){
?>
					<form name="tablename" id="tablename" method="post" action="4.php" onSubmit="return chkform()">

						<table class="tableContent">
							<tr>
								<td colspan="2"><h2>Select one Table available</h2></td>
							</tr>
<?
        $result = $database->getTableName($dbname);
        showtab($database,$result,$dbname);
?>
							<tr>
								<td align="left"><input onclick="GoBack();" name="button" value="Back" onmouseover="this.className='button1 backInput'" onmouseout="this.className='button backInput'" class="button backInput">

                                                       </td>
								<td ALIGN="right">
								<input type="submit" name="Select" value="Continue" onmouseover="this.className='button1 continueInput'" onmouseout="this.className='button continueInput'" class="button continueInput"/>
								</td>
							</tr>
                           <div id=bdbs style=visibility:hidden>
                           <input name="btbs" type="text" id="btbs" value="true" />
                           <input name="dbserver" type="text" id="dbserver" value="<?echo $dbserver;?>" />
                           <input name="dbusername" type="text" id="dbusername" value="<?echo $dbusername;?>" />
                           <input name="dbpassword" type="text" id="dbpassword" value="<?echo $dbpassword;?>" />
                           <input name="dbname" type="text" id="dbname" value="<?echo $dbname;?>" />
                           </div>
						</table>
						</form>
<?
        }
        else{
            $error =  $database->databaseGetErrorMessage();
        }
?>
						<form name="tablenamecreate" id="tablenamecreate" method="post" action="3.php" onSubmit="return chkTableCreateInput()">
						<table class="tableContent">
							<tr>
								<td colspan="2"><h2>Or you can create one new Table with Prefix</h2></td>
							</tr>
							<tr>
								<td ALIGN=CENTER><p id="intro">Table Prefix:</p></td>
                                <td><input name="prefix" type="text" id="prefix" size="15" value=""  class="smallInput" onmouseover="txtfieldSelectAll(this);" /></td>

							</tr>
							<tr>
								    <td align="left">
		 <input onclick="GoBack();" name="button" value="Back" onmouseover="this.className='button1 backInput'" onmouseout="this.className='button backInput'" class="button backInput">
         </td>
								<td ALIGN="right"><input type="submit" name="Create" value="Create" onmouseover="this.className='button1 newTableInput'" onmouseout="this.className='button newTableInput'" class="button newTableInput"/></td>
							</tr>
						    <div id=bdbc style=visibility:hidden>
                           <input name="btbc" type="text" id="btbc" value="true" />
                           <input name="dbserver" type="text" id="dbserver" value="<?echo $dbserver;?>" />
                           <input name="dbusername" type="text" id="dbusername" value="<?echo $dbusername;?>" />
                           <input name="dbpassword" type="text" id="dbpassword" value="<?echo $dbpassword;?>" />
                           <input name="dbname" type="text" id="dbname" value="<?echo $dbname;?>" />
						   </div>
						</table>
						<p>&nbsp;</p>
					</form>
<?
		}
		else{
			$error =  $database->databaseGetErrorMessage();
			if ($error!="")//else
		{
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
		}
		?>
	</td>
</tr>
</table>

</body>
</html>
<?php

function showtab($database, $tablesobjects, $dbname){
    echo "
    <TR class=\"title\">
    <TD>Table ID</TD>
    <TD>Table Name(Prefix)</TD>
    </TR>\n";
    $i=0;
    //filter the name with featureclass
    while($row=$database->getColumns($tablesobjects)){
    	if(substr_count($row["Tables_in_".$dbname], mapTableFeaturegeometry)>0){
	    //check if has the standard fields inside
    	    //$table = $database->getColumnsFromTable($row["Tables_in_" . $dbname]);
            if ($database->databaseGetErrorMessage() == "") {
                $blnSameTable = $database->TableHasSameFields($row["Tables_in_" . $dbname]);
                $detailInfo = $database->getTableDetailInformation($row["Tables_in_" . $dbname]);
            }

	        $i=$i+1;
	        if($i%2==0)
	    	echo "<TR class=\"odd\">";
	     	else echo "<TR class=\"even\">";
	        echo "<TD>$i</TD>\n";
	        echo "<TD>\n";
	        echo "<input type=\"radio\" name= \"tables\"  class=\"button3\" value=\"";
	        echo getPrefixOfTablename($row["Tables_in_".$dbname])."\"";
			if (!$blnSameTable) {
                echo " disabled ";
            }
			echo ">";
	        //echo getPrefixOfTablename($row["Tables_in_".$dbname]);
		echo "<dfn title=\"$detailInfo[0] rows, $detailInfo[1] Kb, created on $detailInfo[2], updated on $detailInfo[3]\">".getPrefixOfTablename($row["Tables_in_" . $dbname])."</dfn>";
	        echo "</TD>\n</TR>\n";
        }
    }
    //Must select one Table
    echo "<script type=\"text/javascript\">\n";
    echo "function chkform()";
    echo "{\n";
    echo "if(";
    //$iradio = 0;
    for ($iradio=0;$iradio<$i;$iradio++){
        if ($i==1) {
        	echo "!document.tablename.tables.checked ";
        }
        else
        echo "!document.tablename.tables[" .$iradio. "].checked ";
        if ($iradio != $i - 1 ){
            echo "&&";
        }
    //++$iradio;
    }
    echo ")";
    echo "{\n";
    echo "showErrorMessage(\"Please select a Table(Prefix) !\");\n";
    echo "return false;\n";
    echo "}\n";
    echo "}\n";
    echo "</script>\n";


    //chkTableCreateInput()
    echo "<script type=\"text/javascript\">\n";
    echo "function chkTableCreateInput()";
    echo "{\n";
    print('
    if(document.tablenamecreate.prefix.value.trim() == ""){
        showErrorMessage("Please input the table name prefix! Can not be empty.");
        document.tablenamecreate.prefix.value = "";
        return false;
    }
	');
    echo "if(";
    //$iradio = 0;
    for ($iradio=0;$iradio<$i;$iradio++){
        echo "document.tablename.tables[" .$iradio. "].value == document.tablenamecreate.prefix.value.toLowerCase().trim()";
        if ($iradio != $i - 1 ){
            echo "||";
        }
    //++$iradio;
    }
    echo ")";
    echo "{\n";
    echo "showErrorMessage(\"Table Prefix \"+document.tablenamecreate.prefix.value.toLowerCase().trim()+\" alreay exist,please input a new prefix!\");\n";
    echo "return false;\n";
    echo "}\n";
    echo "}\n";
    echo "</script>\n";

}


?>
