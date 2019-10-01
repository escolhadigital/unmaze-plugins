<?php

// add_action('wpcf7_mail_sent', 'wpcf7_unmaze_after_submit');
add_action('wpcf7_before_send_mail', 'wpcf7_unmaze_after_submit');
function wpcf7_unmaze_after_submit($cf7)
{

    try {

        // CALL CLASS
        $form = new Webservice_Ed_Functions();
        $fields = $form->getFields();

        $data = array();
        if (is_array($fields)) {

            // PREPARE FIELDS
            foreach ($fields as $field) {
                if (!empty($_POST[$field])) {
                    $data[$field] = $_POST[$field];
                }
            }

            // PREPARE UTM FIELDS
            $utm_fields = $form->getUtmFields();
            if ( is_array($utm_fields) ) {
                foreach ($utm_fields as $id => $utm_field) {
                    if ( !empty($utm_field) ) {
                        $data[$id] = $utm_field;
                    }
                }
            }

            $response = $form->sendToUnmaze($data);

            // echo json_encode($response);
            // return json_encode($response);

        } else {
            $response = 'Error array';
            $to = 'joelrocha@escolhadigital.com';
            $subject = 'The subject wpcf7_mail_sent';
            $headers = array('Content-Type: text/html; charset=UTF-8');
            wp_mail($to, $subject, $response, $headers);
        }
        
    } catch (Exception $e) {
        $body = 'Caught exception: ' .  $e->getMessage();
        $to = 'joelrocha@escolhadigital.com';
        $subject = 'Error wpcf7_mail_sent';
        $headers = array('Content-Type: text/html; charset=UTF-8');
        wp_mail($to, $subject, $body, $headers);
    }

    // return true;

}