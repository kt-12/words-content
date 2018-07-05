<?php
/**
 * Displays Add/Edit Form.
 *
 * @link       https://kt12.in
 * @since      1.0.0
 *
 * @package    Words_Content
 * @subpackage Words_Content/admin/partials
 */

?>
<div class="wrap" id="kt12word-root">
	<h1 class="wp-heading-inline"><?php esc_html_e( "Word's Content", 'kt12words-content' ); ?>
	</h1>
	<a href="<?php echo esc_url_raw( add_query_arg( array( 'page' => 'kt12words-content' ), admin_url( 'admin.php' ) ) ); ?>"
		class="page-title-action"><?php esc_html_e( 'List All', 'kt12words-content' ); ?>
	</a>
	<hr class="wp-header-end">
	<div class="notice notice-warning is-dismissible"> <p><strong><?php esc_html_e( 'Note:', 'kt12words-content' ); ?></strong> <strong style="color: tomato"><?php esc_html_e( "Sidebar's content inherits current pages css. Preview in backend will look different form that in frontend. So do you final check on the frontend page.", 'kt12words-content' ); ?></strong> </p> </div>
	<?php $this->message_notices(); ?>
<form action="<?php echo esc_attr( $form_action ); ?>" method="post" accept-charset="utf-8">
	<input type="hidden" name="action" value="<?php echo esc_attr( $action ); ?>">
	<input type="hidden" name="kt12word_word_id" value="<?php echo esc_attr( $word_id ); ?>">
	<?php wp_referer_field( true ); ?>
	<div class="container margin-top-20 float-left">
		<div class="row">
		<div class="col-md-8 col-lg-8 margin-left-15-desc">
			<div class="form-group">
				<label for="kt12word_word" class="kt12word-label"><strong><?php esc_html_e( 'Highlighting Word', 'kt12words-content' ); ?></strong><small class="text-danger"> (<?php esc_html_e( 'required', 'kt12words-content' ); ?>)</small></label>
				<input type="text" class="form-control" id="kt12word_word" name="kt12word_word" aria-describedby="kt12word-word" placeholder="<?php esc_html_e( 'Enter a word, a phrase or a sentence', 'kt12words-content' ); ?>" value="<?php echo esc_attr( $word ); ?>">
				<small id="kt12word-word" class="form-text  text-primary"><?php esc_html_e( 'You can enter any string. The string will be lower cased while saving', 'kt12words-content' ); ?></small>
			</div>
			<div class="form-group">
				<label for="kt12word_title" class="kt12word-label"><strong><?php esc_html_e( 'Word Title', 'kt12words-content' ); ?></strong><small class="form-text text-danger"> (<?php esc_html_e( 'required', 'kt12words-content' ); ?>)</small></label>
				<input type="text" class="form-control" id="kt12word_title" name="kt12word_title" aria-describedby="kt12word-title" placeholder="<?php esc_html_e( 'Enter title for the content', 'kt12words-content' ); ?>" value="<?php echo esc_attr( $word_title ); ?>" >
				<small id="kt12word-word" class="form-text  text-primary"><?php esc_html_e( 'Title goes into the header of sidebar', 'kt12words-content' ); ?></small>
			</div>

			<div class="form-group">
				<label for="kt12word_content" class="kt12word-label"><strong><?php esc_html_e( 'Content', 'kt12words-content' ); ?></strong><small class="text-danger"> (<?php esc_html_e( 'required', 'kt12words-content' ); ?>)</small></label>
				<?php
					$id = 'kt12word_content';
					wp_editor(
						$content,
						$id,
						array(
							'textarea_rows' => 12,
							'tabindex'      => 2,
							'tinymce'       => array(
								'theme_advanced_buttons1' => 'bold, italic, ul, min_size, max_size',
							),
						)
					);
				?>
			</div>
		</div>
		<div class="col-md-4 col-lg-4 margin-left-15-desc">
			<div class="card kt12word-card">
				<div class="card-header">
					<?php esc_html_e( 'Actions', 'kt12words-content' ); ?>
				</div>
				<div class="card-body">
						<button class="btn btn-danger"><?php esc_html_e( 'Update', 'kt12words-content' ); ?></button>
						<button class="btn btn-primary float-right" id="kt12word_preview" @click.stop.prevent="showPreview"><?php esc_html_e( 'Preview', 'kt12words-content' ); ?></button>
				</div>
			</div>
		</div>
	</div>
	</div>
</form>
<kt12word-sidebar v-show="showSidebar">
	<template slot="footer"></template>
</kt12word-sidebar>
</div>
