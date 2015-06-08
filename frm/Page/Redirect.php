<?php

class Ftl_Redirect {



    public static function toPage( $url, $skipIfIsInThere = false )
    {
        if( $skipIfIsInThere && SCRIPT_NAME == $url ) {
                return false;
        }

        header( 'location: ' . $url );

        exit();
    }


}
?>
