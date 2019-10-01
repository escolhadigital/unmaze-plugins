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

class Webservice_Ed_Functions
{

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Webservice_Ed_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $apikey;
	protected $email;
	protected $url;
	protected $fields;

	protected $api;
	protected $headers;

	/**
	 * Define the core functionality of the class.
	 *
	 */
	public function __construct()
	{

		// $connect = new Webservice_Ed_Connect();

		$plugin_name = 'webservice-ed';
		$options = get_option($plugin_name);

		$this->apikey = $options['apikey'];
		$this->email = $options['email'];
		$this->url = $options['url'];
		$this->fields = $options['fields'];

		// $this->api = $connect->getConnectService();
		$this->prepareFields();
		$this->prepareHeaders();
	}

	/**
	 * UNMAZE FUNCTIONS
	 */

	function wsse_header()
	{

		$nonce = hash_hmac('sha512', uniqid(null, true), uniqid(), true);
		$created = new \DateTime('now', new \DateTimezone('UTC'));
		$created = $created->format(\DateTime::ISO8601);
		$digest = sha1($nonce . $created . $this->apikey, true);

		return sprintf(
			'X-WSSE: UsernameToken Username="%s", PasswordDigest="%s", Nonce="%s", Created="%s"',
			$this->email,
			base64_encode($digest),
			base64_encode($nonce),
			$created
		);

	}

	function prepareFields()
	{
		$this->fields = explode(',', $this->fields);
	}

	function prepareHeaders()
	{
		// $this->url = $this->url . '/api/rest/latest/leads.json?_format=json'; // "https://xxxxxx.unmaze.io/api/rest/latest/leads.json?_format=json";
		$this->headers = array('Content-Type: application/json', 'Accept: application/json');
		$this->headers[] = $this->wsse_header();
	}

	function getFields()
	{
		return $this->fields;
	}

	function getUtmFields()
	{

		// global $wp_session;
		// $wp_session = WP_Session::get_instance();

		/*if (!isset($_SESSION)) {
			session_start();
		}*/

		if( !empty($_SESSION['utm']) ) {
			return $_SESSION['utm'];
		}

        return false;	

	}

	public function sendToUnmaze($data)
	{

		$fields = json_encode($data);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($ch, CURLOPT_POST, true);

		$response = curl_exec($ch);
		curl_close($ch);
		
		return $response;
		
	}
}
