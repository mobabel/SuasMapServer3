/* jsHandler.js , it does not work*/
/*
function inc(filename)
{
var body = document.getElementsByTagName('body').item(0);
script = document.createElement('script');
script.src = filename;
script.type = 'text/javascript';
body.appendChild(script)
}

inc("string.protoype.js");
*/

//var javaScriptDebugMode = document.DEBUG.jsdebug.value;

//######################################################################
//
//The code of Tips is using FSTOOLTIPS.JS V1.1 from FUSIONSCRIPTZ   2006
//Thx!
//######################################################################
var offsetx = 15;
var offsety = 10;
var ie5 = (document.getElementById && document.all);
var ns6 = (document.getElementById && !document.all);
var ua = navigator.userAgent.toLowerCase();
var isapple = (ua.indexOf('applewebkit') != -1 ? 1 : 0);

function CreateNewElement(newid){
    if(document.createElement){
        var el = document.createElement('div');
        el.id = newid;
        with(el.style)
        {
            position = 'absolute';
        }
        el.innerHTML = '&nbsp;';
        window.document.body.appendChild(el);
    }
}

function createNewHiddenElement(newid){

    if(document.createElement){
        var el = document.createElement('div');
        el.id = newid;
        with(el.style)
        {
            display = 'none';
            position = 'absolute';
        }
        el.innerHTML = '&nbsp;';
        document.body.appendChild(el);
    }
}


function getmouseposition(e){
    if(document.getElementById){
        var iebody=(document.compatMode && document.compatMode != 'BackCompat') ?
        		document.documentElement : document.body;
        pagex = (isapple == 1 ? 0:(ie5)?iebody.scrollLeft:window.pageXOffset);
        pagey = (isapple == 1 ? 0:(ie5)?iebody.scrollTop:window.pageYOffset);
        mousex = (ie5)?event.x:(ns6)?clientX = e.clientX:false;
        mousey = (ie5)?event.y:(ns6)?clientY = e.clientY:false;

        var fstooltip = document.getElementById('tooltip');
        var fswarningtip = document.getElementById('warningtip');

	    if(fstooltip){
	        if ((mousex+offsetx+fstooltip.clientWidth+5) > document.body.clientWidth) {
				fstooltip.style.left = ((document.body.scrollLeft+document.body.clientWidth) - (fstooltip.clientWidth*2))+'px';
			}
			else {
				fstooltip.style.left = (mousex+pagex+offsetx)+'px';
			}
			if ((mousey+offsety+fstooltip.clientHeight+5) > document.body.clientHeight) {
	    		fstooltip.style.top = ((document.body.scrollTop+document.body.clientHeight) - (fstooltip.clientHeight*2))+'px';
			}
			else {
				fstooltip.style.top = (mousey+pagey+offsety)+'px';
				}
	    }

	    //for waring tips
		else if(fswarningtip){
		    if ((mousex+offsetx+fswarningtip.clientWidth+5) > document.body.clientWidth) {
		        fswarningtip.style.left = ((document.body.scrollLeft+document.body.clientWidth) - (fswarningtip.clientWidth*2))+'px';
		    }
		    else { fswarningtip.style.left = (mousex+pagex+offsetx)+'px'; }
		    if ((mousey+offsety+fswarningtip.clientHeight+5) > document.body.clientHeight) {
		        fswarningtip.style.top = ((document.body.scrollTop+document.body.clientHeight) - (fswarningtip.clientHeight*2))+'px';
		    }
		    else { fswarningtip.style.top = (mousey+pagey+offsety)+'px'; }
		}
	}
}


