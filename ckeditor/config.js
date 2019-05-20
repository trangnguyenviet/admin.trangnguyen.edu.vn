/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function(config) {
	// Define changes to default configuration here. For example:
	config.language = 'vi';
	//config.uiColor = '#AADC6E';
	config.width = '100%';
	config.height = '30em';
	
	config.extraPlugins = 'eqneditor,youtube,uploadimage,colorbutton,codesnippet,imagepaste,symbol,slideshow,video';
	
//	config.extraPlugins = 'eqneditor';
//	config.extraPlugins = 'youtube';
	//config.extraPlugins = 'uploadimage';
//	config.extraPlugins = 'colorbutton';
//	config.extraPlugins = 'autogrow';
//	config.extraPlugins = 'codesnippet';
	//config.extraPlugins = 'imagepaste';
//	config.extraPlugins = 'symbol';
//	config.extraPlugins = 'slideshow';
//	config.extraPlugins = 'googledocs';
//	config.extraPlugins = 'fixed';
	
	//config.filebrowserBrowseUrl = '/browser/browse.php';
	//config.filebrowserImageBrowseUrl = '/browser/browse.php?type=Images,Audio,Video';
	//config.filebrowserUploadUrl = '/uploader/upload.php';
	//config.filebrowserImageUploadUrl = '/uploader/upload.php?type=Images,Audio';
	config.filebrowserWindowWidth = '900';
	config.filebrowserWindowHeight = '400';
	config.filebrowserBrowseUrl = '/ckfinder/ckfinder.html?Type=Files';
	config.filebrowserImageBrowseUrl = '/ckfinder/ckfinder.html?Type=Images';//xxx
	config.filebrowserVideoBrowseUrl = '/ckfinder/ckfinder.html?Type=Video';
	config.filebrowserAudioBrowseUrl = '/ckfinder/ckfinder.html?Type=Audio';
	config.filebrowserFlashBrowseUrl = '/ckfinder/ckfinder.html?Type=Flash';
	config.filebrowserUploadUrl = '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';
	config.filebrowserImageUploadUrl = '/ckfinder/core/connctor/php/connector.php?command=QuickUpload&type=Images';
	config.filebrowserFlashUploadUrl = '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';
};
