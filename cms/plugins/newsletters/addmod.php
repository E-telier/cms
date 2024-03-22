	<?php
	if ($page=='newsletters') {
	
		$table = "_back_newsletters";
		
		// Traitement form //
		if (isset($_POST['submit_check'])) {
	
			// FILL EVERY LANGUAGES WITH POSTED IF EMPTY //
			$exceptions = array('id_attachments');
			$post_keys = array_keys($_POST);
			for ($i=0;$i<count($_POST);$i++) {
				$key = $post_keys[$i];
				if (empty($_POST[$key])) {					
					$type_key = substr($key, 3);
					if (array_search($type_key, $exceptions)===false) {
						if (isset($_POST[$_POST['post_lang']."_".$type_key])) {
							$_POST[$key] = $_POST[$_POST['post_lang']."_".$type_key];
						}
					}
				}
			}
						
			$new_global_ref = date('YmdHis');
			for ($l=0;$l<$nb_lang;$l++) {
				$temp_lang = eParams::$available_languages[$l];
																	
				if ($addmod==0) {
					$rq = "INSERT INTO ".eParams::$prefix."_".$temp_lang.$table." (global_ref, date, object, text, id_attachments, selected) VALUES (
					'".eMain::$sql->protect_sql($new_global_ref)."', 
					'".eMain::$sql->protect_sql($_POST[$temp_lang.'_date'])."', 
					'".eMain::$sql->protect_sql($_POST[$temp_lang.'_object'])."', 
					'".eMain::$sql->protect_sql($_POST[$temp_lang.'_text'])."', 
					'".eMain::$sql->protect_sql($_POST[$temp_lang.'_id_attachments'])."', 
					'".intval($_POST[$temp_lang.'_selected'])."')";
				} else {					
					$rq = "UPDATE ".eParams::$prefix.'_'.eMain::$sql->protect_sql($temp_lang).$table." SET date='".eMain::$sql->protect_sql($_POST[$temp_lang.'_date'])."', object='".eMain::$sql->protect_sql($_POST[$temp_lang.'_object'])."', text='".eMain::$sql->protect_sql($_POST[$temp_lang.'_text'])."', id_attachments='".eMain::$sql->protect_sql($_POST[$temp_lang.'_id_attachments'])."', selected='".intval($_POST[$temp_lang.'_selected'])."' WHERE global_ref='".eMain::$sql->protect_sql($addmod)."';";
				}
				if (!eMain::$sql->sql_query($rq)) {
					eMain::add_error('SQL request failed');
				} else {
					echo '<div class="success">'.eText::iso_htmlentities(eLang::translate('data successfully saved!', 'ucfirst')).'</div>';
				}
			} // END FOR LANGUAGES
		}
		
		// Affichage datas //
		$values = array();
		if ($addmod==0) {
		
			$content = array();		
						
			$content['date']=date('Y-m-d');
			$content['object']="";
			$content['text']="";			
			$content['id_attachments']="";
			$content['selected']="0";
						
			for ($l=0;$l<$nb_lang;$l++) {
				$values[eParams::$available_languages[$l]] = $content;
			}
			
			$title = "Ajout d'une newsletter";
		
		} else {
						
			for ($l=0;$l<$nb_lang;$l++) {
				$temp_lang = eParams::$available_languages[$l];
								
				$rq = "SELECT * FROM ".eParams::$prefix."_".$temp_lang.$table." WHERE global_ref=$addmod;";
				
				$result_datas = eMain::$sql->sql_to_array($rq);
				$content = $result_datas['datas'][0];
												
				//if (!get_magic_quotes_gpc()) {				
					$content['object'] = eText::html_quotes($content['object']);
					$content['text'] = eText::html_quotes($content['text']);
				//}
				$values[$temp_lang] = $content;				
			}
						
			$title = "Modification de la newsletter \"".$values[eLang::get_lang()]['object']."\"";
									
		}
		
		$rq_attachments = "SELECT * FROM ".eParams::$prefix."_cms_files ORDER BY folder ASC, filename ASC, extension ASC;";
		$attachments_datas = eMain::$sql->sql_to_array($rq_attachments);
					
