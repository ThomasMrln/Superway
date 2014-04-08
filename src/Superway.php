<?php

class Superway {
	
	private $uri;
	private $root;
	private $extension;
	private $roads	=	[];
	private $values	=	[];
	private $offroad;
	
	public function __construct($root) {
			$this->root	=	$root;
					
			$this->uri	=	$_SERVER['REQUEST_URI'];
			
			if ($_SERVER['argc'] > 0)
					$this->values	=	$_SERVER['argv'];
	}
	
	public function __get($key) 		{	return $this->$key;			}
	public function __set($key, $value)	{	$this->$key	=	$value;		}
	
	public function drive( $path=null) {
			if ($path === null)
					if (isset($_SERVER['PATH_INFO']))
							$path = $_SERVER['PATH_INFO'];

			if (!$path)		$path = '/';

	        foreach ($this->roads as $road) {
	            	if ($road->match($path)) {
	            			extract($road->variables);
	            			include($this->root.$road->path.'.'.$this->extension);
	            			return;
	            	}
	        }  
			
	        include($this->root.$this->offroad->path.'.'.$this->extension);
	}
	
	public function add_road( Road $road ) {
			$this->roads[]	=	$road;
	}
	
	public function offroad(Road $road) {
			$this->offroad	=	$road;
	}
	
}