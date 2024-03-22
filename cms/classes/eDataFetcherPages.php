<?php
class eDataFetcherPages extends eDataFetcher {

      public function __construct() {

            $this->is_multilingual = true;

            $this->cols_fields_names = array("id", "reference", "childof", "menu_name", "menu_position", "title", "views", "(reference=childof) AS parent");
            $this->cols_displayed_titles = array("id", "reference", "childof", "menu_name", "menu_position", "title", "views");
            $this->table = "_cms_pages";
                              
            $this->sql_order = "ORDER BY childof ASC, parent DESC, menu_position ASC, reference ASC, id ASC;";
            $this->titlename = "pages";

            $this->current_search = '';
            $this->current_selection = 'all';
            $parent_datas = eMain::$sql->sql_to_array("SELECT DISTINCT childof FROM ".eCMS::$localized_sql_prefix.$this->table." WHERE childof<>reference ORDER BY childof ASC;");
            for ($f=0;$f<$parent_datas['nb'];$f++) {
                  $this->selection_options[] = $parent_datas['datas'][$f]['childof'];
            } 

            parent::__construct();

      }

}