<?php
class StyleParser {
    public $xmlUserLayerName;
    public $xmlUserLayerTitle;
    public $xmlMinScaleDenominator;
    public $xmlMaxScaleDenominator;
    // For point,linestring,polygon,,text
    public $xmlSize;
    // For point,polygon,text
    public $xmlFillColor;
    // For linestring,polygon
    public $xmlStrokeColor ;
    // For text
    public $xmlFont;
    // For point
    public $xmlWellKnownName;
    // For linestring, point
    public $xmlStrokeOpacity;
    // For polygon, point, image
    public $xmlFillOpacity;
    // For text
    public $xmlFontStyle;
    public $xmlFontWeight;
    // For linestring
    public $xmlLineJoin;
    public $xmlLineCap;

    public $prefix;

    public function StyleParser()
    {
        $this->xmlUserLayerName = "Default";
        $this->xmlUserLayerTitle = "Default";
        $this->xmlMinScaleDenominator = "";
        $this->xmlMaxScaleDenominator = "";
        $this->xmlSize = "1";
        $this->xmlFillColor = "-1";
        $this->xmlStrokeColor = "#000000";
        $this->xmlFont = "Arial";
        $this->xmlWellKnownName = "square";
        $this->xmlStrokeOpacity = "100";
        $this->xmlFillOpacity = "100";
        $this->xmlFontStyle = "normal";
        $this->xmlFontWeight = "normal";
        $this->xmlLineJoin = "round";
        $this->xmlLineCap = "butt";
    }

    /**
     *
     * @function createStyleNode4layer
     * @description :read the style xml document and create style node for each layer
     * @return one array which key is layername and value is style node in xml document
     */
    public function createStyleNode4layer()
    {
        $doc = new DOMDocument('1.0', 'utf-8');

        if (file_exists("../SLD/Styles/" . $this->prefix . "WmsStyles.xml")) {
            @$doc->load("../SLD/Styles/" . $this->prefix . "WmsStyles.xml");
        } else {
            $error = 'The ' . $this->prefix . 'WmsStyles.xml does not exist!';
            //exit($error);
            //create a new empty one if not exist!
            $stylefilename = tempnam("../SLD/Styles", "FOO");
            copy($stylefilename,"../SLD/Styles/" . $this->prefix . "WmsStyles.xml");
            @$doc->load("../SLD/Styles/" . $this->prefix . "WmsStyles.xml");
        }

        $xmlNameLayerNode = $doc->getElementsByTagName("NamedLayer");
        foreach ($xmlNameLayerNode as $xmlNameLayerNodes) {
            // print $xmlNameLayerNodes -> textContent."<br>";
            $xmlNameNode = $xmlNameLayerNodes->getElementsByTagName("Name");

            $sLayerName = $xmlNameNode->item(0)->textContent;

            $xmlUserLayerNode = $xmlNameLayerNodes->getElementsByTagName("UserStyle");
            // foreach ($xmlNameLayerNode as $xmlNameLayerNodes){
            // }
            $xmlUserLayerNode->item(0)->removeAttribute("xmlns");
            $aryXmlUserLayerNode[$sLayerName] = $xmlUserLayerNode->item(0);
        }
        // print_r($aryXmlUserLayerNode);
        return $aryXmlUserLayerNode;
    }

