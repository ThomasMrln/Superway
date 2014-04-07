<?php

include('./src/Superway.php');
include('./src/Road.php');

// Create ouy rooter
$sw	=	new Superway('./templates');
$sw->extension	=	'php';

// Add your roads with form : new Road('path', 'pattern')
$sw->add_road(new Road('/home', '/'));
$sw->add_road(new Road('/blog/blog', '/blog/'));
$sw->add_road(new Road('/blog/blog', '/blog/search/{query}'));
$sw->add_road(new Road('/blog/blog', '/blog/category/{id}'));
$sw->add_road(new Road('/blog/article', '/blog/article/{date}/{id}_{titre}'));
$sw->offroad(new Road('/404', '/404'));

// And drive !
try {
	print $sw->drive();
} catch (\Exception $e) {
	print "Erreur : ".$e->getMessage();
}