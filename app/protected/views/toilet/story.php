<?php
/**
 * Created by PhpStorm.
 * User: Apple
 * Date: 15/12/12
 * Time: 上午1:50
 */
?>

<section id="banner">
    <div class="inner">
        <p>
        <video width="820" height="615" controls>
            <source src="http://localhost/~joel.zhong/104/holyshit-online/test-program/story.mp4" type="video/mp4">
        </video>
        </p>
    </div>
</section>

<script>
    $(document).on("ready", function(){
        $("video").on("ended", function() {

            window.location.href = '<?php echo $this->createUrl('/toilet/introduction/'); ?>';
        });
    });
</script>