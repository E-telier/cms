<?php
class eDataFetcherImages extends eDataFetcher {

      public $folder_field_name = 'folder';

      public function __construct() {

            $this->is_multilingual = true;

            $this->cols_fields_names = array("CONCAT(name, '.', extension) AS image", "slideshow_pos", "width", "height", "align");
            $this->cols_displayed_titles = array('image', "position", "width", "height", "align");
            $this->table = "_cms_images";
            $this->sql_order = "ORDER BY folder ASC, name ASC, id ASC;";
            $this->titlename = "images";

            // add slideshow_pos and gallery_pos if modules are activated
            $modules_datas = eMain::$sql->sql_to_array("SELECT reference, activated FROM ".eParams::$prefix."_cms_modules WHERE reference='slideshow' OR reference='gallery' ORDER BY FIELD(reference, 'slideshow', 'gallery');");						
            for ($i=0;$i<$modules_datas['nb'];$i++) {
                  if ($modules_datas['datas'][$i]['activated']=='1') {
                        $cols_fields_names[] = $modules_datas['datas'][$i]['reference'].'_pos';
                        $cols_displayed_titles[] = $modules_datas['datas'][$i]['reference'].' position';
                  }
            }

            // add hidden info
            $this->cols_fields_names = array_merge($this->cols_fields_names, array('name', 'extension', 'description', 'weight', 'folder'));

            $this->current_search = '';
            $this->current_selection = 'all';
            $folders_datas = eMain::$sql->sql_to_array("SELECT DISTINCT folder FROM ".eCMS::$localized_sql_prefix."_cms_images WHERE folder<>'' ORDER BY folder ASC;");
            for ($f=0;$f<$folders_datas['nb'];$f++) {
                  $this->selection_options[] = $folders_datas['datas'][$f]['folder'];
            }            
            
            parent::__construct();

      }

      public function delete_one($reference) {

            $nb_lang = count(eParams::$available_languages);

            for ($lang=0;$lang<$nb_lang;$lang++) {
                  $temp_lang = eParams::$available_languages[$lang];
                  $temp_table = eParams::$prefix.'_'.$temp_lang.$this->table;

                  $rq_img = "SELECT name, extension, folder FROM $temp_table WHERE global_ref=".eMain::$sql->protect_sql($reference)." LIMIT 1;";	
                  $content = eMain::$sql->sql_to_array($rq_img);	
                  $content = $content['datas'][0];
                  $filename = eText::str_to_url($content['name']).'.'.$content['extension'];
                  $filename_full = eText::str_to_url($content['name']).'_full'.'.'.$content['extension'];
                  $folder = $content['folder'];

                  if (!empty($folder)) {
                        $root_folder = '../images/'.$folder.'/';
                  } else {
                        $root_folder = '../images/';
                  }
                  
                  if (file_exists($root_folder.$filename)) {
                        unlink($root_folder.$filename);							
                  } else {
                        eMain::add_error($root_folder.$filename." doesn't exist");
                  }
                  if (file_exists($root_folder.$filename_full)) {
                        unlink($root_folder.$filename_full);							
                  } else {
                        eMain::add_error($root_folder.$filename_full." doesn't exist");
                  }
                  
                  if (!empty($folder) && eFile::is_dir_empty($root_folder)) {
                        rmdir($root_folder);							
                  }
            }
            parent::delete_one($reference);

      }

      public function get_formatted_value($col_value, $col_field_name, $col_displayed_name, &$row_values, $lang) {

            $col_value = parent::get_formatted_value($col_value, $col_field_name, $col_displayed_name, $row_values, $lang);

            // IMAGE //
            if ($col_displayed_name=='image') {
                  $folder = '';
                  $img = 'image link';
                  if ($row_values['folder']!='') { 
                        $folder = $row_values['folder'].'/'; 					
                  } else {
                        $filepath = '../images/'.$folder.$row_values['image'];
                        $img = '<img src="'.$filepath.'" title="'.$row_values['description'].'" />';
                  }
                  
                  $col_value = '
                  <div align="center">
                        <a href="../images/'.$folder.$row_values['name'].'_full.'.$row_values['extension'].'" target="_blank" title="'.$row_values['description'].'" class="img_full">
                              '.$img.'
                        </a>
                        <br />
                        '.$row_values['image'].' ('.eText::format_number($row_values['weight']/1024, false).' KB)
                  </div>';	
            }

            return $col_value;

      }

}
?>