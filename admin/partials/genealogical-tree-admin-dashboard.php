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

?>
<style>
	.gt-dashbord,
	.gt-dashbord * {
		box-sizing: border-box;
	}

	.gt-dashbord {
		padding: 2.5rem;
		background-color: rgb(255 255 255 / 1);
		margin-top: 1.5rem;
		width: 100%;
		margin-right: auto;
		margin-left: auto;
		max-width: 1536px;
	}

	.gt-dashbord--nav {
		display: flex;
		justify-content: space-between;
		align-items: center;
		align-content: center;
		flex-direction: row;
	}

	.gt-dashbord--nav>div {
		display: flex;
		align-items: center;
		align-content: center;
		width: 33.3333333%;
		justify-content: center;
	}

	.gt-dashbord--nav>div:first-child {
		justify-content: flex-start;
	}

	.gt-dashbord--nav>div:last-child {
		justify-content: flex-end;
	}

	.gt-dashbord--nav a {
		display: flex;
		align-items: center;
		align-content: center;
	}

	.gt-dashbord--nav a.gt-logo {
		color: rgb(86 64 52);
		font-weight: 600;
		font-size: 1.125rem;
		line-height: 1.75rem;
		white-space: nowrap;
		align-self: center;
		text-decoration: none !important;
	}

	.gt-dashbord--nav a.gt-logo img {
		margin-right: 10px;
	}

	.gt-dashbord--nav a.gt-sign-in-up {
		background: rgb(86 64 52);
		text-decoration: none !important;
		font-size: .875rem;
		line-height: 1.25rem;
		font-weight: 500;
		padding-left: 1.25rem;
		padding-right: 1.25rem;
		padding-top: 0.625rem;
		padding-bottom: 0.625rem;
		border-radius: 0.5rem;
		color: rgb(255 255 255);
	}

	.gt-dashbord--nav ul {
		display: flex;
		flex-direction: row;
		align-content: center;
		align-items: center;
		flex-wrap: nowrap;
	}

	.gt-dashbord--nav ul li {
		margin-bottom: 0;
	}

	.gt-dashbord--nav ul a {
		display: inline-block;
		padding: 5px 10px;
		font-weight: 700;
		color: rgb(55 65 81);
		text-decoration: none;
		outline: none;
		box-shadow: none !important;
	}

	.gt-dashbord--nav ul a:hover {
		color: rgb(86 64 52);
	}

	.gt-dashbord--content {}

	.gt-dashbord--content {
		padding-top: 60px;
		;
	}

	.gt-dashbord--content h3,
	.gt-dashbord--content h4 {
		text-align: center;
	}

	.gt-dashbord--content h3 {
		font-size: 1.875rem;
		line-height: 2.25rem;
	}

	.gt-dashbord--content h4 {
		font-size: 1rem;
		line-height: 1.5rem;
		margin-bottom: 0.25rem;
		color: rgb(55 65 81);
		font-weight: 400;
	}

	.gt-dashbord--content .gt-content {
		margin-top: 50px;
		display: flex;
		flex-wrap: wrap;
	}

	.gt-dashbord--content .gt-content>div {
		width: 33.3333333%;
		padding: 1rem;
	}

	.gt-dashbord--content .gt-content>div.gt-flex-break {
		flex-break: before;
	}

	.gt-feature--item {
		background-color: rgb(243 244 246 / 1);
		padding: 2rem;
		border-radius: 0.5rem;
		height: 100%;
	}

	.gt-feature--item__header {
		display: flex;
		align-items: center;
		align-content: center;
	}

	.gt-feature--item__header img {
		margin-right: 15px;
		/*display: none;*/
	}

	.gt-feature--item__header h4 {
		font-weight: 500;
		font-size: 1.125rem;
		line-height: 1.75rem;
		margin: 0;
		text-transform: capitalize;
		text-align: left;
	}

	.gt-feature--item p {
		line-height: 1.625;
		font-size: 1rem;
		margin: 1em 0;
	}

	.gt-limit {
		font-weight: 500;
		color: red;
	}

	[data-item="gt-pro"] {
		/*display: none;*/
	}

	.gt-dashbord--nav ul a.gt-hilight-link {
		color: rgb(101 132 94)
	}
