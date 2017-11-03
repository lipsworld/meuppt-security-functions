<?php /*

**************************************************************************

Plugin Name:  MeuPPT - Funções de Segurança e Otimização
Plugin URI:   https://github.com/lipsworld/meuppt-security-functions
Description:  Inclui uma série de funções para melhorar a segurança da instalação do Wordpress, sem alterações diretas no functions.php.
Version:      1.4.4
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Author:       MeuPPT
Author URI:   http://www.meuppt.pt/
Text Domain:  security-functions-meuppt

**************************************************************************

Copyright (C) 2017 MeuPPT

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

Contribuição do sanitizador de SVG de Daryll Doyle - <https://github.com/darylldoyle/svg-sanitizer>

**************************************************************************/

// Aciona autoupdater a partir do Github e script Safe SVG, para uso seguro de imagens SVG na biblioteca de mídia e edição do Wordpress

include ('updater.php');
include ('safe-svg/safe-svg.php');
include ('safe-htaccess/meuppt-htaccess.php');


if (is_admin()) { 
		$config = array(
			'slug' => plugin_basename(__FILE__),
			'proper_folder_name' => 'meuppt-security-functions', // this is the name of the folder your plugin lives in
			'api_url' => 'https://api.github.com/repos/lipsworld/meuppt-security-functions', // the GitHub API url of your GitHub repo
			'raw_url' => 'https://raw.github.com/lipsworld/meuppt-security-functions/master', // the GitHub raw url of your GitHub repo
			'github_url' => 'https://github.com/lipsworld/meuppt-security-functions', // the GitHub url of your GitHub repo
			'zip_url' => 'https://github.com/lipsworld/meuppt-security-functions/zipball/master', // the zip url of the GitHub repo
			'sslverify' => true, // whether WP should check the validity of the SSL cert when getting an update, see https://github.com/jkudish/WordPress-GitHub-Plugin-Updater/issues/2 and https://github.com/jkudish/WordPress-GitHub-Plugin-Updater/issues/4 for details
			'requires' => '3.0', // which version of WordPress does your plugin require?
			'tested' => '4.8.3', // which version of WordPress is your plugin tested up to?
			'readme' => 'README.md', // which file to use as the readme for the version number
			'access_token' => '', // Access private repositories by authorizing under Appearance > GitHub Updates when this example plugin is installed
		);
		new WP_GitHub_Updater($config);
	}

// Remove WLW Manifest

remove_action('wp_head', 'wlwmanifest_link');


// Impede exibição de versão do WP em META e em RSS

function meuppt_remove_version() {
    return '';
    }
    
add_filter('the_generator', 'meuppt_remove_version');


// Altera mensagens de erro no login que possam fornecer dicas a hackers para uma única mensagem sem especificações

function no_wordpress_errors(){
  return 'Erro na autenticação';
}
add_filter( 'login_errors', 'no_wordpress_errors' );


// Desabilita o método XML-RPC

add_filter('xmlrpc_enabled', '__return_false');


// Remove RSS feed para comentários

remove_action('wp_head', 'feed_links', 2);


// Desabilita configurações para clientes externos do Wordpress, tais como o app para iPhone

remove_action('wp_head', 'rsd_link');


// Desabilita emojis de um modo geral

function disable_wp_emojicons() {
  add_filter( 'tiny_mce_plugins', 'disable_emojicons_tinymce');
  remove_action('admin_print_styles', 'print_emoji_styles');
  remove_action('wp_head', 'print_emoji_detection_script', 1);
  remove_action('admin_print_scripts', 'print_emoji_detection_script');
  remove_action('wp_print_styles', 'print_emoji_styles');
  remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
  remove_filter('the_content_feed', 'wp_staticize_emoji');
  remove_filter('comment_text_rss', 'wp_staticize_emoji'); 
}

function disable_emojicons_tinymce( $plugins ) {
  if (is_array($plugins)) {
    return array_diff($plugins, array('wpemoji'));
  } else {
    return array();
  }
}

add_action('init', 'disable_wp_emojicons');


// Adiciona botões de cor de fundo nas fontes, caracteres especiais

function enable_more_buttons($buttons) {
  $buttons[] = 'backcolor';
  $buttons[] = 'copy';
  $buttons[] = 'charmap';
 return $buttons;
}
add_filter("mce_buttons_2", "enable_more_buttons");


// Desabilita pingbacks

function meuppt_no_self_ping( &$links ) {
    $home = get_option( 'home' );
    foreach ( $links as $l => $link )
        if ( 0 === strpos( $link, $home ) )
            unset($links[$l]);
}
 
add_action( 'pre_ping', 'meuppt_no_self_ping' );


// Implementa funções de firewall básicas, bloqueando uma série de possíveis requisições maliciosas

