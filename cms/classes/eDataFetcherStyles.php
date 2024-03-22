<?php
class eDataFetcherStyles extends eDataFetcher {

      public function __construct() {

            $this->cols_fields_names = array("name", "text_font", "text_color", "text_size", "background_color", "activated");
            $this->table = "_cms_styles";
                              
            $this->sql_order = "ORDER BY activated DESC, name ASC, id ASC;";
            $this->titlename = "styles";

            $this->current_search = '';

            parent::__construct();

      }

}