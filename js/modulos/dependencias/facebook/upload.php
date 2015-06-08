<?php
    
    $resp = array();
    try{

        $token      = $_GET['access_token'];
        $aid        = isset($_GET['aid'])       ? $_GET['aid']      : "me";
        $titulo     = isset($_GET['mensaje'])    ? $_GET['mensaje']  : "";
        $foto       = isset($_GET['imagen'])   ? $_GET['imagen']  : "";



        if (file_exists($foto))
        {
            /*
            $args           = array('message' => $titulo);
            $args['image']  = '@' . realpath($foto);
*/

            $arr_attachment = array('image' => '@'.realpath($foto),
                                    'message' => $titulo
                                );

            $_curl = curl_init();
            curl_setopt($_curl, CURLOPT_URL, "https://graph.facebook.com/" . $aid . "/photos?access_token=".$token);
            //curl_setopt($_curl, CURLOPT_URL, "https://graph.facebook.com/" . $aid . "/albums?access_token=".$token);
            curl_setopt($_curl, CURLOPT_HEADER, false);
            curl_setopt($_curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($_curl, CURLOPT_POST, true);
            curl_setopt($_curl, CURLOPT_POSTFIELDS, $arr_attachment);
            curl_setopt($_curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($_curl, CURLOPT_SSL_VERIFYPEER, 0);

            $_photo = curl_exec($_curl);

            $resutl = json_decode($_photo,true);

            if (isset($resutl) && !isset($resutl["error"]))
            {
                
                $resp = array(
                    "estado" => 1,
                    "datos" => array(
                        "id" => $resutl["id"],
                        "ruta" => realpath($foto)
                    )
                );
                
//                echo '{
//                        "data": {
//                            "id":' . json_encode($resutl["id"]) . ',
//                            "ruta": @'.realpath($foto).'
//                         }
//                       }';
            }
            else
            {
                echo '{
                        "error": ' . json_encode($resutl["error"]) . '
                       }';
            }
        }else{
                echo '{
                        "error": "No existe la imagen"
                       }';

        }
    }catch (Exception $e) {
            echo '{
                    "error": ' . $e->getMessage() . '
                   }';
    }

    echo json_encode($resp);