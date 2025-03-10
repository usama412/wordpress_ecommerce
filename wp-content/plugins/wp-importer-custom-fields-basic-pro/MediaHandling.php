<?php
/******************************************************************************************
 * Copyright (C) Smackcoders. - All Rights Reserved under Smackcoders Proprietary License
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * You can contact Smackcoders at email address info@smackcoders.com.
 *******************************************************************************************/

namespace Smackcoders\CFCSV;

if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly

class MediaHandling{
	private static $instance=null,$smack_instance;
	public $header_array;
	public $value_array;

	public function __construct(){		
		require_once(ABSPATH . 'wp-admin/includes/image.php');
		// require_once(ABSPATH . 'wp-admin/includes/file.php');
		add_action('wp_ajax_zip_upload' , array($this , 'zipImageUpload'));
		add_action('wp_ajax_image_options', array($this , 'imageOptions'));
		add_action('wp_ajax_delete_image' , array($this , 'deleteImage'));
		add_action('wp_ajax_media_report' , array($this , 'mediaReport'));
	}

	public static function imageOptions(){	
		check_ajax_referer('smack-importer-custom-fields-basic-pro', 'securekey');
		$media_settings['use_ExistingImage'] = sanitize_text_field($_POST['use_ExistingImage']);
		$media_settings['overwriteImage'] = sanitize_text_field($_POST['overwriteImage']);
		$media_settings['enable_postcontent_image'] = sanitize_text_field($_POST['postContent_image_option']);
		$media_settings['newImage'] = sanitize_text_field($_POST['newImage']);
		$media_settings['title'] = sanitize_text_field($_POST['title']);
		$media_settings['caption'] = sanitize_text_field($_POST['caption']);
		$media_settings['alttext'] = sanitize_text_field($_POST['alttext']);		
		$media_settings['description'] = sanitize_text_field($_POST['description']);		
		$media_settings['file_name'] = sanitize_text_field($_POST['file_name']);		
		$media_settings['thumbnail'] = sanitize_text_field($_POST['thumbnail']);		
		$media_settings['media_handle_option'] = sanitize_text_field($_POST['media_handle_option']);		
		$media_settings['medium'] = sanitize_text_field($_POST['medium']);		
		$media_settings['medium_large'] = sanitize_text_field($_POST['medium_large']);		
		$media_settings['large'] = sanitize_text_field($_POST['large']);		
		$media_settings['custom'] = sanitize_text_field($_POST['custom']);
		$media_settings['custom_slug'] = sanitize_text_field($_POST['custom_slug']);
		$media_settings['custom_slug'] = sanitize_text_field($_POST['custom_width']);
		$media_settings['custom_height'] = sanitize_text_field($_POST['custom_height']);
		$image_info = array(
			'media_settings'  => $media_settings
		);
		update_option( 'smack_image_options', $image_info );
		$result['success'] = 'true';
		echo wp_json_encode($result);
		wp_die();
	}
	public static function getInstance() {
		if (MediaHandling::$instance == null) {
			MediaHandling::$instance = new MediaHandling;
			MediaHandling::$smack_instance = SmackCSV::getInstance();
			return MediaHandling::$instance;
		}
		return MediaHandling::$instance;
	}

	public function zipImageUpload(){
		check_ajax_referer('smack-importer-custom-fields-basic-pro', 'securekey');
		$zip_file_name = $_FILES['zipFile']['name'];
		$hash_key = MediaHandling::$smack_instance->convert_string2hash_key($zip_file_name);
		$media_dir = wp_get_upload_dir();
		$upload_dir = MediaHandling::$smack_instance->create_upload_dir();
		$path = $upload_dir . $hash_key . '.zip';	
		$extract_path = $media_dir['path'] . '/';
		chmod($path, 0777);
		move_uploaded_file($_FILES['zipFile']['tmp_name'], $path);
		$zip = new \ZipArchive;
		$res = $zip->open($path);
		if ($res === TRUE) {
			for ($i = 0; $i < $zip->numFiles; $i++) {
				$filename[$i] = $zip->getNameIndex($i);  
				if ( substr( $filename[$i], -1 ) == '/' ) continue;   	
				$fp = $zip->getStream( $filename[$i] );
				$size[$i] = $zip->statIndex($i)['size'];
				$kbsize[$i] = $this->convertToReadableSize($size[$i]);
				$ofp = fopen( $extract_path.'/'.basename($filename[$i]), 'w' );	
				if ( ! $fp )
					throw new Exception('Unable to extract the file.');

				while ( ! feof( $fp ) )
					fwrite( $ofp, fread($fp, 8192) );

				fclose($fp);
				fclose($ofp);
			}
			$zip->close();
			$result['success'] = true;
			$result['zip_file_name'] = $zip_file_name;
			$result['count'] = $zip->numFiles;
			$result['filename'] = $filename;
			$result['size'] = $kbsize;
		}
		else{
			$result['success'] = false;
		}
		echo wp_json_encode($result);
		wp_die();
	}

	public function convertToReadableSize($size){
		$base = log($size) / log(1024);
		$suffix = array("", "KB", "MB", "GB", "TB");
		$f_base = floor($base);
		return round(pow(1024, $base - floor($base)), 1) . $suffix[$f_base];
	}

	public function deleteImage(){
		check_ajax_referer('smack-importer-custom-fields-basic-pro', 'securekey');
		$image = sanitize_text_field($_POST['image']);
		$media_dir = wp_get_upload_dir();
		$names = glob($media_dir['path'].'/'.'*.*');
		foreach($names as $values){
			if (strpos($values, $image) !== false) {
				unlink($values);
			}
		}   
		$result['success'] = 'true';
		echo wp_json_encode($result);
		wp_die();     
	}
	public function media_handling($img_url , $post_id , $data_array = null  ,$module = null, $image_type = null ,$hash_key = null,$templatekey = null,$unikey = null,$unikey_name = null, $header_array = null , $value_array = null){		
		// $encodedurl = urlencode($img_url);
		// $img_url = urldecode($encodedurl);			
		$img_url = urldecode($img_url);
		global $wpdb;
		$url = parse_url($img_url);
		$media_handle = get_option('smack_image_options');	
		if(isset($url['scheme'])){
			if($url['scheme'] == 'http' || $url['scheme'] == 'https' ){		
			$image_name = basename($img_url);
			$image_title = sanitize_file_name( pathinfo( $image_name, PATHINFO_FILENAME ) );
			}
		}else{
				$image_title=preg_replace('/\\.[^.\\s]{3,4}$/', '', $img_url);
			}			

			if( strpos($img_url, 'wp-importer-custom-fields-basic-pro/assets/images/loading-image.jpg') !== false) {
				$existing_loading_image_id = $wpdb->get_results("SELECT ID FROM {$wpdb->prefix}posts WHERE post_name LIKE 'loading-image%' AND guid LIKE '%loading-image%' ", ARRAY_A);
				if(!empty($existing_loading_image_id[0]['ID'])){
					$attach_id = $existing_loading_image_id[0]['ID'];
					return $attach_id;
				}
			}
	
		
		// Download external images to your media if true
		if($media_handle['media_settings']['media_handle_option'] == 'true'){
			if($media_handle['media_settings']['use_ExistingImage'] == 'true'){
				if(empty($img_url)){
					$attach_id=$img_url;
					$attachment=$wpdb->get_var("SELECT meta_value FROM ".$wpdb->prefix."postmeta WHERE meta_key='$data_array'");
					if($attachment){
						delete_post_thumbnail($post_id);
						
					}
					else{
						set_post_thumbnail( $post_id, $attach_id );
						$this->imageMetaImport($attach_id,$media_handle,$header_array,$value_array);
					}
					return $attach_id;
				}
				
					
				if(is_numeric($img_url)){
					$attach_id=$img_url;
						if($attach_id == 0){
							delete_post_thumbnail($post_id);
						}
						else{
							if(!empty($data_array['featured_image'])) {
								set_post_thumbnail( $post_id, $attach_id );
							}
						}
						$this->imageMetaImport($attach_id,$media_handle,$header_array,$value_array);
						return $attach_id;
					}
				/*if(is_numeric($img_url)){
					$attach_id=$img_url;
					if(!empty($data_array['featured_image'])) {
						set_post_thumbnail( $post_id, $attach_id );
					}
					$this->imageMetaImport($attach_id,$media_handle,$header_array,$value_array);
					return $attach_id;
				}*/
				else{
					$attachment_id = $wpdb->get_results("select ID from {$wpdb->prefix}posts where post_title= '$image_title'" ,ARRAY_A);

				}	
				if(!empty($attachment_id)){
					foreach($attachment_id as $value){
						$attach_id = $value['ID'];
						if(!wp_get_attachment_url($attach_id)){

							$attach_id = $this->image_function($img_url , $post_id , $data_array, '', 'use_existing_image',$header_array , $value_array );	
							if($attach_id)
							$this->media_events($post_id,$attach_id,$img_url,$hash_key,$templatekey,$module,$image_type);
						}else{							
                                if(!empty($data_array['featured_image'])) {
								set_post_thumbnail( $post_id, $attach_id );
						      }
						}
					}
					$this->imageMetaImport($attach_id,$media_handle,$header_array,$value_array);
				}
				else{
					$attach_id = $this->image_function($img_url , $post_id , $data_array, '', 'use_existing_image',$header_array , $value_array);
					if($attach_id)
							$this->media_events($post_id,$attach_id,$img_url,$hash_key,$templatekey,$module,$image_type);
				}
				
				
			}
			elseif($media_handle['media_settings']['overwriteImage'] == 'true'){

					$fimg_name = @basename($img_url);
					$uploadpath=explode(".",$fimg_name);
					$imagename=$uploadpath[0];
					$image_title = str_replace(' ', '-', trim($imagename));
					$get_id = $wpdb->get_results("select ID from {$wpdb->prefix}posts where post_title = '$image_title' AND post_type = 'attachment' limit 1" );
					
					if(!empty($get_id)){
						foreach($get_id as $value){
							$attach_id = $value->ID;
							
							$this->overwrite($attach_id , $img_url);
							if(!empty($data_array['featured_image'])) {
								set_post_thumbnail( $post_id, $attach_id );
							}
						}
				    }
				else{
					$attach_id = $this->image_function($img_url , $post_id , $data_array,'','',$header_array , $value_array);
					if($attach_id)
							$this->media_events($post_id,$attach_id,$img_url,$hash_key,$templatekey,$module,$image_type);
				}
			}
			else{
				$attach_id = $this->image_function($img_url , $post_id , $data_array,'','',$header_array , $value_array);
				if($attach_id)
							$this->media_events($post_id,$attach_id,$img_url,$hash_key,$templatekey,$module,$image_type);
			}


		}
		else{ // Use local url if available, leave the post as attachment free 
			$guid_url = $img_url;
			$attachment_id = $wpdb->get_results("select ID from {$wpdb->prefix}posts where guid = '$guid_url'" ,ARRAY_A);
			if(!empty($attachment_id)){

				foreach($attachment_id as $value){
					$attach_id = $value['ID'];
					if($_wp_attachment_metadata = get_post_meta($attach_id, '_wp_attachment_metadata', true)){
						// When an attachment is available on Media and not has attachment link
						if(!is_array($_wp_attachment_metadata)){
							$attach_id = $this->image_function($img_url , $post_id , $data_array,'','',$header_array , $value_array);
							if($attach_id)
							$this->media_events($post_id,$attach_id,$img_url,$hash_key,$templatekey,$module,$image_type);
						}
					}
				}
			}
		}		
		if(empty($attach_id)) {		
			$result = $wpdb->update( $wpdb->prefix . 'ultimate_cf_importer_shortcode_manager' , 
            array( 
                'status' => 'Failed',
            ), 
            array( 'hash_key' => $hash_key ,
					'image_shortcode' => $image_type,
					'original_image' => $img_url,
					'post_id' => $post_id
            ) 
		);
		}
		return $attach_id;		
	}

