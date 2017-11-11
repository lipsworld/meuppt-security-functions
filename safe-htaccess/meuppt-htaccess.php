<?php


// Atualiza o htaccess
if( !function_exists( 'meuppt_speed_browser_caching_install_htaccess' ) ){
	function meuppt_speed_browser_caching_install_htaccess(){
		$directoryName = plugin_dir_path( __FILE__ ) . '/backup/';
 		// Verifica se diretório já existe.
		if(!is_dir($directoryName)){
    		// Se não existe, cria diretório de backup.
    		mkdir($directoryName, 0755, true);
		}
		$is_install_ok = true;
		$backup_filename = 'meuppt_speed_browser_caching_install_backup' . time() . '.htaccess';
		if( !file_exists( ABSPATH . '.htaccess') ){
			$newHtaccess = fopen(ABSPATH . '.htaccess', "w");
			fclose($newHtaccess);
		}
		
		// Já existe um .htaccess 
		if(file_exists( ABSPATH . '.htaccess') ) {
			// Tenta efetuar backup
			// Havendo falhas
			if(!copy ( ABSPATH . '.htaccess' , ABSPATH . $backup_filename )) {
				// Operação falha
				$is_install_ok = false;
			}
		}
		
		// Segue adiante
		if( $is_install_ok ){		
			// Adiciona diretrizes ao .htaccess
			$is_install_ok = write_htaccess_browser_caching_directives(ABSPATH . '.htaccess');
		}
	
		if( $is_install_ok ){	
			// Apaga o backup
			meuppt_speed_browser_caching_erase_file(false, $backup_filename);	
		}

		if($is_install_ok) {
			update_option( 'meuppt_speed_browser_caching_status', 1);
		} else {
			update_option( 'meuppt_speed_browser_caching_status', 0);
		}
	}
}

/**
 * Inclui diretrizes no htaccess
 * @param string $file_path
 * @return bool whether the write operation was successful or not.
 */
if( !function_exists( 'write_htaccess_browser_caching_directives' ) ){	

	function write_htaccess_browser_caching_directives( $file_path ){

		// Rastreia status da operação
		$is_write_operation_ok = true;
		
		// Se o ficheiro pode ser sobrescrito
		if (is_writable($file_path)) {
			
			// Abre para modificação
			$file_handle = fopen($file_path, "w");
			
			// Bloqueia se o LOCK for obtido
			if (flock($file_handle, LOCK_EX)) {

				// Argumentos do Htaccess 
				fwrite($file_handle, "\n");
				// Configuração básica inicial do Wordpress
				fwrite($file_handle, "# BEGIN WordPress\n");
				fwrite($file_handle, "<IfModule mod_rewrite.c>\n");
				fwrite($file_handle, "RewriteEngine On\n");
				fwrite($file_handle, "RewriteBase /\n");
				fwrite($file_handle, "RewriteRule ^index\.php$ - [L]\n");
				fwrite($file_handle, "RewriteCond %{REQUEST_FILENAME} !-f\n");
				fwrite($file_handle, "RewriteCond %{REQUEST_FILENAME} !-d\n");
				fwrite($file_handle, "RewriteRule . /index.php [L]\n");
				fwrite($file_handle, "</IfModule>\n");
				fwrite($file_handle, "# END WordPress\n");
				fwrite($file_handle, "\n");
				// Bloqueia acesso direto ao HTACCESS
				fwrite($file_handle, "# Nega acesso ao ficheiro do htaccess\n");
				fwrite($file_handle, "<files .htaccess>\n");
				fwrite($file_handle, "order allow,deny\n");
				fwrite($file_handle, "deny from all\n");
				fwrite($file_handle, "</files>\n");
				fwrite($file_handle, "\n");
				fwrite($file_handle, "# Compressão habilitada\n");
				fwrite($file_handle, "<ifmodule mod_deflate.c>\n");
				fwrite($file_handle, "AddOutputFilterByType DEFLATE text/text text/html text/plain text/xml text/css application/x-javascript application/javascript\n");
				fwrite($file_handle, "</ifmodule>\n");
				fwrite($file_handle, "\n");
				// Desabilita assinaturas no servidor e browsing em diretórios a partir do navegador
				fwrite($file_handle, "# Desabilita assinaturas no servidor\n");
				fwrite($file_handle, "ServerSignature Off\n");
				fwrite($file_handle, "\n");
				fwrite($file_handle, "# Desabilita browsing nos diretórios\n");
				fwrite($file_handle, "Options All -Indexes\n");
				fwrite($file_handle, "\n");
                		// Configuração de browser cache
				fwrite($file_handle, "# Configurações do cache no browser\n");
				fwrite($file_handle, "<IfModule mod_expires.c>\n");
				fwrite($file_handle, "ExpiresActive On \n");
				fwrite($file_handle, "ExpiresDefault \"access plus 1 month\" \n");
				fwrite($file_handle, "ExpiresByType image/x-icon \"access plus 1 month\" \n");
				fwrite($file_handle, "ExpiresByType image/gif \"access plus 1 month\" \n");
				fwrite($file_handle, "ExpiresByType image/png \"access plus 1 month\" \n");
				fwrite($file_handle, "ExpiresByType image/jpg \"access plus 1 month\" \n");
				fwrite($file_handle, "ExpiresByType image/jpeg \"access plus 1 month\" \n");
				fwrite($file_handle, "ExpiresByType text/css \"access 1 month\" \n");
				fwrite($file_handle, "ExpiresByType application/javascript \"access plus 1 month\" \n");
				fwrite($file_handle, "</IfModule> \n");
				fwrite($file_handle, "\n");
				fwrite($file_handle, "# Adiciona UTF-8 como character encoding padrão \n");
				fwrite($file_handle, "AddDefaultCharset utf-8\n");
				fwrite($file_handle, "\n");
				fwrite($file_handle, "# Proteção contra clickjacking, ataques de XSS\n");
				fwrite($file_handle, "<IfModule mod_headers.c>\n");
				fwrite($file_handle, "Header set X-XSS-Protection "1; mode=block"\n");
				fwrite($file_handle, "Header always append X-Frame-Options SAMEORIGIN\n");
				fwrite($file_handle, "Header set X-Content-Type-Options nosniff\n");
				fwrite($file_handle, "</IfModule>\n");
				fwrite($file_handle, "\n");
				fwrite($file_handle, "\n");
				fflush($file_handle);
				
				// Força sobrescrita
				fflush($file_handle);
				// Destrava o ficheiro
				flock($file_handle, LOCK_UN);
			
			}else{
				
				// Falha de logging
				$is_write_operation_ok = false;
				
			}
			
			// Encerra o ficheiro
			fclose($file_handle);
		
		// Ficheiro não pode ser sobrescrito	
		} else {
		
			$is_write_operation_ok = false;
		}
		
		return $status;		
	}
}
	

