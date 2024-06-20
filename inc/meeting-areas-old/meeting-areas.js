jQuery(function($) {
	// country toggles
	$('#meetings-listing .header [data-toggle-country]').click(function() {
		var id = $(this).data('toggle-country');
		if($(this).hasClass('active')) return;
		$('#meetings-listing .header [data-toggle-country]').removeClass('active').filter(this).addClass('active');
		$('#meetings-listing .toggle-wrapper .section').removeClass('active').filter('[data-country-id="' + id + '"]').addClass('active');
		$( "#meetings-listing .results-wrapper" ).html('');
	});

	// load meetings in location
	$('#meetings-listing [data-load-meetings-by-area]').click(function() {

		var url = meeting_areas_cfg.ajax_url,
		data = {
			action: 'meeting_areas_load_meetings',
			id: $(this).data('load-meetings-by-area')
		};

		// exit if already loading
		if($( "#meetings-listing .results-wrapper .preloader").length > 0) return;

		// activate loader state
		$( "#meetings-listing .results-wrapper" ).html('<p class="text-center preloader"><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i></p>');

		
		$('html,body').animate({
			scrollTop: $(".sanon-city-result").offset().top},
			'slow');
		

		// query the results
		$.post( url, data, function( result ) {
		  $( "#meetings-listing .results-wrapper" ).html( result );

		  // if there is city filter, activate it
		  if($('#meetings-listing .city-filter').length > 0) {
		  	// hide all results
		  	$('#meetings-listing .meeting').hide();

		  	// activate city filter items
		  	$('#meetings-listing .city-filter .city').on('click', function() {
		  		$('#meetings-listing .meeting').hide().filter('[data-city="' + $(this).data('filter') + '"]').fadeIn(400);

				$('html,body').animate({
					scrollTop: $(".meeting:visible").offset().top},
					'slow');
		  	});
		  }
		});
	});
});
