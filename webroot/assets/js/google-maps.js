/*global jQuery, GoogleMapsApiKey, google, MarkerClusterer */
/*jslint devel: true, browser: true */
(function ($) {
    "use strict";

    var GoogleMaps = {
        markerCluster  : '',
        mapsContainer  : '.map',
        mapsMarker     : '.marker',
        zoom           : 10,
        clusterIcon    : '/assets/img/map/cluster.png',
        clusterSize    : 25,
        clusterMaxZoom : 6,

        init : function () {
            var self = this;

            if (null === GoogleMapsApiKey) {
                return;
            }

            if ($(self.mapsContainer).length) {
                $.getScript('http://maps.google.com/maps/api/js?sensor=false&language=nl&key=' + GoogleMapsApiKey, function () {
                    self.renderMaps();
                });
            }
        },

        renderMaps : function () {
            var self = this;

            $(self.mapsContainer).each(function () {
                var container = $(this),
                    markers = $(self.mapsMarker, container),
                    options = {
                        zoom: self.zoom,
                        center: new google.maps.LatLng(53.176194, 6.182732),
                        mapTypeId: google.maps.MapTypeId.ROADMAP,
                        scrollwheel: false,
                        disableDefaultUI: true,
                        zoomControl: true,
                        panControl: true,
                        streetViewControl: true,
                        navigationControlOptions: {
                            style: google.maps.NavigationControlStyle.ZOOM_PAN
                        }
                    },
                    mcOptions = {
                        gridSize: self.clusterSize,
                        maxZoom: self.clusterMaxZoom,
                        styles: [{
                            url: self.clusterIcon,
                            width: 66,
                            height: 66,
                            textColor: 'white'
                        }]
                    },
                    map = new google.maps.Map(container[0], options),
                    window = null,
                    boundaries = new google.maps.LatLngBounds();

                map.markers = [];

                markers.each(function () {
                    var marker = $(this),
                        markerOptions = {
                            position: new google.maps.LatLng(marker.attr('data-lat'), marker.attr('data-lng')),
                            map: map,
                            icon: marker.attr('data-icon')
                        },
                        mapMarker = new google.maps.Marker(markerOptions);

                    map.markers.push(mapMarker);

                    if (marker.html()) {
                        window = new google.maps.InfoWindow({
                            content: marker.html()
                        });

                        mapMarker.addListener('click', function () {
                            window.close();
                            window.open(map, mapMarker);
                        });

                        map.addListener('zoom_changed', function () {
                            window.close();
                        });
                    }
                });

                self.markerCluster = new MarkerClusterer(map, map.markers, mcOptions);

                /*jslint unparam: true*/
                $.each(map.markers, function (i, marker) {
                    var location = new google.maps.LatLng(marker.position.lat(), marker.position.lng());
                    boundaries.extend(location);
                });
                /*jslint unparam: false*/

                map.setCenter(boundaries.getCenter());
                map.setZoom(self.zoom);

                if (map.markers.length !== 1) {
                    map.fitBounds(boundaries);
                }
            });
        }
    };

    $(document).ready(function () {
        GoogleMaps.init();
    });
}(jQuery));
