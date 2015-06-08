<?php
  require_once("../../../../frm/init.php");
  $graph_url = "https://graph.facebook.com/oauth/access_token?"  
    . "grant_type=fb_exchange_token"
    . "&client_id=" . FB_APP_ID
    . "&client_secret=" . FB_APP_SECRET
    . "&fb_exchange_token=" . $_REQUEST['access_token'];
  

  $result = file_get_contents($graph_url);
  
  if ($result){
      $long_resp    = explode('&', $result);
      $at           = explode('=', $long_resp[0]);
      $ex           = explode('=', $long_resp[1]);
  
      echo Ftl_JsonUtil::encode(array("state"=>1,"data"=>array(
            "access_token" => $at[1],
            "expires"   => $ex[1]
      )));
  }else{
      echo Ftl_JsonUtil::encode(array("state"=>0));
  }