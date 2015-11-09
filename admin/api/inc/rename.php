<?php defined('PANEL_ACCESS') or die('No direct script access.');


/*    RENAME FILE PAGES/BLOCKS
--------------------------------------*/

/*
* @name   Rename File
* @desc   Rename File ( :any use base64_encode remenber decode file)
*/
$p->route('/action/rename/(:any)/(:any)', function($token,$file) use($p){
  if(Session::exists('user')){
    if (Token::check($token)) {
      // directory
      $filename = base64_decode($file);

      // function to redirect
      $url = '';
      if(preg_match('/pages/', $filename)) $url = 'pages'; else $url = 'blocks';

      // error
      $error = '';
      // check if is index
      if(File::name($filename) == 'index'){
          $error = '<span class="well red">'.Panel::$lang['exencial_file'].'</span>';
      }
      // submit function
      if(Request::post('rename')){
        // check token
        if(Token::check(Request::post('token'))){
          // if empty
          if(Request::post('rename_file_name') !== ''){
            $to = str_replace(File::name($filename).'.'.File::ext($filename), '', $filename);
            // if exists
            if(!File::exists($to.Request::post('rename_file_name').'.md')){
                // rename file
                File::rename($filename,$to.'/'.$p->SeoLink(Request::post('rename_file_name')).'.md');
                // set notification
                $p->setMsg($p::$lang['Success_rename']);
                // redirect to edit index
                request::redirect($p->url().'/'.$url);
            }else{
              // if exists
              $error = '<span class="well red">'.Panel::$lang['File_Name_Exists'].'</span>';
            }
          }else{
            // if empty input value
            $error = '<span class="well red">'.Panel::$lang['File_Name_Required'].'</span>';
          }
        }else{
          die('crsf detect');
        }
      }

      // template
      $p->view('actions',[
        'title' => Panel::$lang['Rename_File'],
        'content' => $filename,
        'html' => '<div class="info">
                    <form method="post">
                      <input type="hidden" name="token" value="'.Token::generate().'">
                      <label>'.Panel::$lang['Rename_File'].' :<code>'.File::name(base64_decode($file)).'</code></label>
                      <input type="text" name="rename_file_name" value="'.File::name(base64_decode($file)).'" required>
                      <input class="btn blue" type="submit" name="rename" value="'.Panel::$lang['Rename_File'].'">
                      <a class="btn red" href="'.$p->url().'/'.$url.'">'.Panel::$lang['Cancel'].'</a>
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




/*    RENAME FILE UPLOADS
-----------------------------*/


/*
* @name   Uploads rename
* @desc   rename file ( :any use base64_encode remenber decode file)
*/
$p->route('/action/uploads/rename/(:any)/(:any)', function($token,$file) use($p){
  if(Session::exists('user')){
    if (Token::check($token)) {
      // directory
      $filename = base64_decode($file);
      // error
      $error = '';
      // submit function
      if(Request::post('rename')){
        // check token
        if(Token::check(Request::post('token'))){
          // if empty
          if(Request::post('rename_file_name') !== ''){
            $to = str_replace(File::name($filename).'.'.File::ext($filename), '', $filename);
            // if exists
            if(!File::exists($to.Request::post('rename_file_name').'.'.File::ext($filename))){
                // rename file
                File::rename($filename,$to.'/'.$p->SeoLink(Request::post('rename_file_name')).'.'.File::ext($filename));
                // set notification
                $p->setMsg($p::$lang['Success_rename']);
                // redirect to edit index
                request::redirect($p->url().'/uploads');
            }else{
              // if exists
              $error = '<span class="well red">'.Panel::$lang['File_Name_Exists'].'</span>';
            }
          }else{
            // if empty input value
            $error = '<span class="well red">'.Panel::$lang['File_Name_Required'].'</span>';
          }
        }else{
          die('crsf detect');
        }
      }

      // template
      $p->view('actions',[
        'title' => Panel::$lang['Rename_File'],
        'content' => $filename,
        'html' => '<div class="info">
                    <form method="post">
                      <input type="hidden" name="token" value="'.Token::generate().'">
                      <label>'.Panel::$lang['Rename_File'].' :<code>'.File::name(base64_decode($file)).'</code></label>
                      <input type="text" name="rename_file_name" value="'.File::name(base64_decode($file)).'" required>
                      <input class="btn blue" type="submit" name="rename" value="'.Panel::$lang['Rename_File'].'">
                      <a class="btn red" href="'.$p->url().'/uploads">'.Panel::$lang['Cancel'].'</a>
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












/*    RENAME FILE THEMES
-----------------------------*/


/*
* @name   Themes rename
* @desc   rename file ( :any use base64_encode remenber decode file)
*/
$p->route('/action/themes/rename/(:any)/(:any)', function($token,$file) use($p){
  if(Session::exists('user')){
    if (Token::check($token)) {
      // directory
      $filename = base64_decode($file);
      // function to redirect
      $url = '';
      if(preg_match('/css/', $filename)) $url = 'stylesheets';
      else if(preg_match('/js/', $filename)) $url = 'javascript';
      else$url = 'templates';


      // error
      $error = '';
      // submit function
      if(Request::post('rename')){
        // check token
        if(Token::check(Request::post('token'))){
          // if empty
          if(Request::post('rename_file_name') !== ''){
            $to = str_replace(File::name($filename).'.'.File::ext($filename), '', $filename);
            // if exists
            if(!File::exists($to.Request::post('rename_file_name').'.'.File::ext($filename))){
                // rename file
                File::rename($filename,$to.'/'.$p->SeoLink(Request::post('rename_file_name')).'.'.File::ext($filename));
                // set notification
                $p->setMsg($p::$lang['Success_rename']);
                // redirect to edit index
                request::redirect($p->url().'/'.$url);
            }else{
              // if exists
              $error = '<span class="well red">'.Panel::$lang['File_Name_Exists'].'</span>';
            }
          }else{
            // if empty input value
            $error = '<span class="well red">'.Panel::$lang['File_Name_Required'].'</span>';
          }
        }else{
          die('crsf detect');
        }
      }

      // template
      $p->view('actions',[
        'title' => Panel::$lang['Rename_File'],
        'content' => $filename,
        'html' => '<div class="info">
                    <form method="post">
                      <input type="hidden" name="token" value="'.Token::generate().'">
                      <label>'.Panel::$lang['Rename_File'].' :<code>'.File::name(base64_decode($file)).'</code></label>
                      <input type="text" name="rename_file_name" value="'.File::name(base64_decode($file)).'" required>
                      <input class="btn blue" type="submit" name="rename" value="'.Panel::$lang['Rename_File'].'">
                      <a class="btn red" href="'.$p->url().'/'.$url.'">'.Panel::$lang['Cancel'].'</a>
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












/*    RENAME FILE BACKUPS
-----------------------------*/


/*
* @name   Backups rename
* @desc   rename file ( :any use base64_encode remenber decode file)
*/
$p->route('/action/backups/rename/(:any)/(:any)', function($token,$file) use($p){
  if(Session::exists('user')){
    if (Token::check($token)) {
      // directory
      $filename = base64_decode($file);
      // error
      $error = '';
      // submit function
      if(Request::post('rename')){
        // check token
        if(Token::check(Request::post('token'))){
          // if empty
          if(Request::post('rename_file_name') !== ''){
            $to = str_replace(File::name($filename).'.'.File::ext($filename), '', $filename);
            // if exists
            if(!File::exists($to.Request::post('rename_file_name').'.'.File::ext($filename))){
                // rename file
                File::rename($filename,$to.'/'.$p->SeoLink(Request::post('rename_file_name')).'.'.File::ext($filename));
                // set notification
                $p->setMsg($p::$lang['Success_rename']);
                // redirect to edit index
                request::redirect($p->url().'/backups');
            }else{
              // if exists
              $error = '<span class="well red">'.Panel::$lang['File_Name_Exists'].'</span>';
            }
          }else{
            // if empty input value
            $error = '<span class="well red">'.Panel::$lang['File_Name_Required'].'</span>';
          }
        }else{
          die('crsf detect');
        }
      }

      // template
      $p->view('actions',[
        'title' => Panel::$lang['Rename_File'],
        'content' => $filename,
        'html' => '<div class="info">
                    <form method="post">
                      <input type="hidden" name="token" value="'.Token::generate().'">
                      <label>'.Panel::$lang['Rename_File'].' :<code>'.File::name(base64_decode($file)).'</code></label>
                      <input type="text" name="rename_file_name" value="'.File::name(base64_decode($file)).'" required>
                      <input class="btn blue" type="submit" name="rename" value="'.Panel::$lang['Rename_File'].'">
                      <a class="btn red" href="'.$p->url().'/backups">'.Panel::$lang['Cancel'].'</a>
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
