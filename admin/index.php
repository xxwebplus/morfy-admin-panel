<?php
    // required version
    if (version_compare(PHP_VERSION, "5.3.0", "<")) exit("Panel requires PHP 5.3.0 or greater.");
    // panel folder
    $backend = 'admin';
    // Separator
    define('DS', DIRECTORY_SEPARATOR);
    // Root directory
    define('ROOT', rtrim(dirname(__FILE__), '\\/'));
    // define access
    define('PANEL_ACCESS', true);
    // api
    define('API', ROOT.DS.'api');
    // templates
    define('TEMPLATES', ROOT.DS.'templates');
    // partials
    define('PARTIALS', ROOT.DS.'partials');
    // views
    define('VIEWS', ROOT.DS.'views');


    // Morfy paths
    define('ROOTBASE', rtrim(str_replace(array($backend), array(''), dirname(__FILE__)), '\\/'));
    define('LIBRARIES', ROOTBASE.DS.'libraries');
    define('STORAGE', ROOTBASE.DS.'storage');
    define('PAGES', ROOTBASE.DS.'storage'.DS.'pages');
    define('BLOCKS', ROOTBASE.DS.'storage'.DS.'blocks');
    define('SITE', ROOTBASE.DS.'config'.DS.'site.yml');
    define('THEMES', ROOTBASE.DS.'themes');
    define('PLUGINS', ROOTBASE.DS.'plugins');
    // new folders
    define('BACKUPS', ROOTBASE.DS.'backups');
    define('PUBLICFOLDER', ROOTBASE.DS.'public');
    define('MEDIA', PUBLICFOLDER.DS.'media');
    define('UPLOADS', PUBLICFOLDER.DS.'uploads');


    // Morfy panel class/routes
    include_once(API.DS.'Morfy.panel.php');
    include_once(API.DS.'Morfy.routes.php');


    // check if exist this folders
    if(!Dir::exists(MEDIA)) Dir::create(MEDIA);
    if(!Dir::exists(UPLOADS)) Dir::create(UPLOADS);
    if(!Dir::exists(BACKUPS)) Dir::create(BACKUPS);
    
?>

