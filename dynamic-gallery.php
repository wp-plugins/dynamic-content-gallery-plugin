<?php
/* This file is part of the DYNAMIC CONTENT GALLERY Plugin Version 2.0 beta 2
*****************************************************************************
Copyright 2008  Ade WALKER  (email : info@studiograsshopper.ch)

 *** Do not edit this block *** */
/* Load options */
$options = get_option('dfcg_plugin_settings');
/* Set up some variables to use in WP_Query */
$dfcg_offset = 1; 
$dfcg_imghome = $options['homeurl'];
$dfcg_imgpath = $options['imagepath'];
$dfcg_imgdefpath = $options['defimagepath'];
$dfcg_imgdefdesc = $options['defimagedesc'];
$dfcg_cat01 = $options['cat01'];
$dfcg_cat02 = $options['cat02'];
$dfcg_cat03 = $options['cat03'];
$dfcg_cat04 = $options['cat04'];
$dfcg_cat05 = $options['cat05'];
$dfcg_off01 = $options['off01']-$dfcg_offset;
$dfcg_off02 = $options['off02']-$dfcg_offset;
$dfcg_off03 = $options['off03']-$dfcg_offset;
$dfcg_off04 = $options['off04']-$dfcg_offset;
$dfcg_off05 = $options['off05']-$dfcg_offset;

?>
<div id="featured">
<STYLE>#myGallery, #myGallerySet, #flickrGallery
{
	width: <?php echo $options['gallery-width']; ?>px;
	height: <?php echo $options['gallery-height']; ?>px;
	z-index:5;
	border: <?php echo $options['gallery-border-thick']; ?>px solid <?php echo $options['gallery-border-colour']; ?>;
}
.jdGallery .slideInfoZone
{
	position: absolute;
	z-index: 10;
	width: 100%;
	margin: 0px;
	left: 0;
	bottom: 0;
	height: <?php echo $options['slide-height']; ?>px;
	background: #000;
	color: #fff;
	text-indent: 0;
	overflow: hidden;
}
.jdGallery .slideInfoZone h2
{
	padding: 0;
	border: 0 !important;
	font-size: <?php echo $options['slide-h2-size']; ?>px !important;
	margin: <?php echo $options['slide-h2-margtb']; ?>px <?php echo $options['slide-h2-marglr']; ?>px !important;
	font-weight: bold !important;
	color: <?php echo $options['slide-h2-colour']; ?> !important;
	background: none !important;
}

.jdGallery .slideInfoZone p
{
	padding: 0;
	font-size: <?php echo $options['slide-p-size']; ?>px !important;
	margin: <?php echo $options['slide-p-margtb']; ?>px <?php echo $options['slide-p-marglr']; ?>px !important;
	color: <?php echo $options['slide-p-colour']; ?> !important;
}
</STYLE>

<script type="text/javascript">
   function startGallery() {
      var myGallery = new gallery($('myGallery'), {
         timed: true
      });
   }
   window.addEvent('domready',startGallery);
</script>

