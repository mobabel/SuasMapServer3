/*
* Part of the codes come from Geomedia WebMap
*/
var objFocusedInput;

//dynamically write to Symbolization Div
function writeDivStyleDialog (sLayerName) {

	var sLayerType = document.frmPost[sLayerName + 'layerType'].value;
        var sLayerTypeUpper = sLayerType.toUpperCase();

	var sContents;
	sContents = '<table cellspacing =0 cellpading =0 border=0>' +
				'<tr><td colspan=2>Display Range between Scales</td></tr>' +
				'<tr><td  colspan=2><img src="../img/range1.jpg" border="0" name="range1" onClick="getRange (this);">' +
				'<img src="../img/range2.jpg" border="0" name="range2"  onClick="getRange (this);">' +
				'<img src="../img/range3.jpg" border="0" name="range3"  onClick="getRange (this);">' +
				'<img src="../img/range4.jpg" border="0" name="range4" onClick="getRange (this);">' +
				'<img src="../img/range5.jpg" border="0" name="range5" onClick="getRange (this);">' +
				'<img src="../img/range6.jpg" border="0" name="range6" onClick="getRange (this);">' +
				'<img src="../img/range7.jpg" border="0" name="range7" onClick="getRange (this);">' +
				'<img src="../img/range8.jpg" border="0" name="range8" onClick="getRange (this);"></td></tr>';
	sContents = sContents + '<tr><td>Minimum: </td><td>1: <input type="text" size="12" name ="txtMinRange" class="smallInput" value="' +
	                document.frmPost[sLayerName + 'txtMinRange'].value  + '" onFocus="cancelOnFocus (this);" onChange ="handleOnChange (this);"> </td></tr>' ;
	sContents = sContents + '<tr><td>Maximum: </td><td>1: <input type="text" size="12" name ="txtMaxRange" class="smallInput" value="' +
					document.frmPost[sLayerName + 'txtMaxRange'].value  + '" onFocus="cancelOnFocus (this);" onChange ="handleOnChange (this);"> </td></tr>' ;
	sContents = sContents + '<tr><td colspan=2 width=100% align=center><hr></td><tr>';
	if ( sLayerTypeUpper == 'POLYGON' ) {

		sContents = sContents + '<tr><td colspan=2>' + sLayerName + ' (' + sLayerType + ') ';
		sContents = sContents + '<input type=hidden name ="txtLayerName" class="smallInput" value="' + sLayerName + '"></td></tr>';
		sContents = sContents + '<tr><td>Style Name: </td><td><input type="text" name ="txtStyleName" class="smallInput" value="' + document.frmPost[sLayerName + 'txtStyleName'].value +'" onKeyUp ="handleOnChange (this);" onFocus="cancelOnFocus (this);" size ="10"></td></tr>';
		sContents = sContents + '<tr><td>Style Title : </td><td><input type="text" name ="txtStyleTitle" class="smallInput" value="' + document.frmPost[sLayerName + 'txtStyleTitle'].value +'" onKeyUp ="handleOnChange (this);" onFocus="cancelOnFocus (this);" size ="10"></td></tr>';
		sContents = sContents + '<tr><td>Fill </td><td><div id="divFillColor" class="divFillColorStyle">&nbsp;<div></td></tr>';
		sContents = sContents + '<tr><td>Color <image src="../img/help.png"  border="0" onmouseover="tooltip(\'Fill Color\',\'Description:\',\'For Polygons, if you want to set the polygon be displayed as transparent, please leave the default value of the Fill Color to -1 (transparent).<br>Please click the right Textarea to active the color selecting status.<br>\');" onmouseout="exit();">: </td><td><input type ="text" name="txtFill" class="smallInput" value ="'
		sContents = sContents + document.frmPost[sLayerName + 'txtFill'].value +  '" size="6" onChange ="handleOnChange (this);" onFocus="handleOnFocus (this);" MAXLENGTH =7></td></tr>';
		sContents = sContents + '<tr><td>Stroke </td><td><div id="divStrokeColor" class="divStrokeColorStyle"><div></td></tr>';
		sContents = sContents + '<tr><td>Color: </td><td><input type ="text" name="txtStroke" class="smallInput" value ="' + document.frmPost[sLayerName + 'txtStroke'].value
		sContents = sContents + '" size="6" onChange ="handleOnChange (this);" onFocus="handleOnFocus (this);" MAXLENGTH =7></td></tr>';
		sContents = sContents + '<tr><td>Width: </td><td><input type ="text" name="txtStrokeWidth" class="smallInput" value ="' + document.frmPost[sLayerName + 'txtStrokeWidth'].value
		sContents = sContents + '" size="6"  onKeyPress="return handleKeyPress (event);" onKeyUp ="handleOnChange (this);" onFocus="cancelOnFocus (this);" MAXLENGTH =20></td></tr>';
		sContents = sContents + '<tr><td colspan=2 width=100% align=center><hr></td><tr>';
		sContents = sContents + '<tr><td>Fill Opacity: </td><td><input type ="text" name="txtFillOpacity" class="smallInput" value ="' + document.frmPost[sLayerName + 'txtFillOpacity'].value
		sContents = sContents + '" size="3" onKeyPress="return handleKeyPress (event);" onKeyUp ="handleOnChange (this);" onFocus="cancelOnFocus (this);" MAXLENGTH =3></td></tr>';

	}
	else if ( sLayerTypeUpper == 'LINESTRING' ) {
	    var txtStrokeLinejoin = document.frmPost[sLayerName + 'txtStrokeLinejoin'].value;
	    var txtStrokeLinecap = document.frmPost[sLayerName + 'txtStrokeLinecap'].value;
		sContents = sContents + '<tr><td colspan =2>' + sLayerName + ' (' + sLayerType + ')';
		sContents = sContents + '<input type=hidden name ="txtLayerName" class="smallInput" value="' + sLayerName + '"></td></tr>';
		sContents = sContents + '<tr><td>Style Name: </td><td><input type="text" name ="txtStyleName" class="smallInput" value="' + document.frmPost[sLayerName + 'txtStyleName'].value +'" onKeyUp ="handleOnChange (this);" onFocus="cancelOnFocus (this);" size ="10"></td></tr>';
		sContents = sContents + '<tr><td>Style Title : </td><td><input type="text" name ="txtStyleTitle" class="smallInput" value="' + document.frmPost[sLayerName + 'txtStyleTitle'].value +'" onKeyUp ="handleOnChange (this);" onFocus="cancelOnFocus (this);" size ="10"></td></tr>';
		sContents = sContents + '<tr><td>Stroke </td><td><div id="divStrokeColor" class="divStrokeColorStyle"><div></td></tr>';
		sContents = sContents + '<tr><td >Color:</td><td><input type ="text" name="txtStroke" class="smallInput" value ="'
		sContents = sContents + document.frmPost[sLayerName + 'txtStroke'].value + '" size="6" onChange ="handleOnChange (this);" onFocus="handleOnFocus (this);" MAXLENGTH =7></td></tr>';
		sContents = sContents + '<tr><td>Width:</td><td><input type ="text" name="txtStrokeWidth" class="smallInput" value ="' + document.frmPost[sLayerName + 'txtStrokeWidth'].value
		sContents = sContents + '" size="6" onKeyPress="return handleKeyPress (event);" onKeyUp ="handleOnChange (this);"  onFocus="cancelOnFocus (this);" MAXLENGTH =20></td></tr>';

		sContents = sContents + '<tr><td colspan=2 width=100% align=center><hr></td><tr>';
		sContents = sContents + '<tr><td>Stroke Opacity : </td><td><input type ="text" name="txtStrokeOpacity" class="smallInput" value ="' + document.frmPost[sLayerName + 'txtStrokeOpacity'].value
		sContents = sContents + '" size="3" onKeyPress="return handleKeyPress (event);" onKeyUp ="handleOnChange (this);" onFocus="cancelOnFocus (this);" MAXLENGTH =3></td></tr>';
		sContents = sContents + '<tr><td>Stroke Linejoin: </td><td><select name="txtStrokeLinejoin" class="button4"  onFocus="handleOnChange(this);">';
		if (txtStrokeLinejoin == 'miter')
			sContents = sContents + '<option value ="miter" selected>miter</option>';
		else
			sContents = sContents + '<option value ="miter">miter</option>';
		if (txtStrokeLinejoin == 'round')
			sContents = sContents + '<option value ="round" selected>round</option>';
		else
			sContents = sContents + '<option value ="round">round</option>';
		if (txtStrokeLinejoin == 'bevel' )
			sContents = sContents + '<option selected value ="bevel">bevel</option></select></td></tr>';
		else
			sContents = sContents + '<option value ="bevel">bevel</option></select></td></tr>';
		sContents = sContents + '<tr><td>Stroke Linecap: </td><td><select name="txtStrokeLinecap" class="button4"  onFocus="handleOnChange(this);">';
		if (txtStrokeLinecap == 'butt')
			sContents = sContents + '<option value ="butt" selected>butt</option>';
		else
			sContents = sContents + '<option value ="butt">butt</option>';
		if (txtStrokeLinecap == 'round')
			sContents = sContents + '<option value ="round" selected>round</option>';
		else
			sContents = sContents + '<option value ="round">round</option>';
		if (txtStrokeLinecap == 'square' )
			sContents = sContents + '<option selected value ="square">square</option></select></td></tr>';
		else
			sContents = sContents + '<option value ="square">square</option></select></td></tr>';

		sContents = sContents + '<tr><td colspan=2 width=100% align=center><hr></td><tr>';
        sContents = sContents + '<tr><td>Fill </td><td><div id="divFillColor" class="divFillColorStyle">&nbsp;<div></td></tr>';
		sContents = sContents + '<tr><td>Color <image src="../img/warningwhite.png"  border="0" onmouseover="warningtip(\'Fill Color\',\'Description:\',\'In most case, <font class=error>DO NOT</font> fill LineString Geometry, please leave the default value of the Fill Color to -1 (transparent). Unless you know what you are doing, otherwise the outputted map will be displayed out of your thinking.<br>Please click the right Textarea to active the color selecting status.<br>\');" onmouseout="exitwarning();">: </td><td><input type ="text" name="txtFill" class="smallInput" value ="'
		sContents = sContents + document.frmPost[sLayerName + 'txtFill'].value +  '" size="6" onChange ="handleOnChange (this);" onFocus="handleOnFocus (this);" MAXLENGTH =7></td></tr>';
		sContents = sContents + '<tr><td>Fill Opacity: </td><td><input type ="text" name="txtFillOpacity" class="smallInput" value ="' + document.frmPost[sLayerName + 'txtFillOpacity'].value
		sContents = sContents + '" size="3" onKeyPress="return handleKeyPress (event);" onKeyUp ="handleOnChange (this);" onFocus="cancelOnFocus (this);" MAXLENGTH =3></td></tr>';

	}
	else if( sLayerTypeUpper == 'POINT' ) {
	    var sWellknownName = document.frmPost[sLayerName + 'txtWellknownName'].value;
		sContents = sContents + '<tr><td>' + sLayerName + ' (' + sLayerType + ')';
		sContents = sContents + '<input type=hidden name ="txtLayerName" class="smallInput" value="' + sLayerName + '"></td><td><div id="divPointColor" class="divPointColorStyle"></div><td></tr>';
		sContents = sContents + '<tr><td>Style Name: </td><td><input type="text" name ="txtStyleName" class="smallInput" value="' + document.frmPost[sLayerName + 'txtStyleName'].value +'" onKeyUp ="handleOnChange (this);" onFocus="cancelOnFocus (this);" size ="10"></td></tr>';
		sContents = sContents + '<tr><td>Style Title : </td><td><input type="text" name ="txtStyleTitle" class="smallInput" value="' + document.frmPost[sLayerName + 'txtStyleTitle'].value +'" onKeyUp ="handleOnChange (this);" onFocus="cancelOnFocus (this);" size ="10"></td></tr>';
		sContents = sContents + '<tr><td>Mark Shape: </td><td><select name="txtWellknownName" class="button4"  onFocus="handleOnChange(this);">';
		if (sWellknownName == 'square')
			sContents = sContents + '<option value ="square" selected>square</option>';
		else
			sContents = sContents + '<option value ="square">square</option>';
		if (sWellknownName == 'circle')
			sContents = sContents + '<option value ="circle" selected>circle</option>';
		else
			sContents = sContents + '<option value ="circle">circle</option>';
		if (sWellknownName == 'triangle')
			sContents = sContents + '<option value ="triangle" selected>triangle</option>';
		else
			sContents = sContents + '<option value ="triangle">triangle</option>';
		if (sWellknownName == 'star')
			sContents = sContents + '<option value ="star" selected>star</option>';
		else
			sContents = sContents + '<option value ="star">star</option>';
		if (sWellknownName == 'cross')
			sContents = sContents + '<option value ="cross" selected>cross</option>';
		else
			sContents = sContents + '<option value ="cross">cross</option>';
		if (sWellknownName == 'x' )
			sContents = sContents + '<option selected value ="x">x</option></select></td></tr>';
		else
			sContents = sContents + '<option value ="x">x</option></select></td></tr>';

		sContents = sContents + '<tr><td>Fill Color: </td><td><input type ="text" name="txtFill" class="smallInput" value ="' + document.frmPost[sLayerName + 'txtFill'].value
		sContents = sContents + '" size="6" onChange ="handleOnChange (this);" onFocus="handleOnFocus (this);" MAXLENGTH =7></td></tr>';
		sContents = sContents + '<tr><td>Size: </td><td><input type ="text" name="txtSize" class="smallInput" value ="' + document.frmPost[sLayerName + 'txtSize'].value
		sContents = sContents + '" size="6" onKeyPress="return handleKeyPress (event);" onKeyUp ="handleOnChange (this);"  onFocus="cancelOnFocus (this);" MAXLENGTH =7></td></tr>';
        sContents = sContents + '<tr><td colspan=2 width=100% align=center><hr></td><tr>';
        sContents = sContents + '<tr><td>Fill Opacity : </td><td><input type ="text" name="txtFillOpacity" class="smallInput" value ="' + document.frmPost[sLayerName + 'txtFillOpacity'].value
		sContents = sContents + '" size="3" onKeyPress="return handleKeyPress (event);" onKeyUp ="handleOnChange (this);" onFocus="cancelOnFocus (this);" MAXLENGTH =3></td></tr>';
		sContents = sContents + '<tr><td>Stroke Opacity : </td><td><input type ="text" name="txtStrokeOpacity" class="smallInput" value ="' + document.frmPost[sLayerName + 'txtStrokeOpacity'].value
		sContents = sContents + '" size="3" onKeyPress="return handleKeyPress (event);" onKeyUp ="handleOnChange (this);" onFocus="cancelOnFocus (this);" MAXLENGTH =3></td></tr>';

	}
	else if ( sLayerTypeUpper == 'TEXT' ) {
     	var txtFontStyle = document.frmPost[sLayerName + 'txtFontStyle'].value;
	    var txtFontWeight = document.frmPost[sLayerName + 'txtFontWeight'].value;
		var sFontFamily = document.frmPost[sLayerName + 'sltFontFamily'].value;
		sContents = sContents + '<tr><td>' + sLayerName + ' (' + sLayerType + ')';
		sContents = sContents + '<input type=hidden name ="txtLayerName" class="smallInput" value="' + sLayerName + '"></td><td><div id="divFont" class="divFontStyle">EasyWMS</div></td></tr>';
		sContents = sContents + '<tr><td>Style Name: </td><td><input type="text" name ="txtStyleName" class="smallInput" value="' + document.frmPost[sLayerName + 'txtStyleName'].value +'" onKeyUp ="handleOnChange (this);" onFocus="cancelOnFocus (this);" size ="10"></td></tr>';
		sContents = sContents + '<tr><td>Style Title : </td><td><input type="text" name ="txtStyleTitle" class="smallInput" value="' + document.frmPost[sLayerName + 'txtStyleTitle'].value +'" onKeyUp ="handleOnChange (this);" onFocus="cancelOnFocus (this);" size ="10"></td></tr>';
		sContents = sContents + '<tr><td>Label: </td><td><input type ="text" name="txtLabel" class="smallInput" value ="' + document.frmPost[sLayerName + 'txtLabel'].value+ '" size="12" READONLY onFocus="cancelOnFocus (this);" ></td></tr>';
		sContents = sContents + '<tr><td>Font Family: </td><td><select name ="sltFontFamily" class="button4" onchange="handleOnChange(this);">'
		//get the FontFamily selection from Form frmPost
		if (sFontFamily == 'Arial')
			sContents = sContents + '<option value ="Arial" selected>Arial</option>';
		else
			sContents = sContents + '<option value ="Arial">Arial</option>';
		if (sFontFamily == 'Comic Sans MS' )
			sContents = sContents + '<option selected value ="Comic Sans MS">Comic Sans MS</option>';
		else
			sContents = sContents + '<option value ="Comic Sans MS">Comic Sans MS</option>';
		if (sFontFamily == 'Courier' )
			sContents = sContents + '<option selected value ="Courier">Courier</option>';
		else
			sContents = sContents + '<option value ="Courier">Courier</option>';
		if (sFontFamily == 'Courier New' )
			sContents = sContents + '<option selected value ="Courier New">Courier New</option>';
		else
			sContents = sContents + '<option value ="Courier New">Courier New</option>';
		if (sFontFamily == 'Georgia' )
			sContents = sContents + '<option selected value ="Georgia">Georgia</option>';
		else
			sContents = sContents + '<option value ="Georgia">Georgia</option>';
		if (sFontFamily == 'Helvetica' )
			sContents = sContents + '<option selected value ="Helvetica">Helvetica</option>';
		else
			sContents = sContents + '<option value ="Helvetica">Helvetica</option>';
		if (sFontFamily == 'Impact' )
			sContents = sContents + '<option selected value ="Impact">Impact</option>';
		else
			sContents = sContents + '<option value ="Impact">Impact</option>';
		if (sFontFamily == 'Palatino' )
			sContents = sContents + '<option selected value ="Palatino">Palatino</option>';
		else
			sContents = sContents + '<option value ="Palatino">Palatino</option>';
		if (sFontFamily == 'Times New Roman' )
			sContents = sContents + '<option selected value ="Times New Roman">Times New Roman</option>';
		else
			sContents = sContents + '<option value ="Times New Roman">Times New Roman</option>';
		if (sFontFamily == 'Trebuchet MS' )
			sContents = sContents + '<option selected value ="Trebuchet MS">Trebuchet MS</option>';
		else
			sContents = sContents + '<option value ="Trebuchet MS">Trebuchet MS</option>';
		if (sFontFamily == 'Verdana' )
			sContents = sContents + '<option selected value="Verdana">Verdana</option></select></td></tr>';
		else
			sContents = sContents + '<option value="Verdana">Verdana</option></select></td></tr>';

		sContents = sContents + '<tr><td>Font Color: </td><td><input type ="text" name="txtFontColor" class="smallInput" value ="' + document.frmPost[sLayerName + 'txtFontColor'].value
		sContents = sContents + '" size="6" onChange ="handleOnChange (this);" onFocus="handleOnFocus (this);" MAXLENGTH =7></td></tr>';
		sContents = sContents + '<tr><td>Font Size: </td><td><input type ="text" name="txtFontSize" class="smallInput" value ="' + document.frmPost[sLayerName + 'txtFontSize'].value
		sContents = sContents + '" size="6" onKeyPress="return handleKeyPress (event);" onKeyUp ="handleOnChange (this);"  onFocus="cancelOnFocus (this);" MAXLENGTH =7></td></tr>';
        sContents = sContents + '<tr><td colspan=2 width=100% align=center><hr></td><tr>';
        sContents = sContents + '<tr><td>Font Style: </td><td><select name="txtFontStyle" class="button4"  onFocus="handleOnChange(this);">';
		if (txtFontStyle == 'normal')
			sContents = sContents + '<option value ="normal" selected>normal</option>';
		else
			sContents = sContents + '<option value ="normal">normal</option>';
		if (txtFontStyle == 'italic')
			sContents = sContents + '<option value ="italic" selected>italic</option>';
		else
			sContents = sContents + '<option value ="italic">italic</option>';
		if (txtFontStyle == 'oblique' )
			sContents = sContents + '<option selected value ="oblique">oblique</option></select></td></tr>';
		else
			sContents = sContents + '<option value ="oblique">oblique</option></select></td></tr>';
		sContents = sContents + '<tr><td>Font Weight: </td><td><select name="txtFontWeight" class="button4"  onFocus="handleOnChange(this);">';
		if (txtFontWeight == 'normal')
			sContents = sContents + '<option value ="normal" selected>normal</option>';
		else
			sContents = sContents + '<option value ="normal">normal</option>';
		if (txtFontWeight == 'bold' )
			sContents = sContents + '<option selected value ="bold">bold</option></select></td></tr>';
		else
			sContents = sContents + '<option value ="bold">bold</option></select></td></tr>';

	}
	else if ( sLayerTypeUpper == 'IMAGE' ){
		sContents = sContents + '<tr><td colspan=2>' + sLayerName + ' (' + sLayerType + ') No Symbolization for Image<input type=hidden name ="txtLayerName" value="' + sLayerName + '"></td></tr>';
		sContents = sContents + '<tr><td>Style Name: </td><td><input type="text" name ="txtStyleName" class="smallInput" value="' + document.frmPost[sLayerName + 'txtStyleName'].value +'" onKeyUp ="handleOnChange (this);" onFocus="cancelOnFocus (this);" size ="10"></td></tr>';
		sContents = sContents + '<tr><td>Style Title: </td><td><input type="text" name ="txtStyleTitle" class="smallInput" value="' + document.frmPost[sLayerName + 'txtStyleTitle'].value +'" onKeyUp ="handleOnChange (this);" onFocus="cancelOnFocus (this);" size ="10"></td></tr>';
        sContents = sContents + '<tr><td colspan=2 width=100% align=center><hr></td><tr>';
        sContents = sContents + '<tr><td>Image Opacity : </td><td><input type ="text" name="txtOpacity" class="smallInput" value ="' + document.frmPost[sLayerName + 'txtOpacity'].value
		sContents = sContents + '" size="3" onKeyPress="return handleKeyPress (event);" onKeyUp ="handleOnChange (this);" onFocus="cancelOnFocus (this);" MAXLENGTH =3></td></tr>';

	}

	else {
		sContents = sContents + '<tr><td colspan=2>' + sLayerName + ' (' + sLayerType + ') the Feature Type is unknown<input type=hidden name ="txtLayerName" value="' + sLayerName + '"></td></tr>';
		sContents = sContents + '<tr><td>Style Name: </td><td><input type="text" name ="txtStyleName" class="smallInput" value="' + document.frmPost[sLayerName + 'txtStyleName'].value +'" onKeyUp ="handleOnChange (this);" onFocus="cancelOnFocus (this);" size ="10"></td></tr>';
		sContents = sContents + '<tr><td>Style Title: </td><td><input type="text" name ="txtStyleTitle" class="smallInput" value="' + document.frmPost[sLayerName + 'txtStyleTitle'].value +'" onKeyUp ="handleOnChange (this);" onFocus="cancelOnFocus (this);" size ="10"></td></tr>';
	    sContents = sContents + '<tr><td colspan=2 width=100% align=center><hr></td><tr>';
	}

	sContents = sContents + '</table>';
	document.getElementById ('divStyleDialog').innerHTML = sContents;


	if (document.all ["divFillColor"]) {
		if (document.frmPost[sLayerName + 'txtFill'].value == '-1')
			document.all ["divFillColor"].style.backgroundColor = '#ffffff';
		else
			document.all ["divFillColor"].style.backgroundColor = document.frmPost[sLayerName + 'txtFill'].value;

		}

	if (document.all ["divStrokeColor"]) {
		document.all ["divStrokeColor"].style.backgroundColor = document.frmPost[sLayerName + 'txtStroke'].value;
		document.all ["divStrokeColor"].style.height = document.frmPost[sLayerName + 'txtStrokeWidth'].value;
		}

	if (document.all ["divPointColor"]) {
		if (document.frmPost[sLayerName + 'txtFill'].value == '-1')
			document.all ["divPointColor"].style.backgroundColor = '#ffffff';
		else
			document.all ["divPointColor"].style.backgroundColor = document.frmPost[sLayerName + 'txtFill'].value;

		document.all ["divPointColor"].style.height = document.frmPost[sLayerName + 'txtSize'].value;
		document.all ["divPointColor"].style.width = document.frmPost[sLayerName + 'txtSize'].value;

		}
	if (document.all ["divFont"]) {
			document.all ["divFont"].style.color = document.frmPost[sLayerName + 'txtFontColor'].value;
			document.all ["divFont"].style.fontFamily = document.frmPost[sLayerName + 'sltFontFamily'].value;
			document.all ["divFont"].style.fontSize = document.frmPost[sLayerName + 'txtFontSize'].value;
		}
	objFocusedInput = null;
}

