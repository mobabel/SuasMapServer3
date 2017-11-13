<?php
/**
 *
 *
 * @version $Id$
 * @copyright 2007
 */
?>
<div id="PARENTr">
<ul id="navr">
<li><a href="#Menur=ChildMenur1"  onclick="DoMenur('ChildMenur1')">&nbsp;&nbsp;&nbsp;&nbsp;SVG To Database</a>
	<ul id="ChildMenur1" class="collapsed">
	<li></li>
<form name="FormRemoteSvg" enctype="multipart/form-data" action="6a.php" method="post" onSubmit="return chkRemoteSvgInput()">
<input type="hidden" type="radio" id="idLocalFileFlag" name="nameLocalFileFlag" value="0">
                    <table width="450"  border="0" cellspacing="1" cellpadding="4" bgcolor="white">
                    	<tr>
                    		<td colspan="2"><h3>SVG To Database</h3></td>
                   		</tr>
                   		<tr>
                    		<td>SRS: <image src="../img/help.png"  border="0" onmouseover="tooltip('SRS','Description:','Spatial Reference System, for example: EPSG:31467.<br>EPSG:4326 = WGS84.<br>Please use default value as displayed if you do not know which SRS is using.');" onmouseout="exit();"></td>
                    		<td><? printEPSGList(4);?>
							</td>
                   		</tr>
                   		<tr>
                    		<td>Select SVG File:</td>
                    		<td><?listDataInDirectory(0);?></td>
                   		</tr>
                   		<tr>
                    		    <td></td>
                    		    <td>
				    <input type="submit" name="RemoteSVGImport" value="Import" onmouseover="this.className='button1 importInput'" onmouseout="this.className='button importInput'" class="button importInput"/>
				    </td>
                   		</tr>
                   	</table>
                </form>
	</ul>
</li>
<li><a href="#Menur=ChildMenur2" onclick="DoMenur('ChildMenur2')">&nbsp;&nbsp;&nbsp;&nbsp;CSV To Database</a>
	<ul id="ChildMenur2" class="collapsed">
	<li></li>
<form name="FormRemoteCsv" enctype="multipart/form-data" action="6b.php" method="post" onSubmit="return chkRemoteCsvInput()">
<input type="hidden" type="radio" id="idLocalFileFlag" name="nameLocalFileFlag" value="0">
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
                    		<td><input type="text" name="Remotecsv_terminated"  id="Remotetext_csv_terminated" size="2" maxlength="1" class="smallInput" value=";"/></td>
                   		</tr>
                   		<tr>
                    		<td>Fields enclosed by:</td>
                    		<td><input type="text" name="Remotecsv_enclosed"  id="Remotetext_csv_enclosed" size="2" maxlength="1" class="smallInput" value=","/></td>
                   		</tr>
                   		<tr>
                    		<td>Fields escaped by:</td>
                    		<td><input type="text" name="Remotecsv_escaped"  id="Remotetext_csv_escaped" size="2" maxlength="1" class="smallInput" value="\"/></td>
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
                    		//<td><input type="hidden" name="Remoteldi_local_option" value="something" id="Remotecheckbox_ldi_local_option"  checked="checked" valuse=" Use LOCAL keyword"/>
                                //<input type="checkbox" name="Remoteuse_csv_header" class="button3" id="Remoteuse_csv_header" style="cursor:pointer;"/> Use CSV header
							  //</td>
                   		//</tr>
?>
                   		<tr>
                    		<td>Select CSV File:</td>
                    		<td><?listDataInDirectory(1);?></td>
                   		</tr>
                   		<tr>
                    		<td></td>
                    		<td><input type="submit" name="RemoteCSVImport" value="Import" onmouseover="this.className='button1 importInput'" onmouseout="this.className='button importInput'" class="button importInput"/></td>
                   		</tr>
                   	</table>
                </form>
	</ul>
