<?php defined('PANEL_ACCESS') or die('No direct script access.');


/*    EDIT PAGES/BLOCKS
-----------------------------*/


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
          // save content
          File::setContent($path,$content);
          // set notification
          $p->setMsg($p::$lang['Success_edit']);
          // redirect
          Request::redirect($p->Url().'/'.$url);
        }else{
          die('crsf Detect!');
        }
      }

      $p->view('actions',[
        'url' => $url,
        'title' => Panel::$lang['Edit_File'],
        'html' => '<form method="post">
                    <input type="hidden" name="token" value="'.Token::generate().'">
                    <section class="subheader">
                      <div class="row">
                        <div class="box-1 col">
                          <label class="editor-label"><b>Name: </b>'.File::name($path).'</label>
                        </div>
                      </div>
                    </section>
                    <div class="row">
                        <div class="box-1 col">
                          <textarea class="editor black" name="updateFile">'.File::getContent($path).'</textarea>
                          <input class="btn blue" type="submit" name="saveFile" value="'.Panel::$lang['Update'].'">
                          <a class="btn red" href="'.$p->url().'/'.$url.'">Cancel</a>
                        </div>
                    </div>
                  </form>'
      ]);
    }else{
      die('crsf Detect');
    }
  }
});



/*  EDIT FILE MEDIA
------------------------------------*/

/*
* @name   Edit media Media
* @desc   if session user get Edit Media
*/
$p->route('/action/media/edit/(:num)',function($id) use($p){

  if(Session::exists('user')){
    $error = '';
    $AllowedExtensions = ['gif','jpeg','jpg','png'];
    // json file
    $jsonFile = PUBLICFOLDER.'/media/mdb.json';
    // decode json
    $json = json_decode(File::getContent($jsonFile),true);
    if(Request::post('upload')){
      if(Request::post('token')){
        // json array remenber encode
        $json[$id] = [
          'id' => $id,
          'title' => (Request::post('title')) ? $p->toText(Request::post('title')) : $p->toText($json[$id]['title']),
          'desc' =>  (Request::post('desc')) ? $p->toText(Request::post('desc')) : $p->toText($json[$id]['desc']),
          'thumb' => ($_FILES['file_upload']['name']) ? '/public/media/album_thumbs/album_'.$id.'.'.File::ext($_FILES['file_upload']['name']) : $json[$id]['thumb'],
          'images' => '/public/media/albums/'.'album_'.$id,
          'tag' => (Request::post('tag')) ? $p->toText(Request::post('tag')) : $p->toText($json[$id]['tag']),
          'width' => (Request::post('width')) ? $p->toText(Request::post('width')) : $p->toText($json[$id]['width']),
          'height' => (Request::post('height')) ? $p->toText(Request::post('height')) : $p->toText($json[$id]['height'])
        ];
        // check  input file
        if(!empty($_FILES['file_upload']['name'])){
          // check file types
          if(!in_array(File::ext($_FILES['file_upload']['name']), $AllowedExtensions)) {
            die('Extension not allowed');
          }

            // move to upload dire
            $name = $_FILES['file_upload'];
            $width = Request::post('width');
            $height = Request::post('height');
            $path = PUBLICFOLDER.'/media/album_thumbs/album_'.$id.'.'.File::ext($_FILES['file_upload']['name']);
            // if save image
            if($p->resize($name,$path,$width,$height)){
              // save content
              File::setContent($jsonFile,json_encode($json));
              // set notification
              $p->setMsg($p::$lang['Success_edit']);
              // redirect
              Request::redirect($p->Url().'/media');
            }


        }else{
          // resize old thumb if change values
          $name = $json[$id]['thumb'];
          $width = $json[$id]['width'];
          $height = $json[$id]['height'];
          $path = ROOTBASE.$json[$id]['thumb'];
          // set resize funtion to false = is not upload
          if($p->resize($path,$path, $width,$height,false)){
            // save content
            File::setContent($jsonFile,json_encode($json));
            // set notification
            $p->setMsg($p::$lang['Success_edit']);
            // redirect
            Request::redirect($p->Url().'/media');
          }
        }
      }else{
        die('crsf Detect !');
      }
    }

    // template
    $template = ' <div class="row">
                    <div class="box-2 col">
                      '.$error.'
                      <form class="formFile" method="post"  enctype="multipart/form-data">
                          <input type="hidden" name="token" value="'.Token::generate().'"/>
                          <input type="file" name="file_upload" id="image-input"  value="'.$json[$id]['thumb'].'" accept="image/x-png, image/gif, image/jpeg"  />
                          <input type="number" name="width" value="'.$json[$id]['width'].'" required>
                          <input type="number" name="height" value="'.$json[$id]['height'].'" required>
                          <input type="text" name="title" value="'.$json[$id]['title'].'" required>
                          <textarea name="desc" rows="3" required>'.$json[$id]['desc'].'</textarea>
                          <input type="text"  required  name="tag" value="'.$json[$id]['tag'].'" required>
                          <a href="'.$p->Url().'/media"  class="btn red">Cancel</a>
                          <input type="submit" name="upload" id="upload" class="btn blue" value="Upload">
                      </form>
                    </div>
                    <div  class="box-2 col">
                      <div class="preview">
                        <div class="image-preview">
                          <img  id="image-display"  width="100%" src="'.Panel::$site['url'].$json[$id]['thumb'].'?timestamp=1357571065"/>
                        </div>
                      </div>
                    </div>
                  </div>';



    // show Media
    $p->view('actions',[
      'title' => Panel::$lang['Create_media'],
      'content' => '',
      'html' => $template
    ]);

  }else{
    Request::redirect($p::$site['url'].'/'.$p::$site['backend_folder']);
  }
});












