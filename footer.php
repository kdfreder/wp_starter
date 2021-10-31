			<footer class="footer" role="contentinfo" itemscope itemtype="http://schema.org/WPFooter">
				<div class="wrap">
					<a href="<?php echo home_url(); ?>" class="img-holder logo">
						<img src="<?php echo get_field('footer_logo','option')['sizes']['lo-res'];?>" alt="<?php bloginfo( 'name' ); ?>" />
					</a>
					<nav>
						<?php wp_nav_menu(array(
							'container' => false,
							'menu' => 'Footer Menu',
							'menu_class' => 'nav',
							'depth' => 0,
						)); ?>
					</nav>
				</div>
			</footer>
			<div id="lightbox">
				<div class="overlay lightbox-closer"></div>
				<div class="inner">
					<i class="fa fa-times lightbox-closer"></i>
					<div class="lightbox-content">
						
					</div>
				</div>
			</div>
			<div id="scroll-top-catch"></div>
			<div id="scroll-top"><i class="lnr lnr-arrow-up"></i></div>
		</div><?php //#container ?>
		<?php wp_footer(); ?>
		<?php the_field('footer_scripts', 'option'); ?>
	</body>
</html>
