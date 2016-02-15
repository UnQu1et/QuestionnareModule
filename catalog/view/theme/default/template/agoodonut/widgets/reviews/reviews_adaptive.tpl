<?php if (isset($comments) && $comments) { ?>
<div class="box" id="cmswidget-<?php echo $cmswidget; ?>">
	<div class="box-heading"><?php echo $heading_title; ?></div>
	<div class="box-content">
		<div class="reviews_columns">
			<?php foreach ($comments as $comment) { ?>
			<div style="<?php if (isset($settings['reserved'])) { echo $settings['reserved']; } ?>">
				<div class="blogdescription  margintop5">
					<?php if (isset ($settings['view_author']) && $settings['view_author'] ) { ?>
					<?php if ($comment['author']) { ?>
					<div class="blog-author"><?php echo $comment['author']; ?></div>
					<?php } ?>
					<?php } ?>

					<?php if (isset ($settings['view_rating']) && $settings['view_rating'] ) { ?>
					<?php if ($comment['rating']) { ?>
					<div class="rating">
						<?php
						$themeFile = $this->getThemeFile('image/blogstars-'.$comment['rating'].'.png');
						if ($themeFile) {
						?>
						<img style="border: 0px;"  title="<?php echo $comment['rating']; ?>" alt="<?php echo $comment['rating']; ?>" src="catalog/view/theme/<?php echo $themeFile; ?>">
						<?php } ?>
					</div>
					<?php } ?>
					<?php } ?>


					<?php if (isset ($settings['view_avatar']) && $settings['view_avatar'] ) { ?>
					<?php if ($comment['thumb']) { ?>
					<div class="image blog-image"><a href="<?php echo $comment['record_href']; ?>"><img src="<?php echo $comment['thumb']; ?>" title="<?php echo $comment['record_name']; ?>" alt="<?php echo $comment['record_name']; ?>" /></a></div>
					<?php } ?>
					<?php } ?>

					<?php echo $comment['text']; ?>&nbsp;
					<a href="<?php echo $comment['record_href']; ?>#comment_link_<?php  echo $comment['comment_id']; ?>" class="blog_further"><?php if (isset($settings_general['further'][$this->config->get('config_language_id')])) echo html_entity_decode($settings_general['further'][$this->config->get('config_language_id')]); ?></a>
				</div>


				<div class="clearboth width100 overflowhidden lineheight1">&nbsp;</div>

				<?php if (isset ($settings['view_date']) && $settings['view_date'] ) { ?>
				<?php if ($comment['date']) { ?>
				<div class="blog-date"><?php echo $comment['date']; ?></div>
				<?php } ?>
				<?php } ?>

				<div class="clearboth width100 overflowhidden lineheight1">&nbsp;</div>

				<?php if (isset ($settings['view_category']) && $settings['view_category'] ) { ?>

				<div class="blog-light-small-text"><?php echo $comment['text_category']; ?>
					<a href="<?php echo $comment['blog_href']; ?>" class="blog-little-title"><?php echo $comment['blog_name']; ?></a>
				</div>
				<?php } ?>

				<div class="clearboth width100 overflowhidden lineheight1">&nbsp;</div>

				<?php if (isset ($settings['view_record']) && $settings['view_record'] ) { ?>
				<div class="blog-light-small-text"><?php echo $comment['text_record']; ?>
					<a href="<?php echo $comment['record_href']; ?>" class="blog-little-title"><?php echo $comment['record_name']; ?></a>
				</div>
				<?php } ?>

				<div class="clearboth width100 overflowhidden lineheight1">&nbsp;</div>

				<?php if (isset ($settings['view_comments']) && $settings['view_comments'] ) { ?>
				<?php if ($comment['record_comments']) { ?>
				<div class="blog-light-small-text"><?php echo $text_comments; ?> <?php echo $comment['record_comments']; ?></div>
				<?php } ?>
				<?php } ?>

				<div class="clearboth width100 overflowhidden lineheight1">&nbsp;</div>

				<?php if (isset ($settings['view_viewed']) && $settings['view_viewed'] ) { ?>
				<div class="blog-light-small-text"><?php echo $text_viewed; ?> <?php echo $comment['record_viewed']; ?></div>
				<?php } ?>

				<div class="clearboth width100 overflowhidden lineheight1">&nbsp;</div>

				<div class="lineheight1">&nbsp;</div>
				<div class="blog-child_divider">&nbsp;</div>

			</div>
			<?php } ?>
		</div>
		<?php if (isset ($settings['pagination']) && $settings['pagination'] ) { ?>
		<div class="pagination margintop5"><?php echo $pagination; ?></div>
		<?php } ?>
	</div>
</div>

<?php } ?>
