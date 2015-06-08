<?php

if ($this->showHeader()){


$nombrePagina   = Ftl_Path::getFileName();
$titulo         = "";

?>
<div class="clear">&nbsp;</div>

<!--  start nav-outer-repeat................................................................................................. START -->
<div class="nav-outer-repeat ui-widget-header ui-helper-clearfix">
<!--  start nav-outer -->
<div class="nav-outer ">
    <div id="nav-left">
                <?php
                    if ($this->showMenu){?>
                        <a tabindex="0" href="javascript:void(0);" class="fg-button fg-button-icon-right ui-widget ui-state-default ui-corner-all" id="flyout"><span class="ui-icon ui-icon-triangle-1-s"></span>Menu</a>
                <?php
                    }?>        
        
    </div>
		<!-- start nav-right -->
		<div id="nav-right" class="ui-widget">
<?php
    $user = (isset($this->session) ? $this->session->getUser() : null);
    
?>
                        <!--div class="nav-divider">&nbsp;</div-->
			<a href="logout.php" id="logout">Logout</a>
			<div class="clear">&nbsp;</div>
		</div>
		<!-- end nav-right -->


</div>
<div class="clear"></div>

</div>

 <!--div class="clear"></div-->
 

<?php
}
