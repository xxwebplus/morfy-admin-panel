<?php defined('PANEL_ACCESS') or die('No direct script access.');

// new panel
$p = new Panel();


/*  PAGES AND BLOCKS SECTION GOES HERE
------------------------------------------------*/
include 'inc/pages.php';



/*  ACTIONS GOES HERE
------------------------------------------------*/
include 'inc/search.php';
include 'inc/preview.php';
include 'inc/edit.php';
include 'inc/newfile.php';
include 'inc/newfolder.php';
include 'inc/rename.php';
include 'inc/removefile.php';
include 'inc/removefolder.php';

/*
* @name   Logout
* @desc   rediterct to hombe url
*/
$p->route('/action/logout', function() use($p){
  if(Session::exists('user')){
    Session::delete('user');
    Session::destroy();
    Request::redirect($p::$site['url']);
  }
});


// start
$p->lauch();


