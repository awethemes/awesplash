<!DOCTYPE html>
<html lang="en">
    <head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="profile" href="http://gmpg.org/xfn/11">
		
		<?php wp_head() ?>
    </head>
    <body <?php body_class() ?>>
        <div class="site" id="page">

            <a class="skip-link sr-only" href="#main"><?php echo esc_html__( 'Skip to content', 'awesplash' ) ?></a>
			
            <section class="hero-section--<?php echo esc_attr( get_theme_mod( 'awesplash_background_type', 'color' ) ) ?> clearfix">
                <div class="hero-section__wrap">
					<?php awesplash_template_background() ?>

					<div class="container">
                        <div class="row">
                            <div class="offset-lg-2 col-lg-8">
                                <div class="title text-center">

									<?php awesplash_template_heading(); ?>

									<?php awesplash_template_content(); ?>

									<form method="post">
										
										<?php wp_nonce_field( 'awesplash', 'awesplash_nonce' ); ?>

										<?php awesplash_template_age() ?>

										<?php awesplash_template_opt() ?>

										<?php awesplash_template_button() ?>

										<?php awesplash_template_errors() ?>
									</form>
                                </div>
								
                            </div>
                        </div>
                    </div>

                </div>
            </section>
            <!-- .hero-section -->
        </div>

		<?php wp_footer() ?>

    </body>
</html>
