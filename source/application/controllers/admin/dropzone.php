<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
 
class Dropzone extends Admin_Controller {
  
	public function __construct() {
	   parent::__construct();
	   $this->load->helper(array('url','html','form')); 
	}
 
	public function index() {
		$this->load->model('crud_auth');
        $this->load->model('admin/home_menu');

        $var = array();
        
        $var['main_menu'] = $this->home_menu->fetch();
        $var['main_content'] = $this->load->view('dropzone_view',$var,true);
        $var['dropzone'] = true;
        $this->load->view('layouts/admin/default', $var);
	}
	
	public function uploads() {
	    $segment_array = $this->uri->segment_array();
	 	
	    // first and second segments are the controller and method
	    $controller = array_shift( $segment_array );
	    $method = array_shift( $segment_array );
	    // absolute path using additional segments
	    $path_in_url = '';
	    foreach ( $segment_array as $segment ) $path_in_url.= $segment.'/';
	    $absolute_path = getcwd().'/'.$path_in_url;
	    $absolute_path = rtrim( $absolute_path ,'/' );
	 	
		//var_dump( $absolute_path);
	    // check if it is a path or file
	    if ( is_dir( $absolute_path ))
	    {
	 
	        $dirs = array();
	        $files = array();
	        // fetching directory
	        if ( $handle = @opendir( $absolute_path ))
	        {
	            while ( false !== ($file = readdir( $handle )))
	            {
	                if (( $file != "." AND $file != ".." ))
	                {
	                    if ( is_dir( $absolute_path.'/'.$file ))
	                    {
	                        $dirs[]['name'] = $file;
	                    }
	                    else
	                    {
	                        $files[]['name'] = $file;
	                    }
	                }
	            }
	            closedir( $handle );
	            sort( $dirs );
	            sort( $files );
	 
	        }
	        // parent folder
	        // ensure it exists and is the first in array
	        if ( $path_in_url != '' )
	            array_unshift ( $dirs, array( 'name' => '..' ));
	 	

	 		$this->load->model('crud_auth');
	        $this->load->model('admin/home_menu');

		    $var = array();

	        // view data
	        $data = array(
	            'controller' => $controller,
	            'method' => $method,
	            'virtual_root' => getcwd(),
	            'path_in_url' => $path_in_url,
	            'dirs' => $dirs,
	            'files' => $files,
	        );

	        $var['main_menu'] = $this->home_menu->fetch();
	        $var['main_content'] = $this->load->view('file_browser',$data,true);

	        $this->load->view('layouts/admin/default', $var);
	    }
	    else
	    {
	        // is it a file?
	        if ( is_file($absolute_path) )
	        {
	            // open it
	            header ('Cache-Control: no-store, no-cache, must-revalidate');
	            header ('Cache-Control: pre-check=0, post-check=0, max-age=0');
	            header ('Pragma: no-cache');
	 
	            $text_types = array(
	                'php', 'css', 'js', 'html', 'txt', 'htaccess', 'xml'
	                );
	            $path_parts = pathinfo($absolute_path);
	            // download necessary ?
	            if( isset($path_parts['extension']) && in_array( $path_parts['extension'], $text_types) ) {
	                header('Content-Type: text/plain');
	            } else {
	                header('Content-Type: application/x-download');
	                header('Content-Length: ' . filesize( $absolute_path ));
	                header('Content-Disposition: attachment; filename=' . basename( $absolute_path ));
	            }
	 
	            @readfile( $absolute_path );
	        }
	        else
	        {
	            show_404();
	        }
	    }
	}

	public function upload() {
		if (!empty($_FILES)) {
			$tempFile = $_FILES['file']['tmp_name'];
			$fileName = $_FILES['file']['name'];
			$targetPath = getcwd() . '/uploads/';
			$targetFile = $targetPath . $fileName ;
			move_uploaded_file($tempFile, $targetFile);
			// if you want to save in db,where here
			// with out model just for example
			// $this->load->database(); // load database
			// $this->db->insert('file_table',array('file_name' => $fileName));
		} else {
			echo 'error!';
		}
    }
}
 
/* End of file dropzone.js */
/* Location: ./application/controllers/dropzone.php */
 
 