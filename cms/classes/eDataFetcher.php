<?php
/* LAST UPDATE : 2022-05-02 14:43 */
abstract class eDataFetcher {

      public $is_multilingual = false;
      public $cols_fields_names = array();	
      public $cols_displayed_titles = null;
      public $titlename = '';
      
      protected $table = '';
      protected $sql_order = '';     
      protected $sql_conditions = 'WHERE true';
      protected $is_editable = true;
      protected $is_deletable = true;

      protected $current_search = null;
      protected $current_selection = null;
      protected $selection_options = array();

      protected $result_datas = null;
      protected $table_nb_rows = null;

      public function __construct() {
            // set cols_displayed_titles if not exist
            if (is_null($this->cols_displayed_titles)) {
                  $this->cols_displayed_titles = $this->cols_fields_names;
            }
      
            // add identifier fields
            if ($this->is_multilingual()) {
                  $this->cols_fields_names[] = 'global_ref';
            }
            $this->cols_fields_names[] = 'id';
      }

      // getters
      public function get_identifier() {
            if ($this->is_multilingual()===false) { $identifier = 'id'; }
		else { $identifier = 'global_ref'; }

            return $identifier;
      }

      public function get_actions() {
            if (isset($this->actions)) {
                  return $this->actions;
            } else {
                  return false;
            }
      }

      public function get_main_order() {

            $main_order = $this->sql_order;
            if (isset($this->folder_field_name)) {
                  $regex = '/^ORDER BY [^,]+, ([^ ]+) ([^,;]+)[\s\S]*$/';
            } else {
                  $regex = '/^ORDER BY ([^ ]+) ([^,;]+)[\s\S]*$/';
            }
            $main_order = preg_replace($regex, '$1 $2', $main_order);

            $main_order = explode(' ', $main_order);

            return array('orderby'=>$main_order[0], 'sort'=>$main_order[1]);

      }

      public function get_col_field_name_at($index) {

            $col_field_name = $this->cols_fields_names[$index];

            $alias_pos = strpos($col_field_name, ' AS ');
            if ($alias_pos>0) {
                  $col_field_name = substr($col_field_name, $alias_pos+4);
            }

            return $col_field_name;

      }

      public function get_current_selection() {
            if (is_null($this->current_selection)===true) { return false; }

            return $this->current_selection;
      }
      public function get_selection_options() {
            if (count($this->selection_options)===0) { return false; }
            return $this->selection_options;
      }
      public function get_current_search() {
            if (is_null($this->current_search)===true) { return false; }

            return $this->current_search;
      }

      // bools
      public function is_editable($row_values = null) {
            return $this->is_editable;
      }
      public function is_deletable($row_values = null) {
            return $this->is_deletable;
      }
      public function is_multilingual() {
            return $this->is_multilingual;
      }

      // sent variables
      public function set_order($params) {

            $sort = 'ASC';
            if (isset($params['sort'])) {
			$sort = $params['sort'];
		}
		
		$col_index = array_search($params['orderby'], $this->cols_displayed_titles);
		$col_value = $this->cols_fields_names[$col_index];
		$as_index = strpos($col_value, ' AS ');
		if ($as_index!==false) {
			$col_value = substr($col_value, $as_index+4);
		}
		
		$this->sql_order = "ORDER BY ".$col_value.' '.$sort;
		
		if (isset($this->folder_field_name)) {
			$this->sql_order = str_replace("ORDER BY ", "ORDER BY ".$this->folder_field_name.' ASC, ', $this->sql_order);
		}

      }

