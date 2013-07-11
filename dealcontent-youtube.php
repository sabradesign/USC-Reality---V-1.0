<?php $youtubeID = get_post_meta( get_the_ID(), 'REALITY_deal_video_link', true); ?>
<div class="youtube-player">

	<iframe width="635" height="357" src="http://www.youtube.com/embed/<?php echo $youtubeID ?>?rel=0" frameborder="0" allowfullscreen></iframe>

</div>