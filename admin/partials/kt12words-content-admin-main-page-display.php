<?php
/**
 * Displays list of words in the database along with their preview.
 *
 * @link       https://kt12.in
 * @since      1.0.0
 *
 * @package    Words_Content
 * @subpackage Words_Content/admin/partials
 */

?>
<div class="wrap" id="kt12word-root">
	<h1 class="wp-heading-inline"><?php esc_html_e( "Word's Content", 'kt12words-content' ); ?></h1>
	<a href="<?php echo esc_url_raw( $add_new_page ); ?>" class="page-title-action"><?php esc_html_e( 'Add New', 'kt12words-content' ); ?></a>
	<hr class="wp-header-end">
	<div class="notice notice-warning is-dismissible"> <p><strong><?php esc_html_e( 'Note:', 'kt12words-content' ); ?></strong> <strong style="color: tomato"><?php esc_html_e( "Sidebar's content inherits current pages css. Preview in backend will look different form that in frontend. You should make a final check on the frontend page before going live.", 'kt12words-content' ); ?></strong> </p> </div>
	<?php $this->message_notices(); ?>
	<div style="margin-top: 30px">
		<div class="row">
			<div class="col-md-12" >
				<table class="table table-striped " id="kt12word-list-table">
					<thead class="thead-default">
						<tr>
							<!-- <th>#</th> -->
							<th><?php esc_html_e( 'Id', 'kt12words-content' ); ?></th>
							<th><?php esc_html_e( 'Word', 'kt12words-content' ); ?></th>
							<th><?php esc_html_e( 'Title', 'kt12words-content' ); ?></th>
							<th><?php esc_html_e( 'Preview', 'kt12words-content' ); ?></th>
							<th><?php esc_html_e( 'Action', 'kt12words-content' ); ?></th>
						</tr>
						<tbody>
							<?php
							foreach ( $results as $row ) :
										$edit_word_page = add_query_arg(
											array(
												'page' => 'kt12words-content',
												'view' => 'edit',
												'id'   => $row->id,
											),
											admin_url( 'admin.php' )
										);
									?>
							<tr>
								<!-- <td></td> -->
								<td scope="row"><?php echo esc_html( $row->id ); ?></td>
								<td><?php echo esc_html( $row->word ); ?></td>
								<td><?php echo esc_attr( $row->title ); ?></td>
								<td>
									<kt12word-markup word-id="<?php echo esc_attr( $row->id ); ?>"><?php echo esc_html( $row->word ); ?></kt12word-markup>
								</td>
								<td>
										<a class="btn btn-sm btn-warning" href="<?php echo esc_attr( wp_nonce_url( $edit_word_page, 'edit_kt12word_nonce', '__edit_nonce' ) ); ?>">
											<?php esc_html_e( 'Edit', 'kt12words-content' ); ?>
										</a>
										<form action="<?php echo esc_url_raw( admin_url( 'admin-post.php' ) ); ?>" method="post" accept-charset="utf-8" class="d-inline-block">
											<?php wp_nonce_field( 'delete_kt12word_nonce', '__delete_nonce' ); ?>
											<input type="hidden" name="action" value="kt12word-delete-word">
											<input type="hidden" name="kt12word_word_id" value="<?php echo esc_attr( $row->id ); ?>">
											<input type="submit" class="btn btn-sm btn-danger" value="<?php esc_html_e( 'Delete', 'kt12words-content' ); ?>">
										</form>
								</td>

							</tr>
							<?php endforeach; ?>
						<?php if ( ! $results ) : ?>
							<tr><td colspan="5"><?php esc_html_e( 'No word added yet', 'kt12words-content' ); ?></td></tr>
						<?php endif; ?>
						</tbody>
					</thead>
				</table>
			</div>
		</div>
	</div>
<kt12word-sidebar v-show="showSidebar">
	<template slot="footer"></template>
</kt12word-sidebar>
</div>