function tooltip(tiptitle,tipbold,tipnormal){
    if(!document.getElementById('tooltip')) createNewHiddenElement('tooltip');
    var fstooltip = document.getElementById('tooltip');
    fstooltip.innerHTML = '<table class="fstooltips" cellpadding="2" cellspacing="0"><tr><td class="tipheader"><img src="../img/helptip.png" height="14" width="14" align="right">'
	+ tiptitle + '</td></tr><tr><td class="tipcontent"><b>' + tipbold + '</b><br>' + tipnormal + '</td></tr></table>'
	+ '<iframe src="javascript:false" class="divoverlapiframe" ></iframe>';
    fstooltip.style.display = 'block';
    fstooltip.style.position = 'absolute';
    fstooltip.style.filter = 'progid:DXImageTransform.Microsoft.Alpha(Opacity=70) progid:DXImageTransform.Microsoft.dropshadow(OffX=5, OffY=5, Color=gray, Positive=true)';
    fstooltip.style.zIndex = '1000';
	document.onmousemove = getmouseposition;
}

function warningtip(tiptitle,tipbold,tipnormal){
    if(!document.getElementById('warningtip')) createNewHiddenElement('warningtip');
    var fswarningtip = document.getElementById('warningtip');
    fswarningtip.innerHTML = '<table class="warningtips" cellpadding="2" cellspacing="0"><tr><td class="warningheader"><img src="../img/warning.png" height="14" width="14" align="right">'
	+ tiptitle + '</td></tr><tr><td class="warningcontent"><b>' + tipbold + '</b><br>' + tipnormal + '</td></tr></table>'
	+ '<iframe src="javascript:false" class="divoverlapiframe" ></iframe>';

	fswarningtip.style.display = 'block';
    fswarningtip.style.position = 'absolute';
    fswarningtip.style.filter = 'progid:DXImageTransform.Microsoft.Alpha(Opacity=70) progid:DXImageTransform.Microsoft.dropshadow(OffX=5, OffY=5, Color=gray, Positive=true)';
    fswarningtip.style.zIndex = '1000';
	document.onmousemove = getmouseposition;
}
function exitwarning(){
    document.getElementById('warningtip').style.display = 'none';
}


function exit(){
    document.getElementById('tooltip').style.display = 'none';
}
//################### FSTOOLTIPS END #######################################

//Show or hide the div by the ID
function ShowHide(id,dis){
var bdisplay = (dis==null)?((document.getElementById(id).style.display=="")?"none":""):dis
document.getElementById(id).style.display = bdisplay;
//document.getElementById("xMsg"+id+"bg").style.display = bdisplay;
}

/*****************************************************************************************
 * @Show java script debug error message in the page
 * @access public
 * @return void
 *****************************************************************************************/
function showDebugMessage(error){
    $("ERRORMESSAGE").update("<table width=\"100%\" id=\"idErrorTable\" border=\"0\" cellspacing=\"1\" cellpadding=\"4\" bgcolor=\"#FEF4CC\"><tr><td>"+
    error+"</td></tr></table>");
    window.setInterval("flashit()", 500);
}

function updateMessage(message, type, isparent){
	try{
		if(!isparent){
			if(type == "ERRORMESSAGE"){
				if(message!=""){
					$("ERRORMESSAGE").update(message);
					//bug for prototype
					//$('ERRORMESSAGE').show();
					$("ERRORMESSAGE").style.display = "block";
				}else{
					$("ERRORMESSAGE").hide();
				}
			}
			else if(type == "INFOMESSAGE"){
				if(message!=""){
					$("INFOMESSAGE").update(message);
					//$("INFOMESSAGE").show();
					$("INFOMESSAGE").style.display = "block";
				}else{
					$("INFOMESSAGE").hide();
				}
			}
		}
		else if(isparent){
			if(type == "ERRORMESSAGE"){
				if(message!=""){
					parent.parent.$("ERRORMESSAGE").update(message);
					//bug for prototype
					//$('ERRORMESSAGE').show();
					parent.parent.$("ERRORMESSAGE").style.display = "block";
				}else{
					parent.parent.$("ERRORMESSAGE").hide();
				}
			}
			else if(type == "INFOMESSAGE"){
				if(message!=""){
					parent.parent.$("INFOMESSAGE").update(message);
					//$("INFOMESSAGE").show();
					parent.parent.$("INFOMESSAGE").style.display = "block";
				}else{
					parent.parent.$("INFOMESSAGE").hide();
				}
			}
		}
	}catch(e){alert(e);
		//showDebugMessage(e);
	}
}

