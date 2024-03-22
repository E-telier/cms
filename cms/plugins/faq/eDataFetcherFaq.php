<?php
class eDataFetcherFaq extends eDataFetcher {

      private $categories_datas;

      public $folder_field_name = 'id_category';
      public $categories = array();

      public function __construct() {

            $this->is_multilingual = true;

            $this->cols_fields_names = array("id_category", "position", "title", "keywords", "creation_date", "visible", "views");	
            $this->cols_displayed_titles = array("category", "position", "title", "keywords", "creation_date", "visible", "views");	
            $this->table = "_back_faq";
                              
            $this->sql_order = "ORDER BY id_category ASC, position ASC, creation_date DESC, id DESC;";
            $this->titlename = "FAQ";

            $this->current_search = '';

            parent::__construct();

      }

      public function get_formatted_value($col_value, $col_field_name, $col_displayed_name, &$row_values, $lang) {

            $col_value = parent::get_formatted_value($col_value, $col_field_name, $col_displayed_name, $row_values, $lang);

            if ($col_field_name=='id_category') {
                  $categories_datas = $this->get_categories($lang);
                  $col_value = $categories_datas['datas'][$col_value]['category'];
            }

            return $col_value;

      }

      private function get_categories($lang) {

            if (isset($this->categories[$lang])) {
                  return $this->categories[$lang];
            } 

            $categories_datas = eMain::$sql->sql_to_array("SELECT id, category FROM ".eParams::$prefix."_".$lang."_back_faq_categories WHERE 1 ORDER BY category ASC;", array('index'=>'id', 'returnEmptyAssoc'=>true));
            $this->categories[$lang] = $categories_datas;

            return $categories_datas;
      }

}