<div class="content">
   <div id="myGallery">
         
         
		 
		 <?php
		 // *******************************************
		 // IMAGE ONE
		 // *******************************************
		 ?>
         
      	<div class="imageElement">
        	<?php
			$recent = new WP_Query("cat=$dfcg_cat01&showposts=1&offset=$dfcg_off01"); while($recent->have_posts()) : $recent->the_post();
			// Now find the cat ID
			foreach((get_the_category()) as $dfcg_category); ?>
         
		 		<h3><?php the_title(); ?></h3>
		 								
			<?php if( get_post_meta($post->ID, "dfcg-desc", true) ): ?>
				<p><?php echo get_post_meta($post->ID, "dfcg-desc", true); ?></p>
					<?php elseif (empty($dfcg_category->category_description)): ?>
				<p><?php echo $dfcg_imgdefdesc; ?></p>
					<?php else: ?>
				<p><?php echo $dfcg_category->category_description; ?></p>
					<?php endif; ?>
					
         		<a href="<?php the_permalink() ?>" title="Read More" class="open"></a>
			<?php if( get_post_meta($post->ID, "dfcg-image", true) ): ?>
			<?php $dfcg_imgname = get_post_meta($post->ID, "dfcg-image", true); ?>
         		<img src="<?php echo $dfcg_imghome . $dfcg_imgpath . $dfcg_imgname; ?>" alt="<?php the_title(); ?>" class="full" />
         		<img src="<?php echo $dfcg_imghome . $dfcg_imgpath . $dfcg_imgname; ?>" alt="<?php the_title(); ?>" class="thumbnail" />
					<?php else: ?>
				<img src="<?php echo $dfcg_imghome . $dfcg_imgdefpath . $dfcg_category->cat_ID . '.jpg'; ?>" alt="<?php the_title(); ?>" class="full" />
         		<img src="<?php echo $dfcg_imghome . $dfcg_imgdefpath . $dfcg_category->cat_ID . '.jpg'; ?>" alt="<?php the_title(); ?>" class="thumbnail" />
					<?php endif; ?>
         	<?php endwhile; ?>
      </div>
	  
	  <?php
		 // *******************************************
		 // IMAGE TWO
		 // *******************************************
		 ?>
      
      <div class="imageElement">
        	<?php
			$recent = new WP_Query("cat=$dfcg_cat02&showposts=1&offset=$dfcg_off02"); while($recent->have_posts()) : $recent->the_post();
			// Now find the cat ID
			foreach((get_the_category()) as $dfcg_category); ?>
         
		 		<h3><?php the_title(); ?></h3>
		 								
			<?php if ( get_post_meta($post->ID, "dfcg-desc", true ) ): ?>
				<p><?php echo get_post_meta($post->ID, "dfcg-desc", true); ?></p>
					<?php elseif (empty($dfcg_category->category_description)): ?>
				<p><?php echo $dfcg_imgdefdesc; ?></p>
					<?php else: ?>
				<p><?php echo $dfcg_category->category_description; ?></p>
					<?php endif; ?>
					
         		<a href="<?php the_permalink() ?>" title="Read More" class="open"></a>
			<?php if( get_post_meta($post->ID, "dfcg-image", true) ): ?>
			<?php $dfcg_imgname = get_post_meta($post->ID, "dfcg-image", true); ?>
         		<img src="<?php echo $dfcg_imghome . $dfcg_imgpath . $dfcg_imgname; ?>" alt="<?php the_title(); ?>" class="full" />
         		<img src="<?php echo $dfcg_imghome . $dfcg_imgpath . $dfcg_imgname; ?>" alt="<?php the_title(); ?>" class="thumbnail" />
					<?php else: ?>
				<img src="<?php echo $dfcg_imghome . $dfcg_imgdefpath . $dfcg_category->cat_ID . '.jpg'; ?>" alt="<?php the_title(); ?>" class="full" />
         		<img src="<?php echo $dfcg_imghome . $dfcg_imgdefpath . $dfcg_category->cat_ID . '.jpg'; ?>" alt="<?php the_title(); ?>" class="thumbnail" />
					<?php endif; ?>
         	<?php endwhile; ?>
      </div>
	  
	  <?php
		 // *******************************************
		 // IMAGE THREE
		 // *******************************************
		 ?>
      
      <div class="imageElement">
        	<?php
			$recent = new WP_Query("cat=$dfcg_cat03&showposts=1&offset=$dfcg_off03"); while($recent->have_posts()) : $recent->the_post();
			// Now find the cat ID
			foreach((get_the_category()) as $dfcg_category); ?>
         
		 		<h3><?php the_title(); ?></h3>
		 								
			<?php if( get_post_meta($post->ID, "dfcg-desc", true) ): ?>
				<p><?php echo get_post_meta($post->ID, "dfcg-desc", true); ?></p>
					<?php elseif (empty($dfcg_category->category_description)): ?>
				<p><?php echo $dfcg_imgdefdesc; ?></p>
					<?php else: ?>
				<p><?php echo $dfcg_category->category_description; ?></p>
					<?php endif; ?>
					
         		<a href="<?php the_permalink() ?>" title="Read More" class="open"></a>
			<?php if( get_post_meta($post->ID, "dfcg-image", true) ): ?>
			<?php $dfcg_imgname = get_post_meta($post->ID, "dfcg-image", true); ?>
         		<img src="<?php echo $dfcg_imghome . $dfcg_imgpath . $dfcg_imgname; ?>" alt="<?php the_title(); ?>" class="full" />
         		<img src="<?php echo $dfcg_imghome . $dfcg_imgpath . $dfcg_imgname; ?>" alt="<?php the_title(); ?>" class="thumbnail" />
					<?php else: ?>
				<img src="<?php echo $dfcg_imghome . $dfcg_imgdefpath . $dfcg_category->cat_ID . '.jpg'; ?>" alt="<?php the_title(); ?>" class="full" />
         		<img src="<?php echo $dfcg_imghome . $dfcg_imgdefpath . $dfcg_category->cat_ID . '.jpg'; ?>" alt="<?php the_title(); ?>" class="thumbnail" />
					<?php endif; ?>
         	<?php endwhile; ?>
      </div>
	  
	  <?php
		 // *******************************************
		 // IMAGE FOUR
		 // *******************************************
		 ?>
      
      <div class="imageElement">
        	<?php
			$recent = new WP_Query("cat=$dfcg_cat04&showposts=1&offset=$dfcg_off04"); while($recent->have_posts()) : $recent->the_post();
			// Now find the cat ID
			foreach((get_the_category()) as $dfcg_category); ?>
         
		 		<h3><?php the_title(); ?></h3>
		 								
			<?php if( get_post_meta($post->ID, "dfcg-desc", true) ): ?>
				<p><?php echo get_post_meta($post->ID, "dfcg-desc", true); ?></p>
					<?php elseif (empty($dfcg_category->category_description)): ?>
				<p><?php echo $dfcg_imgdefdesc; ?></p>
					<?php else: ?>
				<p><?php echo $dfcg_category->category_description; ?></p>
					<?php endif; ?>
					
         		<a href="<?php the_permalink() ?>" title="Read More" class="open"></a>
			<?php if( get_post_meta($post->ID, "dfcg-image", true) ): ?>
			<?php $dfcg_imgname = get_post_meta($post->ID, "dfcg-image", true); ?>
         		<img src="<?php echo $dfcg_imghome . $dfcg_imgpath . $dfcg_imgname; ?>" alt="<?php the_title(); ?>" class="full" />
         		<img src="<?php echo $dfcg_imghome . $dfcg_imgpath . $dfcg_imgname; ?>" alt="<?php the_title(); ?>" class="thumbnail" />
					<?php else: ?>
				<img src="<?php echo $dfcg_imghome . $dfcg_imgdefpath . $dfcg_category->cat_ID . '.jpg'; ?>" alt="<?php the_title(); ?>" class="full" />
         		<img src="<?php echo $dfcg_imghome . $dfcg_imgdefpath . $dfcg_category->cat_ID . '.jpg'; ?>" alt="<?php the_title(); ?>" class="thumbnail" />
					<?php endif; ?>
         	<?php endwhile; ?>
      </div>
	  
	  <?php
		 // *******************************************
		 // IMAGE FIVE
		 // *******************************************
		 ?>
      
      <div class="imageElement">
        	<?php
			$recent = new WP_Query("cat=$dfcg_cat05&showposts=1&offset=$dfcg_off05"); while($recent->have_posts()) : $recent->the_post();
			// Now find the cat ID
			foreach((get_the_category()) as $dfcg_category); ?>
         
		 		<h3><?php the_title(); ?></h3>
		 								
			<?php if( get_post_meta($post->ID, "dfcg-desc", true) ): ?>
				<p><?php echo get_post_meta($post->ID, "dfcg-desc", true); ?></p>
					<?php elseif (empty($dfcg_category->category_description)): ?>
				<p><?php echo $dfcg_imgdefdesc; ?></p>
					<?php else: ?>
				<p><?php echo $dfcg_category->category_description; ?></p>
					<?php endif; ?>
					
         		<a href="<?php the_permalink() ?>" title="Read More" class="open"></a>
			<?php if( get_post_meta($post->ID, "dfcg-image", true) ): ?>
			<?php $dfcg_imgname = get_post_meta($post->ID, "dfcg-image", true); ?>
         		<img src="<?php echo $dfcg_imghome . $dfcg_imgpath . $dfcg_imgname; ?>" alt="<?php the_title(); ?>" class="full" />
         		<img src="<?php echo $dfcg_imghome . $dfcg_imgpath . $dfcg_imgname; ?>" alt="<?php the_title(); ?>" class="thumbnail" />
					<?php else: ?>
				<img src="<?php echo $dfcg_imghome . $dfcg_imgdefpath . $dfcg_category->cat_ID . '.jpg'; ?>" alt="<?php the_title(); ?>" class="full" />
         		<img src="<?php echo $dfcg_imghome . $dfcg_imgdefpath . $dfcg_category->cat_ID . '.jpg'; ?>" alt="<?php the_title(); ?>" class="thumbnail" />
					<?php endif; ?>
         	<?php endwhile; ?>
      </div>
      
    </div>
</div>
</div>