	function media_events($id,$attach_id,$url,$hash_key,$templatekey,$module,$type){		
		global $wpdb;	
		if( strpos($url, 'assets/images/loading-image.jpg') === false) {
		$wpdb->get_results( "INSERT INTO {$wpdb->prefix}ultimate_cf_importer_media(post_id ,attach_id ,image_url ,hash_key ,templatekey,`status` ,module , image_type)values($id,$attach_id , '$url' , '$hash_key' , '$templatekey', 'Completed', '$module', '$type')" );		
		}
		$result = $wpdb->update( $wpdb->prefix . 'ultimate_cf_importer_shortcode_manager' , 
            array( 
                'status' => 'Completed',
            ), 
            array( 'hash_key' => $hash_key ,
					'templatekey' => $templatekey,
					'image_shortcode' => $type,
					'original_image' => $url,
					'post_id' => $id  
            ) 
		);	
	}

	public function mediaReport(){
		check_ajax_referer('smack-importer-custom-fields-basic-pro', 'securekey');
		global $wpdb;
		$list_of_images = $wpdb->get_results("select * from {$wpdb->prefix}ultimate_cf_importer_media GROUP BY `hash_key`,`image_type` ",ARRAY_A);
		foreach( $list_of_images as $list_key => $list_val )
		{
			if(!empty($list_val['hash_key'])){
				$file_name = $wpdb->get_results("select file_name from {$wpdb->prefix}smackwpml_file_events where hash_key = '{$list_val['hash_key']}'",ARRAY_A);
				$filename[$list_key]= $file_name[0]['file_name'];
				$number_of_images = $wpdb->get_results("select image_url from {$wpdb->prefix}ultimate_wpml_importer_media where hash_key = '{$list_val['hash_key']}' and image_type = '{$list_val['image_type']}' ",ARRAY_A);				
				$count[$list_key] = count($number_of_images);
			}
			
			if(!empty($list_val['templatekey'])){						
				$number_of_images = $wpdb->get_results("select image_url from {$wpdb->prefix}ultimate_wpml_importer_media where templatekey = '{$list_val['templatekey']}' and image_type = '{$list_val['image_type']}' ",ARRAY_A);				
				$count[$list_key] = count($number_of_images);
			}
			
			$module[$list_key] = $list_val['module'];
			$image_type[$list_key] = $list_val['image_type'];
			$image_status[$list_key] = $list_val['status'];
		}
		$response['file_name'] = isset($filename) ? $filename : '';
		$response['module'] = isset($module) ? $module : '';
		$response['count'] = isset($count) ? $count : '';
		$response['image_type'] = isset($image_type) ? $image_type : '';
		$response['status'] = isset($image_status) ? $image_status : '';
		echo wp_json_encode($response);
		wp_die();
	}

	public function image_function($f_img , $post_id , $data_array = null,$option_name = null, $use_existing_image = false,$header_array = null , $value_array = null){
		global $wpdb;
		$f_img = urldecode($f_img);
		$image = explode("?", $f_img);
		$f_img=$image[0];
		if(isset($data_array['post_author'])){
			$data_array['post_author'] = $this->getAuthor($data_array['post_author']);
		}
		$media_handle = get_option('smack_image_options');
		if(!empty($header_array) && !empty($value_array) ){
			$media_settings = array_combine($header_array,$value_array);
		}
		if(isset($media_handle['media_settings']['alttext'])) {
			$alttext ['_wp_attachment_image_alt'] = isset($media_settings[$media_handle['media_settings']['alttext']]) ? $media_settings[$media_handle['media_settings']['alttext']] :'';
		} 
		if(isset($media_handle['postcontent_image_alt'])) {
			$alttext ['_wp_postcontent_image_alt'] = $media_handle['postcontent_image_alt'];
		} 

		if(preg_match_all('/\b(?:(?:https?|http|ftp|file):\/\/|www\.|ftp\.)[-A-Z0-9+&@#\/%=~_|$?!:,.]*[A-Z0-9+&@#\/%=~_|$]/i', $f_img , $matchedlist, PREG_PATTERN_ORDER)) {
			$f_img = $f_img;
		}   
		else{
			$media_dir = wp_get_upload_dir();
			$names = glob($media_dir['path'].'/'.'*.*');
			foreach($names as $values){
				if (!empty($f_img) && strpos($values, $f_img) !== false) {
					$f_img = $media_dir['url'].'/'.$f_img;

				}
			}            
		}

		$image_name = pathinfo($f_img);

		if(!empty($media_handle['media_settings']['file_name'])){	
			$file_type = wp_check_filetype( $f_img, null );
			$ext = '.'. $file_type['ext'];
			$fimg_name = $media_settings[$media_handle['media_settings']['file_name']].$ext;
		}		
		else{
			$fimg_name = $image_name['basename'];
		}
		$file_type = wp_check_filetype( $fimg_name, null );
		if($use_existing_image){

			if(empty($file_type['ext'])){
				$fimg_name = @basename($f_img);
				$fimg_name = str_replace(' ', '-', trim($fimg_name));
				$fimg_name = preg_replace('/[^a-zA-Z0-9._\-\s]/', '', $fimg_name);
			}

			$attachment_id = $wpdb->get_var("SELECT ID FROM ".$wpdb->prefix."posts WHERE post_type = 'attachment' AND guid LIKE '%$fimg_name'");
			$this->imageMetaImport($attachment_id,$media_handle,$header_array,$value_array);
			
			if($attachment_id){
				if(!empty($data_array['featured_image'])){
					set_post_thumbnail( $post_id, $attachment_id );
					return $attachment_id;
				}else{
					return $attachment_id;
				}
			}
		}

		$attachment_title = sanitize_file_name( pathinfo( $fimg_name, PATHINFO_FILENAME ) );
		$file_type = wp_check_filetype( $fimg_name, null );  
		$dir = wp_upload_dir();
		$dirname = date('Y') . '/' . date('m');
		$uploads_use_yearmonth = get_option('uploads_use_yearmonth_folders');
		if($uploads_use_yearmonth == 1){
            $uploaddir_paths = $dir ['basedir'] . '/' . $dirname ;
            $uploaddir_url = $dir ['baseurl'] . '/' . $dirname;
        }
        else{
            $uploaddir_paths = $dir ['basedir'];
            $uploaddir_url = $dir ['baseurl'];
        }
		$f_img = str_replace(" ","%20",$f_img);
		if(empty($file_type['ext'])){
			$fimg_name = @basename($f_img);
			$fimg_name = str_replace(' ', '-', trim($fimg_name));
			$fimg_name = preg_replace('/[^a-zA-Z0-9._\-\s]/', '', $fimg_name);
		}
		if ($uploaddir_paths != "" && $uploaddir_paths) {
			$uploaddir_path = $uploaddir_paths . "/" . $fimg_name;
		}
		if (strstr($f_img, 'https://drive.google.com')){
			$page_content = file_get_contents($f_img);
			$dom_obj = new \DOMDocument();
			$dom_obj->loadHTML($page_content);
			$meta_val = null;		
			foreach($dom_obj->getElementsByTagName('meta') as $meta) {
				if($meta->getAttribute('property')=='og:image'){ 
					$meta_val = $meta->getAttribute('content');
				}
			}
			$ch = curl_init($meta_val);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
			$rawdata=curl_exec ($ch);	
		}
		
		elseif(isset($media_dir['url'])&& strstr($f_img,$media_dir['url'])){
			if(strstr($f_img,$media_dir['url'])){
				$post_info = array(
					'guid'           => $uploaddir_url . "/" .  $fimg_name,
					'post_mime_type' => $file_type['type'],
					'post_title'     => $attachment_title,
					'post_content'   => '',
					'post_status'    => 'inherit',
					'post_author'  => $data_array['post_author']
					);
				$attach_id = wp_insert_attachment( $post_info,$uploaddir_path, $post_id );
				$attach_data = wp_generate_attachment_metadata( $attach_id, $uploaddir_path );
				wp_update_attachment_metadata( $attach_id,  $attach_data );
				if(!empty($data_array['featured_image'])) {
					set_post_thumbnail( $post_id, $attach_id );
				}
				$this->imageMetaImport($attach_id,$media_handle,$header_array,$value_array);
				return $attach_id;
			}
		}
		else if(strstr($f_img, 'https://www.dropbox.com/')) {	
			$page_content   = file_get_contents($f_img);	
			$dom_obj = new \DOMDocument();
			$dom_obj->loadHTML($page_content);
			$meta_val = null;		
			foreach($dom_obj->getElementsByTagName('meta') as $meta) {
				if($meta->getAttribute('property')=='og:image'){ 
					$meta_val = $meta->getAttribute('content');
				}
			}
			$response = wp_remote_get($meta_val);
			$rawdata =  wp_remote_retrieve_body($response);
			
		}
		//Removed curl and added wordpress http api
		else{
			if($file_type['ext'] == 'jpeg'){
				$response = wp_remote_get($f_img, array( 'timeout' => 30));		
			}
			else if($file_type['ext'] == 'mp4'){
				$response = wp_remote_get($f_img, array( 'timeout' => 30));	
			}
			else{
				$response = wp_remote_get($f_img, array( 'timeout' => 10));		
			}
			$rawdata =  wp_remote_retrieve_body($response);
		}
		if(isset($response)){
			$http_code = wp_remote_retrieve_response_code($response);

			if($http_code == 404){
				return null;
			}

			// When 
			if ( $http_code != 200 && strpos( $rawdata, 'Not Found' ) != 0 ) {
				return null;
			}
		}
		if(isset($rawdata)){
			if ($rawdata == false) {

				return null;
			} else {			
				if (file_exists($uploaddir_path)) {
					$i = 1;
					$exist = true;
					while($exist){
						$fimg_name = $attachment_title . "-" . $i . "." . $file_type['ext'];        
						$uploaddir_path = $uploaddir_paths . "/" . $fimg_name;

						if (file_exists($uploaddir_path)) {
							$i = $i + 1;
						}
						else{
							$exist = false;
						}
					}
				}
				$fp = fopen($uploaddir_path, 'x');
				fwrite($fp, $rawdata);
				fclose($fp);
			}
		}

		if(empty($file_type['type'])){
			$file_type['type'] = 'image/jpeg';
		}

		$post_info = array(
			'guid'           => $uploaddir_url . "/" .  $fimg_name,
			'post_mime_type' => $file_type['type'],
			'post_title'     => $attachment_title,
			'post_content'   => '',
			'post_status'    => 'inherit',
			'post_author'  => isset($data_array['post_author']) ? $data_array['post_author'] :''
		);
		$attach_id = wp_insert_attachment( $post_info,$uploaddir_path, $post_id );
		$attach_data = wp_generate_attachment_metadata( $attach_id, $uploaddir_path );
		wp_update_attachment_metadata( $attach_id,  $attach_data );
		if($media_handle['media_settings']['thumbnail'] == 'true') {
			$thumbnail_width = get_option('thumbnail_size_w');
			$thumbnail_height = get_option('thumbnail_size_h');
			$metadata = wp_get_attachment_metadata($attach_id);
			$metadata['width'] = $thumbnail_width;
			$metadata['height'] = $thumbnail_height;
			wp_update_attachment_metadata($attach_id,$metadata);
		}
		elseif($media_handle['media_settings']['medium'] == 'true'){
			$medium_width = get_option('medium_size_w');
			$medium_height = get_option('medium_size_h');
			$metadata = wp_get_attachment_metadata($attach_id);
			$metadata['width'] = $medium_width;
			$metadata['height'] = $medium_height;
			wp_update_attachment_metadata($attach_id,$metadata);
		}
		elseif($media_handle['media_settings']['medium_large'] == 'true') {
			$medium_large_width = get_option('medium_large_size_w');
			$medium_large_height = get_option('medium_large_size_h');
			$metadata = wp_get_attachment_metadata($attach_id);
			$metadata['width'] = $medium_large_width;
			$metadata['height'] = $medium_large_height;
			wp_update_attachment_metadata($attach_id,$metadata);
		}
		elseif($media_handle['media_settings']['large'] == 'true'){
			$large_width = get_option('large_size_w');
			$large_height = get_option('large_size_h');
			$metadata = wp_get_attachment_metadata($attach_id);
			$metadata['width'] = $large_width;
			$metadata['height'] = $large_height;
			wp_update_attachment_metadata($attach_id,$metadata);
		}
		else{
			$get_image_size = getimagesize($uploaddir_path);
			$attach_data['width'] = $get_image_size[0];
			$attach_data['height'] = $get_image_size[1];
			wp_update_attachment_metadata($attach_id,$attach_data);
		}
		
		$this->imageMetaImport($attach_id,$media_handle,$header_array,$value_array);
		
		if(!empty($data_array['featured_image'])) {
			set_post_thumbnail( $post_id, $attach_id );
		}
	
		return $attach_id;
	}

