<?php defined('PANEL_ACCESS') or die('No direct script access.');

 /**
  * Panel
  *
  * @package Panel
  * @author Moncho Varela / Nakome <nakome@gmail.com>
  * @link http://monchovarela.es
  *
  * For the full copyright and license information, please view the LICENSE
  * file that was distributed with this source code.
  */

class Panel {


  private $routes = array();

  /**
   * The Name of Panel
   *
   * @var string
   */
  public $appName = 'Morfy Panel';


  /**
   * The version of Panel
   *
   * @var string
   */
  public $version = '2.0.0';


  /**
   * Site Config array.
   *
   * @var array
   */
  public static $site;


  /**
   * Site language array.
   *
   * @var array
   */
  public static $lang;





  /**
   * Load Config
   */
  protected function loadConfig(){
      if (file_exists($site_config_path  = SITE)) {
          static::$site  = Spyc::YAMLLoad(file_get_contents($site_config_path));
      } else {
          die("Oops.. Where is config files ?!");
      }
  }

  /**
   * Load Language
   */
  protected function loadLanguage(){
      if (file_exists($language  = ROOT.DS.'lang'.DS.static::$site['backend_language'].'.yml')) {
          static::$lang = Spyc::YAMLLoad(file_get_contents($language));
      } else {
          die("Oops.. Where is language file ?!");
      }
  }


  /*
  *   Get  pretty url like hello-world
  */
  public function SeoLink($str){
      //Lower case everything
      $str = strtolower($str);
      //Make alphanumeric (removes all other characters)
      $str = preg_replace("/[^a-z0-9_\s-]/", "", $str);
      //Clean up multiple dashes or whitespaces
      $str = preg_replace("/[\s-]+/", " ", $str);
      //Convert whitespaces and underscore to dash
      $str = preg_replace("/[\s_]/", "-", $str);
      return $str;
  }

  /**
   * Debug
   * @return  string 
   */
  public function Debug($var){
    return print_r("<pre>$var</pre>");
  }


  /**
  * Get url
  *
  *  <code>
  *       $p->url()
  *  </code>
  *
  * @return var
  */
  public  function url(){
      // Create the Home URL
      return static::$site['url'].'/'.static::$site['backend_folder'];
  }

  /**
  * Get Views
  *
  *  <code>
  *       $p->partial($path, $vars = ['title' => 'i am home'])
  *  </code>
  *
  * @param  string $path  folder of views
  * @param  array  $vars array of options
  * @return require
  */
  public function partial($path, $vars = []) {
      if($vars) extract($vars);
      include_once PARTIALS.DS. trim($path, '/') . '.html';
  }

  /**
  * Get Views
  *
  *  <code>
  *       $p->view($path, $vars = ['title' => 'i am home'])
  *  </code>
  *
  * @param  string $path  folder of views
  * @param  array  $vars array of options
  * @return require
  */
  public  function view($path, $vars = []) {
      if($vars) extract($vars);
      require VIEWS.DS. trim($path, '/') . '.html';
  }


  /**
  * Get Assets file
  *
  *  <code>
  *       $p->Assets($file,$type)
  *  </code>
  *
  * @param  string $file  style.css
  * @param  string  $type folder
  * @return echo
  */
  public  function Assets($file,$type){
      echo $this->url().'/assets/'.$type.'/'.trim($file, '/');
  }


  /**
  * Get routes
  *
  *  <code>
  *       $m = new Panel();
  *       $m->route($patterns, $callback)
  *  </code>
  *
  * @param  patterns $patterns  links
  * @param  callback  $callback function
  * @access  public
  */
  public function route($patterns, $callback) {
      if( ! is_array($patterns))   $patterns = array($patterns);
      foreach($patterns as $pattern) {
          $pattern = trim($pattern, '/');
          $pattern = str_replace(
              array('\(','\)','\|','\:any','\:num','\:all','#'),
              array('(',')','|','[^/]+','\d+','.*?','\#'),
          preg_quote($pattern, '/'));
          $this->routes['#^' . $pattern . '$#'] = $callback;
      }
  }



  /**
  * Execute routes routes
  *
  *  <code>
  *       $m->lauch()
  *  </code>
  * @access  public
  *
  */
  public function lauch() {

      // Use the Force...
      include LIBRARIES.'/Force/ClassLoader/ClassLoader.php';

      // Map Classes
      ClassLoader::mapClasses(array(
          // Yaml Parser/Dumper
          'Spyc'     => LIBRARIES.'/Spyc/Spyc.php',
          // Force Components
          'Arr'      => LIBRARIES.'/Force/Arr/Arr.php',
          'Session'  => LIBRARIES.'/Force/Session/Session.php',
          'Token'    => LIBRARIES.'/Force/Token/Token.php',
          'Request'  => LIBRARIES.'/Force/Http/Request.php',
          'Response' => LIBRARIES.'/Force/Http/Response.php',
          'Url'      => LIBRARIES.'/Force/Url/Url.php',
          'File'     => LIBRARIES.'/Force/FileSystem/File.php',
          'Dir'      => LIBRARIES.'/Force/FileSystem/Dir.php'
      ));

      // Register the ClassLoader to the SPL autoload stack.
      ClassLoader::register();

      // Load config file
      $this->loadConfig();

      // Load language file
      $this->loadLanguage();

      // Sanitize URL to prevent XSS - Cross-site scripting
      Url::runSanitizeURL();

      // Send default header and set internal encoding
      header('Content-Type: text/html; charset='.static::$site['charset']);
      function_exists('mb_language') and mb_language('uni');
      function_exists('mb_regex_encoding') and mb_regex_encoding(static::$site['charset']);
      function_exists('mb_internal_encoding') and mb_internal_encoding(static::$site['charset']);

      // Gets the current configuration setting of magic_quotes_gpc and kill magic quotes
      if (get_magic_quotes_gpc()) {
          function stripslashesGPC(&$value){
              $value = stripslashes($value);
          }
          array_walk_recursive($_GET, 'stripslashesGPC');
          array_walk_recursive($_POST, 'stripslashesGPC');
          array_walk_recursive($_COOKIE, 'stripslashesGPC');
          array_walk_recursive($_REQUEST, 'stripslashesGPC');
      }

      // Start the session
      Session::start();

      $url = $_SERVER['REQUEST_URI'];
      $base = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
      if(strpos($url, $base) === 0) {
          $url = substr($url, strlen($base));
      }
      $url = trim($url, '/');
      foreach($this->routes as $pattern => $callback) {
          if(preg_match($pattern, $url, $params)) {
              array_shift($params);
              return call_user_func_array($callback, array_values($params));
          }
      }
  }
}