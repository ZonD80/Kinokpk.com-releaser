<?php
//тест
     if (!defined('_SAPE_USER')){
        define('_SAPE_USER', '822b8ee7d6bee99501b5089e879dfdbd'); 
     }
     require_once(ROOT_PATH._SAPE_USER.'/sape.php'); 
     $sape = new SAPE_client();
     $content= ' '.$sape->return_links();
?>