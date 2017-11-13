<?php
/**
 *
 *
 * @version $Id$
 * @copyright 2007
 */
?>
<div id="PARENT">
<ul id="nav">
<li><a href="#Menu=ChildMenu1"  onclick="DoMenu('ChildMenu1')">&nbsp;&nbsp;&nbsp;&nbsp;SVG To Database</a>
	<ul id="ChildMenu1" class="collapsed">
	<li></li>
	<form name="FormSvg" enctype="multipart/form-data" action="6a.php" method="post" onSubmit="return chkSvgInput()">
	<input type="hidden" type="radio" id="idLocalFileFlag" name="nameLocalFileFlag" value="1">
                    <table width="450"  border="0" cellspacing="1" cellpadding="4" bgcolor="white">
                    	<tr>
                    		<td colspan="2"><h3>SVG To Database</h3></td>
                   		</tr>
                   		<tr>
                    		<td colspan="2"></td>
                   		</tr>
                   		<tr>
                    		<td>SRS: <image src="../img/help.png"  border="0" onmouseover="tooltip('SRS','Description:','Spatial Reference System, for example: EPSG:31467.<br>EPSG:4326 = WGS84.<br>Please use default value as displayed if you do not know which SRS is using.');" onmouseout="exit();"></td>
                    		<td><? printEPSGList(0);?>
							</td>
                   		</tr>
                   		<tr>
                    		<td>Select SVG File:</td>
                    		<td><input type="file" name="SVGSelect" class="button2 browseFileInput"/></td>
                   		</tr>
                   		<tr>
                    		<td><input type="hidden" name="max_file_size" value="<?=$maxUploadFileSize?>"></td>
                    		<td><input type="hidden" name="SVGUpload" value="Upload" onmouseover="this.className='button1'" onmouseout="this.className='button'" class="button"/>
							    <input type="submit" name="SVGImport" value="Import" onmouseover="this.className='button1 importInput'" onmouseout="this.className='button importInput'" class="button importInput"/>
							</td>
                   		</tr>
                   	</table>
                </form>
	</ul>
</li>
<li><a href="#Menu=ChildMenu2" onclick="DoMenu('ChildMenu2')">&nbsp;&nbsp;&nbsp;&nbsp;CSV To Database</a>
	<ul id="ChildMenu2" class="collapsed">
	<li></li>
	<form name="FormCsv" enctype="multipart/form-data" action="6b.php" method="post" onSubmit="return chkCsvInput()">
	<input type="hidden" type="radio" id="idLocalFileFlag" name="nameLocalFileFlag" value="1">
                    <table width="450"  border="0" cellspacing="1" cellpadding="4" bgcolor="white">
                    	<tr>
                    		<td colspan="2"><h3>CSV To Database</h3></td>
                   		</tr>
                   		<tr>
                    		<td colspan="2">
							<p>&#8226; You should make sure that CSV file has the following standard format<br>
							   &#8226; id|layer|recid|geomtype|xmin|ymin|xmax|ymax|geom|svgxlink|srs|<br>attributes|style</p></td>
                   		</tr>
<?                   		//<tr>
                    		//<td></td>
                    		//<td><input type="checkbox" name="csv_replace" class="button3" value="something" id="checkbox_csv_replace" /> Replace table data with file <br>
                                //<input type="checkbox" name="csv_ignore" class="button3" value="something" id="checkbox_csv_ignore" /> Ignore duplicate rows
							//</td>
                   	//	</tr>
?>
                   		<tr>
                    		<td>Fields terminated by:</td>
                    		<td><input type="text" name="csv_terminated"  id="text_csv_terminated" size="2" maxlength="1" class="smallInput" value=";"/></td>
                   		</tr>
                   		<tr>
                    		<td>Fields enclosed by:</td>
                    		<td><input type="text" name="csv_enclosed"  id="text_csv_enclosed" size="2" maxlength="1" class="smallInput" value=","/></td>
                   		</tr>
                   		<tr>
                    		<td>Fields escaped by:</td>
                    		<td><input type="text" name="csv_escaped"  id="text_csv_escaped" size="2" maxlength="1" class="smallInput" value="\"/></td>
                   		</tr>
