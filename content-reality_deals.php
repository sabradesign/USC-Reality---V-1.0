<?php global $post; ?>
<div class="deal <?php echo $post->post_name; ?>">
	<?php $makerCard = reality_get_maker_card( get_the_ID() ); ?>
	<div class="maker-card"><?php echo $makerCard->description; ?></div>
	<h3><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
	<div class="deal-description"><?php the_excerpt(); ?></div>
	<div class="deal-image">
		<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" style="background-image:url(<?php timthumb_photo( get_the_deal_image_url( get_the_ID(), 'large' ), 284, 159 ) ?>)">
			<div class="aspect-control"></div>
			<div class="deal-points"><?php the_deal_points(); ?><span>points</span></div>
		</a>
	</div>
	

</div>