<?php
/**
 * Class to write or read attribute xml
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
 * @version $3.0$ 2006
 * @Author LI Hui
 * @Contact webmaster@easywms.com
 */

function writeTextAngleAttribute($text, $angle)
{
    $attributes = "
	     <attributes><attribute name=\"Labeltext\" type=\"C\" length=\"64\" dec=\"0\">$text</attribute>
		 <attribute name=\"angle\" type=\"N\" length=\"10\" dec=\"1\">$angle</attribute></attributes>";
    return $attributes;
}

function addTextAngleAttribute($attributes, $angle)
{
    $angletext = "<attribute name=\"angle\" type=\"N\" length=\"10\" dec=\"1\">$angle</attribute>";

    $attributes = str_replace('<attribute name="angle" type="N" length="10" dec="1">0</attribute>', $angletext, $attributes);
    return $attributes;
}

function getTextAngel($attributes)
{
    $xml = simplexml_load_string($attributes);

    foreach ($xml->attribute as $attribute) {
        if($attribute['name']=="Labeltext") $txt[0] = $attribute;
        if($attribute['name']=="angle") $txt[1] = $attribute;
    }
    return $txt;
}

/*
$attri = "<attributes><attribute name=\"Labeltext\" type=\"C\" length=\"64\" dec=\"0\">test</attribute>
		 <attribute name=\"angle\" type=\"N\" length=\"10\" dec=\"1\">0</attribute></attributes>";
getTextAngel($attri);
*/

?>