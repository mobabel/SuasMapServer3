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
 * @version $1.0$ 2005
 * @Author  Filmon Mehari and Professor Dr. Franz Josef Behr
 * @Contact filmon44@yahoo.com and franz-josef.behr@hft-stuttgart.de
 * @version $2.0$ 2006.05
 * @Author  Chen Hang and leelight
 * @Contact unitony1980@hotmail.com
 * @version $3.0$ 2006
 * @Author  leelight
 * @Contact webmaster@easywms.com
 */

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

if ($database->databaseGetErrorMessage() !=""){
    $errornumber = -1;
    $errorexceptionstring = $database->databaseGetErrorMessage();
    $sendexceptionclass->sendexception($errornumber,$errorexceptionstring);
}
if ($errornumber == 0){
   header("Content-type: text/xml;charset=utf-8");
   print('<?xml version="1.0" encoding="UTF-8"?>');
?>
 <!DOCTYPE WMT_MS_Capabilities SYSTEM
 "<?=$wmsmetadata['ServerHost'].$wmsservice?>/capabilities_1_1_1.dtd"
 [
 <!ELEMENT VendorSpecificCapabilities EMPTY>
 ]
 >

<WMT_MS_Capabilities version="<?=$wmsversion?>" updateSequence="<?=date("Ymd")?>">

<Service>
  <Name>OGC:WMS</Name>
  <!-- Human-readable title for pick lists-->
  <Title><?=$wmsmetadata["ServerTitle"]?></Title>
  <!-- Narrative description providing additional information-->
  <Abstract><?=$wmsmetadata["ServerAbstract"]?></Abstract>
  <KeywordList>
    <Keyword>WMS <?=$version?></Keyword>
    <Keyword><?=$wmsmetadata["Keyword1"]?></Keyword>
    <Keyword><?=$wmsmetadata["Keyword2"]?></Keyword>
  </KeywordList>
  <!-- Top-level address of service or service provider-->
  <OnlineResource xmlns:xlink="http://www.w3.org/1999/xlink" xlink:type="simple"
   xlink:href="<?=$wmsmetadata['ServerHost']?>getmapcap.php" />
  <!-- Contact information -->
  <ContactInformation>
    <ContactPersonPrimary>
      <ContactPerson><?=$wmsmetadata["ContactPerson"]?></ContactPerson>
      <ContactOrganization><?=$wmsmetadata["ContactOrganization"]?></ContactOrganization>
    </ContactPersonPrimary>
    <ContactPosition><?=$wmsmetadata["ContactPosition"]?></ContactPosition>
    <ContactAddress>
      <AddressType><?=$wmsmetadata['ContactAddress']['AddressType']?></AddressType>
      <Address><?=$wmsmetadata['ContactAddress']['Address']?></Address>
      <City><?=$wmsmetadata['ContactAddress']['City']?></City>
      <StateOrProvince><?=$wmsmetadata['ContactAddress']['StateOrProvince']?></StateOrProvince>
      <PostCode><?=$wmsmetadata['ContactAddress']['PostCode']?></PostCode>
      <Country><?=$wmsmetadata['ContactAddress']['Country']?></Country>
    </ContactAddress>
    <ContactVoiceTelephone><?=$wmsmetadata["ContactVoiceTelephone"]?></ContactVoiceTelephone>
    <ContactFacsimileTelephone><?=$wmsmetadata["ContactFacsimileTelephone"]?></ContactFacsimileTelephone>
    <ContactElectronicMailAddress><?=$wmsmetadata["ContactElectronicMailAddress"]?></ContactElectronicMailAddress>
  </ContactInformation>
  <!-- Fees or access constraints imposed. -->
  <Fees>none</Fees>
  <AccessConstraints>none</AccessConstraints>
</Service>
<Capability>
  <Request>
    <GetCapabilities>
      <Format>application/vnd.ogc.wms_xml</Format>
      <DCPType>
        <HTTP>
          <Get>
             <OnlineResource xmlns:xlink="http://www.w3.org/1999/xlink"
             xlink:type="simple"
             xlink:href="<?=$wmsmetadata["ServerHost"]?>getmapcap.php" />
          </Get>
          <Post>
             <OnlineResource xmlns:xlink="http://www.w3.org/1999/xlink"
             xlink:type="simple"
             xlink:href="<?=$wmsmetadata["ServerHost"]?>getmapcap.php" />
          </Post>
        </HTTP>
      </DCPType>
    </GetCapabilities>
    <GetMap>
      <Format>image/svg+xml</Format>
      <Format>image/svgt+xml</Format>
      <Format>image/svgb+xml</Format>
      <Format>image/svgz</Format>
      <Format>image/pdf</Format>
      <Format>image/ezpdf</Format>
      <Format>image/swf</Format>
      <Format>image/vml</Format>
      <Format>image/vrml</Format>
      <Format>image/png</Format>
      <Format>image/jpeg</Format>
      <Format>image/gif</Format>
      <Format>image/wbmp</Format>
        <DCPType>
        <HTTP>
          <Get>
             <OnlineResource xmlns:xlink="http://www.w3.org/1999/xlink"
             xlink:type="simple"
             xlink:href="<?=$wmsmetadata["ServerHost"].$wmsservice?>/getmapcap.php"/>
          </Get>
        </HTTP>
      </DCPType>
    </GetMap>
    <GetFeatureInfo>
      <Format>text/xml</Format>
        <DCPType>
        <HTTP>
          <Get>
             <OnlineResource xmlns:xlink="http://www.w3.org/1999/xlink"
		     xlink:href="<?=$wmsmetadata["ServerHost"].$wmsservice?>/getmapcap.php" xlink:type="simple" />
          </Get>
        </HTTP>
        </DCPType>
    </GetFeatureInfo>
    <DescribeLayer>
      <Format>text/xml</Format>
        <DCPType>
        <HTTP>
          <Get>
             <OnlineResource xmlns:xlink="http://www.w3.org/1999/xlink"
		     xlink:href="<?=$wmsmetadata["ServerHost"].$wmsservice?>/getmapcap.php" xlink:type="simple" />
          </Get>
        </HTTP>
        </DCPType>
    </DescribeLayer>
    <GetLegendGraphic>
      <Format>image/png</Format>
      <Format>image/jpeg</Format>
      <Format>image/svg+xml</Format>
        <DCPType>
        <HTTP>
          <Get>
            <OnlineResource xmlns:xlink="http://www.w3.org/1999/xlink"
		    xlink:href="<?=$wmsmetadata["ServerHost"].$wmsservice?>/getmapcap.php" xlink:type="simple" />
          </Get>
        </HTTP>
        </DCPType>
    </GetLegendGraphic>
  </Request>
  <Exception>
    <Format>application/vnd.ogc.se_xml</Format>
    <Format>application/vnd.ogc.se_inimage</Format>
  </Exception>
  <UserDefinedSymbolization SupportSLD="1" UserLayer="0" UserStyle="1" RemoteWFS="0" />
  <Layer queryable="0" opaque="0" noSubsets="0">
    <Title><?=$wmsmetadata["LayerTitle"]?></Title>
<?
   #$rs0 = mysql_query("SELECT * FROM $tbname  GROUP BY srs ",$database->databaseConnection);
   #$rs1 = mysql_query("SELECT * FROM $tbname  GROUP BY srs ",$database->databaseConnection);
   $rs0 = $database->getRows4MetaGroupBy($tbmetaname,"srs");
   $rs1 = $database->getRows4MetaGroupBy($tbmetaname,"srs");
   #while (@$line0 = mysql_fetch_array($rs0)){
   while ($line0 = $database->getColumns($rs0)){
       $currentsrs = $line0["srs"];
       echo "    <SRS>".$currentsrs."</SRS>\n";
   }
    
    $rsLatLonBoundingBox = $database->getRowsMinMaxXY($tbmetaname);
    $lineLatLonBoundingBox = $database->getColumns($rsLatLonBoundingBox);
    $totalminxLatLonBoundingBox = $lineLatLonBoundingBox[0];
    $totalminyLatLonBoundingBox = $lineLatLonBoundingBox[1];
    $totalmaxxLatLonBoundingBox = $lineLatLonBoundingBox[2];
    $totalmaxyLatLonBoundingBox = $lineLatLonBoundingBox[3];
    echo "    <LatLonBoundingBox minx=\"".$totalminxLatLonBoundingBox."\" miny=\"".$totalminyLatLonBoundingBox."\" maxx=\"".$totalmaxxLatLonBoundingBox."\" maxy=\"".$totalmaxyLatLonBoundingBox."\"/>\n";
    echo "    <!-- BoundingBox is inheritable, we define at root, inherited by all--> \n";
    #while (@$line5 = mysql_fetch_array($rs1)){
    while ($line1 = $database->getColumns($rs1)){
        $currentsrs = $line1["srs"];
        #$rs2 = mysql_query("SELECT MIN(xmin), MIN(ymin), MAX(xmax), MAX(ymax) FROM $tbname  where srs = '$currentsrs' ",$database->databaseConnection );
        $rs2 = $database->getRowsMinMaxXYBySrs($tbmetaname,$currentsrs);
	$line2 = $database->getColumns($rs2);
        $totalminx = $line2[0];
        $totalminy = $line2[1];
        $totalmaxx = $line2[2];
        $totalmaxy = $line2[3];
        echo "    <BoundingBox SRS=\"".$currentsrs."\" minx=\"".$totalminx."\" miny=\"".$totalminy."\" maxx=\"".$totalmaxx."\" maxy=\"".$totalmaxy."\"/>\n";
}
?>
    <!-- all layers are available in at least this CRS -->
<?
  #$rs3 = mysql_query("SELECT * FROM  $tbname  GROUP BY layer ",$database->databaseConnection);
  $rs3 = $database->getRows4MetaGroupBy($tbmetaname,"layer");
  while ($line3 = $database->getColumns($rs3)){
      $currentlayername = $line3["layer"];
      $currentlayertitle = $line3["description"];
  ?>
    <Layer queryable="0" cascaded="0" opaque="1" noSubsets="0" fixedWidth="0" fixedHeight="0" >
      <Name><?=$currentlayername?> </Name>
      <Title><?=$currentlayertitle?></Title>
      <!--<Abstract>note</Abstract>-->
  <?
  #$rs4 = mysql_query("SELECT * FROM  $tbname  WHERE layer = '$currentlayername' GROUP BY layer,srs ",$database->databaseConnection);
  $rs4 = $database->getRowsByLayerGroupBy($tbmetaname,$currentlayername,"layer,srs");
  while ($line4 = $database->getColumns($rs4)){
      $currentsrs = $line4["srs"];
      echo "    <SRS>".$currentsrs."</SRS>";
  }
  #$rs5 = mysql_query("SELECT * FROM $tbname  WHERE layer = '$currentlayername' GROUP BY layer,srs ",$database->databaseConnection);
  $rs5 = $database->getRowsByLayerGroupBy($tbmetaname,$currentlayername,"layer,srs");
  while ($line5 = $database->getColumns($rs5)){
      $currentsrs = $line5["srs"];
      $currentstyle = $line5["style"];
      #$rs6 = mysql_query("SELECT MIN(xmin), MIN(ymin), MAX(xmax), MAX(ymax) FROM $tbname where srs = '$currentsrs' AND layer ='$currentlayername' ",$database->databaseConnection );
      $rs6 = $database->getRowsMinMaxXYBySrsLayer($tbmetaname,$currentsrs,$currentlayername);
	  $line6 = $database->getColumns($rs6);
      $totalminx = $line6[0];
      $totalminy = $line6[1];
      $totalmaxx = $line6[2];
      $totalmaxy = $line6[3];
      echo "    <BoundingBox SRS=\"".$currentsrs."\" minx=\"".$totalminx."\" miny=\"".$totalminy."\" maxx=\"".$totalmaxx."\" maxy=\"".$totalmaxy."\"/>\n";
  }
 ?>
      <Style>
        <Name><?=$currentstyle?></Name>
        <Title><?=$currentstyle?></Title>
        <LegendURL width="20" height="15">
          <Format>image/png</Format>
          <OnlineResource xmlns:xlink="http://www.w3.org/1999/xlink"
		  xlink:href="<?=$wmsmetadata["ServerHost"]?>SLD/Legends/<?=$dbname.$tableprefix?>/<?=$currentlayername?>_Default.png" xlink:type="simple" />
        </LegendURL>
      </Style>
	</Layer>
 <?php
  } // end for while ($line1 = mysql_fetch_array($rs1))
 ?>
  </Layer>
</Capability>
</WMT_MS_Capabilities>
<?
}
$database->databaseClose();

if($enablecache == 1){
$cache->caching();
}
exit();
?>