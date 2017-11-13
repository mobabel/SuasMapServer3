<?php
/**
 * Getmapcap Class
 * Copyright (C) 2006-2007  LI Hui
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
 * @copyright (C) 2006-2007  LI Hui
 * @Description : This show the copyright .
 * @contact webmaster@easywms.com
 * @version $1.0$ 2005
 * @Author Filmon Mehari and Professor Dr. Franz Josef Behr
 * @Contact filmon44@yahoo.com and franz-josef.behr@hft-stuttgart.de
 * @version $2.0$ 2006.05
 * @Author Chen Hang and leelight
 * @Contact unitony1980@hotmail.com
 * @version $3.0$ 2006 - now
 * @Author LI Hui
 * @Contact webmaster@easywms.com
 */

  $erroroccured = false;
  $errornumber = 0;
  $errorexceptionstring = "";

  // check format error
  if ( !compareStringInsensitive($format,"image/svg+xml") AND !compareStringInsensitive($format,"image/svgt+xml") AND !compareStringInsensitive($format,"image/svgb+xml")
  AND !compareStringInsensitive($format,"image/svgz+xml") AND !compareStringInsensitive($format,"image/svgtz+xml") AND !compareStringInsensitive($format,"image/svgbz+xml")
  AND !compareStringInsensitive($format,"image/png") AND !compareStringInsensitive($format,"image/jpeg")
  AND !compareStringInsensitive($format,"image/gif")AND !compareStringInsensitive($format,"image/pdf") AND !compareStringInsensitive($format,"image/ezpdf")
  AND !compareStringInsensitive($format,"image/wbmp") AND !compareStringInsensitive($format,"image/swf")
  AND !compareStringInsensitive($format,"image/bmp") AND !compareStringInsensitive($format,"model/vrml")
  AND !compareStringInsensitive($format,"model/vrmlz") AND !compareStringInsensitive($format,"model/x3d+xml")
  AND !compareStringInsensitive($format,"application/vnd.google-earth.kml+xml") AND !compareStringInsensitive($format,"application/vnd.google-earth.kmz")
  AND !compareStringInsensitive($format,"model/x3dz")
  AND $format !=""){
    $erroroccured = false;
    $errornumber = 3;
    $errorexceptionstring = "Invalid Format '" .$format.
	"' is given.The \"image/svg+xml\" , \"image/svgt+xml\",\"image/svgb+xml\", \"image/svgz+xml\",\"image/svgtz+xml\",".
	"\"image/svgbz+xml\",\"model/vrml\",\"model/vrmlz\", \"model/x3d+xml\", \"model/x3dz\",".
	"\"application/vnd.google-earth.kml+xml\",\"application/vnd.google-earth.kmz\",".
	"\"image/png\", \"image/pdf\", \"image/ezpdf\", \"image/swf\", \"image/jpeg\", \"image/gif\" ,".
	"\"image/bmp\" and \"image/wbmp\" are the formats supported" ;
  }
  if ($format  == ""){
    $erroroccured = false;
    $errornumber = 4;
    $errorexceptionstring = "Format has not been given.";
  }
  if ($errornumber != 0){
    $sendexceptionclass->sendexception($errornumber,$errorexceptionstring);
  }

    /**Getmap SVG Class*/
  if ( compareStringInsensitive($format,"image/svg+xml") ){
    include 'GetMap_SVG.class.php';
  }
  /**Getmap SVGT Class*/
  if ( compareStringInsensitive($format,"image/svgt+xml") ){
    include 'GetMap_SVGT.class.php';
  }
  /**Getmap SVGB Class*/
  if ( compareStringInsensitive($format,"image/svgb+xml") ){
    include 'GetMap_SVGT.class.php';
  }
  /**Getmap SVGB Class*/
  if ( compareStringInsensitive($format,"image/svgz+xml") ){
    include 'GetMap_SVGZ.class.php';
  }
    /**Getmap SVGT Class*/
  if ( compareStringInsensitive($format,"image/svgtz+xml") ){
    include 'GetMap_SVGTZ.class.php';
  }
  /**Getmap SVGB Class*/
  if ( compareStringInsensitive($format,"image/svgbz+xml") ){
    include 'GetMap_SVGTZ.class.php';
  }
  /**Getmap PNG Class*/
  if ( compareStringInsensitive($format,"image/png") OR compareStringInsensitive($format,"image/gif") OR compareStringInsensitive($format,"image/jpeg")
   OR compareStringInsensitive($format,"image/wbmp") OR $format == "image/bmp"){
    include 'GetMap_RasterImage.class.php';
  }
  /**Getmap PDF Class*/
  if ( compareStringInsensitive($format,"image/pdf")){
    include 'GetMap_PDF.class.php';
  }
  /**Getmap PDF free Class*/
  if ( compareStringInsensitive($format,"image/ezpdf") ){
    include 'GetMap_EzPDF.class.php';
  }
  /**Getmap SWF Class*/
  if ( compareStringInsensitive($format,"image/swf") ){
    include 'GetMap_SWF.class.php';
  }
  if ( compareStringInsensitive($format,"model/vrml") OR compareStringInsensitive($format,"model/vrmlz")){
      if(compareStringInsensitive($request,"GetMap3D"))
          include 'GetMap_VRML.class.php';
      else{
	      $errornumber = 56;
          $errorexceptionstring = "The REQUEST should be GetMap3D, but ".$request." is given.";
          $sendexceptionclass->sendexception($errornumber,$errorexceptionstring);
	  }

  }
  if ( compareStringInsensitive($format,"model/x3d+xml") OR compareStringInsensitive($format,"model/x3dz")){
      if(compareStringInsensitive($request,"GetMap3D"))
          include 'GetMap_X3D.class.php';
      else{
	      $errornumber = 57;
          $errorexceptionstring = "The REQUEST should be GetMap3D, but ".$request." is given.";
          $sendexceptionclass->sendexception($errornumber,$errorexceptionstring);
	  }

  }
  if ( compareStringInsensitive($format,"application/vnd.google-earth.kml+xml") OR compareStringInsensitive($format,"application/vnd.google-earth.kmz")){
      if(compareStringInsensitive($request,"GetMap3D") OR compareStringInsensitive($request,"GetMap"))
          include 'GetMap_KML.class.php';
      else{
	      $errornumber = 58;
          $errorexceptionstring = "The REQUEST should be GetMap3D, but ".$request." is given.";
          $sendexceptionclass->sendexception($errornumber,$errorexceptionstring);
	  }

  }

?>