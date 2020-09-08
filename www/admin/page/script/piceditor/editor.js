
function $$(sid){
	return document.getElementById(sid);
}

var piceditor = {
	//表单检查函数
	checkSubmit:function (){
		if (($$("title").value) == "") {
            window.alert('请填写标题！');
            $$("title").select();
            $$("title").focus();
            return false;
        }
	    return true;
	},

	//标识检测
	urlResult:function(val) {
		if(val==0){
			var msg="访问标识'"+$$("url").value+"'已经被使用!";
			alert(msg);
			$$("url").select();
            $$("url").focus();
		}
	},
	checkurl:function(){
		if($$("url").value=='') return;
		var id=($$('eid'))?$$('eid').value:0;
		x_checkurl( $$("url").value,$$("pageid").value,$$("mid").value,id,$$("cate").value,this.urlResult );
	},
	
	
	//界面切换函数
	changePic:function(type){
	    if (type == 1) {
			 $$("piceditor").style.display = "none";
			 $$("picfile").style.display = "";
			 $$("picsrc").style.display = "none";
	    }
	    if (type == 2) {
			 $$("piceditor").style.display = "";
			 $$("picfile").style.display = "none";
			 $$("picsrc").style.display = "none";
	    }
	    if (type == 3) {
			 $$("piceditor").style.display = "none";
			 $$("picfile").style.display = "none";
			 $$("picsrc").style.display = "";
	    }
	},
	
	//查看原图片
    view: function(sid){
		if ($$(sid).value != '') {
			var url = $$(sid).value;
			void (window.open(url + '?rand=' + Math.random(), 'showpic', 'resizable=yes,location=no,menubar=no,scrollbars=yes,status=no,toolbar=no,fullscreen=no,dependent=no,width=800,height=500,left=80,top=80'));
		}else{
			alert('No Picture!');
		}
	},
	
    //输出图片信息
    info: function(){
		var sid='picEditorFile';
        var arr0 = $$(sid).src.split('?');
        var arr = arr0[0].split('.');
        var ext = arr[arr.length - 1].toUpperCase();
        
        var meg = '图片大小：' + $$(sid).offsetWidth + 'px × ' + $$(sid).offsetHeight + 'px';
        meg += '\n图片类型：' + ext;
        meg += '\n原始位置：' + $$(sid).src;
        window.alert(meg);
    },
	
	//调用编辑器，对图片进行修改
	edit:function(){
		$$('editBox').style.display='';
		$$('picBox').style.display='none';
		
		var x=$$('pic_x').value;
		var y=$$('pic_y').value;
		var x2=$$('pic_x2').value;
		var y2=$$('pic_y2').value;

		var path=$$('pic_source_file').value;
		var wp=$$('pic_preview_width').value;
		var hp=$$('pic_preview_height').value;
		var w=$$('pic_source_width').value;
		var h=$$('pic_source_height').value;
		
		$$('picIfrEditor').src='/admin/page/script/piceditor/pic.php?wp='+wp+'&hp='+hp+'&coords='+x+','+y+','+x2+','+y2+'&wo='+w+'&ho='+h+'&path='+path;
	},
    
    //刷新当前图片显示
    refresh: function(sid){
        if ($$(sid).value != '') {
			$$('picEditorFile').src = $$(sid).value + '?rand=' + Math.random();
            $$("picEditorFile").style.display = "";
            $$("picEditorNone").style.display = "none";
        }else{
			alert('No Picture!');
		}
    },
	
	//撤消编辑
	reset:function(){
		$$('editBox').style.display='none';
		$$('picBox').style.display='';
	},
	
	//保存图片信息
	saveResult:function(val){
		$$('editBox').style.display='none';
		$$('picBox').style.display='';
		
		if(!val) return;
		$$('pic').value = val[0];
		$$('pic_source_file').value = val[1];
		piceditor.refresh('pic');
	},
	
	save:function(source_file_path){
		
		var x=$$('pic_x').value;
		var y=$$('pic_y').value;
		var x2=$$('pic_x2').value;
		var y2=$$('pic_y2').value;
		
		x_savepicture(source_file_path,x,y,x2,y2,this.saveResult);
	}
	
}