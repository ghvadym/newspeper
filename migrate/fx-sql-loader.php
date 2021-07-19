<?php
	/*
		Plugin Name: FX SQL Loader
		Description: Plugin for loading saved SQL requests.
		Version: 1.0
		Author: FX
	*/

	define( 'SAVEQUERIES', true);

	add_action( 'shutdown', 'sql_logger');
	add_action( 'admin_menu', 'fx_register_sql_loader_page');
	add_action( 'admin_enqueue_scripts', 'sql_loader_enqueue_scripts');
	add_action( 'wp_ajax_fx_sql_load', 'fx_sql_load');
	add_action( 'wp_ajax_fx_sql_clear', 'fx_sql_clear');

	register_activation_hook( __FILE__, 'fx_sql_loader_init');
	register_deactivation_hook( __FILE__, 'fx_sql_loader_deactivate');

	function fx_sql_loader_init(){

		if( !is_dir( ABSPATH . ".git")){
			shell_exec( 'cd ' . ABSPATH . ' && git init --shared=0777');
    	}

    	if( !is_dir( ABSPATH . "/sql-migrations")){
    		shell_exec( 'mkdir ' . ABSPATH . 'sql-migrations -p -m 777');
    	}
    	if( !is_dir( ABSPATH . "/sql-migrations")){
    		die('In order to continue, please execute this command in your terminal: "sudo chmod 777 ' . ABSPATH . '". After that try to activate plugin again.');
    	}

    	file_put_contents( ABSPATH . "/sql-migrations/options.php", get_site_url() );

    	global $wpdb;

		$table_name = 'sql_migrations';

		if( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'") != $table_name){
		     $charset_collate = $wpdb->get_charset_collate();
		 
		     $sql = "CREATE TABLE $table_name (
		          id mediumint(9) NOT NULL AUTO_INCREMENT,
		          hash_name VARCHAR(60) NOT NULL,
		          status enum('success','fail') DEFAULT NULL,
		          created_on timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
		          UNIQUE KEY id (id)
		     ) $charset_collate;";

		     require_once( ABSPATH . 'wp-admin/includes/upgrade.php');

		     dbDelta( $sql);
		}



		if( strpos( home_url(), 'testsite.co.ua') === FALSE ){

	    	$file = ABSPATH . ".gitignore";

	    	$exceptions_array = array( 
	    		"sql-migrations/local-sql-query.log",
	    		"sql-migrations/server-sql-query.log"
			);

			$exceptions = "\n";
			foreach( $exceptions_array as $exception ){
				$exceptions .= $exception."\n";
			}

			if( file_exists( $file)){
				exec("echo '$exceptions' > $file ", $output, $return);
				if( $return != ''){
					$user = sanitize_title( shell_exec("whoami"));
					die('In order to continue, please execute this command in your terminal: "sudo setfacl -m u:' . $user . ':rwx ' . $file . '". After that try to activate plugin again.');
				}
			} else {
				shell_exec( "echo '$exceptions' > $file ");
			}

			$file = ABSPATH . ".git/hooks/post-commit";

			$command = "#FxSQLmigrations
				#!/bin/sh
				migrationDir=\${PWD}'/sql-migrations/'
				fileName=\${migrationDir}'local-sql-query.log';

				if [ -f \"\$fileName\" ]; then 
					gethash=\$(git log --pretty=format:'%h' -n 1)
					timestamp=\$(date +%s)
					mv \$fileName \${migrationDir}\${timestamp}'-'\${gethash}'.queries'
					git add . 
					git commit -m \"New queries for SQL migration.\"
					echo '
					------------ SQL migration queries were successfully added to commit. ------------
					'
				fi
			#FxSQLmigrations";

			if( !file_exists( $file)){
				if ( !$handle = fopen( $file, 'a')){
			    	die('In order to continue, please execute this command in your terminal: "sudo chmod -R 777 ' . ABSPATH . '.git". After that try to activate plugin again.');
			    } else {
			    	if ( fwrite( $handle, $command) === FALSE) {
				        die('In order to continue, please execute this command in your terminal: "sudo chmod -R 777 ' . ABSPATH . '.git". After that try to activate plugin again.');
				        exit;
				    }
			    }
			    shell_exec( " chmod +rwx " . $file );
			} else {
				$content = file_get_contents( $file);
				if( strpos( $content, 'SQL migration queries were successfully added to commit') === FALSE){
					if( fwrite( fopen( $file, 'a'), $command) === FALSE) {
				        die('In order to continue, please execute this command in your terminal: "sudo chmod -R 777 ' . ABSPATH . '.git". After that try to activate plugin again.');
				        exit;
				    }
				}
			}
		}
	}


	function sql_loader_enqueue_scripts(){
		wp_register_script( 'sql_loader_scripts', plugin_dir_url( __FILE__ ) . 'js/fx-sql-loader-admin.js');
		wp_localize_script( 'sql_loader_scripts', 'fx_sql', array( 'ajax_url' => admin_url('admin-ajax.php') ));
		wp_enqueue_script( 'sql_loader_scripts' );
		wp_enqueue_style( 'sql_loader_styles', plugin_dir_url( __FILE__ ) . 'css/fx-sql-loader-admin.css');
	}
	 
	function fx_register_sql_loader_page(){
	    add_submenu_page(
	        'tools.php',
	        'SQL Loader',
	        'SQL Loader',
	        'manage_options',
	        'sql-loader-page',
	        'fx_sql_loader_callback' );
	}
	 
	function fx_sql_loader_callback(){

		global $wpdb;

	    $html = '<div id="fx-sql-wrap" class="wrap" data="'.ABSPATH.'">
			    	<h2>SQL Loader</h2>
			    	<button id="fx-sql-loader" class="button button-primary">SQL Migration</button>
			    	<button id="fx-sql-clear" class="button button-primary">Clear Log</button>';

    	$files = array_diff( scandir( ABSPATH . "/sql-migrations" ), array( '..', '.', 'local-sql-query.log', 'server-sql-query.log', 'options.php'));
    	$resources = $wpdb->get_results( 'SELECT hash_name, status FROM sql_migrations');

    	$html .= '<table id="fx-sql-table" cellspacing="0">
    				<thead>
    					<th>Name</th>
    					<th>Status</th>
    				</thead>
    				<tbody>';

    	$diff = array();

    	foreach( $resources as $resource){
    		$diff[] = $resource->hash_name;
    		$html .= '<tr><td>' . $resource->hash_name . '</td><td class="fx-sql-' . $resource->status . '">' . $resource->status . '</td></tr>';
    	}

    	$files = array_diff( $files, $diff);

    	foreach( $files as $file){
			$html .= '<tr><td>' . $file . '</td><td>unmigrate</td></tr>';
    	}

	    $html .= '	</tbody>
	    		</table>';

	    print_r( $html );
	    exit();
	}

	function sql_logger(){

	    global $wpdb;

	    $key_words = array(
	    	'UPDATE',
	    	'INSERT',
	    	'DELETE'
	    );

	    $file_name = strpos( home_url(), 'testsite.co.ua') === FALSE ? 'local' : 'server';
	    $log_file = fopen( ABSPATH . 'sql-migrations/' . $file_name . '-sql-query.log', 'a+');
	    if(!$log_file){
	    	die('In order to continue, please, execute this command in your terminal: "sudo chmod -R 777 ' . ABSPATH . 'sql-migrations".');
	    }

	    if( isset( $wpdb->queries)) {
		    foreach( $wpdb->queries as $query) {
		    	if( check_array( $key_words, $query[0]) ){
		    		if( strpos( $query[0], 'sql_migrations') === FALSE){
		    			fwrite( $log_file, $query[0] . ";\n");
		    		}
		    	}
		    }
		}

	    fclose($log_file);
	}

	function check_array( $words, $query){

		foreach( $words as $word){
			if( strpos( $query, $word) !== FALSE){
				return true;
			}
		}
   
		return false;
	}

	function fx_sql_load(){

		$conn = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );

		if ( $conn->connect_error) {
		    die( "Connection failed: " . $conn->connect_error);
		}

		$files = array_diff( scandir( ABSPATH . "/sql-migrations" ), array('..', '.', 'local-sql-query.log', 'server-sql-query.log', 'options.php'));
		$resources = $conn->query('SELECT hash_name FROM sql_migrations WHERE status = "success"');

		while ( $row = $resources->fetch_assoc() ) {
		   	$migrated[] = $row['hash_name'];
		}

		if( isset( $migrated)){
			$files = array_diff( $files, $migrated);
		}

		global $wpdb;

		foreach( $files as $filename){

			$sql = file_get_contents( ABSPATH . '/sql-migrations/' . $filename);
			
			$searchfor = file_get_contents( ABSPATH . '/sql-migrations/options.php');
			$replace = get_site_url();

			$pattern = preg_quote($searchfor, '/');
			$pattern = "/^.*$pattern.*\$/m";
			if(preg_match_all($pattern, $sql, $matches)){
			   
			   foreach ($matches[0] as $match) {
			   		preg_match_all( "/\'((?!\').)*http((?!\').)*\'/i", $match, $val);
			   		foreach ($val[0] as $value) {
			   			$parsed =  maybe_unserialize( stripcslashes( substr($value, 1, -1) ) );
			   			if(is_array($parsed)){
			   				$updated = array_walk_recursive(
			   					$parsed,
							    function($str) {
							        $str = str_replace($searchfor, $replace, $str);
							    }
						    );
			   			} else {
			   				$updated = str_replace($searchfor, $replace, $parsed);
			   			}
			   			//$updated = str_replace($searchfor, $replace, $parsed);
			   			/*$updated = array_map(
						    function($str) {
						        return str_replace($searchfor, $replace, $str);
						    },
						    $parsed
						);*/
			   			var_dump($updated);
			   		}
			   }
			   
			}
			else{
			   echo "No matches found";
			}

			$result = mysqli_multi_query( $conn, $sql);

			if ($result === TRUE) {
				echo "<div class='fx-sql-result'>Migration " . $filename . " created successfully</div>";
				$query = "INSERT INTO sql_migrations (hash_name, status) VALUES ('" . $filename . "', 'success' ) ON DUPLICATE KEY UPDATE status = VALUES(status);";
				$wpdb->query( $query);
			} else {
				echo "<div class='fx-sql-result'>Error creating " . $filename . " migration: " . $conn->error . "</div>";
				$query = "INSERT INTO sql_migrations (hash_name, status) VALUES ('" . $filename . "', 'fail' ) ON DUPLICATE KEY UPDATE status = VALUES(status);";
				$wpdb->query( $query);
			}

			while( mysqli_next_result( $conn)){
				if ( $data = mysqli_store_result( $link)) {
					mysqli_free_result( $data);
				}
			}

			mysqli_fetch_all( $conn, MYSQLI_ASSOC);
			mysqli_free_result( $conn);
	    }

		$conn->close();
		fx_sql_loader_callback();

		exit();
	}

	function fx_sql_clear(){

		$file = ABSPATH . '/sql-migrations/local-sql-query.log';

		if( file_exists( $file)){
			file_put_contents( $file, "");
			echo "Log is clear.";
		}
	}

	function fx_sql_loader_deactivate(){

		$file = ABSPATH . ".git/hooks/post-commit";
		if( file_exists( $file)){
			$content = file_get_contents( $file);
			if( strpos( $content, '#FxSQLmigrations') !== FALSE){
				file_put_contents( $file, preg_replace("/#FxSQLmigrations.*#FxSQLmigrations/s", "", $content));
			}
		}
	}

?>