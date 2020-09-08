//-------------------------基本操作----------------------------//
//调用样式
var ie = (navigator.appVersion.indexOf("MSIE") != -1);//IE
var ff = (navigator.userAgent.indexOf("Firefox") != -1);//Firefox
if (ie) {
    document.write("<link href=\"/css/admin/IE_Item.css\" rel=\"stylesheet\" type=\"text/css\" />");
}
if (ff) {
    document.write("<link href=\"/css/admin/FF_Item.css\" rel=\"stylesheet\" type=\"text/css\" />");
}
//跳转菜单
function MM_jumpMenu(targ, selObj, restore){ //v3.0
    eval(targ + ".location='" + selObj.options[selObj.selectedIndex].value + "'");
    if (restore) 
        selObj.selectedIndex = 0;
}
//获取函数
function g(sid){
	return document.getElementById(sid);
}
function $(sid){
	return document.getElementById(sid);
}

//-------------------------用户界面----------------------------//
//显示引导
var temptr = false;
function ResetTrBgcolor(trid){
    if (document.getElementById(trid)) {
        document.getElementById(trid).className = "color";
        if (temptr) {
            document.getElementById(temptr).className = "color0";
        }
        temptr = trid;
    }
}

function MouseColorOver(trid){
    if (document.getElementById(trid)) {
        if (temptr != trid) {
            document.getElementById(trid).className = "color2";
        }
    }
}

function MouseColorOut(trid){
    if (document.getElementById(trid)) {
        if (temptr != trid) {
            document.getElementById(trid).className = "color0";
        }
        else {
            document.getElementById(trid).className = "color";
        }
    }
}

//表单提交
function subform(formaction){
    if (document.forms[0].checkid.value == "") {
        window.alert('请选择要处理的项目！');
    }
    else {       
		document.forms[0].action = formaction;
        document.forms[0].submit();
    }
}

//操作确认
function checkclick(msg){
    if (confirm(msg)) {
        return true;
    }
    else {
        return false;
    }
}


//主菜单和子菜单的分叉数目应不大于6
function ShowMenu(menu,id)
{
	var img_plus=new Image();
	var img_minus=new Image();
	var img_f_0=new Image();
	var img_f_1=new Image();

	img_plus.src="../../images/admin/menuicon/plus.gif";
	img_minus.src="../../images/admin/menuicon/minus.gif";

	img_f_0.src="../../images/admin/menuicon/Tminus.gif";
    img_f_1.src="../../images/admin/menuicon/Tplus.gif";

	if (document.getElementById("menu_"+menu+id)){
		if (document.getElementById("menu_"+menu+id).style.display=="none")
		{
			document.getElementById("menu_"+menu+id).style.display="inline";
			if(document.getElementById("img_design_"+menu+id).src==img_plus.src)
			{
				document.getElementById("img_design_"+menu+id).src=img_minus.src;
			}
			else
			{
				document.getElementById("img_design_"+menu+id).src=img_f_0.src;
			}
		}
		else
		{
			document.getElementById("menu_"+menu+id).style.display="none";
			if(document.getElementById("img_design_"+menu+id).src==img_minus.src)
			{
				document.getElementById("img_design_"+menu+id).src=img_plus.src;
			}
			else
			{
				document.getElementById("img_design_"+menu+id).src=img_f_1.src;
			}
		}
		/*
		for(i=1;i<=12;i++)
		{
			if ((id != i)&&(document.getElementById("menu_"+menu+i))) {
				if (document.getElementById("menu_"+menu+i).style.display =="inline")
				{
					document.getElementById("menu_"+menu+i).style.display="none";
					document.getElementById("img_design_"+menu+i).src=img_plus.src;
				}
			}	
		}
		*/
	}
}
//获得fck的内容
function getfckvalue(id)
{
	var oEditor = FCKeditorAPI.GetInstance(id) ;
	return oEditor.GetXHTML( true );	
}
