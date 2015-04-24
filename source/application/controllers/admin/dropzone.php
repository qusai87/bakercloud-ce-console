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

	        if (!$this->input->get('package')) 
	        {
			    $var = array();
		        // view data
		        $data = array(
		            'controller' => $controller,
		            'method' => $method,
		            'virtual_root' => getcwd(),
		            'path_in_url' => $path_in_url,
		            'dirs' => $dirs,
		            'files' => $files,
		            'package' => dirname($path_in_url) == 'uploads/issues'
		        );

		        $var['main_menu'] = $this->home_menu->fetch();
		        $var['main_content'] = $this->load->view('file_browser',$data,true);

		        $this->load->view('layouts/admin/default', $var);
		    } else {
		    	if (empty($files)) {
			        throw new Exception('Archive should\'t be empty');
			    }
			    
			    $this->load->library('zip');
			    
			    $this->zip->get_files_from_folder($absolute_path,'/');
			    $this->zip->archive($absolute_path.'.zip');

			    redirect('admin/dropzone/uploads/issues/', 'refresh');
		    }
	    }
	    else
	    {
	    	$mimeTypes = array(
		        "323"       => "text/h323",
		        "acx"       => "application/internet-property-stream",
		        "ai"        => "application/postscript",
		        "aif"       => "audio/x-aiff",
		        "aifc"      => "audio/x-aiff",
		        "aiff"      => "audio/x-aiff",
		        "asf"       => "video/x-ms-asf",
		        "asr"       => "video/x-ms-asf",
		        "asx"       => "video/x-ms-asf",
		        "au"        => "audio/basic",
		        "avi"       => "video/x-msvideo",
		        "axs"       => "application/olescript",
		        "bas"       => "text/plain",
		        "bcpio"     => "application/x-bcpio",
		        "bin"       => "application/octet-stream",
		        "bmp"       => "image/bmp",
		        "c"         => "text/plain",
		        "cat"       => "application/vnd.ms-pkiseccat",
		        "cdf"       => "application/x-cdf",
		        "cer"       => "application/x-x509-ca-cert",
		        "class"     => "application/octet-stream",
		        "clp"       => "application/x-msclip",
		        "cmx"       => "image/x-cmx",
		        "cod"       => "image/cis-cod",
		        "cpio"      => "application/x-cpio",
		        "crd"       => "application/x-mscardfile",
		        "crl"       => "application/pkix-crl",
		        "crt"       => "application/x-x509-ca-cert",
		        "csh"       => "application/x-csh",
		        "css"       => "text/css",
		        "dcr"       => "application/x-director",
		        "der"       => "application/x-x509-ca-cert",
		        "dir"       => "application/x-director",
		        "dll"       => "application/x-msdownload",
		        "dms"       => "application/octet-stream",
		        "doc"       => "application/msword",
		        "dot"       => "application/msword",
		        "dvi"       => "application/x-dvi",
		        "dxr"       => "application/x-director",
		        "eps"       => "application/postscript",
		        "etx"       => "text/x-setext",
		        "evy"       => "application/envoy",
		        "exe"       => "application/octet-stream",
		        "fif"       => "application/fractals",
		        "flr"       => "x-world/x-vrml",
		        "gif"       => "image/gif",
		        "gtar"      => "application/x-gtar",
		        "gz"        => "application/x-gzip",
		        "h"         => "text/plain",
		        "hdf"       => "application/x-hdf",
		        "hlp"       => "application/winhlp",
		        "hqx"       => "application/mac-binhex40",
		        "hta"       => "application/hta",
		        "htc"       => "text/x-component",
		        "htm"       => "text/html",
		        "html"      => "text/html",
		        "htt"       => "text/webviewhtml",
		        "ico"       => "image/x-icon",
		        "ief"       => "image/ief",
		        "iii"       => "application/x-iphone",
		        "ins"       => "application/x-internet-signup",
		        "isp"       => "application/x-internet-signup",
		        "jfif"      => "image/pipeg",
		        "jpe"       => "image/jpeg",
		        "jpeg"      => "image/jpeg",
		        "jpg"       => "image/jpeg",
		        "js"        => "application/x-javascript",
		        "latex"     => "application/x-latex",
		        "lha"       => "application/octet-stream",
		        "lsf"       => "video/x-la-asf",
		        "lsx"       => "video/x-la-asf",
		        "lzh"       => "application/octet-stream",
		        "m13"       => "application/x-msmediaview",
		        "m14"       => "application/x-msmediaview",
		        "m3u"       => "audio/x-mpegurl",
		        "man"       => "application/x-troff-man",
		        "mdb"       => "application/x-msaccess",
		        "me"        => "application/x-troff-me",
		        "mht"       => "message/rfc822",
		        "mhtml"     => "message/rfc822",
		        "mid"       => "audio/mid",
		        "mny"       => "application/x-msmoney",
		        "mov"       => "video/quicktime",
		        "movie"     => "video/x-sgi-movie",
		        "mp2"       => "video/mpeg",
		        "mp3"       => "audio/mpeg",
		        "mpa"       => "video/mpeg",
		        "mpe"       => "video/mpeg",
		        "mpeg"      => "video/mpeg",
		        "mpg"       => "video/mpeg",
		        "mpp"       => "application/vnd.ms-project",
		        "mpv2"      => "video/mpeg",
		        "ms"        => "application/x-troff-ms",
		        "mvb"       => "application/x-msmediaview",
		        "nws"       => "message/rfc822",
		        "oda"       => "application/oda",
		        "p10"       => "application/pkcs10",
		        "p12"       => "application/x-pkcs12",
		        "p7b"       => "application/x-pkcs7-certificates",
		        "p7c"       => "application/x-pkcs7-mime",
		        "p7m"       => "application/x-pkcs7-mime",
		        "p7r"       => "application/x-pkcs7-certreqresp",
		        "p7s"       => "application/x-pkcs7-signature",
		        "pbm"       => "image/x-portable-bitmap",
		        "pdf"       => "application/pdf",
		        "pfx"       => "application/x-pkcs12",
		        "pgm"       => "image/x-portable-graymap",
		        "pko"       => "application/ynd.ms-pkipko",
		        "pma"       => "application/x-perfmon",
		        "pmc"       => "application/x-perfmon",
		        "pml"       => "application/x-perfmon",
		        "pmr"       => "application/x-perfmon",
		        "pmw"       => "application/x-perfmon",
		        "pnm"       => "image/x-portable-anymap",
		        "pot"       => "application/vnd.ms-powerpoint",
		        "ppm"       => "image/x-portable-pixmap",
		        "pps"       => "application/vnd.ms-powerpoint",
		        "ppt"       => "application/vnd.ms-powerpoint",
		        "prf"       => "application/pics-rules",
		        "ps"        => "application/postscript",
		        "pub"       => "application/x-mspublisher",
		        "qt"        => "video/quicktime",
		        "ra"        => "audio/x-pn-realaudio",
		        "ram"       => "audio/x-pn-realaudio",
		        "ras"       => "image/x-cmu-raster",
		        "rgb"       => "image/x-rgb",
		        "rmi"       => "audio/mid",
		        "roff"      => "application/x-troff",
		        "rtf"       => "application/rtf",
		        "rtx"       => "text/richtext",
		        "scd"       => "application/x-msschedule",
		        "sct"       => "text/scriptlet",
		        "setpay"    => "application/set-payment-initiation",
		        "setreg"    => "application/set-registration-initiation",
		        "sh"        => "application/x-sh",
		        "shar"      => "application/x-shar",
		        "sit"       => "application/x-stuffit",
		        "snd"       => "audio/basic",
		        "spc"       => "application/x-pkcs7-certificates",
		        "spl"       => "application/futuresplash",
		        "src"       => "application/x-wais-source",
		        "sst"       => "application/vnd.ms-pkicertstore",
		        "stl"       => "application/vnd.ms-pkistl",
		        "stm"       => "text/html",
		        "svg"       => "image/svg+xml",
		        "sv4cpio"   => "application/x-sv4cpio",
		        "sv4crc"    => "application/x-sv4crc",
		        "t"         => "application/x-troff",
		        "tar"       => "application/x-tar",
		        "tcl"       => "application/x-tcl",
		        "tex"       => "application/x-tex",
		        "texi"      => "application/x-texinfo",
		        "texinfo"   => "application/x-texinfo",
		        "tgz"       => "application/x-compressed",
		        "tif"       => "image/tiff",
		        "tiff"      => "image/tiff",
		        "tr"        => "application/x-troff",
		        "trm"       => "application/x-msterminal",
		        "tsv"       => "text/tab-separated-values",
		        "txt"       => "text/plain",
		        "uls"       => "text/iuls",
		        "ustar"     => "application/x-ustar",
		        "vcf"       => "text/x-vcard",
		        "vrml"      => "x-world/x-vrml",
		        "wav"       => "audio/x-wav",
		        "wcm"       => "application/vnd.ms-works",
		        "wdb"       => "application/vnd.ms-works",
		        "wks"       => "application/vnd.ms-works",
		        "wmf"       => "application/x-msmetafile",
		        "wps"       => "application/vnd.ms-works",
		        "wri"       => "application/x-mswrite",
		        "wrl"       => "x-world/x-vrml",
		        "wrz"       => "x-world/x-vrml",
		        "xaf"       => "x-world/x-vrml",
		        "xbm"       => "image/x-xbitmap",
		        "xla"       => "application/vnd.ms-excel",
		        "xlc"       => "application/vnd.ms-excel",
		        "xlm"       => "application/vnd.ms-excel",
		        "xls"       => "application/vnd.ms-excel",
		        "xlsx"      => "vnd.ms-excel",
		        "xlt"       => "application/vnd.ms-excel",
		        "xlw"       => "application/vnd.ms-excel",
		        "xof"       => "x-world/x-vrml",
		        "xpm"       => "image/x-xpixmap",
		        "xwd"       => "image/x-xwindowdump",
		        "z"         => "application/x-compress",
		        "zip"       => "application/zip"
		    );
	        // is it a file?
	        if ( is_file($absolute_path) )
	        {
	        	$path_parts = pathinfo($absolute_path);
	        	$webpage = array(
		            'php', 'html'
		        );
	        	if ($path_parts['extension'] == 'zip') {
	        		$target_path = getcwd().'/uploads/issues/test';
	    			$target_path = rtrim( $target_path ,'/' );
		        	$zip = new ZipArchive;

			     	$res = $zip->open($absolute_path);
			    	if($res==TRUE)
			        {  
			            $zip->extractTo($target_path);

			            $zip->close();

			            redirect('admin/dropzone/uploads/issues/test/', 'refresh');
			        }
	        	} else if(isset($path_parts['extension']) && in_array( $path_parts['extension'], $webpage) ) {
	        		@readfile( $absolute_path );
		        } else {
	        		// open it
		            header ('Cache-Control: no-store, no-cache, must-revalidate');
		            header ('Cache-Control: pre-check=0, post-check=0, max-age=0');
		            header ('Pragma: no-cache');
		 
		            $text_types = array(
		                'css', 'js', 'txt', 'htaccess', 'xml'
		            );
		            
		            // download necessary ?
		            if( isset($path_parts['extension']) && in_array( $path_parts['extension'], $text_types) ) {
		                header('Content-Type:'.$mimeTypes[$path_parts['extension']]);
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
 
 