</li>
<li><a href="#Menur=ChildMenur3" onclick="DoMenur('ChildMenur3')">&nbsp;&nbsp;&nbsp;&nbsp;SHP To Database</a>
	<ul id="ChildMenur3" class="collapsed">
	<li></li>
        <form name="FormRemoteShp" enctype="multipart/form-data" action="6c.php" method="post" onSubmit="return chkRemoteShpInput()">
		<input type="hidden" type="radio" id="idLocalFileFlag" name="nameLocalFileFlag" value="0">
                    <table width="450"  border="0" cellspacing="1" cellpadding="4" bgcolor="white">
				<tr>
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
                    		    <td><? printEPSGList(5);?></td>
                   		</tr>
                   		<tr style="display: block;">
                    		    <td></td>
                    		    <td>
                                    <input type="checkbox" name="RemotenameUseShpFile" class="button3" id="RemoteidUseShpFile" checked disabled/> Use SHP file <br>
                                    <input type="checkbox" name="RemotenameUseDbfFile" class="button3" id="RemoteidUseDbfFile" onClick="show_tbl_both('Remotecontent3_',this,1)" style="cursor:pointer;"
				    <? if(!checkExtensionDbaseInPHP()){echo " disabled";}?>/>
                                    Use DBF file
                                    <image src="../img/help.png"  border="0" onmouseover="tooltip('Use DBF file','Description:','This will store the attributes of geometries meanwhile storing geometries of SHP file. If your checkbox here is inactivive, please modify you php.ini file, delete the semicolon before <font class=error>extension=php_dbase.dll</font> to open the Dbase PHP function.');" onmouseout="exit();">
                                    <br>
                                    <input type="checkbox" name="RemotenameUseShxFile" class="button3" id="RemoteidUseShxFile" onClick="show_tbl_both('Remotecontent3_',this,2)" style="cursor:pointer;" />
                                    Use SHX file <br>
				    </td>
                   		</tr>
                   		<tr style="display: block;">
                    		    <td>Select SHP File:</td>
                    		    <td><? listDataInDirectory(2);?></td>
                   		</tr>
                   		<tr id="Remotecontent3_1"  style="display:none">
                                    <td>Select DBF File:</td>
                                    <td><? listDataInDirectory(3);?></td>
				</tr>
				<tr id="Remotecontent3_2"  style="display:none">;
                                    <td>Select SHX File:</td>
                                    <td><? listDataInDirectory(4);?></td>
				</tr>
                   		<tr style="display: block;">
                    		    <td></td>
                    		    <td><input type="submit" name="RemotenameShpImport" value="Import" onmouseover="this.className='button1 importInput'" onmouseout="this.className='button importInput'" class="button importInput"/></td>
                   		</tr>
                   	</table>
                </form>
	</ul>
</li>
<li><a href="#Menur=ChildMenur4" onclick="DoMenur('ChildMenur4')">&nbsp;&nbsp;&nbsp;&nbsp;MIF To Database</a>
	<ul id="ChildMenur4" class="collapsed">
	<li></li>
<form name="FormRemoteMif" enctype="multipart/form-data" action="6d.php" method="post" onSubmit="return chkRemoteMifInput()">
<input type="hidden" type="radio" id="idLocalFileFlag" name="nameLocalFileFlag" value="0">
                    <table width="450"  border="0" cellspacing="1" cellpadding="4" bgcolor="white">
                    	<tr>
                    		<td colspan="2"><h3>MIF To Database</h3></td>
                   		</tr>
                   		<tr>
                    		<td colspan="2">
							<p>
							</p>
							</td>
                   		</tr>
                   		<tr>
                    		<td>SRS: <image src="../img/help.png"  border="0" onmouseover="tooltip('SRS','Description:','Spatial Reference System, for example: EPSG:31467.<br>EPSG:4326 = WGS84.<br>Please use default value as displayed if you do not know which SRS is using.');" onmouseout="exit();">
							</td>
                    		<td><? printEPSGList(6);?>
							</td>
                   		</tr>
                   		<tr>
                    		<td>Select MIF File:</td>
                    		<td><?listDataInDirectory(5);?></td>
                   		</tr>
                   		<tr>
                    		<td></td>
                    		<td><input type="submit" name="RemotenameMifImport" value="Import" onmouseover="this.className='button1 importInput'" onmouseout="this.className='button importInput'" class="button importInput"/></td>
                   		</tr>
                   	</table>
                </form>
	</ul>
</li>
<li><a href="#Menur=ChildMenur5" onclick="DoMenur('ChildMenur5')">&nbsp;&nbsp;&nbsp;&nbsp;E00 To Database</a>
	<ul id="ChildMenur5" class="collapsed">
	<li></li>
        <form name="FormRemoteE00" enctype="multipart/form-data" action="6e.php" method="post" onSubmit="return chkRemoteE00Input()">
			<input type="hidden" type="radio" id="idLocalFileFlag" name="nameLocalFileFlag" value="0">
                    <table width="450"  border="0" cellspacing="1" cellpadding="4" bgcolor="white">
                    	<tr>
                    		<td colspan="2"><h3>E00 To Database</h3></td>
                   		</tr>
                   		<tr>
                    		<td colspan="2">
							<p>
							</p>
							</td>
                   		</tr>
                   		<tr>
                    		<td>SRS: <image src="../img/help.png"  border="0" onmouseover="tooltip('SRS','Description:','Spatial Reference System, for example: EPSG:31467.<br>EPSG:4326 = WGS84.<br>Please use default value as displayed if you do not know which SRS is using.');" onmouseout="exit();">
							</td>
                    		<td><? printEPSGList(7);?>
							</td>
                   		</tr>
                   		<tr>
                    		<td>Select E00 File:</td>
                    		<td><?listDataInDirectory(6);?></td>
                   		</tr>
                   		<tr>
                    		<td></td>
                    		<td><input type="submit" name="RemotenameE00Import" value="Import" onmouseover="this.className='button1 importInput'" onmouseout="this.className='button importInput'" class="button importInput"/></td>
                   		</tr>
                   	</table>
                </form>
	</ul>
</li>
</ul>
</div>