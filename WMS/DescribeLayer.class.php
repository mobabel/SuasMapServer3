<?php
/**
 * DescribeLayer Class
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
 * @version $3.0$ 2006
 * @Author leelight
 * @Contact webmaster@easywms.com
 */

require_once '../global.php';
require_once '../Models/CommonFormula.class.php';

$erroroccured = false;
$errornumber = 0;
$errorexceptionstring = "";
$database = new Database();

$database->databaseConfig($servername, $username, $password, $dbname);
$database->databaseConnect();

if ($database->databaseGetErrorMessage() != "") {
    $errornumber = -1;
    $errorexceptionstring = $database->databaseGetErrorMessage();
    $sendexceptionclass->sendexception($errornumber, $errorexceptionstring);
}
if ($errornumber == 0) {

    $layersvalues = explode(",", $layers);
    $numberofvalueslayer = count($layersvalues);

    for ($i = 0; $i < $numberofvalueslayer; $i++) {
        $rs1 = $database->getRowsByQueryableLayer($tbmetaname, $layersvalues[$i]);
        $line1 = $database->getColumns($rs1);

        $rs2 = $database->getRowsByLayer($tbmetaname, $layersvalues[$i]);
        $line2 = $database->getColumns($rs2);
        // the layer exists, but not queryable, $line1=0, $line2!=0
        if ($line2 == "" OR $layers == "") {
            $erroroccured = false;
            $errornumber = 5;
            $errorexceptionstring = "Layer " . $layersvalues[$i] . " not specified. Please use other Layer names!";
        }
    }
}
if ($errornumber != 0) {
    $sendexceptionclass->sendexception($errornumber, $errorexceptionstring);
} else {
    header("Content-type: text/xml;charset=utf-8");
    print('<?xml version="1.0" encoding="UTF-8"?>');

    ?>
<WMS_DescribeLayerResponse version="<?=$wmsversion?>">
<?php
    for ($i = 0; $i < $numberofvalueslayer; $i++) {
        $rs1 = $database->getRowsByQueryableLayer($tbmetaname, $layersvalues[$i]);
        $line1 = $database->getColumns($rs1);

        $data1 = $line1["layer"];
        $data2 = $line1["description"];

        if ($line1 == null) {

            ?>
     <LayerDescription name="<?=$layersvalues[$i]?>">
<?php
        } //if ($line2 == NULL)

        ?>
<?php
        if ($line1 != null) {

            ?>
    <LayerDescription name="<?=$data1?>" wfs="<?=$wmsmetadata["ServerHost"] . $wfsservice?>/getmapcap.php">
    <Query typeName="<?=$data2?>" />

<?php
        } //if ($line2 != NULL)

        ?>
      </LayerDescription>
<?php
    } // end of for ($i=0; $i < $numberofvalueslayer; $i++)

    ?>
</WMS_DescribeLayerResponse>
<?php

}
$database->databaseClose();
exit();

?>