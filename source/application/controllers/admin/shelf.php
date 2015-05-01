<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
 
class Shelf extends Admin_Controller {
  
	public function __construct() {
	   parent::__construct();
	   $this->load->helper(array('url','html','form','file')); 
	}
 
	public function index() {
		$this->load->model('crud_auth');
        $this->load->model('admin/home_menu');

        $var = array();
        
        $var['main_menu'] = $this->home_menu->fetch();
        $var['main_content'] = $this->load->view('shelf_view',$var,true);
        $var['dropzone'] = true;
        $this->load->view('layouts/admin/default', $var);
	}
	
	public function generate() {
		$input_data = trim(file_get_contents('php://input'));
		$input_data = rtrim(file_get_contents('php://input'),',');
		if (isset($input_data) && !empty($input_data)) {
			$contents = explode(',', $input_data); 
			$target_path = getcwd().'/uploads/packages/'.$this->input->get('file');

			$json = read_file($target_path.'/book.json');

			if ($json) {
				$book =json_decode($json);
			} else {
				$book = json_decode('{
				    "hpub": 1,
				    "title": "MLC Digital Book 01",
				    "author": ["Faris"],
				    "creator": ["MLC"],
				    "date": "2015-04-01",
				    "url": "book://interactiveapps.mobi:8888/wp-content/uploads/2014/magazines/issue/",
				    "orientation": "portrait",
				    "zoomable": false,

				    "-baker-background": "#ffffff",
				    "-baker-vertical-bounce": true,
				    "-baker-media-autoplay": true,
				    "-baker-background-image-portrait": "gfx/background-portrait.png",
				    "-baker-background-image-landscape": "gfx/background-landscape.png",
				    "-baker-page-numbers-color": "#000000",

				    "contents": [
				    ]
				}');
				$book->url .= $this->input->get('file');
				$book->contents  = $contents;
			}
        	
        	
        	if (isset($book)) {
        		write_file($target_path.'/book_test.json',json_encode($book, JSON_PRETTY_PRINT));
        		die(json_encode($book));
        		redirect('admin/shelf/package/'.$this->input->get('file'), 'refresh');
        	} else {
        		
        	}

			exit(var_dump($contents));
			
		}
		$segment_array = $this->uri->segment_array();
	 	
	    // first and second segments are the controller and method
	    $controller = array_shift( $segment_array );
	    $method = array_shift( $segment_array );
	    // absolute path using additional segments
	    $path_in_url = '';
	    foreach ( $segment_array as $segment ) {
	    	if ($path_in_url=='') {
	    		$path_in_url.= 'uploads/packages/';
	    	} else {
	    		$path_in_url.= $segment.'/';
	    	}
	    }
	    $absolute_path = getcwd().'/'.$path_in_url;
	    $absolute_path = rtrim( $absolute_path ,'/' );

	    $dirs = [];
    	$files = get_filenames($absolute_path);
	    $htmlFiles = [];
	    foreach ($files as $file) {
	    	if (strtolower(substr($file, strrpos($file, '.') + 1)) == 'html') {
	    		$htmlFiles[] = $file;
	    	}
	    }
    	//die(var_dump($htmlFiles));

 		$this->load->model('crud_auth');
        $this->load->model('admin/home_menu');

        $var = array();
        // view data
        $data = array(
            'controller' => $controller,
	        'method' => $method,
	        'virtual_root' => getcwd(),
	        'path_in_url' => $path_in_url,
            'virtual_root' => $absolute_path,
            'files' => $htmlFiles
        );

        $var['main_menu'] = $this->home_menu->fetch();
        $var['main_content'] = $this->load->view('generate_view',$data,true);
        $var['sortable'] = true;

        $this->load->view('layouts/admin/default', $var);

	    
	}
	public function package() {
		$segment_array = $this->uri->segment_array();
	 	
	    // first and second segments are the controller and method
	    $controller = array_shift( $segment_array );
	    $method = array_shift( $segment_array );
	    // absolute path using additional segments
	    $path_in_url = '';
	    $generated_in_url = '';
	    foreach ( $segment_array as $segment ) {
	    	if ($path_in_url=='') {
	    		$path_in_url.= 'uploads/packages/';
	    		$generated_in_url.= 'uploads/generated/';
	    	} else {
	    		$path_in_url.= $segment.'/';
	    		$generated_in_url.= $segment.'/';
	    	}
	    }
	    $absolute_path = getcwd().'/'.$path_in_url;
	    $generated_path = getcwd().'/'.$generated_in_url;
	    $absolute_path = rtrim( $absolute_path ,'/' );
	    $generated_path = rtrim( $generated_path ,'/' );

	    if (!is_dir(getcwd() . '/uploads/generated')) {
			mkdir(getcwd() . '/uploads/generated');
		}

	    //die($absolute_path);
	    $this->load->library('zip');
	    
	    $this->zip->get_files_from_folder($absolute_path,'/');
	    $this->zip->archive($generated_path.'.hpub');

	    //delete_files($absolute_path,true);
	    //rmdir($absolute_path);
	    redirect('admin/shelf/uploads/generated', 'refresh');
	}

