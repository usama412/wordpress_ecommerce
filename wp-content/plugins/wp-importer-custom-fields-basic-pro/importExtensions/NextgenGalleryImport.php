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
class NextGenGalleryImport {
	private static $nextgengallery_core_instance = null,$smack_instance,$media_instance;

	public  function __construct(){
		add_action('wp_ajax_zip_ngg_upload', array($this , 'zip_ngg_upload'));
	}	
	
	public static function getInstance() {	
		if (self::$nextgengallery_core_instance == null) {
			self::$nextgengallery_core_instance = new NextGenGalleryImport();
			self::$smack_instance = SmackCSV::getInstance();
			return self::$nextgengallery_core_instance;
		}
		return self::$nextgengallery_core_instance;
	}

	public function zip_ngg_upload(){
		check_ajax_referer('smack-importer-custom-fields-basic-pro', 'securekey');
		global $wpdb;
		$zip_file_name = $_FILES['zipFile']['name'];
		$zipname = preg_replace('/\\.[^.\\s]{3,4}$/', '', $zip_file_name);
		update_option( 'smack_zip_name',$zipname);
		$zip_folder_name = explode('.zip',$zip_file_name);
		$zip_folder_name = $zip_folder_name[0];
		$hash_key = self::$smack_instance->convert_string2hash_key($zip_file_name);
		$upload_dir = self::$smack_instance->create_upload_dir();
		$path = $upload_dir . $hash_key . '.zip';
		move_uploaded_file($_FILES['zipFile']['tmp_name'], $path);
		chmod($path, 0777);
		$zip = new \ZipArchive;
		$res = $zip->open($path);
		$get_ngg_options = get_option('ngg_options');
		$get_gallery_path = explode('/', $get_ngg_options['gallerypath']);
		$gallery_name=$zip_folder_name;
		$gallery_table = $wpdb->prefix . 'ngg_gallery';
		$wpdb->insert( $gallery_table, array(
			'title' => $zip_folder_name ,
			'name'  => $zip_folder_name,
			'slug'   => $zip_folder_name,
			'path'    => 'wp-content/gallery/'.$gallery_name.'/'
		)
	);
		$gallery_dir = WP_CONTENT_DIR . '/' . $get_gallery_path[1];
		$image_id = $wpdb->insert_id;
		$storage  = \C_Gallery_Storage::get_instance();
		$params = array('watermark' => false, 'reflection' => false);
		$result = $storage->generate_thumbnail($image_id, $params);
		$upload_dir = wp_upload_dir();
		$gallery_table = $wpdb->prefix . 'ngg_gallery';
		$get_gallery_id = $wpdb->get_col($wpdb->prepare("select gid from $gallery_table where name='$gallery_name'"));
		$gallery_id = $get_gallery_id[0];
		$gallery_abspath = $storage->get_gallery_abspath($gallery_id);
		$image_abspath = $storage->get_full_abspath($image_id);
		$storage->get_full_url($image_id);
		$storage->_image_mapper->find($image_id);
		$target_basename = \M_I18n::mb_basename($image_abspath);
		if (strpos($image_abspath, $gallery_abspath) === 0) {
			$target_relpath = substr($image_abspath, strlen($gallery_abspath));
		} else {
			if ($gallery_id) {
				$target_relpath = path_join(strval($gallery_id), $target_basename);
			} else {
				$target_relpath = $target_basename;
			}
		}
		$target_relpath = trim($target_relpath, '\\/');
		$target_path = path_join($gallery_dir, $target_relpath);
		$max_count = 100;
		$count = 0;
		while (@file_exists($target_path) && $count <= $max_count) {
			$count++;
			$pathinfo = \M_I18n::mb_pathinfo($target_path);
			$dirname = $pathinfo['dirname'];
			$filename = $pathinfo['filename'];
			$extension = $pathinfo['extension'];
			$rand = mt_rand(1, 9999);
			$basename = $filename . '_' . sprintf('%04d', $rand) . '.' . $extension;
			$target_path = path_join($dirname, $basename);
		}
		$target_dir = $gallery_dir;
	
		wp_mkdir_p($target_dir);
		if ($res === TRUE) {
			$zip->extractTo($target_dir);
			$zip->close();
			$result['success'] = true;
		} else {
			$result['success'] = false;
		}	

		$wpdb->delete( $wpdb->prefix.'ngg_gallery', array( 'gid' => $gallery_id));

		echo wp_json_encode($result);
		wp_die();
	}
	
