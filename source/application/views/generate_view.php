<?php
$crudAuth = $this->session->userdata('CRUD_AUTH');
?>
<div id="header">
	<div class="container">
		<h1><?php echo $this->lang->line('__LBL_SHELF_TITLE__'). '- Generate Issue'; ?></h1>
	</div>
</div>
<div class="container">
	<br/>
		<div class="row">
			<div style="text-align:right;width:100%;" class="col-lg-5 span-8">
	            <a class="btn btn-success generate-button" style="color:white" href="javascript:void(0);"><b>Prepare Issue</b></a>
	            <a class="btn btn-success show-button" style="color:white" href="javascript:void(0);"><b>Show Files</b></a>
	            <a class="btn" role="button" href="<?php echo base_url(); ?>index.php/admin/shelf">Back to Shelf</a>
	        </div>
	    </div>
	    <hr/>
        <br/>
        <br/>

	    <?php if (!empty($files)) { 
	    	$prefix = $controller.'/'.$method.'/'.$path_in_url;
	    	echo '<ol class="row span10 contents">';
	    	foreach( $files as $file ) {
	    		if ($file != 'index.html')
	    			echo '<li class="well span10 ">' . anchor($prefix.$file, $file).'</li>';
	    	} 
	    	echo '</ol>';
	   	} ?>
</div>

<script type="text/javascript">
	function get_query(){
	    var url = location.href;
	    var qs = url.substring(url.indexOf('?') + 1).split('&');
	    for(var i = 0, result = {}; i < qs.length; i++){
	        qs[i] = qs[i].split('=');
	        result[qs[i][0]] = decodeURIComponent(qs[i][1]);
	    }
	    return result;
	}
	$(function () {
		$("ol.contents").sortable();
		$('.show-button').click(function () {
			location.assign(location.href.replace('generate/','uploads/packages/'));
		});
		$('.generate-button').click(function () {
			var data = '';
			$('.contents').children().each(function () {
				data += $(this).text() + ',';
			});
			
			$.ajax({
      			url: location.href,
		      	type: 'POST',
		      	data:  data,
		      	dataType: 'json',
		      	crossDomain: true
		    }).done(function(data) {
		    	if (data.hpub) {
		    		alert('Generate JSON Completed!');
		    		location.assign(location.href.replace('generate/','package/'));
		    	} else {
		    		alert('Generate JSON Failed!');
		    	}
		    }).fail(function() {

		    });
        });
	});

</script>