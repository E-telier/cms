<?php
class eDataFetcherFiles extends eDataFetcher {

      public $folder_field_name = 'folder';

      public function __construct() {

            $this->cols_fields_names = array("filename", "extension", "folder", "weight", "upload_date");
            $this->table = "_cms_files";
                              
            $this->sql_order = "ORDER BY filename ASC;";
            $this->titlename = "files";

            $this->current_search = '';
            $this->current_selection = 'all';            
            $folders_datas = eMain::$sql->sql_to_array("SELECT DISTINCT folder FROM ".eParams::$prefix.$this->table." WHERE folder<>'' ORDER BY folder ASC;");
            for ($f=0;$f<$folders_datas['nb'];$f++) {
                  $this->selection_options[] = $folders_datas['datas'][$f]['folder'];
            }   

            parent::__construct();

      }

      public function delete_one($reference) {

            $rq = "SELECT filename, extension, folder FROM ".eParams::$prefix.$this->table." WHERE id=".eMain::$sql->protect_sql($reference)." LIMIT 1;";	
            $content = eMain::$sql->sql_to_array($rq);	
            $content = $content['datas'][0];
            $filename = eText::str_to_url($content['filename']).$content['extension'];
            $folder = $content['folder'];

            if (!empty($folder)) {
                  $root_folder = '../uploaded_files/'.$folder.'/';
            } else {
                  $root_folder = '../uploaded_files/';
            }
            
            if (file_exists($root_folder.$filename)) {
                  unlink($root_folder.$filename);							
            } else {
                  eMain::add_error($root_folder.$filename." doesn't exist");
            }
                       
            if (!empty($folder) && eFile::is_dir_empty($root_folder)) {
                  rmdir($root_folder);							
            }
            
            parent::delete_one($reference);

      }

      public function get_formatted_value($col_value, $col_field_name, $col_displayed_name, &$row_values, $lang) {

            $col_value = parent::get_formatted_value($col_value, $col_field_name, $col_displayed_name, $row_values, $lang);

            if ($col_displayed_name=='filename') {
                  $folder = '';
                  
                  if ($row_values['folder']!='') { 
                        $folder = $row_values['folder'].'/'; 					
                  }
                  //$filepath = '../uploaded_files/'.$folder.$col_value.$row_values['extension'];
                  $filepath = '../download/'.$col_value.$row_values['extension'];
                  
                  $col_value = '<a href="'.eMain::get_requested_folder_url().$filepath.'" target="_blank">'.$col_value.'</a>';
            } else if ($col_displayed_name=='weight') {
                  $col_value = (round($col_value*100 /1024)/100). ' kB';
            }

            return $col_value;

      }

}