	public function nextgenImport($header_array ,$value_array , $map , $post_id , $selected_type,$hash_key) {
		global $wpdb;
		$file_table_name = $wpdb->prefix ."smackcf_file_events";
		$get_id = $wpdb->get_results( "SELECT file_name FROM $file_table_name WHERE `hash_key` = '$hash_key'");
		
		$file_name = $get_id[0]->file_name;
		$file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
		foreach($map as $key => $value){
			$csv_value= trim($map[$key]);
			if(!empty($csv_value)){
				$get_key= array_search($csv_value , $header_array);
				if(isset($value_array[$get_key])){
					//$csv_element = $value_array[$get_key];  
					if($file_extension == 'xml'){
						$csv_element = $value;	
					}
					else{
						$csv_element = $value_array[$get_key];	
					}
					$wp_element= trim($key);

					if(!empty($csv_element) && !empty($wp_element)){
						$post_values[$wp_element] = $csv_element;
					}
				}
			}
		}
		if(!empty($post_values['nextgen_gallery'])) {
			$thumbnailId = self::importImage($header_array ,$value_array , $map , $post_id , $selected_type,$post_values);
		}
		if(isset($thumbnailId) && $thumbnailId != null) {
			set_post_thumbnail( $post_id, $thumbnailId );
		}
	}

	public function importImage($header_array ,$value_array , $map , $post_id , $selected_type,$post_values) {
		
		global $wpdb;
		$get_ngg_options = get_option('ngg_options');
		$get_gallery_path = explode('/', $get_ngg_options['gallerypath']);
		$gallery_name=$post_values['nextgen_gallery'];
		$gallery_dir = WP_CONTENT_DIR . '/' . $get_gallery_path[1] . '/' . $gallery_name;
		$names = glob($gallery_dir.'/'.'*.*');
		foreach($names as $values){
			if (strpos($values, $post_values['image_url']) !== false) {
				$fImg_name = content_url().'/'.$get_gallery_path[1] . '/' . $gallery_name.'/'.$post_values['image_url'];
			}
		}           		
		$path_parts = pathinfo($post_values['image_url']);
		$real_fImg_name=$path_parts['filename'];
		$fImg_name = @basename($post_values['image_url']);
		$fImg_name = str_replace(' ', '-', trim($fImg_name));
		$fImg_name = preg_replace('/[^a-zA-Z0-9._\-\s]/', '', $fImg_name);
		$fImg_name = urlencode($fImg_name);	
		$gallery_table = $wpdb->prefix . 'ngg_gallery';
		if(is_numeric($gallery_name)){
			$gallery_id = $gallery_name;
			$gallery_name = $wpdb->get_var("select name from $gallery_table where gid='$gallery_id'");	
		}
		else{
			$get_gallery_id = $wpdb->get_results("select gid from $gallery_table where name='$gallery_name'",ARRAY_A);	
			$gallery_id = $get_gallery_id[0]['gid'];
		}
		$img_import_date = date('Y-m-d H:i:s');
		// Download external images to your media if true
		$ngg_gallery_info = $this->createAttachmentEntryInNGGGalley($real_fImg_name, $gallery_id, $fImg_name, $img_import_date,$post_values);
		$image_id = $ngg_gallery_info['gallery_id'];
		update_post_meta($post_id, '_ngg_image_id', $image_id);
		if($image_id && $ngg_gallery_info['alreadyexists'] !== true){

			$gallery_dir = WP_CONTENT_DIR . '/' . $get_gallery_path[1] . '/' . $gallery_name;
			$storage  = \C_Gallery_Storage::get_instance();
			$params = array('watermark' => false, 'reflection' => false);
			$storage->generate_thumbnail($image_id, $params);
			$copy_image = TRUE;
			$gallery_abspath = $storage->get_gallery_abspath($gallery_id);
			$image_abspath = $storage->get_full_abspath($image_id);
			$url = $storage->get_full_url($image_id);
			
			$image = $storage->_image_mapper->find($image_id);
			$target_basename = \M_I18n::mb_basename($image_abspath);
			if (strpos($image_abspath, $gallery_abspath) === 0) {
				$target_relpath = substr($image_abspath, strlen($gallery_abspath));
			} else {
				if ($gallery_id) {
					$target_relpath = path_join(strval($gallery_id), $target_basename);
				} else {
					$target_relpath = $target_basename;
				}
			}
			$target_relpath = trim($target_relpath, '\\/');
			$target_path = path_join($gallery_dir, $target_relpath);

			$image_url = str_replace(" ","%20", $post_values['image_url']);
			$image= file_get_contents($image_url);

			file_put_contents(ABSPATH .'wp-content/gallery/'.$gallery_name.'/'.$fImg_name,$image);
			file_put_contents(ABSPATH .'wp-content/gallery/'.$gallery_name.'/'.'thumbs/'.'thumbs_'.$fImg_name,$image);
			$max_count = 100;
			$count = 0;
			while (file_exists($target_path) && $count <= $max_count) {
				$count++;
				$pathinfo = \M_I18n::mb_pathinfo($target_path);
				$dirname = $pathinfo['dirname'];
				$filename = $pathinfo['filename'];
				$extension = $pathinfo['extension'];
				$rand = mt_rand(1, 9999);
				$basename = $filename . '_' . sprintf('%04d', $rand) . '.' . $extension;
				$target_path = path_join($dirname, $basename);
			}
			$attachment_id = $wpdb->get_results("select ID from {$wpdb->prefix}posts where guid = '$url'" ,ARRAY_A);
			if ($copy_image) {
				@copy($image_abspath, $target_path);
				if (!$attachment_id) {
					$size = getimagesize($target_path);
					$image_type = $size ? $size['mime'] : 'image/jpeg';
					$title = sanitize_file_name($image->alttext);
					$caption = sanitize_file_name($image->description);
					$attachment = array('post_title' => $title, 'post_content' => $caption, 'post_status' => 'attachment', 'post_parent' => 0, 'post_mime_type' => $image_type, 'guid' => $url);
				}
				update_post_meta($attachment_id, '_ngg_image_id', $image_id);
				wp_update_attachment_metadata($attachment_id, wp_generate_attachment_metadata($attachment_id, $target_path));
			}
			return $attachment_id;
		}
	}

