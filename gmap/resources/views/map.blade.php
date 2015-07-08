<!DOCTYPE html>
<html>
<head>
    <title>Place Autocomplete</title>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <style>
        html, body, #map-canvas {
            height: 100%;
            margin: 0px;
            padding: 0px
        }
        .controls {
            margin: 6px 14px 14px 6px;
            border: 1px solid transparent;
            border-radius: 2px 0 0 2px;
            box-sizing: border-box;
            -moz-box-sizing: border-box;
            height: 32px;
            outline: none;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
            float: left;
        }

        #pac-input {
            text-overflow: ellipsis;
            width: 400px;
        }

        #pac-input:focus {
            border-color: #4d90fe;
        }

        .pac-container {
            font-family: Roboto;
            z-index: 20;
        }

        #type-selector {
            color: #fff;
            background-color: #4d90fe;
            padding: 5px 11px 0px 11px;
        }

        #type-selector label {
            font-family: Roboto;
            font-size: 13px;
            font-weight: 300;
        }

        #map-canvas{
            height: 600px;
            width: 850px;
        }

        .clearfix {
            clear: both;
        }

        .button {
            padding: 4px;
            margin-top: 6px;
            min-width: 100px;
        }

        .modal{
            z-index: 20;
        }

        .modal-backdrop{
            z-index: 10;
        }​

    </style>

    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=places"></script>
    <link rel="stylesheet" href="{{ url(elixir("css/all.css")) }}">
</head>

<body>
<input id="location_type" class="controls" type="text"
       placeholder="Enter a location type i.e Hospital,Hotel,etc" />

<input id="pac-input" class="controls" type="text"
       placeholder="Enter a location">
<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}" />
<button class="btnSavelocation button btn btn-primary">Save</button>
<div class="clearfix"></div>
<div class="alert alert-success fade in">
    {{--<a href="#" class="close" data-dismiss="alert">&times;</a>--}}
    <strong>Success!</strong> Location saved successfully.
</div>

<div class="alert alert-danger fade in">
</div>

<div id="map-canvas"></div>
<input id="latbox" value="" type="hidden">
<input id="lngbox" value="" type="hidden">
{{--<script src="http://code.jquery.com/jquery-2.1.1.js"></script>--}}
<script src="http://code.jquery.com/jquery-2.1.1.js"></script>
{{--   OR    --}}
{{--<script src="{{ url(elixir("js/all.js")) }}"></script>--}}

<script>
    $('.alert').hide();
    function initialize() {
        var mapOptions = {
            center: new google.maps.LatLng(-33.8688, 151.2195),
            zoom: 13,
            zIndex:9999
        };
        var map = new google.maps.Map(document.getElementById('map-canvas'),
                mapOptions);

        var input = /** @type {HTMLInputElement} */(
                document.getElementById('pac-input'));

        var types = document.getElementById('type-selector');
        // map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
        // map.controls[google.maps.ControlPosition.TOP_LEFT].push(types);

        var autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.bindTo('bounds', map);

        var infowindow = new google.maps.InfoWindow();
        var marker = new google.maps.Marker({
            map: map,
            anchorPoint: new google.maps.Point(0, -29),
            zIndex:9999,
            draggable:true
        });

        google.maps.event.addListener(autocomplete, 'place_changed', function() {
            infowindow.close();
            marker.setVisible(false);
            var place = autocomplete.getPlace();
            if (!place.geometry) {
                window.alert("Autocomplete's returned place contains no geometry");
                return;
            }

            $("#latbox").val(place.geometry.location.lat());
            $("#lngbox").val(place.geometry.location.lng());

            // If the place has a geometry, then present it on a map.
            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(17);  // Why 17? Because it looks good.
            }

            marker.setPosition(place.geometry.location);
            marker.setVisible(true);

            var address = '';
            if (place.address_components) {
                address = [
                    (place.address_components[0] && place.address_components[0].short_name || ''),
                    (place.address_components[1] && place.address_components[1].short_name || ''),
                    (place.address_components[2] && place.address_components[2].short_name || '')
                ].join(' ');
            }

            infowindow.setContent(place.formatted_address);//('<div><strong>' + place.name + '</strong><br>' + address);
            infowindow.open(map, marker);
        });

        //drag event of marker
        //change input text and marker's infowindow(tooltip) content
        google.maps.event.addListener(marker, 'dragend', function(event) {
            pos = marker.getPosition();
            geocoder = new google.maps.Geocoder();
            geocoder.geocode({
                        latLng: pos
                    },
                    function(place, status) {
                        //place = place[0];
                        if (status == google.maps.GeocoderStatus.OK)
                        {
                            console.log('',place);
                            infowindow.setContent(place[0].formatted_address);
                            $("#pac-input").val(place[0].formatted_address);
                            $("#latbox").val(event.latLng.lat());
                            $("#lngbox").val(event.latLng.lng());
                        }
                        else
                        {
                            alert('Cannot determine address at this location.');
                        }

                    });
        });
    }

    google.maps.event.addDomListener(window, 'load', initialize);

    /******************** SAVE BUTTON **********************/
    $( ".btnSavelocation" ).click(function() {
//        geocoder = new google.maps.Geocoder();
//        var address = document.getElementById("pac-input").value;
//        geocoder.geocode( { 'address': address}, function(results, status) {
//            if (status == google.maps.GeocoderStatus.OK) {
                data = [];
                $.post('geo_location',{
                    address: $("#pac-input").val(),
                    latitude: $("#latbox").val(),
                    longitude:$("#lngbox").val(),
                    location_type:$('#location_type').val(),
                    _token:$('#_token').val()
                },function(data,status){
                    if(status == 'success') {
                        $('.alert-success').slideDown().delay(1000).slideUp(3000,function(){
                            $('#location_type').val('');
                            $('#pac-input').val('');
                            initialize();
//                        parent.$(".close").trigger('click');
                        });
                    } else {
                        $('.alert-danger').html('Error, Please try again.')
                                .slideDown()
                                .delay(1000)
                                .slideUp();
                    }
                });
//            }
//            else {
//                $('.alert-danger')
//                        .html("Geocode was not successful for the following reason: " + status)
//                        .slideDown()
//                        .delay(1000)
//                        .slideUp();
//            }
//        });
    });

    $('#myModal').on('shown', function () {
        google.maps.event.trigger(map, "resize");
    });
</script>

</body>
</html>