      public function set_positions($params) {
            $col_field_name = $params['col_field_name'];
		$table = $params['table'];	
		$content = eMain::$sql->sql_to_array("SELECT COUNT(id) AS id_num, COUNT(DISTINCT $col_field_name) AS pos_num FROM $table");
		$content = $content['datas'][0];

            $params['position'] = intval($params['position']);
            $params['old_position'] = intval($params['old_position']);
            $params['id'] = intval($params['id']);
		
		if ($content['id_num']==$content['pos_num']) {
			// MOVE ALL POSITIONS //
			
			if ($params['position']<$params['old_position']) {
				$rq_mod_pos = "UPDATE $table SET $col_field_name = $col_field_name + 1 WHERE $col_field_name >= ".$params['position']." AND $col_field_name < ".$params['old_position'];	
			} else {
				$rq_mod_pos = "UPDATE $table SET $col_field_name = $col_field_name - 1 WHERE $col_field_name <= ".$params['position']." AND $col_field_name > ".$params['old_position'];	
			}
					
			if (!eMain::$sql->sql_query($rq_mod_pos)) { die(eMain::$sql->get_error()); }
			
		} 
		
		$rq_mod_pos = "UPDATE $table SET $col_field_name = ".$params['position']." WHERE id = ".$params['id'];	
		if (!eMain::$sql->sql_query($rq_mod_pos)) { die(eMain::$sql->get_error()); }
      }

      public function delete_one($reference) {
            if ($this->is_multilingual()===false) {                   
                  $rq = "DELETE FROM ".eParams::$prefix.$this->table." WHERE id=".intval($reference).";";   
                  if (!eMain::$sql->sql_query($rq)) {
                        return false;				
                  }                                
            } else { 
                                          
                  $nb_lang = count(eParams::$available_languages);

                  for ($l=0;$l<$nb_lang;$l++) {
                        $temp_lang = eParams::$available_languages[$l];
                        $temp_table = eParams::$prefix.'_'.$temp_lang.$this->table;
                        $rq = "DELETE FROM $temp_table WHERE global_ref='".eMain::$sql->protect_sql($reference)."';";
                                               
                        if (!eMain::$sql->sql_query($rq)) {
                              return false;
                        } 
                  } // END FOR LANG				
                  
            }

            return true;
      }
      
      public function set_selection($selection) {

            if ($selection==='all') { return true; }
            $this->set_search($selection);
            
      }

      public function set_search($search) {

            $search = eMain::$sql->protect_sql($search);

            $this->sql_conditions .= " AND (false";

            for ($c=0;$c<count($this->cols_fields_names);$c++) {
                  $col_field = $this->cols_fields_names[$c];
                  if (strpos($col_field, ' AS ')!==false) {
                        $col_field = substr($col_field, 0, strpos($col_field, ' AS '));
                  }

                  $this->sql_conditions .= " OR ".$col_field." LIKE '%".$search."%'";
            }

            $this->sql_conditions .= ")";

            $this->current_search = $search;
      }

      // results
      public function get_results($force_update = false) {

            if (is_null($this->result_datas)===false && $force_update===false) {
                  return $this->result_datas;
            }

	      $nb_lang = (!$this->is_multilingual()) ? 1 : count(eParams::$available_languages);

            if ($this->is_multilingual===false) {
                  $result_datas = $this->fetch_results(eParams::$prefix.$this->table);
            } else {
                  $result_datas = array();
                  $nb_lang = count(eParams::$available_languages);
                  for ($l=0;$l<$nb_lang;$l++) {                      
                        $lang = eParams::$available_languages[$l];
                        $lang_table = eParams::$prefix.'_'.$lang.$this->table;
                        
                        $result_datas[$lang] = $this->fetch_results($lang_table);
                  }
            }

            $this->result_datas = $result_datas;

            return $result_datas;

      }

      private function fetch_results($table) {

            $cols_fields_names_str = implode(', ', $this->cols_fields_names);

            $rq = "SELECT $cols_fields_names_str FROM ".$table." ".$this->sql_conditions.' '.$this->sql_order;
            $result_datas = eMain::$sql->sql_to_array($rq);
            return $result_datas;
      }

