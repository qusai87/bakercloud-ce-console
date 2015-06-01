<?php
$crudAuth = $this->session->userdata('CRUD_AUTH');
?>
<div id="header">
	<div class="container"><h1><?php echo $this->lang->line('__LBL_SHELF_TITLE__'). '- Upload Issue'; ?></h1></div></div>
	<div class="container">
		<br/>
	    <div class="row">
	    	
	    	<div class="col-lg-5 span-8">
			  	<a class="btn btn-success" role="button" href="<?php echo base_url(); ?>index.php/admin/shelf/uploads/generated"><b>Show Generated Hpub Files</b></a></li>
			  	<a class="btn btn-success" role="button" href="<?php echo base_url(); ?>index.php/admin/shelf/uploads/covers"><b>Show Covers</b></a></li>
    		</div>
    	</div>

		<div class="container-fluid">
		  <div class="row">
		    <div class="col-sm-12">
		    </div>
		  </div><!--/row-->
		  <hr>
		  <div> 
		      <form action="/upload" class="dropzone" drop-zone="" id="file-dropzone"></form>
		  </div>
		</div>

		<div style="text-align:right;width:100%;">
			<a class="btn btn" role="button" href="<?php echo base_url(); ?>index.php/admin/shelf/uploads/uploaded"><b>Show Uploaded Files</b></a></li> 
			<a class="btn btn" role="button" href="<?php echo base_url(); ?>index.php/admin/shelf/uploads/packages"><b>Show Packages Files</b></a></li> 
		</div>

	    <script>
	      // Get the template HTML and remove it from the doument
	      // var previewNode = document.querySelector("#template");
	      // previewNode.id = "";
	      // var previewTemplate = previewNode.parentNode.innerHTML;
	      // previewNode.parentNode.removeChild(previewNode);

			Dropzone.autoDiscover = false;

	      var myDropzone = new Dropzone(document.querySelector("#file-dropzone"), { // Make the whole body a dropzone
	        acceptedFiles: ".zip,.png,.hpub",
	        url: location.href+"/upload", // Set the url
	        thumbnailWidth: 80,
	        thumbnailHeight: 80,
	        parallelUploads: 20,
	        queueLimit : 1,
	        autoQueue: true,
	        success : function(file, response) {
	        	console.log('UPLOAD RESPONSE : ' ,response);
	        	var data = response && JSON.parse(response);
		        if (file && data && data.name) {
	      			if (data.type === "image/png") {
		      			location.assign(location.href + '/uploads/covers/');
		      		}
		      		else if (data.type === "application/zip") {
		      			location.assign(location.href + '/uploads/uploaded/' + data.name);
		      		} else if (data.name.indexOf('.hpub') != -1){
		      			location.assign(location.href + '/uploads/generated/');
		      		}
		      	} else {

		      		alert('Can\'t upload file');
		      	}
		    }
	      });

	      myDropzone.on("addedfile", function(file) {
	        // Hookup the start button
	        //console.log(file);
	        //document.querySelector(".start").removeAttribute("disabled");
	        //document.querySelector(".cancel").removeAttribute("disabled");
	        //document.querySelector(".start").onclick = function() { myDropzone.enqueueFile(files); };
	      });

	      // Update the total progress bar
	      // myDropzone.on("totaluploadprogress", function(progress) {
	      //   document.querySelector("#total-progress .progress-bar").style.width = progress + "%";
	      // });

	      myDropzone.on("sending", function(file) {
	        // Show the total progress bar when upload starts
	        //document.querySelector("#total-progress").style.display = "block";
	        // And disable the start button
	        //document.querySelector("#actions .start").setAttribute("disabled", "disabled");
	      });

	      // Hide the total progress bar when nothing's uploading anymore
	      myDropzone.on("queuecomplete", function(progress) {
	        //document.querySelector("#total-progress").style.display = "none";
	      });

	      // Setup the buttons for all transfers
	      // The "add files" button doesn't need to be setup because the config
	      // `clickable` has already been specified.
	      // document.querySelector("#actions .start").onclick = function() {
	      //   myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED));
	      // };
	      // document.querySelector("#actions .cancel").onclick = function() {
	      //   myDropzone.removeAllFiles(true);
	      // };
	    </script>
	 </div>
</div> 