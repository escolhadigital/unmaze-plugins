<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       escolhadigital.com
 * @since      1.0.0
 *
 * @package    Webservice_Ed
 * @subpackage Webservice_Ed/includes
 */

class Webservice_Ed_Connect {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Webservice_Ed_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
  	protected $url;
    protected $user;
    protected $pass;
	/**
	 * Define the core functionality of the class.
	 *
	 */
	public function __construct() {
   
    $webservice = new Webservice_Ed_Admin();
    $options = $webservice->getOptionsWebservice();  
  
    $this->url = $options['server'];
    $this->user = $options['user'];
    $this->pass = $options['pass'];

	}
  

  public function getConnectService(){
    
    $client = new SoapClient($this->url);
    
    return $client;
  }
  
  

}
