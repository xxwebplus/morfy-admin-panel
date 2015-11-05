<?php defined('PANEL_ACCESS') or die('No direct script access.');




/*    REMOVE FILE PAGES/BLOCKS
--------------------------------------*/



/*
* @name   Remove File
* @desc   Remove File ( :any use base64_encode remenber decode file)
*/
$p->route('/action/removefile/(:any)/(:any)', function($token,$file) use($p){
  if(Session::exists('user')){
    if (Token::check($token)) {
      $filename = base64_decode($file);
      // redirect to edit index
      $url = str_replace(STORAGE.'/', '', $filename);
      $url = str_replace('/'.File::name($url).'.'.File::ext($url),'',$url);
      // check url 
      if($url == 'blocks') $url = 'blocks'; else $url = 'pages';
      $error = '';
      // check if is index
      if(File::name($filename) == 'index'){
          $error = '<span class="error">'.Panel::$lang['exencial_file'].'</span>';
      } 
      // submit function
      if(Request::post('remove')){
        // check token
        if(Token::check(Request::post('token'))){
            File::delete($filename);
            // redirect to edit index
            request::redirect($p->url().'/'.$url);           
        }else{
          die('crsf detect');
        }
      }

      $p->view('actions',[
        'title' => Panel::$lang['Remove_File'],
        'content' => $filename,
        'html' => '<div class="info">
                    <form method="post">
                      <input type="hidden" name="token" value="'.Token::generate().'">
                      <label>'.Panel::$lang['Are_you_sure_to_delete'].' : <code>'.File::name(base64_decode($file)).'</code></label>
                      <input type="submit" name="remove" value="'.Panel::$lang['Remove'].'">
                      <a class="btn btn-danger" href="'.$p->url().'/'.$url.'">'.Panel::$lang['Cancel'].'</a>
                    </form>
                    <br>
                    '.$error.'
                  </div>'
      ]);
    }else{
      die('crsf Detect');
    }
  }
});

























/*    REMOVE FILE UPLOADS
-----------------------------*/


/*
* @name   Uploads removefile
* @desc   New file ( :any use base64_encode remenber decode file)
*/
$p->route('/action/uploads/removefile/(:any)/(:any)', function($token,$file) use($p){
  if(Session::exists('user')){
    if (Token::check($token)) {
      // decode
      $path = base64_decode($file);
      // submit function
      if(Request::post('remove')){
        // check token
        if(Token::check(Request::post('token'))){
            File::delete($path);
            // redirect to edit index
            request::redirect($p->url().'/uploads');           
        }else{
          die('crsf detect');
        }
      }

      $p->view('actions',[
        'title' => Panel::$lang['Remove_File'],
        'content' => $path,
        'html' => '<div class="info">
                    <form method="post">
                      <input type="hidden" name="token" value="'.Token::generate().'">
                      <label>'.Panel::$lang['Are_you_sure_to_delete'].' : <code>'.File::name(base64_decode($file)).'</code></label>
                      <input type="submit" name="remove" value="'.Panel::$lang['Remove'].'">
                      <a class="btn btn-danger" href="'.$p->url().'/uploads">'.Panel::$lang['Cancel'].'</a>
                    </form>
                  </div>'
      ]);


    }else{
      die('crsf Detect');
    }
  }
});







/*    REMOVE FILE MEDIA
-----------------------------*/


/*
* @name   Media removefile
* @desc   Remove file on media ( :any use base64_encode remenber decode file)
*/
$p->route('/action/media/removefile/(:any)/(:any)', function($token,$file) use($p){
  if(Session::exists('user')){
    if (Token::check($token)) {

      // get json file fow with and height
      $jsonFile = PUBLICFOLDER.'/media/mdb.json';
      $json = json_decode(File::getContent($jsonFile),true);

      // submit function
      if(Request::post('remove')){
        // check token
        if(Token::check(Request::post('token'))){
          // if remove thumb and dir unlik json file
            File::delete(ROOTBASE.$json[$file]['thumb']);
            Dir::delete(ROOTBASE.$json[$file]['images']);
            unset($json[$file]);
            File::setContent($jsonFile,json_encode($json));
            Request::redirect($p->Url().'/media');
        }else{
          die('crsf detect');
        }
      }

      $p->view('actions',[
        'title' => Panel::$lang['Remove_File'],
        'content' => $json,
        'html' => '<div class="info">
                    <form method="post">
                      <input type="hidden" name="token" value="'.Token::generate().'">
                      <label>'.Panel::$lang['Are_you_sure_to_delete'].' : <code>'.$json[$file]['title'].'</code></label>
                      <input type="submit" name="remove" value="'.Panel::$lang['Remove'].'">
                      <a class="btn btn-danger" href="'.$p->url().'/media">'.Panel::$lang['Cancel'].'</a>
                    </form>
                  </div>'
      ]);


    }else{
      die('crsf Detect');
    }
  }
});


/*
* @name   Media removefile
* @desc   Remove file on media ( :any use base64_encode remenber decode file)
*/
$p->route('/action/media/uploads/removefile/(:any)/(:any)', function($id,$file) use($p){
  if(Session::exists('user')){
      File::delete(base64_decode($file));
      Request::redirect($p->Url().'/media/uploads/'.$id);
  }
});