/*
jQuery(function($) {
	$('#meetings-listing .header [data-toggle-country]').click(function() {
		var id = $(this).data('toggle-country');
		if($(this).hasClass('active')) return;
		$('#meetings-listing .header [data-toggle-country]').removeClass('active').filter(this).addClass('active');
		$('#meetings-listing .toggle-wrapper .section').removeClass('active').filter('[data-country-id="' + id + '"]').addClass('active');
		$( "#meetings-listing .results-wrapper" ).html('');
		if(id != 294 ){
			$('#meetings-listing .weeks').hide();
		}
	});

	$('#meetings-listing [data-load-meetings-by-area]').click(function() {

		$('#meetings-listing span.state').removeClass('activearea');
		$(this).addClass('activearea');
		var url = meeting_areas_cfg.ajax_url,
		data = {
			action: 'meeting_areas_load_meetings',
			id: $(this).data('load-meetings-by-area')
		};

		if($( "#meetings-listing .results-wrapper .preloader").length > 0) return;

		$( "#meetings-listing .results-wrapper" ).html('<p class="text-center preloader"><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i></p>');


		if( $(this).hasClass('languages') ){
			$('#meetings-listing .weeks').show();
			$('html,body').animate({
				scrollTop: $(".weeks").offset().top},
				'slow');
		}else{
			$('#meetings-listing .weeks').hide();
			$('html,body').animate({
				scrollTop: $(".sanon-city-result").offset().top},
				'slow');
		}


		$.post( url, data, function( result ) {
		  $( "#meetings-listing .results-wrapper" ).html( result );

		  if($('#meetings-listing .city-filter').length > 0) {
		  	$('#meetings-listing .meeting').hide();

		  	$('#meetings-listing .city-filter .city').on('click', function() {
		  		$('#meetings-listing .meeting').hide().filter('[data-city="' + $(this).data('filter') + '"]').fadeIn(400);

				$('html,body').animate({
					scrollTop: $(".meeting:visible").offset().top},
					'slow');
		  	});
		  }
		});
	});

	$('#meetings-listing span.weekday').click(function() {
		var weekday = $(this).attr('id');

		$('#meetings-listing span.weekday').removeClass('activeday');
		$(this).addClass('activeday');

		var catid = $('#meetings-listing span.activearea').data('load-meetings-by-area')
		var url = meeting_areas_cfg.ajax_url,
		data = {
			action: 'meeting_areas_load_meetings',
			id: catid,
			weekday: weekday
		};

		if($( "#meetings-listing .results-wrapper .preloader").length > 0) return;

		$( "#meetings-listing .results-wrapper" ).html('<p class="text-center preloader"><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i></p>');


		$('html,body').animate({
			scrollTop: $(".weeks").offset().top},
			'slow');


		$.post( url, data, function( result ) {
			$( "#meetings-listing .results-wrapper" ).html( result );

		});



	});

});
*/
