<?php get_header(); ?>

<?php get_template_part('template-parts/element', 'page-header'); ?>

<?php if (cpotheme_show_posts()) : ?>
	<div id="main" class="main">
		<div class="container">
			<section id="content" class="content">
				<?php do_action('cpotheme_before_content'); ?>
			<?php if (have_posts()) while (have_posts()) : the_post(); ?>
				<?php get_template_part('template-parts/element', 'blog'); ?>
						<?php endwhile; ?>
			<?php cpotheme_numbered_pagination(); ?>
			<?php do_action('cpotheme_after_content'); ?>
			</section>
			<?php get_sidebar(); ?>
			<div class="clear"></div>
		</div>
	</div>
<?php endif; ?>
<!-- google maps -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA8copdEaCILuc-bDfVql-PIxN5X8E1A1Y&callback=initialize" async defer></script>
<script>
    function initialize() {
        var mapCanvas = document.getElementById('map');
        var mapOptions = {
            center: new google.maps.LatLng(-31.680953, -63.883693),
            zoom: 10,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            styles: [{
                    "elementType": "geometry",
                    "stylers": [{
                        "color": "#f5f5f5"
                    }]
                },
                {
                    "elementType": "labels.icon",
                    "stylers": [{
                        "visibility": "off"
                    }]
                },
                {
                    "elementType": "labels.text.fill",
                    "stylers": [{
                        "color": "#616161"
                    }]
                },
                {
                    "elementType": "labels.text.stroke",
                    "stylers": [{
                        "color": "#f5f5f5"
                    }]
                },
                {
                    "featureType": "administrative.land_parcel",
                    "elementType": "labels.text.fill",
                    "stylers": [{
                        "color": "#bdbdbd"
                    }]
                },
                {
                    "featureType": "poi",
                    "elementType": "geometry",
                    "stylers": [{
                        "color": "#eeeeee"
                    }]
                },
                {
                    "featureType": "poi",
                    "elementType": "labels.text.fill",
                    "stylers": [{
                        "color": "#757575"
                    }]
                },
                {
                    "featureType": "poi.park",
                    "elementType": "geometry",
                    "stylers": [{
                        "color": "#e5e5e5"
                    }]
                },
                {
                    "featureType": "poi.park",
                    "elementType": "labels.text.fill",
                    "stylers": [{
                        "color": "#9e9e9e"
                    }]
                },
                {
                    "featureType": "road",
                    "elementType": "geometry",
                    "stylers": [{
                        "color": "#ffffff"
                    }]
                },
                {
                    "featureType": "road.arterial",
                    "elementType": "labels.text.fill",
                    "stylers": [{
                        "color": "#757575"
                    }]
                },
                {
                    "featureType": "road.highway",
                    "elementType": "geometry",
                    "stylers": [{
                        "color": "#dadada"
                    }]
                },
                {
                    "featureType": "road.highway",
                    "elementType": "labels.text.fill",
                    "stylers": [{
                        "color": "#616161"
                    }]
                },
                {
                    "featureType": "road.local",
                    "elementType": "labels.text.fill",
                    "stylers": [{
                        "color": "#9e9e9e"
                    }]
                },
                {
                    "featureType": "transit.line",
                    "elementType": "geometry",
                    "stylers": [{
                        "color": "#e5e5e5"
                    }]
                },
                {
                    "featureType": "transit.station",
                    "elementType": "geometry",
                    "stylers": [{
                        "color": "#eeeeee"
                    }]
                },
                {
                    "featureType": "water",
                    "elementType": "geometry",
                    "stylers": [{
                        "color": "#c9c9c9"
                    }]
                },
                {
                    "featureType": "water",
                    "elementType": "labels.text.fill",
                    "stylers": [{
                        "color": "#9e9e9e"
                    }]
                }
            ]

        }

        var map = new google.maps.Map(mapCanvas, mapOptions)

        var pos = new google.maps.LatLng(-31.680953, -63.883693);
        var marker = new google.maps.Marker({
            position: pos,
            map: map,
            title: "Zenón Lopez 1200 esq. 9 de Julio. Pilar, Córdoba, Argentina. CP: 5972",
            animation: google.maps.Animation.DROP

        });
        //marker.setIcon('http://www.metalurgicacf.com.ar/wp-content/uploads/2019/03/marcadormapa.png');
    }
    google.maps.event.addDomListener(window, 'load', initialize);
</script>

<div id="map" style="width: 100%; height: 500px;"></div>



<!-- ventana modal publicidad -->
<script>
	jQuery(document).ready(function() {
		jQuery('#myModal').modal('toggle')
	});
</script>

<!-- Modal HTML -->
<div id="myModal" class="modal fade bd-example-modal-lg">
	<div class="modal-dialog modal-login modal-lg">
		<div class="modal-content">

			<div class="modal-body">
				<?php
				// ARGUMENTOS A MOSTRAR
				$args = array(
					'post_type' => 'post',
					'category_name' => 'promos',
					'orderby' => 'ID',
					'order'   => 'DESC',
					'posts_per_page' => '1',
					/*array(
													array(
														'taxonomy' => 'people',
														'field'    => 'slug',
														'terms'    => 'bob',
													),*/
				);

				// The Query
				$the_query = new WP_Query($args);

				// The Loop
				if ($the_query->have_posts()) {
						while ($the_query->have_posts()) {
								$the_query->the_post();
								the_post_thumbnail('full');
								?>
								</div>
								<div class="modal-footer">
									<?php
								echo '<p>' . the_excerpt() . '</p><button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>';
							}
					} else {
						// no posts found
					}
				/* Restore original Post Data */
				wp_reset_postdata();
				?>
			</div>
		</div>
	</div>
</div>
<!-- FIN ventana modal publicidad -->

<?php get_footer(); ?>