?>

	<script type="text/javascript">
		function removeAttachments(tLang) {
			var tIDAttachments = $('#'+tLang+'_id_attachments').val();
			$('#form_'+tLang+' .attachment input:checked').each(function() {
				tIDAttachments = tIDAttachments.replace('*'+$(this).val()+'*', '*');
			}).parent('.attachment').remove();
			
			if (tIDAttachments=='*') { tIDAttachments = ''; }
			
			$('#'+tLang+'_id_attachments').val(tIDAttachments);
		}
		
		function addAttachment(tLang) {
			var tID = $('#'+tLang+'_select_attachment').val();
			var tValues = gAttachments['id_'+tID];
			if (tValues['folder']!='') { tValues['folder'] += '/'; }
			$('#form_'+tLang+' .attachments').css({'display':'block'}).children('input[type="button"]').before('\
				<div class="attachment">\
				<input type="checkbox" name="'+tLang+'_del_attachment_'+tID+'" value="'+tID+'" /> \
				<a href="<?php echo eMain::get_website_root_url(); ?>uploaded_files/'+tValues['folder']+tValues['filename']+tValues['extension']+'" target="_blank">'+tValues['folder']+tValues['filename']+tValues['extension']+'</a>\
				</div>');
				
			$('#'+tLang+'_select_attachment').val('');
			
			var tIDAttachments = $('#'+tLang+'_id_attachments').val();
			if (tIDAttachments=='') { tIDAttachments = '*'; }
			tIDAttachments+=(tID+'*');
			$('#'+tLang+'_id_attachments').val(tIDAttachments);			
		}
		
		var gAttachments = new Object();
		<?php
			for ($a=0;$a<$attachments_datas['nb'];$a++) {
				$content_a = $attachments_datas['datas'][$a];
			?>
		gAttachments['id_<?php echo $content_a['id']; ?>'] = {
			'filename':'<?php echo $content_a['filename']; ?>', 
			'folder':'<?php echo addslashes($content_a['folder']); ?>', 
			'extension':'<?php echo addslashes($content_a['extension']); ?>', 
			'id':<?php echo $content_a['id']; ?>
		}
			<?php
			}
		?>
		
	</script>


	<h1><?php echo eText::style_to_html($title); ?></h1>
	<br />
<?php include('_controls_lang.php'); ?>
	<div class="addmod">
	<form method="post" id="form_addmod" name="add" enctype="multipart/form-data">
