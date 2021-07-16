document.addEventListener( 'DOMContentLoaded', function(){
	if( !!document.getElementById( 'fx-sql-loader') ){
		document.getElementById( 'fx-sql-loader').onclick = function(){
			let next = confirm( 'File "server-sql-query.log" will be cleared.' + "\n" + 'Continue?');
			if( next){
				let body = 'action=fx_sql_load';
				let xhr = new XMLHttpRequest();
				xhr.open( "POST", '/wp-admin/admin-ajax.php', true);
				xhr.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded');

				xhr.onreadystatechange = function(){
					if ( this.readyState == 4){
				  		document.getElementById( "fx-sql-wrap").outerHTML = this.responseText;
				  	}
				}
			
				xhr.send( body);
			}
		}
	}

	if( !!document.getElementById( 'fx-sql-clear') ){
		document.getElementById( 'fx-sql-clear').onclick = function(){
			let body = 'action=fx_sql_clear';
				
			let xhr = new XMLHttpRequest();
			xhr.open( "POST", '/wp-admin/admin-ajax.php', true)
			xhr.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded')

			xhr.onreadystatechange = function() {
				if ( this.readyState == 4){
			  		console.log( this.responseText);
			  	}
			}

			xhr.send( body);
		}
	}

}, false);

