<?php

class Road {
	private $path;
	private $pattern;
	private $argv		=	[];
	private $separators	=	[];
	private $variables 	=	[];
	
	public function __construct($path, $argv=null) {
			$this->path		=	$path;
			
			if (preg_match_all('/\{[a-zA-Z0-9]{1,}\}/', $argv, $matches))
					$this->argv	=	$matches[0];
			
			
			$separators	=	preg_split('/\{[a-zA-Z0-9]{1,}\}/', $argv);
			foreach ($separators as $element)
					if (!empty($element))
							$this->separators[]	=	$element;


			$this->pattern 	=	$this->separators[0];
			unset($this->separators[0]);
			for ($i=0; $i < count($this->argv); $i++)
					$this->pattern	.=	$this->separators[$i].$this->argv[$i];
			
			if ($this->pattern[strlen($this->pattern)-1] == '/')
					$this->pattern	=	substr($this->pattern, 0, strlen($this->pattern)-1 );			
	}
	
	public function __get($key) 		{	return $this->$key;		}
	public function __set($key, $value)	{	$this->$key	=	$value;	}
	
	public function match( $path ) {
			$pattern	=	str_replace('/', '\/', '#'.preg_replace('/\{([a-zA-Z0-9]{1,})\}/', '(.*)', $this->pattern).'/?#');

			if (preg_match($pattern, $path, $matches)) {
					if ($matches[0] == $path) {
							$variables	=	array();
							for ($i=0; $i<count($this->argv); $i++) {
									$this->variables[str_replace(array('{', '}'), '', $this->argv[$i])]	=	preg_replace('/\/.*/', '', $matches[($i+1)]);
							}
							return true;
					} else	return false;
			} else 	return false;
			
	}
	
	public function generate( array $arguments=null ) {
			$url	=	'';
			foreach ($this->argv as $arg)
			    	$url	.=	$this->argv['{'.key($arg).'}'];
			    	
			return $url;
	}
}