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
          'title' => (Request::post('title')) ? Url::sanitizeURL(Request::post('title')) : $json[$id]['title'],
          'desc' =>  (Request::post('desc')) ? Url::sanitizeURL(Request::post('desc')) : $json[$id]['desc'],
          'thumb' => ($_FILES['file_upload']['name']) ? '/public/media/album_thumbs/album_'.$id.'.'.File::ext($_FILES['file_upload']['name']) : $json[$id]['thumb'],
          'images' => '/public/media/albums/'.'album_'.$id,
          'tag' => (Request::post('tag')) ? Url::sanitizeURL(Request::post('tag')) : $json[$id]['tag'],
          'width' => (Request::post('width')) ? Url::sanitizeURL(Request::post('width')) : $json[$id]['width'],
          'height' => (Request::post('height')) ? Url::sanitizeURL(Request::post('height')) : $json[$id]['height']
        ];
        // check  input file
        if(!empty($_FILES['file_upload']['name'])){
          // check file types
          if(!in_array(File::ext($_FILES['file_upload']['name']), $AllowedExtensions)) { 
            die('Extension not allowed');
          }
          // remove previews file
          File::remove(ROOBASE.$json[$id]['thumb']);
          // move to upload dire
          $name = $_FILES['file_upload'];
          $width = Request::post('width');
          $height = Request::post('height');
          $path = PUBLICFOLDER.'/media/album_thumbs/album_'.$id.'.'.File::ext($_FILES['file_upload']['name']);
          if($p->resize($name,$path,$width,$height)){
            File::setContent($jsonFile,json_encode($json));
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
            File::setContent($jsonFile,json_encode($json));
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
                          <a href="'.$p->Url().'/media"  class="btn btn-danger">Cancel</a>
                          <input type="submit" name="upload" id="upload" class="btn" value="Upload">
                      </form>
                    </div>
                    <div  class="box-2 col">
                      <div class="preview">
                        <div class="image-preview">
                          <img  id="image-display"  width="100%" src="'.Panel::$site['url'].$json[$id]['thumb'].'"/>
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