	public function createAttachmentEntryInNGGGalley($real_fImg_name, $gallery_id, $fImg_name, $img_import_date,$post_values){

		global $wpdb;
		$ngg_gallery_id = $wpdb->get_var("SELECT pid FROM ".$wpdb->prefix."ngg_pictures WHERE filename = '$fImg_name'");
		if(!$ngg_gallery_id){
			$wpdb->insert( $wpdb->prefix .'ngg_pictures', array(
				'image_slug' => $real_fImg_name,
				'galleryid'  => $gallery_id,
				'filename'   => $fImg_name,
				'alttext'    => $post_values['alttext'],
				'imagedate'  => $img_import_date,
				'description' => $post_values['description']

			)
		);
			return ['gallery_id' => $wpdb->insert_id, 'alreadyexists' => false];
		}

		return ['gallery_id' => $ngg_gallery_id, 'alreadyexists' => true];
	}

	public function nextgenGallery($data_array,$check,$mode,$line_number,$header_array,$value_array)
	{ 
		
		global $wpdb,$core_instance; 
		$core_instance = CoreFieldsImport::getInstance();
		$media_instance = MediaHandling::getInstance();
		$get_ngg_options = get_option('ngg_options');
		$get_gallery_path = explode('/', $get_ngg_options['gallerypath']);
		$gal_id = $data_array['nextgen_gallery'];
		$gallery_table = $wpdb->prefix . 'ngg_gallery';
		$get_gallery_name = $wpdb->get_results("select * from $gallery_table where gid= {$gallery_id}",ARRAY_A);
		$gallery = $get_gallery_name[0]['path'];
		if(is_numeric($data_array['nextgen_gallery'])){
			$gallery_id = $data_array['nextgen_gallery'];
			$gallery_name = $wpdb->get_var("select name from $gallery_table where gid=$gallery_id");	
		}
		else{
			$get_gallery_id = $wpdb->get_results("SELECT gid FROM $gallery_table WHERE title='$gal_id' ORDER BY gid DESC"); 
			$gallery_id = $get_gallery_id[0]->gid;
		}
		//$gallery_name = str_replace('wp-content/gallery/', '', $gallery);
		
		$gallery_dir = WP_CONTENT_DIR . '/' . $get_gallery_path[1] . '/' . $gallery_name;
		$names = glob($gallery_dir . '/' . '*.*');
		foreach ($names as $values) {
			if (strpos($values, $data_array['featured_image']) !== false) {
				$fImg_name = content_url() . '/' . $get_gallery_path[1] . '/' . $gallery_name . '/' . $data_array['featured_image'];
			}
		}
		$path_parts = pathinfo($data_array['featured_image']);
		$real_fImg_name = $path_parts['filename'];
		$fImg_name = @basename($data_array['featured_image']);
		$fImg_name = str_replace(' ', '-', trim($fImg_name));
		$fImg_name = preg_replace('/[^a-zA-Z0-9._\-\s]/', '', $fImg_name);
		$fImg_name = urlencode($fImg_name);
		$gallery_table = $wpdb->prefix . 'ngg_gallery';
		$picturestable = $wpdb->prefix . 'ngg_pictures';
		$img_import_date = date('Y-m-d H:i:s'); 
		if($mode == 'Insert'){
			$wpdb->insert(
				$wpdb->prefix . 'ngg_pictures',
				array(
					'image_slug' => $real_fImg_name,
					'filename' => isset($data_array['filename']) ? $data_array['filename'] : '',					
					'galleryid'  => $gallery_id,
					'filename'   => $fImg_name,
					'alttext'    => isset($data_array['alt_text']) ? $data_array['alt_text'] : '', //changed
					'imagedate'  => $img_import_date,
					'description' => isset($data_array['description']) ? $data_array['description'] : ''
	
				)
			);
			$pID = $wpdb->insert_id;
			$core_instance->detailed_log[$line_number]['Message'] = 'Inserted ngg Images '  . ' ID: ' . $pID ;
	      //  $core_instance->detailed_log[$line_number]['VERIFY'] = "<b> Click here to verify</b> - <a href='" . get_edit_post_link( $pID, true ) . "'target='_blank' title='" . esc_attr( 'Edit this item' ) . "'>Admin View</a>";
		}
		else{
			if($check == 'ID'){	
				$pID = $data_array['id'];
							
				$wpdb->update( $wpdb->prefix . 'ngg_pictures' , array( 'image_slug' => $real_fImg_name,
				'galleryid'  => $gallery_id,
				'filename'   => $fImg_name,
				'alttext'    => $data_array['alt_text'], //changed
				'imagedate'  => $img_import_date,
				'description' => $data_array['description']) , array( 'pid' => $pID ) );
				
			}
			if($check == 'Filename'){
				$filename =$data_array['filename'];
				$get_gallery_name = $wpdb->get_results("SELECT pid FROM $picturestable WHERE filename='$filename' AND galleryid ='$gallery_id' ORDER BY pid DESC"); 
				$pID =$get_gallery_name[0]->pid;
				$wpdb->update( $wpdb->prefix . 'ngg_pictures' , array( 'image_slug' => $real_fImg_name,
				'galleryid'  => $gallery_id,
				'filename'   => $fImg_name,
				'alttext'    => $data_array['alt_text'], //changed
				'imagedate'  => $img_import_date,
				'description' => $data_array['description']) , array( 'pid' => $pID ) );
			}

		}
		
		global $wpdb;
			if(!empty($data_array['manage_tags'])){
				$e_value=$data_array['manage_tags'];
			
				if(strpos($e_value,', ')!== false){
					$split_etag = explode(', ', $e_value);	
				}
				else if (strpos($e_value, ',') !== false){
					$split_etag = explode(',', $e_value);
				}
				else {
					$split_etag = $e_value;
				}
				if(!is_array($split_etag)){
					if(!term_exists($split_etag,'ngg_tag')){
                        $taxonomyID = 	$wpdb->get_results("INSERT INTO {$wpdb->prefix}terms (`slug` , `name`) VALUES( '{$split_etag}', '{$split_etag}')");
						$term_id = $wpdb->insert_id;

						$taxonomyIDs = 	$wpdb->get_results("INSERT INTO {$wpdb->prefix}term_taxonomy (`term_id` , `taxonomy`,`count`) VALUES( $term_id, 'ngg_tag',1)");
						$terms_id = $wpdb->insert_id;
						$retID  = $terms_id ;
					
					        	global $wpdb;

								$get_existing_ids = $wpdb->get_var("SELECT object_id FROM {$wpdb->prefix}term_relationships WHERE object_id = $pID AND term_taxonomy_id = $retID ");
								
								// if(!empty($get_existing_ids)){
								// 	$wpdb->delete( $wpdb->prefix.'term_relationships', array( 'object_id' => $get_existing_ids));
								// }
								$wpdb->insert( $wpdb->prefix .'term_relationships' , array('object_id' =>$pID, 'term_taxonomy_id' => $retID ),array('%d','%d'));
								$term=$wpdb->get_row("SELECT count(object_id) FROM  {$wpdb->prefix}term_relationships tr WHERE tr.term_taxonomy_id = $retID ");

								$term1=json_decode(json_encode($term),true);
   
							   $term2=$term1['count(object_id)'];
							   $wpdb->get_results("UPDATE {$wpdb->prefix}term_taxonomy  SET count = $term2 WHERE term_taxonomy_id = $retID");
						
							
						}	
						else{
							global $wpdb;
								//$term_id=$wpdb->get_row("SELECT term_id FROM {$wpdb->prefix}terms WHERE name='$split_etag' ");
                                $term_id=$wpdb->get_row("SELECT t.term_id FROM {$wpdb->prefix}terms  as t INNER JOIN {$wpdb->prefix}term_taxonomy  as tax ON t.term_id=tax.term_id WHERE t.name='$split_etag' AND tax.taxonomy='ngg_tag'");
								$array=json_decode(json_encode($term_id),true);
								$array2=$array['term_id'];
								$terms_id=$wpdb->get_row("SELECT term_taxonomy_id FROM {$wpdb->prefix}term_taxonomy WHERE term_id='$array2'");
								$array1=json_decode(json_encode($terms_id),true);
								$retID=$array1['term_taxonomy_id'];
								$get_existing_ids = $wpdb->get_var("SELECT object_id FROM {$wpdb->prefix}term_relationships WHERE object_id = $pID AND term_taxonomy_id = $retID ");
								// if(!empty($get_existing_ids)){
								// 	$wpdb->delete( $wpdb->prefix.'term_relationships', array( 'object_id' => $get_existing_ids));
								// }
								$wpdb->insert( $wpdb->prefix .'term_relationships' , array('object_id' =>$pID, 'term_taxonomy_id' => $retID ),array('%d','%d'));
								$term=$wpdb->get_row("SELECT count(object_id) FROM  {$wpdb->prefix}term_relationships tr WHERE tr.term_taxonomy_id = $retID ");
									
								$term1=json_decode(json_encode($term),true);
   
							   $term2=$term1['count(object_id)'];
							   $wpdb->get_results("UPDATE {$wpdb->prefix}term_taxonomy  SET count = $term2 WHERE term_taxonomy_id = $retID");
						}
						
				}
			
				
				if(is_array($split_etag)){
					foreach($split_etag as $tag=>$values){
						
						
							$etagData = (string)$values;
						
						if(!term_exists($etagData,'ngg_tag')){
							$taxonomyID = 	$wpdb->get_results("INSERT INTO {$wpdb->prefix}terms (`slug` , `name`) VALUES( '{$etagData}', '{$etagData}')");
							$term_id = $wpdb->insert_id;

							$taxonomyIDs = 	$wpdb->get_results("INSERT INTO {$wpdb->prefix}term_taxonomy (`term_id` , `taxonomy`) VALUES( $term_id, 'ngg_tag')");
							$terms_id = $wpdb->insert_id;
							$retID  = $terms_id ;
						//}
						//else{
							
									global $wpdb;

									$get_existing_ids = $wpdb->get_var("SELECT object_id FROM {$wpdb->prefix}term_relationships WHERE object_id = $pID AND term_taxonomy_id = $retID ");
									
									// if(!empty($get_existing_ids)){
									// 	$wpdb->delete( $wpdb->prefix.'term_relationships', array( 'object_id' => $get_existing_ids));
									// }

									$wpdb->insert( $wpdb->prefix .'term_relationships' , array('object_id' =>$pID, 'term_taxonomy_id' => $retID ),array('%d','%d'));
								$term=$wpdb->get_row("SELECT count(object_id) FROM  {$wpdb->prefix}term_relationships tr WHERE tr.term_taxonomy_id = $retID ");

								$term1=json_decode(json_encode($term),true);

								$term2=$term1['count(object_id)'];
								$wpdb->get_results("UPDATE {$wpdb->prefix}term_taxonomy  SET count = $term2 WHERE term_taxonomy_id = $retID");
								
							}	
							else{
								global $wpdb;
							
									//$term_id=$wpdb->get_row("SELECT term_id FROM {$wpdb->prefix}terms WHERE name='$etagData' ");
                                    $term_id=$wpdb->get_row("SELECT t.term_id FROM {$wpdb->prefix}terms  as t INNER JOIN {$wpdb->prefix}term_taxonomy  as tax ON t.term_id=tax.term_id WHERE t.name='$etagData' AND tax.taxonomy='ngg_tag'");
									$array=json_decode(json_encode($term_id),true);
									
									$array2=$array['term_id'];
									$terms_id=$wpdb->get_row("SELECT term_taxonomy_id FROM {$wpdb->prefix}term_taxonomy WHERE term_id='$array2'");
									
									$array1=json_decode(json_encode($terms_id),true);
									$retID=$array1['term_taxonomy_id'];
								
									$get_existing_ids = $wpdb->get_var("SELECT object_id FROM {$wpdb->prefix}term_relationships WHERE object_id = $pID AND term_taxonomy_id = $retID ");
									// if(!empty($get_existing_ids)){
									// 	$wpdb->delete( $wpdb->prefix.'term_relationships', array( 'object_id' => $get_existing_ids));
									// }

									$wpdb->insert( $wpdb->prefix .'term_relationships' , array('object_id' =>$pID, 'term_taxonomy_id' => $retID ),array('%d','%d'));
								$term=$wpdb->get_row("SELECT count(object_id) FROM  {$wpdb->prefix}term_relationships tr WHERE tr.term_taxonomy_id = $retID ");

								$term1=json_decode(json_encode($term),true);

								$term2=$term1['count(object_id)'];
								$wpdb->get_results("UPDATE {$wpdb->prefix}term_taxonomy  SET count = $term2 WHERE term_taxonomy_id = $retID");
							}
							
					}
				}
					
			}	
		$gallery_dir = WP_CONTENT_DIR . '/' . $get_gallery_path[1] . '/' . $gallery_name;
		$image_id = $wpdb->insert_id;
		$storage  = \C_Gallery_Storage::get_instance();
		$params = array('watermark' => false, 'reflection' => false);
		$storage->generate_thumbnail($image_id, $params);
		
		$url = $storage->get_full_url($image_id);
		$image = $storage->_image_mapper->find($image_id);
		$target_path =WP_CONTENT_DIR .'/gallery/'.$gallery_name;
		$thumbnail_path = WP_CONTENT_DIR .'/gallery/'.$gallery_name.'/thumbs';
		if(!is_dir($target_path)) {
			wp_mkdir_p($target_path);
			wp_mkdir_p($thumbnail_path);
			chmod($target_path, 0777);
			chmod($thumbnail_path, 0777);
		}
		if(!file_exists(ABSPATH . 'wp-content/gallery/' . $gallery_name.'/'.$fImg_name)){
			$zipname= get_option('smack_zip_name');
			$src=WP_CONTENT_DIR .'/gallery/'.$gallery_name.'/'.$fImg_name;
			$dest[]=ABSPATH . 'wp-content/gallery/' . $gallery_name.'/'.$fImg_name;
			
			foreach($dest as $destination){
				copy($src, $destination);	
			}
		}
		// if(!file_exists(ABSPATH . 'wp-content/gallery/' . $gallery_name.'/'.$fImg_name)){
		// 	$zipname= get_option('smack_zip_name');
		// 	$src=WP_CONTENT_DIR .'/gallery/'.$gallery_name.'/'.$fImg_name;
		// 	$dest[]=ABSPATH . 'wp-content/gallery/' . $gallery_name.'/'.$fImg_name;
			
		// 	foreach($dest as $destination){
		// 		copy($src, $destination);	
		// 	}
		// }
	
		$media_handle = get_option('smack_image_options');	
		
		if($media_handle['media_settings']['media_handle_option'] == 'true'){
			if($media_handle['media_settings']['use_ExistingImage'] == 'true'){
				$image = file_get_contents($data_array['featured_image']);
				if(empty($image)){
					$this->create_ngg_image($data_array, $gallery_name, $fImg_name); 
				}
				else{
					file_put_contents(ABSPATH .'wp-content/gallery/'.$gallery_name.'/'.$fImg_name,$image);
					file_put_contents(ABSPATH .'wp-content/gallery/'.$gallery_name.'/'.'thumbs/'.'thumbs_'.$fImg_name,$image);
					$dir_name = $path_parts['dirname'];
					$year = explode('/wp-content/uploads/', $dir_name);
					@copy(ABSPATH.'/wp-content/uploads/'.$year[1].'/'.$fImg_name, ABSPATH.'/wp-content/gallery/'.$gallery_name.$fImg_name);
					@copy(ABSPATH.'/wp-content/uploads/'.$year[1].'/'.$fImg_name, ABSPATH.'/wp-content/gallery/'.$gallery_name.'/thumbs/'. 'thumbs_' .$fImg_name);
				}
			}
		}elseif($media_handle['media_settings']['newImage'] == 'true'){
			
			$image = file_get_contents($data_array['featured_image']);
			if(empty($image)){
				$this->create_ngg_image($data_array, $gallery_name, $fImg_name); 
			}
			else{
				file_put_contents(ABSPATH . 'wp-content/gallery/' . $gallery_name . '/' . $fImg_name, $image);
				file_put_contents(ABSPATH . 'wp-content/gallery/' . $gallery_name . '/thumbs/' . 'thumbs_' . $fImg_name, $image); 
			}
		}
		else{
			$image = file_get_contents($data_array['featured_image']);
			
			if(empty($image)){
				$this->create_ngg_image($data_array, $gallery_name, $fImg_name); 
			}
			else{
				file_put_contents(ABSPATH . 'wp-content/gallery/' . $gallery_name . '/' . $fImg_name, $image);
				file_put_contents(ABSPATH . 'wp-content/gallery/' . $gallery_name . '/thumbs/' . 'thumbs_' . $fImg_name, $image); 
			}
		}
		$max_count = 100;
		$count = 0;
		while (@file_exists($target_path) && $count <= $max_count) {
			$count++;
			$pathinfo = \M_I18n::mb_pathinfo($target_path);
			$dirname = $pathinfo['dirname'];
			$filename = $pathinfo['filename'];
			$extension = $pathinfo['extension'];
			$rand = mt_rand(1, 9999);
			$basename = $filename . '_' . sprintf('%04d', $rand) . '.' . $extension;
			$target_path = path_join($dirname, $basename);
		}
		//media
		$attach_id = $media_instance->media_handling($data_array['featured_image'],$pID,'','','','','','','',$header_array,$value_array);
		
		return $pID;
	}

	public function create_ngg_image($data_array, $gallery_name, $fImg_name){
		$response = wp_remote_get($data_array['featured_image'], array( 'timeout' => 30));		
		$rawdata =  wp_remote_retrieve_body($response);
		$http_code = wp_remote_retrieve_response_code($response);
		$dest=ABSPATH . 'wp-content/gallery/' . $gallery_name.$fImg_name;
		$thumb=ABSPATH . 'wp-content/gallery/' . $gallery_name.'/thumbs/'. 'thumbs_' .$fImg_name;

		$fp = fopen($dest, 'x');
		fwrite($fp, $rawdata);
		fclose($fp);
		$dirname = date('Y') . '/' . date('m');
		$name[]=$fImg_name;
		
		foreach($name as $fname){
			$thumbs='thumbs_'.$fname;
			@copy(ABSPATH.'wp-content/uploads'.'/'.$dirname.'/'.$fname,ABSPATH . 'wp-content/gallery/' . $gallery_name.$fname);
			@copy(ABSPATH . 'wp-content/gallery/' . $gallery_name.$fname,ABSPATH . 'wp-content/gallery/' . $gallery_name . '/thumbs/' . $thumbs);
		}
			 
	}
}