<?php 
/** Fenom template 'blog.tpl' compiled at 2015-11-10 03:35:00 */
return new Fenom\Render($fenom, function ($var, $tpl) {
?><!DOCTYPE html> <html lang="en"> <head> <meta charset="utf-8"> <meta http-equiv="X-UA-Compatible" content="IE=edge"> <meta name="viewport" content="width=device-width, initial-scale=1.0"> <meta name="description" content="<?php
/* base.tpl:7: {$description} */
 echo $var["description"]; ?>>"> <meta name="keywords" content="<?php
/* base.tpl:8: {$keywords} */
 echo $var["keywords"]; ?>"> <?php
/* base.tpl:10: {Morfy::runAction('theme_meta')} */
 echo Morfy::runAction('theme_meta'); ?> <link rel="shortcut icon" href="<?php
/* base.tpl:12: {$.site.url} */
 echo $tpl->getStorage()->site_config["url"]; ?>/favicon.ico"> <title><?php
/* base.tpl:14: {$.site.title} */
 echo $tpl->getStorage()->site_config["title"]; ?> | <?php
/* base.tpl:14: {$title} */
 echo $var["title"]; ?></title>  <link href="<?php
/* base.tpl:17: {$.site.url} */
 echo $tpl->getStorage()->site_config["url"]; ?>/themes/<?php
/* base.tpl:17: {$.site.theme} */
 echo $tpl->getStorage()->site_config["theme"]; ?>/assets/css/bootstrap.min.css" rel="stylesheet"> <link href="<?php
/* base.tpl:18: {$.site.url} */
 echo $tpl->getStorage()->site_config["url"]; ?>/themes/<?php
/* base.tpl:18: {$.site.theme} */
 echo $tpl->getStorage()->site_config["theme"]; ?>/assets/css/theme.css" rel="stylesheet"> <?php
/* base.tpl:19: {Morfy::runAction('theme_header')} */
 echo Morfy::runAction('theme_header'); ?> </head> <body> <div id="wrap"> <?php
/* base.tpl:24: {include 'navbar.tpl'} */
 $t7e67f4a8_1 = $var; ?><div class="navbar navbar-default navbar-fixed-top" role="navigation"> <div class="container"> <div class="navbar-header"> <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button> <a class="navbar-brand" href="<?php
/* navbar.tpl:10: {$.site.url} */
 echo $tpl->getStorage()->site_config["url"]; ?>"><?php
/* navbar.tpl:10: {$.site.title} */
 echo $tpl->getStorage()->site_config["title"]; ?></a> </div> <div class="collapse navbar-collapse"> <ul class="nav navbar-nav"> <?php
/* navbar.tpl:14: {set $slug = Url::getUriSegment(0)} */
 $var["slug"]=Url::getUriSegment(0); ?> <li <?php
/* navbar.tpl:15: {if $slug == ''} */
 if($var["slug"] == '') { ?> class="active" <?php
/* navbar.tpl:15: {/if} */
 } ?>><a href="<?php
/* navbar.tpl:15: {$.site.url} */
 echo $tpl->getStorage()->site_config["url"]; ?>">Home</a></li> <li <?php
/* navbar.tpl:16: {if $slug == 'blog'} */
 if($var["slug"] == 'blog') { ?> class="active" <?php
/* navbar.tpl:16: {/if} */
 } ?>><a href="<?php
/* navbar.tpl:16: {$.site.url} */
 echo $tpl->getStorage()->site_config["url"]; ?>/blog">Blog</a></li> <li <?php
/* navbar.tpl:17: {if $slug == 'contact'} */
 if($var["slug"] == 'contact') { ?> class="active" <?php
/* navbar.tpl:17: {/if} */
 } ?>><a href="<?php
/* navbar.tpl:17: {$.site.url} */
 echo $tpl->getStorage()->site_config["url"]; ?>/contact">Contact</a></li> </ul> </div> </div> </div> <?php $var = $t7e67f4a8_1; unset($t7e67f4a8_1); ?> <?php
/* base.tpl:25: {Morfy::runAction('theme_content_before')} */
 echo Morfy::runAction('theme_content_before'); ?>  <div class="container"> <?php
/* blog.tpl:4: {set $posts = Morfy::getPages('blog', 'date', 'DESC', ['404','index'])} */
 $var["posts"]=Morfy::getPages('blog', 'date', 'DESC', array('404', 'index')); ?> <?php
/* blog.tpl:5: {foreach $posts as $post} */
  if(!empty($var["posts"]) && (is_array($var["posts"]) || $var["posts"] instanceof \Traversable)) {
  foreach($var["posts"] as $var["post"]) {  ?> <h3><a href="<?php
/* blog.tpl:6: {$.site.url} */
 echo $tpl->getStorage()->site_config["url"]; ?>/blog/<?php
/* blog.tpl:6: {$post.slug} */
 echo $var["post"]["slug"]; ?>"><?php
/* blog.tpl:6: {$post.title} */
 echo $var["post"]["title"]; ?></a></h3> <p>Posted on <?php
/* blog.tpl:7: {$post.date} */
 echo $var["post"]["date"]; ?></p> <div><?php
/* blog.tpl:8: {$post.summary} */
 echo $var["post"]["summary"]; ?></div> <?php
/* blog.tpl:9: {/foreach} */
   } } ?> </div>  <?php
/* base.tpl:27: {Morfy::runAction('theme_content_after')} */
 echo Morfy::runAction('theme_content_after'); ?> </div> <div id="footer"> <div class="container"> <p class="text-muted pull-right">Powered by <a href="http://morfy.org" title="Simple and fast file-based CMS">Morfy</a></p> </div> </div>   <script src="<?php
/* base.tpl:36: {$.site.url} */
 echo $tpl->getStorage()->site_config["url"]; ?>/themes/<?php
/* base.tpl:36: {$.site.theme} */
 echo $tpl->getStorage()->site_config["theme"]; ?>/assets/js/jquery.min.js"></script> <script src="<?php
/* base.tpl:37: {$.site.url} */
 echo $tpl->getStorage()->site_config["url"]; ?>/themes/<?php
/* base.tpl:37: {$.site.theme} */
 echo $tpl->getStorage()->site_config["theme"]; ?>/assets/js/bootstrap.min.js"></script> <?php
/* base.tpl:38: {Morfy::runAction('theme_footer')} */
 echo Morfy::runAction('theme_footer'); ?> </body> </html> <?php
}, array(
	'options' => 16576,
	'provider' => false,
	'name' => 'blog.tpl',
	'base_name' => 'blog.tpl',
	'time' => 1445769054,
	'depends' => array (
  0 => 
  array (
    'base.tpl' => 1445769054,
    'blog.tpl' => 1445769054,
  ),
),
	'macros' => array(),

        ));
