<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link  https://wordpress.org/plugins/genealogical-tree
 * @since 1.0.0
 *
 * @package    Genealogical_Tree
 * @subpackage Genealogical_Tree/admin
 */

$current_tab = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : 'merge-request';

$gt_coll_tabs = array(
	array(
		'id'    => 'merge-request',
		'title' => __( 'Merge Request', 'genealogical-tree' ),
	),
	array(
		'id'    => 'use-request',
		'title' => __( 'Use Request', 'genealogical-tree' ),
	),
	array(
		'id'    => 'suggestions',
		'title' => __( 'Suggestions', 'genealogical-tree' ),
	),
);
?>
<div class="wrap">
	<h1> <?php esc_html_e( 'Collaboration', 'genealogical-tree' ); ?> </h1>
	<hr class="wp-header-end">
	<h2 class="nav-tab-wrapper">
		<?php
		foreach ( $gt_coll_tabs as $key => $gt_coll_tab ) {
			?>
			<a href="admin.php?page=genealogical-collaboration&tab=<?php echo esc_attr( $gt_coll_tab['id'] ); ?>" class="nav-tab <?php echo esc_attr( ( $current_tab === $gt_coll_tab['id'] ) ? 'nav-tab-active' : '' ); ?>">
			<?php echo esc_html( $gt_coll_tab['title'] ); ?>
			</a>
			<?php
		}
		?>
	</h2>
	<div class="wp-clearfix"></div>
	<?php

	if ( 'merge-request' === $current_tab ) {

		if ( isset( $_POST['merge_submit'], $_POST['merge_submit_nonce'] ) && wp_verify_nonce( sanitize_key( $_POST['merge_submit_nonce'] ), 'merge_submit_action' ) ) {
			$members_to_update = array();

			// This member will be merged. this member created by different user.
			// MEMBER WILL BE INACTIVE AND WILL NOT BE USEABLE.
			$merge_member_id = isset( $_POST['merge_member_id'] ) ? sanitize_text_field( wp_unslash( $_POST['merge_member_id'] ) ) : null;

			array_push( $members_to_update, $merge_member_id );

			// This user who request to merge, want to merge with above member.
			$merge_member_author = isset( $_POST['merge_member_author'] ) ? sanitize_text_field( wp_unslash( $_POST['merge_member_author'] ) ) : null;

			// This is te member where above member will be merge.
			// MEMBER WILL BE USABLE TO OTHER USER.
			$member_id = isset( $_POST['member_id'] ) ? sanitize_text_field( wp_unslash( $_POST['member_id'] ) ) : null;

			array_push( $members_to_update, $member_id );

			// Allow user / author to use the member to make connection. Note: above user is not auther of this member, but now above user
			// will able to use this member to his / her family tree.

			// check already allowed user.
			$can_use = get_post_meta( $member_id, 'can_use' ) ? get_post_meta( $member_id, 'can_use' ) : array();
			// put new user.
			array_push( $can_use, $merge_member_author );
			// make unique.
			$can_use = array_unique( $can_use );
			// delete old data.
			delete_post_meta( $member_id, 'can_use' );
			// add new data.
			foreach ( $can_use as $key => $value ) {
				add_post_meta( $member_id, 'can_use', $value );
			}

			// store data to member which user is merged with this member. This data will not be use. just a record.
			// chck current data.
			$merged_with = get_post_meta( $member_id, 'merged_with' ) ? get_post_meta( $member_id, 'merged_with' ) : array();
			// put new data.
			array_push( $merged_with, $merge_member_id );
			// make unique.
			$merged_with = array_unique( $merged_with );
			// delete old data.
			delete_post_meta( $member_id, 'merged_with' );
			// add new data.
			foreach ( $merged_with as $key => $value ) {
				add_post_meta( $member_id, 'merged_with', $value );
			}

			// store data to member which user is merged with this member. This data will not be use. just a record.
			// chck current data.
			$merged_to = get_post_meta( $merge_member_id, 'merged_to' ) ? get_post_meta( $merge_member_id, 'merged_to' ) : array();
			// put new data.
			array_push( $merged_to, $member_id );
			// make unique.
			$merged_to = array_unique( $merged_to );
			// delete old data.
			delete_post_meta( $merge_member_id, 'merged_to' );
			// add new data.
			foreach ( $merged_to as $key => $value ) {
				add_post_meta( $merge_member_id, 'merged_to', $value );
			}

			$query = new \WP_Query(
				array(
					'post_type'      => 'gt-family',
					'posts_per_page' => -1,
					'meta_query'     => array(
						'relation' => 'OR',
						array(
							'key'     => 'husb',
							'value'   => $merge_member_id,
							'compare' => '=',
						),
						array(
							'key'     => 'wife',
							'value'   => $merge_member_id,
							'compare' => '=',
						),
						array(
							'key'     => 'chil',
							'value'   => $merge_member_id,
							'compare' => 'IN',
						),
					),
				)
			);

			if ( $query->posts ) {
				foreach ( $query->posts as $key => $fam ) {

					if ( get_post_meta( $fam->ID, 'husb', true ) === $merge_member_id ) {
						delete_post_meta( $fam->ID, 'husb', $merge_member_id );
						add_post_meta( $fam->ID, 'husb', $member_id );
					}
					if ( get_post_meta( $fam->ID, 'wife', true ) === $merge_member_id ) {
						delete_post_meta( $fam->ID, 'wife', $merge_member_id );
						add_post_meta( $fam->ID, 'wife', $member_id );
					}
					if ( in_array( $merge_member_id, get_post_meta( $fam->ID, 'chil' ), true ) ) {
						delete_post_meta( $fam->ID, 'chil', $merge_member_id );
						add_post_meta( $fam->ID, 'chil', $member_id );
					}
				}
			}

			delete_post_meta( $merge_member_id, 'fams' );
			delete_post_meta( $merge_member_id, 'famc' );
		}

		$query = new \WP_Query(
			array(
				'post_type'      => 'gt-member',
				'posts_per_page' => -1,
				'author'         => get_current_user_id(),
				'meta_query'     => array(
					array(
						'key'     => 'merge_request',
						'compare' => 'EXISTS',
					),
				),
			)
		);
		?>
		<br>
		<table class="wp-list-table widefat striped table-view-list posts">
			<tr>
				<th> <?php esc_html_e( 'Member', 'genealogical-tree' ); ?></th>
				<th> <?php esc_html_e( 'Merge', 'genealogical-tree' ); ?></th>
			</tr>
			<?php
			if ( $query->posts ) {
				foreach ( $query->posts as $key => $member ) {
					$member_id     = $member->ID;
					$member_author = get_post_field( 'post_author', $member_id );
					$merge_request = get_post_meta( $member_id, 'merge_request' ); // make inactive.
					$merged_with   = get_post_meta( $member_id, 'merged_with' ) ? get_post_meta( $member_id, 'merged_with' ) : array();
					?>
					<tr>
						<td width="160">
							<p> <?php echo esc_html( get_post( $member_id )->post_title ); ?>
								<!-- ( By : <?php echo esc_html( get_the_author_meta( 'user_email', $member_author ) ); ?> )-->
							</p>
						</td>
						<td>
							<?php
							foreach ( $merge_request as $key => $merge_member_id ) {
								$merge_member_author = get_post_field( 'post_author', $merge_member_id );
								?>
								<p> <strong> </strong> <?php echo esc_html( get_post( $merge_member_id )->post_title ); ?> ( By : <?php echo esc_html( get_the_author_meta( 'user_login', $merge_member_author ) ); ?> <?php echo esc_html( get_the_author_meta( 'user_email', $merge_member_author ) ); ?> ) </p>
								<?php
								if ( in_array( $merge_member_id, $merged_with, true ) ) {
									?>
									<span style="color:#198754;"> <?php esc_html_e( 'Merged.', 'genealogical-tree' ); ?></span>
									<?php
								} else {
									?>
									<form method="post" action>
										<?php wp_nonce_field( 'merge_submit_action', 'merge_submit_nonce' ); ?>
										<input type="hidden" name="member_id" value="<?php echo esc_attr( $member_id ); ?>">
										<input type="hidden" name="merge_member_id" value="<?php echo esc_attr( $merge_member_id ); ?>">
										<input type="hidden" name="merge_member_author" value="<?php echo esc_attr( $merge_member_author ); ?>">
										<button type="submit" name="merge_submit"><?php esc_html_e( 'Merge', 'genealogical-tree' ); ?></button>
									</form>
									<?php
								}
							}
							?>
						</td>
					</tr>
					<?php
				}
			} else {
				?>
				<tr>
					<td>
						<p style="padding: .5em;"><?php esc_html_e( 'No merge request found.', 'genealogical-tree' ); ?> </p>
					</td>
				</tr>
				<?php
			}
			?>
		</table>
		<?php
	}

	if ( 'use-request' === $current_tab ) {
		?>
		<br>
		<table class="wp-list-table widefat striped table-view-list posts">
			<tr>
				<th> <?php esc_html_e( 'Member', 'genealogical-tree' ); ?> </th>
				<th> <?php esc_html_e( 'User', 'genealogical-tree' ); ?> </th>
			</tr>
			<?php
			if ( isset( $_POST['allow_use_submit'], $_POST['allow_use_submit_nonce'] ) && wp_verify_nonce( sanitize_key( $_POST['allow_use_submit_nonce'] ), 'allow_use_submit_action' ) ) {

				$member_id = sanitize_text_field( wp_unslash( $_POST['member_id'] ) );

				$requested_id_author = isset( $_POST['requested_id_author'] ) ? sanitize_text_field( wp_unslash( $_POST['requested_id_author'] ) ) : null;

				// check already allowed user.
				$can_use = get_post_meta( $member_id, 'can_use' ) ? get_post_meta( $member_id, 'can_use' ) : array();
				// put new user.
				array_push( $can_use, $requested_id_author );
				// make unique.
				$can_use = array_unique( $can_use );
				// delete old data.
				delete_post_meta( $member_id, 'can_use' );
				// add new data.
				foreach ( $can_use as $key => $value ) {
					add_post_meta( $member_id, 'can_use', $value );
				}
			}

			$query = new \WP_Query(
				array(
					'post_type'      => 'gt-member',
					'posts_per_page' => -1,
					'author'         => get_current_user_id(),
					'meta_query'     => array(
						'relation' => 'OR',
						array(
							'key'     => 'use_with',
							'compare' => 'EXISTS',
						),
						array(
							'key'     => 'use_request',
							'compare' => 'EXISTS',
						),
					),
				)
			);

			if ( $query->posts ) {
				foreach ( $query->posts as $key => $member ) {
					$member_id           = $member->ID;
					$member_id_author    = get_post_field( 'post_author', $member_id );
					$requested_id_author = get_post_meta( $member->ID, 'use_request' );
					$can_use             = get_post_meta( $member_id, 'can_use' ) ? get_post_meta( $member_id, 'can_use' ) : array();
					?>
					<tr>
						<td width="160">
							<div>
								<p> <?php echo esc_html( $member->post_title ); ?> </p>
							</div>
						</td>
						<td>
							<div>
								<?php
								foreach ( $requested_id_author as $key => $value ) {
									?>
									<p> <?php echo esc_html( get_the_author_meta( 'user_login', $value ) ); ?> ( <?php echo esc_html( get_the_author_meta( 'user_email', $value ) ); ?> ) </p>
									<?php
									if ( in_array( $value, $can_use, true ) ) {
										?>
										<?php esc_html_e( 'Already Can Use.', 'genealogical-tree' ); ?>
										<?php
									} else {
										?>
										<form method="post" action>
											<?php wp_nonce_field( 'allow_use_submit_action', 'allow_use_submit_nonce' ); ?>
											<input type="hidden" name="member_id" value="<?php echo esc_attr( $member_id ); ?>">
											<input type="hidden" name="requested_id_author" value="<?php echo esc_attr( $value ); ?>">
											<button type="submit" name="allow_use_submit">Allow Use</button>
										</form>
										<?php
									}
								}
								?>
							</div>
						</td>
					</tr>
					<?php
				}
			} else {
				?>
				<tr>
					<td>
						<p style="padding: .5em;"><?php esc_html_e( 'No Use request found.', 'genealogical-tree' ); ?> </p>
					</td>
				</tr>
				<?php
			}
			?>
		</table>
		<?php
	}

	if ( 'suggestions' === $current_tab ) {
		?>
		<br>
		<table class="wp-list-table widefat striped table-view-list posts">
			<tr>
				<th> <?php esc_html_e( 'Member', 'genealogical-tree' ); ?> </th>
				<th> <?php esc_html_e( 'User / Message ', 'genealogical-tree' ); ?> </th>
			</tr>
			<?php
			$query = new \WP_Query(
				array(
					'post_type'      => 'gt-member',
					'posts_per_page' => -1,
					'author'         => get_current_user_id(),
					'meta_query'     => array(
						array(
							'key'     => 'suggestions',
							'compare' => 'EXISTS',
						),
					),
				)
			);
			foreach ( $query->posts as $key => $member ) {
				$member_id   = $member->ID;
				$suggestions = get_post_meta( $member_id, 'suggestions' ); // make inactive.
				?>
				<tr>
					<td width="160">
						<div>
							<p> <strong> <?php esc_html_e( 'To :', 'genealogical-tree' ); ?> </strong> <?php echo esc_html( $member->post_title ); ?> </p>
						</div>
					</td>
					<td>
						<div>
							<?php foreach ( $suggestions as $key => $value ) { ?>
								<p> <strong><?php esc_html_e( 'User:', 'genealogical-tree' ); ?></strong> <?php echo esc_html( get_the_author_meta( 'user_email', $value['sent_by'] ) ); ?> </p>
								<p> <strong><?php esc_html_e( 'Message:', 'genealogical-tree' ); ?></strong> <?php echo esc_html( $value['message'] ); ?> </p>
							<?php } ?>
						</div>
					</td>
				</tr>
				<?php
			}
			?>
		</table>
		<?php
	}
	?>
</div>
<?php
