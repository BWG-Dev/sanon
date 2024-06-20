<div class="container image-left">
    <div class="row">
        <div class="col-12 col-lg-6 image-col">
            <div class="image-col-inner">
            <?php 
                if(strstr(get_sub_field('video'), 'youtube')){
                ?>    
                    <iframe width="560" height="315" src="<?php the_sub_field('video');?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            <?php    }else{
             ?>
                <video id="doc-player"  controls  muted  class="cld-video-player cld-fluid"></video>
                <script type="text/javascript">
                    var cld = cloudinary.Cloudinary.new({ cloud_name: 's-anon-international-family-groups-inc' });
                    var demoplayer = cld.videoPlayer('doc-player').width(600);
                    demoplayer.source('<?php the_sub_field('video'); ?>')

                </script>
                <?php } ?>  
            </div>
        </div>
        <div class="col-12 col-lg-6 para">
            <div class="color-secondary font-secondary bold">
                <?php the_sub_field('paragraph'); ?>
            </div>
            <div class="cta holder">
                <a href="<?php the_sub_field('cta_link'); ?>"
                    class="btn-primary"><?php the_sub_field('cta_text'); ?></a>
            </div>
        </div>

    </div>

</div>

