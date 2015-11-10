<?php defined('PANEL_ACCESS') or die('No direct script access.');


/*    SEARCH PAGES/BLOCKS
-----------------------------*/


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
                      '.$item.'
                      <a class="btn blue right" href="
                        '.$p->Url().'/action/edit/'.Token::generate().'/'.
                        base64_encode($directory.$item).'">
                        '.File::name($item).'
                        | <i class="ti-arrow-right"></i>
                      </a>
                    </li>';
      }
    }
    $result .= '</ul>';
    // render view
    $p->view('actions',array(
        'title' => Panel::$lang['Search'],
        'html' => '<section class="subheader">
                      <div class="row">
                        <div class="box-1 col">
                          <h3><span class="btn blue">'.$count.'</span> results for '.$query.'</h3>
                        </div>
                      </div>
                    </section>
                    <div class="row">
                      <div class="box-1 col">
                        <div class="preview">
                          '.$result.'
                          <a class="btn red" href="javascript:void(0);" onclick="return history.back(0)">
                            '.Panel::$lang['back'].'
                          </a>
                        </div>
                      </div>
                    </div>'
    ));

});



/*    SEARCH IN UPLOADS
-----------------------------*/

/*
* @name   Search
* @sample /action/searchfiles/findme
*/
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
        $result .=
                    '<li>
                      '.$item.'
                      <a class="btn blue right" href="
                        '.$p->Url().'/action/uploads/preview/'.base64_encode($directory.$item).'">
                        '.File::name($item).'.'.File::ext($item).'">
                        '.File::name($item).'
                        | <i class="ti-arrow-right"></i>
                      </a>
                    </li>';
      }
    }
    $result .= '</ul>';
    // render view
    $p->view('actions',array(
        'title' => Panel::$lang['Search'],
        'html' => '<section class="subheader">
                      <div class="row">
                        <div class="box-1 col">
                          <h3><span class="btn blue">'.$count.'</span> results for '.$query.'</h3>
                        </div>
                      </div>
                    </section>
                    <div class="row">
                      <div class="box-1 col">
                        <div class="preview">
                          '.$result.'
                          <a class="btn red" href="javascript:void(0);" onclick="return history.back(0)">
                            '.Panel::$lang['back'].'
                          </a>
                        </div>
                      </div>
                    </div>'
    ));

});



/*    SEARCH MEDIA
-----------------------------*/

/*
* @name   Search
* @sample /action/searchmedia/findme
*/

$p->route('/action/searchmedia/(:any)', function($query = '') use($p) {
    // get file url
    $jsonFile = ROOTBASE.'/public/media/mdb.json';
    $json = json_decode(File::getContent($jsonFile),true);
    // start template
    $result = '<ul>';
    // init count to 0
    $count = 0;
    foreach ($json as $item) {
      if(preg_match('/'.urldecode($query).'/i', $item['title'])){
          // count +1
          $count++;
          // template
          $result .= '<li>
                      '.$p->TextCut($p->toHtml($item['desc']),20).'
                      <a class="btn blue right" href="
                        '.$p->Url().'/action/media/edit/'.$item['id'].' "
                          title="'.$item['title'].'">
                        '.$item['title'].'
                        | <i class="ti-arrow-right"></i>
                      </a>
                    </li>';


      }
    }
    $result .= '</ul>';
    // render view
    $p->view('actions',array(
        'title' => Panel::$lang['Search'],
        'html' => '<section class="subheader">
                      <div class="row">
                        <div class="box-1 col">
                          <h3><span class="btn blue">'.$count.'</span> results for '.$query.'</h3>
                        </div>
                      </div>
                    </section>
                    <div class="row">
                      <div class="box-1 col">
                        <div class="preview">
                          '.$result.'
                          <a class="btn red" href="javascript:void(0);" onclick="return history.back(0)">
                            '.Panel::$lang['back'].'
                          </a>
                        </div>
                      </div>
                    </div>'
    ));

});





/*    SEARCH IN THEMES
-----------------------------*/

/*
* @name   Search
* @sample /action/searchinthemes/findme
*/
$p->route('/action/searchinthemes/(:any)', function($query = '') use($p) {
    // get file url
    $directory = THEMES;
    // scan to obtain files
    $scan = File::scan($directory);
    // start template
    $result = '<ul>';
    // init count to 0
    $count = 0;
    foreach ($scan as $item) {
      // remove storage\$dir
      $item = str_replace(THEMES, '', $item);
      // search query with preg_match
      if(preg_match('/'.urldecode($query).'/i', $item)){
        // count +1
        $count++;
        // template
        $result .=
                    '<li>
                      '.$item.'
                      <a class="btn blue right" href="
                      '.$p->Url().'/action/themes/edit/'.Token::generate().'/'.base64_encode($directory.$item).'">
                        '.File::name($item).'.'.File::ext($item).'
                        '.File::name($item).'
                        | <i class="ti-arrow-right"></i>
                      </a>
                    </li>';
      }
    }
    $result .= '</ul>';
    // render view
    $p->view('actions',array(
        'title' => Panel::$lang['Search'],
        'html' => '<section class="subheader">
                      <div class="row">
                        <div class="box-1 col">
                          <h3><span class="btn blue">'.$count.'</span> results for '.$query.'</h3>
                        </div>
                      </div>
                    </section>
                    <div class="row">
                      <div class="box-1 col">
                        <div class="preview">
                          '.$result.'
                          <a class="btn red" href="javascript:void(0);" onclick="return history.back(0)">
                            '.Panel::$lang['back'].'
                          </a>
                        </div>
                      </div>
                    </div>'
    ));

});
