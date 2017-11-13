//***************************************************************************************
//copy the text content in textbox to clip, use for data upload
//***************************************************************************************
/*
Highlight and Copy form element script- By Dynamicdrive.com
For full source, Terms of service, and 100s DTHML scripts
Visit http://www.dynamicdrive.com
*/
//specify whether contents should be auto copied to clipboard (memory)
//Applies only to IE 4+
//0=no, 1=yes
var copytoclip=1;

function HighlightAll(theField) {
	var tempval=eval("document."+theField);
	//var tempval = eval("document.getElementById(theField)");

	tempval.focus();
	tempval.select();
	if (document.all&&copytoclip==1){
		therange=tempval.createTextRange();
		therange.execCommand("Copy");
		window.status="Contents highlighted and copied to clipboard!";
		setTimeout("window.status=''",1800);
	}
}