function show_deatil_block(id, aid){
	if($("detail_button_"+id).innerHTML == "View Detail"){
		//TODO dynamically to load the detail
		$("block_detail_"+id).show();
		$("detail_button_"+id).update("Hide Detail");
	}else{
		$("block_detail_"+id).hide();
		$("detail_button_"+id).update("View Detail");
	}
}

//***************************************************************************************
//Validate the input value, use for data upload in atlas.php
//***************************************************************************************
//Must input one Table name
function chk_atlas_create_form(){
	if(document.atlas_create_form.AtlasName.value.isEmpty()
	|| document.atlas_create_form.ServerTitle.value.isEmpty()
	|| document.atlas_create_form.ServerAbstract.value.isEmpty()
	|| document.atlas_create_form.LayerTitle.value.isEmpty()
	|| document.atlas_create_form.Keyword1.value.isEmpty()){
        showErrorMessage("You must fill out the fields with star * ");
        return false;
    }
}


//***************************************************************************************
//Hide and show the content of Localfile and Remotefile
//***************************************************************************************
function show_tbl(pre,n,select_n){
	for(i=1;i<=n;i++){
		//content block
		var tblcontent= document.getElementById(pre+i);
		//local link in tab
		var tblmenufirst = document.getElementById(pre+i+"_menu");
		var tblmenusecond = document.getElementById(pre+i+"_second");
		tblcontent.style.display="none";
		if(tblmenusecond){
			tblmenusecond.style.display="none";
		}
		tblmenufirst.className = "current";
		tblmenufirst.style.background = "#FFFAE1";
		tblmenufirst.style.border ="1px solid #666";
		tblmenufirst.style.borderBottom ="1px solid #666";
		tblmenufirst.style.bottom = "0";
		tblmenufirst.style.padding = "4px 0";
		tblmenufirst.style.margin = "1px 2px 0 0";
        if(i==select_n){
			tblcontent.style.display="block";
			if(tblmenusecond){
				tblmenusecond.style.display="block";
			}
			tblmenufirst.className = "current";
			tblmenufirst.style.background = "url(../img/gradient-inner.png)";
			tblmenufirst.style.border ="1px solid #AAA";
			tblmenufirst.style.borderBottom ="none";
			tblmenufirst.style.paddingBottom = "6px";
			tblmenufirst.style.marginTop = "0";
			tblmenufirst.style.bottom = "-1";
		}
	}
	//means select remote file
	//if(select_n==2){
	    //document.getElementById("idLocalFileFlag").value = 0;
	    //alert(document.getElementById("idLocalFileFlag").value);
	//}
	//means select local file
	//if(select_n==1){
	    //document.getElementById("idLocalFileFlag").value = 1;
	    //alert(document.getElementById("idLocalFileFlag").value);
	//}
}

/**
 *
 * @access public
 * @return void
 **/
function setLayerName(opt){
	var layername = opt.options[opt.selectedIndex].value;
	if(layername == "Use_File_Name"){
		$('layernametem').disabled = true;
	}
	else{
		$('layernametem').disabled = false;
	}
	$('layernametem').value=layername;
}
//***************************************************************************************
//Validate the input local file value, use for data upload
//***************************************************************************************
/**
 *
 * @access public
 * @return void
 **/
