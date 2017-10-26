<?php /*

**************************************************************************

Plugin Name:  MeuPPT - Funções de Segurança e Otimização
Plugin URI:   http://www.meuppt.pt/plugins
Description:  Inclui uma série de funções para melhorar a segurança da instalação do Wordpress, sem alterações diretas no functions.php.
Version:      1.1.2
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Author:       MeuPPT
Author URI:   http://www.meuppt.pt/
Text Domain:  security-functions-meuppt

**************************************************************************

Copyright (C) 2017 MeuPPT

Possui uma série de melhorias de segurança para instalações em Wordpress. Distribuição para uso de clientes MeuPPT, porém de livre modificação e uso por terceiros, conforme licença.

**************************************************************************/



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


// Automatiza a atualização de plugins

add_filter( 'auto_update_plugin', '__return_true' );


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



/* Adiciona botões de cor de fundo nas fontes, caracteres especiais

function enable_more_buttons($buttons) {
  $buttons[] = 'backcolor';
  $buttons[] = 'copy';
  $buttons[] = 'charmap';
 return $buttons;
}
add_filter("mce_buttons_2", "enable_more_buttons");

*/

/* Permite uso de compressão de ficheiros via GZIP

if(extension_loaded("zlib") && (ini_get("output_handler") != "ob_gzhandler"))
   add_action('wp', create_function('', '@ob_end_clean();@ini_set("zlib.output_compression", 1);'));
   
*/


/* Adiciona suporte para upload de ficheiros SVG na área de multimédia do Wordpress Admin

function wps_mime_types( $mimes ){
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter( 'upload_mimes', 'wps_mime_types' );

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