<?php
$crudAuth = $this->session->userdata('CRUD_AUTH');
?>
<div id="header"><div class="container"><h1><?php echo $this->lang->line('__LBL_DASHBOARD__'); ?></h1></div></div>
<div class="container">
	     <div class="alert alert-success">
    	  <strong>Welcome to Modern Language Center (MLC) administration Console!</strong>
    	  </div>
    	  
    	  <h3>Get Started!</h3>    	  
    	  <p>
    	  		To get started using Modern Language Center (MLC), you should create a Publication that corresponds to your iOS Baker Newsstand application.  After you create the Publication, you can add Issues.
				 <ul>
			    <li><a href="<?php echo base_url(); ?>index.php/admin/scrud/browse?table=PUBLICATION">Create/Edit Publications</a></li>
			    <li><a href="<?php echo base_url(); ?>index.php/admin/scrud/browse?table=ISSUES">Create/Edit Issues</a></li>		    			    			    			    			    			    			    
			    <li><a href="<?php echo base_url(); ?>index.php/admin/scrud/browse?table=ISSUES">Open Shelf Manager</a></li>		    			    			    			    			    			    			    
			    </ul>   
    	  </p>
    	  
    	  <p>
				The administration console allows you to manage / view the following backend data related to your Modern Language Center (MLC) installation.
			    <table class="table table-bordered">
			    <thead>
			    <tr>
			    <th>Data</th>
			    <th>Purpose</th>
			    </tr>
			    </thead>
			    <tbody>
			    <tr>
			    <td>Publication</td>
			    <td>Defines the list of publications that you are supporting with your Modern Language Center (MLC) backend installation.  Each publication would correspond to a deployed iOS Baker Newsstand application.</td>
			    </tr>
  			    <tr>
			    <td>Issues</td>
			    <td>Defines the available issues for each of your Publications being managed by Modern Language Center (MLC).  These issues are what show up in your iOS Baker Newsstand application.</td>
			    </tr>
			    <tr>
			    <td>Shelf</td>
			    <td>Manage issue packages</td>
			    </tr>
			    <tr>
			    <td>Purchases</td>
			    <td>System table that tracks the issues purchased by end users of your Publications.  Both single "one off" In-App purchased issues are tracked here as well as issues falling under the coverage of an active paid Newsstand subscription.</td>
			    </tr>
			    <tr>
			    <td>Receipts</td>
			    <td>System table that tracks receipt information from In-App Purchases and Subscription purchases.  Stores Apple data from receipt and acts as the central repository of information used to validate downloads and subscription terms.</td>
			    </tr>
			    <tr>
			    <td>Subscriptions</td>
			    <td>System table that summarizes active Newsstand Subscriptions for users.  Used by the API in conjunction with the Receipts data to manage shelf and available downloads.</td>
			    </tr>
			    <tr>
			    <td>APNS Tokens</td>
			    <td>System table that tracks APNS Push Notification tokens for users and Publications.</td>
			    </tr>
			    <tr>
			    <td>System Log</td>
			    <td>System table that tracks and logs debugging level information including Error messages.</td>
			    </tr>
			    </tbody>
			    </table> 	  
    	  </p>
		  
    	  <p>
    	  		To manage your Publications and Issues, use the <strong>Publication Management</strong> dropdown menu.  To view/manage the backend Modern Language Center (MLC) data, use the <strong>Data Browser</strong> dropdown menu, both located at the top of this page.
    	  </p>
        <hr />
        <footer>
            <p><?php echo $this->lang->line('__LBL_COPYRIGHT__'); ?></p>
            <img class="pull-right" src="<?php echo base_url(); ?>media/images/MLCLogo.png">
        </footer>
    </div>
</div>