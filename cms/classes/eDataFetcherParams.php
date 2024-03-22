<?php
class eDataFetcherParams extends eDataFetcher {

      public function __construct() {

            $this->is_multilingual = true;

            $this->cols_fields_names = array("description", "keywords", "banner_content", "footer_content", "activated");
            $this->table = "_cms_params";
                              
            $this->sql_order = "ORDER BY activated DESC, id ASC;";
            $this->titlename = "paramètres";

            parent::__construct();

      }

}