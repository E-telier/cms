<?php
	if ($page=='mailings') {
	
		$table = eParams::$prefix."_back_mailings";
	
		$addmod = $_GET['addmod'];	
		if (isset($_POST['id'])) {
			$addmod = $_POST['id'];	
		}

		// SUBMIT FORM //
		if (isset($_POST['newsletter_ref'])) {
			
			for($a=0;$a<$_POST['nb_recipients'];$a++) {
				if (isset($_POST['del_recipient_'.$a])) {
					// DELETE ATTACHMENT //
					$_POST['id_recipients'] = str_replace("*".$_POST['del_recipient_'.$a]."*", "*", $_POST['id_recipients']);
				}
			}
					
			if (!empty($_POST['add_recipients'])) {
				// ADD RECEIPIENTS //
				
				$conditions = "";	

				//print_r($_POST);

				$conditions_type = "";			
				if (isset($_POST['paying']) && $_POST['paying']!=='') { if (!empty($conditions)) { $conditions_type.= " AND"; } $conditions_type .= " paying=\"".$_POST['paying']."\" "; }
				$conditions .= $conditions_type;
				//echo '-'.empty('0').'-'.$conditions_type.'-';

				$conditions_type = "";			
				if (!empty($_POST['sex'])) { if (!empty($conditions)) { $conditions_type.= " AND"; } $conditions_type .= " sex=\"".$_POST['sex']."\" "; }
				$conditions .= $conditions_type;
							
				$conditions_country = "";			
				if (!empty($_POST['country'])) { if (!empty($conditions)) { $conditions_country.= " AND"; } $conditions_country .= " country=\"".$_POST['country']."\" "; }
				$conditions .= $conditions_country;
				
				$conditions_lang = "";
				for ($i=0;$i<count(eParams::$available_languages);$i++) {
					$this_lang = eParams::$available_languages[$i];
					if (isset($_POST[$this_lang])) { 
						if ($conditions_lang!="") { $conditions_lang .= "OR"; } 
						$conditions_lang .=  " languages LIKE \"%".$this_lang."%\" "; 
					}
				}					
				if ($conditions_lang != "") { 			
					$conditions_lang = "(".$conditions_lang.")"; 
					if (!empty($conditions)) { $conditions_lang = " AND ".$conditions_lang; }
					$conditions .= $conditions_lang;
				}				
				
				$conditions_type = "";			
				if (!empty($_POST['type'])) { if (!empty($conditions)) { $conditions_type.= " AND"; } $conditions_type .= " type=\"".$_POST['type']."\" "; }
				$conditions .= $conditions_type;
				
				$conditions_tax = "";			
				if (!empty($_POST['tax'])) { 
					if (!empty($conditions)) { $conditions_tax.= " AND"; }
					
					if ($_POST['tax']=='oui') {
						$conditions_tax .= " tax<>\"non\" ";
					} else {
						$conditions_tax .= " tax=\"".$_POST['tax']."\" ";
					}
				}
				$conditions .= $conditions_tax;
				
				$conditions_ceo = "";			
				if (isset($_POST['ceo'])) { if (!empty($conditions)) { $conditions_ceo.= " AND"; } $conditions_ceo .= " ceo=\"1\" "; }
				$conditions .= $conditions_ceo;
				
				if (!empty($conditions)) { $conditions = " AND ".$conditions; }
				
				if (!empty($_POST['client'])) {
					$conditions = " AND id=".$_POST['client'];
				} 
				
				$conditions = "WHERE activated=1".$conditions; 
								
				// Get new recipients //
				$rq_recipients = "SELECT id FROM ".eParams::$prefix."_back_clients ".$conditions." ORDER BY lastname ASC, firstname ASC, email ASC;";	
				echo $rq_recipients;				
				$recipients_datas = eMain::$sql->sql_to_array($rq_recipients);		
				$nb_recipients = $recipients_datas['nb'];
				$recipients = array();
				for ($r=0;$r<$nb_recipients;$r++) {
					$content_r = $recipients_datas['datas'][$r];
					array_push($recipients, intval($content_r['id']));
				}
				
				// Get old recipients //
				$id_recipients = explode("*", $_POST['id_recipients']);
				$nb_recipients_in = count($id_recipients);							
				for ($a=0;$a<$nb_recipients_in;$a++) {
					if (empty($id_recipients[$a])) { array_splice($id_recipients, $a, 1); $nb_recipients_in--; $a--; }
					else { $id_recipients[$a]= intval($id_recipients[$a]); }
				}
						
				// Merge old and new //
				$id_recipients = array_diff($recipients, $id_recipients);	
				
				//sort($id_recipients);
										
				// Create String List //			
				$nb_recipients_in = count($id_recipients);
				//echo $nb_recipients_in;
				//print_r($id_recipients);
				//die('test');
				for ($r=0;$r<$nb_recipients_in;$r++) {
					if ($r==0 && empty($_POST['id_recipients'])) { $_POST['id_recipients'] .= '*'; }
					if (!isset($id_recipients[$r])) { continue; }
					$_POST['id_recipients'] .= $id_recipients[$r]."*";				
				}
							
			}
					
			$lang_str = '*';
			if (!isset($_POST['lang_all'])) {
			
				for ($l=0;$l<$nb_lang;$l++) {
					$temp_lang = eParams::$available_languages[$l];
					if (isset($_POST['lang_'.$temp_lang])) {
						$lang_str.=$temp_lang.'*';
					}
				}
			}
																
			if ($addmod>0) {
				$rq = "UPDATE $table SET 
					newsletter_ref='".eMain::$sql->protect_sql($_POST['newsletter_ref'])."', 
					languages='".eMain::$sql->protect_sql($lang_str)."', 
					id_recipients='".eMain::$sql->protect_sql($_POST['id_recipients'])."', 
					status='modified' 
				WHERE id=".intval($addmod).";";					
			} else {
				$rq = "INSERT INTO $table (newsletter_ref, languages, id_recipients, date_creation) VALUES (
					'".eMain::$sql->protect_sql($_POST['newsletter_ref'])."', 
					'".eMain::$sql->protect_sql($lang_str)."', 
					'".eMain::$sql->protect_sql($_POST['id_recipients'])."', 
					'".date('Y-m-d')."'
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
		
			$rq = "SELECT * FROM $table WHERE id=".intval($addmod)." LIMIT 1";
			$result_datas = eMain::$sql->sql_to_array($rq);
			$content = $result_datas['datas'][0];
		
			$values=$content;

			$title = "Modification du mailing créé le \"".$values['date_creation']."\"";

		} else {
			
			$values['newsletter_ref']="";
			$values['id_recipients']="";
			$values['languages']="*";

			$title = "Ajout d'un mailing";
		}
						
?>
	<h1><?php echo eText::style_to_html($title); ?></h1>
	<div id="addmod">	
		
		<form name="add" id="form_addmod" class="addmod" method="post" action="" enctype="multipart/form-data">
			<div class="group">
			<h2>Données de l'envoi</h2>	
			<table>
				<tr>
					<td>Newsletter &agrave; envoyer</td>
					<td>
						<select name="newsletter_ref" id="newsletter_ref" onchange="$('#result_btn').attr('href', 'back/result.php?id='+$('#newsletter_ref').val())">
							<option value="">Sélectionnez une newsletter</option>
<?php							
							$found_news=false;
							for ($l=0;$l<$nb_lang;$l++) {
								$temp_lang = eParams::$available_languages[$l];
?>
								<optgroup label="<?php echo eText::iso_strtoupper($temp_lang); ?>">
<?php							
								$rq = "SELECT id, date, object, global_ref FROM ".eParams::$prefix.'_'.$temp_lang."_back_newsletters ORDER BY date DESC;";

								$result_datas = eMain::$sql->sql_to_array($rq);
								$nb = $result_datas['nb'];
															
								for($n=0;$n<$nb;$n++) {
									$content = $result_datas['datas'][$n];
?>
									<option value="<?php echo $content['global_ref']; ?>" <?php if($values['newsletter_ref']==$content['global_ref'] && $found_news==false) { $found_news=true; echo "selected"; } ?>><?php echo $content['object'].' ('.$content['date'].')'; ?></option>
<?php
								} // END OF FOR NEWSLETTER
?>														
								</optgroup>
<?php
							} // END OF FOR LANG
?>
							
						</select> 					
						<a href="<?php echo eMain::get_requested_folder_url(); ?>plugins/newsletters/preview.php?newsletter_ref=<?php echo $values['newsletter_ref']; ?>" target="_blank" id="result_btn">Visualiser la newsletter sélectionnée</a>
						
					</td>
				</tr>
				<tr>
					<td>Langues d'envoi</td>
					<td>
						<input type="checkbox" id="lang_all" name="lang_all" value="*" onclick="if (this.checked) { $('.lang_check').prop('checked', false); } else { this.checked=true; }" <?php if ($values['languages']=='*') { ?>checked="checked"<?php } ?> /> <label for="lang_all">Automatique</label> &nbsp; &nbsp;- &nbsp; &nbsp;
<?php
						for ($l=0;$l<$nb_lang;$l++) {
							$temp_lang = eParams::$available_languages[$l];
?>
						<input type="checkbox" class="lang_check" id="lang_<?php echo $temp_lang; ?>" name="lang_<?php echo $temp_lang; ?>" value="<?php echo $temp_lang; ?>" onclick="if (this.checked) { $('#lang_all').prop('checked', false); } else if ($('.lang_check:checked').length==0) { $('#lang_all').prop('checked', true); }" <?php if (strpos($values['languages'], $temp_lang)!==false) { ?>checked="checked"<?php } ?> /> <label for="lang_<?php echo $temp_lang; ?>"><?php echo eText::iso_strtoupper($temp_lang); ?></label>
<?php
						}
?>
					</td>
				</tr>
			</table>
			</div>
			<div class="group">
			<h2>Ajout de destinataires</h2>	
			<table>
			<tr>
				<td>Payant</td>
					<td>
						<select name="paying">
							<option value="">Tous</option>
							<option value="1">Oui</option>
							<option value="0">Non</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>Sexe</td>
					<td>
						<select name="sex">
							<option value="">Tous</option>
							<option value="M">Masculin</option>
							<option value="F">Féminin</option>
						</select>
					</td>
				</tr>				
				<tr>
					<td>Langues</td>
					<td>
<?php
					for ($i=0;$i<count(eParams::$available_languages);$i++) {
?>
						<input type="checkbox" name="<?php echo eParams::$available_languages[$i]; ?>" value="on" /> <?php echo eText::iso_strtoupper(eParams::$available_languages[$i]); ?> &nbsp;
<?php
					}
?>
					</td>
				</tr>
				<tr>
					<td>Pays</td>
					<td>
						<select name="country" id="select_country">
							<option value="">Tous</option>
<?php
							$datas_country = eMain::$sql->sql_to_array("SELECT DISTINCT country FROM ".eParams::$prefix."_back_clients ORDER BY country ASC;");
							for ($c=0;$c<$datas_country['nb'];$c++) {
?>	
							<option><?php echo $datas_country['datas'][$c]['country']; ?></option>
<?php
							}
?>
						</select>
					</td>
				</tr>
				<tr>
					<td>Type</td>
					<td>
						<select name="type" id="select_type">
							<option value="">Tous</option>
							<option value="Personne physique">Personnes physiques</option>
							<option value="Société">Sociétés</option>
							<option value="Particlulier">Particluliers</option>
						</select>
					</td>
				</tr>
				<tr id="tr_tax">
					<td>TVA</td>
					<td>
						<select name="tax" id="select_tax">							
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
							<option value="<?php echo $tax_values[$v]; ?>"><?php echo eText::iso_htmlentities($tax_options[$tax_values[$v]]); ?></option>
<?php 
							}
?>							
						</select>
					</td>
				</tr>
				<tr>
					<td>Dirigeant d'entreprise</td>
					<td><input type="checkbox" name="ceo" value="1" size="100" /></td>
				</tr>
				<tr>
					<td>Cas particulier</td>
					<td>
						<select name="client">
							<option value="">Sélectionnez le client à ajouter</option>
	<?php
								$rq_clients = "SELECT firstname, lastname, id, email FROM ".eParams::$prefix."_back_clients WHERE activated=1 ORDER BY lastname ASC, firstname ASC, email ASC;";
								$clients_datas = eMain::$sql->sql_to_array($rq_clients);
								
								for ($c=0;$c<$clients_datas['nb'];$c++) {
									$content_client = $clients_datas['datas'][$c];
	?>	
							<option value="<?php echo $content_client['id']; ?>"><?php echo eText::iso_htmlentities(eText::iso_strtoupper($content_client['lastname']).' '.$content_client['firstname'].' ('.$content_client['email'].')'); ?></option>
	<?php							
								}
	?>
						</select>
					</td>
				</tr>
				<tr>
					<td></td>
					<td>
						<input type="hidden" name="add_recipients" id="add_recipients" value="" />
						<input type="button" class="button" onclick="javascript:$('#add_recipients').val(1); document.forms['add'].submit();" value="Ajouter tous les destinataires correspondants" />
					</td>
				</tr>
			</table>
			</div>
	<?php	

			$id_recipients = explode("*", $values['id_recipients']);
			$nb_recipients_in = count($id_recipients);							
			for ($a=0;$a<$nb_recipients_in;$a++) {
				if (empty($id_recipients[$a])) { array_splice($id_recipients, $a, 1); $nb_recipients_in--; $a--; }
				else { $id_recipients[$a]= intval($id_recipients[$a]); }
			}
		
			if ($nb_recipients_in>0) {
	?>
			<div class="group">
				<h2>Destinataires (<?php echo $nb_recipients_in; ?>)</h2>	
				<table cellspacing="0" cellpadding="0">
	<?php
				$deleted_client=false;
				for ($a=0;$a<$nb_recipients_in;$a++) {
					$rq_recipient = "SELECT * FROM ".eParams::$prefix."_back_clients WHERE id=".$id_recipients[$a]." LIMIT 1;";
					$recipient_datas = eMain::$sql->sql_to_array($rq_recipient);
					if ($recipient_datas['nb']==0) {
						$checked = 'checked="checked"';
						$deleted_client=true;
					} else { $checked=""; }
					$content_r = $recipient_datas['datas'][0];
	?>
				<tr>
					<td><?php echo $a+1; ?></td>
					<td><input type="checkbox" name="del_recipient_<?php echo $a; ?>" value="<?php echo $id_recipients[$a]; ?>" <?php echo $checked; ?> /></td>
					<td><?php echo $content_r['lastname']; ?></td>
					<td><?php echo $content_r['firstname']; ?></td>
					<td><?php echo $content_r['email']; ?></td>				
				</tr>
<?php
				} // END OF FOR RECIPIENT 
?>
				</table>
				<div>
					<input type="checkbox" name="select_all" value="1" onchange="let tSelected = this.checked; $(this).closest('.group').find('input[type=checkbox]').prop('checked', tSelected);" /> 
					<input type="button" class="button" onclick="javascript:document.forms['add'].submit();" value="Retirer les destinataires sélectionnées" />
				</div>
<?php 
				if ($deleted_client) { 
				echo "
				<script type=\"text/javascript\">
					$(document).ready(function() {
						document.forms['add'].submit();
					});
				</script>
				"; } 

?>
			</div><!-- END OF GROUP -->
<?php

			} // END OF IF NB_RECIPIENTS_IN
?>
			
			<input type="hidden" name="nb_recipients" value="<?php echo $nb_recipients_in; ?>" />
			<input type="hidden" name="id_recipients" value="<?php echo $values['id_recipients']; ?>" />
			<br />

			<div class="float-right">
				<div class="submit">ENREGISTRER</div>
			</div>
			<div class="clear"> </div>
		</form>
	</div><!-- END OF ADDMOD -->
<?php
	} // END IF ARTICLES
?>		