      private function get_table_nb_rows($force_update = false) {

            if (is_null($this->table_nb_rows)===false && $force_update===false) {
                  return $this->table_nb_rows;
            }

            $nb_lang = (!$this->is_multilingual()) ? 1 : count(eParams::$available_languages);

            if ($this->is_multilingual===false) {
                  $table_nb_rows = $this->fetch_nb_rows(eParams::$prefix.$this->table);
            } else {
                  $table_nb_rows = array();
                  $nb_lang = count(eParams::$available_languages);
                  for ($l=0;$l<$nb_lang;$l++) {                      
                        $lang = eParams::$available_languages[$l];
                        $lang_table = eParams::$prefix.'_'.$lang.$this->table;
                        
                        $table_nb_rows[$lang] = $this->fetch_nb_rows($lang_table);
                  }
            }

            $this->table_nb_rows = $table_nb_rows;

            return $table_nb_rows;
      }

      private function fetch_nb_rows($table) {
            $rq = "SELECT id FROM ".$table." WHERE 1;";
		$result_datas = eMain::$sql->sql_to_num($rq);
            return $result_datas;
      }      

      public function get_formatted_value($col_value, $col_field_name, $col_displayed_name, &$row_values, $lang) {
            
            // BOOL // 
            if (is_bool($col_value)) {
                  $col_value = ($col_value===true) ? 'oui':'non';
            }
            
            // MULTIPLE REF //
            if (preg_match('/\*([^\* ]+?)\*/im', $col_value)) { 
                  $col_value = eTools::str_to_array($col_value);
                  $col_value = implode(', ', $col_value);
            }            
            
            // NO STYLE //
            $col_value = eText::no_style($col_value, true);			
            $col_value = eText::no_html($col_value, false);
            $col_value = nl2br($col_value);

            // LONG TEXT //
            $col_value = trim($col_value);
            if (eText::iso_strlen($col_value)>128) {
                  $col_value = eText::iso_substr($col_value, 0, 128).' [...]';
            }

            // LONG UNCUT TEXT //
            while(preg_match("/([^- \n\r&]{8})([^- \n\r&]{8})/im", $col_value)) {
                  $col_value = preg_replace("/([^- \n\r&]{8})([^- \n\r&]{8})/im", "$1&#8203;$2", $col_value);
            }

            // POSITION //
            if (stripos($col_displayed_name, 'position')!==false && stripos($col_field_name, ' AS ')===false) {

                  $table = ($this->is_multilingual()) ? eParams::$prefix.'_'.$lang.$this->table : $this->table;
                  $max_rows = ($this->is_multilingual()) ? $this->get_table_nb_rows()[$lang] : $this->get_table_nb_rows();

                  $new_col_value = '';
                  $new_col_value = '
                  <form id="position_'.$row_values['id'].'" method="post">
                        <input type="hidden" name="col_field_name" value="'.$col_field_name.'" />
                        <input type="hidden" name="table" value="'.$table.'" />
                        <select value="'.$col_value.'" name="position" onchange="this.form.submit();">
                              ';
                              
                  if (isset($folder_value)) {
                        $max_value = eMain::$sql->sql_to_array("SELECT COUNT(id) AS max_in_folder FROM $table WHERE $folder_value='".$row_values[$folder_value]."' GROUP BY $folder_value ORDER BY COUNT(id) DESC LIMIT 1;");
                        $max_value = $max_value['datas'][0]['max_in_folder'];
                  } else {
                        $max_value = $max_rows;
                  }
                              
                  for($z=0;$z<=$max_value;$z++) {	                  
                        $selected = ($col_value==($z)) ? 'selected="selected"' : '';
                        $new_col_value .= "\n".'<option '.$selected.'>'.($z).'</option>';      
                  }
                  $new_col_value .= '		
                        </select>
                        <input type="hidden" name="old_position" value="'.$col_value.'" />
                        <input type="hidden" name="id" value="'.$row_values['id'].'" />
                  </form>';
                  
                  $col_value = $new_col_value;
            }

            return $col_value;
            
      }

}
?>