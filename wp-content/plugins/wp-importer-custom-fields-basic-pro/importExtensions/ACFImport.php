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

class ACFImport {
    private static $acf_instance = null ,$media_instance;

    public static function getInstance() {	
		if (ACFImport::$acf_instance == null) {
                        ACFImport::$acf_instance = new ACFImport;
                        ACFImport::$media_instance = MediaHandling::getInstance();
			return ACFImport::$acf_instance;
		}
		return ACFImport::$acf_instance;
    }

    function acf_import_function($acf_wpname_element ,$acf_csv_element, $importAs , $post_id,$mode,$post_values,$line_number,$hash_key,$gmode,$templatekey){
		
        $acf_wp_name = $acf_wpname_element;
        
        $acf_csv_name = $acf_csv_element; 
        $plugin = 'acf';
        $map_acf_wp_element = $map_acf_csv_element = '';
        $key = '';
        
        $listTaxonomy = get_taxonomies();
        if (in_array($importAs, $listTaxonomy)) {
                $get_import_type = 'term';
        }elseif ($importAs == 'Users') {
                $get_import_type = 'user';
        } else {	
                $get_import_type = 'post';
        }

        global $wpdb;

        $helpers_instance = ImportHelpers::getInstance();

        $get_acf_fields = $wpdb->get_results($wpdb->prepare("select post_content, post_name from {$wpdb->prefix}posts where post_type = %s and post_excerpt = %s", 'acf-field', $acf_wp_name ), ARRAY_A);
        
        foreach($get_acf_fields as $keys => $value_type){
                $get_type_field = unserialize($value_type['post_content']);	
        
                $field_type = $get_type_field['type'];
                
                $key = $get_acf_fields[0]['post_name'];
                $return_format = isset($get_type_field['return_format']) ? $get_type_field['return_format'] : "";
                if($field_type == 'text' || $field_type == 'textarea' || $field_type == 'number' || $field_type == 'email' || $field_type == 'url' || $field_type == 'password' || $field_type == 'range' || $field_type == 'radio' || $field_type == 'true_false' || $field_type == 'time_picker' || $field_type == 'color_picker' || $field_type == 'button_group' || $field_type == 'oembed' || $field_type == 'wysiwyg'){
                        $map_acf_wp_element = $acf_wp_name;
                        $map_acf_csv_element = $acf_csv_name;	
                }
                if($field_type == 'date_time_picker'){
                        $dt_var = trim($acf_csv_name);
				$dateformat = "Y-m-d H:i:s";
				$date_time_of = $helpers_instance->validate_datefield($dt_var,$acf_wp_name,$dateformat,$line_number);
				
				if($mode == 'Insert'){
					if($dt_var == 0 || $dt_var == '')
						$map_acf_csv_element = $dt_var;	
					else{
						$map_acf_csv_element = $date_time_of;
					}
				}
				else{
						if($dt_var == 0 || $dt_var == '')
						$map_acf_csv_element = $dt_var;	
						else{
							$map_acf_csv_element = $date_time_of;
						}
				}
				$map_acf_wp_element = $acf_wp_name;                       
                }
                if($field_type == 'date_picker'){
                        $var = trim($acf_csv_name);
				$dateformat = 'Ymd';
				$date = str_replace('/', '-', "$var");
				$date_of = $helpers_instance->validate_datefield($var,$acf_wp_name,$dateformat,$line_number);				

				if($mode == 'Insert'){
					if($var == 0 || $var == '')
						$map_acf_csv_element = $var;	
					else{
						$map_acf_csv_element = $date_of;
					}
				}
				else{
					if($var == 0 || $var == '')
						$map_acf_csv_element = $var;	
					else{
						$map_acf_csv_element = $date_of;
					}
				}
				$map_acf_wp_element = $acf_wp_name;                        
                }
                if ($field_type == 'google_map') {

                        $location = trim($acf_csv_name);
                        list($add, $lat,$lng) = explode('|', $location);
                        $area = rtrim($add, ",");
                        $map = array(
                                'address' => $area,
                                'lat'     =>  $lat,
                                'lng'     => $lng
                        );
                        $map_acf_csv_element = $map;
                        $map_acf_wp_element = $acf_wp_name;
                }

                if($field_type == 'select'){
                        if($get_type_field['multiple'] == 0){
                                $map_acf_csv_element = $acf_csv_name;
                        }else{
                                $explo_acf_csv_name = explode(',',trim($acf_csv_name));
                                $maps_acf_csv_name = array();
                                foreach($explo_acf_csv_name as $explo_csv_value){
                                        $map_acf_csv_element[] = trim($explo_csv_value);
                                }	
                        }
                        $map_acf_wp_element = $acf_wp_name;
                }

                if($field_type == 'post_object' || $field_type == 'page_link'){
                        if($get_type_field['multiple'] == 0){
                                $maps_acf_csv_name = $acf_csv_name;
                        }else{
                                $explo_acf_csv_name = explode(',',trim($acf_csv_name));
                                $maps_acf_csv_name = array();
                                foreach($explo_acf_csv_name as $explo_csv_value){
                                        $maps_acf_csv_name[] = trim($explo_csv_value);
                                }	
                        }
                        $map_acf_csv_elements = $maps_acf_csv_name;
                        if($get_type_field['multiple'] == 0){
                                if (!is_numeric($map_acf_csv_elements ) ){                                        
                                        $id = $wpdb->get_col($wpdb->prepare("select ID from {$wpdb->prefix}posts where post_title = %s  and post_status = 'publish' order by ID DESC",$map_acf_csv_elements));
                                        if(!empty($id))
                                        $ids[]=$id[0];
                                        $map_acf_csv_element = $ids;
                                }
                                else{
                                        $map_acf_csv_element = $maps_acf_csv_name;
                                }
                        }
                        else{
                                foreach($map_acf_csv_elements as $csv_element){
                                        if (!is_numeric($csv_element ) ){
                                                $id = $wpdb->get_col($wpdb->prepare("select ID from {$wpdb->prefix}posts where post_title = %s",$csv_element));
                                                $map_acf_csv_element[]=$id[0];
                                        }
                                        else{
                                                $map_acf_csv_element = $maps_acf_csv_name;
                                        }
                                }
                        }
                        $map_acf_wp_element = $acf_wp_name;
                }
                if($field_type == 'user'){
                        $maps_acf_csv_name = $acf_csv_name;	
                        $map_acf_wp_element = $acf_wp_name;
                        $explo_acf_csv_name = explode(',',trim($acf_csv_name));	
                                foreach($explo_acf_csv_name as $user){
                                        if(!is_numeric($explo_acf_csv_name)){
                                        $userid = $wpdb->get_col($wpdb->prepare("select ID from {$wpdb->prefix}users where user_login = %s",$user));
                                        foreach($userid as $users){
                                                $map_acf_csv_element = $users;
                                        }
                                }
                        }
                        if(is_numeric($user)){
                                $map_acf_csv_element = $user;
                        }
                }
                if($field_type == 'relationship' || $field_type == 'taxonomy'){	
                        $relations = array();
                        $check_is_valid_term = null;
                        $get_relations = $acf_csv_name;
                        if(!empty($get_relations)){
                                $exploded_relations = explode(',', $get_relations);
                                
                                foreach ($exploded_relations as $relVal) {
                                        $relationTerm = trim($relVal);
                                        //$relTerm[] = $relationTerm;
                                        if ($field_type == 'taxonomy') {
                                                $taxonomy_name =  $get_type_field['taxonomy'];
                                                $check_is_valid_term = $helpers_instance->get_requested_term_details($post_id, array($relationTerm),$taxonomy_name);
                                                
                                                $relations[] = $check_is_valid_term;
                                                
                                        } else {
                                                $reldata = strlen($relationTerm);
                                                $checkrelid = intval($relationTerm);
                                                $verifiedRelLen = strlen($checkrelid);
                                                if ($reldata == $verifiedRelLen) {
                                                        $relations[] = $relationTerm;
                                                } else {
                                                        $relation_id = $wpdb->get_col($wpdb->prepare("select id from {$wpdb->prefix}posts where post_title = %s",$relVal));
                                                        if (!empty($relation_id)) {
                                                                $relations[] = $relation_id[0];
                                                        }
                                                }
                                        }
                                }
                        }

                        $map_acf_csv_element = $relations;
                        $map_acf_wp_element = $acf_wp_name;
                }		

                if($field_type == 'checkbox'){

                        $explode_acf_csv = explode(',',trim($acf_csv_name));
                        $explode_acf_csv_name = [];
                        foreach($explode_acf_csv as $explode_acf_csv_value){
                                $explode_acf_csv_name[] = trim($explode_acf_csv_value);
                        }	
                        
                        $map_acf_csv_element = $explode_acf_csv_name;
                        $map_acf_wp_element = $acf_wp_name;
                }
                if($field_type == 'link'){

                        $serial_acf_csv = explode(',' , $acf_csv_name);
                        $serial_acf_csv_name = [];
                        foreach($serial_acf_csv as $serial_acf_csv_value){
                                $serial_acf_csv_name[] = trim($serial_acf_csv_value);
                        }	
                        $serial_acf_csv_names['url'] = $serial_acf_csv_name[0];
                        $serial_acf_csv_names['title'] = $serial_acf_csv_name[1];
                        if($serial_acf_csv_name[2] == 1){
                                $serial_acf_csv_names['target'] = '_blank';
                        }else{
                                $serial_acf_csv_names['target'] = '';
                        }

                        $map_acf_csv_element = $serial_acf_csv_names;
                        $map_acf_wp_element = $acf_wp_name;
                }
                if ($field_type == 'message') {
                        $get_type_field['message'] = $acf_csv_name;
                }
                elseif ($field_type == 'image') {
                        if ($return_format == 'url' || $return_format == 'array') {
                                $ext = pathinfo($acf_csv_name, PATHINFO_EXTENSION);
                                if($ext== 'jpg' || $ext == 'jpeg' || $ext == 'png') {
                                        $img_id = $wpdb->get_col($wpdb->prepare("select ID from {$wpdb->prefix}posts where guid = %s AND post_type='attachment'",$acf_csv_name));
                                        if(!empty($img_id)) {
                                                $map_acf_csv_element=$img_id[0];                                                
                                        }
                                        else {                                                
                                                $map_acf_csv_element = ACFImport::$media_instance->image_meta_table_entry($post_values, $post_id, $acf_wpname_element, $acf_csv_name, $hash_key, 'acf', $get_import_type,$templatekey,$gmode);
                                        }
                                }
                                else {                                        
                                        $map_acf_csv_element = ACFImport::$media_instance->image_meta_table_entry($post_values, $post_id, $acf_wpname_element, $acf_csv_name, $hash_key, 'acf', $get_import_type,$templatekey,$gmode);
                                }
                        }
                        else {                                
                                $map_acf_csv_element = ACFImport::$media_instance->image_meta_table_entry($post_values, $post_id, $acf_wpname_element, $acf_csv_name, $hash_key, 'acf', $get_import_type,$templatekey,$gmode);
                        }
                        $map_acf_wp_element = $acf_wp_name;
                }
                elseif ($field_type == 'file') {
                        if ($return_format == 'url' || $return_format == 'array') {
                                $ext = pathinfo($acf_csv_name, PATHINFO_EXTENSION);
                                if($ext=='pdf' || $ext=='mp3' || $ext == $ext ){
                                        $pdf_id = $wpdb->get_col($wpdb->prepare("select ID from {$wpdb->prefix}posts where guid = %s AND post_type='attachment'",$acf_csv_name));
                                        if(!empty($pdf_id)) {
                                                $map_acf_csv_element=$pdf_id[0];
                                        }
                                        else {
                                                $map_acf_csv_element = ACFImport::$media_instance->media_handling($acf_csv_name, $post_id, $acf_wpname_element);
                                        }
                                }
                                else {
                                        $map_acf_csv_element = ACFImport::$media_instance->media_handling($acf_csv_name, $post_id, $acf_wpname_element);
                                }
                        }
                        else {
                                $map_acf_csv_element = ACFImport::$media_instance->media_handling($acf_csv_name, $post_id, $acf_wpname_element);
                        }
                        $map_acf_wp_element = $acf_wp_name;
                }
        }

        if ($get_import_type == 'user') {
   
                update_user_meta($post_id, $map_acf_wp_element, $map_acf_csv_element);
                update_user_meta($post_id, '_' . $map_acf_wp_element, $key);
        } else {
                
                update_post_meta($post_id, $map_acf_wp_element, $map_acf_csv_element);
                update_post_meta($post_id, '_' . $map_acf_wp_element, $key);
        }
       
        if ($get_import_type == 'term') {
                if($term_meta = 'yes'){
                        $get_meta_info = $wpdb->get_results($wpdb->prepare("select meta_key,meta_value from {$wpdb->prefix}termmeta where term_id=%d and meta_key=%s" , $post_id , $map_acf_wp_element ), ARRAY_A);
                        if( !empty($get_meta_info)){  
                                update_term_meta($post_id, $map_acf_wp_element, $map_acf_csv_element);
                                update_term_meta($post_id, '_' . $map_acf_wp_element, $key);
                        }
                        else{
                                add_term_meta($post_id, $map_acf_wp_element, $map_acf_csv_element);
                                add_term_meta($post_id, '_' . $map_acf_wp_element, $key);
                        }
                }else{
                        $option_name = $importAs . "_" . $post_id . "_" . $map_acf_wp_element;
                        $option_value = $map_acf_csv_element;
                        if (is_array($option_value)) {
                                $option_value = serialize($option_value);
                        }

                        update_option("$option_name", "$option_value");
                }
        }
    }
}