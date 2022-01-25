<script>
	var LOCALISED_VARS = LOCALISED_VARS || {};
	LOCALISED_VARS.env                            = {!! json_encode( WP_ENV ) !!};
	LOCALISED_VARS.ajaxurl                        = {!! json_encode( admin_url( "admin-ajax.php" ) ) !!};
	LOCALISED_VARS.ajaxnonce                      = {!! json_encode( wp_create_nonce( "ajax_nonce" ) ) !!};
	LOCALISED_VARS.stylesheet_directory_uri       = {!! json_encode( get_stylesheet_directory_uri() ) !!};
	LOCALISED_VARS.siteUrl                        = {!! json_encode( home_url()) !!};
</script>