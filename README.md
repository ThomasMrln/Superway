Superway
========================

Superway is a PHP route allowing to forget your .htaccess and others way to route your urls.

Easy to "install" and launch. Superway was created to simplify the web developper work.

Usage
======

Intanciation
-

```php
<?php
  // Instantiate your Superway router with your templates directory
  $sw = new Superway('./templates');
  
  // Superway has require to know the extension of file used
  $sw->extension  = 'php';  // Others with tpl, html, and more
```

Add road
-

Create your routes by adding roads.

Road is composed of  :
    * the path of the file
    * the pattern of the URL
    
The power of Superway is the infinite integration of variables into the url, and the possibility of get this variables in your template, with her name.

```php
<?php
  $sw->add_road(  new Road('/home',         '/'));
  $sw->add_road(  new Road('/blog/blog',    '/blog/'));
  $sw->add_road(  new Road('/blog/blog',    '/blog/search/{query}'));
  $sw->add_road(  new Road('/blog/blog',    '/blog/category/{id}'));
  $sw->add_road(  new Road('/blog/article', '/blog/article/{date}/{id}_{title}'));
```

Add an offroad
-

In case where Superway foundn't a route, it takes you back in the offroad Road, which is required.
```php
<?php
  $sw->offroad(   new Road('/404',           '/404'));
```

Use variables
-

To use your variables passed in the pattern, you have just to call them with their name.

For example, the pattern '__/blog/article__', with this url : '__http://your_url.com/blog/article/2014-04/112_title-of-your-article/__' leads the use of `$date (== 2014-04)`, `$id (== 112)` and `$title (==title-of-your-article)`

Take the wheel and drive !
-

To drive your roads, you have juste to launch this code and wait result...

```php
<?php
  try {
  	print $sw->drive();
  } catch (\Exception $e) {
  	print "Erreur : ".$e->getMessage();
  }
```
