<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.14/themes/base/jquery-ui.css" type="text/css" media="all" /> 
<link rel="stylesheet" href="http://static.jquery.com/ui/css/demo-docs-theme/ui.theme.css" type="text/css" media="all" /> 
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js" type="text/javascript"></script> 
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.14/jquery-ui.min.js" type="text/javascript"></script> 

<!--<script type="text/javascript" src="/js/jquery.js"></script>-->
<script type="text/javascript" src="/js/jquery.pngFix.js"></script>
<script type="text/javascript" src="/js/ddaccordion.js"></script>
<script type="text/javascript" src="/js/accordion.js"></script>
<script type="text/javascript"> 
    $(document).ready(function(){ 
        $(document).pngFix(); 
    }); 
</script> 
<div id="templatemo_header_wrapper">
    <div id="templatemo_header">
        <div id="templatemo_menu">
            <ul>
                <li><a href="/users/view" class="<?php if($tab['0']==1) echo 'current'; ?>"><span></span>Home</a></li>
                <li><a href="/messages/sendnow" class="<?php if($tab['0']==3) echo 'current'; ?>"><span></span>Send SMS</a></li>
                <li><a href="/users/help" class="<?php if(isset($tab) && $tab['0']==9) echo 'current'; ?>"><span></span>API Code</a></li>
                <li><a href="/users/myaccount" class="<?php if($tab['0']==2) echo 'current'; ?>"><span></span>My Account</a></li>
                <!--<li><a href="/users/help" class="<?php if($tab['0']==4) echo 'current'; ?>"><span></span>API</a></li>-->
                <?php if(SHOW_SENDER_ID) { ?>
                <li><a href="/users/pricingsenderid" class="<?php if($tab['0']==7) echo 'current'; ?>"><span></span>Pricing</a></li>
                <?php } else { ?>
                <li><a href="/users/refprogram" class="<?php if($tab['0']==7) echo 'current'; ?>"><span></span>Referral Program</a></li>
                <?php } ?>
                <!--<li><a href="/feedbacks/feedback" class="<?php if($tab['0']==6) echo 'current'; ?>"><span></span>Feedback</a></li>-->
                <!--<li><a href="/faqs" class="<?php if($tab['0']==8) echo 'current'; ?>"><span></span>FAQS</a></li>-->
                <li><a href="/users/signout"><span></span>Sign Out</a></li>
            </ul>   
            <div class="cleaner"></div> 	
        </div> <!-- end of menu -->
        
        <div class="cleaner"></div>
    </div> <!-- end of header -->

</div> <!-- end of header wrapper -->