	public function getAuthor($post_author){
		$helpers_instance = ImportHelpers::getInstance();
			if(isset($post_author )) {
				$user_records = $helpers_instance->get_from_user_details( $post_author );
				$post_author = $user_records['user_id'];
			}
		return $post_author;
	}

	public function imageMetaImport($attach_id,$media_handle,$header_array,$value_array){
		$media_handle = get_option('smack_image_options');
		if(!empty($header_array) && !empty($value_array) ){
		$media_settings = array_combine($header_array,$value_array);
		}
		if(isset($media_handle['media_settings']['alttext'])) {
			$alttext ['_wp_attachment_image_alt'] = isset($media_settings[$media_handle['media_settings']['alttext']]) ? $media_settings[$media_handle['media_settings']['alttext']] :'';
		} 
		if(isset($media_handle['postcontent_image_alt'])) {
			$alttext ['_wp_postcontent_image_alt'] = $media_handle['postcontent_image_alt'];
		}
		if(isset($media_handle['media_settings']['description'])){
			$media_handle['media_settings']['description'] = isset($media_settings[$media_handle['media_settings']['description']]) ? $media_settings[$media_handle['media_settings']['description']] :'';
		}
		if(isset($media_handle['media_settings']['caption'])){
			$media_handle['media_settings']['caption'] = isset($media_settings[$media_handle['media_settings']['caption']]) ? $media_settings[$media_handle['media_settings']['caption']] :'';
		}
		if(isset($media_handle['media_settings']['title'])){
			$media_handle['media_settings']['title'] = isset($media_settings[$media_handle['media_settings']['title']]) ? $media_settings[$media_handle['media_settings']['title']] :'';
		}
		if(isset($media_handle['media_settings']['caption']) || isset($media_handle['media_settings']['description'])){
			wp_update_post(array(
						'ID'           =>$attach_id,
						'post_content' =>$media_handle['media_settings']['description'],
						'post_excerpt' =>$media_handle['media_settings']['caption']
					    ));
		}
		if(!empty($media_handle['media_settings']['title'])){
			wp_update_post(array(
						'ID'           =>$attach_id,
						'post_title'   =>$media_handle['media_settings']['title']
					    ));
		}
		if($attach_id != null && isset($alttext['_wp_attachment_image_alt'])){  
			update_post_meta($attach_id, '_wp_attachment_image_alt', $alttext['_wp_attachment_image_alt']);
		}

		if($attach_id != null && isset($alttext['_wp_postcontent_image_alt'])){  
			update_post_meta($attach_id, '_wp_attachment_image_alt', $alttext['_wp_postcontent_image_alt']);
		}
	}

	public function acfimageMetaImports($attach_id,$media_handle,$plugin){
		if(is_array($attach_id)){
			foreach($attach_id as $attachid => $attachvalid){
				if(isset($media_handle[$plugin.'_caption'][$attachid]) || isset($media_handle[$plugin.'_description'][$attachid])){
					wp_update_post(array(
								'ID'           =>$attachvalid,
								'post_content' =>$media_handle[$plugin.'_description'][$attachid],
								'post_excerpt' =>$media_handle[$plugin.'_caption'][$attachid]
								));
				}
				if(isset($media_handle['title'][$attachid])){
					wp_update_post(array(
								'ID'           =>$attachvalid,
								'post_title'   =>$media_handle[$plugin.'_title'][$attachid]
								));
				}
				if($attachvalid != null && isset($media_handle[$plugin.'_file_name'][$attachid])){ 
					if(!empty($media_handle[$plugin.'_file_name'][$attachid])){
						global $wpdb;
						$guid=$wpdb->get_results("select guid from {$wpdb->prefix}posts where ID= '$attachvalid'" ,ARRAY_A);
						$dirname = date('Y') . '/' . date('m');
						$file_type = wp_check_filetype( $guid[0]['guid'], null );
						$ext = '.'. $file_type['ext'];
						$dir = wp_upload_dir();
							
						$uploaddir_paths = $dir ['basedir'] . '/' . $dirname ;
						$uploaddir_url = $dir ['baseurl'] . '/' . $dirname;
						$guids=$uploaddir_url.'/'.$media_handle[$plugin.'_file_name'][$attachid].$ext;
						$wpdb->update($wpdb->posts, ['guid' => $guids], ['ID' => $attachvalid]);
						$fimg_name = $dirname.'/'.$media_handle[$plugin.'_file_name'][$attachid].$ext;
						update_post_meta($attachvalid, '_wp_attached_file', $fimg_name);
					}
					
				}
				if($attachvalid != null && isset($media_handle[$plugin.'_alt_text'][$attachid])){  
					update_post_meta($attachvalid, '_wp_attachment_image_alt', $media_handle[$plugin.'_alt_text'][$attachid]);
				}
			}
		}
		else{
			if(isset($media_handle[$plugin.'_caption'][0]) || isset($media_handle[$plugin.'_description'][0])){
				wp_update_post(array(
							'ID'           =>$attach_id,
							'post_content' =>$media_handle[$plugin.'_description'][0],
							'post_excerpt' =>$media_handle[$plugin.'_caption'][0]
							));
			}
			if(isset($media_handle['title'][0])){
				wp_update_post(array(
							'ID'           =>$attach_id,
							'post_title'   =>$media_handle[$plugin.'_title'][0]
							));
			}
			if($attach_id != null && isset($media_handle[$plugin.'_file_name'][0])){ 
				if(!empty($media_handle[$plugin.'_file_name'][0])){
					global $wpdb;
					$guid=$wpdb->get_results("select guid from {$wpdb->prefix}posts where ID= '$attach_id'" ,ARRAY_A);
					$dirname = date('Y') . '/' . date('m');
					$file_type = wp_check_filetype( $guid[0]['guid'], null );
					$ext = '.'. $file_type['ext'];
					$dir = wp_upload_dir();
						
					$uploaddir_paths = $dir ['basedir'] . '/' . $dirname ;
					$uploaddir_url = $dir ['baseurl'] . '/' . $dirname;
					$guids=$uploaddir_url.'/'.$media_handle[$plugin.'_file_name'][0].$ext;
					$wpdb->update($wpdb->posts, ['guid' => $guids], ['ID' => $attach_id]);
					$fimg_name = $dirname.'/'.$media_handle[$plugin.'_file_name'][0].$ext;
					update_post_meta($attach_id, '_wp_attached_file', $fimg_name);
				}
			
			}
			if($attach_id != null && isset($media_handle[$plugin.'_alt_text'][0])){  
				update_post_meta($attach_id, '_wp_attachment_image_alt', $media_handle[$plugin.'_alt_text'][0]);
			}
		}
	}
	
