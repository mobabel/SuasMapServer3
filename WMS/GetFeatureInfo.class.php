<?php
/**
 * GetFeatureInfo Class
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
 * @Description : This show the copyright .
 * @contact webmaster@easywms.com
 * @version $1.0$ 2005
 * @Author Filmon Mehari and Professor Dr. Franz Josef Behr
 * @Contact filmon44@yahoo.com and franz-josef.behr@hft-stuttgart.de
 * @version $2.0$ 2006.05
 * @Author Chen Hang and leelight
 * @Contact unitony1980@hotmail.com
 * @version $3.0$ 2006
 * @Author leelight
 * @Contact webmaster@easywms.com
 */

require_once '../global.php';

$erroroccured = false;
$errornumber = 0;
$errorexceptionstring = "";
$database = new Database();

if (strcasecmp($info_format, "text/xml") != 0 AND strcasecmp($info_format, "text/html") != 0 AND $info_format != "") {
    $erroroccured = false;
    $errornumber = 3;
    $errorexceptionstring = "Invalid Info_Format '" . $format . "' given.The \"text/xml\" and \"text/html\"  are the only supported info_format" ;
}
if ($info_format == "") {
    // set default info format
    $info_format = "text/html";
}

$database->databaseConfig($servername, $username, $password, $dbname);
$database->databaseConnect();

