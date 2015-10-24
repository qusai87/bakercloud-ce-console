<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
 

class Shelf extends Admin_Controller {
  	protected $uploadsDirectory = '';
	public function __construct() {
	   parent::__construct();
	   $this->load->helper(array('url','html','form','file')); 

	   if (!file_exists(__DATABASE_CONFIG_PATH__ . '/' . $this->db->database )) {
            exit;
        } else {
        	$this->uploadsDirectory = __DATABASE_CONFIG_PATH__ . '/' . $this->db->database;
        }
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
			$target_path = $this->uploadsDirectory . '/uploads/packages/'.$this->input->get('file');

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
				    "url": "book://www.mlc.edu.jo/magazines/bakercloud-ce-console/source/magazines/issue/",
				    "orientation": "portrait",
				    "zoomable": false,
				    "-baker-rendering" : "three-cards",
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
        		write_file($target_path.'/book.json',json_encode($book, JSON_PRETTY_PRINT));
        		die(json_encode($book));
        		redirect('admin/shelf/package/'.$this->input->get('file'), 'refresh');
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
	    $absolute_path = $this->uploadsDirectory . '/'.$path_in_url;
	    $absolute_path = rtrim( $absolute_path ,'/' );

	    $dirs = [];

    	$htmlFiles = array();
		$dir = opendir($absolute_path);
		while(false != ($file = readdir($dir))) {
	        if(($file != ".") and ($file != "..")) {
	        	if (strtolower(substr($file, strrpos($file, '.') + 1)) == 'html') {
	        		if (strpos($file,'_') !== 0) {
	        			$htmlFiles[] = $file; // put in array.
	        		}
	        	} else if (is_dir($absolute_path.'/'.$file))   {
	        		$subdir = opendir($absolute_path.'/'.$file);
					while(false != ($subfile = readdir($subdir))) {
				        if(($subfile != ".") and ($subfile != "..")) {
				        	if (strtolower(substr($subfile, strrpos($subfile, '.') + 1)) == 'html') {
				        		if (strpos($subfile,'_') === false) {
				        			$htmlFiles[] = $file.'/'.$subfile; // put in array.
				        		}
				        	} else if (is_dir($absolute_path.'/'.$file.'/'.$subfile))  {
				        		$subdir2 = opendir($absolute_path.'/'.$file.'/'.$subfile);
								while(false != ($subfile2 = readdir($subdir2))) {
							        if(($subfile2 != ".") and ($subfile2 != "..")) {
							        	if (strtolower(substr($subfile2, strrpos($subfile2, '.') + 1)) == 'html') {
							        		if (strpos($subfile2,'_') === false) {
							        			$htmlFiles[] = $file.'/'.$subfile.'/'.$subfile2; // put in array.
							        		}
							        	}
							        } 
								}
				        	}
				        } 
					}
	        	}
	        } 
		}

		natsort($htmlFiles); // sort.

	    // $files = get_filenames($absolute_path);
		//$htmlFiles = [];
	    // foreach ($files as $file) {
	    // 	if (strtolower(substr($file, strrpos($file, '.') + 1)) == 'html') {
	    // 		$htmlFiles[] = $file;
	    // 	}
	    // }
    	//die(var_dump($htmlFiles));

 		$this->load->model('crud_auth');
        $this->load->model('admin/home_menu');

        $var = array();
        // view data
        $data = array(
            'controller' => $controller,
	        'method' => $method,
	        'virtual_root' => $this->uploadsDirectory,
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
	    $file_name = '';
	    foreach ( $segment_array as $segment ) {
	    	if ($path_in_url=='') {
	    		$path_in_url.= 'uploads/packages/';
	    		$generated_in_url.= 'uploads/generated/';
	    	} else {
	    		$path_in_url.= $segment.'/';
	    		$generated_in_url.= $segment.'/';
	    		if ($segment!= 'packages' && $segment!= 'generated' && $segment != 'uploaded')
	    			$file_name .= $segment.'/';
	    	}
	    }
	    $absolute_path = $this->uploadsDirectory . '/'.$path_in_url;
	    $generated_path = $this->uploadsDirectory . '/'.$generated_in_url;
	    $absolute_path = rtrim( $absolute_path ,'/' );
	    $generated_path = rtrim( $generated_path ,'/' );
	    $file_name = rtrim( $file_name ,'/' );

	    if (!is_dir($this->uploadsDirectory . '/uploads/generated')) {
			mkdir($this->uploadsDirectory . '/uploads/generated');
		}

	    //die($absolute_path);
	    $this->load->library('zip');
	    
	    $this->zip->get_files_from_folder($absolute_path,'/');
	    $this->zip->archive($generated_path.'.hpub');
	    $fileName = pathinfo($absolute_path.'.zip',PATHINFO_FILENAME);

	    $this->db->delete('SHELF', array('FILE' => $file_name)); 
	    $this->db->insert('SHELF',array('FILE' => $file_name , 'URL' =>  base_url() . 'index.php/admin/shelf/uploads/generated/'.$fileName.'.hpub' ));

	    //delete_files($absolute_path,true);
	    //rmdir($absolute_path);
	    redirect('admin/shelf/uploads/generated', 'refresh');
	}

	public function uploads() {
		$isDelete = $this->input->get('delete');
		$deleteMode = false;
		if (isset($isDelete) && !empty($isDelete)) {
			$deleteMode = true;
		}
	    $segment_array = $this->uri->segment_array();
	 	
	    // first and second segments are the controller and method
	    $controller = array_shift( $segment_array );
	    $method = array_shift( $segment_array );
	    // absolute path using additional segments
	    $path_in_url = '';
	    $file_name = '';

	    foreach ( $segment_array as $segment ) {
	    	if ($path_in_url=='') {
	    		$path_in_url.= 'uploads/';
	    	} else {
	    		$path_in_url .= $segment.'/';
	    		if ($segment!= 'packages' && $segment!= 'generated' && $segment != 'uploaded')
	    			$file_name .= $segment.'/';
	    	}
	    }
	    
	    $absolute_path = urldecode($this->uploadsDirectory . '/'.$path_in_url); 
	    $absolute_path = rtrim( $absolute_path ,'/' );
	    $file_name = rtrim( $file_name ,'/' );
	    // check if it is a path or file
        if ( is_file($absolute_path) )
        {
        	$path_parts = pathinfo($absolute_path);
        	$webpage = array(
	            'php', 'html'
	        );

        	if ($deleteMode) {
        		//die($file_name);
	        	
	        	if ($path_parts['extension'] == 'hpub') {
	        		$file_name = preg_replace('/\\.[^.\\s]{3,4}$/', '', $file_name);
	        		$this->db->delete('SHELF', array('FILE' => $file_name)); 
	        	}
	        	
	        	unlink($absolute_path);
	        	redirect('admin/shelf/'.dirname($path_in_url));
	        }

        	if ($path_parts['extension'] == 'zip'/* || $path_parts['extension'] == 'hpub'*/) {
        		//die(var_dump($path_parts));
        		$target_path = $this->uploadsDirectory . '/uploads/packages/'.$path_parts['filename'];
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
	    	if ($deleteMode) {
	        	delete_files($absolute_path, true);
	        	rmdir($absolute_path);
	        	redirect('admin/shelf/'.dirname($path_in_url));
	        }
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
	            'virtual_root' => $this->uploadsDirectory,
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
		$targetPath = $this->uploadsDirectory . '/uploads';
		if (!is_dir($targetPath)) {
			mkdir($targetPath);
		}

		if (!empty($_FILES)) {
			$tempFile = $_FILES['file']['tmp_name'];
			$fileName = $_FILES['file']['name'];
			$fileType = $_FILES['file']['type'];
			$file_name = preg_replace('/\\.[^.\\s]{3,4}$/', '', $fileName);
			$info = pathinfo($fileName);
			if ($fileType == 'image/png') {

				$targetPath .= '/covers';
				if (!is_dir($targetPath)) {
					mkdir($targetPath);
				}
				
			}  else if ($fileType == 'application/octet-stream' && $info['extension']  =='hpub') {
				$this->db->delete('SHELF', array('FILE' => $file_name)); 
				$this->db->insert('SHELF',array('FILE' => $file_name , 'URL' =>  base_url() . 'index.php/admin/shelf/uploads/generated/'.$fileName.'.hpub' ));
				$targetPath .= '/generated';
				if (!is_dir($targetPath)) {
					mkdir($targetPath);
				}	
			} else {
				$targetPath .= '/uploaded';
				if (!is_dir($targetPath)) {
					mkdir($targetPath);
				}	
			}

			$targetFile = $targetPath . '/'. $fileName ;
			move_uploaded_file($tempFile, $targetFile);
			
			// if ($fileType == 'application/octet-stream' && $info['extension']  =='hpub')  {
			// 	die(json_encode(array('type' => $info['extension'] )));
			// }
			die(json_encode($_FILES['file']));
			// if you want to save in db,where here
			// with out model just for example
			// $this->load->database(); // load database
		} else {
			echo 'error!';
		}
    }
}
 
/* End of file Shelf.js */
/* Location: ./application/controllers/Shelf.php */
 
 