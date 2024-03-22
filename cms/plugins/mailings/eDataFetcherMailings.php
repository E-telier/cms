<?php
class eDataFetcherMailings extends eDataFetcher {

      public $actions = '';

      public function __construct() {

            $this->is_multilingual = false;

            $this->cols_fields_names = array("newsletter_ref", "languages", "id_recipients", "date_creation", "date_lastsent", "status");
            $this->table = "_back_mailings";
                              
            $this->sql_order = "ORDER BY date_lastsent DESC, date_creation DESC;";
            $this->titlename = "Mailings";

            $this->actions .= '<div><a class="button" href="index.php?p=mailings&send={ id }" onclick="ePopup.createPopup(\'message\', {\'message\':\''.urlencode('Envoi en cours, veuillez patienter...').'\'});">Envoyer</a></div>';
            $this->actions .= '<div><a class="button" href="index.php?p=mailings&cronsend={ id }" onclick="ePopup.createPopup(\'message\', {\'message\':\''.urlencode('Envoi en cours, veuillez patienter...').'\'});">CRON Process</a></div>';

            parent::__construct();

      }

      public function get_formatted_value($col_value, $col_field_name, $col_displayed_name, &$row_values, $lang) {

            switch($col_field_name) {
                  case 'newsletter_ref':
                        // SHOW NEWSLETTER OBJECT //
                        $rq_n = "SELECT object FROM ".eCMS::$localized_sql_prefix."_back_newsletters WHERE global_ref=".$col_value." LIMIT 1;";
                        $news_datas = eMain::$sql->sql_to_array($rq_n);
                                                      
                        if ($news_datas['nb']>0) {
                              $content_n = $news_datas['datas'][0];
                              $object = eText::iso_htmlentities(eText::no_style(substr($content_n['object'], 0, 255))); 
                        } else {
                              $object = "<div class=\"error\">WARNING : This newsletter don't exist anymore!</div>";
                        }
                        
                        $col_value = $object; 
                        break;
                  
                  case 'id_recipients':
                        // SHOW RECIPIENTS NB //
                        $list_r = explode("*", $col_value);
                        //print_r($list_r);
                        $list_r = array_filter($list_r);
                        //print_r($list_r);
                        $nb_r = count($list_r);
                        
                        $col_value = $nb_r." recipients selected";
                        break;
                  default:
                        $col_value = parent::get_formatted_value($col_value, $col_field_name, $col_displayed_name, $row_values, $lang);
            }

            return $col_value;
      }

      public function execute_actions() {

            if (isset($_GET['send'])) {
                  
                  $rq = "SELECT newsletter_ref, languages, id_recipients FROM ".eParams::$prefix.$this->table." WHERE id=".intval($_GET['send'])." LIMIT 1;";
                  $mailing_datas = eMain::$sql->sql_to_array($rq);
                  if ($mailing_datas['nb']==0) {
                        eMain::add_error('this mailing does not exist');
                        return false;
                  } else {
                        $mailing_datas = $mailing_datas['datas'][0];
                        $mailings = array();
                  }

                  $languages = eParams::$available_languages;
                  if ($mailing_datas['languages']!='*') {
                        $languages = eTools::str_to_array($mailing_datas['languages']);                        
                  }

                  for ($i=0;$i<count($languages);$i++) {
                       
                        $rq_subject = "SELECT object FROM ".eParams::$prefix.'_'.$languages[$i]."_back_newsletters WHERE global_ref=".eMain::$sql->protect_sql($mailing_datas['newsletter_ref'])." LIMIT 1;";
                        $subject_datas = eMain::$sql->sql_to_array($rq_subject);
                                                                  
                        $preview_url = eMain::get_website_root_url()."plugins/newsletters/preview.php?newsletter_ref=".$mailing_datas['newsletter_ref'].'&lang='.$languages[$i]; 
                        
                        $preview_url = str_replace(':443', '', $preview_url);                        
                        $mailing_content = eTools::url_get_contents($preview_url);

                        $mailing_content = eMail::inline_css($mailing_content);
                        									                        
                        $mailings[$languages[$i]]['title'] = $subject_datas['datas'][0]['object'];
                        $mailings[$languages[$i]]['content'] = $mailing_content;

                  }

                  $clients_sql = "";
                  $id_recipients = eTools::str_to_array($mailing_datas['id_recipients']);
                  $nb_recipients = count($id_recipients);							
                  for ($a=0;$a<$nb_recipients;$a++) {                        
                        $id_recipients[$a]= intval($id_recipients[$a]); 
                        if ($clients_sql!="") { $clients_sql.=" OR "; }							
                        $clients_sql.= "id=".intval($id_recipients[$a]);                         
                  }

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
                                                            
                              $subject = $mailings[$languages[$i]]['title'];
                              $content = $mailings[$languages[$i]]['content'];
                              
                              $result_mails = eMail::send_mail(eParams::$admin_sender, $subject, $content, $recipients, array('showresult'=>true, 'debug'=>true));
                              $nb_dest = count($recipients);
                                                                                                      
                              if ($result_mails==0) {
                                    echo "<h2>ENVOI REUSSI</h2>";                                    
                              } else {
                                    echo "<h2>ECHEC ENVOI MAIL</h2>";
                                    echo $nb_dest+$result_mails.' mails envoyés sur '.$nb_dest;
                              }
                              echo "<hr>";
                              
                        }
                  }

                  $rq_date = "UPDATE ".eParams::$prefix."_back_mailings SET date_lastsent='".date('Y-m-d')."', status='SENT' WHERE id=".$_GET['send'];
			if (!eMain::$sql->sql_query($rq_date)) { eMain::add_error('UPDATE failed'); }
            }

            if (isset($_GET['cronsend'])) {
                  $new_status = 'sending-0';
                  $rq = "UPDATE ".eParams::$prefix."_back_mailings SET date_lastsent='".date('Y-m-d')."', status='".$new_status."' WHERE id=".intval($_GET['cronsend']);
                  if (!eMain::$sql->sql_query($rq)) { eMain::add_error('UPDATE failed'); }

            }
      }

}