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

        <table class="table table-bordered table-hover list table-condensed">
        <thead>
            <tr>
                <th style="cursor:pointer; text-align:center; color:#333333;text-shadow: 0 1px 0 #FFFFFF;  background-color: #e6e6e6;">Path</th>
                <th style="cursor:pointer; text-align:center; color:#333333;text-shadow: 0 1px 0 #FFFFFF;  background-color: #e6e6e6; width:50px;">Actions</th>
            </tr>
        </thead>
        <tbody>
	        <?php
	        	$showButtons  = true;

	        	if ($path_in_url == 'uploads/')
	        		$showButtons = false;
			    $prefix = $controller.'/'.$method.'/'.$path_in_url;
			    if (!empty($dirs)) {
			    	foreach( $dirs as $dir ) {
			    		echo '<tr>';
			    		echo '<td>'.anchor($prefix.$dir['name'], $dir['name']).'</td>';
			    		if ($dir['name'] != '..' && $showButtons ) {
			    			echo '<td>'.'<button onclick="deleteFile(\''. base_url().'index.php/'.$prefix.$dir['name'].'\')" class="btn btn-mini btn-danger"><b>Delete</b></button>'.'</td>';
			    		}
			    		echo '</tr>';
			    	}
			    }
			    if (!empty($files)) {
			    	foreach( $files as $file ) {
			    		echo '<tr>';
			    		echo '<td>'.anchor($prefix.$file['name'], $file['name']).'</td>';
			    		if ($showButtons )
			    			echo '<td>'.'<button onclick="deleteFile(\''. base_url().'index.php/'.$prefix.$file['name'].'\')" class="btn btn-mini btn-danger"><b>Delete</b></button>'.'</td>';
			    		echo '</tr>';
			    	}
			    }
			    	
			?>
        </tbody>
    </table>

<script type="text/javascript">
	function deleteFile(url) {
		window.location = url +"?delete=1";
	}
</script>