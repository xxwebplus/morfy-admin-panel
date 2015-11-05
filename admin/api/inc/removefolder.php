<?php defined('PANEL_ACCESS') or die('No direct script access.');
















/*
* @name   Remove folder
* @desc   Remove folder ( :any use base64_encode remenber decode file)
*/
$p->route('/action/removefolder/(:any)/(:any)', function($token,$file) use($p){
  if(Session::exists('user')){
    if (Token::check($token)) {
      // decode file
      $filename = base64_decode($file);
      // redirect to edit index
      $url = str_replace(STORAGE.'/', '', $filename);
      $url = str_replace('/'.File::name($url).'.'.File::ext($url),'',$url);
      // check url 
      if($url == 'blocks') $url = 'blocks'; else $url = 'pages';
      // error file
      $error = '';
      // submit function
      if(Request::post('remove')){
        // check token
        if(Token::check(Request::post('token'))){
            Dir::delete(STORAGE.'/'.$filename);
            // redirect to edit index
            request::redirect($p->url().'/'.$url);             
        }else{
          die('crsf detect');
        }
      }
      // template
      $p->view('actions',[
        'title' => Panel::$lang['Remove_Folder'],
        'content' => $filename,
        'html' => '<div class="info">
                    <form method="post">
                      <input type="hidden" name="token" value="'.Token::generate().'">
                      <label>'.Panel::$lang['Are_you_sure_to_delete_folder'].' : <code>'.File::name(base64_decode($file)).'</code></label>
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


























/*
* @name   Uploads rename
* @desc   New folder ( :any use base64_encode remenber decode file)
*/
$p->route('/action/uploads/removefolder/(:any)/(:any)', function($token,$file) use($p){
  if(Session::exists('user')){
    if (Token::check($token)) {
      // decode file
      $path = base64_decode($file);
      // submit function
      if(Request::post('remove')){
        // check token
        if(Token::check(Request::post('token'))){
            Dir::delete(PUBLICFOLDER.'/'.$path);
            // redirect to edit index
            request::redirect($p->url().'/uploads');             
        }else{
          die('crsf detect');
        }
      }
      // template
      $p->view('actions',[
        'title' => Panel::$lang['Remove_Folder'],
        'content' => $path,
        'html' => '<div class="info">
                    <form method="post">
                      <input type="hidden" name="token" value="'.Token::generate().'">
                      <label>'.Panel::$lang['Are_you_sure_to_delete_folder'].' : <code>'.File::name(base64_decode($file)).'</code></label>
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

