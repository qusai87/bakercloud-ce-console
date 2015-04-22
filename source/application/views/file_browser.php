<?php
$crudAuth = $this->session->userdata('CRUD_AUTH');
?>
<div id="header">
	<div class="container"><h1><?php echo $this->lang->line('__LBL_FILE_BROWSER__'); ?></h1></div></div>
	<div class="container">
		<p><?php //echo $virtual_root.'/'.$path_in_url ?></p>
		<br/>
		<?php


		    $prefix = $controller.'/'.$method.'/'.$path_in_url;
		    if (!empty($dirs)) foreach( $dirs as $dir )
		        echo '/'.anchor($prefix.$dir['name'], $dir['name']).'<br>';
		 
		    if (!empty($files)) foreach( $files as $file )
		        echo anchor($prefix.$file['name'], $file['name']).'<br>';
		?>