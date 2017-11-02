<?php


// Atualiza o htaccess
if( !function_exists( 'meuppt_speed_browser_caching_install_htaccess' ) ){
	
	function meuppt_speed_browser_caching_install_htaccess(){
	
		// Rastreia status da operação
		$is_install_ok = true;
		
		// We don't mess around with .htaccess
		$backup_filename = 'meuppt_speed_browser_caching_install_backup' . time() . '.htaccess';
		
		// Create htaccess if it's not there
		if( !file_exists( ABSPATH . '.htaccess') ){
			$newHtaccess = fopen(ABSPATH . '.htaccess', "w");
			fclose($newHtaccess);
		}
		
		// If there already is an .htaccess file
		if(file_exists( ABSPATH . '.htaccess') ) {
		
			// Try to copy the .htaccess for backup
			// If copy fails
			if(!copy ( ABSPATH . '.htaccess' , ABSPATH . $backup_filename )) {
			
				// Operation not ok
				$is_install_ok = false;

			}

		}
		
		// So far so good, let's keep going
		if( $is_install_ok ){
					
			// Add new rules to .htaccess
			$is_install_ok = write_htaccess_browser_caching_directives(ABSPATH . '.htaccess');

		}

		// So far so good, let's keep going		
		if( $is_install_ok ){
			
			// Erase backup
			meuppt_speed_browser_caching_erase_file(false, $backup_filename);
			
		}

		// Log whether the plugin was installed successfully
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
                // Configuração de browser cache
				fwrite($file_handle, "# START - TESTE FINAL PARA VER SE ATUALIZA AQUI Spehjghjghjghjghjghjed - Browser Caching\n");
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
				fwrite($file_handle, "# END - meuppt Speed - Browser Caching\n");
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
		
		// Try to remove htaccess directives
		// If it works
		if( meuppt_speed_browser_caching_remove_htaccess_directives($backup_filename) ) {
		
			// Mark plugin as deactivated
			update_option( 'meuppt_speed_browser_caching_status', 0);

			// Rastreia status da operação
			$is_operation_ok = false;
		
		// Oops! Could not remove htaccess files	
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