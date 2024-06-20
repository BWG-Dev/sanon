<?php

/**
 * Areas for meetings post type
 */

new SanonMeetingAreas;

class SanonMeetingAreas
{
	/**
	 * Init
	 */
	public function __construct()
	{
		add_action('init', [$this, 'registerTaxonomy']);
		add_action('wp_enqueue_scripts', [$this, 'registerAssets']);
		add_shortcode('meetings-listing', [$this, 'meetingsListingShortcode']);

		add_action( 'wp_ajax_meeting_areas_load_meetings', [$this, 'ajaxLoadMeetings'] );
		add_action( 'wp_ajax_nopriv_meeting_areas_load_meetings', [$this, 'ajaxLoadMeetings'] );

		add_action( 'wp_ajax_get_areas', [$this, 'getAreas'] );
		add_action( 'wp_ajax_nopriv_get_areas', [$this, 'getAreas'] );

		add_action( 'wp_ajax_get_areas_child', [$this, 'getAreasChild'] );
		add_action( 'wp_ajax_nopriv_get_areas_child', [$this, 'getAreasChild'] );

		add_action( 'wp_ajax_search_areas', [$this, 'search'] );
		add_action( 'wp_ajax_nopriv_search_areas', [$this, 'search'] );

		add_action( 'wp_ajax_get_meetings', [$this, 'get_meetings'] );
		add_action( 'wp_ajax_nopriv_get_meetings', [$this, 'get_meetings'] );

		add_filter( 'gform_form_tag', array($this, 'form_tag'), 10, 2 );

		add_action( 'save_post', array($this, 'add_meeting_order'), 10, 3 );
		add_filter( 'manage_edit-meetings_columns', function($columns) {

			$columns['last_updated'] = __( 'Last Updated' );
			$columns['group_id'] = __( 'ID' );
			$columns['state'] = __( 'State' );
			$columns['zip'] = __( 'Zip' );
			$columns['city'] = __( 'City' );
			$columns['country'] = __( 'Country' );
			unset($columns['date']);
			return $columns;
		},10,1 );

		add_action( 'manage_meetings_posts_custom_column' , function( $column, $post_id ) {
			switch ( $column ) {
				case 'group_id':
					echo get_post_meta($post_id,'group_id',true)."<br/>";

					break;
				case 'city':
					echo get_post_meta($post_id,'city',true);

					break;
				case 'state':
					echo get_post_meta($post_id,'state',true);
					break;
				case 'zip':
					echo get_post_meta($post_id,'zip',true);
					break;
				case 'country' :
					echo get_post_meta($post_id,'country',true);
					break;
				case 'last_updated' :
					echo get_the_modified_time( 'm/d/Y', $post_id);
					break;
			}
		}, 10, 2 );

		add_filter( 'manage_edit-meetings_sortable_columns', 'make_meeting_columns_sortable' );
		/**
		 * Makes the meetings columns sortable.
		 *
		 * @param array $columns An array of contact columns.
		 *
		 * @return array The modified array of contact columns.
		 */
		function make_meeting_columns_sortable( $columns ) {
			$columns['last_updated'] = 'last_updated';
			$columns['group_id']     = 'meeting_id';
			$columns['state']        = 'state';
			$columns['zip']          = 'zip';
			$columns['city']         = 'city';
			$columns['country']      = 'country';

			return $columns;
		}

		add_filter( 'pre_get_posts', 'make_meetings_meta_searchable', 10, 1 );
		/**
		 * Makes meetings meta searchable.
		 *
		 * @param WP_Query $query The query object.
		 *
		 * @return void
		 */
		function make_meetings_meta_searchable( $query ): void {
			if ( 'meetings' === $query->query_vars['post_type'] && is_admin() ) {

                if( $query->is_search ) {
					$result                 = $query->query_vars['s'];
					$query->query_vars['s'] = '';

                    //$query->set( 's', $result );
					$query->set( 'post_status', 'publish');
					$query->set( 'post_type', 'meetings' );
					$query->set( 'meta_query',
						array(
							'relation' => 'OR',
							array(
								'key'     => 'zip',
								'value'   => $result,
								'compare' => 'LIKE',
							),
							array(
								'key'     => 'state',
								'value'   => $result,
								'compare' => '=',
							),
							array(
								'key'     => 'group_id',
								'value'   => $result,
								'compare' => 'LIKE',
							),
							array(
								'key'     => 'country',
								'value'   => $result,
								'compare' => 'LIKE',
							),
							array(
								'key'     => 'city',
								'value'   => $result,
								'compare' => '=',
							),
						)
					);
                }

				$orderby = $query->get( 'orderby');

                if( 'last_updated' === $orderby ) {
					$query->set( 'orderby', 'modified' );
                }

			}

		}

		add_filter( 'posts_where', function ( $where, \WP_Query $q )
		{
			$search = isset($_GET['s']) ? $_GET['s'] : '';
			if('meetings' === $q->query_vars['post_type'] && is_admin() && $q->is_search() && ! empty($search)) // No global $wp_query here
			{
                //var_dump($search);exit;
                $where  .=  " OR (post_title LIKE '%$search%' AND post_type = 'meetings')";
			//	var_dump($where);exit;
			}

			return $where;

		}, 10, 2 );

		add_action( 'post_submitbox_misc_actions', function($post_id){
			echo "<strong style='padding: 10px'><span class='label'>Last Update: </span></strong>" . get_the_modified_time( 'm/d/Y', $post_id) . "";
		}, 10, 1);
	}

	/**
	 * Add the order meeting meta
	 */
	function add_meeting_order($post_ID, $post, $update){
		if($post->post_type == 'meetings'){
			$terms = wp_get_post_terms($post_ID, 'area', array('fields' => 'ids'));
			if($terms[0] == 289){
				update_post_meta($post_ID, '_meeting_order', 1);
			}else{
				update_post_meta($post_ID, '_meeting_order', 10);
			}
		}
	}

	/**
	 * Updating the action attr for the report concern form id:6
	 */
	function form_tag($form_tag, $form){
		if ( $form['id'] != 6 ) {
			// Not the form whose tag you want to change, return the unchanged tag.
			return $form_tag;
		}

		$form_tag = preg_replace( "|action='(.*?)'|", "action=''", $form_tag );

		return $form_tag;
	}

	/**
	 * Register areas taxonomy
	 */
	public function registerTaxonomy()
	{
		// placement taxonomy for meetings
		register_taxonomy('area', ['meetings'], [
			'labels' => [
				'name' => 'Areas',
				'singular_name' => 'Area',
				'search_items' =>  'Search Areas',
				'all_items' => 'All Areas',
				'parent_item' => 'Parent Area',
				'parent_item_colon' => 'Parent Area:',
				'edit_item' => 'Edit Area',
				'update_item' => 'Update Area',
				'add_new_item' => 'Add New Area',
				'new_item_name' => 'New Area Name',
				'menu_name' => 'Areas',
			],
			'hierarchical' => true,
			'show_ui' => true,
			'show_admin_column' => true,
			'publicly_queryable' => false,
			'query_var' => false
		]);
	}