$request_uri = $_SERVER['REQUEST_URI'];
$query_string = $_SERVER['QUERY_STRING'];
$user_agent = $_SERVER['HTTP_USER_AGENT'];
// request uri
if (    //strlen($request_uri) > 255 ||
        stripos($request_uri, 'eval(') ||
        stripos($request_uri, 'CONCAT') ||
        stripos($request_uri, 'UNION+SELECT') ||
        stripos($request_uri, '(null)') ||
        stripos($request_uri, 'base64_') ||
        stripos($request_uri, '/localhost') ||
        stripos($request_uri, '/pingserver') ||
        stripos($request_uri, '/config.') ||
        stripos($request_uri, '/wwwroot') ||
        stripos($request_uri, '/makefile') ||
        stripos($request_uri, 'crossdomain.') ||
        stripos($request_uri, 'proc/self/environ') ||
        stripos($request_uri, 'etc/passwd') ||
        stripos($request_uri, '/https/') ||
        stripos($request_uri, '/http/') ||
        stripos($request_uri, '/ftp/') ||
        stripos($request_uri, '/cgi/') ||
        stripos($request_uri, '.cgi') ||
        stripos($request_uri, '.exe') ||
        stripos($request_uri, '.sql') ||
        stripos($request_uri, '.ini') ||
        stripos($request_uri, '.dll') ||
        stripos($request_uri, '.asp') ||
        stripos($request_uri, '.jsp') ||
        stripos($request_uri, '/.bash') ||
        stripos($request_uri, '/.git') ||
        stripos($request_uri, '/.svn') ||
        stripos($request_uri, '/.tar') ||
        stripos($request_uri, ' ') ||
        stripos($request_uri, '<') ||
        stripos($request_uri, '>') ||
        stripos($request_uri, '/=') ||
        stripos($request_uri, '...') ||
        stripos($request_uri, '+++') ||
        stripos($request_uri, '://') ||
        stripos($request_uri, '/&&') ||
        // query strings
        stripos($query_string, '?') ||
        stripos($query_string, ':') ||
        stripos($query_string, '[') ||
        stripos($query_string, ']') ||
        stripos($query_string, '../') ||
        stripos($query_string, '127.0.0.1') ||
        stripos($query_string, 'loopback') ||
        stripos($query_string, '%0A') ||
        stripos($query_string, '%0D') ||
        stripos($query_string, '%22') ||
        stripos($query_string, '%27') ||
        stripos($query_string, '%3C') ||
        stripos($query_string, '%3E') ||
        stripos($query_string, '%00') ||
        stripos($query_string, '%2e%2e') ||
        stripos($query_string, 'union') ||
        stripos($query_string, 'input_file') ||
        stripos($query_string, 'execute') ||
        stripos($query_string, 'mosconfig') ||
        stripos($query_string, 'environ') ||
        //stripos($query_string, 'scanner') ||
        stripos($query_string, 'path=.') ||
        stripos($query_string, 'mod=.') ||
        // user agents
        stripos($user_agent, 'binlar') ||
        stripos($user_agent, 'casper') ||
        stripos($user_agent, 'cmswor') ||
        stripos($user_agent, 'diavol') ||
        stripos($user_agent, 'dotbot') ||
	    stripos($user_agent, 'ecatch') ||
	    stripos($user_agent, 'eirgrabber') ||
        stripos($user_agent, 'finder') ||
        stripos($user_agent, 'flicky') ||
	    stripos($user_agent, 'heritrix') ||
	    stripos($user_agent, 'httrack') ||
	    stripos($user_agent, 'httpdown') ||
        stripos($user_agent, 'ia_archiver') ||
        stripos($user_agent, 'larbin') ||
        stripos($user_agent, 'lftp') ||
        stripos($user_agent, 'libwww') ||
	    stripos($user_agent, 'netmechanic') ||
        stripos($user_agent, 'nutch') ||
	    stripos($user_agent, 'octopus') ||
        stripos($user_agent, 'planet') ||
        stripos($user_agent, 'purebot') ||
        stripos($user_agent, 'pycurl') ||
        stripos($user_agent, 'sitesnagger') ||
	    stripos($user_agent, 'skygrid') ||
        stripos($user_agent, 'sucker') ||
        stripos($user_agent, 'turnit') ||
        stripos($user_agent, 'vikspi') ||
	    stripos($user_agent, 'xenu') ||
        stripos($user_agent, 'zmeu')
) {
        @header('HTTP/1.1 403 Forbidden');
        @header('Status: 403 Forbidden');
        @header('Connection: Close');
        @exit;
}


// Inicia rotina de reformulação e edição do HTACCESS

register_activation_hook( __FILE__, 'meuppt_speed_browser_caching_install' );
register_deactivation_hook( __FILE__, 'meuppt_speed_browser_caching_uninstall' );


if( !function_exists( 'meuppt_speed_browser_caching_install' ) ) {
	function meuppt_speed_browser_caching_install() {
		meuppt_speed_browser_caching_install_htaccess();
	}
}

add_action( 'upgrader_process_complete', 'meuppt_speed_browser_caching_install', 10, 2 );

if( !function_exists( 'meuppt_speed_browser_caching_uninstall' ) ) {
	function meuppt_speed_browser_caching_uninstall() {
		meuppt_speed_browser_caching_uninstall_htaccess();
	}
}



/* Permite uso de compressão de ficheiros via GZIP

if(extension_loaded("zlib") && (ini_get("output_handler") != "ob_gzhandler"))
   add_action('wp', create_function('', '@ob_end_clean();@ini_set("zlib.output_compression", 1);'));
   
*/


/* Restringe acesso ao painel por Subscribers e Contributors - em teste

function meuppt_no_admin_access()
{
    $redirect = isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : home_url( '/' );
    if ( 
        current_user_can( 'Administrator' )
        OR current_user_can( 'Editor' )
        OR current_user_can( 'Author' )
    )
        exit( wp_redirect( $redirect ) );
}
add_action( 'admin_init', 'meuppt_no_admin_access', 100 );

*/

/* Marca como spam qualquer comentário com link que exceda 465caracteres

 function meuppt_url_spamcheck( $approved , $commentdata ) {  
return ( strlen( $commentdata['comment_author_url'] ) > 45) ? 'spam' : $approved;  
}  
add_filter( 'pre_comment_approved', 'meuppt_url_spamcheck', 99, 2 ); 

*/
