<?php
class eDataFetcherFaqCategories extends eDataFetcher {

      private $categories_datas;

      public function __construct() {

            $this->is_multilingual = true;

            $this->cols_fields_names = array("category", "position", "visible");
            $this->table = "_back_faq_categories";
                              
            $this->sql_order = "ORDER BY position ASC, category ASC, creation_date DESC, id DESC;";
            $this->titlename = "FAQ Categories";

            parent::__construct();

      }

}