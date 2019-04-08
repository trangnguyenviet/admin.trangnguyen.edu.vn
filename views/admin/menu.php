<?php
/**
 * Created by PhpStorm.
 * User: tanmv
 * Date: 23/04/2017
 * Time: 21:39
 */
?>
<link rel="stylesheet" href="/plugins/bootstrap-treeview/dist/bootstrap-treeview.min.css">
<section class="content-header">
	<h1>
		Quản trị Menu
		<small></small>
	</h1>
</section>
<div ng-app="mainApp" ng-controls="mainController">
	<section id="main-section" class="content">
		<div class="row">
			<div class="col-sm-3">
				<div id="treeview"></div>
				<div class="row">
					<div class="col-sm-12 button text-center">
						<button type="button" ng-click="delete()" class="btn btn-primary">Delete</button>
						<button type="button" ng-click="add()" class="btn btn-primary" data-dismiss="modal">Add</button>
					</div>
				</div>
			</div>
			<div class="col-sm-9">
				<form id="form" role="form" class="form-horizontal" data-toggle="validator" style="border: 1px solid #ddd;padding: 20px 20px 20px 0;">
					<div class="form-group">
						<label for="tb_id" class="col-sm-2 control-label">ID:</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="tb_id" value="0" placeholder="ID" disabled>
						</div>
					</div>
					<div class="form-group">
						<label for="tb_name" class="col-sm-2 control-label">Name: (<span class="text-red">*</span>)</label>
						<div class="col-sm-10">
							<input type="text" autofocus class="form-control" id="tb_name" placeholder="name" data-error="Hãy nhập tên server" required>
						</div>
					</div>
					<div class="form-group">
						<label for="tb_sort" class="col-sm-2 control-label">Sort: (<span class="text-red">*</span>)</label>
						<div class="col-sm-10">
							<input type="number" min="0" class="form-control input-sm" id="tb_sort" value="" placeholder="Thứ tự" required>
						</div>
					</div>
					<div class="form-group">
						<label for="tb_icon" class="col-sm-2 col-xs-12 control-label">Icon:</label>
						<div class="col-sm-8 col-xs-8">
							<input type="text" class="form-control" id="tb_icon" placeholder="css left icon">
						</div>
						<div class="col-sm-2 col-xs-2">
							<button type="button" class="btn btn-icons">...</button>
						</div>
					</div>
					<div class="form-group">
						<label for="cb_active" class="col-sm-2 col-xs-4 control-label">Active:</label>
						<div class="col-sm-10 col-xs-8">
							<input type="checkbox" id="cb_active" checked>
						</div>
					</div>
					<div class="form-group">
						<label for="cb_is_all_access" class="col-sm-2 col-xs-4 control-label">All user access:</label>
						<div class="col-sm-10 col-xs-8">
							<input type="checkbox" id="cb_is_all_access"/>
						</div>
					</div>
					<div class="form-group">
						<label for="cb_is_show" class="col-sm-2 col-xs-4 control-label">Show:</label>
						<div class="col-sm-10 col-xs-8">
							<input type="checkbox" id="cb_is_show" checked/>
						</div>
					</div>
					<div class="form-group">
						<label for="cb_is_tree" class="col-sm-2 col-xs-4 control-label">Tree node:</label>
						<div class="col-sm-10 col-xs-8">
							<input type="checkbox" id="cb_is_tree"> <label for="cb_is_tree" class="control-label">(Menu gốc)</label>
						</div>
					</div>
					<div id="div_root">
						<div class="form-group">
							<label for="ddl_parent" class="col-sm-2 control-label">Parent:</label>
							<div class="col-sm-10">
								<select class="form-control" id="ddl_parent">
									<option value="0">(none)</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="tb_url" class="col-sm-2 control-label">Url: (<span class="text-red">*</span>)</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="tb_url" placeholder="link url">
							</div>
						</div>
						<div style="border: 1px solid #f00; padding: 20px; margin: 20px 0 20px 20px; overflow: hidden;">
							<div id="list_action"></div>
							<div class="row">
								<div class="col-sm-12 text-center text-warning">
									<label>(Action được tích hợp vào code, vì vậy các action phải được coder chỉnh sửa để đảm bảo an toàn hệ thống vận hành)</label>
								</div>
							</div>
							<div class="col-sm-12 text-center">
								<button type="button" tn-action="add-action" id="btn-add-action" class="btn btn-info">Add Action</button>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12 text-center text-red">
							<label class="message"></label>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12 button text-center">
							<button type="submit" tn-action="save" class="btn btn-primary btn-save">Save</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</section>

	<div id="modal-icons" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<form role="form" class="form-horizontal" data-toggle="validator">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title text-info"><strong>Icons</strong></h4>
					</div>
					<div class="modal-body">
						<div class="box-body">
							<div class="row">
								<div class="form-group">
									<label for="ddl_icon_type" class="col-sm-2 control-label">Icon type:</label>
									<div class="col-sm-10">
										<select class="form-control" id="ddl_icon_type">
											<option value="0">...</option>
										</select>
									</div>
								</div>
							</div>
							<div class="row text-center box-item">
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script src="/plugins/bootstrap-treeview/dist/bootstrap-treeview.min.js"></script>
<script>
	var icons = [{
		name: '66 New Icons in 4.4',
		type: 'fa',
		list: ['fa-500px', 'fa-amazon', 'fa-balance-scale', 'fa-battery-0', 'fa-battery-1', 'fa-battery-2', 'fa-battery-3', 'fa-battery-4', 'fa-battery-empty', 'fa-battery-full', 'fa-battery-half', 'fa-battery-quarter', 'fa-battery-three-quarters', 'fa-black-tie', 'fa-calendar-check-o', 'fa-calendar-minus-o', 'fa-calendar-plus-o', 'fa-calendar-times-o', 'fa-cc-diners-club', 'fa-cc-jcb', 'fa-chrome', 'fa-clone', 'fa-commenting', 'fa-commenting-o', 'fa-contao', 'fa-creative-commons', 'fa-expeditedssl', 'fa-firefox', 'fa-fonticons', 'fa-genderless', 'fa-get-pocket', 'fa-gg', 'fa-gg-circle', 'fa-hand-grab-o', 'fa-hand-lizard-o', 'fa-hand-paper-o', 'fa-hand-peace-o', 'fa-hand-pointer-o', 'fa-hand-rock-o', 'fa-hand-scissors-o', 'fa-hand-spock-o', 'fa-hand-stop-o', 'fa-hourglass', 'fa-hourglass-1', 'fa-hourglass-2', 'fa-hourglass-3', 'fa-hourglass-end', 'fa-hourglass-half', 'fa-hourglass-o', 'fa-hourglass-start', 'fa-houzz', 'fa-i-cursor', 'fa-industry', 'fa-internet-explorer', 'fa-map', 'fa-map-o', 'fa-map-pin', 'fa-map-signs', 'fa-mouse-pointer', 'fa-object-group', 'fa-object-ungroup', 'fa-odnoklassniki', 'fa-odnoklassniki-square', 'fa-opencart', 'fa-opera', 'fa-optin-monster', 'fa-registered', 'fa-safari', 'fa-sticky-note', 'fa-sticky-note-o', 'fa-television', 'fa-trademark', 'fa-tripadvisor', 'fa-tv', 'fa-vimeo', 'fa-wikipedia-w', 'fa-y-combinator', 'fa-yc']
	}, {
		name: 'Web Application Icons',
		type: 'fa',
		list: ['fa-adjust', 'fa-anchor', 'fa-archive', 'fa-area-chart', 'fa-arrows', 'fa-arrows-h', 'fa-arrows-v', 'fa-asterisk', 'fa-at', 'fa-automobile', 'fa-balance-scale', 'fa-ban', 'fa-bank', 'fa-bar-chart', 'fa-bar-chart-o', 'fa-barcode', 'fa-bars', 'fa-battery-0', 'fa-battery-1', 'fa-battery-2', 'fa-battery-3', 'fa-battery-4', 'fa-battery-empty', 'fa-battery-full', 'fa-battery-half', 'fa-battery-quarter', 'fa-battery-three-quarters', 'fa-bed', 'fa-beer', 'fa-bell', 'fa-bell-o', 'fa-bell-slash', 'fa-bell-slash-o', 'fa-bicycle', 'fa-binoculars', 'fa-birthday-cake', 'fa-bolt', 'fa-bomb', 'fa-book', 'fa-bookmark', 'fa-bookmark-o', 'fa-briefcase', 'fa-bug', 'fa-building', 'fa-building-o', 'fa-bullhorn', 'fa-bullseye', 'fa-bus', 'fa-cab', 'fa-calculator', 'fa-calendar', 'fa-calendar-check-o', 'fa-calendar-minus-o', 'fa-calendar-o', 'fa-calendar-plus-o', 'fa-calendar-times-o', 'fa-camera', 'fa-camera-retro', 'fa-car', 'fa-caret-square-o-down', 'fa-caret-square-o-left', 'fa-caret-square-o-right', 'fa-caret-square-o-up', 'fa-cart-arrow-down', 'fa-cart-plus', 'fa-cc', 'fa-certificate', 'fa-check', 'fa-check-circle', 'fa-check-circle-o', 'fa-check-square', 'fa-check-square-o', 'fa-child', 'fa-circle', 'fa-circle-o', 'fa-circle-o-notch', 'fa-circle-thin', 'fa-clock-o', 'fa-clone', 'fa-close', 'fa-cloud', 'fa-cloud-download', 'fa-cloud-upload', 'fa-code', 'fa-code-fork', 'fa-coffee', 'fa-cog', 'fa-cogs', 'fa-comment', 'fa-comment-o', 'fa-commenting', 'fa-commenting-o', 'fa-comments', 'fa-comments-o', 'fa-compass', 'fa-copyright', 'fa-creative-commons', 'fa-credit-card', 'fa-crop', 'fa-crosshairs', 'fa-cube', 'fa-cubes', 'fa-cutlery', 'fa-dashboard', 'fa-database', 'fa-desktop', 'fa-diamond', 'fa-dot-circle-o', 'fa-download', 'fa-edit', 'fa-ellipsis-h', 'fa-ellipsis-v', 'fa-envelope', 'fa-envelope-o', 'fa-envelope-square', 'fa-eraser', 'fa-exchange', 'fa-exclamation', 'fa-exclamation-circle', 'fa-exclamation-triangle', 'fa-external-link', 'fa-external-link-square', 'fa-eye', 'fa-eye-slash', 'fa-eyedropper', 'fa-fax', 'fa-feed', 'fa-female', 'fa-fighter-jet', 'fa-file-archive-o', 'fa-file-audio-o', 'fa-file-code-o', 'fa-file-excel-o', 'fa-file-image-o', 'fa-file-movie-o', 'fa-file-pdf-o', 'fa-file-photo-o', 'fa-file-picture-o', 'fa-file-powerpoint-o', 'fa-file-sound-o', 'fa-file-video-o', 'fa-file-word-o', 'fa-file-zip-o', 'fa-film', 'fa-filter', 'fa-fire', 'fa-fire-extinguisher', 'fa-flag', 'fa-flag-checkered', 'fa-flag-o', 'fa-flash', 'fa-flask', 'fa-folder', 'fa-folder-o', 'fa-folder-open', 'fa-folder-open-o', 'fa-frown-o', 'fa-futbol-o', 'fa-gamepad', 'fa-gavel', 'fa-gear', 'fa-gears', 'fa-gift', 'fa-glass', 'fa-globe', 'fa-graduation-cap', 'fa-group', 'fa-hand-grab-o', 'fa-hand-lizard-o', 'fa-hand-paper-o', 'fa-hand-peace-o', 'fa-hand-pointer-o', 'fa-hand-rock-o', 'fa-hand-scissors-o', 'fa-hand-spock-o', 'fa-hand-stop-o', 'fa-hdd-o', 'fa-headphones', 'fa-heart', 'fa-heart-o', 'fa-heartbeat', 'fa-history', 'fa-home', 'fa-hotel', 'fa-hourglass', 'fa-hourglass-1', 'fa-hourglass-2', 'fa-hourglass-3', 'fa-hourglass-end', 'fa-hourglass-half', 'fa-hourglass-o', 'fa-hourglass-start', 'fa-i-cursor', 'fa-image', 'fa-inbox', 'fa-industry', 'fa-info', 'fa-info-circle', 'fa-institution', 'fa-key', 'fa-keyboard-o', 'fa-language', 'fa-laptop', 'fa-leaf', 'fa-legal', 'fa-lemon-o', 'fa-level-down', 'fa-level-up', 'fa-life-bouy', 'fa-life-buoy', 'fa-life-ring', 'fa-life-saver', 'fa-lightbulb-o', 'fa-line-chart', 'fa-location-arrow', 'fa-lock', 'fa-magic', 'fa-magnet', 'fa-mail-forward', 'fa-mail-reply', 'fa-mail-reply-all', 'fa-male', 'fa-map', 'fa-map-marker', 'fa-map-o', 'fa-map-pin', 'fa-map-signs', 'fa-meh-o', 'fa-microphone', 'fa-microphone-slash', 'fa-minus', 'fa-minus-circle', 'fa-minus-square', 'fa-minus-square-o', 'fa-mobile', 'fa-mobile-phone', 'fa-money', 'fa-moon-o', 'fa-mortar-board', 'fa-motorcycle', 'fa-mouse-pointer', 'fa-music', 'fa-navicon', 'fa-newspaper-o', 'fa-object-group', 'fa-object-ungroup', 'fa-paint-brush', 'fa-paper-plane', 'fa-paper-plane-o', 'fa-paw', 'fa-pencil', 'fa-pencil-square', 'fa-pencil-square-o', 'fa-phone', 'fa-phone-square', 'fa-photo', 'fa-picture-o', 'fa-pie-chart', 'fa-plane', 'fa-plug', 'fa-plus', 'fa-plus-circle', 'fa-plus-square', 'fa-plus-square-o', 'fa-power-off', 'fa-print', 'fa-puzzle-piece', 'fa-qrcode', 'fa-question', 'fa-question-circle', 'fa-quote-left', 'fa-quote-right', 'fa-random', 'fa-recycle', 'fa-refresh', 'fa-registered', 'fa-remove', 'fa-reorder', 'fa-reply', 'fa-reply-all', 'fa-retweet', 'fa-road', 'fa-rocket', 'fa-rss', 'fa-rss-square', 'fa-search', 'fa-search-minus', 'fa-search-plus', 'fa-send', 'fa-send-o', 'fa-server', 'fa-share', 'fa-share-alt', 'fa-share-alt-square', 'fa-share-square', 'fa-share-square-o', 'fa-shield', 'fa-ship', 'fa-shopping-cart', 'fa-sign-in', 'fa-sign-out', 'fa-signal', 'fa-sitemap', 'fa-sliders', 'fa-smile-o', 'fa-soccer-ball-o', 'fa-sort', 'fa-sort-alpha-asc', 'fa-sort-alpha-desc', 'fa-sort-amount-asc', 'fa-sort-amount-desc', 'fa-sort-asc', 'fa-sort-desc', 'fa-sort-down', 'fa-sort-numeric-asc', 'fa-sort-numeric-desc', 'fa-sort-up', 'fa-space-shuttle', 'fa-spinner', 'fa-spoon', 'fa-square', 'fa-square-o', 'fa-star', 'fa-star-half', 'fa-star-half-empty', 'fa-star-half-full', 'fa-star-half-o', 'fa-star-o', 'fa-sticky-note', 'fa-sticky-note-o', 'fa-street-view', 'fa-suitcase', 'fa-sun-o', 'fa-support', 'fa-tablet', 'fa-tachometer', 'fa-tag', 'fa-tags', 'fa-tasks', 'fa-taxi', 'fa-television', 'fa-terminal', 'fa-thumb-tack', 'fa-thumbs-down', 'fa-thumbs-o-down', 'fa-thumbs-o-up', 'fa-thumbs-up', 'fa-ticket', 'fa-times', 'fa-times-circle', 'fa-times-circle-o', 'fa-tint', 'fa-toggle-down', 'fa-toggle-left', 'fa-toggle-off', 'fa-toggle-on', 'fa-toggle-right', 'fa-toggle-up', 'fa-trademark', 'fa-trash', 'fa-trash-o', 'fa-tree', 'fa-trophy', 'fa-truck', 'fa-tty', 'fa-tv', 'fa-umbrella', 'fa-university', 'fa-unlock', 'fa-unlock-alt', 'fa-unsorted', 'fa-upload', 'fa-user', 'fa-user-plus', 'fa-user-secret', 'fa-user-times', 'fa-users', 'fa-video-camera', 'fa-volume-down', 'fa-volume-off', 'fa-volume-up', 'fa-warning', 'fa-wheelchair', 'fa-wifi', 'fa-wrench']
	}, {
		name: 'Hand Icons',
		type: 'fa',
		list: ['fa-hand-grab-o', 'fa-hand-lizard-o', 'fa-hand-o-down', 'fa-hand-o-left', 'fa-hand-o-right', 'fa-hand-o-up', 'fa-hand-paper-o', 'fa-hand-peace-o', 'fa-hand-pointer-o', 'fa-hand-rock-o', 'fa-hand-scissors-o', 'fa-hand-spock-o', 'fa-hand-stop-o', 'fa-thumbs-down', 'fa-thumbs-o-down', 'fa-thumbs-o-up', 'fa-thumbs-up']
	}, {
		name: 'Transportation Icons',
		type: 'fa',
		list: ['fa-ambulance', 'fa-automobile', 'fa-bicycle', 'fa-bus', 'fa-cab', 'fa-car', 'fa-fighter-jet', 'fa-motorcycle', 'fa-plane', 'fa-rocket', 'fa-ship', 'fa-space-shuttle', 'fa-subway', 'fa-taxi', 'fa-train', 'fa-truck', 'fa-wheelchair']
	}, {
		name: 'Gender Icons',
		type: 'fa',
		list: ['fa-genderless', 'fa-intersex', 'fa-mars', 'fa-mars-double', 'fa-mars-stroke', 'fa-mars-stroke-h', 'fa-mars-stroke-v', 'fa-mercury', 'fa-neuter', 'fa-transgender', 'fa-transgender-alt', 'fa-venus', 'fa-venus-double', 'fa-venus-mars']
	}, {
		name: 'File Type Icons',
		type: 'fa',
		list: ['fa-file', 'fa-file-archive-o', 'fa-file-audio-o', 'fa-file-code-o', 'fa-file-excel-o', 'fa-file-image-o', 'fa-file-movie-o', 'fa-file-o', 'fa-file-pdf-o', 'fa-file-photo-o', 'fa-file-picture-o', 'fa-file-powerpoint-o.fa-file-sound-o', 'fa-file-text', 'fa-file-text-o', 'fa-file-video-o', 'fa-file-word-o', 'fa-file-zip-o']
	}, {
		name: 'Spinner Icons',
		type: 'fa',
		list: ['a-circle-o-notch', 'fa-cog', 'fa-gear', 'fa-refresh', 'fa-spinner']
	}, {
		name: 'Form Control Icons',
		type: 'fa',
		list: ['a-check-square', 'fa-check-square-o', 'fa-circle', 'fa-circle-o', 'fa-dot-circle-o', 'fa-minus-square', 'fa-minus-square-o', 'fa-plus-square', 'fa-plus-square-o', 'fa-square', 'fa-square-o']
	}, {
		name: 'Payment Icons',
		type: 'fa',
		list: ['fa-cc-amex', 'fa-cc-diners-club', 'fa-cc-discover', 'fa-cc-jcb', 'fa-cc-mastercard', 'fa-cc-paypal', 'fa-cc-stripe', 'fa-cc-visa', 'fa-credit-card', 'fa-google-wallet', 'fa-paypal']
	}, {
		name: 'Chart Icons',
		type: 'fa',
		list: ['fa-area-chart', 'fa-bar-chart', 'fa-bar-chart-o', 'fa-line-chart', 'fa-pie-chart']
	}, {
		name: 'Currency Icons',
		type: 'fa',
		list: ['fa-bitcoin', 'fa-btc', 'fa-cny', 'fa-dollar', 'fa-eur', 'fa-euro', 'fa-gbp', 'fa-gg', 'fa-gg-circle', 'fa-ils', 'fa-inr', 'fa-jpy', 'fa-krw', 'fa-money', 'fa-rmb', 'fa-rouble', 'fa-rub', 'fa-ruble', 'fa-rupee', 'fa-shekel', 'fa-sheqel', 'fa-try', 'fa-turkish-lira', 'fa-usd', 'fa-won', 'fa-yen']
	}, {
		name: 'Text Editor Icons',
		type: 'fa',
		list: ['fa-align-center', 'fa-align-justify', 'fa-align-left', 'fa-align-right', 'fa-bold', 'fa-chain', 'fa-chain-broken', 'fa-clipboard', 'fa-columns', 'fa-copy', 'fa-cut', 'fa-dedent', 'fa-eraser', 'fa-file', 'fa-file-o', 'fa-file-text', 'fa-file-text-o', 'fa-files-o', 'fa-floppy-o', 'fa-font', 'fa-header', 'fa-indent', 'fa-italic', 'fa-link', 'fa-list', 'fa-list-alt', 'fa-list-ol', 'fa-list-ul', 'fa-outdent', 'fa-paperclip', 'fa-paragraph', 'fa-paste', 'fa-repeat', 'fa-rotate-left', 'fa-rotate-right', 'fa-save', 'fa-scissors', 'fa-strikethrough', 'fa-subscript', 'fa-superscript', 'fa-table', 'fa-text-height', 'fa-text-width', 'fa-th', 'fa-th-large', 'fa-th-list', 'fa-underline', 'fa-undo', 'fa-unlink']
	}, {
		name: 'Directional Icons',
		type: 'fa',
		list: ['fa-angle-double-down', 'fa-angle-double-left', 'fa-angle-double-right', 'fa-angle-double-up', 'fa-angle-down', 'fa-angle-left', 'fa-angle-right', 'fa-angle-up', 'fa-arrow-circle-down', 'fa-arrow-circle-left', 'fa-arrow-circle-o-down', 'fa-arrow-circle-o-left', 'fa-arrow-circle-o-right', 'fa-arrow-circle-o-up', 'fa-arrow-circle-right', 'fa-arrow-circle-up', 'fa-arrow-down', 'fa-arrow-left', 'fa-arrow-right', 'fa-arrow-up', 'fa-arrows', 'fa-arrows-alt', 'fa-arrows-h', 'fa-arrows-v', 'fa-caret-down', 'fa-caret-left', 'fa-caret-right', 'fa-caret-square-o-down', 'fa-caret-square-o-left', 'fa-caret-square-o-right', 'fa-caret-square-o-up', 'fa-caret-up', 'fa-chevron-circle-down', 'fa-chevron-circle-left', 'fa-chevron-circle-right', 'fa-chevron-circle-up', 'fa-chevron-down', 'fa-chevron-left', 'fa-chevron-right', 'fa-chevron-up', 'fa-exchange', 'fa-hand-o-down', 'fa-hand-o-left', 'fa-hand-o-right', 'fa-hand-o-up', 'fa-long-arrow-down', 'fa-long-arrow-left', 'fa-long-arrow-right', 'fa-long-arrow-up', 'fa-toggle-down', 'fa-toggle-left', 'fa-toggle-right', 'fa-toggle-up']
	}, {
		name: 'Video Player Icons',
		type: 'fa',
		list: ['fa-arrows-alt', 'fa-backward', 'fa-compress', 'fa-eject', 'fa-expand', 'fa-fast-backward', 'fa-fast-forward', 'fa-forward', 'fa-pause', 'fa-play', 'fa-play-circle', 'fa-play-circle-o', 'fa-random', 'fa-step-backward', 'fa-step-forward', 'fa-stop', 'fa-youtube-play']
	}, {
		name: 'Glyphicons',
		type: 'glyphicon',
		list: ['glyphicon-asterisk', 'glyphicon-plus', 'glyphicon-euro', 'glyphicon-eur', 'glyphicon-minus', 'glyphicon-cloud', 'glyphicon-envelope', 'glyphicon-pencil', 'glyphicon-glass', 'glyphicon-music', 'glyphicon-search', 'glyphicon-heart', 'glyphicon-star', 'glyphicon-star-empty', 'glyphicon-user', 'glyphicon-film', 'glyphicon-th-large', 'glyphicon-th', 'glyphicon-th-list', 'glyphicon-ok', 'glyphicon-remove', 'glyphicon-zoom-in', 'glyphicon-zoom-out', 'glyphicon-off', 'glyphicon-signal', 'glyphicon-cog', 'glyphicon-trash', 'glyphicon-home', 'glyphicon-file', 'glyphicon-time', 'glyphicon-road', 'glyphicon-download-alt', 'glyphicon-download', 'glyphicon-upload', 'glyphicon-inbox', 'glyphicon-play-circle', 'glyphicon-repeat', 'glyphicon-refresh', 'glyphicon-list-alt', 'glyphicon-lock', 'glyphicon-flag', 'glyphicon-headphones', 'glyphicon-volume-off', 'glyphicon-volume-down', 'glyphicon-volume-up', 'glyphicon-qrcode', 'glyphicon-barcode', 'glyphicon-tag', 'glyphicon-tags', 'glyphicon-book', 'glyphicon-bookmark', 'glyphicon-print', 'glyphicon-camera', 'glyphicon-font', 'glyphicon-bold', 'glyphicon-italic', 'glyphicon-text-height', 'glyphicon-text-width', 'glyphicon-align-left', 'glyphicon-align-center', 'glyphicon-align-right', 'glyphicon-align-justify', 'glyphicon-list', 'glyphicon-indent-left', 'glyphicon-indent-right', 'glyphicon-facetime-video', 'glyphicon-picture', 'glyphicon-map-marker', 'glyphicon-adjust', 'glyphicon-tint', 'glyphicon-edit', 'glyphicon-share', 'glyphicon-check', 'glyphicon-move', 'glyphicon-step-backward', 'glyphicon-fast-backward', 'glyphicon-backward', 'glyphicon-play', 'glyphicon-pause', 'glyphicon-stop', 'glyphicon-forward', 'glyphicon-fast-forward', 'glyphicon-step-forward', 'glyphicon-eject', 'glyphicon-chevron-left', 'glyphicon-chevron-right', 'glyphicon-plus-sign', 'glyphicon-minus-sign', 'glyphicon-remove-sign', 'glyphicon-ok-sign', 'glyphicon-question-sign', 'glyphicon-info-sign', 'glyphicon-screenshot', 'glyphicon-remove-circle', 'glyphicon-ok-circle', 'glyphicon-ban-circle', 'glyphicon-arrow-left', 'glyphicon-arrow-right', 'glyphicon-arrow-up', 'glyphicon-arrow-down', 'glyphicon-share-alt', 'glyphicon-resize-full', 'glyphicon-resize-small', 'glyphicon-exclamation-sign', 'glyphicon-gift', 'glyphicon-leaf', 'glyphicon-fire', 'glyphicon-eye-open', 'glyphicon-eye-close', 'glyphicon-warning-sign', 'glyphicon-plane', 'glyphicon-calendar', 'glyphicon-random', 'glyphicon-comment', 'glyphicon-magnet', 'glyphicon-chevron-up', 'glyphicon-chevron-down', 'glyphicon-retweet', 'glyphicon-shopping-cart', 'glyphicon-folder-close', 'glyphicon-folder-open', 'glyphicon-resize-vertical', 'glyphicon-resize-horizontal', 'glyphicon-hdd', 'glyphicon-bullhorn', 'glyphicon-bell', 'glyphicon-certificate', 'glyphicon-thumbs-up', 'glyphicon-thumbs-down', 'glyphicon-hand-right', 'glyphicon-hand-left', 'glyphicon-hand-up', 'glyphicon-hand-down', 'glyphicon-circle-arrow-right', 'glyphicon-circle-arrow-left', 'glyphicon-circle-arrow-up', 'glyphicon-circle-arrow-down', 'glyphicon-globe', 'glyphicon-wrench', 'glyphicon-tasks', 'glyphicon-filter', 'glyphicon-briefcase', 'glyphicon-fullscreen', 'glyphicon-dashboard', 'glyphicon-paperclip', 'glyphicon-heart-empty', 'glyphicon-link', 'glyphicon-phone', 'glyphicon-pushpin', 'glyphicon-usd', 'glyphicon-gbp', 'glyphicon-sort', 'glyphicon-sort-by-alphabet', 'glyphicon-sort-by-alphabet-alt', 'glyphicon-sort-by-order', 'glyphicon-sort-by-order-alt', 'glyphicon-sort-by-attributes', 'glyphicon-sort-by-attributes-alt', 'glyphicon-unchecked', 'glyphicon-expand', 'glyphicon-collapse-down', 'glyphicon-collapse-up', 'glyphicon-log-in', 'glyphicon-flash', 'glyphicon-log-out', 'glyphicon-new-window', 'glyphicon-record', 'glyphicon-save', 'glyphicon-open', 'glyphicon-saved', 'glyphicon-import', 'glyphicon-export', 'glyphicon-send', 'glyphicon-floppy-disk', 'glyphicon-floppy-saved', 'glyphicon-floppy-remove', 'glyphicon-floppy-save', 'glyphicon-floppy-open', 'glyphicon-credit-card', 'glyphicon-transfer', 'glyphicon-cutlery', 'glyphicon-header', 'glyphicon-compressed', 'glyphicon-earphone', 'glyphicon-phone-alt', 'glyphicon-tower', 'glyphicon-stats', 'glyphicon-sd-video', 'glyphicon-hd-video', 'glyphicon-subtitles', 'glyphicon-sound-stereo', 'glyphicon-sound-dolby', 'glyphicon-sound-5-1', 'glyphicon-sound-6-1', 'glyphicon-sound-7-1', 'glyphicon-copyright-mark', 'glyphicon-registration-mark', 'glyphicon-cloud-download', 'glyphicon-cloud-upload', 'glyphicon-tree-conifer', 'glyphicon-tree-deciduous', 'glyphicon-cd', 'glyphicon-save-file', 'glyphicon-open-file', 'glyphicon-level-up', 'glyphicon-copy', 'glyphicon-paste', 'glyphicon-alert', 'glyphicon-equalizer', 'glyphicon-king', 'glyphicon-queen', 'glyphicon-pawn', 'glyphicon-bishop', 'glyphicon-knight', 'glyphicon-baby-formula', 'glyphicon-tent', 'glyphicon-blackboard', 'glyphicon-bed', 'glyphicon-apple', 'glyphicon-erase', 'glyphicon-hourglass', 'glyphicon-lamp', 'glyphicon-duplicate', 'glyphicon-piggy-bank', 'glyphicon-scissors', 'glyphicon-bitcoin', 'glyphicon-btc', 'glyphicon-xbt', 'glyphicon-yen', 'glyphicon-jpy', 'glyphicon-ruble', 'glyphicon-rub', 'glyphicon-scale', 'glyphicon-ice-lolly', 'glyphicon-ice-lolly-tasted', 'glyphicon-education', 'glyphicon-option-horizontal', 'glyphicon-option-vertical', 'glyphicon-menu-hamburger', 'glyphicon-modal-window', 'glyphicon-oil', 'glyphicon-grain', 'glyphicon-sunglasses', 'glyphicon-text-size', 'glyphicon-text-color', 'glyphicon-text-background', 'glyphicon-object-align-top', 'glyphicon-object-align-bottom', 'glyphicon-object-align-horizontal', 'glyphicon-object-align-left', 'glyphicon-object-align-vertical', 'glyphicon-object-align-right', 'glyphicon-triangle-right', 'glyphicon-triangle-left', 'glyphicon-triangle-bottom', 'glyphicon-triangle-top', 'glyphicon-console', 'glyphicon-superscript', 'glyphicon-subscript', 'glyphicon-menu-left', 'glyphicon-menu-right', 'glyphicon-menu-down', 'glyphicon-menu-up']
	}];

	$(function() {
		window.modal_icons = $('#modal-icons').modal({
			show: false,
			keyboard: false,
			//backdrop: 'static'
		});

		var init_treeview = function(data){
			return $('#treeview').treeview({
				color: "#428bca",
				enableLinks: false,
				backColor: '#eee',
				onhoverColor: 'orange',
				selectedColor: '#9C27B0',
				selectedBackColor: 'orange',
				selectable: true,
				data: data,
				onNodeSelected: function(event, node) {
					//FillDataDetail(node);
				},
				// onNodeUnselected: function (event, node) {
				// 	console.log(node);
				// }
			});
		};

		init_treeview([{"_id":1,"text":"Home","sort":0,"icon":"fa fa-amazon","is_tree":true,"is_all_access":false,"parent_id":0,"href":"","active":true,"is_show":true,"icon_right":"fa fa-angle-left pull-right","__v":0,"actions":[],"nodeId":1,"nodes":[{"_id":2,"text":"home1","sort":0,"icon":"fa fa-balance-scale","is_tree":false,"is_all_access":false,"parent_id":1,"href":"aaaa","active":true,"is_show":true,"__v":0,"actions":[],"nodeId":2}]},{"_id":3,"text":"document","sort":1,"icon":"fa fa-clone","is_tree":false,"is_all_access":false,"parent_id":0,"href":"bbbbbbbb","active":true,"is_show":true,"__v":0,"actions":[],"nodeId":3}]);
	});

	var app = angular.module("mainApp", []);
	app.controller("mainController", function($scope, $http) {
	//	$scope._id = 0;
	//	$scope.name = '';
	//	$scope.date_from = '';
	//	$scope.date_to = '';
	//	$scope.districts = [];

		//var init_treeview = function(data){
		//	return $('#treeview').treeview({
		//		color: "#428bca",
		//		enableLinks: false,
		//		backColor: '#eee',
		//		onhoverColor: 'orange',
		//		selectedColor: '#9C27B0',
		//		selectedBackColor: 'orange',
		//		selectable: true,
		//		data: data,
		//		onNodeSelected: function(event, node) {
		//			FillDataDetail(node);
		//		},
		//		// onNodeUnselected: function (event, node) {
		//		// 	console.log(node);
		//		// }
		//	});
		//};

		//init_treeview([{"_id":1,"text":"Home","sort":0,"icon":"fa fa-amazon","is_tree":true,"is_all_access":false,"parent_id":0,"href":"","active":true,"is_show":true,"icon_right":"fa fa-angle-left pull-right","__v":0,"actions":[],"nodeId":1,"nodes":[{"_id":2,"text":"home1","sort":0,"icon":"fa fa-balance-scale","is_tree":false,"is_all_access":false,"parent_id":1,"href":"aaaa","active":true,"is_show":true,"__v":0,"actions":[],"nodeId":2}]},{"_id":3,"text":"document","sort":1,"icon":"fa fa-clone","is_tree":false,"is_all_access":false,"parent_id":0,"href":"bbbbbbbb","active":true,"is_show":true,"__v":0,"actions":[],"nodeId":3}]);

		//$scope.delete = function(){
		//	//
		//};
		//
		//$scope.add = function(){
		//	//
		//};
	});
</script>