//format the number based on the current user's locale
function getLocaleString (intNum) {

     var oNumber = new Number(intNum);
     return oNumber.toLocaleString();
}

//user click the predefined range image to enter the predefined range into range text box
function getRange (input) {
	//get the layer's name
	var sLayerName = document.frmStyles.txtLayerName.value;

	if (input.name == 'range1') { //write the range value into form frmPost
		document.frmPost[sLayerName + 'txtMinRange'].value = getLocaleString (80000000);
		//according to calculation, this should be at least 198,784,805,instead of 120,000,000
		document.frmPost[sLayerName + 'txtMaxRange'].value = getLocaleString (200000000);
		document.frmStyles.txtMinRange.value = getLocaleString (80000000) ;
		document.frmStyles.txtMaxRange.value = getLocaleString (200000000);
		}
	else if (input.name == 'range2') {

		document.frmPost[sLayerName + 'txtMinRange'].value = getLocaleString (30000000) ;
		document.frmPost[sLayerName + 'txtMaxRange'].value = getLocaleString (110000000) ;
		document.frmStyles.txtMinRange.value = getLocaleString (30000000);
		document.frmStyles.txtMaxRange.value = getLocaleString (110000000);
		}
	else if (input.name == 'range3') {
		document.frmPost[sLayerName + 'txtMinRange'].value =  getLocaleString (10000000);
		document.frmPost[sLayerName + 'txtMaxRange'].value = getLocaleString (45000000);
		document.frmStyles.txtMinRange.value =  getLocaleString (10000000);
		document.frmStyles.txtMaxRange.value = getLocaleString (45000000);
		}
	else if (input.name == 'range4') {
		document.frmPost[sLayerName + 'txtMinRange'].value = getLocaleString (900000) ;
		document.frmPost[sLayerName + 'txtMaxRange'].value = getLocaleString (16000000);
		document.frmStyles.txtMinRange.value = getLocaleString (900000);
		document.frmStyles.txtMaxRange.value = getLocaleString (16000000);
		}
	else if (input.name == 'range5') {
		document.frmPost[sLayerName + 'txtMinRange'].value = getLocaleString (200000);
		document.frmPost[sLayerName + 'txtMaxRange'].value = getLocaleString (2000000);
		document.frmStyles.txtMinRange.value = getLocaleString (200000);
		document.frmStyles.txtMaxRange.value = getLocaleString (2000000);
		}
	else if (input.name == 'range6') {
		document.frmPost[sLayerName + 'txtMinRange'].value = getLocaleString (5000);
		document.frmPost[sLayerName + 'txtMaxRange'].value = getLocaleString (400000);
		document.frmStyles.txtMinRange.value =  getLocaleString (5000);
		document.frmStyles.txtMaxRange.value = getLocaleString (400000);
		}
	else if (input.name == 'range7') {
		document.frmPost[sLayerName + 'txtMinRange'].value = getLocaleString (100);
		document.frmPost[sLayerName + 'txtMaxRange'].value = getLocaleString (15000);
		document.frmStyles.txtMinRange.value = getLocaleString (100);
		document.frmStyles.txtMaxRange.value = getLocaleString (15000);
		}
	else if (input.name == 'range8') {
		document.frmPost[sLayerName + 'txtMinRange'].value = getLocaleString (1);
		document.frmPost[sLayerName + 'txtMaxRange'].value = getLocaleString (300);
		document.frmStyles.txtMinRange.value = getLocaleString (1);
		document.frmStyles.txtMaxRange.value = getLocaleString (300);
		}
}

