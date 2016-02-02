<div class="header_02">Testimonials</div>

<?php foreach($feedback as $value) { ?>
<div class="testimonial_box_wrapper">
    <div class="testimonial_box">
        <div class="header_03"><?=$domain_list[$value['Feedback']['domain_id']]?></div>
        <p class="f12"><?=$value['Feedback']['feedback']?></p>
    </div>
</div>
<?php } ?>