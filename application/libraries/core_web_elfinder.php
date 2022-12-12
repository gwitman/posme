<?php if ( ! defined('BASEPATH')) exit('No se permite el acceso directo al script');
include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'core_web_elfinder/elFinderConnector.class.php';
include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'core_web_elfinder/elFinder.class.php';
include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'core_web_elfinder/elFinderVolumeDriver.class.php';
include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'core_web_elfinder/elFinderVolumeLocalFileSystem.class.php';

  
class core_web_elfinder {	
  public function __construct($opts) 
  {
    $connector = new elFinderConnector(new elFinder($opts));
    $connector->run();
  }  
}
?>