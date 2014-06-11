<?php

class Superway {
	
	private $uri;
	//private $root;
	private $extension;
	private $roads	=	[];
	private $values	=	[];
	//private $offroad;
	
	public function __construct() {
			//$this->root	=	$this->build_root( $root );
					
			$this->uri	=	$_SERVER['REQUEST_URI'];
			
			if ($_SERVER['argc'] > 0)
					$this->values	=	$_SERVER['argv'];
	}
	
	public function __get($key) 		{	return $this->$key;			}
	public function __set($key, $value)	{	$this->$key	=	$value;		}
	
	public function drive( $path=null ) {
			if ($path === null)
					$path	=	$this->uri;
					//if (isset($_SERVER['PATH_INFO']))
					//		$path = $_SERVER['PATH_INFO'];

			if (!$path)		$path = '/';

	        //foreach ($this->roads as $road) {
	        //    	if ($road->match($path)) {
			//  			@extract($road->variables);
			//  			$path_file	=	$this->build_root( $road->path, $this->root );
	        //    			include('/'.$path_file.'.'.$this->extension);
	        //    			return true;
	        //    	}
	        //}
	        
	        foreach ($this->roads as $regex=>$values) {
	        		
	        		$regex_url	=	'/'.str_replace( '/', '\/', $regex).'/';
	        		//print 	'<br>';
	        		//print $path.'<br>';
	        		if ( preg_match($regex_url, $path) ) {
	        		// Si la regex de l'URL correspond au chemin

	        				if (isset($values['security']) && $values['security'] != "") {
	        				// Si le champs protégeant les prochaines routes est renseigné
	        						
	        						if (file_exists(ROOT.$values['security'])) {
	        						// Si le fichier de sécurité  existe
	        								$security_ok	=	include(ROOT.$values['security']);	
	        								
	        								if ( $security_ok != true ) {
			        						// Sécurité NON OK, on bascule sur le fichier renvoyé dans le error de l'initialisation
			        						// Ou alors, on affiche une page 404
			        						
			        								if (isset($values['error']) && $values['error'] != "") {
			        										include(ROOT.$values['error']);
			        								} else {
			        										$path_file	=	$this->build_root( $values['path'].$values['offroad']->path );
			        										include($path_file.'.'.$this->extension);
			        								}
			        								return;
			        						}
	        						}
	        				}
	        				
	        				foreach ($values['roads'] as $road) {
							    	if ($road->match($path)) {
							  				@extract($road->variables);
							  				$path_file	=	$this->build_root( $values['path'].$road->path );
							    			include($path_file.'.'.$this->extension);
							    			return;
							    	}
							}
	        				
	        		} else {
		        	// Si pas de correspondance, on passe au chemin suivant
		        			//print 'unmatch';
		        			continue;
	        		}
	        		//print '<br><br>';
	        }
	        
	        // Si aucunes routes trouvées, on passe sur le chemin d'erreur
	        //include($this->root.$this->offroad->path.'.'.$this->extension);
	}
	
	public function initialize( $roads ) {
			$this->roads	=	$roads;
	}
	
	public function add_road( Road $road ) {
			$this->roads[]	=	$road;
	}
	
	public function offroad(Road $road) {
			$this->offroad	=	$road;
	}
	
	private function build_root( $root, $root_server=false ) {
			if (!$root_server)
					$root_server	=	str_replace($_SERVER['SCRIPT_NAME'], "/", $_SERVER['SCRIPT_FILENAME']);
					
			if ($root[0] == '/')
					$root	=	substr($root, 1);
	
			if ( preg_match('/(\.\.\/)/', $root) ) {

					for ($i=0; $i<substr_count($root, '../'); $i++) {
							
							$path	=	explode('/', $root_server);
							$path	=	array_filter($path);
							array_pop($path);
							
							$root_server	=	"";
							foreach ($path as $row)
									$root_server	.=	$row.'/';
					}
					return $root_server.str_replace('../', '', $root);
			} else {
					return $root_server.str_replace('./', '', $root);
			}
	}
	
}