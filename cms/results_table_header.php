<?php
	if (isset($my_data_fetcher->folder_field_name)) {
		if (is_null($current_folder) && $table_datas['nb']>0) {
			$current_folder = $table_datas['datas'][0][$my_data_fetcher->folder_field_name];
		}
		
		if (!empty($current_folder)) {
			$empty_array = array();
			$displayed_folder_name = $my_data_fetcher->get_formatted_value($current_folder, $my_data_fetcher->folder_field_name, $my_data_fetcher->folder_field_name, $empty_array, $temp_lang);
?>
	<h2 class="folder"><?php echo eText::iso_htmlentities($displayed_folder_name); ?></h2>
<?php
		}
	}
?>
	<div class="table_container">
	<table cellspacing="0" cellpadding="5" border="0" class="table_result">
		<tr>
			<td class="row_num"> </td> 			
<?php
	
	for($c=0;$c<$nb_cols;$c++) {

		$this_col_field_name = $my_data_fetcher->get_col_field_name_at($c);
		$this_col_displayed_name = $my_data_fetcher->cols_displayed_titles[$c];
	
		$main_order = $my_data_fetcher->get_main_order();	
		if ($this_col_field_name==$main_order['orderby']) {		
			if ($main_order['sort']=='ASC') { 
				$switched_sort = 'DESC';
				$current_sort_symbol = '&#x25B2;';
			} else { 
				$switched_sort = 'ASC'; 
				$current_sort_symbol = '&#x25BC;';
			}				
		} else {
			$switched_sort = 'ASC'; 
			$current_sort_symbol = '';
		}
		
		echo "\n" . '<td><a href="'.eMain::get_requested_url(true).'?p='.$page.'&orderby='.$this_col_field_name.'&sort='.$switched_sort.'">'.$this_col_displayed_name.'</a> '.$current_sort_symbol.'</td>';
	
	}
?>
			<td class="action_btn">EDIT</td>
<?php
		if ($my_data_fetcher->get_actions()!==false) {	
?>	
			<td class="action_btn">ACTION</td>
<?php
		} // END IF ACTION
?>
		</tr> 