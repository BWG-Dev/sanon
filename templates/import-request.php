<?php
if(isset($_POST['import_meeting'])){
	$file_name = sanitize_file_name($_FILES['file_import']['name']);
	$file_tmp = sanitize_text_field($_FILES['file_import']['tmp_name']);
	move_uploaded_file($file_tmp, get_stylesheet_directory() . '/uploads/' . $file_name );


	if (($handle = fopen(get_stylesheet_directory() . '/uploads/' . $file_name, "r")) !== FALSE) {
		$cont=0;
		$current_primary_account = 0;

		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

			if($cont === 0){ $cont++; continue; }
			if('' != trim($data[1]) && '' != trim($data[2])){
				$updated_date = trim($data[0]);
				$group_id = $data[1];
				$name =  $data[2];
				$meeting_type = trim($data[3]);
				$day = $data[4];
				$time = $data[5];
				$end_time = $data[6];
				$timezone = $data[7];
				$address = $data[8];
				$city = $data[9];
				$state = $data[10];
				$zip = $data[11];
				$country = trim($data[12]);
				$meeting_link = $data[13];
				$type_listing = trim($data[14]);
				$instructions = $data[15];
				$email = $data[16];
				$phone = $data[17];
				$website = $data[18];
				$language = $data[19];
				$special = $data[20];

				$array_post = array(
					"post_type" => 'meetings',
					"post_title" => wp_strip_all_tags( $name ),
					'post_status'  => 'publish',
					'post_content'  => '',
					'post_author'  => 12,
					'post_date' => date("Y-m-d H:i:s", strtotime($updated_date)),
					'post_modified' => date("Y-m-d H:i:s", strtotime($updated_date))
				);

				$post_id = wp_insert_post( $array_post, true );


				if(!is_wp_error($post_id)){
					add_post_meta($post_id, 'meeting_last_update', $updated_date);
					add_post_meta($post_id, 'group_id', $group_id);
					add_post_meta($post_id, 'day', $day);
					add_post_meta($post_id, 'start_time', $time ? date('H:i:s', strtotime($time)) : date('H:i:s'));
					add_post_meta($post_id, 'end_time', $time ? date('H:i:s', strtotime($end_time)) : date('H:i:s'));
					add_post_meta($post_id, 'timezone', map_timezone($timezone));
					add_post_meta($post_id, 'address', $address);
					add_post_meta($post_id, 'state', $state);
					add_post_meta($post_id, 'zip', $zip);
					add_post_meta($post_id, 'city', $city);
					add_post_meta($post_id, 'country', $country);
					add_post_meta($post_id, 'meeting_link', $meeting_link);
					add_post_meta($post_id, 'additional_information', $instructions);
					add_post_meta($post_id, 'email', $email);
					add_post_meta($post_id, 'phone', $phone);
					add_post_meta($post_id, 'website', $website);
					add_post_meta($post_id, 'language', $language);
					add_post_meta($post_id, 'special_focus', strtolower($special));

					if($country == 'USA & Canada'){
						update_post_meta($post_id, '_meeting_order', 1);
					}else{
						update_post_meta($post_id, '_meeting_order', 10);
					}


					if($type_listing){
						if(strtolower($type_listing) == 'hybrid' ){
							update_field('list_type', array('worldwide', 'geographic'), $post_id);
						}else{
							update_field('list_type', array($type_listing), $post_id);
						}
					}

					if($meeting_type){
						if(strtolower($meeting_type) == 'hybrid'){
							update_field('type',array('in_person', 'phone_web'), $post_id);
						}else if($meeting_type == 'In-Person'){
							update_field('type', array('in_person'), $post_id);
						}else{
							update_field('type', array('phone_web'), $post_id);
						}
					}

					if($country && $term = get_term_by('name', $country, 'area')){
						wp_set_post_terms( $post_id, array($term->term_id), 'area' );
					}


					echo "SUCCESS: Meeting on index " . $cont . " was imported successfully" . '<br>';
				}else{
					echo "ERROR: Meeting on index " . $cont . " was not imported, required info was not found, error serialized => " . serialize($post_id) . '<br>';
				}

			}else{
				echo "ERROR: Meeting on index " . $cont . " was not imported, required info was not found" . '<br>';
			}

			$cont++;
		}
		fclose($handle);
	}else{
		echo "There was an error in your file.";
	}

	echo "<strong> Import Finished!</strong>" . '<br>';

}

function map_timezone($tz){
	$time_zones = array(
		"Europe/Paris" => "CET Central European Time",
		"Africa/Cairo" => "EET Eastern European Time",
		"Indian/Mayotte" => "EAT Eastern African Time",
		"Asia/Karachi" => "PLT Pakistan Lahore Time",
		"Asia/Calcutta" => "IST India Standard Time",
		"Asia/Dhaka" => "BST Bangladesh Standard Time",
		"Asia/Taipei" => "CTT China Taiwan Time",
		"Asia/Tokyo" =>"JST Japan Standard Time",
		"Australia/South" =>"ACT Australia Central Time",
		"Australia/Tasmania" =>"AET Australia Eastern Time",
		"Antarctica/South_Pole" => "NST New Zealand Standard Time",
		"America/Atka" => "HST Hawaii Standard Time",
		"America/Anchorage" => "AST Alaska Standard Time",
		"America/Los_Angeles" =>"PST Pacific Standard Time",
		"America/Phoenix" =>"MST Mountain Standard Time",
		"Mexico/General" =>"CST Central Standard Time",
		"America/Toronto" => "EST Eastern Standard Time",
		"America/Buenos_Aires" =>"AGT Argentina Standard Time",
		"Brazil/East" =>"BET Brazil Eastern Time",
		"Africa/Maputo" =>"CAT Central African Time",
		"Asia/Jerusalem" => "Israel Standard Time",
		"Canada/Atlantic" => "AST Atlantic Standard Time",
		"Europe/London" => "GMT Greenwich Mean Time",
		"Europe/Helsinki" => "GMT+2 Greenwich Mean Time",
		"Asia/Baghdad" => "GMT+3 Greenwich Mean Time",
		"Asia/Singapore" => "GMT+8 Greenwich Mean Time",


	);

	return array_search($tz, $time_zones) ?: 'UTC';
}

?>
