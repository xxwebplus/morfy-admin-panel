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
                        <a href="'.$p->Url().'/action/media/edit/'.Token::generate().'/'.base64_encode($item['id']).' " 
                          title="'.$item['title'].' ">
                          '.$item['title'].'
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





