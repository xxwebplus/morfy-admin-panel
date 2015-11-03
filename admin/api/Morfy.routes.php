<?php defined('PANEL_ACCESS') or die('No direct script access.');

// new panel
$p = new Panel();


/*  = Sections
--------------------------------------------*/


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


/*
* @name   Blocks
* @desc   if session user get Blocks
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



/*
* @name   Uploads
* @desc   if session user get Uploads
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



























/*  ACTIONS GOES HERE
------------------------------------------------*/



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









/*  PAGES AND BLOCKS SECTION GOES HERE
------------------------------------------------*/

/*
* @name   Search
* @sample /action/search/pages/about
*/
$p->route('/action/search/(:any)/(:any)', function($dir = '',$query = '') use($p) {
    // get file url
    $directory = STORAGE.'/'.$dir;
    // scan to obtain files
    $scan = File::scan($directory);
    // start template
    $result = '<ul>';
    // init count to 0
    $count = 0;
    foreach ($scan as $item) {
      // remove storage\$dir
      $item = str_replace(STORAGE.'/'.$dir, '', $item);
      // search query with preg_match
      if(preg_match('/'.urldecode($query).'/i', $item)){
        // count +1
        $count++;
        // template
        $result .= '<li>
                      <a href="
                        '.$p->Url().'/action/edit/'.Token::generate().'/'.
                        base64_encode($directory.$item).'">
                        '.File::name($item).'
                      </a>
                    </li>';
      }
    }
    $result .= '</ul>';
    // render view
    $p->view('actions',[
        'title' => Panel::$lang['Search'],
        'html' => '<div class="preview">
                    <h3>'.$count.' results for '.$query.'</h3>
                    '.$result.'
                    <a class="btn" href="javascript:void(0);" onclick="return history.back(0)">
                      '.Panel::$lang['back'].'
                    </a>
                  </div>'
    ]);

});
$p->route('/action/searchfiles/(:any)', function($query = '') use($p) {
    // get file url
    $directory = UPLOADS;
    // scan to obtain files
    $scan = File::scan($directory);
    // start template
    $result = '<ul>';
    // init count to 0
    $count = 0;
    foreach ($scan as $item) {
      // remove storage\$dir
      $item = str_replace(UPLOADS, '', $item);
      // search query with preg_match
      if(preg_match('/'.urldecode($query).'/i', $item)){
        // count +1
        $count++;
        // template
        $result .= '<li>
                      <a href="
                        '.$p->Url().'/action/uploads/preview/'.base64_encode($directory.$item).'">
                        '.File::name($item).'.'.File::ext($item).'
                      </a>
                    </li>';
      }
    }
    $result .= '</ul>';
    // render view
    $p->view('actions',[
        'title' => Panel::$lang['Search'],
        'html' => '<div class="preview">
                    <h3>'.$count.' results for '.$query.'</h3>
                    '.$result.'
                    <a class="btn" href="javascript:void(0);" onclick="return history.back(0)">
                      '.Panel::$lang['back'].'
                    </a>
                  </div>'
    ]);

});












/*
* @name   Preview
* @desc   Open preview on blank page
*/
$p->route(array('/action/preview/(:any)'), function($file) use($p){
    // remove dir
    $link = str_replace(PAGES, '', base64_decode($file));
    // remove .md
    $link = str_replace('.md', '', $link);
    Request::redirect($p::$site['url'].$link);
});












/*
* @name   Edit
* @desc   Edit page ( :any use base64_encode remenber decode file)
*/
$p->route('/action/edit/(:any)/(:any)', function($token,$file) use($p){
  if(Session::exists('user')){
    if (Token::check($token)) {

      // directory
      $path = base64_decode($file);

      // search pages or blocks in url
      $url = '';
      if(preg_match('/pages/i',$path)){
        $url = 'pages';
      }else if(preg_match('/blocks/i',$path)){
        $url = 'blocks';
      }

      // update file
      if(Request::post('saveFile')){
        if(Request::post('token')){
          $content = Request::post('updateFile');
          File::setContent($path,$content);
          Request::redirect($p->Url().'/'.$url);
        }else{
          die('crsf Detect!');
        }
      }

      $p->view('actions',[
        'url' => $url,
        'title' => Panel::$lang['Edit_File'],
        'html' => '<div class="row">
                      <div class="box-1 col">
                        <form method="post">
                          <input type="hidden" name="token" value="'.Token::generate().'">
                          <label class="editor-label"><b>Name: </b>'.File::name($path).'</label>
                          <textarea class="editor" name="updateFile">'.File::getContent($path).'</textarea>
                          <input class="btn" type="submit" name="saveFile" value="'.Panel::$lang['Update'].'">
                          <a class="btn btn-danger" href="'.$p->url().'/'.$url.'">Cancel</a>
                        </form>
                      </div>
                  </div>'
      ]);
    }else{
      die('crsf Detect');
    }
  }
});