	public function uploads() {
	    $segment_array = $this->uri->segment_array();
	 	
	    // first and second segments are the controller and method
	    $controller = array_shift( $segment_array );
	    $method = array_shift( $segment_array );
	    // absolute path using additional segments
	    $path_in_url = '';
	    $link_path = '';
	    $file_name = '';

	    foreach ( $segment_array as $segment ) {
	    	if ($path_in_url=='') {
	    		$path_in_url.= 'uploads/';
	    		$link_path.= 'generate/';
	    	} else {
	    		$path_in_url .= $segment.'/';
	    		if ($segment!= 'packages')
	    			$file_name .= $segment.'/';
	    	}
	    }
	    $absolute_path = getcwd().'/'.$path_in_url;
	    $absolute_path = rtrim( $absolute_path ,'/' );
	    // check if it is a path or file
	    //die($absolute_path );
        if ( is_file($absolute_path) )
        {
        	$path_parts = pathinfo($absolute_path);
        	$webpage = array(
	            'php', 'html'
	        );

        	if ($path_parts['extension'] == 'zip'/* || $path_parts['extension'] == 'hpub'*/) {
        		//die(var_dump($path_parts));
        		$target_path = getcwd().'/uploads/packages/'.$path_parts['filename'];
    			$target_path = rtrim( $target_path ,'/' );
	        	$zip = new ZipArchive;

		     	$res = $zip->open($absolute_path);
		    	if($res==TRUE)
		        {  
		            $zip->extractTo($target_path);

		            $zip->close();

		            redirect('admin/shelf/generate/'.$path_parts['filename'].'?file='.$path_parts['filename'], 'refresh');
		        }
        	} else if(isset($path_parts['extension']) && in_array( $path_parts['extension'], $webpage) ) {
        		@readfile( $absolute_path );
	        } else {
        		// open it
	            header ('Cache-Control: no-store, no-cache, must-revalidate');
	            header ('Cache-Control: pre-check=0, post-check=0, max-age=0');
	            header ('Pragma: no-cache');
	 
	            $text_types = array(
	                'css', 'js', 'txt', 'htaccess', 'xml','jpg','svg'
	            );
	            
	            // download necessary ?
	            if( isset($path_parts['extension']) && in_array( $path_parts['extension'], $text_types) ) {
	                header('Content-Type:'.$this->mimeTypes[$path_parts['extension']]);
	            } else {
	                header('Content-Type: application/x-download');
	                header('Content-Length: ' . filesize( $absolute_path ));
	                header('Content-Disposition: attachment; filename=' . basename( $absolute_path ));
	            }
	 
	            @readfile( $absolute_path );
        	}
        }
        else 
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
	        //die(dirname($path_in_url) );
	        $data = array(
	            'controller' => $controller,
	            'method' => $method,
	            'virtual_root' => getcwd(),
	            'path_in_url' => $path_in_url,
	            'dirs' => $dirs,
	            'files' => $files,
	            'package' => dirname($path_in_url) == 'uploads/packages' ?  '../../generate/'.$file_name .'?file='.$file_name: false
	        );

	        $var['main_menu'] = $this->home_menu->fetch();
	        $var['main_content'] = $this->load->view('browse_view',$data,true);

	        $this->load->view('layouts/admin/default', $var);
	    }
	}

	public function upload() {
		$targetPath = getcwd() . '/uploads';
		if (!is_dir($targetPath)) {
			mkdir($targetPath);
		}
		$targetPath .= '/uploaded';
		if (!is_dir($targetPath)) {
			mkdir($targetPath);
		}

		if (!empty($_FILES)) {
			$tempFile = $_FILES['file']['tmp_name'];
			$fileName = $_FILES['file']['name'];
			
			$targetFile = $targetPath . '/'. $fileName ;
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
 
/* End of file Shelf.js */
/* Location: ./application/controllers/Shelf.php */
 
 