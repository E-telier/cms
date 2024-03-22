<?php
		$table = eParams::$prefix."_cms_files";

		$root_folder = '../uploaded_files/';
			
		// Traitement form //		
		if (isset($_POST['new_file'])) {
			
			$error = false;

			$filename = eText::str_to_url($_POST['filename']);
			$folder_name = $_POST['folder'];
			if (!empty($_POST['new_folder'])) { $folder_name = eText::str_to_url($_POST['new_folder']); }	
			$folder_path = $folder_name;		
			if (!empty($folder_name)) { $folder_path .= '/'; }

			$weight = $_POST['weight'];
			$extension = $_POST['extension'];

			if (isset($_FILES['filedatas']) && !empty($_FILES['filedatas']['name'])) {
				$file_datas = eFile::upload_file($_FILES['filedatas'], array('folder'=>$root_folder.$folder_path, 'filename'=>$filename));
					
				if (!empty($file_datas['error'])) { 
					
					$error = eText::style_to_html("[h2]ERROR[/h2]");
					
					if ($file_datas['error']=='type') {
						$error .= 'file type is forbidden';
					}
					else if ($file_datas['error']=='size') {
						$error .= 'file is too large';
					}
					else if ($file_datas['error']=='upload') {
						$error .= 'upload failed';
					}	
					
				} else {

					$filename = $file_datas['filename'];
					$weight = $file_datas['filesize'];

					$last_dot = strrpos($filename, '.');
					if ($last_dot!==false) {
						$extension = substr($filename, $last_dot);
						$filename = substr($filename, 0, $last_dot);						
					}

				}
			} else if ($addmod==0) {
				$error = 'ERROR : no file uploaded';
			} 

			if ($error===false) {
				if ($addmod>0 && ($folder_name!=$_POST['former_folder'] || $filename!=$_POST['former_filename'])) {

					if (isset($file_datas)) {
						// delete former after upload
						unlink($root_folder.$_POST['former_folder'].'/'.$_POST['former_filename'].$_POST['former_extension']);
					} else {
						// move and rename former
						if (is_dir($root_folder.$folder_path)===false) {
							mkdir($root_folder.$folder_path, 0755, true);
						}

						rename($root_folder.$_POST['former_folder'].'/'.$_POST['former_filename'].$_POST['former_extension'], $root_folder.$folder_path.$filename.$_POST['former_extension']);

					}

					// DELETE UNUSED FOLDER //	
					if (!empty($_POST['former_folder'])) {				
						if (is_dir($root_folder.$_POST['former_folder'])) {
							if (eFile::is_dir_empty($root_folder.$_POST['former_folder'])) {
								rmdir($root_folder.$_POST['former_folder']);
							}
						}	
					}			

				}				
			}

			
			
			if ($error===false) {
				if ($addmod==0) {
					$rq = "INSERT INTO $table (folder, filename, extension, weight, upload_date) 
					VALUES ('".eMain::$sql->protect_sql($folder_name)."', 
					'".eMain::$sql->protect_sql($filename)."', 
					'".eMain::$sql->protect_sql($extension)."', 
					'".eMain::$sql->protect_sql($weight)."', 
					'".date('Y-m-d H:i:s')."')";
				
				} else {
					$rq = "UPDATE $table SET 
					folder='".eMain::$sql->protect_sql($folder_name)."', 
					filename='".eMain::$sql->protect_sql($filename)."', 
					extension='".eMain::$sql->protect_sql($extension)."', 
					weight='".eMain::$sql->protect_sql($weight)."', 
					upload_date='".date('Y-m-d H:i:s')."'			
					WHERE id=".intval($addmod).";";
				}
							
				if (!eMain::$sql->sql_query($rq)) {
					$error = eLang::translate("unable to process the query")."<br />$rq<br />".eMain::$sql->get_error();
				} else {
					echo '<div class="success">Données correctement enregistrées !</div>';
				}
			}
			
			if ($error!==false) {
				echo "<div class=\"error\">".$error."</div>";
			}
		}
		
		// Affichage datas //
		if ($addmod==0) {
		
			$content = array();
			$content['folder']="";
			$content['filename']="";
			$content['extension']="";
			$content['weight']=0;
						
			$params = array(
				'color'=>'#ff0000'
			);
			
			$title = "Ajout d'un nouveau fichier";
		
		} else {
		
			$rq = "SELECT * FROM $table WHERE id=$addmod;";
			$result_datas = eMain::$sql->sql_to_array($rq);
			$content = $result_datas['datas'][0];
						
			$title = "Modification du fichier \"".$content['filename']."\"";
					
		}
				
?>
	<h1><?php echo eText::style_to_html($title); ?></h1>
	<div class="addmod">
	<form method="post" name="add" id="add" enctype="multipart/form-data">
		<table cellspacing="0">
			<tr>
				<td>Fichier : </td>
				<td>
					<input type="file" name="filedatas" value="" />					
				</td>
			</tr>
			<tr>
				<td>Nom de fichier : </b><small>(optionnel)</small></td>
				<td><input type="text" value="<?php echo $content['filename']; ?>" name="filename" size="64" /></td>
			</tr>
			<tr>
				<td>Dossier : </td>
				<td>
					<select name="folder">
						<option value="">root</option>
<?php
		$folders_datas = eMain::$sql->sql_to_array("SELECT DISTINCT folder FROM $table WHERE folder<>'' ORDER BY folder ASC;");
		for($f=0;$f<$folders_datas['nb'];$f++) {
			$selected = '';
			if ($folders_datas['datas'][$f]['folder']==$content['folder']) { $selected = 'selected="selected"';}
?>
						<option <?php echo $selected; ?>><?php echo eText::iso_htmlentities($folders_datas['datas'][$f]['folder']); ?></option>
<?php
		}
?>
					</select>
					<input type="text" name="new_folder" value="" placeholder="new folder name" />
					<input type="hidden" name="old_folder" value="<?php echo $content['folder']; ?>" />
				</td>
			</tr>
			
						
		</table>		
		<div class="float-right">
			<input type="hidden" name="new_file" value="true" />
			<input type="hidden" name="extension" value="<?php echo $content['extension']; ?>" />
			<input type="hidden" name="weight" value="<?php echo $content['weight']; ?>" />
			<input type="hidden" name="former_filename" value="<?php echo $content['filename']; ?>" />
			<input type="hidden" name="former_folder" value="<?php echo $content['folder']; ?>" />
			<input type="hidden" name="former_extension" value="<?php echo $content['extension']; ?>" />
			<div class="submit">ENREGISTRER</div>
		</div>
		<div class="clear"> </div>
		
	</form>
	</div> <!--END OF ADDMOD -->