function flashit(){
    if (document.getElementById("idErrorTable").style.borderColor=="green")
        document.getElementById("idErrorTable").style.borderColor="#F709F7";
    else
        document.getElementById("idErrorTable").style.borderColor="green";
}


/*****************************************************************************
// getPageSize()
// Returns array with page width, height and window width, height
// Core code from - quirksmode.org
// Edit for Firefox by pHaez
******************************************************************************/
function getPageSize(){

	var xScroll, yScroll;

	if (window.innerHeight && window.scrollMaxY) {
		xScroll = document.body.scrollWidth;
		yScroll = window.innerHeight + window.scrollMaxY;
	} else if (document.body.scrollHeight > document.body.offsetHeight){ // all but Explorer Mac
		xScroll = document.body.scrollWidth;
		yScroll = document.body.scrollHeight;
	} else { // Explorer Mac...would also work in Explorer 6 Strict, Mozilla and Safari
		xScroll = document.body.offsetWidth;
		yScroll = document.body.offsetHeight;
	}

	var windowWidth, windowHeight;
	if (self.innerHeight) {	// all except Explorer
		windowWidth = self.innerWidth;
		windowHeight = self.innerHeight;
	} else if (document.documentElement && document.documentElement.clientHeight) { // Explorer 6 Strict Mode
		windowWidth = document.documentElement.clientWidth;
		windowHeight = document.documentElement.clientHeight;
	} else if (document.body) { // other Explorers
		windowWidth = document.body.clientWidth;
		windowHeight = document.body.clientHeight;
	}

	// for small pages with total height less then height of the viewport
	if(yScroll < windowHeight){
		pageHeight = windowHeight;
	} else {
		pageHeight = yScroll;
	}

	// for small pages with total width less then width of the viewport
	if(xScroll < windowWidth){
		pageWidth = windowWidth;
	} else {
		pageWidth = xScroll;
	}


	arrayPageSize = new Array(pageWidth,pageHeight,windowWidth,windowHeight)
	return arrayPageSize;
}
//***************************************************************************************
//show the error message
//***************************************************************************************
function showErrorMessage(error){
    var arrayPageSize = getPageSize();

    setBackgroudOverlay(arrayPageSize);

    if(!document.getElementById('errormessagefloat'))
        createNewHiddenElement('errormessagefloat');

    var errormessage = $("errormessagefloat");

    errormessage.innerHTML = "";
    errormessage.innerHTML = '<table class="errortips" cellpadding="2" cellspacing="0"><tr><td class="errorheader"><img src="../img/close.png" height="14" width="14" align="right" onclick="closeMessage();" style="CURSOR: pointer">'
	+ 'Error:' + '</td></tr><tr><td class="errorcontent"><b>' + ''  + '</b><br>' + error + '<br></td></tr></table>'
	+ '<iframe src="javascript:false" class="divoverlapiframe" ></iframe>';

    errormessage.style.top = arrayPageSize[3]/2-60+'px';
    errormessage.style.left = arrayPageSize[2]/2-120+'px';
    errormessage.style.display = "block";
    errormessage.style.filter = 'progid:DXImageTransform.Microsoft.Alpha(Opacity=70) progid:DXImageTransform.Microsoft.dropshadow(OffX=5,OffY=5, Color=gray, Positive=true)';
    //-moz-opacity:0.90;
    //set the errormessage on the top of backgroud overlay
	errormessage.style.zIndex = '1000';

}

function closeMessage(){
    $('errormessagefloat').style.display = 'none';
    clearBackgroudOverlay();
}

/**
 * @description Set the overlay on background to deactive the action on background when error message occors
 * @access public
 * @return void
 **/
function  setBackgroudOverlay(arrayPageSize){
    if(!document.getElementById('overlay')){
        createNewHiddenElement('overlay');
    }
    var objOverlay = document.getElementById("overlay");

    objOverlay.onclick = function tem() {return false;}
	objOverlay.style.top = '0';
	objOverlay.style.left = '0';
	//set the overlay be the lowest layer
	objOverlay.style.zIndex = '999';
 	objOverlay.style.width = '100%';

    // set height of Overlay to take up whole page and show
    $('overlay').style.width = (arrayPageSize[2] + 'px');
	$('overlay').style.height = (arrayPageSize[1] + 'px');
	$('overlay').style.display = 'block';
	//$('overlay').style.visibility='visible';
}