//user click ColorPicker to intrigue this function
function getColor (sColor) {
	//objFocusedInput is the global variable
	if ( objFocusedInput != null) {
		objFocusedInput.value = sColor;
		//the input txtLayerName is dynamically updated
		sLayerName = document.frmStyles.txtLayerName.value;
		//save into hidden form frmPost too
		document.frmPost[sLayerName + '' + objFocusedInput.name].value = sColor;

		//draw the fill color
		if (document.all ["divFillColor"] && objFocusedInput.name == 'txtFill') {
		document.all ["divFillColor"].style.backgroundColor = sColor;
		}
		//draw the stroke color
		if (document.all ["divStrokeColor"] && objFocusedInput.name == 'txtStroke') {
		document.all ["divStrokeColor"].style.backgroundColor = sColor;
		}

		//draw the point fill color
		if (document.all ["divPointColor"] && objFocusedInput.name == 'txtFill') {
		document.all ["divPointColor"].style.backgroundColor = sColor;
		}
		//draw the Font Color
		if (document.all ["divFont"] && objFocusedInput.name == 'txtFontColor') {
		document.all ["divFont"].style.color = sColor;

		}

		}
}

//validate user's input for color value
function validateColorValue (sLayerName,input) {
			HexValue = input.value;

			if (HexValue.length !=7 ) {
				showErrorMessage ('invalid Hex Value' + HexValue);
				input.value = document.frmPost[sLayerName + '' + input.name].value;
				return false;
			}
			else if ( HexValue.substring (0, 1) != '#') {
				showErrorMessage ('invalid Hex Value' + HexValue);
				input.value = document.frmPost[sLayerName + '' + input.name].value;
				return false;
			}
			else  {

				for ( i =1; i < HexValue.length ; ++i) {
					if(isNaN(parseInt(HexValue.substring (i,i+1),16))) {
						showErrorMessage('invalid Hex Value' + HexValue);
						input.value = document.frmPost[sLayerName + '' + input.name].value;
						return false;
					}
				}

						return true;
			}

}

