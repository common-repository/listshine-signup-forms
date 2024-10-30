<?php

include dirname(__FILE__). "/lib/php-listshine-api/ListShine_Api.php";

function insert_listshine_signup_forms( $atts ){
    if(get_option('api_key')){
        $connection = new ListShine_Api(get_option("api_key"));
        $contactlists = $connection->getContactlistsWithForms();
        $forms = array();
        foreach($contactlists as $contactlist){
            if($contactlist->uuid == $atts['id']){
                return $contactlist->signup_form_content;
            }
        }
        return "";
    }

}
function get_listshine_contactlists_with_forms(){
    $connection = new ListShine_Api(get_option("api_key"));
    $contactlists = $connection->getContactlistsWithForms();
    return $contactlists;
}
?>