<?                   		//<tr>
                    		//<td>Lines terminated by:</td>
                    		//<td><input type="text" name="csv_new_line" value="auto" id="text_csv_new_line" size="2" class="smallInput"/></td>
                   		//</tr>
                   		//<tr>
                    		//<td>Column names:</td>
                    		//<td><input type="text" name="csv_columns" value="" id="text_csv_columns" class="smallInput"/></td>
                   	//	</tr>

                   		//<tr>
                    		//<td></td>
                    		//<td><input type="hidden" name="ldi_local_option" value="something" id="checkbox_ldi_local_option"  checked="checked" valuse=" Use LOCAL keyword"/>
                                //<input type="checkbox" name="use_csv_header" class="button3" id="use_csv_header" style="cursor:pointer;"/> Use CSV header
							  //</td>
                   		//</tr>
?>
                   		<tr>
                    		<td>Select CSV File:</td>
                    		<td><input type="file" name="CSVSelect" class="button2 browseFileInput"/></td>
                   		</tr>
                   		<tr>
                    		<td><input type="hidden" name="max_file_size" value="<?=$maxUploadFileSize?>"></td>
                    		<td><input type="submit" name="CSVImport" value="Import" onmouseover="this.className='button1 importInput'" onmouseout="this.className='button importInput'" class="button importInput"/></td>
                   		</tr>
                   	</table>
                </form>

	</ul>
</li>
<li><a href="#Menu=ChildMenu3" onclick="DoMenu('ChildMenu3')">&nbsp;&nbsp;&nbsp;&nbsp;SHP To Database</a>
	<ul id="ChildMenu3" class="collapsed">
	<li></li>
	<form name="FormShp" enctype="multipart/form-data" action="6c.php" method="post" onSubmit="return chkShpInput()">
	<input type="hidden" type="radio" id="idLocalFileFlag" name="nameLocalFileFlag" value="1">
                    <table width="450"  border="0" cellspacing="1" cellpadding="4" bgcolor="white">
                    	<tr style="display: block;">
                    		<td colspan="2"><h3>SHP To Database</h3></td>
                   		</tr>
                   		<tr style="display: block;">
                    		    <td colspan="2">
				    <p>&#8226; You <font class="error">MAY</font> have not *.dbf or *.shx file.<br>
				    &#8226; But the *.shp file is required.<br>
				    </p>
				    </td>
                   		</tr>
                   		<tr style="display: block;">
                    		    <td>SRS: <image src="../img/help.png"  border="0" onmouseover="tooltip('SRS','Description:','Spatial Reference System, for example: EPSG:31467.<br>EPSG:4326 = WGS84.<br>Please use default value as displayed if you do not know which SRS is using.');" onmouseout="exit();">
				    </td>
                    		    <td><? printEPSGList(1);?></td>
                   		</tr>
                   		<tr style="display: block;">
                    		    <td>&nbsp;</td>
                    		    <td>
                                    <input type="checkbox" name="nameUseShpFile" class="button3" id="idUseShpFile" checked disabled/> Use SHP file <br>
                                    <input type="checkbox" name="nameUseDbfFile" class="button3" id="idUseDbfFile" onClick="show_tbl_both('localcontent2_',this,1)" style="cursor:pointer;"
				    <? if(!checkExtensionDbaseInPHP()){echo " disabled";}?>/>
                                    Use DBF file
                                    <image src="../img/help.png"  border="0" onmouseover="tooltip('Use DBF file','Description:','This will store the attributes of geometries meanwhile storing geometries of SHP file. If your checkbox here is inactivive, please modify you php.ini file, delete the semicolon before <font class=error>extension=php_dbase.dll</font> to open the Dbase PHP function.');" onmouseout="exit();">
				    <br>
                                    <input type="checkbox" name="nameUseShxFile" class="button3" id="idUseShxFile" onClick="show_tbl_both('localcontent2_',this,2)" style="cursor:pointer;" />
                                    Use SHX file <br>
				    </td>
                   		</tr>
                   		<tr style="display: block;">
                    		    <td>Select SHP File:</td>
                    		    <td><input type="file" name="nameShpSelect" class="button2 browseFileInput"/></td>
                   		</tr>
                   		<tr id="localcontent2_1"  style="display:none">
                    		    <td>Select DBF File:</td>
                    		    <td><input type="file" name="nameDbfSelect" class="button2 browseFileInput"/></td>
                   		</tr>
                   		<tr id="localcontent2_2"  style="display:none">
                    		    <td>Select SHX File:</td>
                    		    <td><input type="file" name="nameShxSelect" class="button2 browseFileInput"/></td>
                   		</tr>
                   		<tr style="display: block;">
                    		    <td><input type="hidden" name="max_file_size" value="<?=$maxUploadFileSize?>"></td>
                    		    <td><input type="submit" name="nameShpImport" value="Import" onmouseover="this.className='button1 importInput'" onmouseout="this.className='button importInput'" class="button importInput"/></td>
                   		</tr>
                   	</table>
                </form>
	</ul>
