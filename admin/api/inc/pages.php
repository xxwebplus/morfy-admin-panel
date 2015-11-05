<?php defined('PANEL_ACCESS') or die('No direct script access.');








/*    DIAGNOSTIC
-----------------------------*/


/*
* @name   Diagnostic 
* @desc   if session user get Diagnostic
*/
$p->route('/diag', function() use($p){
  if(Session::exists('user')){
    // show Diagnostic
    $p->view('diag',['title' => 'Diagnostic']);
  }
});







/*    DASHBOARD
-----------------------------*/


/*
* @name   Dashboard | login
* @desc   if session user get Dashboard
* @desc   if not redirecto to login page
*/
$p->route('/', function() use($p){
  if(Session::exists('user')){
    
    // show dashboard
    $p->view('index',[
      'title' => $p::$lang['Dashboard'],
      'pages' => count(File::scan(PAGES, 'md')),
      'media' => count(File::scan(MEDIA.'/album_thumbs')),
      'uploads' => count(File::scan(UPLOADS)),
      'blocks' => count(File::scan(BLOCKS, 'md')),
      'themes' => count(Dir::scan(ROOTBASE.'/themes')),
      'plugins' => count(Dir::scan(ROOTBASE.'/plugins'))
    ]);

  }else{
    // empty error
    $error = '';
    if(Request::post('login')){
      if(Request::post('csrf')){
        if(Request::post('pass') == $p::$site['backend_password'] && 
          Request::post('email') == $p::$site['autor']['email']){
          @Session::start();
          Session::set('user',uniqid('morfy_user'));
          Request::redirect($p::$site['url'].'/'.$p::$site['backend_folder']);
        }else{
          // password not correct show error
          $error = '<span class="login-error error">'.$p::$lang['Password_Error'].'</span>';
        }
      }else{
        // crsf
        die('crsf detect');
      }
    }
    // get template login
    $p->view('login',[
      'error' => $error
    ]);
  }
});














/*    PAGES
-----------------------------*/

/*
* @name   Pages
* @desc   if session user get Pages
* @desc   if not redirecto to login page
*/
$p->route(array('/pages','/pages/(:num)'),function($offset = 1) use($p){
  if(Session::exists('user')){

    $per_page = 6;
    $content = File::scan(PAGES);
    rsort($content);
    $showPag = array_chunk($content, $per_page);

    $prev = '';
    $next = '';
    if($offset > 1) {
        $prev = '<a class="btn" href="'.$p->Url().'/pages/'.($offset - 1).'"><i class="ti-arrow-left"></i></a>';
    } else {
        $prev = '<span class="btn disable"><i class="ti-arrow-left"></i></span>';
    }
    if($offset < ceil(count($content) / $per_page)) {
        $next = '<a href="' . $p->Url().'/pages/'.($offset + 1).'" class="btn"><i class="ti-arrow-right"></i></a>';
    } else {
        $next = '<span class="btn disable"><i class="ti-arrow-right"></i></span>';
    }
    // show pages
    $p->view('pages',[
      'title' => Panel::$lang['Pages'],
      'offset' => $offset,
      'prev' => $prev,
      'next' => $next,
      'content' => $showPag[$offset - 1]
    ]);
  }else{
    Request::redirect($p::$site['url'].'/'.$p::$site['backend_folder']);
  }
});











/*    UPLOADS
-----------------------------*/


/*
* @name   Uploads
* @desc   if session user get Uploads
* @desc   if not redirecto to login page
*/
$p->route(array('/uploads','/uploads/(:num)'),function($offset = 1) use($p){
  if(Session::exists('user')){
    // results per page
    $per_page = 6;
    $content = File::scan(UPLOADS);
    // check files 
    if($content){
      rsort($content);
      $showPag = array_chunk($content, $per_page);

      $prev = '';
      $next = '';
      if($offset > 1) {
          $prev = '<a class="btn" href="'.$p->Url().'/uploads/'.($offset - 1).'"><i class="ti-arrow-left"></i></a>';
      } else {
          $prev = '<span class="btn disable"><i class="ti-arrow-left"></i></span>';
      }
      if($offset < ceil(count($content) / $per_page)) {
          $next = '<a href="' . $p->Url().'/uploads/'.($offset + 1).'" class="btn"><i class="ti-arrow-right"></i></a>';
      } else {
          $next = '<span class="btn disable"><i class="ti-arrow-right"></i></span>';
      }

      // show blocks
      $p->view('uploads',[
        'title' => Panel::$lang['Uploads'],
        'offset' => $offset,
        'prev' => $prev,
        'next' => $next,
        'content' => $showPag[$offset - 1]
      ]);

  }else{
          // show blocks
      $p->view('uploads',[
        'title' => Panel::$lang['Uploads'],
        'offset' => 1,
        'prev' => '',
        'next' => '',
        'content' => $content
      ]);
  }

  }else{
    Request::redirect($p::$site['url'].'/'.$p::$site['backend_folder']);
  }
});












/*    BLOCKS
-----------------------------*/

