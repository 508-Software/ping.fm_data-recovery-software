<?php
/*
	Template Name: ChromeCast Page
*/
	get_header();

	$aUrl = explode("/", $_SERVER['REQUEST_URI']);
	$articleUrl = $aUrl[count($aUrl) - 2];

	$current_language = get_locale();

    if($current_language == 'de_DE') {
        $curr_url = 'de/';
    } elseif ($current_language == 'es_ES') {
        $curr_url = 'es/';
    } elseif ($current_language == 'fr_FR') {
        $curr_url = 'fr/';
    } elseif ($current_language == 'it_IT') {
        $curr_url = 'it/';
    } elseif ($current_language == 'ja') {
        $curr_url = 'ja/';
    } elseif ($current_language == 'pt_PT') {
        $curr_url = 'pt/';
    } elseif ($current_language == 'nl_NL') {
        $curr_url = 'nl/';
    } elseif ($current_language == 'ar') {
        $curr_url = 'ar/'; 
    } elseif ($current_language == 'zh-CN' || $current_language == 'zh_CN') {
        $curr_url = 'zh/';
    } elseif ($current_language == 'sv-SE' || $current_language == 'sv_SE') {
        $curr_url = 'sv/';
    } else {
        $curr_url = '';
    }
?>
		<main id="mainTag" data-href="<?php echo get_site_url();?>" data-uri="<?php echo $articleUrl; ?>" data-lang="<?php echo $curr_url; ?>">
			<div class="container">
				<div class="breadcrumb">
					<?php
						if(function_exists('bcn_display'))
						{
							bcn_display();
						}
					?>
				</div>
				<div class="info">
					<div class="author">
						<div class="img">
							<img src="<?php echo get_site_url();?>/wp-content/uploads/roman-krop.jpeg" alt="Roman Kropachek Photo" title="Roman Kropachek Photo" width="400" height="400">
						</div>
						<div class="auhordata">
							<div class="authorpost"><?php pll_e('Written by'); ?>:</div>
							<div class="title">
								<a href="https://www.linkedin.com/in/kropachek/" rel="nofollow" target="_blank">Roman Kropachek</a>
							</div>
						</div>
					</div>
					<p class="updated"><?php if ( get_the_modified_time() != get_the_time()) pll_e('Last update on '); ?> <time datetime="<?php echo the_modified_time( 'c' ); ?>"><?php echo ' '. the_modified_date('F d, Y'); ?></time></p>
				</div>
				<article>
					<?php
						the_content();
					?>
					<?php if ( get_field('faq' ) ): ?>
						<?php echo get_field('faq'); ?>
					<?php endif; ?>
				</article>
				<?php
					$pages = get_pages( [
						'exclude' => [get_the_ID(), 9, 2074, 2076, 2103, 2371, 2373, 2340, 2503, 2499, 2494, 2489, 2484, 2480, 2474]
					] );

					if(count($pages) > 0) {
				?>

				<aside>
					<div class="h2">
						<?php pll_e('More Articles'); ?>
					</div>
					<ul>
						<?php
							shuffle($pages);
							$output = array_slice($pages, 0, 14);
							foreach( $output as $post ) {
								$date = date_format(date_create($post->post_modified), 'M j, Y');
							?>
								<li>
									<div class="article">
										<div><?php echo $date ?></div>
										<div class="innerart">
											<img srcset="<?php echo get_site_url();?>/wp-content/uploads/note.png" src="<?php echo get_site_url();?>/wp-content/uploads/note.png" width="512" height="512" alt="Note icon">
											<a href="<?php echo esc_url( get_permalink($post->id) ); ?>" rel="follow"><?php echo $post->post_title ?></a>
										</div>
									</div>
								</li>
							<?php }
							wp_reset_postdata();
						?>
					</ul>
				</aside>
				<?php } ?>
			</div>
		</main>
		<script>
			jQuery('.langList a').each(function(index) {
				const lang = jQuery(this).attr('lang');
				const site = jQuery('#mainTag').attr('data-href');
				const uri = jQuery('#mainTag').attr('data-uri');
				if(lang === 'en-US') {
					jQuery(this).attr('href', site + '/' + uri + '/');
				} else {
					jQuery(this).attr('href', site + '/' + lang[0] + lang[1] + '/' + uri + '/')
				}
			})
			jQuery('.langList a').on('click', function(e) {
				e.preventDefault();
				const lang = jQuery(this).attr('lang');
				const site = jQuery('#mainTag').attr('data-href');
				const uri = jQuery('#mainTag').attr('data-uri');
				if(lang === 'en-US') {
					window.location.href = site + '/' + uri + '/';
				} else {
					window.location.href = site + '/' + lang[0] + lang[1] + '/' + uri + '/';
				}
			})

			jQuery('.breadcrumb a').attr('href', jQuery('#mainTag').attr('data-href') + '/' + jQuery('#mainTag').attr('data-lang'));
		</script>
<?php
	get_footer();