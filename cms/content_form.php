<?php 
		if (!isset($nb_wysiwyg)) {
			$nb_wysiwyg = 0;
		}
		$nb_wysiwyg++;

		if (!isset($wysiwyg_params['name'])) {
			$wysiwyg_params['name'] = "wysiwyg_".$nb_wysiwyg;
		}
		if (!isset($wysiwyg_params['empty_on_focus'])) {
			$wysiwyg_params['empty_on_focus'] = false;
		}
		
		$contenteditable_class = '';		
		if (!isset($wysiwyg_params['line_max_chars'])) {
			$wysiwyg_params['line_max_chars'] = 0;
		}
		if (!isset($wysiwyg_params['max_lines'])) {
			$wysiwyg_params['max_lines'] = 0;
		} 
		if (!isset($wysiwyg_params['max_chars'])) {
			$wysiwyg_params['max_chars'] = 0;
		}		
		if ($wysiwyg_params['max_chars']<1) {
			$wysiwyg_params['max_chars'] = $wysiwyg_params['max_lines']*$wysiwyg_params['line_max_chars'];
		}
		
		if ($wysiwyg_params['line_max_chars']>0) {
			$contenteditable_class .= ' line_max_chars_'.$wysiwyg_params['line_max_chars'];
		}	
		if ($wysiwyg_params['max_lines']>0) {
			$contenteditable_class .= ' max_lines_'.$wysiwyg_params['max_lines'];
		}	
		if ($wysiwyg_params['max_chars']>0) {
			$contenteditable_class .= ' max_chars_'.$wysiwyg_params['max_chars'];
		}		
		if (!empty($contenteditable_class)) {
			$contenteditable_class = 'limits '.$contenteditable_class;
		}
		
		$container_class = '';
		if (isset($wysiwyg_params['simple_editor'])) { $container_class .= 'simple_editor'; }
		
		if ($_GET['p']=='blocks') {	
			$contenteditable_class.=' block';			
		} else if ($_GET['p']=='pages') { $contenteditable_class.=' content'; }

		$contenteditable_css = '';
		if (isset($wysiwyg_params['bgcolor']) && !empty($wysiwyg_params['bgcolor'])) { 
			$contenteditable_css.='background-color:#'.$wysiwyg_params['bgcolor'].';';
			$rgb = eTools::hex2rgb($wysiwyg_params['bgcolor']);
			if ($rgb[0]+$rgb[1]+$rgb[2]>=600) { $contenteditable_class.=' darktext'; } else { $contenteditable_class.=' lighttext'; }
		}
		if (isset($wysiwyg_params['textalign']) && $wysiwyg_params['textalign']!='initial') { 
			$contenteditable_class.=' align_'.$wysiwyg_params['textalign'];
		}
		if (!empty($contenteditable_css)) { $contenteditable_css = 'style="'.$contenteditable_css.'"'; }
												
		$default_content = eText::style_to_html($wysiwyg_params['content']);
