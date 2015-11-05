<?php defined('PANEL_ACCESS') or die('No direct script access.');



/*    PREVIEW PAGES
-----------------------------*/


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






/*    PREVIEW UPLOADS
-----------------------------*/


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
                <li class="code"><b>Markdown: </b><code>![text img]('.Panel::$site['url'].'/public/uploads/'.$link.')</code></li>
                <li class="code"><b>Html: </b><code>&lt;img src="'.Panel::$site['url'].'/public/uploads/'.$link.'" /&gt;</code></li>
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


