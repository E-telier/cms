<script type="text/javascript">
	
	function checkKey(e) {
			
		if (e.which == 13 || e.keyCode == 13) {
		
			var val = $('#search_input').val();
			val = val.split(' ').join('+');
			val = val.split('"').join('&quot;');
            
			window.location.href = '<?php echo eMain::get_website_root_url() . eText::str_to_url(eLang::translate('search')); ?>/'+val;
			
			e.preventDefault();
			
            return false;
        }
        return true;
	}
</script>

<form onsubmit="return false;">
	<input type="text" name="search" id="search_input" value="<?php eLang::show_translate('search'); ?>" onfocus="if (this.value=='<?php eLang::show_translate('search'); ?>') { this.value=''; }" onkeypress="return checkKey(event)" />	
</form>