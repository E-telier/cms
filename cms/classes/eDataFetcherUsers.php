<?php
class eDataFetcherUsers extends eDataFetcher {

      public function __construct() {

            $this->cols_fields_names = array("login", "domain");
            $this->cols_displayed_titles = array("login", "type");
            $this->table = "_users";
                              
            $this->sql_order = "ORDER BY access_level ASC, login ASC, id ASC;";
            $this->titlename = "utilisateurs";

            $this->current_selection = 'all';
            $this->current_search = '';
            $this->selection_options = eUser::$access_types;
          
            parent::__construct();

      }

      public function is_editable($row_values = null) {
            if ($row_values['login']=='admin') {
                  $user_datas = eUser::getInstance()->get_datas('login');
                  if ($user_datas['login']!='admin') {
                        return false;
                  }
            }
            parent::is_editable();
      }
      public function is_deletable($row_values = null) {
            if ($row_values['login']=='admin') {
                 return false;
            }
            parent::is_deletable();
      }

}