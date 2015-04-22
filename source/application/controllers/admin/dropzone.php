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
 
 