<?php
/**
 * Getmapcap GetCapabilities Class
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
 * @Description: This show the copyright .
 * @contact webmaster@easywms.com
 * @version $3.0$ 2006
 * @Author  leelight
 * @Contact webmaster@easywms.com
 */

//include_once 'SendException.php';
require_once '../global.php';

if($enablecache == 1){
$cache = new Cache($cacheLimitTime,10);
$cache->cacheCheck();
}

$database = new Database();
$erroroccured = false;
$errornumber = 0;
$errorexceptionstring = "";


  $database->databaseConfig($servername,$username,$password,$dbname);
  $database->databaseConnect();

  #if (mysql_select_db($dbname) != True){
  #$errornumber = -1;
  #$errorexceptionstring = "Database could not be opened!";
  #}

  if ($database->databaseGetErrorMessage() !=""){
      $errornumber = -1;
      $errorexceptionstring = $database->databaseGetErrorMessage();
  }

  if ($errornumber != 0){
      $sendexceptionclass->sendexception($errornumber,$errorexceptionstring);
  }
  else{
   header("Content-type: text/xml;charset=utf-8");
   print('<?xml version="1.0" encoding="UTF-8"?>');

?>
<WFS_Capabilities
    updateSequence="0"
	version="<?=$wfsversion?>"
	xmlns:wfs="http://www.opengis.net/wfs"
	xmlns:ogc="http://www.opengis.net/ogc"
	xmlns="http://www.opengis.net/wfs"
	xmlns:gml="http://www.opengis.net/gml"
	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://www.opengis.net/wfs http://schemas.opengeospatial.net/wfs/1.0.0/WFS-capabilities.xsd">
	<Service>
		<Name><?=$softName?> WFS</Name>
		<Title><?=$softName?> WFS</Title>
		<OnlineResource>www.easywms.com</OnlineResource>
	</Service>
    <Capability>
		<Request>
			<GetCapabilities>
				<DCPType>
					<HTTP>
						<Get onlineResource="<?=$wmsmetadata["ServerHost"].$wfsservice?>/getmapcap.php"/>
						<Post onlineResource="<?=$wmsmetadata["ServerHost"].$wfsservice?>/getmapcap.php"/>
					</HTTP>
				</DCPType>
			</GetCapabilities>
			<DescribeFeatureType>
				<SchemaDescriptionLanguage>
					<XMLSCHEMA/>
				</SchemaDescriptionLanguage>
				<DCPType>
					<HTTP>
						<Get onlineResource="<?=$wmsmetadata["ServerHost"].$wfsservice?>/getmapcap.php"/>
						<Post onlineResource="<?=$wmsmetadata["ServerHost"].$wfsservice?>/getmapcap.php"/>
					</HTTP>
				</DCPType>
			</DescribeFeatureType>
			<GetFeature>
				<ResultFormat>
					<GML2/>
				</ResultFormat>
				<DCPType>
					<HTTP>
						<Get onlineResource="<?=$wmsmetadata["ServerHost"].$wfsservice?>/getmapcap.php"/>
						<Post onlineResource="<?=$wmsmetadata["ServerHost"].$wfsservice?>/getmapcap.php"/>
					</HTTP>
				</DCPType>
			</GetFeature>
			<Transaction>
				<DCPType>
					<HTTP>
						<Post onlineResource="<?=$wmsmetadata["ServerHost"].$wfsservice?>/getmapcap.php"/>
					</HTTP>
				</DCPType>
			</Transaction>
		</Request>
	</Capability>

    <FeatureTypeList>
        <Operations>
            <Query />
        </Operations>
<?
    $rs3 = $database->getRows4MetaGroupBy($tbmetaname,"layer");
    while ($line3 = $database->getColumns($rs3)){
        $currentlayername = $line3["layer"];
        $currentlayertitle = $line3["description"];
?>
    <FeatureType>
      <Name><?=$currentlayername?> </Name>
      <Title><?=$currentlayertitle?></Title>
<?
  $rs4 = $database->getRowsByLayerGroupBy($tbmetaname,$currentlayername,"layer,srs");
  while ($line4 = $database->getColumns($rs4)){
      $currentsrs = $line4["srs"];
      echo "    <SRS>".$currentsrs."</SRS>";
  }
  $rs5 = $database->getRowsByLayerGroupBy($tbmetaname,$currentlayername,"layer,srs");
  while ($line5 = $database->getColumns($rs5)){
      $currentsrs = $line5["srs"];
      $rs6 = $database->getRowsMinMaxXYBySrsLayer($tbmetaname,$currentsrs,$currentlayername);
	  $line6 = $database->getColumns($rs6);
      $totalminx = $line6[0];
      $totalminy = $line6[1];
      $totalmaxx = $line6[2];
      $totalmaxy = $line6[3];
      echo "    <LatLongBoundingBox minx=\"".$totalminx."\" miny=\"".$totalminy."\" maxx=\"".$totalmaxx."\" maxy=\"".$totalmaxy."\"/>\n";
  }
?>
      </FeatureType>
<?php
  } // end for while ($line1 = mysql_fetch_array($rs1))
?>
    </FeatureTypeList>
	<!-- ADDITIONAL CAPABILITIES -->
	<ogc:Filter_Capabilities>
		<ogc:Spatial_Capabilities>
			<ogc:Spatial_Operators>
				<ogc:BBOX/>
				<ogc:Intersect/>
			</ogc:Spatial_Operators>
		</ogc:Spatial_Capabilities>
		<ogc:Scalar_Capabilities>
			<ogc:Logical_Operators/>
			<ogc:Comparison_Operators>
				<ogc:Simple_Comparisons/>
				<ogc:Like/>
			</ogc:Comparison_Operators>
		</ogc:Scalar_Capabilities>
	</ogc:Filter_Capabilities>
</WFS_Capabilities>
<?
}
$database->databaseClose();

if($enablecache == 1){
$cache->caching();
}
exit();
?>