	public function acfgalleryMetaImports($attach_id,$media_handle,$plugin){
		if(is_array($attach_id)){
			foreach($attach_id as $attachid => $attachvalid){
				if(isset($media_handle[$plugin.'_gallery_caption'][$attachid]) || isset($media_handle[$plugin.'_gallery_description'][$attachid])){
					wp_update_post(array(
						'ID'           =>$attachvalid,
						'post_content' =>$media_handle[$plugin.'_gallery_description'][$attachid],
						'post_excerpt' =>$media_handle[$plugin.'_gallery_caption'][$attachid]
					));
				}
				if(isset($media_handle[$plugin.'_gallery_title'][$attachid])){
					wp_update_post(array(
						'ID'           =>$attachvalid,
						'post_title'   =>$media_handle[$plugin.'_gallery_title'][$attachid]
					));
				}
				if($attachvalid != null && isset($media_handle[$plugin.'_gallery_file_name'][$attachid])){ 
					global $wpdb;
					$guid=$wpdb->get_results("select guid from {$wpdb->prefix}posts where ID= '$attachvalid'" ,ARRAY_A);
					$dirname = date('Y') . '/' . date('m');
					$file_type = wp_check_filetype( $guid[0]['guid'], null );
					$ext = '.'. $file_type['ext'];
					$dir = wp_upload_dir();

					$uploaddir_paths = $dir ['basedir'] . '/' . $dirname ;
					$uploaddir_url = $dir ['baseurl'] . '/' . $dirname;
					$guids=$uploaddir_url.'/'.$media_handle[$plugin.'_gallery_file_name'][$attachid].$ext;
					$wpdb->update($wpdb->posts, ['guid' => $guids], ['ID' => $attachvalid]);
					$fimg_name = $dirname.'/'.$media_handle[$plugin.'_gallery_file_name'][$attachid].$ext;
					update_post_meta($attachvalid, '_wp_attached_file', $fimg_name);
				}
				if($attachvalid != null && isset($media_handle[$plugin.'_gallery_alt_text'][$attachid])){  
					update_post_meta($attachvalid, '_wp_attachment_image_alt', $media_handle[$plugin.'_gallery_alt_text'][$attachid]);
				}
			}
		}
		else{
			if(isset($media_handle[$plugin.'_gallery_caption'][0]) || isset($media_handle[$plugin.'_gallery_description'][0])){
				wp_update_post(array(
					'ID'           =>$attach_id,
					'post_content' =>$media_handle[$plugin.'_gallery_description'][0],
					'post_excerpt' =>$media_handle[$plugin.'_gallery_caption'][0]
				));
			}
			if(isset($media_handle[$plugin.'_title'][0])){
				wp_update_post(array(
					'ID'           =>$attach_id,
					'post_title'   =>$media_handle[$plugin.'_gallery_title'][0]
				));
			}
			if($attach_id != null && isset($media_handle[$plugin.'_gallery_file_name'][0])){ 
				global $wpdb;
				$guid=$wpdb->get_results("select guid from {$wpdb->prefix}posts where ID= '$attach_id'" ,ARRAY_A);
				$dirname = date('Y') . '/' . date('m');
				$file_type = wp_check_filetype( $guid[0]['guid'], null );
				$ext = '.'. $file_type['ext'];
				$dir = wp_upload_dir();

				$uploaddir_paths = $dir ['basedir'] . '/' . $dirname ;
				$uploaddir_url = $dir ['baseurl'] . '/' . $dirname;
				$guids=$uploaddir_url.'/'.$media_handle[$plugin.'_gallery_file_name'][0].$ext;
				$wpdb->update($wpdb->posts, ['guid' => $guids], ['ID' => $attach_id]);
				$fimg_name = $dirname.'/'.$media_handle[$plugin.'_gallery_file_name'][0].$ext;
				update_post_meta($attach_id, '_wp_attached_file', $fimg_name);
			}
			if($attach_id != null && isset($media_handle[$plugin.'_gallery_alt_text'][0])){  
				update_post_meta($attach_id, '_wp_attachment_image_alt', $media_handle[$plugin.'_gallery_alt_text'][0]);
			}
		}
	}
	