	/**
	 * Register scripts and styles for this functionality so they can be enqueued on shortcode call
	 */
	public function registerAssets()
	{
		gravity_form_enqueue_scripts(6, true);
		wp_enqueue_script('google-map-js', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyDLuSE8QLWitaQzzN0u_yeRWzrD3WVy4Qk&v=weekly&libraries=places',array(),'1.0', true);

		wp_enqueue_script( 'sweet-js', 'https://unpkg.com/sweetalert/dist/sweetalert.min.js');
		wp_enqueue_script( 'map-logic-js', get_template_directory_uri() . '/js/map.js', array('jquery', 'sweet-js'), 'v-1.0' , true );

		wp_register_style( 'meeting-areas', get_stylesheet_directory_uri() . '/inc/meeting-areas/meeting-areas.css', [], '1.0.2', 'all' );

		wp_localize_script( 'map-logic-js', 'parameters', [
			'ajax_url' => admin_url( 'admin-ajax.php' ),
		] );
	}

	/**
	 * Enqueue assets into page
	 */
	protected function enqueueAssets() {
		wp_enqueue_style( 'meeting-areas' );
		wp_enqueue_style( 'autocomplete-css' );
		wp_enqueue_script( 'meeting-areas' );
		wp_enqueue_script( 'autocomplete-js' );
	}

	/**
	 * Listing meetings template
	 */
	public function meetingsListingShortcode() {
		// enqueue scripts and styles
		$this->enqueueAssets();

		$countries = $this->getHierarchicalAreas();
		$specials = get_field_object('field_6477f20e4979a', false);
		$timezones = get_field_object('field_63487d00d89f8', false);



		$ip     = $_SERVER['REMOTE_ADDR']; // means we got user's IP address
		$ipData = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
		// var_dump($ipData);
		$timezone_client = 'UTC';
		if ( isset( $ipData->timezone ) ) {
			$timezone_client = $ipData->timezone;
		} else {
			$timezone_client = $this->sa_get_timezone_from_ip( $ip );
		}
		// php_info();
		// var_dump(date_default_timezone_get());
		ob_start();?>

        <div id="meetings-listing">
            <div class="print-heading">
                <p class="print-heading-top" style="color: black; text-align: center;">List of Meetings</p>



                ..
            </div>
            <div class="meeting-mask">
                <p>Loading...</p>
            </div>

            <ul class="header">
                <div id="autocomplete" class="autocomplete meet-col">
                    <div class="user-location-col meet-col">
                        <input type="checkbox" id="user_location" style="display: none;">
                        <label for="user_location"><span class="user_location_btn"><i class="fa fa-compass" aria-hidden="true"></i></span></label>
                    </div>
                    <input id="meeting_input_search" data-term_id="" data-parent_id="" class="autocomplete-input filter-selection" placeholder="Enter City or Zip Code" aria-label="Enter City or Zip Code"><!--<i class="fa fa-search"></i>-->
                    <div class="autocomplete-results"></div>
                </div>

                <div class="meeting_type_wrapper meet-col">
                    <select class="filter-selection" name="meeting_type" id="meeting_type">
                        <option class="type_item" value="">All Types</option>
                        <option class="type_item" value="in_person">In Person</option>
                        <option class="type_item" value="phone_web">Phone / Web</option>
                    </select>
                </div>
                <div class="meeting_week_wrapper meet-col">
                    <select class="filter-selection" name="meeting_week" id="meeting_week">
                        <option class="week_item" value="">Any day</option>
                        <option class="week_item" value="Monday">Monday</option>
                        <option class="week_item" value="Tuesday">Tuesday</option>
                        <option class="week_item" value="Wednesday">Wednesday</option>
                        <option class="week_item" value="Thursday">Thursday</option>
                        <option class="week_item" value="Friday">Friday</option>u
                        <option class="week_item" value="Saturday">Saturday</option>
                        <option class="week_item" value="Sunday">Sunday</option>
                    </select>
                </div>

                <div class="meeting_week_wrapper meet-col">
                    <select class="filter-selection" name="meeting_time" id="meeting_time">
                        <option class="week_item" value="">Any Time</option>
                        <option class="week_item" value="next">Next Available</option>
                        <option class="week_item" value="am">AM</option>
                        <option class="week_item" value="pm">PM</option>
                    </select>
                </div>

                <div class="list-options meet-col">
                    <span class="list active"><i class="fa fa-list" aria-hidden="true"></i></span>
                    <span class="map"><i class="fa fa-map" aria-hidden="true"></i></span>
                </div>
            </ul>
            <div><span class="advanced_search hidden-zone"><i class="fa fa-caret-up" aria-hidden="true"></i> Advanced Search</span></div>

            <ul class="header-list" id="advanced_filters" style="display: none">
                <!--<div class="meeting_lang_wrapper meet-col">
					<select class="filter-selection" name="meeting_timezone" id="meeting_timezone">
						<option value="">Timezone</option>
						<?php /*foreach ($timezones['choices'] as $key => $value){ */?>
							<option value="<?php /*echo $key == 'any' ? '' : $key; */?>"><?php /*echo $value; */?></option>
						<?php /*} */?>
					</select>
				</div>-->
                <!-- <div class="user-city-state meet-col">
                    <input class="filter-selection" type="text" id="user_city_state" name="user_city_state" placeholder="State/Province">
                </div> -->
				<div class="user-city-state meet-col">
					<select class="state-select filter-selection" id="user_city_state" name="user_city_state">
						<option value="">State/Province</option>
						<option value="Alabama-AL">Alabama</option>
						<option value="Alaska-AK">Alaska</option>
						<option value="Arizona-AZ">Arizona</option>
						<option value="Arkansas-AR">Arkansas</option>
						<option value="California-CA">California</option>
						<option value="Colorado-CO">Colorado</option>
						<option value="Connecticut-CT">Connecticut</option>
						<option value="Delaware-DE">Delaware</option>
						<option value="Florida-FL">Florida</option>
						<option value="Georgia-GA">Georgia</option>
						<option value="Hawaii-HI">Hawaii</option>
						<option value="Idaho-ID">Idaho</option>
						<option value="Illinois-IL">Illinois</option>
						<option value="Indiana-IN">Indiana</option>
						<option value="Iowa-IA">Iowa</option>
						<option value="Kansas-KS">Kansas</option>
						<option value="Kentucky-KY">Kentucky</option>
						<option value="Louisiana-LA">Louisiana</option>
						<option value="Maine-ME">Maine</option>
						<option value="Maryland-MD">Maryland</option>
						<option value="Massachusetts-MA">Massachusetts</option>
						<option value="Michigan-MI">Michigan</option>
						<option value="Minnesota-MN">Minnesota</option>
						<option value="Mississippi-MS">Mississippi</option>
						<option value="Missouri-MO">Missouri</option>
						<option value="Montana-MT">Montana</option>
						<option value="Nebraska-NE">Nebraska</option>
						<option value="Nevada-NV">Nevada</option>
						<option value="New Hampshire-NH">New Hampshire</option>
						<option value="New Jersey-NJ">New Jersey</option>
						<option value="New Mexico-NM">New Mexico</option>
						<option value="New York-NY">New York</option>
						<option value="North Carolina-NC">North Carolina</option>
						<option value="North Dakota-ND">North Dakota</option>
						<option value="Ohio-OH">Ohio</option>
						<option value="Oklahoma-OK">Oklahoma</option>
						<option value="Oregon-OR">Oregon</option>
						<option value="Pennsylvania-PA">Pennsylvania</option>
						<option value="Rhode Island-RI">Rhode Island</option>
						<option value="South Carolina-SC">South Carolina</option>
						<option value="South Dakota-SD">South Dakota</option>
						<option value="Tennessee-TN">Tennessee</option>
						<option value="Texas-TX">Texas</option>
						<option value="Utah-UT">Utah</option>
						<option value="Vermont-VT">Vermont</option>
						<option value="Virginia-VA">Virginia</option>
						<option value="Washington-WA">Washington</option>
						<option value="West Virginia-WV">West Virginia</option>
						<option value="Wisconsin-WI">Wisconsin</option>
						<option value="Wyoming-WY">Wyoming</option>
						<option value="Alberta-AB">Alberta</option>
						<option value="British Columbia-BC">British Columbia</option>
						<option value="Manitoba-MB">Manitoba</option>
						<option value="New Brunswick-NB">New Brunswick</option>
						<option value="Newfoundland and Labrador-NL">Newfoundland and Labrador</option>
						<option value="Northwest Territories-NT">Northwest Territories</option>
						<option value="Nova Scotia-NS">Nova Scotia</option>
						<option value="Nunavut-NU">Nunavut</option>
						<option value="Ontario-ON">Ontario</option>
						<option value="Prince Edward Island-PE">Prince Edward Island</option>
						<option value="Quebec-QC">Quebec</option>
						<option value="Saskatchewan-SK">Saskatchewan</option>
						<option value="Yukon-YT">Yukon</option>
					</select>
				</div>
                <div class="meeting_lang_wrapper meet-col">
                    <select class="filter-selection" name="meeting_lang" id="meeting_lang">
                        <option class="lang_item" value="">All Languages</option>
                        <option class="lang_item" value="Finnish">Finnish</option>
                        <option class="lang_item" selected value="English">English</option>
                        <option class="lang_item" value="Spanish">Spanish</option>
                        <option class="lang_item" value="Portuguese">Portuguese</option>
                        <option class="lang_item" value="French">French</option>
                        <option class="lang_item" value="Italian">Italian</option>
                        <option class="lang_item" value="Russian">Russian</option>
                        <option class="lang_item" value="Polish">Polish</option>
                        <option class="lang_item" value="Slovak">Slovak</option>
                        <option class="lang_item" value="Dutch">Dutch</option>
                    </select>
                </div>

                <div class="area_meetings_wrapper meet-col">
                    <select class="filter-selection" id="area_meetings">
                        <option value="">All Countries</option>
                        <option value="289">USA & Canada</option>
						<?php foreach($countries as $k => $country): ?>
                            <option value="<?php echo $country->term_id; ?>"><?php echo $country->name; ?></option>
						<?php endforeach; ?>
                    </select>
                </div>

                <div class="area_meetings_wrapper meet-col">
                    <select class="filter-selection" id="meeting_special_focus">
						<?php foreach ($specials['choices'] as $key => $value){
							$posts = get_posts(array(
								'numberposts'   => 1,
								'post_type'     => 'meetings',
								'meta_key'      => 'special_focus',
								'meta_value'    => $key
							));

							if(count($posts)>0){
								?>
                                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
								<?php
							}

						} ?>
                    </select>
                </div>

            </ul>

            <p id="search_location_notice" style="display: none; text-align: center;margin-top: 20px;"><strong>Important: </strong>Meeting results are provided within 100 miles of the entered location</p>

            <br>

            <div class="meeting_action_wrapper meet-col">
                <div class="action-right">
                    <button class="" id="get_meeting_btn">APPLY</button>
                    <button class="print-btn" onclick="window.print()"><i class="fa fa-print"></i></button>
                    <button class="reset_btn" id="reset_filter_btn">RESET</button>
                </div>


            </div>

            <br>
            <div class="results-wrapper"></div>
            <div class="meeting_map">
                <div id="map_id_wrapper"></div>
                <div style="text-align: center" class="mask_map">Loading Map...</div>
            </div>
        </div>

		<?php return ob_get_clean();
	}

	/**
	 * Hierarchical sort of terms in taxonomy
	 */
	protected function sortTermsHierarchicaly(Array &$cats, Array &$into, $parentId = 0)
	{
		foreach ($cats as $i => $cat) {
			if ($cat->parent == $parentId) {
				$into[] = $cat;
				unset($cats[$i]);
			}
		}

		foreach ($into as $topCat) {
			$topCat->children = array();
			$this->sortTermsHierarchicaly($cats, $topCat->children, $topCat->term_id);
		}
	}

	/**
	 * Get areas hierarchical
	 */
	protected function getHierarchicalAreas() {
		// get areas and create hierarchy
		$areas = get_terms([
			'taxonomy' => 'area',
			'hide_empty' => true,
			'orderby'  => 'slug',
			'order'    => 'ASC',
			'parent' => 286
		]);

		return $areas;
	}

	/**
	 * Get Areas Ajax Call
	 */
	function getAreas(){

		$areas = $this->getHierarchicalAreas();

		echo json_encode(array('areas' => $areas));
		wp_die();
	}

	function search(){
		global $wpdb;

		$search = $_POST['search'];


		$areas = $wpdb->get_results(
			$wpdb->prepare("SELECT T.term_id, T.name, TT.parent FROM $wpdb->prefix" . "terms T
                INNER JOIN $wpdb->prefix" . "term_taxonomy TT ON (T.term_id = TT.term_taxonomy_id) WHERE TT.taxonomy = %s AND TT.parent > %d AND T.name LIKE %s ORDER BY T.name ASC", 'area' ,0, '%'.$search.'%')
		);

		echo json_encode(array('areas' => $areas));
		wp_die();
	}

	function getAreasChild( $output = 'ajax' ) {

		// get areas and create hierarchy
		$areas = get_terms(
                array(
                    'taxonomy'   => 'area',
                    'hide_empty' => 'true',
                    'orderby'    => 'name',
                    'parent'     => wp_doing_ajax() ? $_POST['id'] : 289,
                    'order'      => 'ASC'
				)
        );

		if ( ! wp_doing_ajax() ) {
			return $areas;
		}

		echo json_encode( array( 'areas' => $areas ) );
		wp_die();
	}

	/**
	 * Load Meetings and output them for ajax call
	 */
	public function ajaxLoadMeetings() {
		$search = isset( $_POST['search'] ) ? strtoupper( $_POST['search'] ) : '' ;
		$type = $_POST['type'] ?? 'phone_web';
		$weekday =  $_POST['weekday'];
		$lang =  $_POST['lang'] ?? 'English';

		$city_state =  $_POST['city_state'] ?? '';
		$city_state_parts = explode('-', $city_state);
		$city_state_full_name = $city_state_parts[0] ?? '';
		$city_state_abbreviation = $city_state_parts[1] ?? '';

		$time_am_pm =  $_POST['time'] ?? 'next';
		$special =  $_POST['special'] ?? '';
		//$timezone =  $_POST['timezone'] ?? '';
		$country =  $_POST['country'] ?? '289';
		$lat =  $_POST['lat'] ?? '';
		$lng =  $_POST['lng'] ?? '';
		$user_location =  $_POST['user_location'] ?? '';

		$meta_query = [];

		$meta_query[] = !empty($weekday) ? array('key' => 'day', 'value'   => $weekday, 'compare' => 'LIKE') : [];
		$meta_query[] = !empty($lang) ? array('key' => 'language', 'value'   => $lang, 'compare' => 'LIKE') : [];
		$meta_query[] = !empty($type) ? array('key' => 'type', 'value'  => $type, 'compare' => 'LIKE') : [];
		$meta_query[] = !empty($city_state) ? array('relation' => 'OR', array('key' => 'city', 'value'  => $city_state_full_name, 'compare' => '='), array('key' => 'state', 'value'  => $city_state_full_name, 'compare' => '='), array('key' => 'city', 'value'  => $city_state_abbreviation, 'compare' => '='), array('key' => 'state', 'value'  => $city_state_abbreviation, 'compare' => '=')) : [];
		//$meta_query[] = !empty($timezone) ? array('key' => 'timezone', 'value'  => $timezone, 'compare' => 'LIKE') : [];

		if($special !== '' && $special !== 'any'){

			$meta_query[] = array('key' => 'special_focus', 'value'  => $special, 'compare' => '=');
		}

		$query_array = array(
			'post_type' => 'meetings',
			'meta_query' => $meta_query,
			'posts_per_page' => empty($user_location) && empty($search) && empty($weekday) && empty($lang) && empty($type) ? 500 : 500,
			//   'fields' => 'ids',
			'nopaging' => false,
			'meta_key' => '_meeting_order',
			'orderby' => 'meta_value_num',
			'order' => 'ASC',

		);

		if(!empty($country)){
			$query_array['tax_query'][] =  [
				[
					'taxonomy' => 'area',
					'field' => 'term_id',
					'terms' => $country,
				],
			];
		}

		$the_query = new WP_Query( $query_array );
		$post_count = $the_query->found_posts;
		//var_dump($post_count);
		// prepare var to hold meetings output and cities
		$meetings_html = '';
		$cities = [];
		$special_meetings = [];
		$next_availables = array();
		$normal_meetings = array();

		$ip     = $_SERVER['REMOTE_ADDR']; // means we got user's IP address
		$ipData = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
		$timezone_client = 'UTC';

        //var_dump($ipData);

		if ( isset( $ipData->timezone ) ) {
			$timezone_client = $ipData->timezone;
		} else {
			$timezone_client = $this->sa_get_timezone_from_ip( $ip );
		}

       // var_dump($timezone_client);
        $cont = 0;
		$is_dst =  ! empty( $this->sa_is_dst( $ip ) );
       // var_dump($is_dst);
		while ( $the_query->have_posts() ) : $the_query->the_post();

			$product_id = get_the_ID();
			$meeting_type = get_field('type');
			$listing_type = get_field('list_type');
			$start_time = get_field('start_time');
			$state = get_field('state');
			$zip = get_field('zip');
			$time = get_field('start_time');
			$end_time = get_field('end_time');
			$phone = get_field('phone');
			$email = get_field('email');
			$calendar_link = get_field('calendar_link') ?? '';
			$day = get_field('day') ?? '';
			$meeting_calendar_day = '';
			$address = get_field('address');
			$city = get_field('city');
			$meeting_country = get_field('country');
			$meeting_link = get_field('meeting_link');
			$website = get_field('website');
			$additional_information = get_field('additional_information');
			$last_updated = get_the_modified_time( 'm/d/Y');
			$groupId = get_field('group_id');
			$facility = get_field('facility');
			$language = get_field('language');
			$phone_web = get_field('phone_web');

			//var_dump($product_id);
			//if(empty(get_post_meta($product_id, '_meeting_order', true))){
			$terms = wp_get_post_terms($product_id, 'area', array('fields' => 'ids'));
			if($terms[0] == 289){
				update_post_meta($product_id, '_meeting_order', 1);
			}else{
				update_post_meta($product_id, '_meeting_order', 10);
			}
			// }
			if($day){

				$today_date = new DateTime();
				$today_date->modify('next ' . strtolower($day));
				$meeting_calendar_day = $today_date->format('Y-m-d');

			}

			if($city) {
				$city_slug = sanitize_title_with_dashes( $city );
				$cities[$city_slug] = $city;
			} else {
				$city_slug = 'other';
			}

			//Visibility rules
			/*if(empty($country)){
				if(count( ) != 1 && count($listing_type) != 2){
					continue;
				}
				if(in_array('in_person', $meeting_type) && count($meeting_type) == 1 && in_array('worldwide', $listing_type) && count($listing_type) == 1){
					continue;
				}
				if(in_array('in_person', $meeting_type) && in_array('geographic', $listing_type) && count($listing_type) == 1 && empty($search) && $user_location == 0){
					continue;
				}
				if(in_array('phone_web', $meeting_type) && in_array('geographic', $listing_type) && count($listing_type) == 1 && empty($search) && $user_location == 0){
					continue;
				}
			}*/

			$meeting_timezone = get_post_meta($product_id, 'timezone', true)  ? get_post_meta($product_id, 'timezone', true) : 'UTC';
			$meeting_am_pm = $time ? date("a",strtotime($time)) : '';

			$difference_server = 0;

			$local_tz = new DateTimeZone('UTC');
			$local = new DateTime('now', $local_tz);

			//NY is 3 hours ahead, so it is 2am, 02:00
			$user_tz = new DateTimeZone($timezone_client);
			$user = new DateTime('now', $user_tz);

			$local_offset = $local->getOffset();
			$user_offset = $user->getOffset();

			$diff_seconds = $user_offset - $local_offset;

            /*var_dump($local);
            var_dump($user);*/

			//var_dump($diff_seconds);

			if($time && $meeting_timezone){
				$dt = new DateTime($time, new DateTimeZone($meeting_timezone));
				$mtz_is_d = $dt->format('I');
				$meeting_real_tz = $dt->format('T'); //Get the current timezone for example if the meeting has EST, this will return  EDT
				$dt->setTimezone(new DateTimeZone($timezone_client));
				$time = $dt->format('g:i a');
				$timezone = $dt->format('T');
				$meeting_am_pm = $dt->format('a');
                /*var_dump($dt->format('I'));
                var_dump($mtz_is_d);*/

				if(($dt->format('I') && !$mtz_is_d) && $state != 'AZ'){
					//date_default_timezone_set($timezone_client);
					$time   = strtotime($time);
					$time   = $time - (60*60); //one hour
					$time = date("g:i a", $time);
				}
			}
			$end_am_pm = $end_time ? date("a",strtotime($end_am_pm)) : '';
			if($end_time && $meeting_timezone){
				$dt = new DateTime($end_time, new DateTimeZone($meeting_timezone));
				$mtz_is_d = $dt->format('I');
				$dt->setTimezone(new DateTimeZone($timezone_client));
				$meeting_real_tz_end_time = $dt->format('T');
				$end_time = $dt->format('g:i a');
				$end_am_pm = $dt->format('a');

				if($dt->format('I') && !$mtz_is_d){
					$end_time   = strtotime($end_time);
					$end_time   = $end_time - (60*60); //one hour
					$end_time = date("g:i a", $end_time);
				}

			}

			$add_day = '';

			if($meeting_am_pm == 'pm' && $end_am_pm == 'am'){
				$add_day = ' +1 day';
			}

			$meet_lat = '';
			$meet_lng = '';
			$distance = '';

			//if( in_array('in_person', $meeting_type) && $zip){
			if( $zip){

				if($coords = $this->get_geo_code($zip, $this->format_address($address, $city, $state, $zip, $meeting_country), $product_id)){
					$meet_lat = $coords['lat'];
					$meet_lng = $coords['lng'];
				}

			}

			if($meet_lat && $meet_lng && $lat && $lng){
				$distance = ceil($this->distance($meet_lat, $meet_lng, $lat, $lng));
				//var_dump($distance);
				//var_dump($product_id);
			}


			if(($user_location == 1 && $distance > 100) || ($user_location == 1 && empty($distance))){
				continue;
			}

			if( !empty($search) && empty($distance)){
				continue;
			}

			if( !empty($search) && !empty($distance) && $distance > 100){
				continue;
			}

			$special_meeting_focus = get_field('special_focus', $product_id);

			if(!empty($special_meeting_focus && $special_meeting_focus['value'] != 'any' && $special_meeting_focus != 'any') ){
				$special_meetings[] = $product_id;
				continue;
			}

			$meeting_obj = array(
				'id' => $product_id,
				'time_strtotime' => strtotime( $time ),
				'time' => $time,
				'day' => $day,
				'phone' => str_replace( '', ',', $phone ),
				'email' => $email,
				'index' => $this->index_weekend($day),
				'timezone' => $timezone,
				'real_timezone' => $meeting_real_tz,
				'start_time' => $start_time,
				'end_time' => $end_time,
				'address' => $address,
				'website' => $website,
				'zip' => $zip,
				'title' => str_replace('', ',', get_the_title($product_id)),
				'city' => $city,
				'state' => $state,
				'timezone_client' => $timezone_client,
				'additional_information' => str_replace('', ',', $additional_information),
				'meeting_country' => $meeting_country,
				'meeting_timezone' => $meeting_timezone,
				'meeting_link' => $meeting_link,
				'distance' => $distance,
				'meeting_type' => $meeting_type,
				'last_updated' => $last_updated,
				'group_id' => $groupId,
				'city_slug' => $city_slug,
				'facility' => $facility,
				'phone_web' => $phone_web,
				'language' => $language,
				'listing_type' => $listing_type,
				'calendar_link' => $calendar_link,
				'content' => the_content(),
				'add_day' => $add_day,
				'meeting_calendar_day' => $meeting_calendar_day,
			);

			//var_dump(get_the_title($product_id));

			if ( $time_am_pm == 'next' ) {
				if ( ( $time && date('l' ) != $day ) ||  ( date( 'l' ) == get_field( 'day' ) && strtotime( $time ) - $diff_seconds > strtotime( date( 'g:i a' ) ) ) ) {
					array_push( $next_availables, $meeting_obj );
					$cont++;
				}

				continue;
			}

			if ( in_array( $time_am_pm, array( 'am', 'pm' ) ) && $time_am_pm !== $meeting_am_pm ) {
				continue;
			}

			array_push( $normal_meetings, $meeting_obj );
            $cont++;
		endwhile;

		$meetings_html.= $this->normalMeetings( $normal_meetings, ! empty( $search ) || $user_location == 1 );

        if( $cont === 0 ){
			$meetings_html.= '<p style="text-align: center;font-size: 17px; color: #BA3C3C;font-weight: bold">Sorry, there are no meetings found with the search criteria provided. Please reset and try again with broader criteria. To find a phone or online meeting, select “Phone/Web” and “Next Available”</p>' .
                              '<h2>No Meeting in Your Area?</h2>
                                <p style="text-align: center;">Download the S-Anon Recovery When There’s No Local Group Pamphlet</p>
                                <a class="btn btn-primary" href="https://member.sanon.org/wp-content/uploads/sites/3/2021/07/L-16_NoLocalGroup_Jul2021.pdf" target="_blank" rel="noopener noreferrer">Download</a>';
        }

		$meetings_html.= $this->nextAvailablesOutput( $next_availables, $timezone_client );

		ob_end_flush();
		$meetings_html.= "<h4 class='special-meeting-heading'>Special Meetings</h4>";
		$meetings_html.= "<p class='special-meeting-heading'>S-Anon Special Meetings focus on recovery from the effects of specific sets of experiences or other special situations or conditions related to sexaholism. They are held as a complement to,
                             not as a replacement for, regular group meetings. Some S-Anon members have found it helpful to participate in meetings that focus on specific topics common to members' shared experiences.
                            The S-Anon Special Meetings posted here are in keeping with all S-Anon Twelve Traditions, and welcome the attendance of all members of S-Anon. However, Special Meetings differ from regular
                            registered group meetings, which focus on recovery topics applicable to all S-Anon members. Our common experience suggests that recovery does not depend on meetings with others who have had the same experiences or circumstances.</p>";

		$cont = 0;
		foreach ( $special_meetings as $special_meeting ) {
			ob_start();
			$meeting_type         = get_field( 'type', $special_meeting );
			$listing_type         = get_field( 'list_type', $special_meeting );
			$start_time           = get_field( 'start_time', $special_meeting );
			$state                = get_field( 'state', $special_meeting );
			$zip                  = get_field( 'zip', $special_meeting );
			$time                 = get_field( 'start_time', $special_meeting );
			$calendar_link        = get_field( 'calendar_link', $special_meeting ) ?? '';
			$day                  = get_field( 'day', $special_meeting ) ?? '';
			$meeting_calendar_day = '';
			$address              = get_field( 'address', $special_meeting );
			$city                 = get_field( 'city', $special_meeting );
			$meeting_country      = get_field( 'country', $special_meeting );
			$meeting_link         = get_field( 'meeting_link', $special_meeting );
			$topic                = get_field( 'special_focus', $special_meeting );

			if ( isset( $topic['label'] ) ) {
				$topic = $topic['label'];
			}

			if ( $day ) {
				$today_date = new DateTime();
				$today_date->modify( 'next ' . strtolower( $day ) );
				$meeting_calendar_day = $today_date->format( 'Y-m-d' );

			}

			if ( $city ) {
				$city_slug = sanitize_title_with_dashes( $city );
				$cities[$city_slug] = $city;
			} else {
				$city_slug = 'other';
			}

			if ( ( $special != get_field( 'special_focus', $special_meeting ) && $special != get_field( 'special_focus', $special_meeting )['value']) && ( $special != '' && $special != 'any' ) ) {
				continue;
			}
			$cont++;

			$time             = get_field( 'start_time', $special_meeting );
			$end_time         = get_field( 'end_time', $special_meeting );
			$meeting_timezone = get_post_meta( $special_meeting, 'timezone', true )  ? get_post_meta( $special_meeting, 'timezone', true ) : 'UTC';

			if ( $time && $meeting_timezone ) {

				$dt              = new DateTime( $time, new DateTimeZone( $meeting_timezone ) );
				$mtz_is_d        = $dt->format( 'I' );
				$meeting_real_tz = $dt->format( 'T' ); //Get the current timezone for example if the meeting has EST, this will return  EDT
				$dt->setTimezone( new DateTimeZone($timezone_client ) );
				$time            = $dt->format( 'g:i a' );
				$timezone        = $dt->format( 'T' );

				if ( $dt->format('I' ) && ! $mtz_is_d ) {
					$time   = strtotime( $time );
					$time   = $time - ( 60*60 ); //one hour
					$time   = date( "g:i a", $time );
				}
			}

			$end_am_pm = $end_time ? date( "a",strtotime( $end_am_pm ) ) : '';

			if ( $end_time && $meeting_timezone ) {
				$dt                       = new DateTime( $end_time, new DateTimeZone( $meeting_timezone ) );
				$mtz_is_d                 = $dt->format( 'I' );
				$dt->setTimezone( new DateTimeZone( $timezone_client ) );
				$meeting_real_tz_end_time = $dt->format( 'T' );
				$end_time                 = $dt->format( 'g:i a' );
				$end_am_pm                = $dt->format( 'a' );

				if ( $dt->format('I' ) && ! $mtz_is_d ) {
					$end_time   = strtotime( $end_time );
					$end_time   = $end_time - ( 60*60 ); //one hour
					$end_time   = date( "g:i a", $end_time );
				}

			}

			$title =  get_the_title( $special_meeting );
			?>

            <div class="meeting special-meeting" data-city="<?php echo $city_slug; ?>">
                <div class="row"><div class="col-12"><h5 class="title"><?php echo $title; ?></h5><p class="title-print-meeting"><strong><?php echo  $title; ?></strong></p></div></div>
                <!--<p><?php /*echo !empty($distance) ? '<strong>Distance: </strong>' . $distance . ' miles' : '' */?></p>-->
                <br>
                <div class="row">
                    <div class="col-md-5">
						<?php if($start_time && !empty($meeting_calendar_day)): ?>
                            <form method="post" action="/download-ics.php">
                                <input type="hidden" name="date_start" value="<?= date('Y-m-d H:i:s', strtotime($meeting_calendar_day . ' ' . $time)) ?>">
                                <input type="hidden" name="date_end" value="<?= date('Y-m-d H:i:s', strtotime($meeting_calendar_day . ' ' . $end_time)) ?>">
                                <input type="hidden" name="location" value="<?= $this->format_address($address, $city, $state, $zip, $meeting_country, false) ?>">
                                <input type="hidden" name="description" value="<?= get_field('additional_information', $special_meeting) . '\n ' . $phone . '\n ' . $email . '\n ' . $meeting_link ?>">
                                <input type="hidden" name="summary" value="<?= $title; ?>">
                                <input type="hidden" name="timezone" value="<?= $timezone_client ?>">
                                <input type="hidden" name="url" value="">
                                <button style="padding: 0 !important;" class="meeting_add_to_calendar" type="submit"><i class="fa fa-plus"></i> Add to Calendar</button>
                            </form>
                            <i class="fa fa-calendar"></i>
						<?php endif;
						if($time && $meeting_timezone)
							echo $day . ' ' . $time . ' ' . $timezone;
						?>
						<?php if($facility = get_field('facility', $special_meeting)): ?>
                            <strong><i class="fa fa-map-marker" aria-hidden="true"></i> <?php echo $facility; ?></strong><br>
						<?php endif; ?>
                        <br>
						<?php if((get_field('city', $special_meeting) || get_field('state', $special_meeting) || get_field('state', $special_meeting)) && empty($facility)): ?> <i class="fa fa-map-marker" aria-hidden="true"></i><?php endif; ?>
						<?php if(!empty(trim($address))): ?>
                            <span class="label"><?php echo $address; ?></span><br>
						<?php endif; ?>
						<?php if(!empty(trim($city)) ): ?>
                            <span><?php echo $city; ?></span>
						<?php endif; ?>
						<?php if(!empty(trim($state)) ): ?>
                            <span><?php echo ', ' . $state; ?></span>
						<?php endif; ?>
						<?php if(!empty(trim($zip))): ?>
                            <span><?php echo $zip; ?></span>
						<?php endif; ?>
						<?php if(!empty(trim($meeting_country)) ): ?>
                            <span><?php echo $meeting_country; ?></span><br>
						<?php endif; ?>

                    </div>
                    <div class="col-md-4">
						<?php if($email = get_field('email', $special_meeting)): ?>
                            <div><i class="fa fa-envelope-o"></i> <a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a></div>
						<?php endif; ?>
						<?php if($phone = get_field('phone', $special_meeting) && !empty($phone)): ?>
                            <div><a href="tel:<?php echo $phone; ?>"><i class="fa fa-phone"></i> <?php echo $phone; ?></a></div>
						<?php endif ?>
						<?php if($website = get_field('website', $special_meeting) && !empty($website)): ?>
                            <!--<div><i class="fa fa-globe"></i> <a class="external_link" href="<?php /*echo str_contains($website, 'http') ?  $website : 'https://' .  $website; */?>" target="_blank" rel="nofollow"><?php /*echo $website; */?></a></div>-->
                            <div><button type="button" data-toggle="modal" data-target="#modal-disclaimer-<?php echo $special_meeting; ?>">
                                    <span class="external_link" ><?php echo $website; ?></span>
                                </button><i class="fa fa-globe"></i> </div>
						<?php endif; ?>
                    </div>

                    <div class="col-md-3">
						<?php if(in_array('in_person', $meeting_type) && in_array('phone_web', $meeting_type)){

							if($meeting_link){?>
                                <a href="<?= $meeting_link ?>">Join Now</a>
							<?php }else{ ?>
                                <span>In-Person and Phone/Web</span><br>
							<?php } ?>

						<?php }else if(in_array('in_person', $meeting_type)){
							if($meeting_link){?>
                                <i class="fa fa-link" aria-hidden="true"></i> <a href="<?= $meeting_link ?>">Join Now</a>
							<?php }else{ ?>
                                <span><i class="fa fa-user" aria-hidden="true"></i>In Person</span><br>
							<?php } ?>

						<?php }else{
							if($meeting_link){?>
                                <span><i class="fa fa-link" aria-hidden="true"></i> <a href="<?= $meeting_link ?>">Join Now</a></span>
							<?php }else{ ?>
                                <span><i class="fa fa-phone" aria-hidden="true"></i>Web / Phone</span><br>
							<?php } ?>
						<?php } ?>
                    </div>

                    <div class="col-12">
						<?php if($additional_information = get_field('additional_information', $special_meeting)): ?>
                            <span class="additional_information"><?php echo $additional_information; ?></span>
						<?php endif; ?>
                    </div>

                </div>
                <div class="row my-3">
                    <div class="col-12">
                        <span data-id="<?php echo $special_meeting; ?>" class="view-more-trigger view-more-<?php echo $special_meeting; ?>"><i class="fa fa-caret-up" aria-hidden="true"></i> View More</span>
                    </div>
                </div>
                <div class="row more-info-section section-hidden section-<?php echo $special_meeting ?>">
                    <div class="col-md-7 col-lg-8">

						<?php if ($groupId = get_field('group_id', $special_meeting)): ?>
                            <span class="label">ID:</span><strong><?php echo $groupId; ?></strong><br>
						<?php endif; ?>
						<?php if ($topic): ?>
                            <span class="label">Topic:</span><strong><?php echo ucfirst($topic); ?></strong><br>
						<?php endif; ?>
						<?php if(in_array('in_person', get_field('type', $special_meeting) )): ?>

						<?php endif; ?>

						<?php /*elseif(in_array('phone_web', get_field('type', $special_meeting) )): */?>
						<?php if($language = get_field('language', $special_meeting)): ?>
                            <span class="label">Language:</span><strong><?php echo $language; ?></strong><br>
						<?php endif; ?>
						<?php if($phone_web = get_field('phone_web', $special_meeting)): ?>
                            <span class="label">Type:</span><strong><?php echo $phone_web; ?></strong><br>
						<?php endif; ?>


                        <span class="label">Last Update: </span><strong><?php echo  get_the_modified_time( 'm/d/Y', $special_meeting) ?></strong><br>

                    </div>
                    <div class="col-12 meeting-content">
						<?php echo get_the_content(null, false, $special_meeting); ?>
                    </div>
                    <div class="col-12">
                        <div class="link-report">
                            <!--<span data-name="<?php /*echo get_the_title(); */?>"
                              data-id="<?php /*echo get_the_ID(); */?>"
                              class="report popmake-48035 report_concern_trigger report-<?php /*echo get_the_ID(); */?>">Report Concern</span>-->
                            <button class="update rep update-<?php echo $special_meeting; ?>">Update this meeting</button>
                            <button type="button" data-toggle="modal" data-target="#modal-<?php echo $special_meeting; ?>">
                                Report Concern
                            </button>
                        </div>
                    </div>
                </div>


                <div class="modal fade" id="modal-<?php echo $special_meeting; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
								<?php echo do_shortcode('[gravityform id="6" ajax="true" field_values="meeting_name='.$title.'&meeting_id='.$special_meeting.'&meeting_group_id='. get_field('group_id', $special_meeting) .'"]') ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="modal-disclaimer-<?php echo $special_meeting; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>IMPORTANT: You are now leaving the official web site of the S-Anon International Family Groups. This link is made available to provide information about local S-Anon/S-Ateen activities. By providing this link we do not imply review, endorsement, or approval of the linked site. Thank you for visiting www.sanon.org. We hope that you have found the information you were seeking.</p><br>
                                <button class="disclaimer-btn"><a class="external_link_disclaimer" href="<?php echo str_contains($meeting['website'], 'http') ?  $meeting['website'] : 'https://' .  $meeting['website']; ?>" target="_blank" rel="nofollow">Go To Site</a></button>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
			<?php $meetings_html .= ob_get_clean();
		}
		ob_end_flush();

		$meetings_html .= "<input class='cont_special_meeting' value='" .$cont ."' type='hidden'>";

		// render meetings content
		echo $meetings_html;

		exit();
	}

	function normalMeetings($meetings, $geocoding_search){

		if($geocoding_search){
			$distances = array_column($meetings, 'distance');
			array_multisort($distances, SORT_ASC,  $meetings);
		}else{
			$indexes = array_column($meetings, 'index');
			$times = array_column($meetings, 'time_strtotime');
			array_multisort($indexes, SORT_ASC, $times, SORT_ASC, $meetings);
		}

		foreach ( $meetings as $meeting ) {
			ob_start();
			?>
            <div class="meeting" data-city="<?php echo $meeting['city_slug']; ?>">
                <div class="row"><div class="col-12"><h5 class="title"><?php echo $meeting['title']; ?></h5><p class="title-print-meeting"><strong><?php echo $meeting['title']; ?></strong></p></div></div>
				<?php if(!empty($meeting['distance'])){ ?>
                    <p><strong>Distance: </strong><?php echo  $meeting['distance'] > 1 ? $meeting['distance'] . ' miles' : '1 mile';?></p>
				<?php } ?>
                <br>
                <div class="row">
                    <div class="col-md-5">
						<?php if($meeting['start_time'] && !empty($meeting['meeting_calendar_day'])): ?>
                            <form method="post" action="/download-ics.php">
                                <input type="hidden" name="date_start" value="<?= date('Y-m-d H:i:s', strtotime($meeting['meeting_calendar_day'] . ' ' . $meeting['time'])) ?>">
                                <input type="hidden" name="date_end" value="<?= !empty($meeting['end_time']) ? date('Y-m-d H:i:s', strtotime($meeting['meeting_calendar_day'] . ' ' . $meeting['end_time'] )) : date('Y-m-d H:i:s', strtotime($meeting['meeting_calendar_day'] . ' ' . $meeting['time'])) ?>">
                                <input type="hidden" name="location" value="<?= $this->format_address($meeting['address'], $meeting['city'], $meeting['state'], $meeting['zip'], $meeting['meeting_country'], false) ?>">
                                <input type="hidden" name="description" value="<?= htmlspecialchars($meeting['additional_information'], ENT_QUOTES, 'UTF-8') . ' \n ' . $meeting['phone'] . ' \n ' . $meeting['email'] . ' \n ' . $meeting['meeting_link'] ?>">
                                <input type="hidden" name="summary" value="<?= $meeting['title'] ?>">
                                <input type="hidden" name="timezone" value="<?= $meeting['timezone_client'] ?>">
                                <input type="hidden" name="url" value="">
                                <button style="padding: 0 !important;" class="meeting_add_to_calendar" type="submit"><i class="fa fa-plus"></i> Add to Calendar</button>
                            </form>
                            <i class="fa fa-calendar"></i>
						<?php endif;
						if($meeting['time'] && $meeting['meeting_timezone'])
							echo $meeting['day'] . ' ' . $meeting['time'] . ' ' . $meeting['timezone'];
						?>

                        <br>
						<?php if($facility = get_field('facility', $meeting['id'])): ?>
                            <i class="fa fa-map-marker" aria-hidden="true"></i> <strong><?php echo $facility; ?></strong><br>
						<?php endif; ?>
						<?php if(($meeting['city'] || $meeting['state'] || $meeting['zip']) && empty($facility)): ?> <i class="fa fa-map-marker" aria-hidden="true"></i><?php endif; ?>
						<?php if(!empty(trim($meeting['address']))): ?>
                            <span class="label"><?php echo $meeting['address']; ?></span><br>
						<?php endif; ?>
						<?php if(!empty(trim($meeting['city'])) ): ?>
                            <span><?php echo $meeting['city']; ?></span>
						<?php endif; ?>
						<?php if(!empty(trim($meeting['state'])) ): ?>
                            <span><?php echo ', ' . $meeting['state']; ?></span>
						<?php endif; ?>
						<?php if(!empty(trim($meeting['zip']))): ?>
                            <span><?php echo $meeting['zip']; ?></span>
						<?php endif; ?>
						<?php if(!empty(trim($meeting['meeting_country'])) ): ?>
                            <span><?php echo $meeting['meeting_country']; ?></span><br>
						<?php endif; ?>

                    </div>
                    <div class="col-md-4">
						<?php if($meeting['email']): ?>
                            <div><i class="fa fa-envelope-o"></i> <a href="mailto:<?php echo $meeting['email']; ?>"><?php echo $meeting['email']; ?></a></div>
						<?php endif; ?>
						<?php if(!empty($meeting['phone']) && strlen($meeting['phone']) > 6 ): ?>
                            <div><a href="tel:<?php echo $meeting['phone']; ?>"><i class="fa fa-phone"></i> <?php echo $meeting['phone']; ?></a></div>
						<?php endif ?>
						<?php if( !empty($meeting['website'])): ?>
                            <!--<div><i class="fa fa-globe"></i> <a class="external_link aaa" href="<?php /*echo str_contains($meeting['website'], 'http') ?  $meeting['website'] : 'https://' .  $meeting['website']; */?>" target="_blank" rel="nofollow"><?php /*echo $meeting['website']; */?></a></div>-->
                            <div><button  class="disclaimer-btn" type="button" data-toggle="modal" data-target="#modal-disclaimer-<?php echo $meeting['id']; ?>">
                                    <i class="fa fa-globe"></i>  <span class="external_link" ><?php echo $meeting['website']; ?></span>
                                </button></div>
						<?php endif; ?>
                    </div>

                    <div class="col-md-3">
						<?php if(in_array('in_person', $meeting['meeting_type']) && in_array('phone_web', $meeting['meeting_type'])){

							if($meeting['meeting_link']){?>
                                <a href="<?= $meeting['meeting_link']; ?>">Join Now</a>
							<?php }else{ ?>
                                <span>In-Person and Phone/Web</span><br>
							<?php } ?>

						<?php }else if(in_array('in_person', $meeting['meeting_type'])){
							if($meeting['meeting_link']){?>
                                <i class="fa fa-link" aria-hidden="true"></i> <a href="<?= $meeting['meeting_link']; ?>">Join Now</a>
							<?php }else{ ?>
                                <span><i class="fa fa-user" aria-hidden="true"></i>In Person</span><br>
							<?php } ?>

						<?php }else{
							if($meeting['meeting_link']){?>
                                <span><i class="fa fa-link" aria-hidden="true"></i> <a href="<?= $meeting['meeting_link'] ?>">Join Now</a></span>
							<?php }else{ ?>
                                <span><i class="fa fa-phone" aria-hidden="true"></i>Web / Phone</span><br>
							<?php } ?>
						<?php } ?>
                    </div>

                    <div class="col-12">
						<?php if($meeting['additional_information']): ?>
                            <span class="additional_information"><?php echo $meeting['additional_information']; ?></span>
						<?php endif; ?>
                    </div>
                </div>
                <div class="row my-3">
                    <div class="col-12">
                        <span data-id="<?php echo $meeting['id'];  ?>" class="view-more-trigger view-more-<?php echo $meeting['id'];  ?>"><i class="fa fa-caret-up" aria-hidden="true"></i> View More</span>
                    </div>
                </div>
                <div class="row more-info-section section-hidden section-<?php echo $meeting['id']; ?>">
                    <div class="col-md-7 col-lg-8">
						<?php if ($meeting['group_id']): ?>
                            <span class="label">ID:</span><strong><?php echo $meeting['group_id']; ?></strong><br>
						<?php endif; ?>

						<?php /*if($meeting['facility'] && in_array('in_person', $meeting['meeting_type'] )): */?><!--
							<strong><?php /*echo $meeting['facility']; */?></strong><br>
						--><?php /*endif; */?>

						<?php /*elseif(in_array('phone_web', get_field('type'))): */?>
						<?php if($meeting['language']): ?>
                            <span class="label">Language:</span><strong><?php echo $meeting['language']; ?></strong><br>
						<?php endif; ?>
						<?php if($meeting['phone_web']): ?>
                            <span class="label">Type:</span><strong><?php echo $meeting['phone_web']; ?></strong><br>
						<?php endif; ?>
						<?php /*endif; */?>

                        <span class="label">Last Update: </span><strong><?php echo   get_the_modified_time( 'm/d/Y', $meeting['id']) ?></strong><br>

                    </div>
                    <div class="col-12 meeting-content">
						<?php echo $meeting['content']; ?>
                    </div>
                    <div class="col-12">
                        <div class="link-report">

                            <button class="update rep update-<?php echo $meeting['id']; ?>">Update this meeting</button>
                            <button type="button" data-toggle="modal" data-target="#modal-<?php echo $meeting['id']; ?>">
                                Report Concern
                            </button>
                        </div>
                    </div>
                </div>


                <div class="modal fade" id="modal-<?php echo $meeting['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
								<?php echo do_shortcode('[gravityform id="6" ajax="true" field_values="meeting_name='.$meeting['title'].'&meeting_id='.$meeting['id'].'&meeting_group_id='.$meeting['group_id'].'"]') ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="modal-disclaimer-<?php echo $meeting['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>IMPORTANT: You are now leaving the official web site of the S-Anon International Family Groups. This link is made available to provide information about local S-Anon/S-Ateen activities. By providing this link we do not imply review, endorsement, or approval of the linked site. Thank you for visiting www.sanon.org. We hope that you have found the information you were seeking.</p><br>
                                <button class="disclaimer-btn"><a class="external_link_disclaimer" href="<?php echo str_contains($meeting['website'], 'http') ?  $meeting['website'] : 'https://' .  $meeting['website']; ?>" target="_blank" rel="nofollow">Go To Site</a></button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
			<?php $meetings_html .= ob_get_clean();
		}
		ob_end_flush();
		return $meetings_html;
	}

	function nextAvailablesOutput( $next_availables, $timezone_client ){

		$indexes = array_column( $next_availables, 'index' );
		$times = array_column( $next_availables, 'time_strtotime' );
		array_multisort( $indexes, SORT_ASC, $times, SORT_ASC, $next_availables );

		foreach ($next_availables as $next){
			ob_start();
			// generate cities list
			$meeting_id = $next['id'];
			$zip = $next['zip'];
			$title = $next['title'];
			$website = $next['website'];
			$state = $next['state'];
			$address = $next['address'];
			$additional_information = str_replace('', ',', $next['additional_information']);
			$city = $next['city'];
			$day = $next['day'];
			$phone = $next['phone'];
			$email = $next['email'];
			$start_time = $next['start_time'];
			$end_time = $next['end_time'];
			$meeting_link = $next['meeting_link'];
			$meeting_country = $next['meeting_country'];
			$add_day = $next['add_day'];
			$timezone = $next['timezone'];
			$group_id = $next['group_id'];
			$time = $next['time'];
			$meeting_calendar_day = $next['meeting_calendar_day'];
			$meeting_timezone = $next['meeting_timezone'];
			if($city) {
				$city_slug = sanitize_title_with_dashes( $city );
				$cities[$city_slug] = $city;
			} else {
				$city_slug = 'other';
			}


			?>

            <div class="meeting next-meeting" data-city="<?php echo $city_slug; ?>">
                <div class="row"><div class="col-12"><h5 class="title"><?php echo $title; ?></h5><p class="title-print-meeting"><strong><?php echo $title; ?></strong></p></div></div>
                <br>
                <div class="row">
                    <div class="col-md-5">
						<?php if($start_time && !empty($meeting_calendar_day)): ?>
                            <form method="post" action="/download-ics.php">
                                <input type="hidden" name="date_start" value="<?= date('Y-m-d H:i:s', strtotime($meeting_calendar_day . ' ' . $time)) ?>">
                                <input type="hidden" name="date_end" value="<?= !empty($end_time) ? date('Y-m-d H:i:s', strtotime($meeting_calendar_day . ' ' . $end_time )) : date('Y-m-d H:i:s', strtotime($meeting_calendar_day . ' ' . $time . ' +1 day')) ?>">
                                <input type="hidden" name="location" value="<?= $this->format_address($address, $city, $state, $zip, $meeting_country, false) ?>">
                                <input type="hidden" name="description" value="<?= $additional_information . ' \n ' . $phone . ' \n ' . $email . ' \n ' . $meeting_link ?>">
                                <input type="hidden" name="summary" value="<?= get_the_title($meeting_id) ?>">
                                <input type="hidden" name="timezone" value="<?= $timezone_client ?>">
                                <input type="hidden" name="url" value="">
                                <button style="padding: 0 !important;" class="meeting_add_to_calendar" type="submit"><i class="fa fa-plus"></i> Add to Calendar</button>
                            </form>
                            <i class="fa fa-calendar"></i>
							<?php /*the_field('start_time') */?>
							<?php

							// echo get_field('timezone') ? get_field('timezone')['value']  : ''; ?>
						<?php endif;
						if($time && $meeting_timezone)
							echo $day . ' ' . $time . ' ' . $timezone;
						/*if($end_time && $meeting_timezone)
							echo $end_time . ' ' . $timezone;*/
						?>

                        <br>
						<?php if($facility = get_field('facility', $meeting_id)): ?>
                            <i class="fa fa-map-marker" aria-hidden="true"></i> <strong><?php echo $facility; ?></strong><br>
						<?php endif; ?>
						<?php if((get_field('city') || get_field('state') || get_field('state')) && empty($facility)): ?> <i class="fa fa-map-marker" aria-hidden="true"></i><?php endif; ?>
						<?php if(!empty(trim($address))): ?>
                            <span class="label"><?php echo $address; ?></span><br>
						<?php endif; ?>
						<?php if(!empty(trim($city)) ): ?>
                            <span><?php echo $city; ?></span>
						<?php endif; ?>
						<?php if(!empty(trim($state)) ): ?>
                            <span><?php echo ', ' . $state; ?></span>
						<?php endif; ?>
						<?php if(!empty(trim($zip))): ?>
                            <span><?php echo $zip; ?></span>
						<?php endif; ?>
						<?php if(!empty(trim($meeting_country)) ): ?>
                            <span><?php echo $meeting_country; ?></span><br>
						<?php endif; ?>

                    </div>
                    <div class="col-md-4">
						<?php if($email = get_field('email', $meeting_id)): ?>
                            <div><i class="fa fa-envelope-o"></i> <a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a></div>
						<?php endif; ?>
						<?php if($phone = get_field('phone', $meeting_id)): ?>
                            <div><a href="tel:<?php echo $phone; ?>"><i class="fa fa-phone"></i> <?php echo $phone; ?></a></div>
						<?php endif ?>
						<?php if($website = get_field('website', $meeting_id)): ?>
                            <!--<div><i class="fa fa-globe"></i> <a class="external_link" href="<?php /*echo str_contains($website, 'http') ? $website : 'https://' .  $website; */?>" target="_blank" rel="nofollow"><?php /*echo $website; */?></a></div>-->
                            <div><button  class="disclaimer-btn" type="button" data-toggle="modal" data-target="#modal-disclaimer-<?php echo $meeting_id; ?>">
                                    <i class="fa fa-globe"></i>  <span class="external_link" ><?php echo $website; ?></span>
                                </button></div>
						<?php endif; ?>
                    </div>

                    <div class="col-md-3">
						<?php if(get_field('type', $meeting_id) == 'in_person'){ ?>
                            <span><i class="fa fa-user" aria-hidden="true"></i>In Person</span><br>

						<?php }else{
							if($meeting_link = get_field('meeting_link', $meeting_id)){?>
                                <a href="<?php echo $meeting_link; ?>" target="_blank"><i class="fa fa-link" aria-hidden="true"></i>Join Now</a><br>
							<?php }else{ ?>
                                <span><i class="fa fa-phone" aria-hidden="true"></i>Web / Phone</span><br>
							<?php } ?>
						<?php } ?>
                    </div>
                </div>
                <div class="col-12">
					<?php if($additional_information = get_field('additional_information', $meeting_id)): ?>
                        <span class="additional_information"><?php echo $additional_information; ?></span>
					<?php endif; ?>
                </div>
                <div class="row my-3">
                    <div class="col-12">
                        <span data-id="<?php echo $meeting_id; ?>" class="view-more-trigger view-more-<?php echo $meeting_id; ?>"><i class="fa fa-caret-up" aria-hidden="true"></i> View More</span>
                    </div>
                </div>
                <div class="row more-info-section section-hidden section-<?php echo $meeting_id; ?>">
                    <div class="col-md-7 col-lg-8">
						<?php /*if(in_array('in_person', get_field('type', $meeting_id))): */?>
						<?php if ($groupId = get_field('group_id', $meeting_id)): ?>
                            <span class="label">ID:</span><strong><?php echo $groupId; ?></strong><br>
						<?php endif; ?>
						<?php /*if($facility = get_field('facility', $meeting_id) && in_array('in_person', get_field('type', $meeting_id ))): */?><!--
							<strong><?php /*echo $facility; */?></strong><br>
						--><?php /*endif; */?>

						<?php /*elseif(in_array('phone_web', get_field('type', $meeting_id))): */?>
						<?php if($language = get_field('language', $meeting_id)): ?>
                            <span class="label">Language:</span><strong><?php echo $language; ?></strong><br>
						<?php endif; ?>
						<?php if($phone_web = get_field('phone_web', $meeting_id)): ?>
                            <span class="label">Type:</span><strong><?php echo $phone_web; ?></strong><br>
						<?php endif; ?>
						<?php /*endif; */?>

                        <span class="label">Last Update: </span><strong><?php echo  get_the_modified_time( 'm/d/Y', $meeting_id) ?></strong><br>

                    </div>
                    <div class="col-12 meeting-content">
						<?php echo get_post_field('post_content', $meeting_id); ?>
                    </div>
                    <div class="col-12">
                        <div class="link-report">
                            <button class="update rep update-<?php echo $meeting_id ?>">Update this meeting</button>
                            <button type="button" data-toggle="modal" data-target="#modal-<?php echo $meeting_id; ?>">
                                Report Concern
                            </button>
                        </div>
                    </div>
                </div>


                <div class="modal fade" id="modal-<?php echo $meeting_id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
								<?php $name = get_the_title();echo do_shortcode('[gravityform id="6" ajax="true" field_values="meeting_name='.$name.'&meeting_id='.$meeting_id.'&meeting_group_id='.$group_id.'"]') ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="modal-disclaimer-<?php echo $meeting_id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>IMPORTANT: You are now leaving the official web site of the S-Anon International Family Groups. This link is made available to provide information about local S-Anon/S-Ateen activities. By providing this link we do not imply review, endorsement, or approval of the linked site. Thank you for visiting www.sanon.org. We hope that you have found the information you were seeking.</p><br>
                                <button class="disclaimer-btn"><a class="external_link_disclaimer" href="<?php echo str_contains($website, 'http') ?  $website : 'https://' .  $website; ?>" target="_blank" rel="nofollow">Go To Site</a></button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
			<?php $meetings_html .= ob_get_clean();
		}
		ob_end_flush();
		return $meetings_html;

	}

	/**
	 * Fetch meeting with some filter applied and returned as json (AJAX).
	 *
	 * @return void
	 * @throws Exception
	 */
	public function get_meetings() {
		$search = isset( $_POST['search'] ) ? strtoupper( $_POST['search'] ) : '';
		$type = $_POST['type'];
		$weekday =  $_POST['weekday'];
		$lang =  $_POST['lang'];
		$special =  $_POST['special'] ?? '';
		$timezone =  $_POST['timezone'] ?? '';
		$country =  $_POST['country'] ?? '';
		$lat =  $_POST['lat'] ?? '';
		$lng =  $_POST['lng'] ?? '';
		$user_location =  $_POST['user_location'] ?? '';

		$meta_query = [];

		$meta_query[] = !empty($weekday) ? array('key' => 'day', 'value'   => $weekday, 'compare' => 'LIKE') : [];
		$meta_query[] = !empty($lang) ? array('key' => 'language', 'value'   => $lang, 'compare' => 'LIKE') : [];
		$meta_query[] = !empty($type) ? array('key' => 'type', 'value'  => $type, 'compare' => 'LIKE') : [];
		$meta_query[] = !empty($timezone) ? array('key' => 'timezone', 'value'  => $timezone, 'compare' => 'LIKE') : [];

		if($special !== '' && $special !== 'any'){

			$meta_query[] = array('key' => 'special_focus', 'value'  => $special, 'compare' => 'LIKE');
		}

		$query_array = array(
			'post_type' => 'meetings',
			'meta_query' => $meta_query,
			'order' => 'ASC',
			'posts_per_page' => 500,
			'nopaging' => false
		);

		if(!empty($country)){
			$query_array['tax_query'] =  [
				[
					'taxonomy' => 'area',
					'field' => 'term_id',
					'terms' => $country,
				],
			];
		}

		// prepare var to hold meetings output and cities
		$cities = [];



		$ip     = $_SERVER['REMOTE_ADDR']; // means we got user's IP address
		$ipData = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
		$timezone_client = "UTC";

		if( isset( $ipData->timezone ) ) {
			$timezone_client = $ipData->timezone;
		}else{
			$timezone_client = $this->sa_get_timezone_from_ip( $ip );
		}
		$meetings = array();
		//wp_reset_query();
		$the_query = new WP_Query( $query_array );
		while ( $the_query->have_posts() ) : $the_query->the_post();

			$meeting_id = get_the_ID();
			$additional_information = get_post_meta($meeting_id, 'additional_information', true);
			$website = get_field('website');
			$group_id = get_field('group_id');
			$day = get_field('day');
			$start_time = get_field('start_time');
			$address = get_field('address');
			$city = get_field('city');
			$state = get_field('state');
			$zip = get_field('zip');
			$country = get_field('country');
			$language = get_field('language');
			$phone_web = get_field('phone_web');
			$email = get_field('email');
			$meeting_type = get_field('type');
			$listing_type = get_field('list_type');
			$time_am_pm =  $_POST['time'] ?? '';

			if($day){
				$today_date = new DateTime();
				$today_date->modify('next ' . strtolower($day));

			}

			if($city) {
				$city_slug = sanitize_title_with_dashes( $city );
				$cities[$city_slug] = $city;
			}

			//Visibility rules
			if(count($listing_type) != 1 && count($listing_type) != 2){
				continue;
			}
			if(in_array('in_person', $meeting_type) && count($meeting_type) == 1 && in_array('worldwide', $listing_type)){
				continue;
			}
			if(in_array('in_person', $meeting_type) && in_array('geographic', $listing_type) && count($listing_type) == 1 && empty($search) && $user_location == 0){
				continue;
			}
			if(in_array('phone_web', $meeting_type) && in_array('geographic', $listing_type) && count($listing_type) == 1 && empty($search) && $user_location == 0){
				continue;
			}

			$meeting_timezone = get_post_meta( $meeting_id, 'timezone', true ) ? get_post_meta( $meeting_id, 'timezone', true ) : 'UTC';
			$meeting_am_pm    = $start_time ? gmdate( 'a', strtotime( $start_time ) ) : '';

			if ( $start_time && $meeting_timezone ) {
				$dt = new DateTime( $start_time, new DateTimeZone( $meeting_timezone ) );
				$dt->setTimezone( new DateTimeZone( $timezone_client ) );
				$start_time       = $dt->format( 'g:i a' );
				$meeting_timezone = $dt->format( 'T' );
				$meeting_am_pm    = $dt->format( 'a' );
			}

			$meet_lat = '';
			$meet_lng = '';
			$distance = '';

			if ( $zip ) {
				$coords = $this->get_geo_code( $zip, $this->format_address( $address, $city, $state, $zip, $country ), $meeting_id );
				if ( $coords ) {
					$meet_lat = $coords['lat'];
					$meet_lng = $coords['lng'];
				}
			}

			if ( $meet_lat && $meet_lng && $lat && $lng ) {
				$distance = intval( $this->distance( $meet_lat, $meet_lng, $lat, $lng ) );
			}

			if ( ( 1 === $user_location && $distance > 100 ) || ( 1 === $user_location && empty( $distance ) ) ) {
				continue;
			}

			if ( empty( $meet_lat ) || empty( $meet_lng ) ) {
				continue;
			}

			if ( ! empty( $search ) && empty( $distance ) ) {
				continue;
			}

			if ( ! empty( $search ) && ! empty( $distance ) && $distance > 100 ) {
				continue;
			}

			if ( in_array( $time_am_pm, array( 'am', 'pm' ), true ) && $time_am_pm !== $meeting_am_pm ) {
				continue;
			}

			$meetings[] = array(
				'website'                => $website,
				'address'                => $address,
				'city'                   => $city,
				'state'                  => $state,
				'zipcode'                => $zip,
				'phone'                  => $phone_web,
				'day'                    => $day,
				'time'                   => $start_time,
				'timezone'               => $meeting_timezone,
				'email'                  => $email,
				'name'                   => str_replace( '', ',', get_the_title() ),
				'additional_information' => $additional_information,
				'group_id'               => $group_id,
				'lat'                    => $meet_lat,
				'lng'                    => $meet_lng,
			);

		endwhile;

		echo wp_json_encode(
			array(
				'meetings' => $meetings,
				'success'  => true,
			)
		);
		wp_die();
	}

	/**
	 * Get the lat and long returned in an array.
	 *
	 * @param string  $zip The address zip code.
	 * @param string  $address_formatted The address formatted e.g (9015 sw 36th st, Miami FL 33165).
	 * @param integer $id The meeting ID.
	 *
	 * @return array|null
	 */
	public function get_geo_code( $zip, $address_formatted, $id ) {
		$saved_lat = get_post_meta( $id, 'meet_lat', true );
		$saved_lng = get_post_meta( $id, 'meet_lng', true );

        if ( 'undefined' === $saved_lat || "undefined" === $saved_lng){
			$saved_lng = '';
            $saved_lat = '';
        }

		// The zipcode need to captured to compare the next time in case the address change, and we need to generate again the coordinates.
		if ( ! empty( $saved_lat ) && ! empty( $saved_lng ) ) {
			return array(
				'lat'   => $saved_lat,
				'lng'   => $saved_lng,
				'saved' => 1,
			);
		}

		if ( empty( $address_formatted ) ) {
			return null;
		}

		$url = "https://maps.google.com/maps/api/geocode/json?address=$address_formatted&key=AIzaSyDLuSE8QLWitaQzzN0u_yeRWzrD3WVy4Qk";


		$geocode     = wp_remote_get( $url );

		$json        = json_decode( $geocode['body'], true );

		$data['lat'] = $json['results'][0]['geometry']['location']['lat'] ?? 'undefined';
		$data['lng'] = $json['results'][0]['geometry']['location']['lng'] ?? 'undefined';




		update_post_meta( $id, 'meet_lat', $data['lat'] );
		update_post_meta( $id, 'meet_lng', $data['lng'] );

		return $data;
	}

	/**
	 * Get the distance in miles from two cords
	 *
	 * @param float $lat1 latitude1.
	 * @param float $lon1 longitude1.
	 * @param float $lat2 latitude2.
	 * @param float $lon2 longitude2.
	 *
	 * @return float|int
	 */
	public function distance( $lat1, $lon1, $lat2, $lon2 ) {
		if ( $lat1 === $lat2 && $lon1 === $lon2 ) {
			return 1;
		} else {
			$theta = $lon1 - $lon2;
			$dist  = sin( deg2rad( $lat1 ) ) * sin( deg2rad( $lat2 ) ) + cos( deg2rad( $lat1 ) ) * cos( deg2rad( $lat2 ) ) * cos( deg2rad( $theta ) );
			$dist  = acos( $dist );
			$dist  = rad2deg( $dist );
			$miles = $dist * 60 * 1.1515;

			if ( 0 === intval( $miles ) ) {
				return 1;
			}

			return $miles;
		}
	}

	/**
	 * Get the timezone from the ip
	 *
	 * @param string $ip The internet protocol used to get the user time zone.
	 *
	 * @return string;
	 */
	public function sa_get_timezone_from_ip( $ip ): string {
		$resource = 'https://api.ipgeolocation.io/ipgeo?apiKey=bb18ac49ab454a75a50dd215bdf1b1f3&fields=time_zone&ip=' . $ip;

		$response = wp_remote_get(
			$resource,
			array(
				'timeout' => 120,
			)
		);

		if ( ! isset( $response['body'] ) ) {
			return 'UTC';
		}

		$timezone_object = json_decode( $response['body'], true );

		if ( isset( $timezone_object['time_zone']['name'] ) ) {

			return $timezone_object['time_zone']['name'];

		}

		return 'UTC';
	}

	public function sa_is_dst( $ip ): string {
		$resource = 'https://api.ipgeolocation.io/ipgeo?apiKey=bb18ac49ab454a75a50dd215bdf1b1f3&fields=time_zone&ip=' . $ip;

		$response = wp_remote_get(
			$resource,
			array(
				'timeout' => 120,
			)
		);

		if ( ! isset( $response['body'] ) ) {
			return '';
		}

		$timezone_object = json_decode( $response['body'], true );

		if ( isset( $timezone_object['time_zone']['is_dst'] ) ) {

			return $timezone_object['time_zone']['is_dst'];

		}

		return '';
	}

	/**
	 * Return a string address with its components formatted to be used by the google address api.
	 *
	 * @param string $address The address component.
	 * @param string $city The city address component.
	 * @param string $state The state address component.
	 * @param string $zip The address zip code.
	 * @param string $country The country.
	 * @param string $url_formatted If the output will be oriented to url or not (default true).
	 *
	 * @return string
	 */
	public function format_address( $address, $city, $state, $zip, $country = '', $url_formatted = true ): string {
		$format = '';

		if ( ! empty( trim( $address ) ) ) {
			$format .= $address . ' ';
		}

		if ( ! empty( trim( $city ) ) ) {
			$format .= $city;
		}

		if ( ! empty( trim( $state ) ) ) {
			$format .= ', ' . $state . ' ';
		}

		if ( ! empty( trim( $zip ) ) ) {
			$format .= ' ' . $zip;
		}

		if ( ! empty( trim( $country ) ) ) {
			$format .= ' ' . $country;
		}

		if ( $url_formatted ) {
			$format = str_replace( ' ', '+', $format );

			$format = str_replace( '#', '', $format );
		}

		return trim( $format );
	}

	/**
	 * Get the weekday index
	 *
	 * @param string $day The week day e.g (Monday).
	 *
	 * @return false|int|string
	 */
	public function index_weekend( $day ) {
		$current_day_index = gmdate( 'w' );
		$day_index         = gmdate( 'w', strtotime( $day ) );
		$week_array_index  = array();

		$cont = 0;
		for ( $i = $current_day_index; $i <= 6; $i++ ) {
			$week_array_index[] = $i;
			++$cont;
		}

		for ( $i = 0; $i < 7 - $cont; $i++ ) {
			$week_array_index[] = $i;
		}

		return array_search( $day_index, $week_array_index, false );
	}

}

/*
UTC : UTC Universal Coordinated Time
Europe/Paris : CET Central European Time
Africa/Cairo : EET Eastern European Time
Indian/Mayotte : EAT Eastern African Time
Asia/Karachi : PLT Pakistan Lahore Time
Asia/Calcutta : IST India Standard Time
Asia/Dhaka : BST Bangladesh Standard Time
:VST Vietnam Standard Time
Asia/Taipei : CTT China Taiwan Time
Asia/Tokyo :JST Japan Standard Time
Australia/South :ACT Australia Central Time
Australia/Tasmania :AET Australia Eastern Time
Antarctica/South_Pole : NST New Zealand Standard Time
:MIT Midway Islands Time
America/Atka : HST Hawaii Standard Time
America/Anchorage : AST Alaska Standard Time
America/Los_Angeles :PST Pacific Standard Time
:PNT Phoenix Standard Time
America/Phoenix :MST Mountain Standard Time
Mexico/General :CST Central Standard Time
America/Toronto : EST Eastern Standard Time
America/Buenos_Aires :AGT Argentina Standard Time
Brazil/East :BET Brazil Eastern Time
Africa/Maputo :CAT Central African Time
*/
