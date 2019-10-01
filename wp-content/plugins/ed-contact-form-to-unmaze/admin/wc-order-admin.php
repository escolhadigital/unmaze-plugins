<?php

global $functions;
global $orderID;

function ed_add_order_send_column($columns)
{

  $new_columns = array();

  foreach ($columns as $column_name => $column_info) {

    $new_columns[$column_name] = $column_info;

    if ('order_total' === $column_name) {
      $new_columns['order_send'] = __('Estado da Fatura', 'my-textdomain');
    }
  }

  return $new_columns;
}

add_filter('manage_edit-shop_order_columns', 'ed_add_order_send_column', 20);


//Retorna número da factura.

function createInvoice($order_id, $customer_id)
{
  global $functions;
  $order = wc_get_order($order_id);
  $order_data = $order->get_data();
  //$customer_id = $order_data['customer_id'];
  $header_invoice = $functions->createInvoice($customer_id);
  if ($header_invoice != -2 && $header_invoice != -4 && $header_invoice != -3) {
    $refer = '';
    $code = '';
    $description = '';
    $price = 0;
    $qty = 0;
    $discount = 0;
    $iva = 23;
    $warehouse = 0;
    $unity = 'UNI';
    $ivaincluido = 1;

    foreach ($order->get_items() as $item_key => $item_values) {
      $item_data = $item_values->get_data();
      $prod_name = $item_values->get_name();
      $qty = $item_values->get_quantity();
      $prod_id = $item_values->get_product_id();
      $price = $item_values->get_total();

      $invoice_line = $functions->createInvoiceLine($header_invoice, $refer, $code, $description, $price, $qty, $discount, $iva, $warehouse, $unity, $ivaincluido);
    }
    if ($invoice_line >= 1) {
      $response = $functions->closeInvoice($header_invoice);
      if ($response != -4 && $response != -2)
        return $response;
    }
  }


  return false;
}


//Retorna id do cliente criado ou editado. 

function editClient($order_id)
{
  global $functions;
  $order = wc_get_order($order_id);
  $order_data = $order->get_data();
  $nif = 0;
  $customer_id = '';
  foreach ($order_data['meta_data'] as $billing_nif) {
    $nif = $billing_nif->value;
  }

  $clients = $functions->getClients();
  foreach ($clients as $client) {
    foreach ($client as $key => $value) {
      $contribuin = str_replace(' ', '', $value['contribuin']);
      if ($nif == $contribuin) {
        $customer_id = $value['cliente'];
      }
    }
  }
  //$customer_id = $order_data['customer_id'];
  $name = $order_data['billing']['first_name'];
  $address = $order_data['billing']['address_1'];
  $local = $order_data['billing']['city'];
  $state = $order_data['billing']['state'];
  $postal_code = $order_data['billing']['postcode'];
  $country = $order_data['billing']['country'];
  $email = $order_data['billing']['email'];
  $phone = $order_data['billing']['phone'];
  $fax = 0;
  $mobile_phone = 0;
  $contact = 0;

  $response = $functions->editClient($customer_id, $name, $address, $local, $postal_code, $country, $phone, $fax, $mobile_phone, $email, $nif, $contact);
  if ($response != -1 && $response != -2)
    return $response;

  return false;
}


// Envia Factura e gera link no email para download.
add_action('woocommerce_email_before_order_table', 'add_order_email_pdf_invoice', 10, 10);

function add_order_email_pdf_invoice($order, $sent_to_admin)
{
  $order_id = $order->id;
  global $functions;
  $functions = new Webservice_Ed_Functions();
  if ($order->has_status('completed')) {
    $client = editClient($order_id);
    if ($client) {
      $invoice = createInvoice($order_id, $client);
      if ($invoice) {
        $invoice_pdf = $functions->InvoicePdf($invoice);
        if ($invoice_pdf) {

          foreach ($order->get_items() as $item_id => $item) {
            $value = 1;
            $key = 'send_state';
            wc_update_order_item_meta($item_id, $key, $value);
          }

          echo '<span>Link para download da Encomenda: </span><a href="' . $invoice_pdf . '">' . $invoice_pdf . '</a>';
          echo '<br>';
        }
      }
    }
  }
}


//Adiciona coluna com o estado da Encomenda.
// adding the data for each orders by column (example)
add_action('manage_shop_order_posts_custom_column', 'custom_orders_list_column_content', 10, 2);

function custom_orders_list_column_content($column, $order_id)
{
  switch ($column) {
    case 'order_send':

      global $wpdb;
      global $woocommerce;

      $result = $wpdb->get_results('SHOW COLUMNS FROM ' . $wpdb->prefix . 'woocommerce_order_items LIKE "send_state"');

      if (empty($result)) {
        $wpdb->query(' ALTER TABLE ' . $wpdb->prefix . 'woocommerce_order_items ADD send_state INT(1) NOT NULL DEFAULT 0');
      }

      $state_text = "<span class='order'>Não Enviado</span>";
      $order = wc_get_order($order_id);


      if (!empty($order->get_items())) {
        foreach ($order->get_items() as $item_id => $item) {
          $key = 'send_state';
          $atual_value = wc_get_order_item_meta($item_id, $key);
          if ($atual_value == 1) {
            $state_text = "<span class='order'>Enviado</span>";
          }
        }
      }

      echo $state_text;
  }
}
