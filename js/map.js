(function(window, $) {
    "use strict";

    function MapHandler(){
        var self = this;

        self.init();

    }

    MapHandler.prototype = {

        init: function () {
            var self = this;
            var map = null;
        },
        markers: [],
        initMap(){
            var self = this;
            if(!window.ffl_lon || !window.ffl_lat){
                window.ffl_lat = 38.2879632;
                window.ffl_lon = -98.0370821;
            }

            /*$.getJSON("https://api.ipify.org?format=json", function(dataIP) {
                $.getJSON("https://ipinfo.io/"+dataIP.ip+"/json", function(data) {

                    // Setting text of element P with id
                    console.log(data)
                    $("#client_ip").val(data.timezone);
                });
            });*/

            window.location_selected = 0;
            window.search_text = '';

            if(!document.getElementById("map_id_wrapper")){
                return;
            }

            self.map = new google.maps.Map(document.getElementById("map_id_wrapper"), {
                center: { lat: window.ffl_lat, lng: window.ffl_lon },
                zoom: 4,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                mapTypeControl: false,
                fullscreenControl: false,
                streetViewControl: false,
                style : [{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#343e56"}]},{"featureType":"administrative","elementType":"labels.text.stroke","stylers":[{"visibility":"off"}]},{"featureType":"landscape","elementType":"geometry.fill","stylers":[{"color":"#c3ccda"}]},{"featureType":"landscape","elementType":"labels.icon","stylers":[{"color":"#788aa3"}]},{"featureType":"landscape","elementType":"labels.text.fill","stylers":[{"color":"#343e56"}]},{"featureType":"landscape","elementType":"labels.text.stroke","stylers":[{"visibility":"off"}]},{"featureType":"poi","elementType":"geometry.fill","stylers":[{"color":"#c3ccda"}]},{"featureType":"poi","elementType":"labels.icon","stylers":[{"color":"#788aa3"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#343e56"}]},{"featureType":"poi","elementType":"labels.text.stroke","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"geometry.fill","stylers":[{"color":"#aeb9c8"}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"color":"#343e56"}]},{"featureType":"road","elementType":"labels.text.stroke","stylers":[{"visibility":"off"}]},{"featureType":"road.arterial","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"road.highway","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"road.local","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"labels.icon","stylers":[{"color":"#788aa3"}]},{"featureType":"transit","elementType":"labels.text.fill","stylers":[{"color":"#343e56"}]},{"featureType":"transit","elementType":"labels.text.stroke","stylers":[{"visibility":"off"}]},{"featureType":"transit.line","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"geometry.fill","stylers":[{"color":"#788aa3"}]},{"featureType":"water","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"labels.text.stroke","stylers":[{"visibility":"simplified"}]}],
            });

            $(document).on('click', '.view-more-trigger', function(){
                var id = $(this).data('id');
                var $section = $('.section-' + id);
                var $view_more = $('.view-more-' + id+ ' i');
                if($section.hasClass('section-hidden')){
                    $section.removeClass('section-hidden');
                    $view_more.removeClass('fa-caret-up').addClass('fa-caret-down');
                }else{
                    $section.addClass('section-hidden');
                    $view_more.removeClass('fa-caret-down').addClass('fa-caret-up');
                }
            });

            $('.advanced_search').on('click', function(){
                if($(this).hasClass('hidden-zone')){
                    $('#advanced_filters').css('display', 'flex');
                    $(this).removeClass('hidden-zone');
                    $('.advanced_search i').removeClass('fa-caret-up').addClass('fa-caret-down');
                }else{
                    $('#advanced_filters').css('display', 'none');
                    $(this).addClass('hidden-zone');
                    $('.advanced_search i').removeClass('fa-caret-down').addClass('fa-caret-up');
                }
            });

            $('#reset_filter_btn').on('click', function(){
                window.Kgm.MapHandler.resetSearch();
            });

            $('#meeting_miles').hide();
            $('#user_location').on('change', function(){
                if($(this).is(':checked')){
                    window.Kgm.MapHandler.getLocation();
                    $('.user_location_btn').css('background', '#2a548b');
                    $('.user_location_btn').css('color', 'white');
                    $('#meeting_input_search').hide();
                    $('.autocomplete-input').val('');
                    $('#meeting_miles').show();
                }else{
                    $('.user_location_btn').css('background', 'lightgray');
                    $('.user_location_btn').css('color', 'black');
                    $('#meeting_input_search').show();
                    $('#meeting_miles').hide();
                }
            });



            $(document).on('click', 'button.update', function(){
                swal("Please only continue if you are the designated group contact that has permission from the group to update", {
                    buttons: {
                        cancel: "Cancel",
                        catch: {
                            text: "Continue",
                            value: "yes",
                        },
                    },
                }).then((value) => {
                    switch (value) {

                        case "yes":
                            window.open('https://form.jotform.com/222725214556050', '_blank') ;
                            break;
                        default:
                    }
                });
            });

            self.getMeetingList({ id : '', action: 'meeting_areas_load_meetings'});
            window.Kgm.MapHandler.loadMarkersAjax(self.map, {});

            //self.loadMarkersAjax(self.map, { id : '' });
            $('.meeting-mask').css('display', 'none');

            window.Kgm.MapHandler.getLocation();

            // load meetings in location
            $('#get_meeting_btn').click(function() {
                /* console.log('location_selected: ' + window.location_selected);
                console.log('Old serach: ' + window.search_text);
                console.log('New search: ' + $('.autocomplete-input').val());
                console.log('New search: ' + window.ffl_lat);
                console.log('New search: ' + window.ffl_lon);
                console.log('State: ' + $('#user_city_state').val()); */
                if(window.search_text != $('.autocomplete-input').val() && window.location_selected != 1 && $('.autocomplete-input').val() != ''){
                    swal("Please select an option from the location field!", {
                        buttons: {
                            cancel: "OK",
                        },
                    })
                    $('.meeting-mask').css('display', 'none');
                    return;
                }

                window.search_text = $('.autocomplete-input').val();
                $('.meeting-mask').css('display', 'flex');

                
                var data = {
                    weekday: $('#meeting_week').val(),
                    search: $('.autocomplete-input').val(),
                    type: $('#meeting_type').val(),
                    lang: $('#meeting_lang').val(),
                    city_state: $('#user_city_state').val(),
                    time: $('#meeting_time').val(),
                    special: $('#meeting_special_focus').val(),
                    country: $('#area_meetings').val(),
                   // timezone:  $('#meeting_timezone').val(),
                    lat: window.ffl_lat,
                    lng: window.ffl_lon,
                    user_location: $('#user_location').is(':checked') ? 1 : 0,
                    miles: $('#meeting_miles').val(),
                    location_selected: window.location_selected,
                    client_ip: $("#client_ip").val()

                };
                window.Kgm.MapHandler.getMeetingList(data);
                window.Kgm.MapHandler.loadMarkersAjax(self.map, data);
            });

            $('body').keypress(function (e) {
                var key = e.which;
                if(key == 13)  // the enter key code
                {
                    $('#get_meeting_btn').trigger('click');
                    return false;
                }
            });

            $('.meeting_map').css('display', 'none');

            $('.list-options span').click(function(){
                if($(this).hasClass('list')){
                    $('.list-options span.list').addClass('active');
                    $('.list-options span.map').removeClass('active');
                    $('.meeting_map').css('display', 'none');
                    $('.results-wrapper').css('display', 'block');
                }else{
                    $('.list-options span.map').addClass('active');
                    $('.list-options span.list').removeClass('active');
                    $('.meeting_map').css('display', 'block');
                    $('.results-wrapper').css('display', 'none');
                }
            });

        },
        getMeetingList(data){
            data.action = 'meeting_areas_load_meetings';
            $.post( parameters.ajax_url, data, function( result ) {
                $( ".results-wrapper" ).html( result );

                // window.Kgm.MapHandler.resetSearch();

                if($('.cont_special_meeting').val() == 0){
                    $('.results-wrapper .special-meeting-heading').css('display', 'none');

                }
                $('.meeting-mask').css('display', 'none');
                /*window.ffl_lat = '';
                window.ffl_lon = '';*/
            });
        },
        resetSearch(){
            $('.autocomplete-input').val('');
            $('#meeting_type').val('');
            $('#meeting_week').val('');
            $('#meeting_time').val('');
            $('#user_city_state').val('');
            //$('#meeting_timezone').val('');
            $('#meeting_lang').val('');
            $('#area_meetings').val('');
            $('#meeting_special_focus').val('any');
            if($('#user_location').is(':checked')){
                $('#user_location').trigger('click');
            }
        },
        loadMarkersAjax(map, data) {
            var self = this;
            data.action = 'get_meetings';
            $.ajax( {
                type: 'POST',
                url:  parameters.ajax_url,
                data : data,
                dataType: "json",
                beforeSend: function () {
                    $('#map_id_wrapper').hide();
                    $('.mask_map').show();
                },
                complete: function () {
                    $('#map_id_wrapper').show();
                    $('.mask_map').hide();
                },
                success: function (response) {
                    var locators = [];
                    /*window.ffl_lat = '';
                    window.ffl_lon = '';*/
                    window.location_selected = 0;
                    if(response.success){
                        var bounds = new google.maps.LatLngBounds();
                        var infoWindows = [];

                        var infowindow = new google.maps.InfoWindow();
                        var marker, i, bg_image;
                        var l = [];

                        var locators = response.meetings;

                        let cont = 0;
                        window.Kgm.MapHandler.deleteMarkers();
                        for (i = 0; i < locators.length; i++) {
                            let locator = locators[i];
                            if(locator.lat !== undefined && locator.lat !== '' && locator.lng !== undefined && locator.lng !== ''){

                                var zipcode = locator.zipcode;
                                var latLng = {lat:parseFloat(locator.lat), lng: parseFloat(locator.lng)};
                                map.setCenter(latLng);
                                var marker = new google.maps.Marker({
                                    map: map,
                                    position: latLng
                                });

                                google.maps.event.addListener(marker, 'click', (function(marker, locator) {
                                    let email = locator.email ? '<p><i class="fa fa-envelope" aria-hidden="true"></i> '+locator.email+'</p>' : '';
                                    let phone = locator.phone ? '<p><i class="fa phone" aria-hidden="true"></i> '+locator.phone+'</p>' : '';
                                    let time = locator.time && locator.day ? '<i class="fa fa-calendar" aria-hidden="true"></i> '+locator.time+ ' ' +locator.timezone+'</p>' : '';
                                    let group_id = locator.group_id ? '<p><strong>Group: </strong> '+locator.group_id+'</p>' : '';
                                    let info = locator.additional_information ? '<p><strong>Info: </strong> '+locator.additional_information+'</p>' : '';
                                    return function() {
                                        infowindow.setContent('<div  class="infowindow w-inline-block">' +
                                            '<div class="map-card-text-wrap">' +
                                            '<p><strong class="dark-blue-text no-margin">'+locator.name+'</strong></p>' +
                                            '<p><i class="fa fa-map-marker" aria-hidden="true"></i> '+locator.address + ' '  + locator.city+', '+ locator.state + '</p>' +
                                            phone +
                                            email +
                                            time +
                                            group_id +
                                            info +
                                            '</div>' +
                                            '</div>');

                                        infowindow.open(map, marker);
                                    }
                                })(marker, locator));

                                bounds.extend(marker.position);
                                window.Kgm.MapHandler.markers.push(marker);

                            }

                        }

                        if(locators.length > 0){
                            // $('.meeting_map').css('display', 'block');
                            map.setZoom(12);
                            map.panTo({lat:parseFloat(locators[0].lat), lng: parseFloat(locators[0].lng)});
                        }else{
                            //$('.meeting_map').css('display', 'none');
                        }

                    }

                },
                error : function(jqXHR, exception){
                    var msg = '';
                    if (jqXHR.status === 0) {
                        msg = 'Not connect.\n Verify Network.';
                    } else if (jqXHR.status == 404) {
                        msg = 'Requested page not found. [404]';
                    } else if (jqXHR.status == 500) {
                        msg = 'Internal Server Error [500].';
                    } else if (exception === 'parsererror') {
                        msg = 'Requested JSON parse failed.';
                    } else if (exception === 'timeout') {
                        msg = 'Time out error.';
                    } else if (exception === 'abort') {
                        msg = 'Ajax request aborted.';
                    } else {
                        msg = 'Uncaught Error.\n' + jqXHR.responseText;
                    }
                }

            });
        },
        getLocation(){
            var self = this;
            /*navigator.geolocation.watchPosition(function(position) {
                    console.log("i'm tracking you!");
                },
                function(error) {
                    if (error.code == error.PERMISSION_DENIED){
                        alert('Sanon.org need access to your location in order to use this feature, please enable it ')
                        return true;
                    }

                });*/
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(self.showPosition);
            }else {
                alert("In order to get your current location you must select allow, in case you did it your browser do not support location then");
            }

        },
        showPosition(position) {
            window.ffl_lat = position.coords.latitude;
            window.ffl_lon = position.coords.longitude;
        },
        calcCrow(lat1, lon1, lat2, lon2)
        {
            var self = this;
            var R = 6371;
            var dLat = self.toRad(lat2-lat1);
            var dLon = self.toRad(lon2-lon1);
            var lat1 = self.toRad(lat1);
            var lat2 = self.toRad(lat2);

            var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                Math.sin(dLon/2) * Math.sin(dLon/2) * Math.cos(lat1) * Math.cos(lat2);
            var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            var d = R * c;
            return d;
        },
        toRad(value)
        {
            return value * Math.PI / 180;
        },
        setMapOnAll(map) {
            for (let i = 0; i < window.Kgm.MapHandler.markers.length; i++) {
                window.Kgm.MapHandler.markers[i].setMap(map);
            }
        },
        hideMarkers() {
            window.Kgm.MapHandler.setMapOnAll(null);
        },
        showMarkers(map) {
            window.Kgm.MapHandler.setMapOnAll(map);
        },
        deleteMarkers(){
            window.Kgm.MapHandler.hideMarkers();
            window.Kgm.MapHandler.markers = [];
        }
    }

    window.Kgm = window.Kgm || {};
    window.Kgm.MapHandler = new MapHandler();

    $(window).ready(function(){
        window.Kgm.MapHandler.initMap();

        var input = document.getElementById('meeting_input_search');

        if(!input){
            return;
        }

        //var autocomplete = new google.maps.places.Autocomplete(input);  
        var autocomplete = new google.maps.places.Autocomplete(input, { types: ['(regions)'] });      
        //var autocomplete = new google.maps.places.Autocomplete(input, { types: ['(cities)'] });
        autocomplete.addListener('place_changed', function () {

            var place = autocomplete.getPlace();
            if(place.geometry){
                window.ffl_lat = place.geometry['location'].lat();

                window.ffl_lon = place.geometry['location'].lng();

                //console.log(window.ffl_lat,window.ffl_lon)

                window.location_selected = 1;

                $('#search_location_notice').css('display','block');

            }



        });
    });

})(window, window.jQuery);
