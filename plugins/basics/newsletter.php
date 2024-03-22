<div class="form_newsletter">

	<?php
					
		if (isset($_GET['unsubscribe']) && preg_match('/[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+/', $_GET['unsubscribe']) !== 0) {
			$rq = "UPDATE ".eParams::$prefix."_back_clients SET activated=0 WHERE email='".eMain::$sql->protect_sql($_GET['unsubscribe'])."';";
			if (!eMain::$sql->sql_query($rq)) { eMain::add_error('UPDATE failed'); } 
			else { 
				echo eText::style_to_html(eLang::translate('successfully unsubscribed', 'ucfirst')); 
				echo '<script type="text/javascript">alert(\''.eLang::translate('you have been successfully unsubscribed from our newsletter', 'ucfirst').'\');</script>';
			}
		}
	
		if (isset($_POST['newsletter_mail']) && preg_match('/[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+/', $_POST['newsletter_mail']) !== 0) {
			$sender = eParams::$sender_email;//"contact@e-telier.be";
			$titre = $site_name." : inscription";
			$contenu = eLang::translate('subscription on the website', 'ucfirst')." : ".$_POST['newsletter_mail'];
			$destinataires = array("contact@e-telier.be", $_POST['newsletter_mail'], eParams::$contact_email);//, "info@marieklein.be");
			if (eMail::send_mail($sender, $titre, $contenu, $destinataires)>=0) { 
				//echo eText::style_to_html(eLang::translate('success']);

				$rq = "SELECT activated FROM ".eParams::$prefix."_back_clients WHERE email=\"".$_POST['newsletter_mail']."\" LIMIT 1;";
				$result_datas = eMain::$sql->sql_to_array($rq);
				if ($result_datas['nb']>0) {
					$contact_datas = $result_datas['datas'][0];
					if ($contact_datas['activated']==1) {				
						echo eText::style_to_html(eLang::translate('email already subscribed', 'ucfirst'));
					} else {
						$rq_subscribe = "UPDATE ".eParams::$prefix."_back_clients SET activated=1 WHERE email='".$_POST['newsletter_mail']."';";						
					}
				} else {
					$rq_subscribe = "INSERT INTO ".eParams::$prefix."_back_clients (email, languages, ceo, tax) VALUES (\"".$_POST['newsletter_mail']."\", \"fr\", 1, \"oui\");";					
				} 
				
				if (isset($rq_subscribe)) {
					if (eMain::$sql->sql_query($rq_subscribe)) {
						echo eText::style_to_html(eLang::translate('successful subscription', 'ucfirst'));
					} else {
						eMain::add_error('rq_subscribe failed');
					}
				}
				
			} else {
				echo "...";
			}
				
		}
			
		$values = array();
		$values['email'] = eLang::translate('your email', 'ucfirst');
			
	?>

	<script type="text/javascript">
		function validateEmail() {

			if (document.forms['form_newsletter'].elements['email'].value!=='') {
				// robot fell into the trap
				alert('Are you a robot?');
				return false;
			}
			
			// email //
			if ($('#newsletter_mail').val().match('[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9-.]+')==null) {					
				$('#newsletter_mail').css({'border':'1px solid #ff0000'});
				alert("<?php echo eLang::translate('incorrect email format', 'ucfirst'); ?>");
				return false;
			}

			return true;
				
		}
		$(document).ready(function() {
			document.getElementById('form_newsletter').addEventListener('submit', function(event) {
				if (!validateEmail()) {
					event.preventDefault(); // Prevent form submission
				}
			});
		});
		
	</script>
	<form id="form_newsletter" action="" method="post">
		<div>
			<h3><?php eLang::show_translate('subscribe to our newsletter'); ?></h3>
			<input type="text" name="newsletter_mail" id="newsletter_mail" value="<?php echo $values['email']; ?>" onfocus="if (this.value=='<?php echo $values['email']; ?>') { this.value=''; }" onblur="if (this.value=='') { this.value='<?php echo $values['email']; ?>'; }" />
			<input type="button" name="send" value="<?php echo eLang::translate('send', 'ucfirst'); ?>" onclick="this.form.submit();" />
			<input type="hidden" value="" name="email" />
		</div>
	</form>
	<div class="clear"> </div>

</div>