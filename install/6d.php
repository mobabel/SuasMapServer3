<?php
/**
 *
 * @description : this part is for input MIF file
 */
require_once '../Models/Installation.class.php';
include_once '../global.php';
include_once '../Models/CommonFunction.class.php';

setConnectionTime($maxTimeOutLimit);

// $uploaddir = "./upload/";
// $uploadfile = $uploaddir . basename($_FILES['nameShpSelect']['name']);
$blnLocalFileFlag = $_POST['nameLocalFileFlag'];

if ($blnLocalFileFlag == 1) {
    $SRSname = $_POST['nameMifSRS'];

    $tempNameMif = $_FILES['nameMifSelect']['tmp_name'];
    $fileNameMif = $_FILES['nameMifSelect']['name'];

    $file_sizeMif = round(filesize($tempNameMif)/1024);
    //the user upload fize more than 2mb and it was stopped by browser.
    if(($fileNameMif AND !$tempNameMif) OR $file_sizeMif >=$maxUploadFileSize){
	    $error="You are allowed to upload files with size under ".$maxUploadFileSize."Mbytes only. if you have big files, please upload the files to data folder in $softName and then run Remote Files Import.";
	}
}
if ($blnLocalFileFlag == 0) {
    $SRSname = $_POST['nameRemoteMifSRS'];

    $tempNameMif = $_POST['RemoteSelectMIF'];
    $fileNameMif = $_POST['RemoteSelectMIF'];
}
// get the file name without postfix
$layerName = substr(basename($fileNameMif),0, strlen(basename($fileNameMif))-4);
//echo $layerName;
if (!$fileNameMif) {
    $error = "No MIF file has been selected!";
}

?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title><?=$softName?> Installation</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link href="../cssjs/setup.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../cssjs/common.js"></script>
<script type="text/javascript" src="../cssjs/string.protoype.js"></script>
<script type="text/javascript" src="../cssjs/copy.js"></script>
</head>
<body>
<script type="text/javascript" src="../cssjs/loading.js"></script>

<table cellspacing="0" cellpadding="0" id="main">
<tr id="logo"><td colspan="2"><span class="logoprefix"><?=$softName."  ".$softVersion.$softEdition?></span></td></tr>
<tr id="top">
	<td id="left">Setting Progress</td>
	<td id="right">
		<?
	if(!empty($error))
		echo '<div id="progressbar"><div id="process" style="width: 60%;"></div></div>';
	else
		echo '<div id="progressbar"><div id="process" style="width: 70%;"></div></div>';
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
				<li class="done">General Setting</li>
                <li class="error">Data Import</li>
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
if ($error == "") {
    require_once "../Parser/MIF2DB.class.php";
    require_once('../config.php');

    $fileNameMif = $tempNameMif;
    $mif2db = new MIF2DB($servername, $username, $password, $dbname, $tbname, $layerName, $SRSname, $fileNameMif);

    $error = $mif2db->getError();
    $log = $mif2db->getLog();
    $recordgood = $mif2db->recordgood;
    $recordbad = $mif2db->recordbad;
    //echo $recordbad;
}

if ($error == "" AND $recordgood > 0) {
    ?>
			<h1>Data Import Successfully</h1>
			<p id="intro"><FONT color=green><?=$recordgood?></FONT> records has been imported into database successfully. <br>
			You <FONT color=red>MUST</FONT> create style after new data has been imported! </p>
			<br />

			<div id="options">
			    <table class="tableContent">
			    <tr>
			    <td>
			    <h2>Select more files</h2>
				<p>Select more file to import data into database<br />
				</p>
				</td>
				</tr>
				<tr>
			    <td align="right">
				<div class="begin"><a href="7.php">Select file</a></div>
				</td>
				</tr>
				</table>
                <br><br>
                <table class="tableContent">
                <tr>
			    <td>
				<h2>Style Defination</h2>
				<p>Create Styles (display range and symbology) for each Layer in the data that imported in the previous steps.<br />
				</p>
				</td>
				</tr>
				<tr>
			    <td align="right">
				<div class="begin"><a href="8.php">Style Defination</a></div>
				</td>
				</tr>
				</table>
			</div>

<?php
}

?>
<?php

if ($error == "" AND $recordgood == 0) {

?>
			<h1>No Data Has Been Imported</h1>
			<p id="intro"><FONT color=green><?=$recordgood?></FONT> records has been imported into database. <br>
			Please check your data. </p>
			<br />

			<div id="options">
			<table class="tableContent">
                <tr>
			    <td>
			    <h2>Select more files</h2>
				<p>Select more file to import data into database<br />
				</p>
				</td>
				</tr>
				<tr>
			    <td align="right">
				<div class="begin"><a href="7.php">Select file</a></div>
				</td>
				</tr>
				</table>

			</div>

<?php
}

