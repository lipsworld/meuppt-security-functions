<?php
// Ativa GZIP na inicialização do WP
add_action('init' ,'meuppt_buffer');
function meuppt_buffer () {
	ob_start('ob_gzhandler');
}
