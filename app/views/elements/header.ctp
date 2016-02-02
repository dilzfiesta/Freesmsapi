<?php if(ONLY_DEVELOPER_SECTION) $reg = '/users/registration'; else $reg = '/users/register'; ?>
<div id="templatemo_header_wrapper">
    <div id="templatemo_header">
    	<!--<div id="logo"></div>-->
        <div id="templatemo_menu">
            <ul>
                <li><a href="<?=SERVER?>" class="<?php if(isset($tab) && $tab['0']==1) echo 'current'; ?>"><span></span>Home</a></li>
                <li><a href="<?=SERVER?>company/features" class="<?php if(isset($tab) && $tab['0']==2) echo 'current'; ?>"><span></span>Features</a></li>
                <li><a href="<?=SERVER?>widgets" class="<?php if(isset($tab) && $tab['0']==8) echo 'current'; ?>"><span></span>Widget</a></li>
                <li><a href="<?=SERVER?>company/services" class="<?php if(isset($tab) && $tab['0']==3) echo 'current'; ?>"><span></span>Services</a></li>
                <li><a href="<?=SERVER?>users/pricing" class="<?php if(isset($tab) && $tab['0']==7) echo 'current'; ?>"><span></span>Bulk Pricing</a></li>
                <!--<li><a href="<?=SERVER?>faqs" class="<?php if(isset($tab) && $tab['0']==6) echo 'current'; ?>"><span></span>FAQs</a></li>-->
                <li><a href="<?=SERVER?>feedbacks/contact" class="<?php if(isset($tab) && $tab['0']==5) echo 'current'; ?>"><span></span>Contact Us</a></li>
            </ul>   
            <div class="cleaner"></div> 	
        </div> <!-- end of menu -->
        
        <div class="cleaner"></div>
    </div> <!-- end of header -->

</div> <!-- end of header wrapper -->