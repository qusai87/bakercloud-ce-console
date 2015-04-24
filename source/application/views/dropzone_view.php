<?php
$crudAuth = $this->session->userdata('CRUD_AUTH');
?>
<div id="header">
	<div class="container"><h1><?php echo $this->lang->line('__LBL_SHELF_TITLE__'); ?></h1></div></div>
	<div class="container">
	    <h1>Upload Issue Files</h1>

	     <p>
    	  	<ul>
			    <li><a href="<?php echo base_url(); ?>index.php/admin/dropzone/uploads">Show Uploaded files</a></li>
			 </ul>   
    	  </p>

	    <div class="row">
	      <div class="col-lg-5">
	        <!-- The global file processing state -->
	       <!--  <span class="fileupload-process">
	          <div id="total-progress" class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
	            <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
	          </div>
	        </span> -->
	      </div>

	    </div>

	    <div class="table table-striped" class="files" id="previews">

	      <div id="template" class="file-row alert alert-success">
	        <!-- This is used as the file preview template -->
	        <div>
	        	<span class="preview"><img data-dz-thumbnail /></span>
	            <p class="name" data-dz-name></p>
	            <strong class="error text-danger" data-dz-errormessage></strong>
	            <p class="size" data-dz-size></p>
	           	<!--  <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
	              <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
	            </div> -->
	        </div>
	        <!-- <div>
	          <button class="btn btn-primary start hide">
	              <i class="glyphicon glyphicon-upload"></i>
	              <span>Start</span>
	          </button>
	          <button data-dz-remove class="btn btn-warning cancel hide">
	              <i class="glyphicon glyphicon-ban-circle"></i>
	              <span>Cancel</span>
	          </button>
	          <button data-dz-remove class="btn btn-danger delete hide">
	            <i class="glyphicon glyphicon-trash"></i>
	            <span>Delete</span>
	          </button>
	        </div> -->
	      </div>
	    </div>

	    <div id="actions"  class="col-lg-7">
	        <!-- The fileinput-button span is used to style the file input field as button -->
	        <span class="btn btn-success fileinput-button">
	            <i class="glyphicon glyphicon-plus"></i>
	            <span>Add files...</span>
	        </span>
	        <button type="submit" class="btn btn-primary start" disabled="disabled">
	            <i class="glyphicon glyphicon-upload"></i>
	            <span>Start upload</span>
	        </button>
	        <button type="reset" class="btn btn-warning cancel" disabled="disabled">
	            <i class="glyphicon glyphicon-ban-circle"></i>
	            <span>Cancel upload</span>
	        </button>
	      </div>


	    <script>
	      // Get the template HTML and remove it from the doument
	      var previewNode = document.querySelector("#template");
	      previewNode.id = "";
	      var previewTemplate = previewNode.parentNode.innerHTML;
	      previewNode.parentNode.removeChild(previewNode);

	      var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
	        acceptedFiles: "application/zip",
	        url: "http://localhost/bakercloud-ce-console/source/index.php/admin/dropzone/upload", // Set the url
	        thumbnailWidth: 80,
	        thumbnailHeight: 80,
	        parallelUploads: 20,
	        previewTemplate: previewTemplate,
	        autoQueue: false, // Make sure the files aren't queued until manually added
	        previewsContainer: "#previews", // Define the container to display the previews
	        clickable: ".fileinput-button" // Define the element that should be used as click trigger to select files.
	      });

	      myDropzone.on("addedfile", function(file) {
	        // Hookup the start button
	        document.querySelector(".start").removeAttribute("disabled");
	        document.querySelector(".cancel").removeAttribute("disabled");
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
	        document.querySelector("#actions .start").setAttribute("disabled", "disabled");
	      });

	      // Hide the total progress bar when nothing's uploading anymore
	      myDropzone.on("queuecomplete", function(progress) {
	      	document.querySelector("#previews").innerHTML = "<b>Complete!</b>"
	        //document.querySelector("#total-progress").style.display = "none";
	      });

	      // Setup the buttons for all transfers
	      // The "add files" button doesn't need to be setup because the config
	      // `clickable` has already been specified.
	      document.querySelector("#actions .start").onclick = function() {
	        myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED));
	      };
	      document.querySelector("#actions .cancel").onclick = function() {
	        myDropzone.removeAllFiles(true);
	      };
	    </script>
	 </div>
</div> 