function checkImportForm(flag){
	//for all form
	//csv has not srs list
	if($('srs_'+flag)){
		if($('srs_'+flag).value.trim().isEmpty()){
			showErrorMessage("Please input the Spatial Reference System name! Default value: SRS_not_defined");
			return false;
		}
		else if($('srs_'+flag).value.trim().indexOf(" ")>0){
			showErrorMessage("SRS name can not have space inside.");
			return false;
		}
	}
	if(flag == 'svg'){
		 var strSVGSelect = document.Form_svg.file_svg.value.toUpperCase().trim();
		 if(strSVGSelect.isEmpty()){
			showErrorMessage("Please select "+flag+" file!");
			return false;
		 }
		 //validate the file type
		 if(strSVGSelect.lastIndexOf(".SVG")==-1 && strSVGSelect.lastIndexOf(".TXT")==-1){
		     showErrorMessage("You can only import SVG file!");
		     return false;
		 }
	}
	else if(flag == 'csv'){
		if(document.Form_csv.csv_terminated.value.isEmpty()){
		    showErrorMessage("Please input the terminated letter");
		    return false;
		}
		var strCSVSelect = document.Form_csv.file_csv.value.toUpperCase().trim();
		if(strCSVSelect.isEmpty()){
			showErrorMessage("Please select "+flag+" file!");
			return false;
		}
		//validate the file type
		if(strCSVSelect.lastIndexOf(".CSV")==-1 && strCSVSelect.lastIndexOf(".TXT")==-1){
		    showErrorMessage("You can only import CSV file!");
		    return false;
		}
	}
	else if(flag == 'shp'){
		var strSHPSelect = document.Form_shp.file_shp.value.toUpperCase().trim();
		var strDBFSelect = document.Form_shp.file_dbf.value.toUpperCase().trim();
		var strShxSelect = document.Form_shp.file_shx.value.toUpperCase().trim();
		//window system, use anti slash
		if(strSHPSelect.lastIndexOf('\\')!=-1){
			var strFileNameShp  = strSHPSelect.substring(strSHPSelect.lastIndexOf('\\')+1,strSHPSelect.lastIndexOf('.'));
			var strFileNameShx  = strShxSelect.substring(strShxSelect.lastIndexOf('\\')+1,strShxSelect.lastIndexOf('.'));
			var strFileNameDbf  = strDBFSelect.substring(strDBFSelect.lastIndexOf('\\')+1,strDBFSelect.lastIndexOf('.'));
		}
		//unix system
		else{
			var strFileNameShp  = strSHPSelect.substring(strSHPSelect.lastIndexOf('/')+1,strSHPSelect.lastIndexOf('.'));
			var strFileNameShx  = strShxSelect.substring(strShxSelect.lastIndexOf('/')+1,strShxSelect.lastIndexOf('.'));
			var strFileNameDbf  = strDBFSelect.substring(strDBFSelect.lastIndexOf('/')+1,strDBFSelect.lastIndexOf('.'));
		}

		if(strSHPSelect.isEmpty()){
		    showErrorMessage("Please select SHP file!");
		    return false;
		}

		 //validate the file type
		 if(strSHPSelect.lastIndexOf(".SHP")==-1){
		     showErrorMessage("You can only import SHP file!");
		     return false;
		 }

		 if(document.Form_shp.UseDbfFile.checked == true){
		     if(strDBFSelect.isEmpty()){
		         showErrorMessage("Please select DBF file!");
		         return false;
		     }
		      //validate the file type
		     if(strDBFSelect.lastIndexOf(".DBF")==-1){
		         showErrorMessage("You can only import DBF file!");
		         return false;
		     }
		     if(strFileNameShp!=strFileNameDbf){
		         showErrorMessage("You can only import DBF file with same name of SHP file!");
		         return false;
		     }

		 }

		  if(document.Form_shp.UseShxFile.checked == true){
		     if(strShxSelect.isEmpty()){
		         showErrorMessage("Please select SHX file!");
		         return false;
		     }
		      //validate the file type
		     if(strShxSelect.lastIndexOf(".SHX")==-1){
		         showErrorMessage("You can only import SHX file!");
		         return false;
		     }
		     if(strFileNameShp!=strFileNameShx){
		         showErrorMessage("You can only import SHX file with same name of SHP file!");
		         return false;
		     }
		 }
	}
	else if(flag == 'mif'){
		var strMifSelect = $('file_'+flag).value.toUpperCase().trim();
		var strMidSelect = $('file_mid').value.toUpperCase().trim();
		//window system, use anti slash
		if(strMifSelect.lastIndexOf('\\')!=-1){
			var strFileNameMif  = strMifSelect.substring(strMifSelect.lastIndexOf('\\')+1,strMifSelect.lastIndexOf('.'));
			var strFileNameMid  = strMidSelect.substring(strMidSelect.lastIndexOf('\\')+1,strMidSelect.lastIndexOf('.'));
		}
		//unix system
		else{
			var strFileNameMif  = strMifSelect.substring(strMifSelect.lastIndexOf('/')+1,strMifSelect.lastIndexOf('.'));
			var strFileNameMid  = strMidSelect.substring(strMidSelect.lastIndexOf('/')+1,strMidSelect.lastIndexOf('.'));
		}
		if(strMifSelect.isEmpty()){
		    showErrorMessage("Please select MIF file!");
		    return false;
		}
		//validate the file type
		if(strMifSelect.lastIndexOf(".MIF")==-1 && strMifSelect.lastIndexOf(".TXT")==-1){
		    showErrorMessage("You can only import MIF file!");
		    return false;
		}
		if($('Form_'+flag).UseMidFile.checked == true){
		     if(strMidSelect.isEmpty()){
		         showErrorMessage("Please select MID file!");
		         return false;
		     }
		      //validate the file type
		     if(strMidSelect.lastIndexOf(".MID")==-1){
		         showErrorMessage("You can only import MID file!");
		         return false;
		     }
		     if(strFileNameMif!=strFileNameMid){
		         showErrorMessage("You can only import MID file with same name of MIF file!");
		         return false;
		     }
		 }
	}
	else if(flag == 'e00'){
		var strE00Select = $('file_'+flag).value.toUpperCase().trim();
		if(strE00Select.isEmpty()){
		    showErrorMessage("Please select E00 file!");
		    return false;
		}
		//validate the file type
		if(strE00Select.lastIndexOf(".E00")==-1 && strE00Select.lastIndexOf(".TXT")==-1){
		    showErrorMessage("You can only import E00 file!");
		    return false;
		}
	}
	else if(flag == 'osm'){
		var strOSMSelect = $('file_'+flag).value.toUpperCase().trim();
		if(strOSMSelect.isEmpty()){
		    showErrorMessage("Please select OSM file!");
		    return false;
		}
		//validate the file type
		if(strOSMSelect.lastIndexOf(".OSM")==-1 && strOSMSelect.lastIndexOf(".TXT")==-1){
		    showErrorMessage("You can only import OSM file!");
		    return false;
		}
	}
	//=========================remote file====================================
	else if(flag == 'svg_remote'){
	 	var strSVGSelect = $('file_'+flag).value.toUpperCase();
		 if(strSVGSelect.isEmpty()){
		     showErrorMessage("Please select SVG file!");
		     return false;
		 }
		 //validate the file type
		 if(strSVGSelect.lastIndexOf(".SVG")==-1 && strSVGSelect.lastIndexOf(".TXT")==-1){
		     showErrorMessage("You can only import SVG file!");
		     return false;
		 }
	}
	else if(flag == 'csv_remote'){
		 if($('Form_'+flag).csv_terminated.value.isEmpty()){
		     showErrorMessage("Please input the terminated letter");
		     return false;
		 }

		 var strCSVSelect = $('file_'+flag).value.toUpperCase();
		 if(strCSVSelect.isEmpty()){
		     showErrorMessage("Please select CSV file!");
		     return false;
		 }
		 //validate the file type
		 if(strCSVSelect.lastIndexOf(".CSV")==-1 && strCSVSelect.lastIndexOf(".TXT")==-1){
		     showErrorMessage("You can only import CSV file!");
		     return false;
		 }
	}
	else if(flag == 'shp_remote'){
		var strSHPSelect = $('file_shp_remote').value.toUpperCase();
		var strDBFSelect = $('file_dbf_remote').value.toUpperCase();
		var strShxSelect = $('file_shx_remote').value.toUpperCase();
		var strFileNameShp  = strSHPSelect.substring(strSHPSelect.lastIndexOf('\\')+1,strSHPSelect.lastIndexOf('.'));
		var strFileNameShx  = strShxSelect.substring(strShxSelect.lastIndexOf('\\')+1,strShxSelect.lastIndexOf('.'));
		var strFileNameDbf  = strDBFSelect.substring(strDBFSelect.lastIndexOf('\\')+1,strDBFSelect.lastIndexOf('.'));

		 if(strSHPSelect.isEmpty()){
		     showErrorMessage("Please select SHP file!");
		     return false;
		 }

		 //validate the file type
		 if(strSHPSelect.lastIndexOf(".SHP")==-1){
		     showErrorMessage("You can only import SHP file!");
		     return false;
		 }

		 if($('Form_'+flag).UseDbfFile.checked == true){
		     if(strDBFSelect.isEmpty()){
		         showErrorMessage("Please select DBF file!");
		         return false;
		     }
		      //validate the file type
		     if(strDBFSelect.lastIndexOf(".DBF")==-1){
		         showErrorMessage("You can only import DBF file!");
		         return false;
		     }
		     if(strFileNameShp!=strFileNameDbf){
		         showErrorMessage("You can only import DBF file with same name of SHP file!");
		         return false;
		     }

		 }

		  if($('Form_'+flag).UseShxFile.checked == true){
		     if(strShxSelect.isEmpty()){
		         showErrorMessage("Please select SHX file!");
		         return false;
		     }
		      //validate the file type
		     if(strShxSelect.lastIndexOf(".SHX")==-1){
		         showErrorMessage("You can only import SHX file!");
		         return false;
		     }
		     if(strFileNameShp!=strFileNameShx){
		         showErrorMessage("You can only import SHX file with same name of SHP file!");
		         return false;
		     }
		 }
	}
	else if(flag == 'mif_remote'){
		 var strMifSelect = $('file_'+flag).value.toUpperCase();
		 var strMidSelect = $('file_mid_remote').value.toUpperCase().trim();
		 //window system, use anti slash
		if(strMifSelect.lastIndexOf('\\')!=-1){
			var strFileNameMif  = strMifSelect.substring(strMifSelect.lastIndexOf('\\')+1,strMifSelect.lastIndexOf('.'));
			var strFileNameMid  = strMidSelect.substring(strMidSelect.lastIndexOf('\\')+1,strMidSelect.lastIndexOf('.'));
		}
		//unix system
		else{
			var strFileNameMif  = strMifSelect.substring(strMifSelect.lastIndexOf('/')+1,strMifSelect.lastIndexOf('.'));
			var strFileNameMid  = strMidSelect.substring(strMidSelect.lastIndexOf('/')+1,strMidSelect.lastIndexOf('.'));
		}
		 if(strMifSelect.isEmpty()){
		     showErrorMessage("Please select MIF file!");
		     return false;
		 }
		 //validate the file type
		 if(strMifSelect.lastIndexOf(".MIF")==-1 && strMifSelect.lastIndexOf(".TXT")==-1){
		     showErrorMessage("You can only import MIF file!");
		     return false;
		 }
		 if($('Form_'+flag).UseMidFile.checked == true){
		     if(strMidSelect.isEmpty()){
		         showErrorMessage("Please select MID file!");
		         return false;
		     }
		      //validate the file type
		     if(strMidSelect.lastIndexOf(".MID")==-1){
		         showErrorMessage("You can only import MID file!");
		         return false;
		     }
		     if(strFileNameMif!=strFileNameMid){
		         showErrorMessage("You can only import MID file with same name of MIF file!");
		         return false;
		     }
		 }
	}
	else if(flag == 'e00_remote'){
		 var strE00Select = $('file_'+flag).value.toUpperCase();
		 if(strE00Select.isEmpty()){
		     showErrorMessage("Please select E00 file!");
		     return false;
		 }
		 //validate the file type
		 if(strE00Select.lastIndexOf(".E00")==-1 && strE00Select.lastIndexOf(".TXT")==-1){
		     showErrorMessage("You can only import E00 file!");
		     return false;
		 }
	}
	else if(flag == 'osm_remote'){
		 var strOSMSelect = $('file_'+flag).value.toUpperCase();
		 if(strOSMSelect.isEmpty()){
		     showErrorMessage("Please select OSM file!");
		     return false;
		 }
		 //validate the file type
		 if(strOSMSelect.lastIndexOf(".OSM")==-1 && strOSMSelect.lastIndexOf(".TXT")==-1){
		     showErrorMessage("You can only import OSM file!");
		     return false;
		 }
	}

	//begin to upload file
	upload(flag);
}


function upload(flag) {
	$('Form_'+flag).layername.value = $('layernametem').value;
	$('Form_'+flag).target="uploadhideiframe";
	$('Form_'+flag).action="upload.php";
	$('Form_'+flag).submit();
	//$('import_'+flag).disabled = true;

	var arrayPageSize = getPageSize();
    setBackgroudOverlay(arrayPageSize);
	createContainer();
	//window.setTimeout("finish()", 2000);
}

/**
 *
 * @access public
 * @return void
 **/
function finish(){
	//if has error
	if($('photostatus').innerHTML.indexOf("ERROR")>0){
		$('idPhotoOperation').disabled = false;
	}else{
		deviceid = $('deviceid').value;
		vendorid = $('vendorid').value;
		$('idPhotoFile').value = "";
		$('idphotolink').value = vendorid+"_"+deviceid+".jpg";
		$('photoimage').src = "deviceimages/"+vendorid+"_"+deviceid+"_thumb.jpg";
	}
}


function openWindows(url){
	var tmp=window.open("about:blank","","fullscreen=1");
	tmp.moveTo(0,0);
	tmp.resizeTo(screen.width+20,screen.height);
	tmp.focus();
	tmp.location=url;
}