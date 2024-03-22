<?php
class eDataFetcherBlocks extends eDataFetcher {

      public function __construct() {

            $this->is_multilingual = true;

            $this->cols_fields_names = array("reference", "title", "content", "pages_ref", "sections_ref", "position");
            $this->table = "_cms_blocks";
                              
            $this->sql_order = "ORDER BY pages_ref ASC, sections_ref ASC, position ASC, reference ASC, id ASC;";
            $this->titlename = "text-blocks";

            parent::__construct();

      }

}