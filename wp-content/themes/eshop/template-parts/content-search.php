<?php
/**
 * The template part for displaying results in search pages.
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

		<?php if ( 'post' == get_post_type() ) : ?>
		<div class="entry-meta">
		<?php magzimum_posted_on(); ?>
		</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<div class="entry-summary">
		<?php if ( has_post_thumbnail() ): ?>
          <a href="<?php echo get_permalink();?>">
          <?php the_post_thumbnail( 'medium', array( 'class' => 'aligncenter' ) ); ?>
        </a>
        <?php endif ?>

		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->

	<footer class="entry-footer">
		<?php magzimum_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->