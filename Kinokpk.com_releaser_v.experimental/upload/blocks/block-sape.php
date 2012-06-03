<?php
     if (!defined('_SAPE_USER')){
        define('_SAPE_USER', '76cd827ff295786c49aee6406a5150af'); 
     }
     require_once(ROOT_PATH._SAPE_USER.'/sape.php'); 
     $sape = new SAPE_client();
     $content= ' '.$sape->return_links();
?>