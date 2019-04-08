/* CodeCogs Commercial Software
   Copyright (C) 2004-2013 CodeCogs, Zyba Ltd, Broadwood, Holford, TA5 1DU, England.
   This software is licensed for commercial usage using version 2 of the CodeCogs Commercial Licence.
	 You must read this License (available at www.codecogs.com) before using this software.
   If you distribute this file it is YOUR responsibility to ensure that all
   recipients have a valid number of commercial licenses. You must retain a
   copy of this licence in all copies you make.
   This program is distributed WITHOUT ANY WARRANTY; without even the implied
   warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
   See the CodeCogs Commercial Licence for more details.
*/
var editorwindow = null;
var lasteditormode = null;

function Get_Cookie(name) {
  var start = document.cookie.indexOf(name+"=");
  var len = start+name.length+1;
  if ((!start) && (name != document.cookie.substring(0,name.length))) return null;
  if (start == -1) return null;
  var end = document.cookie.indexOf(";",len);
  if (end == -1) end = document.cookie.length;
  return unescape(document.cookie.substring(len,end));
}

function Set_Cookie(name,value,expires) {
  var cookieString = name + "=" +escape(value) + ( (expires) ? ";expires=" + expires.toGMTString() : "");
  document.cookie = cookieString;
}

function OpenLatexEditor(target,mode,language,inline,latex, design)
{
	var url='https://latex.codecogs.com/editor_json.php?target='+target+'&type='+mode;
	if(language!='') url+='&lang='+language;
	if(inline==true) url+='&inline';
	if(design!=undefined && design!='') url+='&design='+design;

	if(latex!=undefined && latex!='')
	{
		latex=latex.replace(/\+/g,'&plus;');
		url+='&latex='+encodeURIComponent(latex);
	}
	else latex='';
	
	// check to see if open editor compatible with new request
	if(lasteditormode!=url)
	{
		lasteditormode=url;
		if(editorwindow!=null) editorwindow.close();
		editorwindow=null;
	}
	
	var SID=Get_Cookie('eqeditor'); // sessionID
	var d=new Date();
	if (!SID) SID=d.getTime()+Math.random();
	var expires=new Date(d.getTime()+(1000*3600*24*30));
	Set_Cookie('eqeditor',SID, expires);
	url+='&sid='+SID;
	
	//console.log(url);
	
	if (editorwindow==null || editorwindow.closed || !editorwindow.location){
		editorwindow=window.open('','LaTexEditor','width=700,height=450,status=1,scrollbars=yes,resizable=1');
		if(!editorwindow.opener) editorwindow.opener = self;
		editorwindow.document.open();
		editorwindow.document.write('<!DOCTYPE html><html><head><style>#download,.footer{display:none;}</style><meta charset="utf-8"/><script src="'+url+'" type="text/javascript"></script></head><body><script>document.title="Công thức toán học";EqnExport = function(text,url) {if(window.opener.GameTool) window.opener.GameTool.SetLatex(text,url,function(){window.blur(); }); };</script></body></html>');
		// editorwindow.document.write('<!DOCTYPE html><html><head><meta http-equiv="content-type" content="text/html; charset=utf-8" /><meta http-equiv="content-language" content="VI" /><script src="/js/latex_editer.js" type="text/javascript"></script></head><body></body></html>');
		editorwindow.document.close();
	}
	else
	if(window.focus) editorwindow.focus();
}