if ($database->databaseGetErrorMessage() != "") {
    $errornumber = -1;
    $errorexceptionstring = $database->databaseGetErrorMessage();
    $sendexceptionclass->sendexception($errornumber, $errorexceptionstring);
}
if ($errornumber == 0) {
    $bboxvalues = explode(",", $bbox);
    $numberofvalues = count($bboxvalues);
    $minx = $bboxvalues[0];
    $miny = $bboxvalues[1];
    $maxx = $bboxvalues[2];
    $maxy = $bboxvalues[3];

    if ($width == "" OR $height == "") {
        $erroroccured = false;
        $errornumber = 13;
        $errorexceptionstring = "The width OR the height cannot be empty. " ;
    }
    if ($maxx <= $minx OR $maxy <= $miny) {
        $erroroccured = false;
        $errornumber = 15;
        $errorexceptionstring = "The BBox parameters are not valid." ;
    }

    $query_layersvalues = explode(",", $query_layers);
    $numberofquery_valueslayer = count($query_layersvalues);

    $size = getStretchWidthHeight($minx, $miny, $maxx, $maxy, $width, $height, $enablestretchmap);
    $width = $size[0];
    $height = $size[1];

    $newsquare = getSelectSquare($minx, $miny, $maxx, $maxy, $width, $height, $Pixel_x, $Pixel_y, $radius, $enablestretchmap);
    $x_plus = $newsquare[0];
    $x_minus = $newsquare[1];
    $y_plus = $newsquare[2];
    $y_minus = $newsquare[3];
    $x_real = $newsquare[4];
    $y_real = $newsquare[5];

    for ($i = 0; $i < $numberofquery_valueslayer; $i++) {
        // $rs1 = $database->getSelectFeatureInBoxBy($tbmetaname, $minx, $miny, $maxx, $maxy, $x_plus, $x_minus, $y_plus, $y_minus, $query_layersvalues[$i]);
        // $rs2 = $database->getSelectFeatureInSquareBy($tbmetaname, $x_plus, $x_minus, $y_plus, $y_minus, $query_layersvalues[$i]);
        // $line2 = $database->getColumns($rs2);
        /*$line3 is used to justify one layername with no result, $line3 must have something, unless the layer doesnt exist!
        *1, layername is wrong, $line3 is  null, send exception
        *2, layername is right, but $line2 is null, there is no entities inside,but $line3 is not null, then go on to create XML file.
        */

        $rs3 = $database->getRowsByLayer($tbmetaname, $query_layersvalues[$i]);
        $line3 = $database->getColumns($rs3);

        $rs7 = $database->getRowsMinMaxXYByLayer($tbmetaname, $query_layersvalues[$i]);
        $line7 = $database->getColumns($rs7);
        $totalminx = $line7[0];
        $totalminy = $line7[1];
        $totalmaxx = $line7[2];
        $totalmaxy = $line7[3];
        // check BBox error, if not in bbox, should be skipped
        if ($maxx < $totalminx OR $miny > $totalmaxy OR $minx > $totalmaxx OR $maxy < $totalminy) {
            $erroroccured = false;
            // $errornumber = 1;
            // $errorexceptionstring = "Invalid bounding box coordinates for Query_Layers =" .$query_layersvalues[$i].". Easting must be between " .$totalminx. " and " .$totalmaxx. " AND Northing  must be between " .$totalminy. " and " .$totalmaxy.".";
        }
        // check X Y error
        if ($Pixel_x > $width OR $Pixel_y > $height OR $Pixel_x < 0 OR $Pixel_y < 0) {
            $erroroccured = false;
            $errornumber = 10;
            $errorexceptionstring = "Invalid feature coordinates in Query_Layers =" . $query_layersvalues[$i] . ". X must be between 0 and " . $width . " AND Y  must be between 0 and " . $height . ".";
        }
        // check layer error
        if ($line3 == null AND $query_layers != "" AND $Pixel_x != "" AND $Pixel_y != "" AND $radius != "") {
            $erroroccured = false;
            $errornumber = 2;
            $errorexceptionstring = "LayerNotDefined.Layer " . $query_layersvalues[$i] . " or feature with coordinate " . $Pixel_x . "," . $Pixel_y . " with REDIUS" . $radius . " is not found, Please check your Layer name AND/OR reset the feature X Y coordinate or radius.";
        } else if ($Pixel_x == "" OR $Pixel_y == "") {
            $erroroccured = false;
            $errornumber = 3;
            $errorexceptionstring = "Feauture coordinate X Y " . $Pixel_x . " or " . $Pixel_y . " not specified. Please input valid Feauture coordinate X Y!";
        } else if ($radius == "") {
            $radius = $params['GetFeatureInfoRedius'];
            // $erroroccured = false;
            // $errornumber = 4;
            // $errorexceptionstring = "Redius " . $radius . " not specified. Please set it!";
        } else if ($query_layers == "") {
            $erroroccured = false;
            $errornumber = 5;
            $errorexceptionstring = "Layer " . $query_layersvalues[$i] . " not specified. Please insert Query Layer names!";
        }
        // $data2 = $line2["style"];
        // $data3 = $line2["srs"];
        // $data4 = $line2["layer"];
        // $data5a = $line2["recid"];
        // $data6a = $line2["svgxlink"];
        // $data7a = $line2["attributes"];
    }
    if ($errornumber != 0) {
        $sendexceptionclass->sendexception($errornumber, $errorexceptionstring);
    } else {
        if (strcasecmp($info_format, "text/xml") == 0) {
            header("Content-type: text/xml;charset=utf-8");
            print('<?xml version="1.0" encoding="UTF-8"?>');

            ?>
  <FeatureInfoResponse
  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
  xsi:noNamespaceSchemaLocation="">
  <!-- First layer from the requests QUERY_LAYER parameter.
    [Mandatory for each parameter value.]
  -->
<?php
            $newsquare = getSelectSquare($minx, $miny, $maxx, $maxy, $width, $height, $Pixel_x, $Pixel_y, $radius, $enablestretchmap);
            $x_plus = $newsquare[0];
            $x_minus = $newsquare[1];
            $y_plus = $newsquare[2];
            $y_minus = $newsquare[3];
            $x_real = $newsquare[4];
            $y_real = $newsquare[5];

            for ($i = 0; $i < $numberofquery_valueslayer; $i++) {
                $rs2 = $database->getSelectFeatureInSquareBy($tbname, $x_plus, $x_minus, $y_plus, $y_minus, $query_layersvalues[$i]);
                $line2 = $database->getColumns($rs2);

                $data2 = $line2["style"];
                $data3 = $line2["srs"];
                $data4 = $line2["layer"];
                $data_geomtype = $line2["geomtype"];
?>
 <Layer>
   <name><?=$query_layersvalues[$i]?></name>
   <title><?=$query_layersvalues[$i]?></title>
<?php
                if ($line2 == null) {
                    // echo "<Feature>none</Feature>";
                } //if ($line2 == NULL)
                if ($line2 != null) {
                        $data5 = $line2["recid"];
                        $data6 = $line2["svgxlink"];
                        $data7 = $line2["attributes"];
?>
   <Feature fid="<?=$data5?>">
      <Properties>
        <Property>
          <title>ID</title>
          <value><?=$data5?></value>
        </Property>
        <Property>
          <title>Attributes</title>
          <!--
          Units of measure have been given their own attribute.
          -->
          <value><?=$data7?></value>
        </Property>
      </Properties>
      <!-- Links only represent related web resources so the "rel"
        attribute has been dropped, and all link element must
        have an attribute named "title".
      -->
      <link type="text/html"
        href="<?=$data6?>"
        title="<?=$query_layersvalues[$i]?> <?=$data5?>"/>
    </Feature>

<?php
                } //if ($line2 != NULL)
                echo "</Layer>";
            } // end of for ($i=0; $i < $numberofvalueslayer; $i++)
            echo "</FeatureInfoResponse>";
        } elseif (strcasecmp($info_format, "text/html") == 0) {
            header("Content-type: text/html;charset=utf-8");

            $newsquare = getSelectSquare($minx, $miny, $maxx, $maxy, $width, $height, $Pixel_x, $Pixel_y, $radius, $enablestretchmap);
            $x_plus = $newsquare[0];
            $x_minus = $newsquare[1];
            $y_plus = $newsquare[2];
            $y_minus = $newsquare[3];
            $x_real = $newsquare[4];
            $y_real = $newsquare[5];

            for ($i = 0; $i < $numberofquery_valueslayer; $i++) {
                $rs2 = $database->getSelectFeatureInSquareBy($tbname, $x_plus, $x_minus, $y_plus, $y_minus, $query_layersvalues[$i]);
                $line2 = $database->getColumns($rs2);

                $data2 = $line2["style"];
                $data3 = $line2["srs"];
                $data4 = $line2["layer"];
                $data_geomtype = $line2["geomtype"];

                echo "<style type=\"text/css\">
				tr,td {
					font-size : 95%;
				}
				table {
				    border-color: #3366cc;
					border : 0px dashed #5575B7;
				}
				</style>";
                echo "<TABLE><TR>";
                echo '<TD BGCOLOR="#8F8FEF" cospan="2"><font color="white"><b>' . $query_layersvalues[$i] . "</b></font></TD>";
                // echo '<TD BGCOLOR="#8F8FEF"><font color="white"><i>'.""."</i></font></TD>";
                echo "</TR>";

                echo "<TR><TABLE>";
                if ($line2 == null) {
                    echo "<TR>";
                    echo '<TD BGCOLOR="#D7DFE7" cospan="2">none</TD>';
                    echo "</TR>";
                    echo "</TABLE>";
                } else {
                    $data4 = $line2["id"];
                    $data5 = $line2["recid"];
                    $data6 = $line2["svgxlink"];
                    $data7 = $line2["attributes"];

                    echo "<TR>";
                    echo '<TD BGCOLOR="#D7DFE7">id:</TD>';
                    echo '<TD BGCOLOR="#D7DFE7">' . $data4 . '</TD>';
                    echo "</TR>";

                    echo "<TR>";
                    echo '<TD BGCOLOR="#D7DFE7">link:</TD>';
                    echo '<TD BGCOLOR="#D7DFE7">' . $data6 . '</TD>';
                    echo "</TR>";

                    if ($data7 != "" AND strstr($data7, '<attributes>')) {
                        // print('<xsd:sequence>');
                        $xml = simplexml_load_string(iconv('ISO-8859-1', 'UTF-8', $data7));
                        foreach ($xml->attribute as $attribute) {
                            echo "<TR>";
                            echo '<TD BGCOLOR="#D7DFE7">' . $attribute['name'] . ":</TD>";
                            echo '<TD BGCOLOR="#D7DFE7">' . $attribute . "</TD>";
                            echo "</TR>";
                        }
                    }
                    echo "</TABLE>";
                }
                echo "</TD></TR><TABLE>";
            }
        }
    }
}
$database->databaseClose();
exit();

?>