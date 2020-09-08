//浏览器判断
var ie = (navigator.appVersion.indexOf("MSIE") != -1);//IE
var ff = (navigator.userAgent.indexOf("Firefox") != -1);//Firefox

//快速获取对象
function g(sid){
	if(sid==""){
		return;
	}
	if(document.getElementById(sid) ) {
		return document.getElementById(sid);
	}
}
//快速切换
function goUrl(url){
	if(url=='') return;
	if (url.indexOf('act=logout') != -1) {
		parent.parent.parent.location = url;
	}else {
		parent.mainFrame.location = url;
	}
}
//动态改变菜单高度
function menuheight(){
    g('info').style.height = '';
    var bodyheight = (document.body.clientHeight ? document.body.clientHeight : document.documentElement.clientHeight);
    g('info').style.height = (bodyheight - g('top').offsetHeight - g('bottom').offsetHeight);
}

//改变一级导航的颜色
function ChangeLink(idstr, lb, pathstr){
    if (g("li" + idstr) && g("span" + idstr)) {
        if (lb=='on') {
            g("li" + idstr).style.background = 'url(/images/admin/face/' + pathstr + '/dhbg2.jpg)';
            g("span" + idstr).style.color = '#000000';
        } else {
            g("li" + idstr).style.background = 'url(/images/admin/face/' + pathstr + '/dhbg1.jpg)';
            g("span" + idstr).style.color = '#FFFFFF';
        }
    }
}


//显示一级菜单内容
function ShowMenuList(mid){
    var childnodelist=document.getElementById("info").getElementsByTagName("table");	
    var tid;
	//var x="";
	for(var i=0; i<childnodelist.length; i++){
		tid = childnodelist[i].id;
	    if(g( tid ) && tid.indexOf('menulist') > -1){
			if (tid == "menulist" +mid) {
		        g( tid ).style.display = 'inline';
		    } else {
		        g( tid ).style.display = 'none';
		    }
			//x+=tid+"\n";
		}			
	}
	//alert(x);
}

//显示二级菜单内容
function ShowMenu(area,level,mid,tag){
    var childnodelist = document.getElementById( area ).getElementsByTagName( tag );	
	var tid;
	//var x="";
	for(var i=0; i<childnodelist.length; i++){
		if (childnodelist[i].id != 'undefined') {
			tid = childnodelist[i].id;
			if (g(tid) && tid.indexOf("menu" + level) > -1) {
				if (tid == "menu" + level + mid) {
					if (g(tid).style.display == 'inline') {
						closemenu(tid, "pic" + level + mid);
					}
					else {
						openmenu(tid, "pic" + level + mid);
					}
				}
				else {
					//使用正确的当前选项ID作为判断的标准，关闭菜单
					closemenu(tid, "pic" + level + tid.replace("menu" + level, ""));
				}
			//x+=tid+"\n";
			}
		}			
	}
	//alert(x);
}

//展开菜单
function openmenu(tid,pid){
	g( tid ).style.display = 'inline';
	var path="/images/admin/menuicon/";
	var tail=".gif";

	if(g(pid).src.indexOf("/Tplus")>-1){
		g(pid).src=path+"Tminus"+tail;
	}
	if(g(pid).src.indexOf("/plus")>-1){
		g(pid).src=path+"minus"+tail;
	}
}

//关闭菜单
function closemenu(tid,pid){
	g( tid ).style.display = 'none';	
	var path="/images/admin/menuicon/";
	var tail=".gif";
	
	if(g(pid).src.indexOf("/Tminus")>-1){
		g(pid).src=path+"Tplus"+tail;
	}
	if(g(pid).src.indexOf("/minus")>-1){
		g(pid).src=path+"plus"+tail;
	}	
}

//侧栏显示与隐藏
function Menuhidden(str){
    if (str == 1) {
        g("top").style.display = "none";
        g("info").style.display = "none";
        g("bottom").style.display = "none";
        parent.g('main').cols = '10,*'
        g("showmenu").style.display = "";
    }
    else {
        g("top").style.display = "";
        g("info").style.display = "";
        g("bottom").style.display = "";
        parent.g('main').cols = '200,*'
        g("showmenu").style.display = "none";
    }
}