<?php
	ini_set('memory_limit', '512M');
	
	include('../../../_eCore/eMain.php');
	eMain::start_app('../../../_eCore/eMain.php');
      
	include('../../../_eCore/addons/eCMS.php');
	eCMS::start_cms();
?>
<?php

      $success = false; 

      $rq = "SELECT id, newsletter_ref, languages, id_recipients, status FROM ".eParams::$prefix."_back_mailings WHERE status LIKE 'sending-%' ORDER BY id ASC LIMIT 1;";
      $mailing_datas = eMain::$sql->sql_to_array($rq);

      if ($mailing_datas['nb']==0) { die('no mailing to process'); }
     
      $mailing_datas = $mailing_datas['datas'][0];
      $mailings = array();
      
      $languages = eParams::$available_languages;
      if ($mailing_datas['languages']!='*') {
            $languages = eTools::str_to_array($mailing_datas['languages']);                        
      }

      for ($i=0;$i<count($languages);$i++) {
            
            $rq_subject = "SELECT object FROM ".eParams::$prefix.'_'.$languages[$i]."_back_newsletters WHERE global_ref=".eMain::$sql->protect_sql($mailing_datas['newsletter_ref'])." LIMIT 1;";
            $subject_datas = eMain::$sql->sql_to_array($rq_subject);
            
            $website_url = eMain::get_website_root_url();
            $preview_url = $website_url."cms/plugins/newsletters/preview.php?newsletter_ref=".$mailing_datas['newsletter_ref'].'&lang='.$languages[$i]; 
            
            $preview_url = str_replace(':443', '', $preview_url);                        
            $mailing_content = eTools::url_get_contents($preview_url);

            $mailing_content = eMail::inline_css($mailing_content);
                                                                                          
            $mailings[$languages[$i]]['title'] = $subject_datas['datas'][0]['object'];
            $mailings[$languages[$i]]['content'] = $mailing_content;

      }

      $clients_sql = "";
      $id_recipients = eTools::str_to_array($mailing_datas['id_recipients']);
      $num_recipient = substr($mailing_datas['status'], 8);
      /*
      $nb_recipients = count($id_recipients);							
      for ($a=0;$a<$nb_recipients;$a++) {                        
            $id_recipients[$a]= intval($id_recipients[$a]); 
            if ($clients_sql!="") { $clients_sql.=" OR "; }							
            $clients_sql.= "id=".intval($id_recipients[$a]);                         
      }
      */
      $clients_sql.= "id=".intval($id_recipients[$num_recipient]);

      if ($clients_sql!="") { 
            for ($i=0;$i<count($languages);$i++) {
                                                
                  echo "<h2>ENVOI ".eText::iso_strtoupper($languages[$i])."</h2>";
                        
                  $sql_conditions = "WHERE languages LIKE '%".$languages[$i]."%' AND (".$clients_sql.")";                               
                  if ($i==0) {
                        // IF CLIENT LANGUAGE IS EMPTY, USE FIRST LANGUAGE //                                         
                        $sql_conditions = "WHERE (languages LIKE '%".$languages[$i]."%' OR (languages='')) AND (".$clients_sql.")"; 
                  }                                    
                                                      
                  $rq_clients = "SELECT email FROM ".eParams::$prefix."_back_clients ".$sql_conditions.";";                              
                  $clients_datas = eMain::$sql->sql_to_array($rq_clients);
                  $recipients = array();
                  for ($c=0;$c<$clients_datas['nb'];$c++) {                                   
                        $recipients[] = $clients_datas['datas'][$c]['email'];
                  }
                  $nb_dest = count($recipients);

                  if ($nb_dest==0) { continue; }
                                                
                  $subject = $mailings[$languages[$i]]['title'];
                  $content = $mailings[$languages[$i]]['content'];
                  
                  $result_mails = eMail::send_mail(eParams::$admin_sender, $subject, $content, $recipients, array('showresult'=>true, 'debug'=>true));
                                                                                                            
                  if ($result_mails==0) {
                        echo "<h2>ENVOI REUSSI</h2>";      
                        $success = true;                              
                  } else {
                        echo "<h2>ECHEC ENVOI MAIL</h2>";
                        echo $nb_dest+$result_mails.' mails envoyés sur '.$nb_dest;
                  }
                  echo "<hr>";
                  
            }
      }

      if ($success) {
            $num_recipient++;
            if ($num_recipient==count($id_recipients)) {
                  $new_status = 'SENT';
            } else {
                  $new_status = 'sending-'.$num_recipient;
            }

            $rq = "UPDATE ".eParams::$prefix."_back_mailings SET date_lastsent='".date('Y-m-d')."', status='".$new_status."' WHERE id=".$mailing_datas['id'];
            if (!eMain::$sql->sql_query($rq)) { eMain::add_error('UPDATE failed'); }
      } else {
            header("HTTP/1.1 418 I'm a teapot", true, 418);
      }
?>
<?php
	eMain::end_app();
?>