/*
* @name   New File
* @desc   New page ( :any use base64_encode remenber decode file)
*/
$p->route('/action/newfile/(:any)/(:any)', function($token,$file) use($p){
  if(Session::exists('user')){
    if (Token::check($token)) {
      // directory
      $path = base64_decode($file);

      // search pages or blocks in url
      $url = '';
      $textContent = '';
      if(preg_match('/pages/i',$path)){
        $url = 'pages';
        $textContent = '
---
title: 
date: '.date('d/m/y').'
template: index
---
        ';
      }else if(preg_match('/blocks/i',$path)){
        $url = 'blocks';
        $textContent = 'Write here your text block...';
      }

      // get directory without base url
      $directory = str_replace(STORAGE.'/', '', $path); 
      $directory = str_replace(STORAGE.'/'.File::name($path).'.'.File::ext($path), '', $path); 

      $error = '';

      // save file
      if(Request::post('saveFile')){
        if(Request::post('token')){
          $filename = $p->SeoLink(Request::post('filename'));
          $content = Request::post('newfile');
          if(File::exists(STORAGE.'/'.$path.'/'.$filename.'.md')){
            $error = '<span class="error">'.Panel::$lang['File_Name_Exists'].'</span>';
          }else{
            File::setContent(STORAGE.'/'.$path.'/'.$filename.'.md',$content);
            Request::redirect($p->Url().'/'.$url);
          }
        }else{
          die('crsf Detect!');
        }
      }


      $p->view('actions',[
        'url' => $url,
        'title' => Panel::$lang['New_File'],
        'html' => '<div class="row">
            <div class="box-1 col">
              '.$error.'
              <form method="post">
                <input type="hidden" name="token" value="'.Token::generate().'">
                <input type="text" value="" name="filename" required placeholder="File name">
                <textarea class="editor" name="newfile">'.$textContent.'</textarea>
                <input class="btn" type="submit" name="saveFile" value="'.Panel::$lang['Save_file'].'">
                <a class="btn btn-danger" href="'.$p->url().'/'.$url.'">'.Panel::$lang['Cancel'].'</a>
              </form>
            </div>
        </div>'
      ]);
    }else{
      die('crsf Detect');
    }
  }
});