	public function image_import($data_array,$check,$mode,$line_number,$unikey_value ,$unikey_name){
		global $wpdb,$core_instance; 
		$core_instance = CoreFieldsImport::getInstance();
		$helpers_instance = ImportHelpers::getInstance();
		if($mode == 'Insert'){
		    $encodedurl = urlencode($data_array['featured_image']);
			$data_array['featured_image'] = urldecode($encodedurl);
			if(isset($data_array['post_author'])){
				$data_array['post_author'] = $this->getAuthor($data_array['post_author']);
			}
           if(isset($data_array['alt_text'])) {
			  $alttext ['_wp_attachment_image_alt'] = $data_array['alt_text'];
		   } 
		   if(preg_match_all('/\b(?:(?:https?|http|ftp|file):\/\/|www\.|ftp\.)[-A-Z0-9+&@#\/%=~_|$?!:,.]*[A-Z0-9+&@#\/%=~_|$]/i', $data_array['featured_image'] , $matchedlist, PREG_PATTERN_ORDER)) 
		   {
			   $f_img = $data_array['featured_image'];
		   }   
		   else{
			$media_dir = wp_get_upload_dir();
			$names = glob($media_dir['path'].'/'.'*.*');
			$f_img = explode('/',$data_array['featured_image']);
			$f_img = end($f_img);
			    foreach($names as $values){
				    if (strpos($values, $f_img) !== false) {
					    $f_img = $media_dir['url'].'/'.$f_img;
				    }
			    }            
		    }
	
			$attachment_title = sanitize_file_name( pathinfo( $f_img, PATHINFO_FILENAME ) );  
		    $file_type = wp_check_filetype( $f_img, null );         
		    $dir = wp_upload_dir();
		    $dirname = date('Y') . '/' . date('m');
		    $uploaddir_paths = $dir ['basedir'] . '/' . $dirname ;
		    $uploaddir_url = $dir ['baseurl'] . '/' . $dirname;
		    if(empty($file_type['ext']) || empty($data_array['file_name'])) {
			    $fimg_name = @basename($f_img);
				$fimg_name = str_replace(' ', '-', trim($fimg_name));
				$fimg_name = preg_replace('/[^a-zA-Z0-9._\-\s]/', '', $fimg_name);
			}
			if(!empty($data_array['file_name'])){	
				$file_type = wp_check_filetype( $f_img, null );
				$fimg_name = $data_array['file_name'];
				$fimg_name = explode('/',$data_array['file_name']);
				$fimg_name = end($fimg_name);
			}	
			if ($uploaddir_paths != "" && $uploaddir_paths) {
				$uploaddir_paths_str = is_array($uploaddir_paths) ? implode('/', $uploaddir_paths) : $uploaddir_paths;
				$fimg_name_str = is_array($fimg_name) ? implode('/', $fimg_name) : $fimg_name;
				$uploaddir_path = $uploaddir_paths_str . "/" . $fimg_name_str;		
			}
		    if (strstr($f_img, 'https://drive.google.com')){
				$page_content = file_get_contents($f_img);
				$dom_obj = new \DOMDocument();
				$dom_obj->loadHTML($page_content);
				$meta_val = null;		
				foreach($dom_obj->getElementsByTagName('meta') as $meta) {
					if($meta->getAttribute('property')=='og:image'){ 
						$meta_val = $meta->getAttribute('content');
					}
				}
				$ch = curl_init($meta_val);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
				$rawdata=curl_exec ($ch);	
			}
		    //Removed curl and added wordpress http api
		    else{
				if($file_type['ext'] == 'jpeg'){
					$response = wp_remote_get($f_img, array( 'timeout' => 30));		
				}else{
					$response = wp_remote_get($f_img, array( 'timeout' => 10));	
				}
				$rawdata =  wp_remote_retrieve_body($response);
			}
		    $http_code = wp_remote_retrieve_response_code($response);
	
		    if($http_code == 404){
				return null;
			}

		    // When 
		    if ( $http_code != 200 && strpos( $rawdata, 'Not Found' ) != 0 ) {
			    return null;
		    }
			$uploaddir_url_str = is_array($uploaddir_url) ? implode('/', $uploaddir_url) : $uploaddir_url;
			$fimg_name_str = is_array($fimg_name) ? implode('/', $fimg_name) : $fimg_name;
			
			$guid = $uploaddir_url_str . "/" . $fimg_name_str;
			if(is_plugin_active('exmage-wp-image-links/exmage-wp-image-links.php')){
				$guid =$fimg_name;
			}
			if ($rawdata == false) {
			    return null;
		    } else {
				if(is_plugin_active('exmage-wp-image-links/exmage-wp-image-links.php')){
					$link = new \EXMAGE_WP_IMAGE_LINKS;
					$postID = $link->add_image($data_array['featured_image'],$value);
					wp_update_post(array(
						'ID'           => $postID['id'],
						'post_title'   => $data_array['title'],
						'post_content' => $data_array['description'],
						'post_excerpt' => $data_array['caption']
					));
					//if($postID['id'] != null && isset($alttext['_wp_attachment_image_alt'])){  
						//update_post_meta($postID['id'], '_wp_attachment_image_alt', $alttext['_wp_attachment_image_alt']);
					//}
					if($postID['id'] != null && isset($data_array['alt_text'])){  
						update_post_meta($postID['id'], '_wp_attachment_image_alt', $data_array['alt_text']);
					}
				}
				else{		
					if (file_exists($uploaddir_path)) {
						$i = 1;
						$exist = true;
						while($exist){
							$fimg_name = $attachment_title . "-" . $i . "." . $file_type['ext'];        
							$uploaddir_path = $uploaddir_paths . "/" . $fimg_name;
		
							if (file_exists($uploaddir_path)) {
								$i = $i + 1;
							}
							else{
								$exist = false;
							}
						}
					}
					$fp = fopen($uploaddir_path, 'x');
					fwrite($fp, $rawdata);
					fclose($fp);
				}
			}
		    if(empty($file_type['type'])){
				$file_type['type'] = 'image/jpeg';
			}
			$uploaddir_url_str = is_array($uploaddir_url) ? implode('/', $uploaddir_url) : $uploaddir_url;
            $fimg_name_str = is_array($fimg_name) ? implode('/', $fimg_name) : $fimg_name;

            $guid = $uploaddir_url_str . "/" . $fimg_name_str;
			$post_info = array(
				'guid'           => $guid,
				'post_mime_type' => $file_type['type'],
				'post_title'     => $attachment_title,
				'post_content'   => '',
				'post_status'    => 'inherit',
				'post_author'  => isset($data_array['post_author']) ? $data_array['post_author'] :''
				);
			$uploadpath=explode(".",$fimg_name);
			$imagename=$uploadpath[0];
			$length=strlen($imagename);
			$index=$length-1;
			$upload=$imagename[$index]	;
			if(!is_plugin_active('exmage-wp-image-links/exmage-wp-image-links.php')){
				if($upload=='1'){
					$fimg_names = $attachment_title  . "." . $file_type['ext'];  
						$uploaddir_path1 = $uploaddir_paths . "/" . $fimg_names;
						$attach_id = wp_insert_attachment( $post_info,$uploaddir_path1 );
						$attach_data = wp_generate_attachment_metadata( $attach_id, $uploaddir_path1 );
				}
				else{			
				$attach_id = wp_insert_attachment( $post_info,$uploaddir_path );
				$attach_data = wp_generate_attachment_metadata( $attach_id, $uploaddir_path );
				}	
				wp_update_attachment_metadata( $attach_id,  $attach_data );
			}
			$media_handle = get_option('smack_image_options');
			
		if($media_handle['media_settings']['thumbnail'] == 'true') {
			$thumbnail_width = get_option('thumbnail_size_w');
			$thumbnail_height = get_option('thumbnail_size_h');
			$metadata = wp_get_attachment_metadata($attach_id);
			$metadata['width'] = $thumbnail_width;
			$metadata['height'] = $thumbnail_height;
			wp_update_attachment_metadata($attach_id,$metadata);
		}
		elseif($media_handle['media_settings']['medium'] == 'true'){
			$medium_width = get_option('medium_size_w');
			$medium_height = get_option('medium_size_h');
			$metadata = wp_get_attachment_metadata($attach_id);
			$metadata['width'] = $medium_width;
			$metadata['height'] = $medium_height;
			wp_update_attachment_metadata($attach_id,$metadata);
		}
		elseif($media_handle['media_settings']['medium_large'] == 'true') {
			$medium_large_width = get_option('medium_large_size_w');
			$medium_large_height = get_option('medium_large_size_h');
			$metadata = wp_get_attachment_metadata($attach_id);
			$metadata['width'] = $medium_large_width;
			$metadata['height'] = $medium_large_height;
			wp_update_attachment_metadata($attach_id,$metadata);
		}
		elseif($media_handle['media_settings']['large'] == 'true'){
			$large_width = get_option('large_size_w');
			$large_height = get_option('large_size_h');
			$metadata = wp_get_attachment_metadata($attach_id);
			$metadata['width'] = $large_width;
			$metadata['height'] = $large_height;
			wp_update_attachment_metadata($attach_id,$metadata);
		}
		else{
			$get_image_size = getimagesize($uploaddir_path);
			$attach_data['width'] = $get_image_size[0];
			$attach_data['height'] = $get_image_size[1];
			wp_update_attachment_metadata($attach_id,$attach_data);
		}
			wp_update_post(array(
	        	'ID'           => $attach_id,
	    		'post_title'   => $data_array['title'],
				'post_content' => isset($data_array['description']) ? $data_array['description'] :'',
				'post_excerpt' => $data_array['caption']
			));
												
			if($attach_id != null && isset($alttext['_wp_attachment_image_alt'])){  
				update_post_meta($attach_id, '_wp_attachment_image_alt', $alttext['_wp_attachment_image_alt']);
			}
			$core_instance->detailed_log[$line_number]['Message'] = 'Inserted Images '  . ' ID: ' . $attach_id ;
	        $core_instance->detailed_log[$line_number]['VERIFY'] = "<b> Click here to verify</b> - <a href='" . get_edit_post_link( $attach_id, true ) . "'target='_blank' title='" . esc_attr( 'Edit this item' ) . "'>Admin View</a>";
			$log_table_name = $wpdb->prefix ."cfimport_detail_log";
			$updated_row_counts = $helpers_instance->update_count($unikey_value,$unikey_name);
			$created_count = $updated_row_counts['created'];
			$updated_count = $updated_row_counts['updated'];
			$skipped_count = $updated_row_counts['skipped'];
			$wpdb->get_results("UPDATE $log_table_name SET created = $created_count WHERE $unikey_name = '$unikey_value'");
	
		}

		else{
				//changed - added update case if image url is missing
				if(!isset($data_array['featured_image'])){
			
					if($check == 'ID'){
						$image_id = $data_array['ID'];
					}
					elseif($check == 'Filename'){
						$fimg_names = $data_array['file_name'];
						$image_id = $wpdb->get_var("SELECT ID FROM ".$wpdb->prefix."posts WHERE post_type = 'attachment' AND guid LIKE '%$fimg_names'");
					}
	
					$update_post_info = [];
					if(isset($data_array['caption'])){
						$update_post_info['post_excerpt'] = $data_array['caption'];
					}
					if(isset($data_array['title'])){
						$update_post_info['post_title'] = $data_array['title'];
					}
					if(isset($data_array['alt_text'])){
						update_post_meta($image_id, '_wp_attachment_image_alt', $data_array['alt_text']);
					}
					if(isset($data_array['description'])){
						$update_post_info['post_content'] = $data_array['description'];
					}
					
					$update_post_info['ID'] = $image_id;
					$update_post_info['post_status'] = $data_array['inherit'];
					$update_post_info['post_author'] = 1;
					wp_update_post($update_post_info);
	
					$core_instance->detailed_log[$line_number]['Message'] = 'Updated Images '  . ' ID: ' . $image_id ;
					$core_instance->detailed_log[$line_number]['VERIFY'] = "<b> Click here to verify</b> - <a href='" . get_edit_post_link( $image_id, true ) . "'target='_blank' title='" . esc_attr( 'Edit this item' ) . "'>Admin View</a>";
					
					return $image_id;
				}
			 
			$encodedurl = urlencode($data_array['featured_image']);
			$data_array['featured_image'] = urldecode($encodedurl);
			if(isset($data_array['post_author'])){
				$data_array['post_author'] = $this->getAuthor($data_array['post_author']);
			}	
			 
			if(isset($data_array['alt_text'])) {
			   $alttext ['_wp_attachment_image_alt'] = $data_array['alt_text'];
    		 } 
			if(preg_match_all('/\b(?:(?:https?|http|ftp|file):\/\/|www\.|ftp\.)[-A-Z0-9+&@#\/%=~_|$?!:,.]*[A-Z0-9+&@#\/%=~_|$]/i', $data_array['featured_image'] , $matchedlist, PREG_PATTERN_ORDER)) 
			{
			   $f_img = $data_array['featured_image'];
			}   
		   else{
			   $media_dir = wp_get_upload_dir();
			   $names = glob($media_dir['path'].'/'.'*.*');
			   $f_img = $data_array['featured_image'];
			   	    foreach($names as $values){
						if(!empty($f_img)){
							if (strpos($values, $f_img) !== false) {
								$f_img = $media_dir['url'].'/'.$f_img;
							}
						}
				    }            
		    }
		 
    		$attachment_title = sanitize_file_name( pathinfo( $f_img, PATHINFO_FILENAME ) );             
			$file_type = wp_check_filetype( $f_img, null );         
			$dir = wp_upload_dir();
			$dirname = date('Y') . '/' . date('m');
			$uploaddir_paths = $dir ['basedir'] . '/' . $dirname ;
			$uploaddir_url = $dir ['baseurl'] . '/' . $dirname;
			if(empty($file_type['ext']) || empty($data_array['file_name'])) {
				$fimg_name = @basename($f_img);
				$fimg_name = str_replace(' ', '-', trim($fimg_name));
				$fimg_name = preg_replace('/[^a-zA-Z0-9._\-\s]/', '', $fimg_name);
		    }
		 
			if(!empty($data_array['file_name'])){	
				$file_type = wp_check_filetype( $f_img, null );
				$fimg_name = $data_array['file_name'];
		    }	
			if ($uploaddir_paths != "" && $uploaddir_paths) {
				$uploaddir_path = $uploaddir_paths . "/" . $fimg_name;
		    }
				 
			if (strstr($f_img, 'https://drive.google.com')){
				$page_content = file_get_contents($f_img);
				$dom_obj = new \DOMDocument();
				$dom_obj->loadHTML($page_content);
				$meta_val = null;		
				foreach($dom_obj->getElementsByTagName('meta') as $meta) {
					if($meta->getAttribute('property')=='og:image'){ 
				    	$meta_val = $meta->getAttribute('content');
					}
				}
				$ch = curl_init($meta_val);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
				$rawdata=curl_exec ($ch);	
			}
			 //Removed curl and added wordpress http api
			else{
				if($file_type['ext'] == 'jpeg'){
					$response = wp_remote_get($f_img, array( 'timeout' => 30));		
				}else{
					$response = wp_remote_get($f_img, array( 'timeout' => 10));	
				}
				$rawdata =  wp_remote_retrieve_body($response);
			}
			$http_code = wp_remote_retrieve_response_code($response);
		 
			if($http_code == 404){
				return null;
			}
	 
			 // When 
			if ( $http_code != 200 && strpos( $rawdata, 'Not Found' ) != 0 ) {
				return null;
			}
			$guid=$uploaddir_url . "/" .  $fimg_name;
			if ($rawdata == false) {
				return null;
			} else {		
				if (file_exists($uploaddir_path)) {
					$i = 1;
					$exist = true;
					while($exist){
						$fimg_name = $attachment_title . "-" . $i . "." . $file_type['ext'];        
						$uploaddir_path = $uploaddir_paths . "/" . $fimg_name;
	 
						if (file_exists($uploaddir_path)) {
							$i = $i + 1;
						}
						else{
							$exist = false;
						}
					}
				}
				$fp = fopen($uploaddir_path, 'x');
				fwrite($fp, $rawdata);
				fclose($fp);
			}
			if(empty($file_type['type'])){
				$file_type['type'] = 'image/jpeg';
			}
			else{
				$file_type['type']= $file_type['type'];
			}
			$caption=$data_array['caption'];
			$post_title=$data_array['title'];
			$alt_text=$data_array['alt_text'];
			$description=$data_array['description'];
			$post_info = array(
				// 'ID' =>$attachment_id,
				'post_excerpt' =>$caption,
				'post_title'     => $post_title,
				'post_content'   => $description,
				'post_status'    => 'inherit',
				'post_author'  => 1,
				'guid'           => $uploaddir_url . "/" .  $fimg_name,
				'post_mime_type' => $file_type['type'],
			);
					
			if($check == 'Filename'){
				$fimg_names=$data_array['file_name'];
				$dirname=date('Y').'/'.date('M').'/'.$fimg_names;
				global $wpdb;
				$attachment_id = $wpdb->get_var("SELECT ID FROM ".$wpdb->prefix."posts WHERE post_type = 'attachment' AND guid LIKE '%$fimg_names'");
				if(!empty($attachment_id)){
		
				$caption=$data_array['caption'];
				$post_title=$data_array['title'];
				$alt_text=$data_array['alt_text'];
				$description=$data_array['description'];
							$post_info = array(
								'ID' =>$attachment_id,
								'post_excerpt' =>$caption,
								'post_title'     => $post_title,
								'post_content'   => $description,
								'post_status'    => 'inherit',
								'post_author'  => 1,
								);
							$attach_id=$attachment_id;
							wp_update_post($post_info);
						//	update_post_meta($attachment_id, '_wp_attached_file', $dirname); 
							update_post_meta($attachment_id, '_wp_attachment_image_alt', $alt_text);
					$core_instance->detailed_log[$line_number]['Message'] = 'Updated Images '  . ' ID: ' . $attach_id ;
	        		$core_instance->detailed_log[$line_number]['VERIFY'] = "<b> Click here to verify</b> - <a href='" . get_edit_post_link( $attach_id, true ) . "'target='_blank' title='" . esc_attr( 'Edit this item' ) . "'>Admin View</a>";
					$log_table_name = $wpdb->prefix ."cfimport_detail_log";
					$updated_row_counts = $helpers_instance->update_count($unikey_value,$unikey_name);
					$created_count = $updated_row_counts['created'];
					$updated_count = $updated_row_counts['updated'];
					$skipped_count = $updated_row_counts['skipped'];
					$wpdb->get_results("UPDATE $log_table_name SET updated = $updated_count WHERE $unikey_name = '$unikey_value'");
				}
				else{
					$attach_id = $this->images_import_function($upload, $attachment_title, $file_type, $uploaddir_paths, $post_info, $uploaddir_path, $data_array, $alttext, $line_number);
					$log_table_name = $wpdb->prefix ."cfimport_detail_log";
					$updated_row_counts = $helpers_instance->update_count($unikey_value,$unikey_name);
					$created_count = $updated_row_counts['created'];
					$updated_count = $updated_row_counts['updated'];
					$skipped_count = $updated_row_counts['skipped'];
					$wpdb->get_results("UPDATE $log_table_name SET created = $created_count WHERE $unikey_name = '$unikey_value'");
				}
			}
			else if($check == 'ID'){
               	
				$id =$data_array['ID'];
                $post_values['ID']=$id;
				if (isset($data_array['post_author'])) {
					$post_author = $data_array['post_author'];
				}else {
					$post_author = 0; 
				}
				$post_info = array(
					'guid'           => $uploaddir_url . "/" .  $fimg_name,
					'post_mime_type' => $file_type['type'],
					'post_title'     => $attachment_title,
					'post_content'   => '',
					'post_status'    => 'inherit',
					'post_author'  => $post_author
					);
				
					$uploadpath=explode(".",$fimg_name);
					$imagename=$uploadpath[0];
					$length=strlen($imagename);
					$index=$length-1;
					$upload=$imagename[$index]	;
					global $wpdb;
					$check_for_existence = $wpdb->get_var("SELECT ID FROM {$wpdb->prefix}posts WHERE ID = $id");
				
					if(!empty($check_for_existence)){
						if($upload=='1'){
							$fimg_names = $attachment_title  . "." . $file_type['ext'];  
								$uploaddir_path1 = $uploaddir_paths . "/" . $fimg_names;
								$post_info = array(
									'ID'            =>$post_values['ID'],
									'guid'           => $uploaddir_url . "/" .  $fimg_name,
									'post_mime_type' => $file_type['type'],
									'post_title'     => $attachment_title,
									'post_content'   => '',
									'post_status'    => 'inherit',
									'post_author'  => $post_author,
									);
								$attach_id=$post_values['ID'];
								wp_update_post($post_info);
								//$attach_id = wp_insert_attachment( $post_info,$uploaddir_path1 );
								$attach_data = wp_generate_attachment_metadata( $post_values['ID'], $uploaddir_path1 );
						}
						else{	
							$post_info = array(
								'ID'            =>$post_values['ID'],
								'post_mime_type' => $file_type['type'],
								'post_title'     => $attachment_title,
								'post_content'   => '',
								'post_status'    => 'inherit',
								'post_author'  => $post_author,
								'guid'           => $uploaddir_url . "/" .  $fimg_name,
								);
							$attach_id=$post_values['ID'];
						wp_update_post($post_info);
						$attach_data = wp_generate_attachment_metadata( $post_values['ID'], $uploaddir_path );
						}		
						
						wp_update_attachment_metadata( $post_values['ID'],  $attach_data );
						wp_update_post(array(
							'ID'           => $post_values['ID'],
							'post_name'    => $fimg_name,
							'post_title'   => $data_array['title'],
							'post_content' => $data_array['description'],
							'post_excerpt' => $data_array['caption'],
							'guid'           => $uploaddir_url . "/" .  $fimg_name,
							));
							global $wpdb;
							$wpdb->update($wpdb->posts, ['guid' => $uploaddir_url . "/" .  $fimg_name], ['ID' => $post_values['ID']]);	
						if($post_values['ID'] != null && isset($alttext['_wp_attachment_image_alt'])){
						update_post_meta($post_values['ID'], '_wp_attached_file', $attach_data['file']);  
						update_post_meta($post_values['ID'], '_wp_attachment_image_alt', $alttext['_wp_attachment_image_alt']);
						} 
						$core_instance->detailed_log[$line_number]['Message'] = 'Updated Images '  . ' ID: ' . $attach_id ;
						$core_instance->detailed_log[$line_number]['VERIFY'] = "<b> Click here to verify</b> - <a href='" . get_edit_post_link( $attach_id, true ) . "'target='_blank' title='" . esc_attr( 'Edit this item' ) . "'>Admin View</a>";
						$log_table_name = $wpdb->prefix ."cfimport_detail_log";
						$updated_row_counts = $helpers_instance->update_count($unikey_value,$unikey_name);
						$created_count = $updated_row_counts['created'];
						$updated_count = $updated_row_counts['updated'];
						$skipped_count = $updated_row_counts['skipped'];
						$wpdb->get_results("UPDATE $log_table_name SET updated = $updated_count WHERE $unikey_name = '$unikey_value'");
					}
					else{
						$attach_id = $this->images_import_function($upload, $attachment_title, $file_type, $uploaddir_paths, $post_info, $uploaddir_path, $data_array, $alttext, $line_number);
						$log_table_name = $wpdb->prefix ."cfimport_detail_log";
						$updated_row_counts = $helpers_instance->update_count($unikey_value,$unikey_name);
						$created_count = $updated_row_counts['created'];
						$updated_count = $updated_row_counts['updated'];
						$skipped_count = $updated_row_counts['skipped'];
						$wpdb->get_results("UPDATE $log_table_name SET created = $created_count WHERE $unikey_name = '$unikey_value'");
					
					}

			}
		    // else if($check == 'Featured_image'){
			else if($check == 'image_url'){
		        global $wpdb;
		        $guid1=explode('.',$guid);
				$guid2=$guid1[0];
				$get_result =  $wpdb->get_results("SELECT ID FROM {$wpdb->prefix}posts WHERE post_type = 'attachment' AND guid LIKE '$guid2%' order by ID DESC ");	
				$uploadpath = explode(".",$fimg_name);
					$imagename = $uploadpath[0];
					$length = strlen($imagename);
					$index = $length-1;
					$data_array['post_author'] = isset($data_array['post_author'])?$data_array['post_author']:'';
					$upload = $imagename[$index];
				if (is_array($get_result) && !empty($get_result)) {
				
					$post_id = $get_result[0]->ID;	
					$post_values['ID'] = $post_id;
					$data_array['post_author'] = isset($data_array['post_author'])?$data_array['post_author']:'';
				$post_info = array(
					'guid'           => $uploaddir_url . "/" .  $fimg_name,
					'post_mime_type' => $file_type['type'],
					'post_title'     => $attachment_title,
					'post_content'   => '',
					'post_status'    => 'inherit',
					'post_author'  => $data_array['post_author']
					);
				
					// $uploadpath=explode(".",$fimg_name);
					// $imagename=$uploadpath[0];
					// $length=strlen($imagename);
					// $index=$length-1;
					// $upload=$imagename[$index]	;
					$uploadpath = explode(".",$fimg_name);
					$imagename = $uploadpath[0];
					$length = strlen($imagename);
					$index = $length-1;
					$data_array['post_author'] = isset($data_array['post_author'])?$data_array['post_author']:'';
					$upload = $imagename[$index];
					
					if($upload=='1'){
						$fimg_names = $attachment_title  . "." . $file_type['ext'];  
							$uploaddir_path1 = $uploaddir_paths . "/" . $fimg_names;
							$post_info = array(
								'guid'           => $uploaddir_url . "/" .  $fimg_name,
								'post_mime_type' => $file_type['type'],
								'post_title'     => $attachment_title,
								'post_content'   => '',
								'post_status'    => 'inherit',
								'post_author'  => $data_array['post_author'],
								);
							$attach_id=$post_values['ID'];
							wp_update_post($post_info);
							//$attach_id = wp_insert_attachment( $post_info,$uploaddir_path1 );
							$attach_data = wp_generate_attachment_metadata( $post_values['ID'], $uploaddir_path1 );
					}
					else{	
						$post_info = array(
							'ID'   => $post_values['ID'],
							'post_mime_type' => $file_type['type'],
							'post_title'     => $attachment_title,
							'post_content'   => '',
							'post_status'    => 'inherit',
							'post_author'  => $data_array['post_author'],
							'guid'           => $uploaddir_url . "/" .  $fimg_name,
							);
						$attach_id=$post_values['ID'];
					 wp_update_post($post_info);
					  $attach_data = wp_generate_attachment_metadata( $post_values['ID'], $uploaddir_path );
					}		
					
					wp_update_attachment_metadata( $post_values['ID'],  $attach_data );
					wp_update_post(array(
						'ID'           => $post_values['ID'],
						'post_name'     => $fimg_name,
						'post_title'   => $data_array['title'],
						'post_content' => $data_array['description'],
						'post_excerpt' => $data_array['caption'],
						'guid'         => $uploaddir_url . "/" .  $fimg_name,
						));
						global $wpdb;
						$wpdb->update($wpdb->posts, ['guid' => $uploaddir_url . "/" .  $fimg_name], ['ID' => $post_values['ID']]);	
					if($post_values['ID'] != null && isset($alttext['_wp_attachment_image_alt'])){
					update_post_meta($post_values['ID'], '_wp_attached_file', $attach_data['file']);  
					update_post_meta($post_values['ID'], '_wp_attachment_image_alt', $alttext['_wp_attachment_image_alt']);
					} 
					$core_instance->detailed_log[$line_number]['Message'] = 'Updated Images '  . ' ID: ' . $attach_id ;
	        		$core_instance->detailed_log[$line_number]['VERIFY'] = "<b> Click here to verify</b> - <a href='" . get_edit_post_link( $attach_id, true ) . "'target='_blank' title='" . esc_attr( 'Edit this item' ) . "'>Admin View</a>";
					$log_table_name = $wpdb->prefix ."cfimport_detail_log";
					$updated_row_counts = $helpers_instance->update_count($unikey_value,$unikey_name);
					$created_count = $updated_row_counts['created'];
					$updated_count = $updated_row_counts['updated'];
					$skipped_count = $updated_row_counts['skipped'];
					$wpdb->get_results("UPDATE $log_table_name SET updated = $updated_count WHERE $unikey_name = '$unikey_value'");
				}
				
				else{
					$attach_id=$this->images_import_function($upload, $attachment_title, $file_type, $uploaddir_paths, $post_info, $uploaddir_path, $data_array, $alttext, $line_number);
					$log_table_name = $wpdb->prefix ."cfimport_detail_log";
					$updated_row_counts = $helpers_instance->update_count($unikey_value,$unikey_name);
					$created_count = $updated_row_counts['created'];
					$updated_count = $updated_row_counts['updated'];
					$skipped_count = $updated_row_counts['skipped'];
					$wpdb->get_results("UPDATE $log_table_name SET created = $created_count WHERE $unikey_name = '$unikey_value'");
					
				}

		    }
					
            else{
				
				$post_info = array(
					'guid'           => $uploaddir_url . "/" .  $fimg_name,
					'post_mime_type' => $file_type['type'],
					'post_title'     => $attachment_title,
					'post_content'   => '',
					'post_status'    => 'inherit',
					'post_author'  => $data_array['post_author']
					);
				
					$uploadpath=explode(".",$fimg_name);
					$imagename=$uploadpath[0];
					$length=strlen($imagename);
					$index=$length-1;
					$upload=$imagename[$index]	;
					if($upload=='1'){
						$fimg_names = $attachment_title  . "." . $file_type['ext'];  
							$uploaddir_path1 = $uploaddir_paths . "/" . $fimg_names;
							$attach_id = wp_insert_attachment( $post_info,$uploaddir_path1 );
							$attach_data = wp_generate_attachment_metadata( $attach_id, $uploaddir_path1 );
					}
					else{			
					  $attach_id = wp_insert_attachment( $post_info,$uploaddir_path );
					  $attach_data = wp_generate_attachment_metadata( $attach_id, $uploaddir_path );
					}		
					
					wp_update_attachment_metadata( $attach_id,  $attach_data );
					wp_update_post(array(
						'ID'           => $attach_id,
						'post_title'   => $data_array['title'],
						'post_content' => $data_array['description'],
						'post_excerpt' => $data_array['caption']
						));
						
			      if($attach_id != null && isset($alttext['_wp_attachment_image_alt'])){  
				    update_post_meta($attach_id, '_wp_attachment_image_alt', $alttext['_wp_attachment_image_alt']);
			      }
				  $core_instance->detailed_log[$line_number]['Message'] = 'Inserted Images '  . ' ID: ' . $attach_id ;
	        	  $core_instance->detailed_log[$line_number]['VERIFY'] = "<b> Click here to verify</b> - <a href='" . get_edit_post_link( $attach_id, true ) . "'target='_blank' title='" . esc_attr( 'Edit this item' ) . "'>Admin View</a>";
				  $log_table_name = $wpdb->prefix ."cfimport_detail_log";
					$updated_row_counts = $helpers_instance->update_count($unikey_value,$unikey_name);
					$created_count = $updated_row_counts['created'];
					$updated_count = $updated_row_counts['updated'];
					$skipped_count = $updated_row_counts['skipped'];
					$wpdb->get_results("UPDATE $log_table_name SET created = $created_count WHERE $unikey_name = '$unikey_value'");

			}


		}
		$media_handle = get_option('smack_image_options');
			
		if($media_handle['media_settings']['thumbnail'] == 'true') {
			$thumbnail_width = get_option('thumbnail_size_w');
			$thumbnail_height = get_option('thumbnail_size_h');
			$metadata = wp_get_attachment_metadata($attach_id);
			$metadata['width'] = $thumbnail_width;
			$metadata['height'] = $thumbnail_height;
			wp_update_attachment_metadata($attach_id,$metadata);
		}
		elseif($media_handle['media_settings']['medium'] == 'true'){
			$medium_width = get_option('medium_size_w');
			$medium_height = get_option('medium_size_h');
			$metadata = wp_get_attachment_metadata($attach_id);
			$metadata['width'] = $medium_width;
			$metadata['height'] = $medium_height;
			wp_update_attachment_metadata($attach_id,$metadata);
		}
		elseif($media_handle['media_settings']['medium_large'] == 'true') {
			$medium_large_width = get_option('medium_large_size_w');
			$medium_large_height = get_option('medium_large_size_h');
			$metadata = wp_get_attachment_metadata($attach_id);
			$metadata['width'] = $medium_large_width;
			$metadata['height'] = $medium_large_height;
			wp_update_attachment_metadata($attach_id,$metadata);
		}
		elseif($media_handle['media_settings']['large'] == 'true'){
			$large_width = get_option('large_size_w');
			$large_height = get_option('large_size_h');
			$metadata = wp_get_attachment_metadata($attach_id);
			$metadata['width'] = $large_width;
			$metadata['height'] = $large_height;
			wp_update_attachment_metadata($attach_id,$metadata);
		}
		else{
			$get_image_size = getimagesize($uploaddir_path);
			$attach_data['width'] = $get_image_size[0];
			$attach_data['height'] = $get_image_size[1];
			wp_update_attachment_metadata($attach_id,$attach_data);
		}
		return $attach_id;
	}
	public function images_import_function($upload, $attachment_title, $file_type, $uploaddir_paths, $post_info, $uploaddir_path, $data_array, $alttext, $line_number){
		global $wpdb;
		global $core_instance; 

		if($upload=='1'){
			$fimg_names = $attachment_title  . "." . $file_type['ext'];  
			$uploaddir_path1 = $uploaddir_paths . "/" . $fimg_names;
			$attach_id = wp_insert_attachment( $post_info,$uploaddir_path1 );
			$attach_data = wp_generate_attachment_metadata( $attach_id, $uploaddir_path1 );
		}
		else{			
			$attach_id = wp_insert_attachment( $post_info,$uploaddir_path );
			$attach_data = wp_generate_attachment_metadata( $attach_id, $uploaddir_path );
		}	
		wp_update_attachment_metadata( $attach_id,  $attach_data );
		wp_update_post(array(
			'ID'           => $attach_id,
			'post_title'   => $data_array['title'],
			'post_content' => $data_array['description'],
			'post_excerpt' => $data_array['caption']
		));

		if($attach_id != null && isset($alttext['_wp_attachment_image_alt'])){  
			update_post_meta($attach_id, '_wp_attachment_image_alt', $alttext['_wp_attachment_image_alt']);
		}
		$core_instance->detailed_log[$line_number]['Message'] = 'Inserted Images '  . ' ID: ' . $attach_id ;
		$core_instance->detailed_log[$line_number]['VERIFY'] = "<b> Click here to verify</b> - <a href='" . get_edit_post_link( $attach_id, true ) . "'target='_blank' title='" . esc_attr( 'Edit this item' ) . "'>Admin View</a>";
		return $attach_id;
	}

