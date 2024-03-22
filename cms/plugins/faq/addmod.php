	<?php
	if ($page=='faq') {
	
		$table = "_back_faq";
	
		$base_wysiwyg_name = "wysiwyg_name"; 
	
		// Traitement form //
		if (isset($_POST['submit_check'])) {
		
			//if (!get_magic_quotes_gpc()) {				
				foreach ($_POST as $post_key => $post_value) {
					$_POST[$post_key] = addslashes($post_value);
				}				
			//}
					
			// FILL EVERY LANGUAGES WITH POSTED IF EMPTY //
			$exceptions = array();
			$post_keys = array_keys($_POST);
			for ($i=0;$i<count($_POST);$i++) {
				$key = $post_keys[$i];
				if (empty($_POST[$key])) {					
					$type_key = substr($key, 3);
					if (array_search($type_key, $exceptions)==false) {
						if (isset($_POST[$_POST['post_lang']."_".$type_key])) {
							$_POST[$key] = $_POST[$_POST['post_lang']."_".$type_key];
						}
					}
				}
			}
						
			$new_global_ref = date('YmdHis');
			for ($l=0;$l<$nb_lang;$l++) {
				$temp_lang = eParams::$available_languages[$l];
				
				if (!isset($_POST[$temp_lang.'_image'])) {
					$_POST[$temp_lang.'_image'] = '';
				}
									
				if ($addmod==0) {
					$rq = "INSERT INTO ".eParams::$prefix."_".$temp_lang.$table." (global_ref, id_category, position, title, content, keywords, image, creation_date, visible) VALUES ('".$new_global_ref."', \"".$_POST[$temp_lang.'_category']."\", \"".$_POST[$temp_lang.'_position']."\", \"".$_POST[$temp_lang.'_title']."\", \"".$_POST[$temp_lang.'_'.$base_wysiwyg_name]."\", \"".$_POST[$temp_lang.'_keywords']."\", \"".$_POST[$temp_lang.'_image']."\", '".date('Y-m-d')."', \"".$_POST[$temp_lang.'_visible']."\");";
				} else {
					$rq = "UPDATE ".eParams::$prefix."_".$temp_lang.$table." SET id_category=\"".$_POST[$temp_lang.'_category']."\", position=\"".$_POST[$temp_lang.'_position']."\", title=\"".$_POST[$temp_lang.'_title']."\", keywords=\"".$_POST[$temp_lang.'_keywords']."\", content=\"".$_POST[$temp_lang.'_'.$base_wysiwyg_name]."\", image='".$_POST[$temp_lang.'_image']."', visible='".$_POST[$temp_lang.'_visible']."' WHERE global_ref='".$addmod."';";
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
			$content['id_category']=1;
			$content['title']="";
			$content['position']="0";
			$content['description']="";
			$content['keywords']="";
			$content['content']="";
			$content['image']='';
			$content['visible']='1';
						
			for ($l=0;$l<$nb_lang;$l++) {
				$values[eParams::$available_languages[$l]] = $content;
			}
			
			$title = "Ajout d'un article";
		
		} else {
						
			for ($l=0;$l<$nb_lang;$l++) {
				$temp_lang = eParams::$available_languages[$l];
								
				$rq = "SELECT * FROM ".eParams::$prefix."_".$temp_lang.$table." WHERE global_ref=$addmod;";
				
				$result_datas = eMain::$sql->sql_to_array($rq);
				$content = $result_datas['datas'][0];
												
				//if (!get_magic_quotes_gpc()) {				
					$content['title'] = eText::html_quotes($content['title']);
					$content['content'] = eText::html_quotes($content['content']);
					$content['keywords'] = eText::html_quotes($content['keywords']);
				//}
				$values[$temp_lang] = $content;
			}
						
			$title = "Modification de l'article \"".$values[eLang::get_lang()]['title']."\"";
			
			
						
		}
					
?>
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
				<td width="30%">Catégorie : <br /><small></small></td>
				<td>
					<select name="<?php echo $temp_lang; ?>_category">
<?php
					$rq = "SELECT id, category FROM ".eParams::$prefix."_".$temp_lang."_back_faq_categories WHERE visible=1 ORDER BY position ASC, category ASC;";
					$result_datas = eMain::$sql->sql_to_array($rq);
					$nb_cat = $result_datas['nb'];;
					for($c=0;$c<$nb_cat;$c++) {
						$content_cat = $result_datas['datas'][$c];
?>
						<option value="<?php echo $content_cat['id']; ?>" <?php if ($values[$temp_lang]['id_category']==$content_cat['id']) { ?>selected="selected"<?php } ?>><?php echo eText::iso_htmlentities($content_cat['category']); ?></option>
<?php
					}
?>
					</select>
					
				</td>
			</tr>			
			<tr>
				<td width="30%">Question : <br /><small>(Vide = pas affiché)</small></td>
				<td>
					<input type="text" value=<?php echo "\"".$values[$temp_lang]['title']."\""; ?> name="<?php echo $temp_lang; ?>_title" size="64" />
				</td>
			</tr>			
			<tr>
				<td>Mots clés de la question : </td>
				<td><input type="text" value=<?php echo "\"".$values[$temp_lang]['keywords']."\""; ?> name="<?php echo $temp_lang; ?>_keywords" size="64" /></td>
			</tr>
			<tr>
				<td width="30%">Position : <br /><small></small></td>
				<td>
					<input type="text" value=<?php echo "\"".$values[$temp_lang]['position']."\""; ?> name="<?php echo $temp_lang; ?>_position" size="3" />
				</td>
			</tr>
			<!--
			<tr>
				<td>Image</td>
				<td>
					<select name="<?php echo $temp_lang; ?>_image">
						<option value="">Liste des images</option>
						<?php
							$rq = "SELECT * FROM ".eParams::$prefix."_".$temp_lang."_cms_images ORDER BY name ASC;";
							$result_datas = eMain::$sql->sql_to_array($rq);
							$nb_images = $result_datas['nb'];;
							$img_list = array();
							for($i=0;$i<$nb_images;$i++) {									
								$content_img = $result_datas['datas'][0];
								$img_list[] = $content_img;
								
								$selected='';
								if ($content_img['name']==$values[$temp_lang]['image']) {
									$current_img = $content_img;
									$selected = 'selected';
								}
								
								echo "
						<option value=\"".$content_img['name']."\" $selected>".$content_img['name']."</option>
								";
								
							}
						?>
					</select>
					<?php 
						if ($addmod!=0 && isset($current_img)) { 	

							if ($current_img['width']>800) {								
								$current_img['width'] = 800;
								$current_img['height'] = 'auto';
							}
						
							echo "<img src=\"../images/".$current_img['name'].".".$current_img['extension']."?d=".date("YmdHis")."\" width=\"".$current_img['width']."\" height=\"".$current_img['height']."\" >"; 
						} 
					?>
		
				</td>
			</tr>
			-->
			<tr>
				<td>Visible : </td>
				<td>
					<select name="<?php echo $temp_lang; ?>_visible">
						<option value="1" <?php if ($content['visible']=='1') { ?>selected="selected"<?php } ?>>Oui</option>
						<option value="0" <?php if ($content['visible']=='0') { ?>selected="selected"<?php } ?>>Non</option>
					</select>
				</td>
			</tr>
						
		</table>
		<br />
		<b>Réponse : </b><br />
		<?php 			
			$wysiwyg_params['content'] = $values[$temp_lang]['content']; 
			$wysiwyg_params['name'] = $temp_lang.'_'.$base_wysiwyg_name;
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