<?php

// Tables
//DROP TABLE IF EXISTS `featuregeometry`;

$tables_sql[0] = "
CREATE TABLE `featuregeometry` (
`id` int( 11 ) NOT NULL auto_increment,
`layer` varchar( 60 ) NOT NULL default 'LayerNotDefined',
`recid` varchar( 20 ),
`geomtype` varchar( 20 ) NOT NULL default 'Unknown',
`xmin` double default NULL ,
`ymin` double default NULL ,
`xmax` double default NULL ,
`ymax` double default NULL ,
`geom` GEOMETRY ,
`svgxlink` text,
`srs` varchar( 30 ),
`attributes` text NOT NULL,
`style` varchar( 20 ) NOT NULL default 'Default',
PRIMARY KEY ( `id` ),
INDEX srs_layer (srs,layer)
) TYPE = MYISAM DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;";

//not safe, maybe create with the same name
$tables_sql[1] = "
CREATE TABLE IF NOT EXISTS `featuregeometry` (
`id` int( 11 ) NOT NULL auto_increment,
`layer` varchar( 60 ) NOT NULL default 'LayerNotDefined',
`recid` varchar( 20 ),
`geomtype` varchar( 20 ) NOT NULL default 'Unknown',
`xmin` double default NULL ,
`ymin` double default NULL ,
`xmax` double default NULL ,
`ymax` double default NULL ,
`geom` GEOMETRY ,
`svgxlink` text,
`srs` varchar( 30 ),
`attributes` text NOT NULL,
`style` varchar( 20 ) NOT NULL default 'Default',
PRIMARY KEY ( `id` ),
INDEX srs_layer (srs,layer)
) TYPE = MYISAM DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;";

$tables_sql[2] = "DROP TABLE IF EXISTS `featureclass`;";

$tables_sql[3] = "CREATE TABLE `featureclass` (
`layertype` varchar( 20 ) NOT NULL default 'Unknown',
`layer` varchar( 60 ),
`description` text,
`geomtype` varchar( 20 ) NOT NULL default 'Unknown',
`xmin` double default NULL ,
`ymin` double default NULL ,
`xmax` double default NULL ,
`ymax` double default NULL ,
`srs` varchar( 30 ),
`style` text,
`queryable` boolean NOT NULL default TRUE,
`visiable` boolean NOT NULL default TRUE,
`priority` int( 2 ),
`elevation` double default NULL ,
INDEX srs_layer(srs,layer)
) TYPE = MYISAM DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;";

?>