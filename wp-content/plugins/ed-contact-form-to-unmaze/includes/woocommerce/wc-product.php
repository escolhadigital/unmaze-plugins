<?php

/**
 * ADD COLUMN TO LIST
 */

// ADDING A CUSTOM COLUMN TITLE TO ADMIN PRODUCTS LIST
add_filter('manage_edit-product_columns', 'custom_product_column', 11);
function custom_product_column($columns)
{
	//add columns
	$columns['ws_stock'] = __('Update Stock', 'woocommerce'); // title
	return $columns;
}

// ADDING THE DATA FOR EACH PRODUCTS BY COLUMN (EXAMPLE)
add_action('manage_product_posts_custom_column', 'custom_product_list_column_content', 10, 2);
function custom_product_list_column_content($column, $product_id)
{

	$current_url = "http://" . $_SERVER['HTTP_HOST'] . "" . $_SERVER['REQUEST_URI'];

	if ($column == 'ws_stock') {

		// CHECK CHANGES
		if (!empty($_GET['update_id']) && $_GET['update_id'] == $product_id) {

			$plugin = new Webservice_Ed_Functions();

			if ($_GET['update_action'] == 'product') {
				$plugin->importProductDataById($product_id);
			}
		}

		echo "<a href='{$current_url}&update_action=product&update_id={$product_id}' class='button'>Update</a>";

		// SHOW HISTORY
		if ($updated_meta = get_post_meta($product_id, 'ws_product_updated', true)) {
			echo "<p style='float:left; width:100%;'>Updated at: {$updated_meta}</p>";
		}

		// SHOW ERRORS
		if ($error_meta = get_post_meta($product_id, 'ws_product_error', true)) {
			echo "<p style='float:left; width:100%;'>{$error_meta}</p>";
		}
	}
}

/**
 * BULK ACTION
 */

add_filter('bulk_actions-edit-product', 'register_my_bulk_actions');

function register_my_bulk_actions($bulk_actions)
{

	$bulk_actions['update_webservice_product'] = __('Actualizar Stock Produtos', 'theme');

	return $bulk_actions;
}

add_filter('handle_bulk_actions-edit-product', 'my_bulk_action_handler', 10, 3);
function my_bulk_action_handler($redirect_to, $doaction, $post_ids)
{

	if ($doaction !== 'update_webservice_product') {
		return $redirect_to;
	}

	$plugin = new Webservice_Ed_Functions();
	foreach ($post_ids as $product_id) {
		$plugin->importProductDataById($product_id);
	}

	$redirect_to = add_query_arg('update_webservice_product', count($post_ids), $redirect_to);

	return $redirect_to;
}

/**
 * ADD FIELDS TO PRODUCT 
 */

add_action('woocommerce_product_options_advanced', 'wc_add_product_options');
function wc_add_product_options()
{

	echo '<div class="options_group">';

	$args = array(
		'id'      => 'ws_product_id',
		'value'   => get_post_meta(get_the_ID(), 'ws_product_id', true),
		'label'   => 'Webservice Product ID/REF',
		'desc_tip' => true,
		'description' => 'Insert the webservice product ID',
	);

	woocommerce_wp_text_input($args);

	echo '</div>';
}


add_action('woocommerce_process_product_meta', 'wc_save_fields', 10, 2);
function wc_save_fields($id, $post)
{

	//if( !empty( $_POST['super_product'] ) ) {
	update_post_meta($id, 'ws_product_id', $_POST['ws_product_id']);
	//} else {
	//	delete_post_meta( $id, 'super_product' );
	//}

}
