<?php
$crudAuth = $this->session->userdata('CRUD_AUTH');
?>
<div id="header">
	<div class="container"><h1><?php echo $this->lang->line('__LBL_SHELF_TITLE__'). '- Browse Issue'; ?></h1></div></div>
	<div class="container">
		<p><?php //echo $virtual_root.'/'.$path_in_url ?></p>
		<br/>

		 <div class="row">
	    	
	    	<div  style="text-align:right;width:100%;" class="col-lg-5 span-8">

		<?php if (isset($package) && $package) { ?>
	            <a style="color:white" class="btn btn-success" href="<?php echo $package ?>"><b>Generate Issue</b></a>
		<?php } ?>

    	 	<a class="btn btn " role="button" href="<?php echo base_url(); ?>index.php/admin/shelf">Back to Shelf</a>
    	 	</div>
    	 </div>
    	 	    <hr/>
        <br/>
        <br/>


		<?php

			
		    $prefix = $controller.'/'.$method.'/'.$path_in_url;
		    if (!empty($dirs)) {
		    	foreach( $dirs as $dir ) {
		    		echo '/'.anchor($prefix.$dir['name'], $dir['name']).'<br>';
		    	}
		    }
		    if (!empty($files)) {
		    	foreach( $files as $file ) {
		    		echo anchor($prefix.$file['name'], $file['name']).'<br>';
		    	}
		    }
		    	
		?>