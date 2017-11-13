<?php 
// Database config data
$servername = 'localhost';
$username   = 'root';
$password   = 'test';
$dbname     = 'rr';
$tbname     = 'fridafeaturegeometry';
$tbmetaname     = 'fridafeatureclass';
$tableprefix     = 'frida';
$wmsservice     = 'WMS';
$wmsversion     = '1.1.1';
$wfsservice     = 'WFS';
$wfsversion     = '1.1.1';
$enablestretchmap                                      = 1;
$enablecache                                           = 0;
//Metadata
$wmsmetadata = array();
$wmsmetadata['ServerHost'] 				= 'http://localhost/suas3/';
$wmsmetadata['ServerTitle'] 				= 'Open Source SUAS MapServer';
$wmsmetadata['ServerAbstract'] 			= 'Open source based WMS compliant Web Map Sever';
$wmsmetadata['LayerTitle'] 				= 'SUAS MapServer layers';
$wmsmetadata['Keyword1'] 				= 'SVG';
$wmsmetadata['Keyword2'] 				= 'WEB MAP SERVER';
$wmsmetadata['ContactPerson'] 			= 'leelight';
$wmsmetadata['ContactOrganization'] 	                = 'EasyWMS';
$wmsmetadata['ContactPosition'] 			            = 'ContactPosition';
$wmsmetadata['ContactAddress']['AddressType'] 	= 'postal';
$wmsmetadata['ContactAddress']['Address'] 		= 'Address';
$wmsmetadata['ContactAddress']['City'] 		= 'Stuttgart';
$wmsmetadata['ContactAddress']['StateOrProvince'] 	= 'BW';
$wmsmetadata['ContactAddress']['PostCode']     	= '70437';
$wmsmetadata['ContactAddress']['Country'] 		= 'Germany';
$wmsmetadata['ContactVoiceTelephone'] 		= '0049';
$wmsmetadata['ContactFacsimileTelephone'] 	    	= '0049';
$wmsmetadata['ContactElectronicMailAddress'] 	= 'webmaster@easywms.com';
?>