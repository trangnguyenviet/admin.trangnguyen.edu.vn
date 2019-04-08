<script src="/ckfinder/ckfinder.js"></script>
<section class="content-header">
	<h1>
		Quản lý ảnh phần Game
		<small></small>
	</h1>
</section>
<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<script type="text/javascript">
				$(function(){
					$('body').addClass('sidebar-collapse');
					//finder.height = '555px';
				});
				var finder = new CKFinder();
				// finder.basePath = '/ckfinder/'; // The path for the installation of CKFinder (default = "/ckfinder/").
				// Setting custom width and user language.
				// finder.width = '80%';
				finder.height = '555px';
				finder.defaultLanguage = 'vi';
				finder.language = 'vi';
			
				finder.removePlugins = 'basket';
				//finder.selectActionFunction = function(a){
				//	document.getElementById('input').value = a;
				//};
				finder.resourceType = 'Image_Game';
				finder.tabIndex = 1;
				finder.startupPath = "Image_Game:/";
			
				finder.callback = function(api)
				{
					//api.openMsgDialog("Thông báo", "Thumb tin tức nên quy hoạch vào thư mục Images/Thumb_news" );
					// api.hideTool( "f2" );//hide flash folder
					api.openFolder('Images', '/image_game');
					// var folder = api.getSelectedFolder();
					//console.debug(folder);
					//folder.createNewFolder( 'New Folder' );
					// api.setUiColor('white');
				};
				var api = finder.create();
				// finder.SelectFunction = function(a){
				// 	console.log('SelectFunction',a);
				// };
				// finder.SelectFunctionData = function(a){
				// 	console.log('SelectFunctionData',a);
				// };
				//finder.popup('/ckfinder/',600,400);
				// console.debug(api);
				// setTimeout(function(){
				// 	api.openMsgDialog("Sample title","Sample message."); //doesnt work here, CKFinder still not loaded.
				// },1000);
			</script>
		</div>
	</div>
</section>