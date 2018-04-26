<?php
/**
 * The Template for displaying the directory top bar.
 *
 * This template can be overridden by copying it to yourtheme/wpum/directory/top-bar.php
 *
 * HOWEVER, on occasion WPUM will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @version 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

?>

<div id="wpum-directory-top-bar">

	<div class="wpum-row">

		<div class="wpum-col-xs">
			<?php echo sprintf( esc_html__( 'Found %s users.' ), 0 ) ?>
		</div>

		<div class="wpum-col-xs">
			<p>
				<?php esc_html_e( 'Sort by:' ); ?>
				<?php echo WPUM()->elements->select( [
					'name'             => 'wpum-directory-sortby',
					'show_option_all'  => false,
					'show_option_none' => false,
					'options'          => wpum_get_directory_sort_by_methods()
				] ); ?>
			</p>
		</div>

		<div class="wpum-col-xs">
			<p>
				<?php esc_html_e( 'Results per page:' ); ?>
			</p>
		</div>

	</div>

</div>