</li>
<li><a href="#Menu=ChildMenu4" onclick="DoMenu('ChildMenu4')">&nbsp;&nbsp;&nbsp;&nbsp;MIF To Database</a>
	<ul id="ChildMenu4" class="collapsed">
	<li></li>
        <form name="FormMif" enctype="multipart/form-data" action="6d.php" method="post" onSubmit="return chkMifInput()">
	    <input type="hidden" type="radio" id="idLocalFileFlag" name="nameLocalFileFlag" value="1">
                    <table width="450"  border="0" cellspacing="1" cellpadding="4" bgcolor="white">
                    	<tr>
                    		<td colspan="2"><h3>MIF To Database</h3></td>
                   		</tr>
                   		<tr>
                    		<td colspan="2">
							<p><br>
                                </p>
							</td>
                   		</tr>
                   		<tr>
                    		<td>SRS: <image src="../img/help.png"  border="0" onmouseover="tooltip('SRS','Description:','Spatial Reference System, for example: EPSG:31467.<br>EPSG:4326 = WGS84.<br>Please use default value as displayed if you do not know which SRS is using.');" onmouseout="exit();">
							</td>
                    		<td><? printEPSGList(2);?>
							</td>
                   		</tr>
                   		<tr>
                    		<td>Select MIF File:</td>
                    		<td><input type="file" name="nameMifSelect" class="button2 browseFileInput"/></td>
                   		</tr>
                   		<tr>
                    		<td><input type="hidden" name="max_file_size" value="<?=$maxUploadFileSize?>"></td>
                    		<td><input type="submit" name="nameMifImport" value="Import" onmouseover="this.className='button1 importInput'" onmouseout="this.className='button importInput'" class="button importInput"/></td>
                   		</tr>
                   	</table>
                </form>
	</ul>
</li>
<li><a href="#Menu=ChildMenu5" onclick="DoMenu('ChildMenu5')">&nbsp;&nbsp;&nbsp;&nbsp;E00 To Database</a>
	<ul id="ChildMenu5" class="collapsed">
	<li></li>
        <form name="FormE00" enctype="multipart/form-data" action="6e.php" method="post" onSubmit="return chkE00Input()">
	    <input type="hidden" type="radio" id="idLocalFileFlag" name="nameLocalFileFlag" value="1">
                    <table width="450"  border="0" cellspacing="1" cellpadding="4" bgcolor="white">
                    	<tr>
                    		<td colspan="2"><h3>E00 To Database</h3></td>
                   		</tr>
                   		<tr>
                    		<td colspan="2">
							<p><br>
                                </p>
							</td>
                   		</tr>
                   		<tr>
                    		<td>SRS: <image src="../img/help.png"  border="0" onmouseover="tooltip('SRS','Description:','Spatial Reference System, for example: EPSG:31467.<br>EPSG:4326 = WGS84.<br>Please use default value as displayed if you do not know which SRS is using.');" onmouseout="exit();">
							</td>
                    		<td><? printEPSGList(3);?>
							</td>
                   		</tr>
                   		<tr>
                    		<td>Select E00 File:</td>
                    		<td><input type="file" name="nameE00Select" class="button2 browseFileInput"/></td>
                   		</tr>
                   		<tr>
                    		<td><input type="hidden" name="max_file_size" value="<?=$maxUploadFileSize?>"></td>
                    		<td><input type="submit" name="nameE00Import" value="Import" onmouseover="this.className='button1 importInput'" onmouseout="this.className='button importInput'" class="button importInput"/></td>
                   		</tr>
                   	</table>
                </form>
	</ul>
</li>
</ul>
</div>