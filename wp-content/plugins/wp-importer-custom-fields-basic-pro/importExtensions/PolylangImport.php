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

class PolylangImport {
	private static $polylang_instance = null;

	public static function getInstance() {

		if (PolylangImport::$polylang_instance == null) {
			PolylangImport::$polylang_instance = new PolylangImport;
			return PolylangImport::$polylang_instance;
		}
		return PolylangImport::$polylang_instance;
	}
	function setPolylangValues($header_array ,$value_array ,$map,$post_id ,$type, $get_mode){
		$post_values = [];
		$helpers_instance = ImportHelpers::getInstance();	
		$post_values = $helpers_instance->get_header_values($map , $header_array , $value_array);

		$this->polylangImportFunction($post_values,$type, $post_id,$get_mode);	
	}

	function polylangImportFunction($data_array,$importas,$pId,$mode) {
		global $wpdb;
		$term_id = $checkid = "";
		$code = trim($data_array['language_code']);				 
		$language = $wpdb->get_results("select term_id, term_taxonomy_id, description from {$wpdb->prefix}term_taxonomy where taxonomy ='language'");				
		$language_id = $wpdb->get_results($wpdb->prepare("select term_taxonomy_id from {$wpdb->prefix}term_relationships WHERE object_id = %d",$pId));

		foreach($language_id as $key => $lang_ids){
			$taxonomy = $wpdb->get_results("select taxonomy from {$wpdb->prefix}term_taxonomy where term_taxonomy_id ='$lang_ids->term_taxonomy_id'");
			if(!empty($taxonomy)) {
				$language_name=$taxonomy[0];
				$lang_name=$language_name->taxonomy;				
			}
			else {
				$lang_name = "";
			}
			if($lang_name == 'language'){					
				$wpdb->get_results("DELETE FROM {$wpdb->prefix}term_relationships WHERE object_id = '$pId' and term_taxonomy_id = '$lang_ids->term_taxonomy_id'");
			}
		}
	
		foreach($language as $langkey => $langval){
			$description = unserialize($langval->description);
			$descript = explode('_',$description['locale']);
			$languages = $descript[0];
			if($languages == $code){
				//$term_id = $langval->term_id;
				$term_id = $langval->term_taxonomy_id;
			}
		}
	
		if (!empty($term_id)) {
			$exists = $wpdb->get_var($wpdb->prepare(
				"SELECT COUNT(*) FROM {$wpdb->prefix}term_relationships WHERE term_taxonomy_id = %d AND object_id = %d",
				$term_id, $pId
			));
		
			if (!$exists) {
				$wpdb->insert(
					$wpdb->prefix . 'term_relationships',
					array(
						'term_taxonomy_id' => $term_id,
						'object_id' => $pId
					),
					array(
						'%d',
						'%d'
					)
				);
			}
		}	
		//$get_term = $wpdb->get_results($wpdb->prepare("select term_id from {$wpdb->prefix}terms where slug like %s ",'%-'.$code));			 			 
		$get_term = $wpdb->get_results($wpdb->prepare("select term_id from {$wpdb->prefix}terms where slug like %s ",$code));			 			 
	
		foreach($get_term as $keys =>$values){
			//$id = $values->term_id;

			$code_id = $values->term_id;
			$id = $wpdb->get_var("SELECT term_taxonomy_id FROM {$wpdb->prefix}term_taxonomy WHERE term_id = $code_id");
			$wpdb->insert($wpdb->prefix.'term_relationships',array(
					'term_taxonomy_id'  => $id,
					'object_id'  => $pId
				),
				array(
					'%s',
					'%s'
				) 
			);
		}					
		
		if($data_array['language_code']){			
			
			$translatepost=isset($data_array['translated_post_title']) ? $data_array['translated_post_title'] : "";			
			$child=$wpdb->get_results("SELECT ID FROM {$wpdb->prefix}posts WHERE post_title ='$translatepost' AND post_status='publish' ORDER BY ID ASC");
			if($mode == 'Insert'){
			
                $result_of_check = $wpdb->get_results("SELECT description, term_id, term_taxonomy_id FROM {$wpdb->prefix}term_taxonomy WHERE taxonomy='post_translations' ");
				$array = json_decode(json_encode($result_of_check),true);
				$trans_post_id = !empty($child) ? $child[0]->ID : "";
				
                $languageid = $wpdb->get_results("SELECT term_id FROM {$wpdb->prefix}terms WHERE slug= '$code' ");
				$lang_id =!empty($languageid) ? $languageid[0]->term_id : "";
			
				if(!empty($lang_id))	{
					$langcount = $wpdb->get_results("SELECT count FROM {$wpdb->prefix}term_taxonomy WHERE term_id='$lang_id'");
					$termcount=$langcount[0]->count;
					$termcount = $termcount+1;
					$wpdb->update( $wpdb->term_taxonomy , array( 'count' => $termcount  ) , array( 'term_id' => $lang_id ) );
				}
			
				foreach($array as $res_key => $res_val){
					$get_term_id = $array[$res_key]['term_id'];
					$get_termtaxo_id = $array[$res_key]['term_taxonomy_id'];
					$description = unserialize($array[$res_key]['description']);
					$values = is_array($description)? array_values($description): array(); 
					if(is_array($values) && !empty($values)){  
						if (!empty($trans_post_id) && in_array($trans_post_id,$values)) {
							//$checkid = $get_term_id;
							$checkid = $get_termtaxo_id;
							$check_term_id = $get_term_id;
						}
					}
				}   
			
				if($checkid){
					$language = $wpdb->get_results("SELECT term_id,description FROM {$wpdb->prefix}term_taxonomy WHERE taxonomy = 'language'");
					if (!empty($checkid) && !empty($pId)) {
						$table_name = $wpdb->prefix . 'term_relationships';
					
						$exists = $wpdb->get_var(
							$wpdb->prepare(
								"SELECT COUNT(*) FROM $table_name WHERE term_taxonomy_id = %s AND object_id = %s",
								$checkid, $pId
							)
						);
					
						if (!$exists) {
							$wpdb->insert(
								$table_name,
								array(
									'term_taxonomy_id' => $checkid,
									'object_id' => $pId
								),
								array(
									'%s',
									'%s'
								)
							);
						}
					}
					
					 													
					// $result = $wpdb->get_results("select description from {$wpdb->prefix}term_taxonomy where term_id = '$checkid' ");
					$result = $wpdb->get_results("select description from {$wpdb->prefix}term_taxonomy where term_id = '$check_term_id' ");			
					$description = unserialize($result[0]->description);					
				
					foreach($description as $desckey =>$descval){  

						//insert with update 
						$array2= array($code => $pId);
						$descript=array_merge($description,$array2);
						$count = count($descript);
						$description_data = serialize($descript);
						// $wpdb->update( $wpdb->term_taxonomy , array( 'description' => $description_data  ) , array( 'term_id' => $checkid ) );
						// $wpdb->update( $wpdb->term_taxonomy , array( 'count' => $count  ) , array( 'term_id' => $checkid ) );

						$wpdb->update( $wpdb->term_taxonomy , array( 'description' => $description_data  ) , array( 'term_id' => $check_term_id ) );
						$wpdb->update( $wpdb->term_taxonomy , array( 'count' => $count  ) , array( 'term_id' => $check_term_id ) );
				    }
				}
				else{
					if(!empty($translatepost)){	
					
						$term_name = uniqid('pll_');
						$table_name = $wpdb->prefix . 'term_relationships';
						$terms = wp_insert_term($term_name,'post_translations');
						$term_id = $terms['term_id'];
						$term_tax_id = $terms['term_taxonomy_id'];
					
						$wpdb->insert(
							$table_name,
							array(
								'term_taxonomy_id' => $term_tax_id,
								'object_id' => $trans_post_id
							),
							array(
								'%s',
								'%s'
							)
						);
						$language = $wpdb->get_results("SELECT term_id,description FROM {$wpdb->prefix}term_taxonomy WHERE taxonomy ='language'");
					
						if (!empty($term_tax_id) && !empty($pId)) {
							$table_name = $wpdb->prefix . 'term_relationships';
						
							$exists = $wpdb->get_var(
								$wpdb->prepare(
									"SELECT COUNT(*) FROM $table_name WHERE term_taxonomy_id = %s AND object_id = %s",
									$term_tax_id, $pId
								)
							);
						
							if (!$exists) {
								$wpdb->insert(
									$table_name,
									array(
										'term_taxonomy_id' => $term_tax_id,
										'object_id' => $pId
									),
									array(
										'%s',
										'%s'
									)
								);
							}
						}
						
											
						$taxonomyid = $wpdb->get_results($wpdb->prepare("select term_taxonomy_id from {$wpdb->prefix}term_relationships where object_id = %d",$trans_post_id));
						foreach($taxonomyid as $key => $taxo_id){
							$tid = $taxo_id->term_taxonomy_id;						
							$get_details = $wpdb->get_results($wpdb->prepare("select description,taxonomy from {$wpdb->prefix}term_taxonomy where term_taxonomy_id = %d",$tid));
							
							if(isset($get_details[0]->taxonomy) && $get_details[0]->taxonomy == 'language'){														
								$description=unserialize($get_details[0]->description);
								$descript=explode('_',$description['locale']);
								$language = array_key_exists(0,$descript) ? $descript[0] : "";
								if(!empty($language)) {
								$array = array($language => $trans_post_id);
								$post_description=array_merge($array,array($code => $pId));
								$count=count($post_description);
								$description_data=serialize($post_description);
								$wpdb->update( $wpdb->term_taxonomy , array( 'description' => $description_data  ) , array( 'term_id' => $term_id ) );
								$wpdb->update( $wpdb->term_taxonomy , array( 'count' => $count  ) , array( 'term_id' => $term_id ) );
								}
							}
						}
					}
				}				
			}
			else{

                $result_of_check = $wpdb->get_results("SELECT description,term_id, term_taxonomy_id FROM {$wpdb->prefix}term_taxonomy WHERE taxonomy='post_translations' ");
			
				$array=json_decode(json_encode($result_of_check),true);
				$trans_post_id = !empty($child) ? $child[0]->ID : "";

				foreach($array as $res_key => $res_val){
					$get_term_id = $array[$res_key]['term_id'];
					$get_taxo_id = $array[$res_key]['term_taxonomy_id'];
					$description = unserialize($array[$res_key]['description']);
					$values = is_array($description)? array_values($description): array(); 
					if(is_array($values) && !empty($values)){  
						if (!empty($trans_post_id) && in_array($trans_post_id,$values)) {
							$check_termid = $get_term_id;
							$checkid = $get_taxo_id;
						}
					}
				}   
				if($checkid){
				
					$language=$wpdb->get_results("SELECT term_id,description FROM {$wpdb->prefix}term_taxonomy WHERE taxonomy ='language'");
		
					if (!empty($checkid) && !empty($pId)) {
						$table_name = $wpdb->prefix . 'term_relationships';
					
						$exists = $wpdb->get_var(
							$wpdb->prepare(
								"SELECT COUNT(*) FROM $table_name WHERE term_taxonomy_id = %s AND object_id = %s",
								$checkid, $pId
							)
						);
					
						if (!$exists) {
							$wpdb->insert(
								$table_name,
								array(
									'term_taxonomy_id' => $checkid,
									'object_id' => $pId
								),
								array(
									'%s',
									'%s'
								)
							);
						}
					}
						
					
					// $result=$wpdb->get_results("select description from {$wpdb->prefix}term_taxonomy where term_id ='$checkid'");
					$result=$wpdb->get_results("select description from {$wpdb->prefix}term_taxonomy where term_id ='$check_termid'");					
					$description=unserialize($result[0]->description);					
					foreach($description as $desckey =>$descval){  

						//insert with update 
						$array2= array($code => $pId);
						$descript=array_merge($description,$array2);
						$count = count($descript);
						$description_data = serialize($descript);
						// $wpdb->update( $wpdb->term_taxonomy , array( 'description' => $description_data  ) , array( 'term_id' => $checkid ) );
						// $wpdb->update( $wpdb->term_taxonomy , array( 'count' => $count  ) , array( 'term_id' => $checkid ) );

						$wpdb->update( $wpdb->term_taxonomy , array( 'description' => $description_data  ) , array( 'term_id' => $check_termid ) );
						$wpdb->update( $wpdb->term_taxonomy , array( 'count' => $count  ) , array( 'term_id' => $check_termid ) );
				    }
				}
				else{
					if(!empty($translatepost)){	
						$term_name = uniqid('pll_');
						$terms = wp_insert_term($term_name,'post_translations');
						$term_id = $terms['term_id'];
						$term_tax_id = $terms['term_taxonomy_id'];
						$language = $wpdb->get_results("SELECT term_id,description FROM {$wpdb->prefix}term_taxonomy WHERE taxonomy ='language'");
						$table_name = $wpdb->prefix . 'term_relationships';
						$wpdb->insert(
							$table_name,
							array(
								'term_taxonomy_id' => $term_tax_id,
								'object_id' => $trans_post_id
							),
							array(
								'%s',
								'%s'
							)
						);
						if (!empty($term_tax_id) && !empty($pId)) {
							$table_name = $wpdb->prefix . 'term_relationships';
						
							$exists = $wpdb->get_var(
								$wpdb->prepare(
									"SELECT COUNT(*) FROM $table_name WHERE term_taxonomy_id = %s AND object_id = %s",
									$term_tax_id, $pId
								)
							);
						
							if (!$exists) {
								$wpdb->insert(
									$table_name,
									array(
										'term_taxonomy_id' => $term_tax_id,
										'object_id' => $pId
									),
									array(
										'%s',
										'%s'
									)
								);
							}
						}
						
						
						$taxonomyid=$wpdb->get_results("SELECT term_taxonomy_id FROM {$wpdb->prefix}term_relationships WHERE object_id ='$trans_post_id'");
						foreach($taxonomyid as $res1key => $resval){
							$resval1=$resval->term_taxonomy_id;
							$taxonomy=$wpdb->get_results("SELECT taxonomy FROM {$wpdb->prefix}term_taxonomy WHERE term_taxonomy_id ='$resval1'");
							if($taxonomy[0]->taxonomy == 'language'){
								$taxid =$resval1;
							
								$desc=$wpdb->get_results("SELECT description FROM {$wpdb->prefix}term_taxonomy WHERE term_taxonomy_id ='$taxid'");
								$description=unserialize($desc[0]->description);
								$descript=explode('_',$description['locale']);
								$language=$descript[0];
								$array=array($language => $trans_post_id);
								$post_trans=array_merge($array,array($code => $pId));
								$count =count($post_trans);
								$ser=serialize($post_trans);
								$wpdb->update( $wpdb->term_taxonomy , array( 'description' => $ser  ) , array( 'term_id' => $term_id ) );
								$wpdb->update( $wpdb->term_taxonomy , array( 'count' => $count  ) , array( 'term_id' => $term_id ) );
							}
						}
					}
				}
			}
		}
	}
}