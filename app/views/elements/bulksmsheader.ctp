<script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript" src="/js/jquery.pngFix.js"></script>
<script type="text/javascript" src="/js/ddaccordion.js"></script>
<script type="text/javascript" src="/js/accordion.js"></script>
<script type="text/javascript" src="/js/common.js"></script>
<script type="text/javascript"> 
    $(document).ready(function(){ 
        //$(document).pngFix(); 
    }); 
</script> 
<div id="templatemo_header_wrapper">
    <div id="templatemo_header">
        <div id="templatemo_menu">
            <ul>
            	<li><a href="/bulksms/view" class="<?php if($tab['0']==1) echo 'current'; ?>"><span></span>Home</a></li>
            	<li><a href="/bulksms/sendnow" class="<?php if($tab['0']==2) echo 'current'; ?>"><span></span>Send SMS</a></li>
            	<!--<li><a href="/bulksms/showreport" class="<?php if($tab['0']==3) echo 'current'; ?>"><span></span>Reports</a></li>-->
            	<li><a href="/bulksms/addressbook" class="<?php if($tab['0']==4) echo 'current'; ?>"><span></span>Address Book</a></li>
            	<li><a href="/bulksms/help" class="<?php if($tab['0']==6) echo 'current'; ?>"><span></span>API</a></li>
            	<li><a href="/bulksms/myaccount" class="<?php if($tab['0']==5) echo 'current'; ?>"><span></span>My Account</a></li>
                <li><a href="/bulksms/signout"><span></span>Signout</a></li>
            </ul>   
            <div class="cleaner"></div> 	
        </div> <!-- end of menu -->
        
        <div class="cleaner"></div>
    </div> <!-- end of header -->

</div> <!-- end of header wrapper -->