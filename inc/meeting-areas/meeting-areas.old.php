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
		wp_register_style( 'meeting-areas', get_stylesheet_directory_uri() . '/inc/meeting-areas/meeting-areas.css', [], '1.0.2', 'all' );
		wp_register_script( 'meeting-areas', get_stylesheet_directory_uri() . '/inc/meeting-areas/meeting-areas.js', ['jquery'], '1.0.5', 'all' );

		// localize
		wp_localize_script( 'meeting-areas', 'meeting_areas_cfg', [
			'ajax_url' => admin_url( 'admin-ajax.php' ),
		] );
	}

	/**
	 * Enqueue assets into page
	 */
	protected function enqueueAssets() {
		wp_enqueue_style( 'meeting-areas' );
		wp_enqueue_script( 'meeting-areas' );
	}

	/**
	 * Listing meetings template
	 */
	public function meetingsListingShortcode() {
		// enqueue scripts and styles
		$this->enqueueAssets();

		$countries = $this->getHierarchicalAreas();
		
		ob_start(); ?>
		<div id="meetings-listing">
			<ul class="header">
				<?php foreach($countries as $k => $country): ?>
					<li
						data-toggle-country="<?php echo $country->term_id; ?>"
						<?php if(empty($country->children)): ?> data-load-meetings-by-area="<?php echo $country->term_id; ?>" <?php endif; ?>
					><?php echo $country->name; ?></li>
				<?php endforeach; ?>
			</ul>

			<div class="toggle-wrapper">
				<?php foreach($countries as $k=> $country): ?>
					<div class="section <?php if($k === 0) echo 'active'; ?>" data-country-id="<?php echo $country->term_id; ?>">
						<h2><?php echo $country->name; ?></h2>

						<?php if($country->description): ?>
							<div class="description">
								<?php echo $country->description; ?>
							</div>
						<?php endif; ?>

						<?php // show states if any
						if($country->children): ?>
							<h2 class="meeting-heading">Choose below to narrow down meetings:</h2>
							<?php if ($country->term_id == 294 ){
								$langclass = "languages";
							}else{
								$langclass = "";
							}
							?>
							<div class="state-list">
								<?php foreach($country->children as $state): ?>
									<span class="state <?php echo $langclass; ?>" data-load-meetings-by-area="<?php echo $state->term_id; ?>"><?php echo $state->name; ?></span><br>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
				<div class="weeks" style="display:none;">
					<h2 class="meeting-heading">Find your meeting:</h2>
					<span class="weekday" id="Sunday" > Sunday </span> 
					<span class="weekday" id="Monday"> Monday </span>
					<span class="weekday" id="Tuesday"> Tuesday </span>
					<span class="weekday" id="Wednesday"> Wednesday </span>
					<span class="weekday" id="Thursday"> Thursday </span>
					<span class="weekday" id="Friday"> Friday </span>
					<span class="weekday" id="Saturday"> Saturday </span>
				</div>
			</div>
			<div class="sanon-city-result"></div>
			<div class="results-wrapper"></div>
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
		    'hide_empty' => 'true',
			'orderby'  => 'id',
          	'order'    => 'ASC'
		]);

		$areas_hierarchy = [];
		$this->sortTermsHierarchicaly($areas, $areas_hierarchy);

		return $areas_hierarchy;
	}


	/**
	 * Load Meetings and output them for ajax call
	 */
	public function ajaxLoadMeetings()
	{
		$id = $_POST['id'];
		if(!$id) die('Error: cannot find area ID.');
		if(isset($_POST['weekday']) && !empty($_POST['weekday'])) {

			$the_query = new WP_Query( array(
				'post_type' => 'meetings',
				'nopaging' => true,
				'meta_query' => array(
					array(
						'key'     => 'day',
						'value'   => $_POST['weekday'],
						'compare' => 'LIKE',
					)),
				'tax_query' => [
					[
						'taxonomy' => 'area',
						'field' => 'term_id',
						'terms' => $id,
					]
				],
				'order_by' => 'meta_value',
				'meta_key' => 'group_id',
				'order' => 'ASC',
			) );


		} else {
			$the_query = new WP_Query( array(
				'post_type' => 'meetings',
				'nopaging' => true,
				'tax_query' => [
					[
						'taxonomy' => 'area',
						'field' => 'term_id',
						'terms' => $id,
					]
				],
				'order_by' => 'meta_value',
				'meta_key' => 'group_id',
				'order' => 'ASC',
			) );
		}

		

		// prepare var to hold meetings output and cities
		$meetings_html = '';
		$cities = [];

		while ( $the_query->have_posts() ) : $the_query->the_post();
			// generate cities list
			$city = get_field('city');
			if($city) {
				$city_slug = sanitize_title_with_dashes( $city );
				$cities[$city_slug] = $city;
			} else {
				$city_slug = 'other';
				$cities['other'] = 'Other';
			}

			// append meeting detail to $meetings_html
			ob_start();
			?>
				<div class="meeting" data-city="<?php echo $city_slug; ?>">
					<div class="row">
						<div class="col-md-7 col-lg-8">
							<h5 class="title"><?php the_title(); ?></h5>
						</div>
						<div class="col-md-5 col-lg-4 date-time">
							<?php if(get_field('day') || get_field('time')): ?>
								<i class="fa fa-calendar"></i>
								<?php the_field('day') ?>
								<?php the_field('time') ?>
							<?php endif; ?>
							<?php if ($meetings_type = get_field('meetings_type')): ?>
								<br><span class="label">Meetings:</span><strong><?php echo $meetings_type; ?></strong>
							<?php endif; ?>
						</div>
					</div>
					<div class="row">
						<div class="col-md-7 col-lg-8">
							<?php if(get_field('type') == 'in_person'): ?>
								<?php if ($groupId = get_field('group_id')): ?>
									<span class="label">Group ID:</span><strong><?php echo $groupId; ?></strong><br>
								<?php endif; ?>
								<?php if($facility = get_field('facility')): ?>
									<span class="label">Facility:</span><strong><?php echo $facility; ?></strong><br>
								<?php endif; ?>
								<?php if($address = get_field('address')): ?>
									<span class="label">Address:</span><strong><?php echo $address; ?></strong><br>
								<?php endif; ?>
								<?php if($city = get_field('city')): ?>
									<span class="label">City:</span><strong><?php echo $city; ?></strong><br>
								<?php endif; ?>
								<?php if($state = get_field('state')): ?>
									<span class="label">State:</span><strong><?php echo $state; ?></strong><br>
								<?php endif; ?>
								<?php if($zip = get_field('zip')): ?>
									<span class="label">Zip code:</span><strong><?php echo $zip; ?></strong><br>
								<?php endif; ?>
								<?php if($country = get_field('country')): ?>
									<span class="label">Country:</span><strong><?php echo $country; ?></strong><br>
								<?php endif; ?>

							<?php elseif(get_field('type') == 'phone_web'): ?>
								<?php if($language = get_field('language')): ?>
									<span class="label">Language:</span><strong><?php echo $language; ?></strong><br>
								<?php endif; ?>
								<?php if($phone_web = get_field('phone_web')): ?>
									<span class="label">Type:</span><strong><?php echo $phone_web; ?></strong><br>
								<?php endif; ?>
							<?php endif; ?>

							<?php if($additional_information = get_field('additional_information')): ?>
								<span class="additional_information"><?php echo $additional_information; ?></span>
							<?php endif; ?>
						</div>
						<div class="col-md-5 col-lg-4 contacts">
							<?php if($email = get_field('email')): ?>
								<div><i class="fa fa-envelope-o"></i> <a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a></div>
							<?php endif; ?>
							<?php if($phone = get_field('phone')): ?>
								<div><i class="fa fa-phone"></i> <?php echo $phone; ?></div>
							<?php endif ?>
							<?php if($website = get_field('website')): ?>
								<div><i class="fa fa-globe"></i> <a class="external_link" href="<?php echo $website; ?>" target="_blank" rel="nofollow"><?php echo $website; ?></a></div>
							<?php endif; ?>
						</div>
					</div>
					<div class="meeting-content">
						<?php the_content(); ?>
					</div>
				</div>
		    <?php $meetings_html .= ob_get_clean();
		endwhile;



		// render the ouput - header
		if(count($cities) > 1):
			// adjust cities filter - order alphabetically, place Other as last
			asort($cities);
			if(isset($cities['other'])) {
				unset($cities['other']);
				$cities['other'] = 'Other';
			}

			?>
			<h2 class="meeting-heading">Find your meeting:</h2>
			<div class="city-filter">
				<?php foreach($cities as $city_slug => $city): ?>
					<span class="city" data-filter="<?php echo $city_slug; ?>"><?php echo $city; ?></span><br>
				<?php endforeach; ?>
			</div>
		<?php endif;

		// render meetings content
		echo $meetings_html;

		exit();
	}
}
