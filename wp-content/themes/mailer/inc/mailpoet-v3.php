<?php
/**
 * MailPoet v3 customization
 **/

/* subscribtion form code customisation */
add_shortcode('mailpoet_tst_form', 'mailpoet_tst_form_screen');
function mailpoet_tst_form_screen($atts) {

    extract(shortcode_atts(array(
        'id'  => 0
    ), $atts));

    if($id <= 0)
        return '';

    $out = "<div class='tst-mailpoet-form'>1";
    $out .= tst_mailpoet_subscribtion_form($id);
    $out .= "</div>2";

    return $out;
}

function tst_mailpoet_subscribtion_form($form_id){

    if(!class_exists('\MailPoet\Form\Widget'))
        return '';

    $form_widget = new \MailPoet\Form\Widget();
    $form_html = $form_widget->widget(array('form' => $form_id, 'form_type' => 'php'));
    $form_html = preg_replace('/<style(.*?)<\/style>/s', "", $form_html);

    preg_match_all('/<p class=\"mailpoet_paragraph\">(.*?)<\/p>/s',$form_html, $m);
    if(isset($m[1]) && !empty($m[1])){
        foreach($m[1] as $c_box){
            if(preg_match('/<label class=\"mailpoet_checkbox_label\"><input(.*?)<\/label>/s', $c_box, $ml)) {
                $c_box_new = str_replace("mailpoet_paragraph", "mailpoet_paragraph tst-agree-pd-paragraph", $c_box);
                $form_html = str_replace($c_box, $c_box_new, $form_html);
                $label = strip_tags($ml[0]);
                if(!empty($label)){
                    $c_box_new = str_replace($label, "<span class=\"mailpoet_tst_checkbox\">{$label}</span>", $c_box);
                    $form_html = str_replace($c_box, $c_box_new, $form_html);
                }
            }
        }
    }

    preg_match_all('/(<p class=\"mailpoet_paragraph\">.*?<\/p>)/s',$form_html, $m);
    if(isset($m[1]) && !empty($m[1])){
        foreach($m[1] as $c_box){
            if(preg_match('/<label class=\"mailpoet_checkbox_label\"><input(.*?)<\/label>/s', $c_box)) {
                $c_box_new = str_replace("mailpoet_paragraph", "mailpoet_paragraph tst-checkbox-paragraph", $c_box);
                $form_html = str_replace($c_box, $c_box_new, $form_html);
            }

            if(preg_match('/.*?pd-agreement.*?/', $c_box)) {
                $c_box_new = str_replace("mailpoet_paragraph", "mailpoet_paragraph tst-agree-pd-paragraph", $c_box);
                $form_html = str_replace($c_box, $c_box_new, $form_html);
            }
        }
    }

    preg_match_all('/(<label class=\"mailpoet_checkbox_label\">.*?pd-agreement.*?<\/label>)/s',$form_html, $m);
    if(isset($m[1]) && !empty($m[1])){
        foreach($m[1] as $c_box){

            $label = '';
            $label = strip_tags($c_box);
            if(!empty($label)){

                $c_box_new = $c_box;

                $c_box_new = str_replace("mailpoet-checkbox-label", "mailpoet-checkbox-label tst-agree-pd", $c_box_new);
                $c_box_new = str_replace($label, "<span>{$label}</span>", $c_box_new);
                $c_box_new = htmlspecialchars_decode($c_box_new);

                $form_html = str_replace($c_box, $c_box_new, $form_html);
            }
        }
    }

    return $form_html;
}