/*
* @name   Blocks
* @desc   if session user get Blocks
*/
$p->route(array('/blocks','/blocks/(:num)'),function($offset = 1) use($p){
  if(Session::exists('user')){

    $per_page = 6;
    $content = File::scan(BLOCKS);
    if($content){
      rsort($content);
      $showPag = array_chunk($content, $per_page);

      $prev = '';
      $next = '';
      if($offset > 1) {
          $prev = '<a class="btn" href="'.$p->Url().'/blocks/'.($offset - 1).'"><i class="ti-arrow-left"></i></a>';
      } else {
          $prev = '<span class="btn disable"><i class="ti-arrow-left"></i></span>';
      }
      if($offset < ceil(count($content) / $per_page)) {
          $next = '<a href="' . $p->Url().'/blocks/'.($offset + 1).'" class="btn"><i class="ti-arrow-right"></i></a>';
      } else {
          $next = '<span class="btn disable"><i class="ti-arrow-right"></i></span>';
      }
      // show blocks
      $p->view('blocks',[
        'title' => Panel::$lang['Blocks'],
        'offset' => $offset,
        'prev' => $prev,
        'next' => $next,
        'content' => $showPag[$offset - 1]
      ]);
    }else{
      // show blocks
      $p->view('blocks',[
        'title' => Panel::$lang['Blocks'],
        'offset' => '',
        'prev' => '',
        'next' => '',
        'content' => $content
      ]);
    }
  }else{
    Request::redirect($p::$site['url'].'/'.$p::$site['backend_folder']);
  }
});
















/*    MEDIA
-----------------------------*/

/*
* @name   Media
* @desc   if session user get Media
*/
$p->route(array('/media','/media/(:num)'),function($offset = 1) use($p){
  if(Session::exists('user')){
    // items per page
    $per_page = 3;
    // array json
    $json = [];
    // next prev 
    $prev = '';
    $next = '';
    // template
    $templateAll = '';
    // json file
    $jsonFile = ROOTBASE.'/public/media/mdb.json';
    // if not exists create 
    if(!File::exists($jsonFile)){
      File::setContent($jsonFile,'[]');
      // create folders album and album_thumbs
      Dir::create(ROOTBASE.'/public/media');
      Dir::create(ROOTBASE.'/public/media/albums');
      Dir::create(ROOTBASE.'/public/media/album_thumbs');
    }else{
      // get json file
      $json = json_decode(File::getContent($jsonFile),true);
      if(count($json) > 0){
        rsort($json);
        $showPag = array_chunk($json, $per_page);
        if($offset > 1) {
            $prev = '<a class="btn" href="'.$p->Url().'/media/'.($offset - 1).'"><i class="ti-arrow-left"></i></a>';
        } else {
            $prev = '<span class="btn disable"><i class="ti-arrow-left"></i></span>';
        }
        if($offset < ceil(count($json) / $per_page)) {
            $next = '<a href="' . $p->Url().'/media/'.($offset + 1).'" class="btn"><i class="ti-arrow-right"></i></a>';
        } else {
            $next = '<span class="btn disable"><i class="ti-arrow-right"></i></span>';
        }

        // all media files
        foreach($showPag[$offset - 1] as $media) {
            $templateAll .= '
            <div class="box-1 col">
              <div class="media">
                <div class="image-media">
                    <img src="'.Panel::$site['url'].$media['thumb'].'"/>
                </div>
                <div class="info-media">
                  <ul>
                    <li><b>Title: </b>'.$media['title'].'</lI>
                    <li><b>Description: </b>'.$p->TextCut($media['desc'],20).'</lI>
                    <li><b>Width: </b>'.$media['width'].'</li>
                    <li><b>Height: </b>'.$media['height'].'</li>
                    <li><b>Tag: </b>'.$media['tag'].'</li>
                    <li><b>Markdown: </b></li>
                    <li><code>[link text]('.Panel::$site['url'].'/media?action=view&id='.$media['id'].')</code></li>
                    <li><b>Html: </b></li>
                    <li><code>&lt;a href="'.Panel::$site['url'].'/media?action=view&id='.$media['id'].'"&gt;link Text&lt;/a&gt;</code></li>
                    <li>
                        <a class="btn editfile" 
                        href="'.$p->Url().'/action/media/edit/'.$media['id'].'" 
                        title="'.Panel::$lang['Edit_File'].'"><i class="ti-pencil-alt"></i></a>
                        <a class="btn renamefile" 
                        href="'.$p->Url().'/media/uploads/'.$media['id'].'" 
                        title="'.Panel::$lang['Upload_media'].'"><i class="ti-upload"></i></a>
                        <a class="btn removefile" 
                        href="'.$p->Url().'/action/media/removefile/'.Token::generate().'/'.$media['id'].'" 
                        title="'.Panel::$lang['Remove_File'].'"><i class="ti-trash"></i></a>
                    </li>
                  </ul>
                </div>
              </div>
            </div>'; 
        }
      }
    }

    // show Media
    $p->view('media',[
      'title' => Panel::$lang['Media'],
      'offset' => $offset,
      'prev' => $prev,
      'next' => $next,
      'content' => (count($json) > 0) ? $templateAll : '<div class="error">Empty Media albums</div>'
    ]);

  }else{
    Request::redirect($p::$site['url'].'/'.$p::$site['backend_folder']);
  }
});