<?php
	for ($l=0;$l<$nb_lang;$l++) {
		$temp_lang = eParams::$available_languages[$l];
?>		
	<div id="form_<?php echo $temp_lang; ?>" class="form_lang">
		<table cellspacing="0">
			<tr>				
				<td width="30%">Objet du Mail</td>
				<td><input type="text" class="input_text" name="<?php echo $temp_lang; ?>_object" value="<?php echo $values[$temp_lang]['object']; ?>" size="64" /></td>
			</tr>
			<tr>				
				<td width="30%">Date à afficher</td>
				<td><input type="text" class="input_text" name="<?php echo $temp_lang; ?>_date" value="<?php echo $values[$temp_lang]['date']; ?>" size="64" /></td>
			</tr>
			<tr>				
				<td width="30%">Afficher en news</td>
				<td>
					<input type="radio" name="<?php echo $temp_lang; ?>_selected" value="1" <?php if ($values[$temp_lang]['selected']==1) { ?>checked="checked"<?php } ?> /> Oui
					|
					<input type="radio" name="<?php echo $temp_lang; ?>_selected" value="0" <?php if ($values[$temp_lang]['selected']==0) { ?>checked="checked"<?php } ?> /> Non
				</td>
			</tr>	
						
			<tr>
				<td width="30%">Pièce jointe</td>
				<td>
								
					<select name="<?php echo $temp_lang; ?>_select_attachment" id="<?php echo $temp_lang; ?>_select_attachment"  class="input_text">
						<option value="">Sélectionner une pièce jointe à ajouter</option>
						<?php
						
							$id_attachments = explode("*", $values[$temp_lang]['id_attachments']);							
							$nb_attachments_in = count($id_attachments);							
							for ($a=0;$a<$nb_attachments_in;$a++) {
								if (empty($id_attachments[$a])) { array_splice($id_attachments, $a, 1); $nb_attachments_in--; $a--; }
								else { $id_attachments[$a] = intval($id_attachments[$a]); }
							}

							for ($a=0;$a<$attachments_datas['nb'];$a++) {
								$content_a = $attachments_datas['datas'][$a];
								
								$id_pos = array_search($content_a['id'], $id_attachments);
								if ($id_pos!==false) {
									$id_attachments[$id_pos] = array(
										'id'=>$content_a['id'], 
										'folder'=>$content_a['folder'], 
										'extension'=>$content_a['extension'], 
										'filename'=>$content_a['filename']
									);
								} else {
								
						?>
						<option value="<?php echo $content_a['id']; ?>"><?php echo $content_a['folder']."/".$content_a['filename'].$content_a['extension']; ?></option>
						<?php
								} // END OF IF IN ARRAY
							} // END OF FOR ATTACHMENTS
						?>
					</select>					
					<input type="button" class="button" onclick="addAttachment('<?php echo $temp_lang; ?>');" value="Ajouter" />
									
					<div class="attachments" style="<?php if ($nb_attachments_in==0) { ?>display:none;<?php } ?>">						
						<b>Pièces jointes ajoutées :</b>					
					<?php							
						for ($a=0;$a<$nb_attachments_in;$a++) {	
							
							if (!isset($id_attachments[$a]['id'])) {
								// MISSING FILE //
								$missing_file = true;
								$id = $id_attachments[$a];
								$checked = 'checked="checked"'; 
							} else {	
								$missing_file = false;						
								$checked = ""; 
								$id = $id_attachments[$a]['id']; 
								$folder = $id_attachments[$a]['folder'];														
								if (!empty($folder)) { $folder .= '/'; }
							}
					?>
						<div class="attachment">
							<input type="checkbox" <?php echo $checked; ?> name="<?php echo $temp_lang; ?>_del_attachment_<?php echo $a; ?>" value="<?php echo $id; ?>" /> 
					<?php 
							if ($missing_file) {
								echo 'missing file '.$id_attachments[$a];
							} else {
					?>
							<a href="<?php echo eMain::get_website_root_url(); ?>uploaded_files/<?php echo $folder.$id_attachments[$a]['filename'].$id_attachments[$a]['extension']; ?>" target="_blank">
								<?php echo $folder.$id_attachments[$a]['filename'].$id_attachments[$a]['extension']; ?>
							</a>
					<?php
							}
					?>		
						</div>
					<?php							
						} // END OF FOR ATTACHMENTS								
					?>
					
						<br />
						<input type="button" class="button" onclick="removeAttachments('<?php echo $temp_lang; ?>');" value="Supprimer les pièces jointes sélectionnées" />
					</div> 
					<input type="hidden" value="<?php echo $values[$temp_lang]['id_attachments']; ?>" name="<?php echo $temp_lang; ?>_id_attachments" id="<?php echo $temp_lang; ?>_id_attachments" />
										
				</td>
			</tr>
									
		</table>
		
		<b>Contenu de la newsletter : </b><br />
		<script type="text/javascript">
			$(document).ready(function() {
				$('.wysiwyg').css({'width':'600px', 
					'font-family': 'Arial, Helvetica, sans-serif',
					'text-align': 'left',
					'color': '#010000',
					'font-size': '12px',
					'line-height': '16px',
					'max-height': 'none'
				});
			});
		</script>
		<?php 
			$wysiwyg_params['name'] = $temp_lang."_text";
			$wysiwyg_params['content'] = $values[$temp_lang]['text']; 
			include('content_form.php'); 
		?>
				
		<div class="float-right">
			<div class="submit" onclick="$('#post_lang').val('<?php echo $temp_lang; ?>'); eForm.submit(this);">ENREGISTRER</div>
		</div>
		<div class="clear"> </div>
		
		</div><!-- END OF LANG CONTAINER -->
<?php
	} // END FOR LANGUAGES
?>	
		<input type="hidden" name="submit_check" value="1" />
		<input type="hidden" name="post_lang" id="post_lang" value="default" />
	</form>
	</div> <!--END OF ADDMOD -->
<?php
	} // END IF ARTICLES
?>		