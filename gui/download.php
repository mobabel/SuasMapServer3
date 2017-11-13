<?PHP

	// File Download Script
	// by James Warkentin

	// This script is provided without warrenty of any kind, nor may the author be held liable for
	// for any problems which arise as a result of the use of this script.


	// Modify this line to indicate the location of the files you want people to be able to download
	// This path must not contain a trailing slash.  ie.  /temp/files/download

	$download_path = $_SERVER['DOCUMENT_ROOT'] . "/suas3/gui";



	/* ###################################################################################*/
	/*                     DO NOT MODIFY THE SCRIPT BEYOND THIS POINT                     */
	/* ###################################################################################*/

	$filename = "demo".$_GET['id'].".pdf";

	// Detect missing filename
	if(!$filename) die("map id must be given.");

	// Make sure we can't download files above the current directory location.
	if(eregi("\.\.", $filename)) die("You may not download that file.");
	$file = str_replace("..", "", $filename);

	// Make sure we can't download .ht control files.
	if(eregi("\.ht.+", $filename)) die("I'm sorry, you may not download that file.");

	// Combine the download path and the filename to create the full path to the file.
	$file = "$download_path/$file";

	// Test to ensure that the file exists.
	if(!file_exists($file)) die("The pdf map doesn't seem to exist.");

	// Extract the type of file which will be sent to the browser as a header
	$type = filetype($file);

	// Get a date and timestamp
	$today = date("F j, Y, g:i a");
	$time = time();

	// Send file headers
	header("Content-type: $type");
	header("Content-Disposition: attachment;filename=$filename");
	header('Pragma: no-cache');
	header('Expires: 0');

	// Send the file contents.
	readfile($file);
?>