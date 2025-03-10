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

class LogManager {

    private static $instance = null;
    private static $smack_csv_instance = null;
    // declare log file and file pointer as private properties
	private $log_file, $fp;
	
	public function __construct(){
		add_action('wp_ajax_display_log',array($this,'display_log'));
		add_action('wp_ajax_download_log',array($this,'download_log'));
		add_action('wp_ajax_delete_log',array($this,'delete_log'));
    }

    public static function getInstance() {
		if (LogManager::$instance == null) {
			LogManager::$instance = new LogManager;
            LogManager::$smack_csv_instance = SmackCSV::getInstance();
			return LogManager::$instance;
		}
		return LogManager::$instance;
    }

    public function lfile($path) {
		$this->log_file = $path;
    }
    
	// write message to the log file
	public function lwrite($message, $timestamp = true) {
		$message = $message;
		// if file pointer doesn't exist, then open log file
		if (!is_resource($this->fp)) {
			$this->lopen();
		}
		// define script name
		$script_name = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
		// define current time and suppress E_WARNING if using the system TZ settings
		// (don't forget to set the INI setting date.timezone)
		$time = '';
		if($timestamp == true) {
			$time = @date( '[Y-m-d H:i:s]' );
		}
		// write current time, script name and message to the log file
		fwrite($this->fp, "$time $message" . PHP_EOL);
    }
    