/**
 *
 * @access public
 * @return void
 **/
function clearBackgroudOverlay(){
	if(document.getElementById('overlay')){
		$('overlay').style.display = 'none';
    	//$('overlay').style.visibility='hidden';
    }
}

// addLoadEvent()
// Adds event to window.onload without overwriting currently assigned onload functions.
// Function found at Simon Willison's weblog - http://simon.incutio.com/
//
function addLoadEvent(func)
{
	var oldonload = window.onload;
	if (typeof window.onload != 'function'){
    	window.onload = func;
	} else {
		window.onload = function(){
		if (oldonload) {
        	    oldonload();
      		}

		func();
		}
	}

}

/*
* quit install
*/
function quitInstallation(){
    if(window.confirm('Install is not complete. If you exit now, the program will not be installed.\n'+
        'You may run Install again at another time to complete the installation.\n Exit Install?')){
        window.close();
    }else{
        ;
    }
}

function quitConfigure(){
    if(window.confirm('Configure is not complete. If you exit now, the Configuration will not be saved.\n'+
        'You may run Configure again at another time to complete the Configuration.\n Exit Configure?')){
        window.close();
    }else{
        ;
    }
}

/*
* close windows
*/
/*function  window.onunload() {
     if(window.confirm("ÄãÒª¹Ø±ÕÂð?")){
	 window.close();
     }else{
	 return;
     }
}
*/
/**************************************************************************/

//Show the file size, but it doesnt work
function ShowFileSize(fileName)
{
  var fso,f, fsize;
  fso=new ActiveXObject("Scripting.FileSystemObject");
  f=fso.GetFile(fileName);
  alert(f.size);
  fsize = Math.round(f.size/1024*100)/100;
  return fsize;
}

//click to display the hidden column by the ID, used in 5.php or 7.php
function show_tbl_both(pre,objSelect,select_n){
		var tbl= document.getElementById(pre+select_n);
		if(tbl.style.display=="none"){
		    tbl.style.display="block";
		    objSelect.checked=true;
		}
		else if(tbl.style.display=="block"){
		    tbl.style.display="none";
		    objSelect.checked=false;
		}
//document.all(pre+select_n).style.display=='block')?document.all(pre+select_n).style.display='none':document.all(pre+select_n).style.display='block';
}

/**
 *
 * @access public
 * @return void
 **/
function continueInstall(message){
	if(confirm(message)){
	}else{return false;}
}
/**
 * @Description Validate the input value, use for data upload in 2.php and setting.php
 * @access public
 * @return void
 **/
function chkLoginformInput(){
    if(document.formdb.ServerHost.value.isEmpty()){
        showErrorMessage("Server Host can not be none!");
        return false;
    }
    if(document.formdb.ServerHost.value.lastIndexOf('/') !=document.formdb.ServerHost.value.length-1){
        showErrorMessage("Please end Server Host with \"/\"");
        return false;
    }
    if(document.formdb.dbserver.value.isEmpty() || document.formdb.username.value.isEmpty() || document.formdb.dbpassword.value.isEmpty()){
        showErrorMessage("Please input server name, user name and password");
        return false;
    }
}

//***************************************************************************************
//Validate the input value, use for data upload in 2a.php
//***************************************************************************************
//Must input one database name
function chkDabaseCreateInput(){
    if(document.databasenamecreate.databasei.value.isEmpty()){
    	document.databasenamecreate.databasei.value = "";
        //alert("Please input the database name!");
        showErrorMessage("Please input the database name!");
        return false;
    }
    //cant not input only number
    /*var meme = parseInt(document.databasenamecreate.databasei.value);
    if(!isNaN(meme)){
	    document.databasenamecreate.databasei.value = "";
	    showErrorMessage("Could not input only number!");
	    return false;
	}*/
	if(document.databasenamecreate.databasei.value.trim().isNumber()){
		document.databasenamecreate.databasei.value = "";
	    showErrorMessage("Could not input only number!");
	    return false;
	}

}

