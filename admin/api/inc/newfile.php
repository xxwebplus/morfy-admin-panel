<?php defined('PANEL_ACCESS') or die('No direct script access.');




/*    NEW FILE PAGES/BLOCKS
-----------------------------*/


/*
* @name   New File
* @desc   New file pages ( :any use base64_encode remenber decode file)
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






/*    NEW FILE UPLOADS
-----------------------------*/

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


/*  NEW FILE MEDIA
------------------------------------*/

/*
* @name   Create media Media
* @desc   if session user get Create Media
*/
$p->route('/media/create',function($offset = 1) use($p){
  if(Session::exists('user')){

    $error = '';
    $AllowedExtensions = ['gif','jpeg','jpg','png'];
    if(Request::post('upload')){
      if(Request::post('token')){

        $jsonFile = PUBLICFOLDER.'/media/mdb.json';
        $json = [];
        // if not exists create 
        if(!File::exists($jsonFile)){
          File::setContent($jsonFile,'[]');
          // create folders album and album_thumbs
          Dir::create(PUBLICFOLDER.'/media');
          Dir::create(PUBLICFOLDER.'/media/albums');
          Dir::create(PUBLICFOLDER.'/media/album_thumbs');
        }else{
          // decode json
          $json = json_decode(File::getContent($jsonFile),true);
          // id
          $id = time();
          // json array remenber encode 
          $json[$id] = [
            'id' => $id,
            'title' => (Request::post('title')) ? Url::sanitizeURL(Request::post('title')) : 'No title',
            'desc' =>  (Request::post('desc')) ? Url::sanitizeURL(Request::post('desc')) : 'No desc',
            'thumb' => '/public/media/album_thumbs/album_'.$id.'.'.File::ext($_FILES['file_upload']['name']),
            'images' => '/public/media/albums/'.'album_'.$id,
            'tag' => (Request::post('tag')) ? Url::sanitizeURL(Request::post('tag')) : 'No tag',
            'width' => (Request::post('width')) ? Url::sanitizeURL(Request::post('width')) : 'No width',
            'height' => (Request::post('height')) ? Url::sanitizeURL(Request::post('height')) : 'No height'
          ];
          // check if exists
          if(File::exists(PUBLICFOLDER.'/media/albums_thumbs/'.$_FILES['file_upload']['name'])){
            $error = '<span class="error">'.Panel::$lang['File_Name_Exists'].'</span>';
          }else{
            // check file types
            if(!in_array(File::ext($_FILES['file_upload']['name']), $AllowedExtensions)) { 
              die('Extension not allowed');
            }
            // move to upload dire
            $name = $_FILES['file_upload'];
            $width = Request::post('width');
            $height = Request::post('height');
            $path = PUBLICFOLDER.'/media/album_thumbs/album_'.$id.'.'.File::ext($_FILES['file_upload']['name']);
            if($p->resize($name,$path,$width,$height)){
                File::setContent($jsonFile,json_encode($json));
                Dir::create(PUBLICFOLDER.'/media/albums/album_'.$id);
                Request::redirect($p->Url().'/media');
            }
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
                          <input type="file" name="file_upload" id="image-input" accept="image/x-png, image/gif, image/jpeg"  />
                          <input type="number" name="width" placeholder="width" required>
                          <input type="number" name="height" placeholder="height" required>
                          <input type="text" name="title" placeholder="title" required>
                          <textarea name="desc" rows="3" placeholder="Description" required></textarea>
                          <input type="text"  required  name="tag" id="tag"  placeholder="Tag" required>
                          <a href="'.$p->Url().'/media"  class="btn btn-danger">Cancel</a>
                          <input type="submit" name="upload" id="upload" class="btn" value="Upload">
                      </form>
                    </div>
                    <div  class="box-2 col">
                      <div class="preview">
                        <div class="image-preview">
                          <img  id="image-display"  width="100%" src="'.$p->Assets('nomediapreview.jpg','img').'"/>
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
















/*
* @name   Media uploads
* @desc   if session user get Media
*/
$p->route(array('/media/uploads/(:num)','/media/uploads/(:num)/(:num)'),function($id,$offset = 1) use($p){
  if(Session::exists('user')){
    $info = '';
    // items per page
    $per_page = 16;
    // array json
    $json = [];
    // next prev 
    $prev = '';
    $next = '';
    // template
    $templateAll = '';
    // json file
    $file = ROOTBASE.'/public/media/albums/album_'.$id;
    // get json file
    $scan = File::scan($file);

    // get json file fow with and height
    $jsonFile = PUBLICFOLDER.'/media/mdb.json';
    $json = json_decode(File::getContent($jsonFile),true);

    // upload files
    $error = ''; 
    $AllowedExtensions = ['gif','jpeg','jpg','png','md','txt','zip','pdf','mp4','webm','html','css','js','mp3','vaw','doc'];
    if(Request::post('uploadMedia')){
        if(Request::post('token')){
                    // check if exists
          if(File::exists(PUBLICFOLDER.'/media/albums/album_'.$id.'/'.$_FILES['media_upload']['name'])){
            $error = '<span class="error">'.Panel::$lang['File_Name_Exists'].'</span>';
          }else{
            // check file types
            if(!in_array(File::ext($_FILES['media_upload']['name']), $AllowedExtensions)) { 
              die('Extension not allowed');
            }
            // move to upload dire
            $name = $_FILES['media_upload'];
            $width = Request::post('width');
            $height = Request::post('height');

            $path = PUBLICFOLDER.'/media/albums/album_'.$id.'/'.
            // seo name
            $p->SeoLink(File::name($_FILES['media_upload']['name'])).'.'.
            // extension
            File::ext($_FILES['media_upload']['name']);
            // if resize redirect
            if($p->resize($name,$path,$width,$height)){
                Request::redirect($p->Url().'/media/uploads/'.$id);
            }
          }
        }else{
          die('crsf Detect !');
        }
    }



    // if folder is not empty
    if(count($scan) > 0){
      rsort($scan);
      $showPag = array_chunk($scan, $per_page);
      if($offset > 1) {
          $prev = '<a class="btn" href="'.$p->Url().'/media/uploads/'.$id.'/'.($offset - 1).'"><i class="ti-arrow-left"></i></a>';
      } else {
          $prev = '<span class="btn disable"><i class="ti-arrow-left"></i></span>';
      }
      if($offset < ceil(count($scan) / $per_page)) {
          $next = '<a href="' . $p->Url().'/media/uploads/'.$id.'/'.($offset + 1).'" class="btn"><i class="ti-arrow-right"></i></a>';
      } else {
          $next = '<span class="btn disable"><i class="ti-arrow-right"></i></span>';
      }




      // template html
      $templateAll .= '<div class="box-1 col">

          '.$info.'

          <h3><b>'.Panel::$lang['Upload_file'].' id:</b> '.$id.'</h3>
          <a href="#" class="btn open-modal"><i class="ti-upload"></i>  '.Panel::$lang['Upload'].'</a>
          <a href="'.$p->Url().'/media"  class="btn btn-danger">Cancel</a>
          <div class="modal">
            <form  class="mediauploader" method="post"  enctype="multipart/form-data">
                <input type="hidden" name="token" value="'.Token::generate().'"/>
                <input type="file" name="media_upload" accept="image/x-png, image/gif, image/jpeg"  required/>
                <input type="number" name="width" value="'.$json[$id]['width'].'" required>
                <input type="number" name="height" value="'.$json[$id]['height'].'" required>
                <input type="submit" name="uploadMedia" id="upload" class="btn" value="Upload">
            </form>
          </div>
          <div class="thumbs">';


      // all media files
      foreach($showPag[$offset - 1] as $media) {
        $image = str_replace(PUBLICFOLDER, Panel::$site['url'].'/public', $media);
        $templateAll .= '
            <div class="thumb">
              <a onclick="return confirm(\''.Panel::$lang['Are_you_sure_to_delete'].' !\')" 
              href="'.$p->Url().'/action/media/uploads/removefile/'.$id.'/'.base64_encode($media).'">
                <i class="ti-trash"></i>
              </a>
              <img src="'.$image.'" alt="'.File::name($media).'"/>
            </div>'; 
      }

      // end template
      $templateAll .=  '</div></div>';


      // show Media
      $p->view('media',[
        'title' => Panel::$lang['Uploads_Media'],
        'offset' => $offset,
        'prev' => $prev,
        'next' => $next,
        'content' => $templateAll 
      ]);

    // if folder is empty
    }else{
      $templateAll .= '<div class="info">
            '.$error.'
            <h3><b>'.Panel::$lang['Upload_file'].' id:</b> '.$id.' '.Panel::$lang['Upload_empty'].'</h3>
            <form method="post"  enctype="multipart/form-data">
                <input type="hidden" name="token" value="'.Token::generate().'"/>
                <input type="file" name="media_upload" accept="image/x-png, image/gif, image/jpeg"  required/>
                <input type="number" name="width" value="'.$json[$id]['width'].'" required>
                <input type="number" name="height" value="'.$json[$id]['height'].'" required>
                <a href="'.$p->Url().'/media"  class="btn btn-danger">Cancel</a>
                <input type="submit" name="uploadMedia" id="upload" class="btn" value="Upload">
            </form>
        </div>';

         // show Media
        $p->view('actions',[
          'title' => Panel::$lang['Uploads_Media'],
          'content' => '',
          'html' => $templateAll 
        ]);
    }
  }else{
    Request::redirect($p::$site['url'].'/'.$p::$site['backend_folder']);
  }
});
