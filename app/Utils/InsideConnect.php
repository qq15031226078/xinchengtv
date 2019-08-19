<?php
namespace App\Utils;


class InsideConnect {
    function request_post($url = '',$post_data = array()) {
            if (empty($url) || empty($post_data)) {
                return false;
            }
            
            $o = "";
            foreach ( $post_data as $k => $v ){ 
                $o.= "$k=" . urlencode( $v ). "&" ;
            }
            $post_data = substr($o,0,-1); 

            $postUrl = $url;
            $curlPost = $post_data;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$postUrl);
            curl_setopt($ch, CURLOPT_HEADER, 0); 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
            curl_setopt($ch, CURLOPT_POST, 1); 
            curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
            $data = curl_exec($ch); 
            curl_close($ch); 
            return $data;
    } 

    function request_get($url = '',$get_data = array()){
         if (empty($url) || empty($get_data)) {
                return false;
            }
            
            $o = "";
            foreach ( $get_data as $k => $v ){ 
                $o.= "$k=" . urlencode( $v ). "&" ;
            }
            $get_data = substr($o,0,-1); 
            $getUrl = $url;
            $curlGet = $get_data;
            $ch = curl_init(); 
            curl_setopt($ch, CURLOPT_URL, $getUrl);  
            curl_setopt($ch, CURLOPT_HEADER, 0); 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
            curl_setopt($ch, CURLOPT_POSTFIELDS, $curlGet);
            $data = curl_exec($ch); 
            curl_close($ch); 
            return $data;
    }

}