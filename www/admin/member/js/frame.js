var frame = {
	top:0,
    g: function(sid){
        return document.getElementById(sid);
    },
    
    //系统提示框架
    load: function(element, url){
        var layer = "ifrLayer";
        var background = "ifrBG";
        
        var ie = (navigator.appVersion.indexOf("MSIE") != -1);//IE
        var ff = (navigator.userAgent.indexOf("Firefox") != -1);//Firefox
        if (!this.g(layer)) {
            var size = this.getPageSize();
            var bodyheight = size[1];
            
            if (ff) {
                this.g(background).innerHTML += "<div id=\"" + layer + "\" style=\"position:absolute;left:0px;top:0px;width:100%;height:" + bodyheight + "px; background-color:#000000;-moz-opacity: 0.5;opacity:0.5;z-index:1;\"></div>";
            }
            if (ie) {
                this.g(background).innerHTML += "<div id=\"" + layer + "\" style=\"position:absolute;left:0px;top:0px;width:100%;height:" + bodyheight + "px; background-color:#000000;filter: Alpha(Opacity=50);z-index:1;\"></div>";
            }
        }
        else {
            this.g(layer).style.display = 'block';
        }
        
        this.g('ifrBoxTitle').innerHTML = element.innerHTML;
        this.g('ifrBox').style.top = this.getY(element);
        this.g('ifrBox').style.display = 'block';
        this.g('ifrFrame').src = url;
    },
    
    closed: function(){
        this.g('ifrBox').style.display = 'none';
        this.g('ifrLayer').style.display = 'none';
    },
    
    getY: function(element){
        var y = lib.y(element);
        y = (y > 150) ? y - 150 : y / 2;
        var pos = y + 'px';
		if(this.top!=0) return this.top+'px';
        return pos;
    },
    
    set: function(id){
        var e = document.getElementById(id);
        if (e.checked == true) {
            e.checked = false;
        }
        else {
            e.checked = true;
        }
    },
	
	setAll: function(id){
        var childnodelist=document.getElementById(id).getElementsByTagName("input");	
   		for(i in childnodelist){
			e = childnodelist[i];
		    e.checked=(e.checked)?false:true;	
		}
    },
    
    //获取页面的高度信息
    getPageSize: function(){
        var xScroll, yScroll;
        if (window.innerHeight && window.scrollMaxY) {
            xScroll = document.body.scrollWidth;
            yScroll = window.innerHeight + window.scrollMaxY;
        }
        else 
            if (document.body.scrollHeight > document.body.offsetHeight) { // all but Explorer Mac
                xScroll = document.body.scrollWidth;
                yScroll = document.body.scrollHeight;
            }
            else { // Explorer Mac...would also work in Explorer 6 Strict, Mozilla and Safari
                xScroll = document.body.offsetWidth;
                yScroll = document.body.offsetHeight;
            }
        
        var windowWidth, windowHeight;
        if (self.innerHeight) { // all except Explorer
            windowWidth = self.innerWidth;
            windowHeight = self.innerHeight;
        }
        else 
            if (document.documentElement && document.documentElement.clientHeight) { // Explorer 6 Strict Mode
                windowWidth = document.documentElement.clientWidth;
                windowHeight = document.documentElement.clientHeight;
            }
            else 
                if (document.body) { // other Explorers
                    windowWidth = document.body.clientWidth;
                    windowHeight = document.body.clientHeight;
                }
        
        // for small pages with total height less then height of the viewport
        if (yScroll < windowHeight) {
            pageHeight = windowHeight;
        }
        else {
            pageHeight = yScroll;
        }
        
        if (xScroll < windowWidth) {
            pageWidth = windowWidth;
        }
        else {
            pageWidth = xScroll;
        }
        
        arrayPageSize = new Array(pageWidth, pageHeight, windowWidth, windowHeight)
        return arrayPageSize;
    }
}