if( !function_exists('meuppt_speed_browser_caching_uninstall_htaccess') ){	
	function meuppt_speed_browser_caching_uninstall_htaccess() {
		// Don't mess with htaccess files; make a backup
		$backup_filename = 'meuppt_speed_browser_caching_uninstall_backup' . time() . '.htaccess';
		// Rastreia status da operação
		$is_operation_ok = true;
		
		if( meuppt_speed_browser_caching_remove_htaccess_directives($backup_filename) ) {
		
			// Mark plugin as deactivated
			update_option( 'meuppt_speed_browser_caching_status', 0);
			// Rastreia status da operação
			$is_operation_ok = false;
		
		} else {
			
			// Mark plugin as still active
			update_option( 'meuppt_speed_browser_caching_status', 1);
		}
		
		return $is_operation_okstatus;
	}
}	

if( !function_exists('meuppt_speed_browser_caching_remove_htaccess_directives') ){	
	function meuppt_speed_browser_caching_remove_htaccess_directives($backup_filename){	
		// Rastreia status da operação
		$is_operation_ok = true;
		
		// Copy htaccess for backup
		// If backup failed
		if( !copy ( ABSPATH . '.htaccess' , ABSPATH . $backup_filename )) {
			// Rastreia status da operação
			$is_operation_ok = false;
		}
		
		// All good, let's keep going.
		if($is_operation_ok) {
			// Get file handle for writing
			$file_handle = fopen(ABSPATH . '.htaccess', "w");
			// Get file lines as array
			$lines = file( ABSPATH . $backup_filename );
			// Lock htaccess 
			if (flock($file_handle, LOCK_EX)) {
			
				// Truncate file to 0 (erase everything)
				ftruncate($file_handle, 0);

				$inmeupptSpeedDirectives = false;
				
				// Lopp over lines
				foreach($lines as $line) {
				
					// When we find the first line of our directives
					if(strpos($line, 'START - meuppt Speed - Browser Caching') !== false) {	

						$inmeupptSpeedDirectives = true;

					}


					if(!$inmeupptSpeedDirectives) {
						

						fwrite($file_handle, $line);
						

						fflush($file_handle);
						
					}


					if(strpos($line, 'END - meuppt Speed - Browser Caching') !== false) {

						$inmeupptSpeedDirectives = false;

					}
					
				}
				
				flock($file_handle, LOCK_UN);
			
			} else {
			
				$is_operation_ok = false;
				
			}
			
			// Encerra o ficheiro
			fclose($file_handle);
			
		}
		
		return $is_operation_ok;
		
	}

}
