<?php
class eDataFetcherNewsletters extends eDataFetcher {

      private $categories_datas;

      public $actions = '';

      public function __construct() {

            $this->is_multilingual = true;

            $this->cols_fields_names = array('object', 'text', 'id_attachments', 'date', 'selected');
            $this->table = "_back_newsletters";
                              
            $this->sql_order = "ORDER BY date DESC, object ASC;";
            $this->titlename = "Newsletters";

            $this->current_search = '';

            $this->actions = '
            <div><a class="button" href="plugins/newsletters/preview.php?newsletter_ref={ global_ref }&lang={ lang }" target="_blank">Visualiser</a></div>
            <hr>
            <div><a class="button" href="'.eMain::get_requested_url(true).'?p=newsletters&selected_id={ id }&lang={ lang }">{ shownews_btn }</a></div>
            ';

            parent::__construct();

      }

      public function get_formatted_value($col_value, $col_field_name, $col_displayed_name, &$row_values, $lang) {

            switch($col_field_name) {
                  case 'id_attachments':
                        // SHOW ATTACHMENTS REF //
                        $list_a = eTools::str_to_array($col_value);                       
                        sort($list_a);
                        $nb_a = count($list_a);
                                    
                        $string_value = '';
                        for ($a=0;$a<$nb_a;$a++) {
                              $rq_a = "SELECT folder, filename, extension FROM ".eParams::$prefix."_cms_files WHERE id=".intval($list_a[$a])." LIMIT 1;";
                              $attachment_datas = eMain::$sql->sql_to_array($rq_a);

                              if ($attachment_datas['nb']===0) { 
                                    echo "<br>\n" . 'missing file '.intval($list_a[$a]);
                                    continue;
                              }

                              $content_a = $attachment_datas['datas'][0];

                              if (!empty($content_a['folder'])) { $content_a['folder'].= '/'; }
                              $relativepath = $content_a['folder'].$content_a['filename'].$content_a['extension'];
                              if ($content_a!= false) {
                                    $attachment = '<a href="'.eMain::get_website_root_url().'uploaded_files/'.$relativepath.'">'.$relativepath.'</a><br>';
                              } else {
                                    $attachment = "<span class=\"error\">WARNING : file not found</span>";
                              }
                              $string_value .= $attachment."\n";
                              
                        }
                        
                        $col_value = $string_value;
                        break;
                        
                  case 'selected': 
                        $row_values['shownews_btn'] = 'Afficher la news';
                        if ($col_value==1) {
                              $row_values['shownews_btn'] = 'Désélectionner';						
                        }							
                        
                        break;
                  default:
                        $col_value = parent::get_formatted_value($col_value, $col_field_name, $col_displayed_name, $row_values, $lang);
            }

            

            return $col_value;
      }

      public function execute_actions() {
            if (isset($_GET['selected_id'])) {
                  $rq = "UPDATE ".eParams::$prefix.'_'.$_GET['lang']."_back_newsletters SET selected = CASE selected 
                              WHEN 1 THEN 0				
                              ELSE 1
                              END 
                              WHERE id=".intval($_GET['selected_id']);
                              //echo $rq;
                  if (!eMain::$sql->sql_query($rq)) {
                        eMain::add_error('UPDATE failed');
                  } else {
                        echo '<script type="text/javascript">$(document).ready(function() { showLang("'.$_GET['lang'].'"); });</script>';
                  }
            }
      }

}