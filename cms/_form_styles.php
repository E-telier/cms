<?php
	
		$table = eParams::$prefix."_cms_styles";
	
		// Traitement form //
		if (isset($_POST['name'])) {
							
			if ($addmod==0) {
				$rq = "INSERT INTO $table (name, text_font, text_color, text_size, background_color, border_color, border_size, other, activated) 
				VALUES (
					'".eMain::$sql->protect_sql($_POST['name'])."', 
					'".eMain::$sql->protect_sql($_POST['text_font'])."', 
					'".eMain::$sql->protect_sql($_POST['text_color'])."', 
					'".eMain::$sql->protect_sql($_POST['text_size'])."', 
					'".eMain::$sql->protect_sql($_POST['background_color'])."', 
					'".eMain::$sql->protect_sql($_POST['border_color'])."', 
					'".eMain::$sql->protect_sql($_POST['border_size'])."', 
					'".eMain::$sql->protect_sql($_POST['other'])."', 
					".intval($_POST['activated'])."
				);";
			} else {
				$rq = "UPDATE $table SET 
					name='".eMain::$sql->protect_sql($_POST['name'])."', 
					text_font='".eMain::$sql->protect_sql($_POST['text_font'])."', 
					text_color='".eMain::$sql->protect_sql($_POST['text_color'])."', 
					text_size='".eMain::$sql->protect_sql($_POST['text_size'])."', 
					background_color='".eMain::$sql->protect_sql($_POST['background_color'])."', 
					border_color='".eMain::$sql->protect_sql($_POST['border_color'])."', 
					border_size='".eMain::$sql->protect_sql($_POST['border_size'])."', 
					other='".eMain::$sql->protect_sql($_POST['other'])."', 
					activated=".intval($_POST['activated'])." 
				WHERE id=".intval($addmod).";";
			}
			if (!eMain::$sql->sql_query($rq)) {
				echo "<div class=\"error\">ERROR CMS002 : Unable to process the query $rq</div>";
			} else {
				echo '<div class="success">Données correctement enregistrées !</div>';
			}
		}
		
		// Affichage datas //
		if ($addmod==0) {
		
			$content = array();
			$content['name']="";
			$content['text_font']="Arial, Verdana, Geneva";
			$content['text_color']="#000000";
			$content['text_size']="12";
			$content['background_color']="#cccccc";
			$content['border_color']="#ffffff";
			$content['border_size']="1";
			$content['other']="";
			$content['activated']=1;
		
			$title = "Ajout d'un nouveau style";
		
		} else {
		
			$rq = "SELECT * FROM $table WHERE id=$addmod;";
			$result_datas = eMain::$sql->sql_to_array($rq);
			$content = $result_datas['datas'][0];
			
			$title = "Modification du style \"".$content['name']."\"";
					
		}
				
?>
	<h1><?php echo eText::style_to_html($title); ?></h1>	
	<div class="addmod">
	<form method="post" name="add" id="add">
		<div class="property">
			<h3>Nom de l'élément : </h3>
			<input type="text" value="<?php echo $content['name']; ?>" name="name" size="64" />
		</div>	
		
		<div class="property">
			<h3>Police de texte : </h3>
<?php
		$field_name = "text_font";
		$values_list = array("Arial, Verdana, Geneva", "Times New Roman, Times", "Courrier New, Courrier", "Garamond, Helvetica", "inherit");
		for ($v=0;$v<count($values_list);$v++) {
?>
			<input type="radio" id="<?php echo $field_name.'_radio_'.$v; ?>" name="<?php echo $field_name; ?>" value="<?php echo $values_list[$v]; ?>" <?php if ($content[$field_name]==$values_list[$v]) { echo "checked"; } ?> /> <label class="check_label" for="<?php echo $field_name; ?>_radio_<?php echo $v; ?>" style="font-family:<?php echo $values_list[$v]; ?>;"><?php echo $values_list[$v]; ?></label> 
<?php
		}
?>
		</div>

		<div class="property">
			<h3>Couleur du texte : </h3>
<?php
		$field_name = "text_color";
		$values_list = array("#336699", "#333333", "#cccccc", "#000000", "#ffffff", "inherit");
		$names_list = array("blue", "dark gray", "light gray", "black", "white", "inherit");
		for ($v=0;$v<count($values_list);$v++) {
?>
			<input type="radio" id="<?php echo $field_name.'_radio_'.$v; ?>" class="radio_to_input" name="<?php echo $field_name; ?>_radio" value="<?php echo $values_list[$v]; ?>" <?php if ($content[$field_name]==$values_list[$v]) { echo "checked"; } ?> /> <label class="check_label" for="<?php echo $field_name; ?>_radio_<?php echo $v; ?>" style="color:<?php echo $values_list[$v]; ?>;"><?php echo $names_list[$v]; ?></label> 
<?php
		}
