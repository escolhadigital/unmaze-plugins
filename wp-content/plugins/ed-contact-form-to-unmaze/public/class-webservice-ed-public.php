<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       escolhadigital.com
 * @since      1.0.0
 *
 * @package    Webservice_Ed
 * @subpackage Webservice_Ed/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Webservice_Ed
 * @subpackage Webservice_Ed/public
 * @author     Escolha Digital <geral@escolhadigital.com>
 */
class Webservice_Ed_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Webservice_Ed_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Webservice_Ed_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/webservice-ed-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Webservice_Ed_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Webservice_Ed_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/webservice-ed-public.js', array( 'jquery' ), $this->version, false );

	}

	// Unbounce form submission webhook, and then sending an email notification.      
	public function stripslashes_deep($value) {

		$value = is_array($value) ?
			array_map('stripslashes_deep', $value) :
			stripslashes($value);

		return $value;

	}

	/**
	 * Check external vars in URL - add to session
	 *
	 * @since    1.0.0
	 */
	 public function enqueue_params_url() {

		// if( current_user_can('administrator') ) {

			// global $wp_session;
			// $wp_session = WP_Session::get_instance();

			// $wp_session['user_name'] = 'User Name'; // A string
			// $wp_session['user_contact'] = array( 'email' => 'user@name.com' );// An array
			// $wp_session['user_obj'] = new WP_User( 1 ); // An object

			// https://medicapilar.pt/?utm_source=escolhadigital&utm_medium=cpc&utm_campaign=test&utm_term=test_term&utm_content=test_content

			// $wp_session['utm'] = array();	

            if (isset($_GET['utm_source'])) {

                if (!isset($_SESSION)) {
                    session_start();
                }
            
                $_SESSION['utm']['utm_source'] = $_GET['utm_source'];  

                if (isset($_GET['utm_medium'])) {
                    $_SESSION['utm']['utm_medium'] = $_GET['utm_medium'];
                }

                if (isset($_GET['utm_campaign'])) {
                    $_SESSION['utm']['utm_campaign'] = $_GET['utm_campaign'];
                }

                if (isset($_GET['utm_source'])) {
                    $_SESSION['utm']['utm_source'] = $_GET['utm_source'];
                }

                if (isset($_GET['utm_term'])) {
                    $_SESSION['utm']['utm_term'] = $_GET['utm_term'];
                }

                if (isset($_GET['utm_content'])) {
                    $_SESSION['utm']['utm_content'] = $_GET['utm_content'];
				}
				
            }

            /*if (current_user_can('administrator')) {
                print_R($_SESSION['utm']);
			}*/
			
		// } 

		// END WEBHOOK
		if (isset($_GET['webhook'])) {

			/*if (get_magic_quotes_gpc()) {
				$unescaped_post_data = $this->stripslashes_deep($_POST);
			} else {
				$unescaped_post_data = $_POST;
			}*/
			$unescaped_post_data = $this->stripslashes_deep($_POST);
			$json = json_decode($unescaped_post_data['data_json']);
			// error_log( 'data_json: ' . print_r($json, true) );

			// CHECK DATA SEND BY UNBOUNCE
			if (isset($json)) {

				$unbounce_data = array();

				// NAME
				if ( isset($json->nome)) {
					$unbounce_data['name'] = $json->nome[0];
				}

				if ( isset($json->name) ) {
					$unbounce_data['name'] = $json->name[0];
				}

				if ( isset($json->email) ) {
					$unbounce_data['email'] = $json->email[0];
				}

				// PHONE
				if ( isset($json->telemovel) ) {
					$unbounce_data['phoneNumber'] = $json->telemovel[0];
				}

				if ( isset($json->phoneNumber) ) {
					$unbounce_data['phoneNumber'] = $json->phoneNumber[0];
				}

				// COUNTRY
				if ( isset($json->cidade_2) ) {
					$unbounce_data['cidade_2'] = $json->cidade_2[0];
				}

				if ( isset($json->country) ) {
					$unbounce_data['cidade_2'] = $json->country[0];
				}

				// Origem Campanha
				if ( isset($json->origem_campanha) ) {
					$unbounce_data['origem_campanha'] = $json->origem_campanha[0];
				}

				if ( isset($json->lp_name) ) {
					$unbounce_data['lp_name'] = $json->lp_name[0];
				}	

				if ( isset($json->utm_source) ) {
					$unbounce_data['utm_source'] = $json->utm_source[0];
				}

				if ( isset($json->utm_medium) ) {
					$unbounce_data['utm_medium'] = $json->utm_medium[0];
				}

				if ( isset($json->utm_campaign) ) {
					$unbounce_data['utm_campaign'] = $json->utm_campaign[0];
				}

				if ( isset($json->utm_source) ) {
					$unbounce_data['utm_source'] = $json->utm_source[0];
				}

				if ( isset($json->utm_term) ) {
					$unbounce_data['utm_term'] = $json->utm_term[0];
				}

				if ( isset($json->utm_content) ) {
					$unbounce_data['utm_content'] = $json->utm_content[0];
				}

				// SEND TO UNMAZE
				// CALL CLASS
				$form = new Webservice_Ed_Functions();
				$response = $form->sendToUnmaze($unbounce_data);

				/*$content = 'Unbounce Webhook Response: ';
				$content .= print_r($response, true);
				$to = 'joelrocha@escolhadigital.com';
				$subject = 'The subject Unbounce Webhook -> Unmaze';
				$headers = array('Content-Type: text/html; charset=UTF-8');
				wp_mail($to, $subject, $content, $headers);*/

				echo "true";
				return true;

			}
			// END CHECK DATA SEND BY UNBOUNCE

			return false;

		}
		// END WEBHOOK


	}

}