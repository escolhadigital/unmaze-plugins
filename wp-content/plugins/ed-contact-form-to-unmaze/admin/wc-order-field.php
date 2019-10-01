<?php
//ADD CUSTOM USER FIELDS TO ADMIN ORDER SCREEN
// add_filter('woocommerce_admin_billing_fields', 'order_admin_custom_fields');
function order_admin_custom_fields($fields)
{
	global $theorder;
	$fields['nif'] = array(
		'label' => __('NIF', '_billing_nif'),
		'value' => get_post_meta($theorder->id, '_billing_nif', true),
		'show'  => true,
		//'class'   => '',
		'wrapper_class' => 'form-field-wide',
		'style' => '',
		'required' => true,
		//'id' => '',
		//'type' => '',
		//'name' => '',
		//'placeholder' => '',
		//'description' => '',
		//'desc_tip' => bool,
		//'custom_attributes' => '',
	);


	return $fields;
}

// LOAD CUSTOMER USER FIELDS VIA AJAX ON ADMIN ORDER SCREEN FROM CUSTOMER RECORD
// add_filter('woocommerce_found_customer_details', 'add_custom_fields_to_admin_order', 10, 1);
function add_custom_fields_to_admin_order($customer_data)
{
	$user_id = $_POST['user_id'];
	$customer_data['billing_nif'] = get_user_meta($user_id, 'billing_nif', true);
	return $customer_data;
}

//SAVE META DATA / CUSTOM FIELDS WHEN EDITING ORDER ON ADMIN SCREEN
// add_action('woocommerce_process_shop_order_meta', 'woocommerce_process_shop_order', 10, 2);
function woocommerce_process_shop_order($post_id, $post)
{

	if (empty($_POST['woocommerce_meta_nonce'])) {
		return;
	}

	if (!wp_verify_nonce($_POST['woocommerce_meta_nonce'], 'woocommerce_save_data')) {
		return;
	}

	if (!isset($_POST['cxccoo-save-billing-address-input'])) {
		return;
	}

	if (isset($_POST['_billing_nif'])) {
		update_user_meta($_POST['user_ID'], 'billing_nif', sanitize_text_field($_POST['_billing_nif']));
	}
}
