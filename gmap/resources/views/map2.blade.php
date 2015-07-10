<?php
/**
 * Created by PhpStorm.
 * User: pruthvi
 * Date: 8/7/15
 * Time: 6:28 PM
 */
        ?>
<link rel="stylesheet" href="{{ url(elixir("css/all.css")) }}">
<script src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
<div id="map_canvas"></div>
<style>
    #map_canvas {
        border:1px solid black;
        height: 400px;
        width: 100%;
        border-radius: 4px;
    }
</style>

<script src="{{ url(elixir("js/all.js")) }}"></script>
<script type="text/javascript">
    var map;
    var markers = [];

    function init(locations){
        map = new google.maps.Map(document.getElementById('map_canvas'), {
            zoom: 10,
            center: new google.maps.LatLng(parseFloat(locations[0]['latitude']), parseFloat(locations[0]['longitude'])),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        var num_markers = locations.length;
        for (var i = 0; i < num_markers; i++) {
            console.log('',locations[i]);
            markers[i] = new google.maps.Marker({
                position: {lat:parseFloat(locations[i]['latitude']), lng:parseFloat(locations[i]['longitude'])},
                map: map,
                html: locations[i]['name'],
                id: i,
                labelContent: i,
                icon: 'https://chart.googleapis.com/chart?chst=d_map_pin_letter&chld='+ ( i     + 1) +'|FF776B|000000'
                //icon:"http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=A|FF0000|000000"
            });

            google.maps.event.addListener(markers[i], 'click', function(){
                var infowindow = new google.maps.InfoWindow({
                    id: this.id,
                    content:this.html,
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
    $.getJSON("places", function(result){
        var locations = result;
        init(locations);
    });
</script>