//validate user's input for Range value
function validateRangeValue (sLayerName,input) {

	//var inputValue = input.value;
	//var GoodChars = "0123456789,.";
	//var i = 0;
	//for (i =0; i <= inputValue.length -1; i++) {
	//	if (GoodChars.indexOf(inputValue.charAt(i)) == -1) {
	//		alert (inputValue.charAt(i) + ' ' + i);
	//		alert("Please input a valid number!")
	///		//roll back
	//		input.value = document.frmPost[sLayerName + '' + input.name].value;
	//		return false;
	//	} // End if statement
	//} // End for loop

	//return true;
	if ( input.value =="" ||  parseFloat  (input.value) ) {
		return true;

		}
	else {
		showErrorMessage("Please input a valid number!");
		//roll back
		input.value = document.frmPost[sLayerName + '' + input.name].value;
		return false;

		}


}

//validate user's input for opcity value
function validateOpcityValue (sLayerName,input) {
    var obj = eval(input);
	if (input.value >100 || input.value <0 || input.value=="") {
	    showErrorMessage("Please input a valid Opcity Value between 0 and 100!");
		//roll back
		input.value = document.frmPost[sLayerName + '' + input.name].value;
		return false;
		}
	else {
		return true;

		}
}

//this is used by function handleKeyPress to get key pressed
function getkey(e){
	if (window.event)
		return window.event.keyCode;
	else if (e)
		return e.which;
	else
		return null;
}