    /**
     *
     * @function getLayerStyleFromStyleNode
     * @description :read the style node of one layer and get the style
     * @return one array which key is layername and value is style node in xml document
     */
    public function getLayerStyleFromStyleNode($layername, $layertype, $aryXmlUserLayerNode)
    {
        $doc = new DOMDocument('1.0', 'utf-8');
        $xpath = new domxpath($doc);

        @$fragment = $blnfragment = $doc->importNode($aryXmlUserLayerNode[$layername], true);
        if ($blnfragment != "") {
            $doc->appendChild($fragment);
            // delete the attribute xmlns="http://www.opengis.net/sld" in UserStyle in $aryXmlUserLayerNode
            // $UserStyle = $doc->getElementsByTagName("UserStyle")-> item(0);
            // $xmlns = $UserStyle->getAttributeNode("xmlns") ;
            // $doc->removeAttribute("xmlns");
            // $doc->saveXML();
            $xmlUserLayerNameNode = $xpath->query("/UserStyle/Name");
            $this->xmlUserLayerName = $xmlUserLayerNameNode->item(0)->textContent;

            $xmlUserLayerTitleNode = $xpath->query("/UserStyle/Title");
            $this->xmlUserLayerTitle = $xmlUserLayerTitleNode->item(0)->textContent;

            $xmlMinScaleDenominatorNode = $xpath->query("/UserStyle/FeatureTypeStyle/Rule/MinScaleDenominator");
            $this->xmlMinScaleDenominator = $xmlMinScaleDenominatorNode->item(0)->textContent;

            $xmlMaxScaleDenominatorNode = $xpath->query("/UserStyle/FeatureTypeStyle/Rule/MaxScaleDenominator");
            $this->xmlMaxScaleDenominator = $xmlMaxScaleDenominatorNode->item(0)->textContent;

            switch (strtoupper($layertype)) {
                case 'POINT': {
                        $xmlSymbolizerNode = $xpath->query("/UserStyle/FeatureTypeStyle/Rule/PointSymbolizer/Graphic/*");
                        foreach ($xmlSymbolizerNode as $nodes) {
                            if ($nodes->nodeName == 'Size') {
                                $this->xmlSize = $nodes->textContent;
                                // echo $xmlSize;
                            }
                        }
                        $this->xmlWellKnownName = $xpath->query("/UserStyle/FeatureTypeStyle/Rule/PointSymbolizer/Graphic/Mark/WellKnownName")->item(0)->textContent;
                        // echo $this->xmlWellKnownName;
                        $xmlStrokeNode = $xpath->query("/UserStyle/FeatureTypeStyle/Rule/PointSymbolizer/Graphic/Mark/Stroke/*");
                        foreach ($xmlStrokeNode as $nodes) {
                            if ($nodes->nodeName == 'CssParameter') {
                                foreach ($nodes->attributes as $attribute) {
                                    if ($attribute->name == 'name' && $attribute->value == 'stroke-opacity') {
                                        $this->xmlStrokeOpacity = $nodes->textContent;
                                        // echo $xmlFillColor;
                                    }
                                }
                            }
                        }

                        $xmlFillNode = $xpath->query("/UserStyle/FeatureTypeStyle/Rule/PointSymbolizer/Graphic/Mark/Fill/*");
                        foreach ($xmlFillNode as $nodes) {
                            if ($nodes->nodeName == 'CssParameter') {
                                foreach ($nodes->attributes as $attribute) {
                                    if ($attribute->name == 'name' && $attribute->value == 'fill') {
                                        $this->xmlFillColor = $nodes->textContent;
                                        //echo $xmlFillColor;
                                    }
                                    if ($attribute->name == 'name' && $attribute->value == 'fill-opacity') {
                                        $this->xmlFillOpacity = $nodes->textContent;
                                        // echo $xmlFillColor;
                                    }
                                }
                            }
                        }
                    }
                    break;
                case 'LINESTRING': {
                        $xmlSymbolizerNodeFill = $xpath->query("/UserStyle/FeatureTypeStyle/Rule/LineSymbolizer/Fill/*");
                        foreach ($xmlSymbolizerNodeFill as $nodes) {
                            if ($nodes->nodeName == 'CssParameter') {
                                foreach ($nodes->attributes as $attribute) {
                                    if ($attribute->name == 'name' && $attribute->value == 'fill') {
                                        $this->xmlFillColor = $nodes->textContent;
                                        // echo $this->xmlFillColor;
                                    }
                                    if ($attribute->name == 'name' && $attribute->value == 'fill-opacity') {
                                        $this->xmlFillOpacity = $nodes->textContent;
                                        // echo $this->xmlFillOpacity;
                                    }
                                }
                            }
                        }

                        $xmlSymbolizerNode = $xpath->query("/UserStyle/FeatureTypeStyle/Rule/LineSymbolizer/Stroke/*"); // /CssParameter[.name='stroke']
                        foreach ($xmlSymbolizerNode as $nodes) {
                            if ($nodes->nodeName == 'CssParameter') {
                                foreach ($nodes->attributes as $attribute) {
                                    if ($attribute->name == 'name' && $attribute->value == 'stroke') {
                                        $this->xmlStrokeColor = $nodes->textContent;
                                        //$xmlStrokeColor;
                                    }
                                    if ($attribute->name == 'name' && $attribute->value == 'stroke-width') {
                                        $this->xmlSize = $nodes->textContent;
                                        // echo $xmlSize;
                                    }
                                    if ($attribute->name == 'name' && $attribute->value == 'stroke-opacity') {
                                        $this->xmlFillOpacity = $nodes->textContent;
                                        // echo $xmlSize;
                                    }
                                    if ($attribute->name == 'name' && $attribute->value == 'stroke-linejoin') {
                                        $this->xmlLineJoin = $nodes->textContent;
                                        // echo $xmlSize;
                                    }
                                    if ($attribute->name == 'name' && $attribute->value == 'stroke-linecap') {
                                        $this->xmlLineCap = $nodes->textContent;
                                        // echo $xmlSize;
                                    }
                                }
                            }
                        }
                    }
                    break;
                case 'POLYGON': {
                        $xmlSymbolizerNodeFill = $xpath->query("/UserStyle/FeatureTypeStyle/Rule/PolygonSymbolizer/Fill/*");
                        foreach ($xmlSymbolizerNodeFill as $nodes) {
                            if ($nodes->nodeName == 'CssParameter') {
                                foreach ($nodes->attributes as $attribute) {
                                    if ($attribute->name == 'name' && $attribute->value == 'fill') {
                                        $this->xmlFillColor = $nodes->textContent;
                                        // echo $xmlFillColor;
                                    }
                                    if ($attribute->name == 'name' && $attribute->value == 'fill-opacity') {
                                        $this->xmlFillOpacity = $nodes->textContent;
                                        // echo xmlFillOpacity;
                                    }
                                }
                            }
                        }

                        $xmlSymbolizerNodeStroke = $xpath->query("/UserStyle/FeatureTypeStyle/Rule/PolygonSymbolizer/Stroke/*");
                        foreach ($xmlSymbolizerNodeStroke as $nodes) {
                            if ($nodes->nodeName == 'CssParameter') {
                                foreach ($nodes->attributes as $attribute) {
                                    if ($attribute->name == 'name' && $attribute->value == 'stroke') {
                                        $this->xmlStrokeColor = $nodes->textContent;
                                        // echo $xmlStrokeColor;
                                    }
                                    if ($attribute->name == 'name' && $attribute->value == 'stroke-width') {
                                        $this->xmlSize = $nodes->textContent;
                                        // echo $xmlSize;
                                    }
                                }
                            }
                        }
                    }
                    break;
                case 'TEXT': {
                        $xmlSymbolizerNodeFill = $xpath->query("/UserStyle/FeatureTypeStyle/Rule/TextSymbolizer/Fill/*");
                        foreach ($xmlSymbolizerNodeFill as $nodes) {
                            if ($nodes->nodeName == 'CssParameter') {
                                foreach ($nodes->attributes as $attribute) {
                                    if ($attribute->name == 'name' && $attribute->value == 'fill') {
                                        $this->xmlFillColor = $nodes->textContent;
                                        // echo $xmlFillColor;
                                    }
                                }
                            }
                        }

                        $xmlSymbolizerNodeStroke = $xpath->query("/UserStyle/FeatureTypeStyle/Rule/TextSymbolizer/Font/*");
                        foreach ($xmlSymbolizerNodeStroke as $nodes) {
                            if ($nodes->nodeName == 'CssParameter') {
                                foreach ($nodes->attributes as $attribute) {
                                    if ($attribute->name == 'name' && $attribute->value == 'font-family') {
                                        $this->xmlFont = $nodes->textContent;
                                        // echo $xmlFont;
                                    }
                                    if ($attribute->name == 'name' && $attribute->value == 'font-size') {
                                        $this->xmlSize = $nodes->textContent;
                                        // echo $xmlSize;
                                    }
                                    if ($attribute->name == 'name' && $attribute->value == 'font-style') {
                                        $this->xmlFontStyle = $nodes->textContent;
                                        // echo $xmlSize;
                                    }
                                    if ($attribute->name == 'name' && $attribute->value == 'font-weight') {
                                        $this->xmlFontWeight = $nodes->textContent;
                                        // echo $xmlSize;
                                    }
                                }
                            }
                        }
                    }
                    break;
                case 'IMAGE': {
                        $xmlSymbolizerNodeOpacity = $xpath->query("/UserStyle/FeatureTypeStyle/Rule/RasterSymbolizer/*");
                        foreach ($xmlSymbolizerNodeOpacity as $nodes) {
                            if ($nodes->nodeName == 'Opacity') {
                                $this->xmlFillOpacity = $nodes->textContent;
                                // echo $this->xmlFillOpacity;
                            }
                        }
                    }
                    break;
                case 'UNKNOWN': {
                    }
                    break;
                default: {
                    }
            }
        }
    }
}
// $styleparser = new StyleParser();
// $aryXmlUserLayerNode = $styleparser->createStyleNode4layer();
// $styleparser->getLayerStyleFromStyleNode('wald','POLYGON',$aryXmlUserLayerNode);
// $styleparser->getLayerStyleFromStyleNode('LayerNotDefined','LINESTRING',$aryXmlUserLayerNode);
// $styleparser->getLayerStyleFromStyleNode('animationsubahn','POINT',$aryXmlUserLayerNode);
// $styleparser->getLayerStyleFromStyleNode('loading','TEXT',$aryXmlUserLayerNode);
// $styleparser->getLayerStyleFromStyleNode('StadtKarte200','IMAGE',$aryXmlUserLayerNode);

?>