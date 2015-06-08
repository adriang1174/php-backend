<?php


if ($this->charsetEncoding)
    Ftl_Header::setCharsetEncoding($this->charsetEncoding);
if (!isset($_REQUEST['export']) || (isset($_REQUEST['export']) && $_REQUEST['export'] == '0'))
{
    //
    if ( !$this->cacheable )
    Ftl_Header::setNoCache();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $this->title;?></title>


<style type="text/css">

	</style>
<?php echo Ftl_ScriptLoader::load($this->scripts);?>
<?php
    foreach($this->jsController as $tupla){

        echo Ftl_ScriptLoader::loadJSController($tupla['file'], $tupla['dir']);

    }
?>

<link rel="stylesheet" href="<?php echo URL_ROOT . "admin/" ;?>css/screen.css" type="text/css" media="screen" title="default" />
<!--<link rel="stylesheet" href="<?php echo URL_ROOT . "js/plugins/jqueryui/" ;?>jquery-ui.custom.css" type="text/css" media="all" />-->
<!--[if IE]>
<link rel="stylesheet" media="all" type="text/css" href="css/pro_dropline_ie.css" />
<![endif]-->

<!-- Custom jquery scripts -->
<!--<script src="<?php echo URL_ROOT . "admin/" ;?>js/jquery/custom_jquery.js" type="text/javascript"></script>-->

<script src="<?php echo URL_ROOT . "admin/" ;?>js/jquery/jquery.pngFix.pack.js" type="text/javascript"></script>
<script type="text/javascript">

    $(document).ready(function(){
        $.get('<?php echo URL_ROOT ."admin/includes/inc_menu.php?idPerfil=".(isset($this->session) && $this->session->getUser() !== null  ? $this->session->getUser()->getIdPerfil():0)."&root=".URL_ROOT . "admin/";?>', function(data){ // grab content from another page
            $('#flyout').menu({ content: data, flyOut: true });
            //jQuery.each($('.flyout'), function() {
                    //$('#flyout').menu({ content: data, flyOut: true });
            //});                
                
                
        });
        $(document).pngFix( );
    });
</script>

</head>
<body>
    <?php include (PATH_SITE . '/admin/includes/inc_nav.php');?>
<div id="content-outer">
<!-- start content -->
<div id="content"  class="ui-widget">

	<!--  start page-heading -->
	<div id="page-heading">

                <span class="title"><?php echo $this->title;?></span>
	</div>
	<!-- end page-heading -->

	<table border="0"  cellpadding="0" cellspacing="0" id="content-table">
	<tr>
		<!--th rowspan="3" class="sized"><img src="images/shared/side_shadowleft.jpg" width="20" height="300" alt="" /></th-->
		<th class="topleft"></th>
		<td id="tbl-border-top">&nbsp;</td>
		<th class="topright"></th>
		<!--th rowspan="3" class="sized"><img src="images/shared/side_shadowright.jpg" width="20" height="300" alt="" /></th-->
	</tr>
	<tr>
		<td id="tbl-border-left"></td>
		<td>
		<!--  start content-table-inner ...................................................................... START -->
		<div id="content-table-inner">
<?php
    }
?>