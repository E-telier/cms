	<?php
	if ($page=='clients') {
	
		$table = eParams::$prefix."_back_clients";
		$addmod = $_GET['addmod'];
	
		// SUBMIT FORM //
		if (isset($_POST['email'])) {
		
			if (!isset($_POST['ceo'])) { $_POST['ceo'] = 0; }
																
			if ($addmod>0) {
				$rq = "UPDATE $table SET 
					email='".eMain::$sql->protect_sql($_POST['email'])."', 
					lastname='".eMain::$sql->protect_sql($_POST['lastname'])."', 
					firstname='".eMain::$sql->protect_sql($_POST['firstname'])."', 
					sex='".eMain::$sql->protect_sql($_POST['sex'])."', 
					type='".eMain::$sql->protect_sql($_POST['type'])."', 
					tax='".eMain::$sql->protect_sql($_POST['tax'])."', 
					ceo='".intval($_POST['ceo'])."', 
					country='".eMain::$sql->protect_sql($_POST['country'])."', 
					languages='".eMain::$sql->protect_sql($_POST['language'])."', 
					activated='".intval($_POST['activated'])."' 
				WHERE id=".intval($_GET['addmod']).";";					
			} else {
				$rq = "INSERT INTO $table (email, lastname, firstname, sex, type, tax, ceo, country, languages, activated) VALUES (
					'".eMain::$sql->protect_sql($_POST['email'])."', 
					'".eMain::$sql->protect_sql($_POST['lastname'])."', 
					'".eMain::$sql->protect_sql($_POST['firstname'])."', 
					'".eMain::$sql->protect_sql($_POST['sex'])."', 
					'".eMain::$sql->protect_sql($_POST['type'])."', 
					'".eMain::$sql->protect_sql($_POST['tax'])."', 
					'".intval($_POST['ceo'])."', 
					'".eMain::$sql->protect_sql($_POST['country'])."', 
					'".eMain::$sql->protect_sql($_POST['language'])."', 
					'".intval($_POST['activated'])."'
				)";
			}
						
			if (!eMain::$sql->sql_query($rq)) {
				eMain::add_error('SQL request failed');
			} else {
				echo '<div class="success">'.eText::iso_htmlentities(eLang::translate('data successfully saved!', 'ucfirst')).'</div>';
			}
			
		}
				
		// LOAD DATAS //
		if ($addmod>0) {
		
			$rq = "SELECT * FROM $table WHERE id=".intval($_GET['addmod']);
			$result_datas = eMain::$sql->sql_to_array($rq);
			$content = $result_datas['datas'][0];
		
			$values=$content;
		} else {
			
			$values['email']="";
			$values['lastname']="";				
			$values['firstname']="";
			$values['type']="";
			$values['tax']="";
			$values['ceo']="";
			$values['languages']="";
			$values['activated']=1;
			$values['country']="";
			
			$values['sex']="M";
		}
		
		//print_r($values);
		
				
	?>
	
	<h1>Données du contact</h1>	
	<div id="addmod">	
		
		<form name="add" id="form_addmod" class="addmod" method="post" action="" enctype="multipart/form-data">
			
			<table>
				<tr>
					<td>Adresse e-mail</td>
					<td><input type="text" class="input_text" name="email" value="<?php echo $values['email']; ?>" size="100" /></td>
				</tr>
				<tr>
					<td>Nom</td>
					<td><input type="text" class="input_text" name="lastname" value="<?php echo $values['lastname']; ?>" size="100" /></td>
				</tr>
				<tr>
					<td>Prénom</td>
					<td><input type="text" class="input_text" name="firstname" value="<?php echo $values['firstname']; ?>" size="100" /></td>
				</tr>
				<tr>
					<td>Sexe</td>
					<td>
						<input type="radio" name="sex" value="M" id="sex_M" <?php if ($values['sex']=='M') { echo "checked"; } ?> /> <label for="sex_M">Masculin</label>
						&nbsp;
						<input type="radio" name="sex" value="F" id="sex_F" <?php if ($values['sex']=='F') { echo "checked"; } ?> /> <label for="sex_F">Féminin</label>
					</td>
				</tr>
	
				<tr>
					<td>Langue</td>
					<td><input type="text" class="input_text" name="language" value="<?php echo $values['languages']; ?>" size="100" /> (FR, NL, EN, ...)</td>
				</tr>
				<tr>
					<td>Pays</td>
					<td><input type="text" class="input_text" name="country" value="<?php echo $values['country']; ?>" size="100" /> (Belgium, France, ...)</td>
				</tr>
				<tr>
					<td>Type</td>
					<td>
						<select name="type" id="select_type" class="input_text">
							<option value="">Non précisé</option>
							<option value="Personne physique" <?php if ($values['type']=="Personne physique") { echo "selected"; } ?>>Personne physique</option>
							<option value="Société" <?php if ($values['type']=="Société") { echo "selected"; } ?>>Société</option>
							<option value="Particulier" <?php if ($values['type']=="Particulier") { echo "selected"; } ?>>Particulier</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>TVA</td>
					<td>
						<select name="tax" id="select_tax" class="input_text">							
							<option value="">Non précisé</option>
<?php
							$tax_options = array(
								'no'=>'Non assujetti',
								'yes'=>'Assujetti',
								'franchise'=>'Franchisé',
								'monthly'=>'Assujetti mensuelle',
								'quarterly'=>'Assujetti trimestrielle'
							);
							$tax_values = array_keys($tax_options);
							for ($v=0;$v<count($tax_values);$v++) {
?>
							<option value="<?php echo $tax_values[$v]; ?>" <?php if ($values['tax']==$tax_values[$v]) { echo "selected"; } ?>><?php echo eText::iso_htmlentities($tax_options[$tax_values[$v]]); ?></option>
<?php 
							}
?>
							
						</select>
					</td>
				</tr>
				<tr>
					<td>Dirigeant d'entreprise</td>
					<td><input type="checkbox" name="ceo" value="1" size="100" <?php if ($values['ceo']==1) { echo "checked"; } ?> /></td>
				</tr>
				<tr>
					<td>Activation</td>
					<td>
						<select name="activated">
							<option value="1" <?php if($values['activated']==1) { echo "selected"; } ?>>Oui</option>
							<option value="0" <?php if($values['activated']==0) { echo "selected"; } ?>>Non</option>
						</select>
					</td>
				</tr>
					
			</table>
			<br />

			<div class="float-right">
				<div class="submit" onclick="eForm.submit(this);">ENREGISTRER</div>
			</div>
			<div class="clear"> </div>
		</form>
	</div><!-- END OF RESULT -->
<?php
	} // END IF ARTICLES
?>		