var label = {
	g: function(id){
        return document.getElementById(id);
    },
	
	/**
	 * 初始化多级联动标签
	 * 
	 * @param {Object} val 初始时的键值，以all开头，如 "all.67.149"
	 * @param {Object} id  标签调用ID，如 "region"或 "67"
	 * @param {Object} box  标签呈现容器，如 "regionbox"
	 */
	init:function(val,id,box){
		//创建初始容器
		var p=label.g(box);
		var el=document.createElement("div");
		el.id=id+'div0';
		p.appendChild(el);
		
		$.ajax({
			type: "GET",
		    url: "/include/plugins/jquery.php",
	  	    data: "app=label,ajax&sess=true&func=initLabel&args[]="+ val+'&args[]='+ id,
		    success:function( val ){eval(val); label.initHTML(res); }
		});
	},
	
	initHTML:function(result){
		if( result==0 ) return;
		
		var id=result[0];
		for(var i in result[1]){
			label.write(result[1][i],i,id);
		}
	},
	
	write:function(result,i,id){
		var item=result[0];
		var itemkey=result[1];
		
		//当前项目序号，i是上级项目序号
		var n=parseInt(i)+1;
		
		//创建容器
		var p=label.g(id+'div'+i);
		var el=document.createElement("div");
		el.id=id+'div'+n;
		p.appendChild(el);
		label.g(id+'div'+n).setAttribute("style", "float:left;margin-right:8px;");
		
		//创建菜单
		var box=label.g(id+'div'+n);
		var select=document.createElement("select");
		select.name=id+"[]";
		select.id=id+'select'+n;
		select.options[select.options.length]=new Option('请选择','');
		for (var j in item) {
			select.options[select.options.length]=new Option(item[j][1],item[j][0],false,item[j][2]);
		}
		box.appendChild(select);
		label.g(id+'select'+n).setAttribute("style", "float:left;margin-right:8px;");
		label.g(id+'select'+n).setAttribute("onchange", "label.getnext(this.value,'"+id+"',"+n+",'"+itemkey+"');");
	},
	
	getnext:function(val,id,n,itemkey){
		//删除下级容器
		var obj=label.g(id+'select'+n).parentNode;
		var num=n+1;
		if(label.g(id+'div'+num)) obj.removeChild(label.g(id+'div'+num));
		
		//空选无效
		if(val=='') return;
		
		//重新初始下级容器
		$.ajax({
			type: "GET",
		    url: "/include/plugins/jquery.php",
	  	    data: "app=label,ajax&sess=true&func=getLabel&args[]="+ val+'&args[]='+ id+'&args[]='+ n +'&args[]='+ itemkey,
		    success:function( val ){eval(val); label.setnext(res); }
		});
	},
	
	setnext:function(result){
		if( result==0 ) return;
		
		var id=result[0];
		var item=result[1];
		var i=result[2];
		
		label.write(item,i,id);
	}
};