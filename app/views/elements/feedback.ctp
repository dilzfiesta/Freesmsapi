<? $current_page = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>

<div class="testimonial_header"><span>Testimonials</span></div>

<?php foreach($feedback as $value) { ?>
<div class="testimonial_box_wrapper">
    <div class="testimonial_box">
        <div class="header_03"><?=$domain_list[$value['Feedback']['domain_id']]?></div>
        <p class="f12"><?=$value['Feedback']['feedback']?></p>
    </div>
</div>
<?php } ?>

<div class="margin_bottom_20"></div>

<div><iframe src="http://www.facebook.com/plugins/like.php?href=www.facebook.com%2Ffreesmsapi&amp;layout=standard&amp;show_faces=true&amp;width=200&amp;action=like&amp;font=arial&amp;colorscheme=light&amp;height=80" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:250px; height:80px;" allowTransparency="true"></iframe></div>

<div class="pad5">&nbsp;</div>
<div class="pad5">
	<div class="header_05">Share Freesmsapi on</div>
	<div>
		<a href="http://www.facebook.com/sharer.php?u=<?=$current_page?>" target="_blank"><img src="/img/fb.jpg" width="41" height="41" border="0" alt=""></a>&nbsp;
		<a href="http://twitter.com/share?url=<?=$current_page?>" target="_blank"><img src="/img/twit.jpg" width="41" height="41" border="0" alt=""></a>&nbsp;
		<a href="http://www.google.com/buzz/post?url=<?=$current_page?>" target="_blank"><img src="/img/buzz.jpg" width="41" height="41" border="0" alt=""></a>			
		<a href="http://www.stumbleupon.com/submit?url=<?=$current_page?>" target="_blank"><img src="/img/stum.png" width="41" height="41" border="0" alt=""></a>&nbsp;
		<a href="http://digg.com/submit?url=<?=$current_page?>" target="_blank"><img src="/img/digg.gif" width="41" height="41" border="0" alt=""></a>&nbsp;
	</div>
</div>