/**
 *
 * @access public
 * @return void
 **/
function reset_atlas_create_form(){
	try{
		//var serverhost = document.atlas_create_form.ServerHost.value;
		//document.atlas_create_form.reset();
		//document.atlas_create_form.ServerHost.value = serverhost;
		document.atlas_create_form.AtlasName.value = "";
		document.atlas_create_form.ServerTitle.value = "";
		document.atlas_create_form.ServerAbstract.value = "";
		document.atlas_create_form.LayerTitle.value = "";
		document.atlas_create_form.Keyword1.value = "";
		document.atlas_create_form.Keyword2.value = "";
		document.atlas_create_form.Keyword3.value = "";
		document.atlas_create_form.Keyword4.value = "";
		document.atlas_create_form.ContactPerson.value = "";
		document.atlas_create_form.ContactOrganization.value = "";
		document.atlas_create_form.ContactPosition.value = "";
		document.atlas_create_form.ContactAddress.value = "";
		document.atlas_create_form.AddressType.value = "";
		document.atlas_create_form.Address.value = "";
		document.atlas_create_form.City.value = "";
		document.atlas_create_form.StateOrProvince.value = "";
		document.atlas_create_form.PostCode.value = "";
		document.atlas_create_form.Country.selectedIndex = 0;
		document.atlas_create_form.ContactVoiceTelephone.value = "";
		document.atlas_create_form.ContactFacsimileTelephone.value = "";
		document.atlas_create_form.ContactElectronicMailAddress.value = "";
		document.atlas_create_form.ServerFee.value = "";
		document.atlas_create_form.ServerAccessconstraints.value = "";
		document.atlas_create_form.ServerType.value = "";
	}catch(e){
		showDebugMessage(e + " in reset_atlas_create_form");
	}
}


//***************************************************************************************
//Validate the input value, use for create table name in 2b.php
//***************************************************************************************
//Must input one Table name
/* does not use it, because in 2b.php use one dynamic function
function chkTableCreateInput(){
    if(document.tablenamecreate.prefix.value.trim() == ""){
        showErrorMessage("Please input the table name prefix! Can not be empty.");
        document.tablenamecreate.prefix.value = "";
        return false;
    }
}
*/



/**
 *
 * @access public
 * @  used in s2a.php in setting, confirm empty table
 * @return void
 **/
function submitEmptyTable(){
    if(confirm("Are you sure to empty this Table ?"))
	{
		document.tablenameselect.action ="s2aempty.php";
    	document.tablenameselect.submit();
	}
	else{return false;}

}

function submitDeleteSRS(){
    if(confirm("Are you sure to delete the layers?"))
	{
	    //document.nameFormDelete.blndelete = "true";
		document.nameFormDelete.action ="datadelete.php";
    	document.nameFormDelete.submit();

	}
	else{return false;}

}

/*
* Select or deselect the select option in Form, used in getmap demo
*/
function checkall(form, prefix, checkall) {
	var checkall = checkall ? checkall : 'chkall';
	for(var i = 0; i < form.elements.length; i++) {
		var e = form.elements[i];
		if(e.name != checkall && (!prefix || (prefix && e.name.match(prefix)))) {
			e.checked = form.elements[checkall].checked;
		}
	}
}

function checkall_s(node)
{
    if(node.length){
        for (i = 0; i < node.length; i++){
	        node[i].checked = true ;
	    }
	}
	else if(!node.length){
        node.checked = true ;
	}
}

function uncheckAll_s(node)
{
    if(node.length){
    for (i = 0; i < node.length; i++)
	    node[i].checked = false ;
	}
	else if(!node.length){
        node.checked = false ;
	}
}

/**
 *
 * @access public
 * @return void
 **/
