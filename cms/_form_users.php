<?php
		$table = eParams::$prefix."_users";
			
		// Traitement form //
		
		if (isset($_POST['login'])) {
			
			$error = false;
			
			if (!isset($_POST['domain'])) { $_POST['domain'] = '*admin*'; }
							
			$_POST['access_level'] = intval($_POST['access_level']);
			$access_level = eUser::getInstance()->get_datas('access_level');
			if ($access_level['access_level']>$_POST['access_level']) { $error = eLang::translate('incorrect access level'); }
			
			$password = eMain::encrypt($_POST['password']);
			if ($addmod!=0 && $_POST['password']==$_POST['old_password']) {
				$password['encrypted'] = $_POST['old_password'];
				$password['coef'] = $_POST['password_coef'];
			} else if ($_POST['password']!=$_POST['password_conf']) { 
				$error = eLang::translate("incorrect Password Confirmation"); 
			}
			
			$params = json_encode(array('color'=>$_POST['color']));
			
			if (!$error) {				
				if ($addmod==0) {
					$rq = "INSERT INTO $table (firstname, lastname, email, login, password, password_coef, access_level, domain, params) 
					VALUES ('".eMain::$sql->protect_sql($_POST['firstname'])."', '".eMain::$sql->protect_sql($_POST['lastname'])."', '".eMain::$sql->protect_sql($_POST['email'])."', '".eMain::$sql->protect_sql($_POST['login'])."', '".eMain::$sql->protect_sql($password['encrypted'])."', '".eMain::$sql->protect_sql($password['coef'])."', '".eMain::$sql->protect_sql($_POST['access_level'])."', '".eMain::$sql->protect_sql($_POST['domain'])."', '".eMain::$sql->protect_sql($params)."')";
				
				} else {
					$rq = "UPDATE $table SET 
					firstname='".eMain::$sql->protect_sql($_POST['firstname'])."', lastname='".eMain::$sql->protect_sql($_POST['lastname'])."', email='".eMain::$sql->protect_sql($_POST['email'])."', login='".eMain::$sql->protect_sql($_POST['login'])."', 
					password='".eMain::$sql->protect_sql($password['encrypted'])."', password_coef='".eMain::$sql->protect_sql($password['coef'])."', access_level='".eMain::$sql->protect_sql($_POST['access_level'])."', domain='".eMain::$sql->protect_sql($_POST['domain'])."', 
					params='".eMain::$sql->protect_sql($params)."'
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
			$content['firstname']="";
			$content['lastname']="";
			$content['email']="";
			$content['login']="";
			$content['password']="";
			$content['password_coef']="";
			$content['domain']="*admin*";
			$content['access_level']=0;
			
			$params = array(
				'color'=>'#ff0000'
			);
			
			$title = "Ajout d'un nouvel utilisateur";
		
		} else {
		
			$rq = "SELECT * FROM $table WHERE id=$addmod;";
			$result_datas = eMain::$sql->sql_to_array($rq);
			$content = $result_datas['datas'][0];
			
			$params = json_decode($content['params'], true);
			
			$title = "Modification de l'utilisateur \"".$content['login']."\"";
					
		}
				
?>
	<h1><?php echo eText::style_to_html($title); ?></h1>
	<div class="addmod">
	<form method="post" name="add" id="add">
		<table cellspacing="0">
			<tr>
				<td>login : </td>
				<td>
<?php
				if ($content['login']=="admin") {
?>
					admin<input type="hidden" value="admin" name="login" />
<?php
				} else {
?>
					<input type="text" value=<?php echo "\"".$content['login']."\""; ?> name="login" size="64" />
<?php
				} // END IF ADMIN
?>
				</td>
			</tr>
			<tr>
				<td>Mot de passe : </b></td>
				<td><input type="password" value="<?php echo $content['password']; ?>" name="password" size="64" /></td>
			</tr>
			<tr>
				<td>Confirmation mot de passe : </b></td>
				<td><input type="password" value="<?php echo $content['password']; ?>" name="password_conf" size="64" /></td>
			</tr>
			<!--
			<tr>
				<td>Type d'accès : </b></td>
				<td>
<?php
					if ($content['login']=="admin") {
?>
						0 : Complet (Administrateur)<input type="hidden" value="0" name="access_level" />
<?php
					} else {
?>
					<div class="slider">
						<div class="left"><?php echo eLang::translate('high', 'ucfirst'); ?></div>
						<div class="right"><?php echo eLang::translate('low', 'ucfirst'); ?></div>
						<input type="text" name="access_level" value="<?php echo $content['access_level']; ?>" placeholder="0=highest" />
					</div>
<?php
					} // END IF USER
?>
				</td>
			</tr>
			-->
			<input type="hidden" name="access_level" value="<?php echo $content['access_level']; ?>" placeholder="0=highest" />
			<tr>
				<td>Nom : </b></td>
				<td><input type="text" value="<?php echo $content['lastname']; ?>" name="lastname" size="64" /></td>
			</tr>
			<tr>
				<td>Prénom : </b></td>
				<td><input type="text" value="<?php echo $content['firstname']; ?>" name="firstname" size="64" /></td>
			</tr>
			<tr>
				<td>E-mail : </b></td>
				<td><input type="text" value="<?php echo $content['email']; ?>" name="email" size="64" /></td>
			</tr>
			
			<tr>
				<td>Couleur : </b></td>
				<td><span id="color_sample" style="background-color:<?php echo $params['color']; ?>;"> &nbsp; </span> &nbsp; <input type="text" value="<?php echo $params['color']; ?>" name="color" size="7" onchange="document.getElementById('color_sample').style.backgroundColor = this.value;" /> (hexadecimals)</td>
			</tr>
			
			<tr>
				<td>Type d'accès : </b></td>
				<td>
<?php
					if ($content['login']=="admin") {
?>
					<input type="hidden" name="domain" value="*" />Super Admin
<?php
					} else {

						for ($a=0;$a<count(eUser::$access_types);$a++) {
?>
					<input type="radio" <?php if ($content['domain']=="*".eUser::$access_types[$a]."*") { ?>checked="checked"<?php } ?> value="*<?php echo eUser::$access_types[$a]; ?>*" name="domain" id="user_<?php echo eUser::$access_types[$a]; ?>" /> 
					<label for="user_<?php echo eUser::$access_types[$a]; ?>"><?php eLang::show_translate('usertype '.eUser::$access_types[$a]); ?></label>
					<br />
<?php 
						}
					} // END IF USER
?>				
				</td>
			</tr>	
			
			<!-- <input type="hidden" value="<?php echo $content['domain']; ?>" name="domain" size="64" /> -->
		</table>
		<input type="hidden" name="old_password" value="<?php echo $content['password']; ?>" />
		<input type="hidden" name="password_coef" value="<?php echo $content['password_coef']; ?>" />
		<div class="float-right">
			<div class="submit">ENREGISTRER</div>
		</div>
		<div class="clear"> </div>
		
	</form>
	</div> <!--END OF ADDMOD -->

	<script type="text/javascript">
						
		$(document).ready(function() {
			// SLIDERS //
			$('.slider').slider({
				step:1,
				max:10,
				min:0,
							
				slide: function(event, ui) {
					$(this).find('input').val(ui.value);
					$(this).find('.ui-slider-handle').html(ui.value);
				}
			}).each(function() {
				var tValue = $(this).find('input').val();
				$(this).find('.ui-slider-handle').html(tValue);
				
				$(this).slider('value', $(this).find('input').val());
			});
		});
		
	</script>