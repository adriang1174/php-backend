<?php
require_once '../../frm/init.php';
$root = $_REQUEST['root'];
$idPerfil = $_REQUEST['idPerfil'];


$menu = array(
//    "Dashboard" => array(
//    	"url"   => "index.php",
//    ),
    "Usuarios" => array(

        "url"   => "usuarios.php",
        "sub"   => array(

            "usuarios.php"      => array("titulo" => "Listado"),
            "am-usuarios.php"   => array("titulo" => "Alta")

        )

    ),
    "Registrados" => array(

        "url"   => "users.php",
        "sub"   => array(

            "users.php"   => array("titulo" => "Participantes Registrados")

        )

    ),
//    "Fotos" => array(
//
//      "url"   => "fotos.php",
//        "sub"   => array(
//            "fotos.php"   => array("titulo" => "Listado"),
//            "ranking_votos.php"=> array("titulo" => "Ranking por votos")
            
//        )

//    ),
    "Tracking/Estadísticas" => array(
        "url"   => "codes.php",
        "sub"   => array(
            "codes.php"   => array("titulo" => "Códigos de empaque ingresados"),
            "rank.php"   => array("titulo" => "Ranking Semanal"),
            "packs.php"   => array("titulo" => "Totales empaques"),
        )
    ),
//    "Sorteo" => array(
//    	"url"   => "sorteo.php",
//    ), 
//    "Estadísticas" => array(
//
//        "url"   => "estadisticas.php",
//        "sub"   => array(
//            "estadisticas.php"   => array("titulo" => "Ver")
//        )
//
//    )
    

);



$ulMenu = "";

if ( count($menu) > 0 ){

    $ulMenu .= "<ul>";
    foreach ($menu as $item => $datos){

        $ulMenu .= "<li>";

        $ulMenu .= "<a href=\"". $root . $datos["url"] . "\">" . $item . "</a>";
//        $ulMenu .= "<a href=\"" . HTTP_URL_ROOT . "admin/". $datos["url"] . "\">" . $item . "</a>";

        if ( isset($datos["sub"]) && count($datos["sub"]) > 0 ) {
            
            $ulMenu .= "<ul>";
            foreach ($datos["sub"] as $url => $datosSub)
            {
                $ulMenu .=  "<li><a href=\"$root$url\">{$datosSub["titulo"]}</a></li>";
//                $ulMenu .=  "<li><a href=\"" .  HTTP_URL_ROOT . "admin/". $url . "\">{$datosSub["titulo"]}</a></li>";
            }
            $ulMenu .= "</ul>";
        }


        $ulMenu .= "</li>";
    }
    $ulMenu .= "</ul>";
}
echo $ulMenu;
/*
foreach ($menu as $item => $datos)
{
    $menuActivo = false;
    if (array_key_exists($nombrePagina, $datos["sub"]))
            $menuActivo = true;

    echo "<ul>";
    echo "<!--[if lte IE 6]><table><tr><td><![endif]-->\n";
    echo "<div class=\"select_sub". ($menuActivo ? " show" : "") . "\">\n";
    echo "<ul class=\"sub\">\n";

    foreach ($datos["sub"] as $url => $datosSub)
    {
        $subMenuActivo = ($nombrePagina == $url) ? true : false;

        if ($subMenuActivo) $titulo = $datosSub["titulo"];

        echo "<li " . ($subMenuActivo ? "class=\"sub_show\"" : "")  . "><a href=\"{$url}\">{$datosSub["titulo"]}</a></li>\n";
    }
    echo "</ul>\n";
    echo "</div>\n";
    echo "<!--[if lte IE 6]></td></tr></table></a><![endif]-->\n";
    echo "</li>\n";
    echo "</ul>\n";
    echo "<div class=\"nav-divider\">&nbsp;</div>\n";*/