</style>
<div class="wrap">
	<div class="gt-dashbord">
		<div class="gt-dashbord--nav">
			<div>
				<a class="gt-logo" href="https://www.devs.family/genealogical-tree/">
					<img height="30" src="<?php echo esc_attr( GENEALOGICAL_TREE_DIR_URL ); ?>admin/img/menu-icon.png">
					<span>Genealogical Tree</span>
				</a>
			</div>
			<div>
				<nav>
					<ul class="gt-feature-filter">
						<li>
							<a class="gt-hilight-link" href="#" data-type="free">Features</a>
						</li>
						<li>
							<a href="#" data-type="pro">Pro Features</a>
						</li>
					</ul>
				</nav>
			</div>
			<div>
				<a class="gt-sign-in-up" href="https://www.devs.family/genealogical-tree/help/forum/">Sign In / Up</a>
			</div>
		</div>
		<div class="gt-dashbord--content">
			<div id="gt-free">
				<div class="gt-content--header">
					<h4>Genealogical Tree</h4>
					<h3>Impressive Features</h3>
				</div>
				<div class="gt-content">
					<div data-item="gt-free">
						<div class="gt-feature--item">
							<div class="gt-feature--item__header">
								<img height="40" src="<?php echo esc_attr( plugin_dir_url( dirname( __FILE__ ) ) ); ?>img/dash/member.png">
								<h4>Member Profile</h4>
							</div>
							<p>You can see each family member details on there single page with <span class="gt-limit">limited</span> information for free, Also you can display member / members in any page / post through shortcode. .</p>
						</div>
					</div>
					<div data-item="gt-free">
						<div class="gt-feature--item">
							<div class="gt-feature--item__header">
								<img height="40" src="<?php echo esc_attr( plugin_dir_url( dirname( __FILE__ ) ) ); ?>img/dash/members.png">
								<h4>Member Archive</h4>
							</div>
							<p>You can see all family members into archive page according genealogical tree design, Also you can display members on custom page through short code.</p>
						</div>
					</div>
					<div data-item="gt-free">
						<div class="gt-feature--item">
							<div class="gt-feature--item__header">
								<img height="40" src="<?php echo esc_attr( plugin_dir_url( dirname( __FILE__ ) ) ); ?>img/dash/birth.png">
								<h4>Birth Record</h4>
							</div>
							<p>Genealogical Tree allow user to record birth and death record according reference.</p>
						</div>
					</div>
					<div data-item="gt-free">
						<div class="gt-feature--item">
							<div class="gt-feature--item__header">
								<img height="40" src="<?php echo esc_attr( plugin_dir_url( dirname( __FILE__ ) ) ); ?>img/dash/death.png">
								<h4>Death Record</h4>
							</div>
							<p>Genealogical Tree allow user to record birth and death record according reference.</p>
						</div>
					</div>
					<div data-item="gt-pro">
						<div class="gt-feature--item">
							<div class="gt-feature--item__header">
								<img height="40" src="<?php echo esc_attr( plugin_dir_url( dirname( __FILE__ ) ) ); ?>img/dash/spouse.png">
								<h4>Multiple Spouse</h4>
							</div>
							<p>You can add multiple spouse for any member. Also you can display children separately for each spouse.</p>
						</div>
					</div>
					<div data-item="gt-pro">
						<div class="gt-feature--item">
							<div class="gt-feature--item__header">
								<img height="40" src="<?php echo esc_attr( plugin_dir_url( dirname( __FILE__ ) ) ); ?>img/dash/parents.png">
								<h4>Multiple Parents</h4>
							</div>
							<p>You can add multiple parents for any member. Like some member can have biological parent as well as addopted parents.</p>
						</div>
					</div>
					<div data-item="gt-pro">
						<div class="gt-feature--item">
							<div class="gt-feature--item__header">
								<img height="40" src="<?php echo esc_attr( plugin_dir_url( dirname( __FILE__ ) ) ); ?>img/dash/events.png">
								<h4>Events</h4>
							</div>
							<p>You will able to add life event of each member in member edit page. You will able to add multiple life event</p>
						</div>
					</div>
					<div data-item="gt-pro">
						<div class="gt-feature--item">
							<div class="gt-feature--item__header">
								<img height="40" src="<?php echo esc_attr( plugin_dir_url( dirname( __FILE__ ) ) ); ?>img/dash/contact.png">
								<h4>Multiple contact information</h4>
							</div>
							<p>Option to add multiple contact information.</p>
						</div>
					</div>
					<div data-item="gt-pro">
						<div class="gt-feature--item">
							<div class="gt-feature--item__header">
								<img height="40" src="<?php echo esc_attr( plugin_dir_url( dirname( __FILE__ ) ) ); ?>img/dash/gallery.png">
								<h4>Member Gallery</h4>
							</div>
							<p>Showing member image into tree.</p>
						</div>
					</div>
					<div data-item="gt-pro">
						<div class="gt-feature--item">
							<div class="gt-feature--item__header">
								<img height="40" src="<?php echo esc_attr( plugin_dir_url( dirname( __FILE__ ) ) ); ?>img/dash/image.png">
								<h4>Member Image</h4>
							</div>
							<p>Showing member image into member page.</p>
						</div>
					</div>
					<div data-item="gt-pro">
						<div class="gt-feature--item">
							<div class="gt-feature--item__header">
								<img height="40" src="<?php echo esc_attr( plugin_dir_url( dirname( __FILE__ ) ) ); ?>img/dash/pop.png">
								<h4>Popup Member Profile</h4>
							</div>
							<p>We have support popup member profile support, If it enable, popup member profile will be appear on click member link,</p>
						</div>
					</div>
				</div>
				<div class="gt-content">
					<div data-item="gt-free">
						<div class="gt-feature--item">
							<div class="gt-feature--item__header">
								<img height="40" src="<?php echo esc_attr( plugin_dir_url( dirname( __FILE__ ) ) ); ?>img/dash/groups.png">
								<h4>Family Group</h4>
							</div>
							<p>Genealogical Tree help user to display family tree on WordPress site / blog. You will <span class="gt-limit">limit</span> to create more than 1 family tree for free.</p>
						</div>
					</div>
					<div data-item="gt-pro">
						<div class="gt-feature--item">
							<div class="gt-feature--item__header">
								<img height="40" src="<?php echo esc_attr( plugin_dir_url( dirname( __FILE__ ) ) ); ?>img/dash/1.png">
								<h4>unlimited family groups</h4>
							</div>
							<p>Create unlimited family groups.</p>
						</div>
					</div>
				</div>
				<div class="gt-content">
					<div class="gt-flex-break" data-item="gt-free">
						<div class="gt-feature--item">
							<div class="gt-feature--item__header">
								<img height="40" src="<?php echo esc_attr( plugin_dir_url( dirname( __FILE__ ) ) ); ?>img/dash/family.png">
								<h4>Family Details</h4>
							</div>
							<p>Genealogical Tree help user to display family tree on WordPress site / blog. You will <span class="gt-limit">limit</span> to create more than 1 family tree for free.</p>
						</div>
					</div>
					<div data-item="gt-free">
						<div class="gt-feature--item">
							<div class="gt-feature--item__header">
								<img height="40" src="<?php echo esc_attr( plugin_dir_url( dirname( __FILE__ ) ) ); ?>img/dash/families.png">
								<h4>Family Archive</h4>
							</div>
							<p>Genealogical Tree help user to display family tree on WordPress site / blog. You will <span class="gt-limit">limit</span> to create more than 1 family tree for free.</p>
						</div>
					</div>
				</div>
				<div class="gt-content">
					<div data-item="gt-free">
						<div class="gt-feature--item">
							<div class="gt-feature--item__header">
								<img height="40" src="<?php echo esc_attr( plugin_dir_url( dirname( __FILE__ ) ) ); ?>img/dash/tree.png">
								<h4>Family Tree</h4>
							</div>
							<p>Genealogical Tree help user to display family tree on WordPress site / blog. You will <span class="gt-limit">limit</span> to create more than 1 family tree for free.</p>
						</div>
					</div>
					<div data-item="gt-pro">
						<div class="gt-feature--item">
							<div class="gt-feature--item__header">
								<img height="40" src="<?php echo esc_attr( plugin_dir_url( dirname( __FILE__ ) ) ); ?>img/dash/1.png">
								<h4>Unlimited family trees</h4>
							</div>
							<p>Create unlimited family trees.</p>
						</div>
					</div>
					<div data-item="gt-pro">
						<div class="gt-feature--item">
							<div class="gt-feature--item__header">
								<img height="40" src="<?php echo esc_attr( plugin_dir_url( dirname( __FILE__ ) ) ); ?>img/dash/1.png">
								<h4>Multiple Tree Style</h4>
							</div>
							<p>We have 7 tree style, which is fully customizable in different way.</p>
						</div>
					</div>
					<div data-item="gt-pro">
						<div class="gt-feature--item">
							<div class="gt-feature--item__header">
								<img height="40" src="<?php echo esc_attr( plugin_dir_url( dirname( __FILE__ ) ) ); ?>img/dash/1.png">
								<h4>Multiple Tree Layout</h4>
							</div>
							<p>On each tree tree style we have 2 layout, vertical and horizontal</p>
						</div>
					</div>
					<div data-item="gt-pro">
						<div class="gt-feature--item">
							<div class="gt-feature--item__header">
								<img height="40" src="<?php echo esc_attr( plugin_dir_url( dirname( __FILE__ ) ) ); ?>img/dash/1.png">
								<h4>Enable Ajax</h4>
							</div>
							<p>In this plugin we have ajax support. were tree build on ajax, Its handy for big tree.</p>
						</div>
					</div>
					<div data-item="gt-pro">
						<div class="gt-feature--item">
							<div class="gt-feature--item__header">
								<img height="40" src="<?php echo esc_attr( plugin_dir_url( dirname( __FILE__ ) ) ); ?>img/dash/1.png">
								<h4>Show Ancestor</h4>
							</div>
							<p>You can show ancestor for root in family tree, its up to one level.</p>
						</div>
					</div>
					<div data-item="gt-pro">
						<div class="gt-feature--item">
							<div class="gt-feature--item__header">
								<img height="40" src="<?php echo esc_attr( plugin_dir_url( dirname( __FILE__ ) ) ); ?>img/dash/1.png">
								<h4>Hide Female Tree</h4>
							</div>
							<p>You can hide part of a tree which build from a female member, Its useful when you do not want to show others family on your groups.</p>
						</div>
					</div>
					<div data-item="gt-pro">
						<div class="gt-feature--item">
							<div class="gt-feature--item__header">
								<img height="40" src="<?php echo esc_attr( plugin_dir_url( dirname( __FILE__ ) ) ); ?>img/dash/1.png">
								<h4>Hide Unknown Spouse</h4>
							</div>
							<p>Some member may have children’s with unknown spouse, There is way to hide that unknown spouse,</p>
						</div>
					</div>
					<div data-item="gt-pro">
						<div class="gt-feature--item">
							<div class="gt-feature--item__header">
								<img height="40" src="<?php echo esc_attr( plugin_dir_url( dirname( __FILE__ ) ) ); ?>img/dash/1.png">
								<h4>Collapsible Family</h4>
							</div>
							<p>Here is a facilities of collapsible family.</p>
						</div>
					</div>
					<div data-item="gt-pro">
						<div class="gt-feature--item">
							<div class="gt-feature--item__header">
								<img height="40" src="<?php echo esc_attr( plugin_dir_url( dirname( __FILE__ ) ) ); ?>img/dash/1.png">
								<h4>Sibling Order</h4>
							</div>
							<p>You can control sibling order as youngest or as oldest.</p>
						</div>
					</div>
					<div data-item="gt-pro">
						<div class="gt-feature--item">
							<div class="gt-feature--item__header">
								<img height="40" src="<?php echo esc_attr( plugin_dir_url( dirname( __FILE__ ) ) ); ?>img/dash/1.png">
								<h4>Show Generation</h4>
							</div>
							<p>You can display generation on tree for each member generation.</p>
						</div>
					</div>
					<div data-item="gt-free">
						<div class="gt-feature--item">
							<div class="gt-feature--item__header">
								<img height="40" src="<?php echo esc_attr( plugin_dir_url( dirname( __FILE__ ) ) ); ?>img/dash/1.png">
								<h4>Show Tree Link</h4>
							</div>
							<p>You can show or hide tree link of each member specific on tree.</p>
						</div>
					</div>
					<div data-item="gt-free">
						<div class="gt-feature--item">
							<div class="gt-feature--item__header">
								<img height="40" src="<?php echo esc_attr( plugin_dir_url( dirname( __FILE__ ) ) ); ?>img/dash/1.png">
								<h4>Container Setting</h4>
							</div>
							<p>This plugin will allow you to control style settings of container,</p>
						</div>
					</div>
					<div data-item="gt-free">
						<div class="gt-feature--item">
							<div class="gt-feature--item__header">
								<img height="40" src="<?php echo esc_attr( plugin_dir_url( dirname( __FILE__ ) ) ); ?>img/dash/1.png">
								<h4>Box Setting</h4>
							</div>
							<p>This plugin will allow you to control style settings of box,</p>
						</div>
					</div>
					<div data-item="gt-free">
						<div class="gt-feature--item">
							<div class="gt-feature--item__header">
								<img height="40" src="<?php echo esc_attr( plugin_dir_url( dirname( __FILE__ ) ) ); ?>img/dash/1.png">
								<h4>Box Layout</h4>
							</div>
							<p>We have two type o box layout, vertical and horizontal view of image and info.</p>
						</div>
					</div>
					<div data-item="gt-pro">
						<div class="gt-feature--item">
							<div class="gt-feature--item__header">
								<img height="40" src="<?php echo esc_attr( plugin_dir_url( dirname( __FILE__ ) ) ); ?>img/dash/1.png">
								<h4>Image Setting</h4>
							</div>
							<p>You can hide part of a tree which build from a female member, Its useful when you do not want to show others family on your groups.</p>
						</div>
					</div>
					<div data-item="gt-pro">
						<div class="gt-feature--item">
							<div class="gt-feature--item__header">
								<img height="40" src="<?php echo esc_attr( plugin_dir_url( dirname( __FILE__ ) ) ); ?>img/dash/1.png">
								<h4>Google Font</h4>
							</div>
							<p>You can use any google fonts for tree styling.</p>
						</div>
					</div>
					<div data-item="gt-pro">
						<div class="gt-feature--item">
							<div class="gt-feature--item__header">
								<img height="40" src="<?php echo esc_attr( plugin_dir_url( dirname( __FILE__ ) ) ); ?>img/dash/1.png">
								<h4>6 Tree layout</h4>
							</div>
							<p>Extra 6 Tree layout with many customizable options</p>
						</div>
					</div>
					<div data-item="gt-pro">
						<div class="gt-feature--item">
							<div class="gt-feature--item__header">
								<img height="40" src="<?php echo esc_attr( plugin_dir_url( dirname( __FILE__ ) ) ); ?>img/dash/1.png">
								<h4>Horizontal and Vertical Tree</h4>
							</div>
							<p>Horizontal and Vertical view of each layout.</p>
						</div>
					</div>
					<div data-item="gt-pro">
						<div class="gt-feature--item">
							<div class="gt-feature--item__header">
								<img height="40" src="<?php echo esc_attr( plugin_dir_url( dirname( __FILE__ ) ) ); ?>img/dash/1.png">
								<h4>Multiple birth and death recored</h4>
							</div>
							<p>Support multiple birth and death recored with different source.</p>
						</div>
					</div>
					<div data-item="gt-pro">
						<div class="gt-feature--item">
							<div class="gt-feature--item__header">
								<img height="40" src="<?php echo esc_attr( plugin_dir_url( dirname( __FILE__ ) ) ); ?>img/dash/1.png">
								<h4>Multiple Parents</h4>
							</div>
							<p>Support multiple parents.</p>
						</div>
					</div>
					<div data-item="gt-pro">
						<div class="gt-feature--item">
							<div class="gt-feature--item__header">
								<img height="40" src="<?php echo esc_attr( plugin_dir_url( dirname( __FILE__ ) ) ); ?>img/dash/1.png">
								<h4>Children By Spouse</h4>
							</div>
							<p>Display children separately by each spouse.</p>
						</div>
					</div>
					<div data-item="gt-pro">
						<div class="gt-feature--item">
							<div class="gt-feature--item__header">
								<img height="40" src="<?php echo esc_attr( plugin_dir_url( dirname( __FILE__ ) ) ); ?>img/dash/1.png">
								<h4>Sibling Order</h4>
							</div>
							<p>Manage Sibling Order to display on tree.</p>
						</div>
					</div>
					<div data-item="gt-pro">
						<div class="gt-feature--item">
							<div class="gt-feature--item__header">
								<img height="40" src="<?php echo esc_attr( plugin_dir_url( dirname( __FILE__ ) ) ); ?>img/dash/1.png">
								<h4>Tree Styleing </h4>
							</div>
							<p>Manage tree style including color, border etc.</p>
						</div>
					</div>
					<div data-item="gt-pro">
						<div class="gt-feature--item">
							<div class="gt-feature--item__header">
								<img height="40" src="<?php echo esc_attr( plugin_dir_url( dirname( __FILE__ ) ) ); ?>img/dash/1.png">
								<h4>Collapsible Tree</h4>
							</div>
							<p>Collapsible features on tree.</p>
						</div>
					</div>
					<div data-item="gt-free">
						<div class="gt-feature--item">
							<div class="gt-feature--item__header">
								<img height="40" src="<?php echo esc_attr( plugin_dir_url( dirname( __FILE__ ) ) ); ?>img/dash/1.png">
								<h4>Custom Root</h4>
							</div>
							<p>You can set custom root in a family group, where that you may have different actual root of that family group.</p>
						</div>
					</div>
				</div>
				<div class="gt-content">
					<div data-item="gt-free">
						<div class="gt-feature--item">
							<div class="gt-feature--item__header">
								<img height="40" src="<?php echo esc_attr( plugin_dir_url( dirname( __FILE__ ) ) ); ?>img/dash/7.png">
								<h4>Custom Role</h4>
							</div>
							<p>In this plugin have a custom user role to manage, which role allow user to manage this plugin on author level.</p>
						</div>
					</div>

					<div data-item="gt-pro">
						<div class="gt-feature--item">
							<div class="gt-feature--item__header">
								<img height="40" src="<?php echo esc_attr( plugin_dir_url( dirname( __FILE__ ) ) ); ?>img/dash/1.png">
								<h4>Import Gedcom</h4>
							</div>
							<p>Import Gedcom format (.ged) files.</p>
						</div>
					</div>
					<div data-item="gt-pro">
						<div class="gt-feature--item">
							<div class="gt-feature--item__header">
								<img height="40" src="<?php echo esc_attr( plugin_dir_url( dirname( __FILE__ ) ) ); ?>img/dash/1.png">
								<h4>Export Gedcom</h4>
							</div>
							<p>Export Gedcom format (.ged) files.</p>
						</div>
					</div>
					<div data-item="gt-pro">
						<div class="gt-feature--item">
							<div class="gt-feature--item__header">
								<img height="40" src="<?php echo esc_attr( plugin_dir_url( dirname( __FILE__ ) ) ); ?>img/dash/1.png">
								<h4>Collaboration</h4>
							</div>
							<p>Collaboration / Contribution to build family tree / history.</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	(function($) {
		$(document).on('click', '.gt-feature-filter a', function(event) {
			$('.gt-feature-filter a').removeClass('gt-hilight-link');
			$(this).addClass('gt-hilight-link');
			if($(this).data('type') == 'free') {
				$('[data-item="gt-pro"]').hide()
				$('[data-item="gt-free"]').show()
			}
			if($(this).data('type') == 'pro') {
				$('[data-item="gt-pro"]').show()
				$('[data-item="gt-free"]').hide()
			}
			return false;
		})
	})(jQuery)
</script>
