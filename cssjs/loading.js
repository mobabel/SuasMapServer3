/***************************************************************************************
*set the loading gauge, use for data upload
*Usage:
* The deploy of this js file MUST be behind the <body> element!!!!!
* Otherwise the body element will be none!!!
*  <body>
*  <script type="text/javascript" src="loading.js"></script>
***************************************************************************************/
//createContainer();
//var t_id = window.setInterval(animate,200);

//does notwork
//var t_id = setInterval(animate,200);

var pos=0;
var dir=2;
var len=0;
var i=0;
var text="&nbsp;&nbsp;&nbsp;&nbsp;File Uploading";

/**
 * @description create loader_container DIV
 * @access public
 * @return void
 **/
function createContainer(){
    var arrayPageSize = getPageSize();

    if(!$('loader_container')) {
		CreateNewElement('loader_container');
	}

	$('loader_container').style.display='block';
	$('loader_container').style.visibility='visible';
	//$('loader_container').show();

    var loadercontainer = $('loader_container');
    loadercontainer.innerHTML = "<div id=\"loader\">"+
	"<div id=\"loadtext\" align=\"left\">File Uploading...</div>"+
	//"<div id=\"loader_bg\"><div id=\"inprogress\"> </div></div>"+
	"<div align=\"center\"><br><image src=\"../img/loadingbar.gif\"></div>"+
	"</div>"
	+ "<iframe src=\"javascript:false\" class=\"divoverlapiframe_upload\" ></iframe>";

	loadercontainer.style.top = arrayPageSize[3]/2-60;
    loadercontainer.style.left = arrayPageSize[2]/2-110;
    loadercontainer.style.zIndex = '1000';
    var t_id = window.setInterval(animate,200);
    setInterval(animate,200);
}

function animate(){
    var elem = $('inprogress');
    var eleloadtext = $('loadtext');

    if(elem != null) {
        if (pos==0) len += dir;
        if (len>32 || pos>79) pos += dir;
        if (pos>79) len -= dir;
        if (pos>79 && len==0) pos=0;
        elem.style.left = pos;
        elem.style.width = len;
    }

    if (i<6) {
        text+=".";
        eleloadtext.innerHTML=text;
        i++;
    }
    else {
         text="&nbsp;&nbsp;&nbsp;&nbsp;File Uploading";
         i=0;
   }

}

function remove_loading() {
	windows.clearInterval(t_id);
	this.clearInterval(t_id);
	var targelem = $('loader_container');
	targelem.style.display='none';
	targelem.style.visibility='hidden';
	//$('loader_container').hide();
}


//addLoadEvent(createContainer);	// run createContainer onLoad

//addLoadEvent(remove_loading);

