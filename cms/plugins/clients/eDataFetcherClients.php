<?php
class eDataFetcherClients extends eDataFetcher {

      public function __construct() {

            $this->is_multilingual = false;

            $this->cols_fields_names = array('firstname', 'lastname', 'email', 'country', 'languages', 'type', 'tax', 'ceo', 'activated');
            $this->table = "_back_clients";
                              
            $this->sql_order = "ORDER BY activated DESC, lastname ASC, firstname ASC;";
            $this->titlename = "Clients";

            $this->current_search = '';

            parent::__construct();

      }

      public function get_formatted_value($col_value, $col_field_name, $col_displayed_name, &$row_values, $lang) {

            $col_value = parent::get_formatted_value($col_value, $col_field_name, $col_displayed_name, $row_values, $lang);

            if ($col_field_name=='email') {
                  if (strlen($col_value)>24) {
                        $col_value = str_replace('@', "\n@", $col_value);
                  }			
            }

            return $col_value;
      }

}