?>
					<div class="wysiwyg_container <?php echo $wysiwyg_params['name']; ?> <?php echo $container_class; ?>">
						<script type="text/javascript">															
							$(document).ready(function() {

								let thisWysiwyg = new eWysiwyg(
									'<?php echo $wysiwyg_params['name']; ?>', 
									'<?php echo $temp_lang; ?>', 
									<?php echo json_encode($wysiwyg_params); ?>
								);
								
								myWysiwygs['<?php echo $wysiwyg_params['name']; ?>'] = thisWysiwyg;
								
							});							
						</script>	
						
						<div id="controls_editor">
							<a id="code_btn_<?php echo $wysiwyg_params['name']; ?>" class="selected_control_editor" href="javascript:myWysiwygs['<?php echo $wysiwyg_params['name']; ?>'].setEditorControls('code');">Code</a>
							<a id="wysiwyg_btn_<?php echo $wysiwyg_params['name']; ?>" href="javascript:myWysiwygs['<?php echo $wysiwyg_params['name']; ?>'].setEditorControls('wysiwyg');">Wysiwyg</a>
						</div>
						<div class="group">
							
							<h2>
								Prévisualisation 
								<a href="javascript:myWysiwygs['<?php echo $wysiwyg_params['name']; ?>'].showPreview(true);">Afficher</a> 
								/ 
								<a href="javascript:myWysiwygs['<?php echo $wysiwyg_params['name']; ?>'].showPreview(false);">Masquer</a>
							</h2>

							<div class="simple_styles">
								<a class="simple_style" href="bold" onclick="myWysiwygs['<?php echo $wysiwyg_params['name']; ?>'].addStyle('bold'); return false;"><img src="design/wysiwyg/bold-off.gif" onmouseover="this.src=this.src.substring(0, this.src.lastIndexOf('-'))+'-on.gif';" onmouseout="if (!$(this).hasClass('currentNode')) { this.src=this.src.substring(0, this.src.lastIndexOf('-'))+'-off.gif'; }" width="27" height="27" alt="Bold" /></a> 
								<a class="simple_style" href="italic" onclick="myWysiwygs['<?php echo $wysiwyg_params['name']; ?>'].addStyle('italic'); return false;"><img src="design/wysiwyg/italic-off.gif" onmouseover="this.src=this.src.substring(0, this.src.lastIndexOf('-'))+'-on.gif';" onmouseout="if (!$(this).hasClass('currentNode')) { this.src=this.src.substring(0, this.src.lastIndexOf('-'))+'-off.gif'; }" width="27" height="27" alt="italic" /></a> 
								<a class="simple_style" href="underlined" onclick="myWysiwygs['<?php echo $wysiwyg_params['name']; ?>'].addStyle('underlined'); return false;"><img src="design/wysiwyg/underline-off.gif" onmouseover="this.src=this.src.substring(0, this.src.lastIndexOf('-'))+'-on.gif';" onmouseout="if (!$(this).hasClass('currentNode')) { this.src=this.src.substring(0, this.src.lastIndexOf('-'))+'-off.gif'; }" width="27" height="27" alt="underlined" /></a> 
							</div>
							
							<div class="wysiwyg <?php echo $contenteditable_class; ?>" <?php echo $contenteditable_css; ?> id="wysiwyg_<?php echo $wysiwyg_params['name']; ?>" style="" onfocus="if (myWysiwygs['<?php echo $wysiwyg_params['name']; ?>'].emptyOnFocus=='empty') { $(this).html(''); myWysiwygs['<?php echo $wysiwyg_params['name']; ?>'].emptyOnFocus=''; }"><?php echo eText::no_script(eText::style_to_html($wysiwyg_params['content'], $temp_lang)); ?></div><!-- END OF WYSIWYG -->
							
							<h2>Code <a href="javascript:myWysiwygs['<?php echo $wysiwyg_params['name']; ?>'].showCode(true);">Afficher</a> / <a href="javascript:myWysiwygs['<?php echo $wysiwyg_params['name']; ?>'].showCode(false);">Masquer</a></h2>
							<textarea name="<?php echo $wysiwyg_params['name']; ?>" class="<?php echo $contenteditable_class; ?>" id="<?php echo $wysiwyg_params['name']; ?>"><?php echo $wysiwyg_params['content']; ?></textarea>		
												
							<div class="align_right">
								<div class="submit" onclick="$('#post_lang').val('<?php echo $temp_lang; ?>'); eForm.submit(this);">ENREGISTRER</div>
							</div>
														
							<table class="wysiwyg_toolbox" cellspacing="0">
								<tr>
									<td width="25%">
										<b>Structure :</b>
									</td>
									<td>
										<input type="button" value="Titre" onclick="myWysiwygs['<?php echo $wysiwyg_params['name']; ?>'].addStyle('title1');" style="font-size: 16px;font-weight:bold;" class="h1_btn">
										<input type="button" value="Sous-titre" onclick="myWysiwygs['<?php echo $wysiwyg_params['name']; ?>'].addStyle('title2');" style="font-size: 14px;" class="h2_btn">
										<input type="button" value="Paragraphe" onclick="myWysiwygs['<?php echo $wysiwyg_params['name']; ?>'].addStyle('paragraph');">
										<input type="button" value="Liste pucée" onclick="if (this.value!=0) { myWysiwygs['<?php echo $wysiwyg_params['name']; ?>'].addStyle('dotlist'); }">		
										<input type="button" value="Liste numérique" onclick="if (this.value!=0) { myWysiwygs['<?php echo $wysiwyg_params['name']; ?>'].addStyle('numlist'); }">	
									</td>
								</tr>
								<tr>
									<td width="25%">
										<b>Forme :</b>
									</td>
									<td>
										<input type="button" value="Gras" onclick="myWysiwygs['<?php echo $wysiwyg_params['name']; ?>'].addStyle('bold');" style="font-weight: bold;" class="b_btn">
										<input type="button" value="Italique" onclick="myWysiwygs['<?php echo $wysiwyg_params['name']; ?>'].addStyle('italic');" style="font-style: italic;" class="i_btn">
										<input type="button" value="Souligné" onclick="myWysiwygs['<?php echo $wysiwyg_params['name']; ?>'].addStyle('underlined');" style="font-style: underline;" class="u_btn">
										<input type="button" value="Petit" onclick="if (this.value!=0) { myWysiwygs['<?php echo $wysiwyg_params['name']; ?>'].addStyle('small'); }" style="font-size: x-small;" class="small_btn">
									</td>
								</tr>
								<tr>
									<td width="25%">
										<b>Couleur :</b>
									</td>
									<td>
										<input type="button" value="Bleu" onClick="myWysiwygs['<?php echo $wysiwyg_params['name']; ?>'].addStyle('blue');" class="blue">
										<input type="button" value="Rouge" onClick="myWysiwygs['<?php echo $wysiwyg_params['name']; ?>'].addStyle('red');" class="red">
										<input type="button" value="Vert" onClick="myWysiwygs['<?php echo $wysiwyg_params['name']; ?>'].addStyle('green');" class="green">
										<input type="button" value="Gris" onClick="myWysiwygs['<?php echo $wysiwyg_params['name']; ?>'].addStyle('gray');" class="gray">
									</td>
								</tr>	
								<tr>
									<td width="25%">
										<b>Alignement :</b>
									</td>
									<td>
										<input type="button" value="Gauche" onClick="myWysiwygs['<?php echo $wysiwyg_params['name']; ?>'].addStyle('left');" style="text-align:left;">
										<input type="button" value="Centre" onClick="myWysiwygs['<?php echo $wysiwyg_params['name']; ?>'].addStyle('center');" style="text-align:center;">
										<input type="button" value="Droit" onClick="myWysiwygs['<?php echo $wysiwyg_params['name']; ?>'].addStyle('right');" style="text-align:right;">
										<input type="button" value="Justifié" onClick="myWysiwygs['<?php echo $wysiwyg_params['name']; ?>'].addStyle('justify');" style="text-align:justify;">
									</td>
								</tr>
								<?php
								if (!isset($simple_form)) { $simple_form=false; }
								if ($simple_form!=true) {
								?>
								<tr>
									<td width="25%">
										<b>Zone spéciale :</b>
									</td>
									<td>
										<input type="button" value="Zone pour code HTML" onclick="myWysiwygs['<?php echo $wysiwyg_params['name']; ?>'].addStyle('HTML');">
										<input type="button" value="Zone sans retour de ligne" onclick="myWysiwygs['<?php echo $wysiwyg_params['name']; ?>'].addStyle('NO-RETURN');">
									</td>
								</tr>
								<tr>
									<td width="25%">
										<b>Ajouter un lien :</b>
									</td>
									<td>
										<div class="sub_form">
											<label for="<?php echo $wysiwyg_params['name']; ?>_link">Vers une page du site</label>
											<select id="<?php echo $wysiwyg_params['name']; ?>_link" onchange="javascript:if (this.value!=0) { myWysiwygs['<?php echo $wysiwyg_params['name']; ?>'].addStyle('link=\''+(this.value)+'\''); this.value = 0; }">
												<option value="0">Liste des pages</option>
												<?php
													$rq = "SELECT reference, id FROM ".eParams::$prefix.'_'.$temp_lang."_cms_pages ORDER BY reference ASC;";
													$result_datas = eMain::$sql->sql_to_array($rq);
													$nb_pages = $result_datas['nb'];;
													for($i=0;$i<$nb_pages;$i++) {
														$content_page = $result_datas['datas'][$i];
														echo "
												<option value=\"".$content_page['reference']."\" >".$content_page['reference']."</option>
														";
													}
												?>
											</select>
										</div>
										
										<div class="sub_form">
											<label for="<?php echo $wysiwyg_params['name']; ?>_file">Vers un fichier uploadé</label>											
											<select id="<?php echo $wysiwyg_params['name']; ?>_file" name="file" value="" onchange="myWysiwygs['<?php echo $wysiwyg_params['name']; ?>'].addStyle('url'+'=\'download/'+this.value+'\''); this.value='';">
												<option value="">Liste des fichiers</option>
												<?php
												
												$list_files = eFile::explore_folder('../uploaded_files', 'file');
												
												for ($i=0;$i<count($list_files);$i++) {
												?>
												<option><?php echo $list_files[$i]; ?></option>
												<?php											
												} // END FOR FILES											
												?>
											</select>
										</div>

										<div class="sub_form">
											<label for="<?php echo $wysiwyg_params['name']; ?>_url">Lien externe</label>
											<input type="text" id="<?php echo $wysiwyg_params['name']; ?>_url" name="url" value="" class="no_enter_submit" /> <input type="button" value="Ajouter ce lien" onclick="myWysiwygs['<?php echo $wysiwyg_params['name']; ?>'].addURL(document.getElementById(myWysiwygs['<?php echo $wysiwyg_params['name']; ?>'].name+'_url').value);" style="font-style: underline;">
										</div>
									</td>
								</tr>
								<tr>
									<td width="25%">
										<b>Ajouter une image :</b><br />
									</td>
									<td>
										<select id="<?php echo $wysiwyg_params['name']; ?>_addimage" class="custom_select img_select" onchange="javascript:if (this.value!=0) { myWysiwygs['<?php echo $wysiwyg_params['name']; ?>'].addStyle('img='+(this.value)+' /img', NO_CLOSING_TAG); this.value = 0; }">
											<option value="0">Liste des images</option>
											<?php
												$rq = "SELECT * FROM ".eParams::$prefix.'_'.$temp_lang."_cms_images ORDER BY folder ASC, name ASC;";
												$result_datas = eMain::$sql->sql_to_array($rq);
												$nb_images = $result_datas['nb'];
												$img_list = array();
												for($i=0;$i<$nb_images;$i++) {									
													$content_img = $result_datas['datas'][$i];
													if ($content_img['folder']!='') { $content_img['folder'].= '/'; }
													$img_list[] = $content_img;
													echo "
											<option value=\"".$content_img['name']."\" class=\"img_option\" style=\"background-image:url('../images/".$content_img['folder'].$content_img['name'].".".$content_img['extension']."');\">".$content_img['name']."</option>
													";
												}
											?>
										</select>										
										<script type="text/javascript">
											$(document).ready(function() {
												tImgList = new Array();
												<?php
													for($i=0;$i<$nb_images;$i++) {
														$ref = $img_list[$i]['name'];
														$filename = $ref.".".$img_list[$i]['extension'];
														$width = $img_list[$i]['width'];
														$height = $img_list[$i]['height'];
														$description = $img_list[$i]['description'];
														$align = $img_list[$i]['align'];
														$folder = $img_list[$i]['folder'];
												?>
												tImgList['<?php echo $ref; ?>'] = new Array();
												tImgList['<?php echo $ref; ?>']['filename'] = '<?php echo $filename; ?>';
												tImgList['<?php echo $ref; ?>']['width'] = '<?php echo $width; ?>';
												tImgList['<?php echo $ref; ?>']['height'] = '<?php echo $height; ?>';
												tImgList['<?php echo $ref; ?>']['description'] = '<?php echo addslashes($description); ?>';
												tImgList['<?php echo $ref; ?>']['align'] = '<?php echo $align; ?>';
												tImgList['<?php echo $ref; ?>']['folder'] = '<?php echo $folder; ?>';
												<?php
													} // END OF FOR img_list
												?>
																								
												myWysiwygs['<?php echo $wysiwyg_params['name']; ?>'].imgList = tImgList;
																								
											});
										</script>										
										<input type="button" onclick="ePopup.createPopup('addimg', {'url':'index.php?p=images&addmod=0', 'class':'large addimg'});" value="Ajouter une nouvelle image" />
										
									</td>
								</tr>
								<?php
								if ($page=="pages" || $page=='params' || $page=='blocks') {
									
									if ($page=='params') {
										$values[$temp_lang]['reference'] = '*';
									}
									
									$rq = "SELECT * FROM ".eParams::$prefix.'_'.$temp_lang."_cms_blocks WHERE content_block=1 ORDER BY position;";
									if ($page!='blocks') { $rq = str_replace('WHERE', "WHERE (pages_ref='*' OR pages_ref LIKE '%*".$values[$temp_lang]['reference']."*%' OR sections_ref='*' OR sections_ref LIKE '%*".$values[$temp_lang]['reference']."*%') AND", $rq); }
									
									$blocks_datas = eMain::$sql->sql_to_array($rq);
								
									if ($blocks_datas['nb']>0) {
									?>
									<tr>
										<td width="25%">
											<b>Ajouter un block :</b><br />
										</td>				
										<td>
											<select id="addblock" onchange="javascript:if (this.value!=0) { myWysiwygs['<?php echo $wysiwyg_params['name']; ?>'].addStyle('block='+(this.value)+' /block', NO_CLOSING_TAG); this.value = 0; }">
												<option value="0">Liste des blocks</option>
												<?php												
													for ($m=0;$m<$blocks_datas['nb'];$m++) {
												?>
												<option value="<?php echo $blocks_datas['datas'][$m]['reference']; ?>"><?php echo $blocks_datas['datas'][$m]['reference']; ?></option>
												<?php
													} // END FOR MODULES
												?>
											</select>
										</td>
									</tr>
									<?php } // END IF CONTENT BLOCKS
								
								}
								
								// MODULES //
								$allowed_modules = array();	
								for ($m=0;$m<eCMS::$modules['nb'];$m++) {									
									if (strpos(eCMS::$modules['datas'][$m]['places'], $page)!==false) {
										$allowed_modules[eCMS::$modules['datas'][$m]['reference']] = eCMS::$modules['datas'][$m];
									}
								}									
								$keys = array_keys($allowed_modules);
								if (count($keys)>0) {
								?>
								<tr>
									<td width="25%">
										<b>Ajouter un module :</b><br />
									</td>				
									<td>
										<select id="addmodule" onchange="javascript:if (this.value!=0) { myWysiwygs['<?php echo $wysiwyg_params['name']; ?>'].addStyle('module='+(this.value)+' /module', NO_CLOSING_TAG); this.value = 0; }">
											<option value="0">Liste des modules</option>
											<?php												
												for ($m=0;$m<count($allowed_modules);$m++) {
											?>
											<option value="<?php echo $keys[$m]; ?>"><?php echo $allowed_modules[$keys[$m]]['name']; ?></option>
											<?php
												} // END FOR MODULES
											?>
										</select>
									</td>
								</tr>
								<?php } // END IF MODULES ?>
								<?php } //END OF IF NOT SIMPLE ?>
							</table>
														
							<div class="clear"> </div>			
							<!--&nbsp; &gt;&gt; <a href="addmod.php?p=images" target="_blank">Ajouter une image Ã  la liste</a>-->						
						</div>
					</div><!-- END OF WYSIWYG CONTAINER -->