	function overwrite($post_id , $img_url){

		global $wpdb;
		$sql = "SELECT post_mime_type FROM {$wpdb->prefix}posts WHERE ID = $post_id";
		list($current_filetype) = $wpdb->get_row($sql, ARRAY_N);
		$current_filename = wp_get_attachment_url($post_id);
		$current_guid = $current_filename;
		$current_filename = substr($current_filename, (strrpos($current_filename, "/") + 1));
		$ID = $post_id;
		$current_file = get_attached_file($ID);
		$current_path = substr($current_file, 0, (strrpos($current_file, "/")));
		$current_file = preg_replace("|(?<!:)/{2,}|", "/", $current_file);
		$current_filename = basename($current_file);
		$current_metadata = wp_get_attachment_metadata( $post_id );
		$data = file_get_contents($img_url);
		$new_filename = basename($img_url);
		$file_type = wp_check_filetype( $new_filename, null );
		$new_filetype = $file_type["type"];
		if(empty($new_filetype['ext'])){
			$new_filename = @basename($img_url);
			$new_filename = str_replace(' ', '-', trim($new_filename));
			$new_filename = preg_replace('/[^a-zA-Z0-9._\-\s]/', '', $new_filename);
		}
		if(empty($new_filetype)){
			$new_filetype = 'image/jpeg';
		}
		if(preg_match_all('/\b(?:(?:https?|http|ftp|file):\/\/|www\.|ftp\.)[-A-Z0-9+&@#\/%=~_|$?!:,.]*[A-Z0-9+&@#\/%=~_|$]/i', $img_url , $matchedlist, PREG_PATTERN_ORDER)) {
			$img_url = $img_url;

		}   
		else{
			$media_dir = wp_get_upload_dir();
			$names = glob($media_dir['path'].'/'.'*.*');
			foreach($names as $values){
				$exp=basename($values);
				if($exp == $img_url){
						$attach_data = wp_generate_attachment_metadata( $ID, $values );
		                wp_update_attachment_metadata( $ID,  $attach_data );

				}
			
				if (!empty($f_img) && strpos($values, $img_url) !== false) {
					$img_url = $media_dir['url'].'/'.$img_url;

				}
			}         
			return true;   
		}

		$original_file_perms = fileperms($current_file) & 0777;
		$this->emr_delete_current_files( $current_file, $post_id, $current_metadata);
		$new_filename = wp_unique_filename( $current_path, $new_filename );
		$new_file = $current_path . "/" . $new_filename;
		file_put_contents($new_file, $data);
		@chmod($current_file, $original_file_perms);
		$new_filetitle = preg_replace('/\.[^.]+$/', '', basename($new_file));
		$new_guid = str_replace($current_filename, $new_filename, $current_guid);
		$post_date = gmdate( 'Y-m-d H:i:s' );
		$sql = $wpdb->prepare(
			"UPDATE {$wpdb->prefix}posts SET post_title = '$new_filetitle', post_name = '$new_filetitle', guid = '$new_guid', post_mime_type = '$new_filetype', post_date = '$post_date', post_date_gmt = '$post_date' WHERE ID = %d;",
			$post_id
		);
		$wpdb->query($sql);
		$sql = $wpdb->prepare(
			"SELECT meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key = '_wp_attached_file' AND post_id = %d;",
			$post_id
		);
		$old_meta_name = $wpdb->get_row($sql, ARRAY_A);
		$old_meta_name = $old_meta_name["meta_value"];
		// Make new postmeta _wp_attached_file
		$new_meta_name = str_replace($current_filename, $new_filename, $old_meta_name);
		$sql = $wpdb->prepare(
			"UPDATE {$wpdb->prefix}postmeta SET meta_value = '$new_meta_name' WHERE meta_key = '_wp_attached_file' AND post_id = %d;",
			$post_id
		);
		$wpdb->query($sql);
		$new_metadata = wp_generate_attachment_metadata( $post_id, $new_file );
		wp_update_attachment_metadata( $post_id, $new_metadata );
		$current_base_url = $this->emr_get_match_url( $current_guid ); //  .wp-contet.uplodas/ dae name without ext
		$sql = $wpdb->prepare(
			"SELECT ID, post_content FROM {$wpdb->prefix}posts WHERE post_status = 'publish' AND post_content LIKE %s;",
			'%' . $current_base_url . '%'
		);
		$rs = $wpdb->get_results( $sql, ARRAY_A );
		$number_of_updates = 0;
		if ( ! empty( $rs ) ) {
			$search_urls  = $this->emr_get_file_urls( $current_guid, $current_metadata );
			$replace_urls = $this->emr_get_file_urls( $new_guid, $new_metadata );
			$replace_urls = $this->emr_normalize_file_urls( $search_urls, $replace_urls );
			foreach ( $rs AS $rows ) {
				$number_of_updates = $number_of_updates + 1;
				// replace old URLs with new URLs.
				$post_content = $rows["post_content"];
				$post_content = addslashes( str_replace( $search_urls, $replace_urls, $post_content ) );
				$sql = $wpdb->prepare(
					"UPDATE {$wpdb->prefix}posts SET post_content = '$post_content' WHERE ID = %d;",
					$rows["ID"]
				);
				$wpdb->query( $sql );
			}
		}
		update_attached_file( $post_id, $new_file );
	}