function chkCacheformInput(){
    strDatefrom = document.namecache.txtdatefrom.value;
    strDatefrom = strDatefrom.replace(/\//g,"-");
    strDateto = document.namecache.txtdateto.value;
    strDateto = strDateto.replace(/\//g,"-");
    //if not select all
    if(!document.namecache.ckbSelectAll.checked){
        if(strDatefrom!=""){
            //12/31/2007
            var reg = /^(\d{1,2})(-|\/)(\d{1,2})\2(\d{1,4})$/;
            var r = strDatefrom.match(reg);
            var D= new Date(r[4], r[1]-1,r[3]);
            var B = D.getFullYear()==r[4]&&(D.getMonth()+1)==r[1]&&D.getDate()==r[3];
            if (!B) {
			    showErrorMessage("The date format is not right, it should be mm/dd/yyyy");
			    return false;
			}
        }
        if(strDateto!=""){
            //12/31/2007
            var reg = /^(\d{1,2})(-|\/)(\d{1,2})\2(\d{1,4})$/;
            var r = strDateto.match(reg);
            var D= new Date(r[4], r[1]-1,r[3]);
            var B = D.getFullYear()==r[4]&&(D.getMonth()+1)==r[1]&&D.getDate()==r[3];
            if (!B) {
			    showErrorMessage("The date format is not right, it should be mm/dd/yyyy");
			    return false;
			}
        }
    }
}


//***************************************************************************************
//Show and hide table contents
//***************************************************************************************
/*
* GetmapViewers  show and hide the contents of viewers
*/
function ShowHide(item) {

 obj=document.getElementById(item);
 col=document.getElementById("x" + item);
 inf=document.getElementById("m" + item);

 if (obj.style.display=="none") {
  obj.style.display="block";
  col.innerHTML="<img src='../img/minimize.png' alt='minimize' border='0' >";
  inf.innerHTML="&nbsp;";
 }
 else {
  obj.style.display="none";
  col.innerHTML="<img src='../img/maximize.png' alt='maximize' border='0'>";
  inf.innerHTML="Click <img src='../img/maximize.png' alt='maximize' border='0' onclick=\"ShowHide(\'"+item+"\');\"> to expand this content";
 }

}

function GoBack(){
    history.go(-1);
}

/**
 *
 * @access public
 * @desc run the function when page on load
 * @return void
 **/
function doOnload(functionname){
if (window.addEventListener)
window.addEventListener("load", functionname, false);
else if (window.attachEvent)
window.attachEvent("onload", functionname);
else if (document.getElementById)
window.onload=functionname;
}

/**
* works with all the input textfilesd
* it will work for all the pages, it document.onmouseover=mouseOverEventHandle has been set
*/
function   mouseOverEventHandle(e){
  try{
	  if(event.srcElement.tagName.toLowerCase()=='input'){
		  if(event.srcElement.type.toLowerCase()=='text'||event.srcElement.type.toLowerCase()=='password'){
			  cc=event.srcElement;
			  cc.focus();
			  cc.select();
  		}
  	}
  }catch(e){
  }
  try{
	  if(event.srcElement.tagName.toLowerCase()=='textarea'){
		  cc=event.srcElement;
		  cc.focus();
		  cc.select();
	  }
  }catch(e){
  }
}
/*document.onmouseover=mouseOverEventHandle;*/

/**
 *
 * @access public
 * @return void
 **/
function txtfieldSelectAll(e){
	try{
		e.focus();
		e.select();
	}catch(e){
		Debug(e);
	}
}

/**
 * extend the size of text field
 * @access public
 * @return void
 **/
function txtfieldExtendSize(e){
	try{
		size = e.size;
		if(size<50)
			e.size = size + 20;
	}catch(e){
		Debug(e);
	}
}

/**
 * shorten the size of text field
 * @access public
 * @return void
 **/
function txtfieldShortenSize(e){
	try{
		size = e.size;
		if(size>50)
			e.size = size - 20;
	}catch(e){
		Debug(e);
	}
}

/**
 *
 * @access public
 * @return void
 **/
function Debug(error){
	if(javaScriptDebugMode=='true')
		showDebugMessage(error);
}
