jQuery(document).ready(function(){
//var quantity= jQuery(".qty").val();

jQuery("#don_amount").attr('maxlength','8');
jQuery("#don_amount").change(function(){
  var donation_amm=jQuery("#don_amount").val();
  jQuery(".qty").attr('type','text');
  jQuery(".qty").attr('value', donation_amm);
  jQuery(".donation-amtspan").text("$"+donation_amm+"");
});

jQuery('.search-wrapper').click(function(){
	jQuery('.search-form-wrapper').slideDown();
	jQuery(".search-form .search-field").focus();
});

jQuery('.search-wrapper-mobile').click(function(){
	jQuery('.search-form-wrapper-mobile').slideToggle();
	jQuery(".search-form-wrapper-mobile .search-field").focus();
});

if (jQuery('body').hasClass('search-results-found')) {
	// Clear the value of the search field
	jQuery('.search-form .search-field').val('');
}


/*
jQuery(".single_add_to_cart_button").click(function(){
  var donation_amm=jQuery("#don_amount").val();
  jQuery(".qty").val(donation_amm);
});
*/


var cart_quantity=jQuery(".qty").val();
jQuery(".price_newcalcu").text("$"+cart_quantity+".00");

// hide shipping lebel form donation Prodcut
var show_shippping=jQuery( ".shipping" ).hasClass( "woocommerce-shipping-totals" );
if(show_shippping==false){
	jQuery(".shipping").css("display","none");

}
jQuery(".order-total th").text("Grand Total Incl. Tax");

//jQuery(".fellowship_registration").append(" <label class=''>Fellowship Registration - S-Anon</label>");

jQuery( "<label class='fellowship_lab'>*Fellowship Registration - S-Anon</label>" ).insertBefore( "#fellowship_registration" );

jQuery( "<label class='check_label'></label>" ).insertBefore( "#literature,#hospitality,#registration,#talent,#temporary,#welcome_table,#meeting" );

jQuery( "<label class='check_label'>Service Opportunities</label>" ).insertBefore( "#general" );


 jQuery("#pa_meal-selection").change(function(){
	var meals=jQuery("#pa_meal-selection option:selected").text();
    if(meals=='None'){
	jQuery(".single_variation_wrap").addClass("hide_price");
	}
	else{
	jQuery(".single_variation_wrap").removeClass("hide_price");
	}

  });

  jQuery(document).ready(function(){
    var header = jQuery(".header-bar-wrapper");
    var mobile_menu = jQuery(".mob-menu-header-holder");
    var stickyOffset = 60;

    jQuery(window).scroll(function() {
      if (jQuery(window).scrollTop() > stickyOffset) {
        header.addClass("sticky");
      } else {
        header.removeClass("sticky");
      }
    });

	jQuery(window).scroll(function() {
		if (jQuery(window).scrollTop() > stickyOffset) {
			mobile_menu.addClass("sticky");
		} else {
			mobile_menu.removeClass("sticky");
		}
	  });	
	jQuery('.search-form-wrapper .close-icons-cls').click(function() {
		jQuery('.search-form-wrapper').slideUp();
	});
  });


	jQuery(document).on('click', '.contacts a.external_link', function (e){
        e.preventDefault();
		var sitelink = jQuery(this).attr('href');
		jQuery('#snon-Modal .gotosite').html('<a target="_blank" href="'+sitelink+'" class="btn btn-primary"> Confirm </a>');
		jQuery('#snon-Modal').css('display', 'block');


    });

	jQuery(document).on('click', 'span.snon-close', function(){
		jQuery('#snon-Modal').css('display', 'none');
	});
	/*jQuery(document).on('select2:open', function(){
		console.log('asdasdasdasd');
		jQuery('.select2-search__field').focus().css({
			'color':'red'
		}).prop('focus', true);
		console.log(jQuery('.select2-search__field'));
	});*/

	jQuery(document).on('select2:open', function() {
		document.querySelector('.select2-search__field').focus();
	});

});

jQuery(document).on('select2:open', function() {
	document.querySelector('.select2-search__field').focus();
});