	function emr_delete_current_files( $current_file, $post_id, $metadta = null) {
		// Find path of current file
		$current_path = substr($current_file, 0, (strrpos($current_file, "/")));
		// Check if old file exists first
		if (file_exists($current_file)) {
			// Now check for correct file permissions for old file
			clearstatcache();
			if (is_writable($current_file)) {
				// Everything OK; delete the file
				unlink($current_file);
			}
			else {
				// File exists, but has wrong permissions. Let the user know.
				printf( esc_html__('The file %1$s can not be deleted by the web server, most likely because the permissions on the file are wrong.'), $current_file);
				exit;	
			}
		}

		// Delete old resized versions if this was an image
		$suffix = substr($current_file, (strlen($current_file)-4));
		$prefix = substr($current_file, 0, (strlen($current_file)-4));

		if (strtolower($suffix) === ".pdf") {
			$prefix .= "-pdf";
			$suffix = ".jpg";
		}
		$imgAr = array(".png", ".gif", ".jpg", ".jpeg");
		if (in_array($suffix, $imgAr)) {
			// It's a png/gif/jpg based on file name
			// Get thumbnail filenames from metadata
			if ( empty( $metadata ) ) {
				$metadata = wp_get_attachment_metadata( $post_id );
			}
			if (is_array($metadata)) { // Added fix for error messages when there is no metadata (but WHY would there not be? I don't know…)
				foreach($metadata["sizes"] AS $thissize) {
					// Get all filenames and do an unlink() on each one;
					$thisfile = $thissize["file"];
					// Create array with all old sizes for replacing in posts later
					$oldfilesAr[] = $thisfile;
					// Look for files and delete them
					if (strlen($thisfile)) {
						$thisfile = $current_path . "/" . $thissize["file"];
						if (file_exists($thisfile)) {
							unlink($thisfile);
						}
					}
				}
			}

		}
	}

