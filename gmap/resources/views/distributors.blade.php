<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <link rel="stylesheet" href="{{ url(elixir("css/all.css")) }}" />
    <style>
        .mapContainer {position:relative;}
        .container-fluid{
            height: 400px;
            z-index: 9;
            position:relative;
            /*top: 20px;*/

        }
        .container{
            height: 800px;
            width:100%;

            position:absolute;
            /*top:0;*/
        }
        .collapse-header {font-weight: bold;}
        .container-fluid .header{font-size: 1.5em;}
        .collapse-content{overflow-y: scroll;overflow-x: hidden;max-height: 100%;}
    </style>
    <script src="http://maps.googleapis.com/maps/api/js?sensor=false&libraries=places"></script>
</head>

<body>

<div class="mapContainer">
    <div id="map_canvas" class="container">{{--container to locate all distributors--}}</div>

    <div class="collapse container-fluid" id="directionDiv">
        <div class="alert alert-info">
            <a href="#" class="close" aria-label="close">&times;</a>
            <div class="collapse-header">Location</div>
            <hr class="divider">
            {{--<div id="directionHeading" class="heading"></div>--}}
            <div id="collapse-contnet" class="collapse-content">
                <div id="dvDistance">
                </div>

                <div class="row">
                    <div class="col-xs-6" id="dvMap" style="width: 500px; height: 500px">
                        {{--container for map--}}
                    </div>
                    <div class="col-xs-6" id="dvDirection" style="width: 500px; height: 500px">
                        {{--container for direction--}}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<script src="{{ url(elixir("js/all.js")) }}"></script>
<script type="text/javascript">
    var locations = [];
    var map;
    var markers = [];
    var source, destination;

    source = 'vashi railway station';
    $('.close').click(function(){
        $("#directionDiv").collapse('hide');
        init(locations);
    });

    function init(locations){
        map = new google.maps.Map(document.getElementById('map_canvas'), {
            zoom: 10,
            center: new google.maps.LatLng(parseFloat(locations[0]['latitude']), parseFloat(locations[0]['longitude'])),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        //********* PLOT MARKERS **********************//
        var num_markers = locations.length;
        for (var i = 0; i < num_markers; i++) {
            markers[i] = new google.maps.Marker({
                position: {lat:parseFloat(locations[i]['latitude']), lng:parseFloat(locations[i]['longitude'])},
                map: map,
                html: locations[i]['name'],
                type:locations[i]['type'],
                id: i,
                labelContent: i,
                icon: 'https://chart.googleapis.com/chart?chst=d_map_pin_letter&chld='+ ( i     + 1) +'|FF776B|000000'
                //icon:"http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=A|FF0000|000000"
            });


            google.maps.event.addListener(markers[i], 'click', function(){
                var infowindow = new google.maps.InfoWindow({
                    maxWidth: 200,
                    id: this.id,
                    content:"<strong>Location</strong><br>"+this.html+"<hr><button class='btn btn-primary' onclick='getDirection("+this.id+")'>Get Direction</button>",
                    position:this.getPosition()
                });
                google.maps.event.addListenerOnce(infowindow, 'closeclick', function(){
                    markers[this.id].setVisible(true);
                });
                this.setVisible(false);
                infowindow.open(map);
            });
        }
    }
    function getDirection(index)
    {
        destination = new google.maps.LatLng(locations[index]['latitude'], locations[index]['longitude']);
        $("#directionDiv").collapse('show');

        GetRoute();
    }

    $.getJSON("places", function(result){
        locations = result;
        init(locations);
    });

    //create object for direction service
    var directionsService = new google.maps.DirectionsService();

    //render direction with draggable ends
    var directionsDisplay = new google.maps.DirectionsRenderer({ 'draggable': true });

    // display routes i.e map and direction with destination and source
    function GetRoute() {
        // set initial map and direction route panel
        var mumbai = new google.maps.LatLng(19.100105, 73.005210);

        var mapOptions = {
            zoom: 7,
            center: mumbai
        };

        map = new google.maps.Map(document.getElementById('dvMap'), mapOptions);
        directionsDisplay.setMap(map);
        directionsDisplay.setPanel(document.getElementById('dvDirection'));

        //*********DIRECTIONS AND ROUTE**********************//
        //set source and destination
        var request = {
            origin: source,
            destination: destination,
            travelMode: google.maps.TravelMode.DRIVING
        };

        //set direction for given request
        directionsService.route(request, function (response, status) {
            if (status == google.maps.DirectionsStatus.OK) {
                directionsDisplay.setDirections(response);
            }
        });

        //*********DISTANCE AND DURATION**********************//
        var service = new google.maps.DistanceMatrixService();
        service.getDistanceMatrix({
            origins: [source],
            destinations: [destination],
            travelMode: google.maps.TravelMode.DRIVING,
            unitSystem: google.maps.UnitSystem.METRIC,
            avoidHighways: false,
            avoidTolls: false
        }, function (response, status) {
            if (status == google.maps.DistanceMatrixStatus.OK && response.rows[0].elements[0].status != "ZERO_RESULTS") {
                var distance = response.rows[0].elements[0].distance.text;
                var duration = response.rows[0].elements[0].duration.text;

                $('#dvDistance').html(
                        "<strong>Distance: </strong>" + distance + "<br />" +
                        "<strong>Duration: </strong>" + duration
                );

            } else {
                alert("Unable to find the distance via road.");
            }
        });
    }
</script>

</body>
</html>