/*
* @name   New Folder
* @desc   Create new folder ( :any use base64_encode remenber decode file)
*/
$p->route('/action/newfolder/(:any)/(:any)', function($token,$file) use($p){
  if(Session::exists('user')){
    if (Token::check($token)) {
      // directory
      $dir = base64_decode($file);
      // redirect to edit index
      $url = str_replace(STORAGE.'/', '', $dir);
      if($url !== 'pages'){
        $url = 'pages';
      }
      // error
      $error = '';      
      // submit function
      if(Request::post('create_new_folder')){
        // check token
        if(Token::check(Request::post('token'))){
          // if empty
          if(Request::post('new_folder_name') !== ''){
            // name of folder
            $foldername = STORAGE.'/'.$dir.'/'.$p->SeoLink(Request::post('new_folder_name'));
            // if exists
            if(!Dir::exists($foldername)){
                // create folder
                Dir::create($foldername);
                // create index file with folder name
                File::setContent($foldername.'/index.md',"---\ntitle: Holas\n---");
                // redirect to edit index
                Request::redirect($p->url().'/action/edit/'.Token::generate().'/'.base64_encode($foldername.'/index.md'));       
            }else{
              // if exists
              $error = '<span class="error">'.Panel::$lang['Folder_Already_Exists'].'</span>';
            }             
          }else{
            // if empty input value
            $error = '<span class="error">'.Panel::$lang['Folder_Name_Required'].'</span>';
          }
        }else{
          die('crsf detect');
        }
      }
      // template
      $p->view('actions',[
        'title' => Panel::$lang['New_Folder'],
        'content' => $dir,
        'html' => '<div class="info">
                    <form method="post">
                      <input type="hidden" name="token" value="'.Token::generate().'">
                      <label>'.Panel::$lang['New_Folder'].' : <code>'.base64_decode($file).'</code></label>
                      <input type="text" name="new_folder_name">
                      <input type="submit" name="create_new_folder" value="'.Panel::$lang['New_Folder'].'">
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
          $error = '<span class="error">'.Panel::$lang['exencial_file'].'</span>';
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
                // redirect to edit index
                request::redirect($p->url().'/'.$url); 
            }else{
              // if exists
              $error = '<span class="error">'.Panel::$lang['File_Name_Exists'].'</span>';
            }             
          }else{
            // if empty input value
            $error = '<span class="error">'.Panel::$lang['File_Name_Required'].'</span>';
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
                      <input type="submit" name="rename" value="'.Panel::$lang['Rename_File'].'">
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






























/*  UPLOADS SECTION GOES HERE
------------------------------------------------*/


/*
* @name   Preview
* @desc   Open preview on blank page
*/
$p->route('/action/uploads/preview/(:any)', function($file) use($p){
    // remove dir
    $link = str_replace(UPLOADS, '', base64_decode($file));
    $link = str_replace('\\', '/', $link);
    $link = str_replace('//', '/', $link);
    // check mime types
    $template = '';
    // decode file
    $path = base64_decode($file);
    $link = Url::sanitizeURL($link);
    // check mime types
    if(File::mime($path)){
      if(File::ext($path) == 'jpg'  || File::ext($path) == 'JPG'  ||
        File::ext($path) == 'png'   || File::ext($path) == 'PNG'  ||
        File::ext($path) == 'jpeg'  || File::ext($path) == 'JPEG' ||
        File::ext($path) == 'gif'){
        // get image size
        list($width, $height) = getimagesize($path);
        // image template
        $template = '
          <div class="preview">
            <div class="image-preview">
              <img src="'.$p::$site['url'].'/public/uploads/'.$link.'"/>
            </div>
            <div class="image-info">
              <ul>
                <li><b>Filename: </b>'.File::name($path).'</li>
                <li><b>Extension: </b>'.File::ext($path).'</li>
                <li><b>Size: </b>'.$width.'x'.$height.'px</li>
                <li class="code"><b>Markdown: </b>![text img]({$.site.url}/public/uploads/'.$link.')</li>
                <li class="code"><b>Html: </b>&lt;img src="{$.site.url}/public/uploads/'.$link.'" /&gt;</li>
              </ul>
            </div>
            <a class="btn" href="'.$p->url().'/uploads">'.Panel::$lang['back_to_uploads'].'</a>
          </div>';

      }else{
        // other template files
        $template = '
          <div class="preview">
            <div class="file-preview">
              <span class="information">
                  '.Panel::$lang['no_preview_for_this_file'].'
                  <a download href="'.$p::$site['url'].'/public/uploads/'.$link.'">'.File::name($path)   .'</a>
              </span>   
            </div>
            <div class="file-info">
              <ul>
                <li><b>Filename: </b>'.File::name($path).'</li>
                <li><b>Extension: </b>'.File::ext($path).'</li>
                <li class="code"><b>Markdown: </b>[text link]({$.site.url}/public/uploads/'.$link.')</li>
                <li class="code"><b>Html: </b>&lt;a href="{$.site.url}/public/uploads/'.$link.'" download &gt;text link&lt;/a&gt;</li>
              </ul>
            </div>
            <a class="btn" href="'.$p->url().'/uploads">'.Panel::$lang['back_to_uploads'].'</a>
          </div>';

      }
    }

    $p->view('actions',[
      'type' => 'Upload Preview',
      'title' => Panel::$lang['Preview'],
      'content' => $file,
      'html' => $template
    ]);
});












/*
* @name   Uploads New File
* @desc   New file ( :any use base64_encode remenber decode file)
*/
$p->route('/action/uploads/newfile/(:any)/(:any)', function($token,$file) use($p){
  if(Session::exists('user')){
    if (Token::check($token)) {

      $path = base64_decode($file);
      $error = ''; 
      $AllowedExtensions = ['gif','jpeg','jpg','png','md','txt','zip','pdf','mp4','webm','html','css','js','mp3','vaw','doc'];
      if(Request::post('uploadFile')){
          if(Request::post('token')){
              $files = $_FILES['file']['name'];
              $path = str_replace('\\','/',$path);
              // change blank spaces for -
              $fileUploaded = PUBLICFOLDER.'/'.$path.'/'.$p->SeoLink(File::name($_FILES['file']['name'])).'.'.File::ext($_FILES['file']['name']);
              if(File::exists($fileUploaded)){
                $error = '<span class="error">'.Panel::$lang['File_Name_Exists'].'</span>';
              }else{
                if(!in_array(File::ext($_FILES['file']['name']), $AllowedExtensions)) { 
                  die('Extension not allowed');
                }
                if(move_uploaded_file($_FILES['file']['tmp_name'], $fileUploaded)) {
                    Request::redirect($p->Url().'/action/uploads/preview/'.base64_encode($fileUploaded));
                }
              }
          }else{
            die('crsf Detect !');
          }
      }

      $p->View('actions',[
        'title' => 'Upload File',
        'content' => $path,
        'html' => '
                <div class="info">
                  '.$error.'
                  <h3><b>'.Panel::$lang['Upload_file'].' on:</b> '.$path.'</h3>
                  <form method="post" action="" enctype="multipart/form-data">
                      <input type="hidden" name="token" value="'.Token::generate().'">
                      <input name="file" class="upload" type="file" required/>
                      <input type="submit" name="uploadFile" value="'.Panel::$lang['Upload'].'">
                      <a class="btn btn-danger" href="'.$p->url().'/uploads">'.Panel::$lang['Cancel'].'</a>
                  </form>
                </div>'
      ]);

    }else{
      die('crsf Detect');
    }
  }
});


/*
* @name   Uploads New Folder
* @desc   New folder ( :any use base64_encode remenber decode file)
*/
$p->route('/action/uploads/newfolder/(:any)/(:any)', function($token,$file) use($p){
  if(Session::exists('user')){
    if (Token::check($token)) {
      // directory
      $dir = base64_decode($file);
      // error
      $error = '';      
      // submit function
      if(Request::post('create_new_folder')){
        // check token
        if(Token::check(Request::post('token'))){  
          // if empty
          if(Request::post('new_folder_name') !== ''){
            $dir = str_replace('\\','/',$dir);
            // name of folder
            $foldername = PUBLICFOLDER.'/'.$dir.'/'.$p->SeoLink(Request::post('new_folder_name'));
            $foldername = str_replace('//','/',$foldername);
            // if exists
            if(!Dir::exists($foldername)){
                // create folder
                Dir::create($foldername);
                // init folder with one file
                File::setContent($foldername.'/upload_here.txt',$foldername);
                // redirect to edit index
                Request::redirect($p->url().'/uploads');       
            }else{
              // if exists
              $error = '<span class="error">'.Panel::$lang['Folder_Already_Exists'].'</span>';
            }             
          }else{
            // if empty input value
            $error = '<span class="error">'.Panel::$lang['Folder_Name_Required'].'</span>';
          }
        }else{
          die('crsf detect');
        }
      }
      // template
      $p->view('actions',[
        'title' => Panel::$lang['New_Folder'],
        'content' => $dir,
        'html' => '<div class="info">
                    <form method="post">
                      <input type="hidden" name="token" value="'.Token::generate().'">
                      <label>'.Panel::$lang['New_Folder'].' : <code>'.base64_decode($file).'</code></label>
                      <input type="text" name="new_folder_name">
                      <input type="submit" name="create_new_folder" value="'.Panel::$lang['New_Folder'].'">
                      <a class="btn btn-danger" href="'.$p->url().'/uploads">'.Panel::$lang['Cancel'].'</a>
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
                // redirect to edit index
                request::redirect($p->url().'/uploads'); 
            }else{
              // if exists
              $error = '<span class="error">'.Panel::$lang['File_Name_Exists'].'</span>';
            }             
          }else{
            // if empty input value
            $error = '<span class="error">'.Panel::$lang['File_Name_Required'].'</span>';
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
                      <input type="submit" name="rename" value="'.Panel::$lang['Rename_File'].'">
                      <a class="btn btn-danger" href="'.$p->url().'/uploads">'.Panel::$lang['Cancel'].'</a>
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






// start
$p->lauch();