/*    EDIT THEMES
-----------------------------*/


/*
* @name   Edit themes
* @desc   Edit themes ( :any use base64_encode remenber decode file)
*/
$p->route('/action/themes/edit/(:any)/(:any)', function($token,$file) use($p){
  if(Session::exists('user')){
    if (Token::check($token)) {

      // directory
      $path = base64_decode($file);

      // search templates/css/js in url
      $url = '';
      if(preg_match('/tpl/i',$path)){
        $url = 'templates';
      }else if(preg_match('/css/i',$path)){
        $url = 'stylesheets';
      }else if(preg_match('/js/i',$path)){
        $url = 'javascript';
      }

      // update file
      if(Request::post('saveFile')){
        if(Request::post('token')){
          $content = Request::post('updateFile');
          // save content
          File::setContent($path,$content);
          // set notification
          $p->setMsg($p::$lang['Success_edit']);
          // redirect
          Request::redirect($p->Url().'/'.$url);
        }else{
          die('crsf Detect!');
        }
      }

      $p->view('actions',[
        'url' => $url,
        'title' => Panel::$lang['Edit_File'],
        'html' => '<form method="post">
                      <section class="subheader">
                        <div class="row">
                            <div class="box-1 col">
                                <input type="hidden" name="token" value="'.Token::generate().'">
                                <label class="editor-label"><b>Name: </b>'.File::name($path).'.'.File::ext($path).'</label>
                            </div>
                          </div>
                      </section>
                      <div class="row">
                          <div class="box-1 col">
                              <textarea class="editor black" name="updateFile">'.File::getContent($path).'</textarea>
                              <input class="btn blue" type="submit" name="saveFile" value="'.Panel::$lang['Update'].'">
                              <a class="btn red" href="'.$p->url().'/'.$url.'">Cancel</a>
                          </div>
                      </div>
                    </form>'
      ]);
    }else{
      die('crsf Detect');
    }
  }else{
    Request::redirect($p::$site['url'].'/'.$p::$site['backend_folder']);
  }
});





/*    EDIT SITE.YML
-----------------------------*/


/*
* @name   Edit site
* @desc   Edit site ( :any use base64_encode remenber decode file)
*/
$p->route('/config', function() use($p){
  if(Session::exists('user')){

      // update file
      if(Request::post('saveFile')){
        if(Request::post('token')){
          $content = Request::post('updateFile');
          // save content
          File::setContent(SITE,$content);
          // set notification
          $p->setMsg($p::$lang['Success_edit']);
          // redirect
          Request::redirect($p->Url());
        }else{
          die('crsf Detect!');
        }
      }

      $p->view('actions',[
        'url' => 'Config',
        'title' => Panel::$lang['Config'],
        'html' => '<form method="post">
                      <section class="subheader">
                        <div class="row">
                            <div class="box-1 col">
                                <input type="hidden" name="token" value="'.Token::generate().'">
                                <label class="editor-label"><b>Name: </b> site.yml</label>
                            </div>
                          </div>
                      </section>
                      <div class="row">
                          <div class="box-1 col">
                              <textarea class="editor black" name="updateFile">'.File::getContent(SITE).'</textarea>
                              <input class="btn blue" type="submit" name="saveFile" value="'.Panel::$lang['Update'].'">
                              <a class="btn red" href="'.$p->url().'">Cancel</a>
                          </div>
                      </div>
                    </form>'
      ]);
  }else{
    Request::redirect($p::$site['url'].'/'.$p::$site['backend_folder']);
  }
});