    // close log file (it's always a good idea to close a file when you're done with it)
	public function lclose() {
		fclose($this->fp);
	}
	// open log file (private method)
	private function lopen() {
		// in case of Windows set default log file
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
			$log_file_default = 'c:/php/logfile.txt';
		}
		// set default log file for Linux and other systems
		else {
			$log_file_default = '/tmp/logfile.txt';
		}
		// define log file from lfile method or use previously set default
		$lfile = $this->log_file ? $this->log_file : $log_file_default;
		// open log file for writing only and place file pointer at the end of the file
		// (if the file does not exist, try to create it)
		$this->fp = fopen($lfile, 'a') or exit("Can't open $lfile!");
	}


	/**
	 * Writes event log in log file.
	 * @param  string $hash_key - file hash key
     * @param  string $original_file_name - file name
	 * @param  string $fileType - file extension
	 * @param  string $mode - file mode (import or update)
	 * @param  int    $totalCount - Total number of records
	 * @param  string $importType - Post type
	 * @param  string $core_log - Event log
	 * @param  boolean $addHeader 
	 */
	public function get_event_log($hash_key , $original_file_name , $fileType , $mode , $totalCount , $importType , $core_log, $addHeader,$templatekey = null){
		$smack_instance = SmackCSV::getInstance();

		if($templatekey != null) {
			$upload_dir = $smack_instance->create_upload_dir('CLI');
			$eventLogFile = $upload_dir.$hash_key.'/'.$templatekey.'/'.$templatekey . '.html';
		}
		else {
			$upload_dir = $smack_instance->create_upload_dir();
			$eventLogFile = $upload_dir.$hash_key.'/'.$hash_key.'.html';
		}
		$limit = 1;

		$this->lfile("$eventLogFile");

		if ($addHeader) {
			$this->lwrite(__("File has been used for this event: ") . $original_file_name . '<br/>', false);
			$this->lwrite(__("Type of the imported file: ") . $fileType . '<br/>', false);
			$this->lwrite(__("Mode of event: ") . $mode . '<br/>', false);
			$this->lwrite(__("Total no of records: ") . $totalCount. '<br/>', false);
			$this->lwrite(__("Rows handled on each iterations (Based on your server configuration): ") . $limit. '<br/>', false);
			$this->lwrite(__("File used to import data into: ") . $importType . '<br/>', false);
		}

		if (is_array($core_log)){
			foreach ($core_log as $lkey => $lvalue) {
				$verify_link = '';
				$eventLog = '';
				foreach ($lvalue as $lindex => $lresult) {
					if($lindex != 'VERIFY')
						$eventLog .= $lindex . ':' . $lresult;
					else
						$verify_link = '<tr><td><p>' . $lresult . ' </td><p></tr>';
				}
				$eventLog .= $verify_link;
				$this->lwrite($eventLog);
			}	
		}

	}


	/**
	 * Retrieves and display the file events history.
	 */
	public function display_log(){
		check_ajax_referer('smack-importer-custom-fields-basic-pro', 'securekey');
		global $wpdb;
		$response = [];
		$logInfo = [];
		$value = [];
		
		$logInformation = $wpdb->get_results("select * from ultimate_cfimporter_pro_smackuci_events where deletelog = 0 order by id desc ");
		if(empty($logInformation)){
			$response['success'] = false;
			$response['message'] = "No logs Found";
		}else{
			foreach($logInformation as $logIndex => $logValue){

				$file_name = $logValue->original_file_name;
				$revision = $logValue->revision;
				$module = $logValue->import_type;
				$inserted = $logValue->created;
				$updated = $logValue->updated;
				$skipped = $logValue->skipped;
	
				$logInfo['filename'] = $file_name;
				$logInfo['revision'] = $revision;
				$logInfo['module'] = $module;
				$logInfo['inserted'] = $inserted ;
				$logInfo['updated'] = $updated;
				$logInfo['skipped'] = $skipped;
	
				array_push($value , $logInfo);
			}
			$response['success'] = true;
			$response['info'] = $value;
		}	
		echo wp_json_encode($response);
		wp_die();
	}

	/**
	 * Delete the Logs
	 */
	public function delete_log(){
		check_ajax_referer('smack-importer-custom-fields-basic-pro', 'securekey');
		global $wpdb;
		$filename = sanitize_text_field($_POST['filename']);
		$revision = sanitize_text_field($_POST['revision']);
		$upload_path = LogManager::$smack_csv_instance->create_upload_dir();
		$get_details = $wpdb->get_results($wpdb->prepare("select id,eventKey from ultimate_cfimporter_pro_smackuci_events where revision = %d and original_file_name = %s", $revision, $filename));		
		if(	!empty( $get_details)) {
			foreach($get_details as $records){
				$logPath = $upload_path . $records->eventKey . '/' . $records->eventKey . '.html';
				if(file_exists($logPath))
				{
					array_map('unlink', glob("$logPath"));
				}
				else {
					$response['message'] = "File not available.";
					echo wp_json_encode($response);
					wp_die();
				}
				$wpdb->update('ultimate_cfimporter_pro_smackuci_events',array('deletelog' => true),array('id' => $records->id));
			}
		}
		$response['message'] = "Deleted Successfully";
		echo wp_json_encode($response);
		wp_die();
	}


	/**
	 * Downloads file event log.
	 */
	public function download_log(){
		check_ajax_referer('smack-importer-custom-fields-basic-pro', 'securekey');
		global $wpdb;       
        $response = [];
        $filename = sanitize_text_field($_POST['filename']);
        $revision = sanitize_text_field($_POST['revision']);
        $upload = wp_upload_dir();
        $upload_dir = $upload['baseurl'];
        $upload_url = $upload_dir . '/smack_uci_uploads/imports/';        
        $upload_path = LogManager::$smack_csv_instance->create_upload_dir();
		$get_event_key = $wpdb->get_results($wpdb->prepare("select eventKey from ultimate_cfimporter_pro_smackuci_events where revision = %d and original_file_name = %s", $revision , $filename));
		if(empty($get_event_key)) {
			$response['success'] = false;
            $response['message'] = 'Log not exists';
		}
		else {
			$logPath = $upload_path .$get_event_key[0]->eventKey .'/'.$get_event_key[0]->eventKey. '.html';
			
			if (file_exists($logPath)) :
				$loglink = $upload_url .$get_event_key[0]->eventKey .'/'.$get_event_key[0]->eventKey. '.html';
				$response['success'] = true;
				$response['log_link'] = $loglink;
				
			else :
				$response['success'] = false;
				$response['message'] = 'Log not exists';
				
			endif;
		}    
    echo wp_json_encode($response); 
    wp_die();
	}
}