?>
<?php
if ($error != "" AND $recordgood > 0) {
?>
<table class="tableError">
<tr>
      <td>
			<h4>Data Import Meet Errors</h4>
			<p id="intro"><FONT color=green><?=$recordgood?></FONT> records have been imported into database<br>
			<FONT color=red><?=$recordbad?></FONT> records have mistakes and can not be imported into database<br>
            Errors are listed below, please copy the error and go to
			<a href="http://www.easywms.com/easywms/?q=en/node/158" target="_blank" class="error">Issue Tracker</a>
			to submit the error, to help us fix the bugs, thank you very much!
			</p>
	</td>
  </tr>
  <tr>
      <td>
  <form name="formLog">
  <table class="tableError">
  <tr>
     <td align=right><INPUT class="button" name="Button" onclick="HighlightAll('formLog.textareaLog')" type="button" value="Copy to Clip"></td>
  </tr>
    <tr>
      <td height="50" align="middle">
       <TEXTAREA class="editbox1" name="textareaLog" id="textareaLog" wrap="VIRTUAL"><?=$log?>
	   </TEXTAREA>
  </td>
  </tr>
  </table>
  </form>
  </td>
  </tr>
</table>
<br />
<p id="intro">But you can still do that:
You <FONT class="error">MUST</FONT> create style after new data has been imported!<br>
</p>
<br>

			<div id="options">
			<table class="tableContent">
             <tr>
              <td>
			    <h2>Select more files</h2>
				<p>Select more files to import data into database<br />
				</p>
				</td>
  </tr>
				<tr>
              <td align="right">
				<div class="begin"><a href="7.php">Select file</a></div><br />
				</td>
  </tr>
  </table>
<br><br>
            <table class="tableContent">
             <tr>
              <td>
				<h2>Style Defination</h2>
				<p>Create Styles (display range and symbology) for each Layer in the data that imported in the previous steps.<br />
				</p>
				</td>
  </tr>
				<tr>
              <td align="right">
				<div class="begin"><a href="8.php">Style Defination</a></div><br />
				</td>
  </tr>
  </table>

			</div>

<?php
}

?>
<?php
if ($error != "" AND $recordgood == 0 OR $recordgood == null) {
    // Failure
    ?>
    <br><br>
    <table class="tableError">
    <tr>
    <td>
			<h4>Failure</h4>
			<p id="intro">You must correct the error below before installation can continue:<br />
			Refer to <a href="http://dev.mysql.com/doc/refman/5.0/en/error-messages-server.html" target="_blank">MySQL Error Messages</a>	  <br />
			</p>
			</td>
	</tr>
	<tr>
    <td>
		<form name="formError">
  		<table class="tableError">
 		 <tr>
     	<td align=right><INPUT class="button" name="Button" onclick="HighlightAll('formError.textareaError')" type="button" value="Copy Error to Clip"></td>
  		</tr>
    	<tr>
      	<td height="50" align="middle">
       <TEXTAREA class="editbox2" name="textareaError" id="textareaError" wrap="VIRTUAL"><?=$error?>
	   </TEXTAREA>
 		 </td>
  	</tr>
  	</table>
  	</form>
  	<br>
  			<p id="intro">
			<FONT class="error"><?=$recordbad?></FONT> errors are listed below, please copy the error and go to
			<a href="http://www.easywms.com/easywms/?q=en/node/158" target="_blank" class="error">Issue Tracker</a>
			to submit the error, to help us fix the bugs, thank you very much!<br /><br />
			</p>
  <form name="formLog">
  <table class="tableError">
  <tr>
     <td align=right><INPUT class="button" name="Button" onclick="HighlightAll('formLog.textareaLog')" type="button" value="Copy Log to Clip"></td>
  </tr>
    <tr>
      <td height="100" align="middle">
       <TEXTAREA class="editbox1" name="textareaLog" id="textareaLog" wrap="VIRTUAL"><?=$log?>
	   </TEXTAREA>
  </td>
  </tr>
  </table>
  </form>
  	</td>
	</tr>
  </table>
  <br />
					 <input onclick="GoBack();" name="button" value="Back" onmouseover="this.className='button1'" onmouseout="this.className='button'" class="button">

<?php
}

?>
	</td>
</tr>
</table>

</body>
</html>