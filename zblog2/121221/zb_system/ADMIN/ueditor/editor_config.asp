﻿<%@ CODEPAGE=65001 %>
<!-- #include file="../../../zb_users/c_option.asp" -->
<!-- #include file="../../function/c_function.asp" -->
<!-- #include file="../../function/c_system_lib.asp" -->
<!-- #include file="../../function/c_system_base.asp" -->
<!-- #include file="../../function/c_system_plugin.asp" -->
<!-- #include file="../../../zb_users/plugin/p_config.asp" -->
<%
Response.ContentType="application/x-javascript"
%>
<%
Call ActivePlugin()
For Each sAction_Plugin_UEditor_Config_Begin in Action_Plugin_UEditor_Config_Begin
	If Not IsEmpty(sAction_Plugin_UEditor_Config_Begin) Then Call Execute(sAction_Plugin_UEditor_Config_Begin)
Next

        
	Dim strUPLOADDIR

	strUPLOADDIR = Replace(ZC_UPLOAD_DIRECTORY&"/"&Year(GetTime(Now()))&"/"&Month(GetTime(Now())),"\","/")

	Dim Path
	Path=BlogHost & ""& strUPLOADDIR &"/"
	dim strJSContent
	strJSContent="(function(){var URL;URL = '"&BlogHost&"zb_system/admin/ueditor/';window.UEDITOR_CONFIG = {"
	Dim oDic
	Set oDic = Server.CreateObject("Scripting.Dictionary")
	oDic.Add "UEDITOR_HOME_URL","URL"
    oDic.Add "imageUrl"," URL+""asp/imageUp.asp"""
    oDic.Add "imagePath",""""&Path&""""
    oDic.Add "imageFieldName"," ""edtFileLoad"""
    oDic.Add "fileUrl"," URL+""asp/fileUp.asp"""
    oDic.Add "filePath",""""&Path&""""
    oDic.Add "fileFieldName"," ""edtFileLoad"""
    oDic.Add "catchRemoteImageEnable"," false"
    oDic.Add "imageManagerUrl","URL+""asp/imageManager.asp"""
    oDic.Add "imageManagerPath",""""&BlogHost&""""
    oDic.Add "wordImageUrl"," URL+""asp/imageUp.asp"""
    oDic.Add "wordImagePath",""""&Path&""""
    oDic.Add "wordImageFieldName","""edtFileLoad"""
    oDic.Add "getMovieUrl","URL+""asp/getMovie.asp"""
	oDic.Add "toolbars","[ [ 'source', '|', 'undo', 'redo', '|', 'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript','forecolor', 'backcolor', '|', 'insertorderedlist', 'insertunorderedlist','indent', 'justifyleft', 'justifycenter', 'justifyright','|', 'removeformat','formatmatch','autotypeset', 'searchreplace'],[ 'fontfamily', 'fontsize','|', 'emotion','link','music','insertimage',,'insertvideo', 'attachment','spechars','|', 'map', 'gmap','|', "&IIf(ZC_SYNTAXHIGHLIGHTER_ENABLE,"'highlightcode',","")&"'blockquote', 'pasteplain','wordimage','inserttable', '|' ,'scrawl','horizontal','fullscreen']]"
	oDic.Add "maximumWords",1000000000
	oDic.Add "wordCountMsg","'当前已输入 {#count} 个字符 '"
	oDic.Add "initialContent","'<p></p>'"
	oDic.Add "initialStyle","'body{font-size:14px;font-family:微软雅黑,宋体,Arial,Helvetica,sans-serif;}'"
	oDic.Add "wordCount","true"
	oDic.Add "elementPathEnabled","true"
	oDic.Add "minFrameHeight","300"
    oDic.Add "scrawlUrl"," URL+""asp/scrawlUp.asp"""
    oDic.Add "scrawlPath",""""&Path&""""
	oDic.Add "scrawlFieldName","""edtFileLoad"""
	oDic.Add "maxImageSideLength","2147483647"
	oDic.Add "sourceEditor",""""&IIf(ZC_CODEMIRROR_ENABLE,"codemirror","textarea")&""""
	oDic.Add "theme","'default'"
    oDic.Add "themePath","URL +'themes/'"
	'oDic.Add "contextMenu","[]"
	
	Dim i,aryKeys,aryItems
	aryKeys=oDic.Keys
	aryItems=oDic.Items
	For i=0 To Ubound(aryKeys)-1
		strJSContent=strJSContent&aryKeys(i)&":"&aryItems(i)&","
	Next
	strJSContent=strJSContent&aryKeys(i)&":"&aryItems(i)
	
	
	strJSContent=strJSContent&"}})();"


Call Filter_Plugin_UEditor_Config(strJSContent)

For Each sAction_Plugin_UEditor_Config_End in Action_Plugin_UEditor_Config_End
	If Not IsEmpty(sAction_Plugin_UEditor_Config_End) Then Call Execute(sAction_Plugin_UEditor_Config_End)
Next

	response.write strJSContent

%>
