<!-- #include file="rsaEncrypter.asp" -->
<%
Const APPCENTRE_URL="http://app.zblogcn.com/client/"

Const APPCENTRE_SYSTEM_UPDATE="http://update.zblogcn.com/zblog2/"

Const APPCENTRE_API_URL="http://app.zblogcn.com/api/index.php?api="
Const APPCENTRE_API_APP_ISBUY="isbuy"
Const APPCENTRE_API_USER_INFO="userinfo"
Const APPCENTRE_API_ORDER_LIST="orderlist"
Const APPCENTRE_API_ORDER_DETAIL="orderdetail"


Call RegisterPlugin("AppCentre","ActivePlugin_AppCentre")
'挂口部分
Function ActivePlugin_AppCentre()

	Dim ac
	Set ac=New TConfig
	ac.Load "AppCentre"

	If BlogUser.Level=1 Then Call Add_Response_Plugin("Response_Plugin_ThemeMng_SubMenu",MakeSubMenu("在线安装主题<script src='"& BlogHost &"zb_users/plugin/appcentre/theme_js.asp' type='text/javascript'></script>",BlogHost & "zb_users/plugin/appcentre/server.asp?cate=2","m-left",False))

	If BlogUser.Level=1 Then Call Add_Response_Plugin("Response_Plugin_ThemeMng_SubMenu",MakeSubMenu("编辑当前主题信息",BlogHost & "zb_users/plugin/appcentre/theme_edit.asp?id="&Server.URLEncode(ZC_BLOG_THEME),"m-left",False))

	If BlogUser.Level=1 Then Call Add_Response_Plugin("Response_Plugin_PluginMng_SubMenu",MakeSubMenu("在线安装插件<script src='"& BlogHost &"zb_users/plugin/appcentre/plugin_js.asp' type='text/javascript'></script>",BlogHost & "zb_users/plugin/appcentre/server.asp?cate=1","m-left",False))

	Call Add_Response_Plugin("Response_Plugin_Admin_Left",MakeLeftMenu(1,"应用中心",GetCurrentHost&"zb_users/plugin/appcentre/main.asp","nav_appcentre","aAppcentre",BlogHost&"zb_users/plugin/appcentre/images/cube1.png"))

	'检查更新
	If BlogUser.Level=1 Then
		Dim last
		last=ac.read("LastChechUpdate")
		If last="" Then last="2000-01-01"
		last=Replace(last,"|","")
		If DateDiff("h", last, Now)>=11 Then
			Randomize
			Call Add_Response_Plugin("Response_Plugin_SiteInfo_SubMenu","<script type='text/javascript'>$(document).ready(function(){  $.getScript('"&BlogHost&"zb_users/plugin/appcentre/server.asp?method=checksilent&rnd="&Rnd()&"'); });</script>")
			ac.Write "LastChechUpdate","|"&Now&"|"
			ac.Save			
		End If
	End If

	Call Add_Response_Plugin("Response_Plugin_SettingMng_SubMenu",MakeSubMenu("应用中心设置",GetCurrentHost() & "zb_users/plugin/appcentre/setting.asp","m-left",False))

End Function


Function AppCentre_Check_App_IsBuy(appid)

	Dim app_modified
	Dim app_config
	Dim objXmlFile,strXmlFile
	Dim login_un,login_pw,shop_un,shop_pw
	Dim fso, s ,t
	Set fso = CreateObject("Scripting.FileSystemObject")

	strXmlFile =BlogPath & "zb_users/plugin/AppCentre/plugin.xml"

	Set objXmlFile=Server.CreateObject("Microsoft.XMLDOM")
	objXmlFile.async = False
	objXmlFile.ValidateOnParse=False
	objXmlFile.load(strXmlFile)
	If objXmlFile.readyState=4 Then
		If objXmlFile.parseError.errorCode <> 0 Then
		Else
			app_modified=objXmlFile.documentElement.selectSingleNode("modified").text
		End If
	End If

	t = LoadFromFile(BlogPath &"zb_users\PLUGIN\AppCentre\include.asp","utf-8")
	t = Replace(t,vbCr,"")
	t = Replace(t,vbLf,"")
	s=appid & "|" & ZC_BLOG_HOST & "|" & MD5(t)


	Dim objXmlHttp
	Dim strResponse,strURL,strPost

	Dim crypt, crypted, decrypted
	Set crypt = rsaEncrypter()
	crypt.setKey "-----BEGIN PUBLIC KEY-----" & vbCr & _
	"MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA3HYTjyOIzYnJtIl4M50l" & vbCr & _
	"aYgEQmRGeOQA+5H1Ze3Fgc7bbEc+DtJMAmwYaGR3+ULkL4c0m/KXXxujTgEfxGkk" & vbCr & _
	"fO7XI7Z0b1EWFm4M7IbXox6LaLU6mK4OK5nMWWyIyawYn0bdw6X/vaXEyzkDE8fP" & vbCr & _
	"ZGGPo5OydyZdTm47lXdCewyxk1CQ6nMs75u0mLjnDfsFXNiDx8hvXODnTSJKzb+C" & vbCr & _
	"154qg0uRXjaB2ylnhJKDcQCFAbg5uy0iRcrp7+CFG4qvk0c7d/xRRjqY/y3HI+o5" & vbCr & _
	"29/vvByD9KVXfWQQI6unfWfO1uEegXcgypHKHRmuyZoIDH7r56sleXKcN0OLesxp" & vbCr & _
	"zwIDAQAB" & vbCr & _
	"-----END PUBLIC KEY-----"
	crypted = crypt.encrypt(s)

	strPost = "info=" & Server.URLEncode(crypted)


	Set app_config=New TConfig
	app_config.Load "AppCentre"
	login_un=app_config.read("DevelopUserName")
	login_pw=app_config.read("DevelopPassWord")
	shop_un=app_config.read("ShopUserName")
	shop_pw=app_config.read("ShopPassWord")

	Set objXmlHttp=Server.CreateObject("MSXML2.ServerXMLHTTP")

	strURL=replace(APPCENTRE_URL,"http","https") & "?checkbuy"
	objXmlHttp.Open "POST",strURL
	objXmlhttp.SetRequestHeader "Content-Type","application/x-www-form-urlencoded"

	objXmlhttp.SetRequestHeader "User-Agent","AppCentre/"&app_modified & " ZBlog/"&BlogVersion&" "&Request.ServerVariables("HTTP_USER_AGENT") &""
	objXmlhttp.SetRequestHeader "Cookie","username="&Server.URLEncode(login_un)&"; password="&Server.URLEncode(login_pw)&"; shop_username="&Server.URLEncode(shop_un)&"; shop_password="&Server.URLEncode(shop_pw)
	objXmlhttp.SetRequestHeader "Website",ZC_BLOG_HOST
	objXmlHttp.Send strPost

	If objXmlHttp.ReadyState=4 Then
		If objXmlhttp.Status=200 Then
			strResponse=objXmlhttp.ResponseText
			decrypted = crypt.decrypt(strResponse)
		Else
			strResponse=objXmlhttp.ResponseText
		End If
	End If

	If decrypted = MD5(login_un & "ok") Then
		AppCentre_Check_App_IsBuy=True
		Exit Function
	Else
		Response.Write decrypted
		Response.End
	End If

	AppCentre_Check_App_IsBuy=False

End Function
%>