?>
			<blockquote>
				<b>Valeur : </b>
				<input type="text" id="<?php echo $field_name; ?>" value="<?php echo $content[$field_name]; ?>" name="<?php echo $field_name; ?>" size="8" /> (exemples : #336699 ou rgb(255,160,140))
			</blockquote>
		</div>

		<div class="property">
			<h3>Taille du texte : </h3>
<?php
		$field_name = "text_size";
		$values_list = array("10", "12", "14", "16", "inherit");
		$names_list = array("small", "normal", "increased", "large", "inherit");
		for ($v=0;$v<count($values_list);$v++) {
?>
			<input type="radio" id="<?php echo $field_name.'_radio_'.$v; ?>" class="radio_to_input" name="<?php echo $field_name; ?>_radio" value="<?php echo $values_list[$v]; ?>" <?php if ($content[$field_name]==$values_list[$v]) { echo "checked"; } ?> /> <label class="check_label" for="<?php echo $field_name; ?>_radio_<?php echo $v; ?>" style="font-size:<?php echo $values_list[$v]; ?>px;"><?php echo $names_list[$v]; ?></label> 
<?php
		}
?>
			<blockquote>
				<b>Valeur : </b>
				<input type="text" id="<?php echo $field_name; ?>" value="<?php echo $content[$field_name]; ?>" name="<?php echo $field_name; ?>" size="8" />
			</blockquote>
		</div>


		<div class="property">
			<h3>Couleur du fond : </h3>
<?php
		$field_name = "background_color";
		$values_list = array("#336699", "#333333", "#cccccc", "#000000", "#339966", "#993366", "#ffffff", "inherit");
		$names_list = array("blue", "dark gray", "light gray", "black", "green", "red", "white", "inherit");
		for ($v=0;$v<count($values_list);$v++) {
?>
			<input type="radio" id="<?php echo $field_name.'_radio_'.$v; ?>" class="radio_to_input" name="<?php echo $field_name; ?>_radio" value="<?php echo $values_list[$v]; ?>" <?php if ($content[$field_name]==$values_list[$v]) { echo "checked"; } ?> /> <label class="check_label" for="<?php echo $field_name; ?>_radio_<?php echo $v; ?>" style="background-color:<?php echo $values_list[$v]; ?>;"><?php echo $names_list[$v]; ?></label> 
<?php
		}
?>
			<blockquote>
				<b>Valeur : </b>
				<input type="text" id="<?php echo $field_name; ?>" value="<?php echo $content[$field_name]; ?>" name="<?php echo $field_name; ?>" size="8" /> (exemples : #336699 ou rgb(255,160,140))
			</blockquote>
		</div>


		<div class="property">
			<h3>Taille de la bordure (0 = aucune) : </h3>
<?php
		$field_name = "border_size";
		$values_list = array("1", "2", "3", "0", "inherit");
		$names_list = array("1 pixel", "2 pixels", "3 pixels", "none", "inherit");
		for ($v=0;$v<count($values_list);$v++) {
?>
			<input type="radio" id="<?php echo $field_name.'_radio_'.$v; ?>" class="radio_to_input" name="<?php echo $field_name; ?>_radio" value="<?php echo $values_list[$v]; ?>" <?php if ($content[$field_name]==$values_list[$v]) { echo "checked"; } ?> /> <label class="check_label" for="<?php echo $field_name; ?>_radio_<?php echo $v; ?>" style="border:<?php echo $values_list[$v]; ?>px solid #000;"><?php echo $names_list[$v]; ?></label> 
<?php
		}
?>
			<blockquote>
				<b>Valeur : </b>
				<input type="text" id="<?php echo $field_name; ?>" value="<?php echo $content[$field_name]; ?>" name="<?php echo $field_name; ?>" size="8" />
			</blockquote>
		</div>

		<div class="property">
			<h3>Couleur de la bordure : </h3>
<?php
		$field_name = "border_color";
		$values_list = array("#336699", "#333333", "#cccccc", "#000000", "#ffffff", "inherit");
		$names_list = array("blue", "dark gray", "light gray", "black", "white", "inherit");
		for ($v=0;$v<count($values_list);$v++) {
?>
			<input type="radio" id="<?php echo $field_name.'_radio_'.$v; ?>" class="radio_to_input" name="<?php echo $field_name; ?>_radio" value="<?php echo $values_list[$v]; ?>" <?php if ($content[$field_name]==$values_list[$v]) { echo "checked"; } ?> /> <label class="check_label" for="<?php echo $field_name; ?>_radio_<?php echo $v; ?>" style="border:3px solid <?php echo $values_list[$v]; ?>;"><?php echo $names_list[$v]; ?></label> 
<?php
		}
?>
			<blockquote>
				<b>Valeur : </b>
				<input type="text" id="<?php echo $field_name; ?>" value="<?php echo $content[$field_name]; ?>" name="<?php echo $field_name; ?>" size="8" /> (exemples : #336699 ou rgb(255,160,140))
			</blockquote>
		</div>
		
		
		<div class="property">
			<h3>Activation : </h3>
			<select name="activated">
				<option value="0" <?php if ($content['activated']==0) { echo "selected"; } ?> >0 : Non</option>
				<option value="1" <?php if ($content['activated']==1) { echo "selected"; } ?> >1 : Oui</option>
			</select>
		</div>
		
		<div class="property">
			<h3>Autres valeurs : (langage CSS, réservé aux professionnels)</h3>
			<div class="group">
				<textarea name="other" cols="96" rows="10" id="form-content"><?php echo $content['other']; ?></textarea>			
			</div>
		</div>
		
		<div class="align_right">
			<div class="submit">ENREGISTRER</div>
		</div>
		
	</form>
	</div> <!--END OF ADDMOD -->

	<script type="text/javascript">
		$(document).ready(function() {
			$('.radio_to_input').change(function() {
				let tDOM = $(this)[0];
				let tID = tDOM.name.replace('_radio', '');
				document.getElementById(tID).value = tDOM.value;
			});
		});		
	</script>