	function emr_get_match_url($url) {
		$url = $this->emr_remove_scheme($url);
		$url = $this->emr_maybe_remove_query_string($url);
		$url = $this->emr_remove_size_from_filename($url, true);
		$url = $this->emr_remove_domain_from_filename($url);
		return $url;
	}

	function emr_remove_scheme( $url ) {
		return preg_replace( '/^(?:http|https):/', '', $url );
	}

	function emr_maybe_remove_query_string( $url ) {
		$parts = explode( '?', $url );
		return reset( $parts );
	}

	function emr_remove_size_from_filename( $url, $remove_extension = false ) {
		$url = preg_replace( '/^(\S+)-[0-9]{1,4}x[0-9]{1,4}(\.[a-zA-Z0-9\.]{2,})?/', '$1$2', $url );
		if ( $remove_extension ) {
			$ext = pathinfo( $url, PATHINFO_EXTENSION );
			$url = str_replace( ".$ext", '', $url );
		}
		return $url;
	}

	function emr_remove_domain_from_filename($url) {
		// Holding place for possible future function
		$url = str_replace($this->emr_remove_scheme(get_bloginfo('url')), '', $url);
		return $url;
	}


	function emr_get_file_urls( $guid, $metadata ) {
		$urls = array();
		$guid = $this->emr_remove_scheme( $guid );
		$guid= $this->emr_remove_domain_from_filename($guid);
		$urls['guid'] = $guid;
		if ( empty( $metadata ) ) {
			return $urls;
		}
		$base_url = dirname( $guid );
		if ( ! empty( $metadata['file'] ) ) {
			$urls['file'] = trailingslashit( $base_url ) . wp_basename( $metadata['file'] );
		}
		if ( ! empty( $metadata['sizes'] ) ) {
			foreach ( $metadata['sizes'] as $key => $value ) {
				$urls[ $key ] = trailingslashit( $base_url ) . wp_basename( $value['file'] );
			}
		}
		return $urls;
	}

	function emr_normalize_file_urls( $old, $new ) {
		$result = array();
		if ( empty( $new['guid'] ) ) {
			return $result;
		}
		$guid = $new['guid'];
		foreach ( $old as $key => $value ) {
			$result[ $key ] = empty( $new[ $key ] ) ? $guid : $new[ $key ];
		}
		return $result;
	}
	public function image_meta_table_entry($post_values, $post_id ,$acf_wpname_element, $acf_csv_name, $hash_key, $plugin, $get_import_type, $templatekey = null,$gmode = null,$header_array = null, $value_array = null){
		global $wpdb;
		$shortcode_table = $wpdb->prefix . "ultimate_cf_importer_shortcode_manager";
		$media_handle = get_option('smack_image_options');
		$type = $wpdb ->get_var("SELECT post_type from {$wpdb->prefix}posts where id ='{$post_id}'");
		$image_type = $plugin;
	
		if($media_handle['media_settings']['use_ExistingImage'] == 'true'){
			$fimg_name = @basename($acf_csv_name);
			$fimg_name = str_replace(' ', '-', trim($fimg_name));
			$fimg_name = preg_replace('/[^a-zA-Z0-9._\-\s]/', '', $fimg_name);			

			$attachment_id = $wpdb->get_var("SELECT ID FROM ".$wpdb->prefix."posts WHERE post_type = 'attachment' AND guid LIKE '%$fimg_name'");
			if($attachment_id){
				$attach_id = $this->media_handling( $acf_csv_name, $post_id ,$post_values,'','',$hash_key,$templatekey,'','',$header_array,$value_array);	
				
				if(strpos($acf_csv_name, 'loading-image.jpg')){
				}
				else{
					update_option('smack_schedule_image_exists_'.$plugin.'_'.$post_id, true);
				}
			}
			else{
				if(empty($post_values)){
					$acf_image_meta = NULL;
				}else{
					if(strpos($plugin, 'jetengine_') !== false ){
						$image_meta_value = array(
							'headerarray' => $header_array,
							'valuearray' => $value_array
						);
						$acf_image_meta  =json_encode($image_meta_value);
					}
					else{
						$acf_image_meta = serialize($post_values);
					}	
				}

				if($post_id && ($post_id != 0) && !empty($acf_csv_name)){
					$wpdb->insert($shortcode_table,
						array('image_shortcode' => $plugin.'_image__'.$acf_wpname_element,
								'original_image' => $acf_csv_name,
								'post_id' => $post_id,
								'hash_key' => $hash_key,
								'image_meta' => $acf_image_meta,
						),
						array('%s','%s','%d','%s','%s')
					);
				}

				$acf_csv_name = WP_PLUGIN_URL . '/wp-importer-custom-fields-basic-pro/assets/images/loading-image.jpg';	
				//added	
				$attach_id = $this->media_handling($acf_csv_name, $post_id, $post_values,'','',$hash_key,$templatekey);	
			}
		}
		else{
			
			
			$acf_image_meta = serialize($post_values);

			if($post_id && ($post_id != 0) && !empty($acf_csv_name)){
				$wpdb->insert($shortcode_table,
					array('image_shortcode' => $plugin.'_image__'.$acf_wpname_element,
							'original_image' => $acf_csv_name,
							'post_id' => $post_id,
							'hash_key' => $hash_key,
							'image_meta' => $acf_image_meta,
					),
					array('%s','%s','%d','%s','%s')
				);
			}
			$acf_csv_name = WP_PLUGIN_URL . '/wp-importer-custom-fields-basic-pro/assets/images/loading-image.jpg';	
			//added	
			$attach_id = $this->media_handling($acf_csv_name, $post_id, $post_values,'','',$hash_key,$templatekey);	
		}
		return $attach_id;
	}
}