//this function only allow user to enter .1234567890
function handleKeyPress(e){
	var key, keychar;
	var goods = '.1234567890';
	key = getkey(e);
	if (key == null) return true;

	// get character
	keychar = String.fromCharCode(key);
	keychar = keychar.toLowerCase();
	goods = goods.toLowerCase();

	// check goodkeys
	if (goods.indexOf(keychar) != -1) {
		return true;
	}

	// control keys
	if ( key==null || key==0 || key==8 || key==9 || key==13 || key==27 )
	return true;

	// else return false
	return false;
	}

//when the text input for Color purpose is focused,
//set global variable objFocusedInput to the text input
function handleOnFocus (input) {
	objFocusedInput = input;

}

//when the text input for other purpose, like width,  is focused,
//set global variable objFocusedInput to null
function cancelOnFocus (input) {

	objFocusedInput = null;
	//highlight all the text in the text input,
	//easy for user to write new value
	input.select ();
}


//used to handle user's input by keyboard
function handleOnChange (input) {
		sLayerName = document.frmStyles.txtLayerName.value;

		//call function validateColorValue to validate input first
		if ( input.name == 'txtFill' && input.value == '-1' ) {
			document.frmPost[sLayerName + '' + input.name].value = input.value;
			if (document.all ["divFillColor"])
				document.all ["divFillColor"].style.backgroundColor = '#ffffff';
			else if (document.all ["divPointColor"])
				document.all ["divPointColor"].style.backgroundColor = '#ffffff';
			}

		else if ( input.name == 'txtFill' && validateColorValue (sLayerName,input) ) {
			document.frmPost[sLayerName + '' + input.name].value = input.value;
			if (document.all ["divFillColor"])
				document.all ["divFillColor"].style.backgroundColor = input.value;
			else if (document.all ["divPointColor"])
				document.all ["divPointColor"].style.backgroundColor = input.value;
			}


		if ( input.name == 'txtStroke' && validateColorValue (sLayerName,input) ) {
			document.frmPost[sLayerName + '' + input.name].value = input.value;
			document.all ["divStrokeColor"].style.backgroundColor = input.value;
			}


		if ( input.name == 'txtStrokeWidth' && (input.value != '' && input.value != '0' )) {
		    if(input.value.substring(0,1) == '.'){
		        input.value = '0.';
		    }
			document.frmPost[sLayerName + '' + input.name].value = input.value;
			document.all ["divStrokeColor"].style.height = input.value;
			}

		if ( input.name == 'txtStrokeLinejoin' ) {
			document.frmPost[sLayerName + 'txtStrokeLinejoin'].value = input.options [input.selectedIndex].value;
			}

		if ( input.name == 'txtStrokeLinecap' ) {
			document.frmPost[sLayerName + 'txtStrokeLinecap'].value = input.options [input.selectedIndex].value;
			}

		if ( input.name == 'txtSize' && (input.value != '' && input.value != '0') ) {
		    if(input.value.substring(0,1) == '.'){
		        input.value = '0.';
		    }
			document.frmPost[sLayerName + '' + input.name].value = input.value;
			document.all ["divPointColor"].style.height = input.value;
			document.all ["divPointColor"].style.width = input.value;
		}
		if (input.name == "txtStyleName" || input.name == "txtStyleTitle") {
			document.frmPost[sLayerName + '' + input.name].value = input.value;
		}
		if (input.name == "sltFontFamily") {
			document.frmPost[sLayerName + 'sltFontFamily'].value = input.options [input.selectedIndex].value;
			document.all ["divFont"].style.fontFamily = input.value;
		}
		if (input.name == "txtWellknownName") {
			document.frmPost[sLayerName + 'txtWellknownName'].value = input.options [input.selectedIndex].value;
		}

		if ( input.name == 'txtFontColor' && validateColorValue (sLayerName,input) ) {
			document.frmPost[sLayerName + '' + input.name].value = input.value;
			document.all ["divFont"].style.color = input.value;
			}
		if ( input.name == 'txtFontSize' && ( input.value != ''&& input.value != '0')) {
		    if(input.value.substring(0,1) == '.'){
		        input.value = '0.';
		    }
			document.frmPost[sLayerName + '' + input.name].value = input.value;
			document.all ["divFont"].style.fontSize = input.value;
			}

		if ( input.name == 'txtMinRange'&&  validateRangeValue (sLayerName,input) ) {
			document.frmPost[sLayerName + '' + input.name].value = input.value;
			}
		if ( input.name == 'txtMaxRange'&& validateRangeValue (sLayerName,input) ) {
			document.frmPost[sLayerName + '' + input.name].value = input.value;
			}

		if ( input.name == 'txtFillOpacity' &&  validateOpcityValue(sLayerName,input)) {
			document.frmPost[sLayerName + '' + input.name].value = input.value;
			}

        if ( input.name == 'txtStrokeOpacity'&&  validateOpcityValue(sLayerName,input)) {
			document.frmPost[sLayerName + '' + input.name].value = input.value;
			}

		if ( input.name == 'txtOpacity'&& validateOpcityValue(sLayerName,input)) {
			document.frmPost[sLayerName + '' + input.name].value = input.value;
			}

		return false;
}

//when the page is onloaded, initialize divStyleDialog
function handleOnload () {
	//highlight the first option of the select
	document.frmStyles.sltLayers.selectedIndex = 0;
	//call function writeDivStyleDialog
	writeDivStyleDialog (document.frmStyles.sltLayers [0].value);


}

//submit the form frmPost to create SLD, then create maps
function doSubmit () {
	document.frmPost.action = '9.php';
	document.frmPost.submit ();

}

