//定义字节长度函数,增加中文长度识别能力
String.prototype.getBytes = function() {   
	var cArr = this.match(/[^\x00-\xff]/ig);   
	return this.length + (cArr == null ? 0 : cArr.length);   
}

//删除头像后的执行函数
function result(id){
	document.getElementById( id ).style.display='none';
}
var reg = {
    tick: '<img src="/images/passport/msg_tick.gif" border="0"  style="float:left;margin:5px 10px 2px 0px;">',
    alert: '<img src="/images/passport/msg_alert.gif" border="0"  style="float:left;margin:5px 10px 2px 0px;">',
    nameReg: /^[A-Za-z0-9-.]+$/,
    emailReg: /^[_A-Za-z0-9-.]+@([_A-Za-z0-9-]+\.)+[A-Za-z0-9]{2,3}$/,
	username:0,//记录当前访问标识是否存在
	nickname:0,//记录当前用户名是否存在
	email:0,//记录当前邮箱地址是否存在
	msg:new Array(
		['用户名的长度为5-20位,支持中文及特殊字符!','用户名不能为空!','您输入的用户名长度不在5～20个字符之间','此用户名已经存在!'],
		['此邮箱将用于您修改密码，订阅新闻信，参与活动之用，只有通过邮箱激活后才能完成注册。','用户邮箱不能为空!','邮件地址格式不正确!','此邮件地址已经存在!'],
		['密码为6-20个字符，请使用英文字母（区分大小写）、符号或数字。', '用户密码不能为空!','两次输入的密码不相同，请确认输入!'],
		['访问标识设置后不能修改，长度为5-20位,支持英文字符，英文点号，英文中划线，英文下划线和数字!','您输入的访问标识长度不在5～20个字符之间!','此访问标识已经存在!']
	),
    g: function(id){
        return document.getElementById(id);
    },
    checkSubmit: function(){
        if (this.g('username').value != '' && !this.nameReg.test(this.g('username').value) ) {
            alert(this.msg[3][0]);
            this.g('username').focus();
            this.g('username').select();
            return false;
        }else if ( this.g('username').value != '' && (this.g('username').value.length < 5 || this.g('username').value.length > 20)) {
            alert(this.msg[3][1]);
            this.g('username').focus();
            this.g('username').select();
            return false;
        }else if( this.g('username').value != '' &&this.username==1){
			alert(this.msg[3][2]);
			return false;
		}else if (this.g('nickname').value == '') {
            alert(this.msg[0][1]);
            this.g('nickname').focus();
            this.g('nickname').select();
            return false;
        }else if ( this.g('nickname').value.length < 5 || this.g('nickname').value.length > 20 ) {
            alert(this.msg[0][2]);
            this.g('nickname').focus();
            this.g('nickname').select();
            return false;
        }else if(this.nickname==1){
			alert(this.msg[0][3]);
			return false;
		}else if (this.g('email').value == '') {
            alert(this.msg[1][1]);
            this.g('email').focus();
            this.g('email').select();
            return false;
        }else if(!this.emailReg.test(this.g('email').value)) {
            alert(this.msg[1][2]);
            this.g('email').focus();
            this.g('email').select();
            return false;
        }else if(this.email==1){
			alert(this.msg[1][3]);
			return false;
		}else if(this.g('password').value==''){
			alert(this.msg[2][1]);
			this.g('password').focus();
            this.g('password').select();
			return false;
		}else if(this.g('password').value.getBytes() < 6||this.g('password').value.getBytes()>20||!this.underRule(this.g('password').value)){
			alert(this.msg[2][0]);
			this.g('password').focus();
            this.g('password').select();
			return false;
		}else{
            return true;
        }
    },
  //用户名
    checkNickName: function(value){
 	   var html='';
 		if(this.g('nickname').value==''){
 			html = reg.alert+'<span style="float:left;color:red;">'+reg.msg[0][1]+'</span>';
 			$("#nicknamemsg").html(html);
 			return;
 		}
 		if(this.g('nickname').value.getBytes()<5||this.g('nickname').value.getBytes()>20){
 			html=reg.alert+'<span style="float:left;color:red;">'+reg.msg[0][2]+'</span>';
 			$("#nicknamemsg").html(html);
 			return;
 		}
 		
 		$.ajax({
  		   type: "POST",
  		   url: "/include/plugins/jquery.php?app=passport,checkreg&func=checkNickName",
  	  	   data: { 
  	  	    	nickname: encodeURIComponent($("#nickname").val()),
  	  	    	uid: 0
  	  	    },
  		   success:function( val ){ 
  			   eval(val); 
  			   if(res==1){
  					reg.nickname=0;
  					html=reg.tick+reg.msg[0][0];
  				}else{
  					reg.nickname=1;
  					html=reg.alert+'<span style="color:red;">'+reg.msg[0][3]+'</span>';
  				}
  		      $("#nicknamemsg").html(html);
  		    }
 	 	});
     },
 	resetNickName:function(){
 		$("#nicknamemsg").html(this.msg[0][0]);
 	},
    
 	//邮箱地址
    checkEmail: function(){
       var html='';
       if(this.g('email').value==''){
 			html=reg.alert+'<span style="color:red;">'+reg.msg[1][1]+'</span>';
 			$("#emailmsg").html(html);
 			return;
 		}
 		if (!this.emailReg.test(this.g('email').value)) {
 			html=reg.alert+'<span style="color:red;">'+reg.msg[1][2]+'</span>';
 			$("#emailmsg").html(html);
 			return;
 		}
 		
 		$.ajax({
  		   type: "POST",
  		   url: "/include/plugins/jquery.php?app=passport,checkreg&func=checkEmail",
  	  	   data: { 
  	  	    	email: encodeURIComponent($("#email").val()),
  	  	    	uid: 0
  	  	    },
  		   success:function( val ){ 
  			   eval(val); 
  			   if(res==1){
  				   reg.email=0;
  					html=reg.tick+reg.msg[1][0];
  				}else{
  					reg.email=1;
  					html=reg.alert+'<span style="color:red;">'+reg.msg[1][3]+'</span>';
  				}
  			   $("#emailmsg").html(html);
  		    }
 	 	});
     },
 	resetEmail:function(){
 		$("#emailmsg").html(this.msg[1][0]);
 	},
	
 	//密码
	checkPasswd:function(value){
		var tmp = new Array(0,0,0,0,0);
		var msg=new Array([30,"#F63","弱"],[30,"#F63","弱"],[60,"#36F","中"],[90,"#090","强"],[120,"#060","很强"],[150,"#039","极强"]);
		
		if(value.length>5) tmp[0]=1;
		for (i=0;i<value.length;i++) tmp[this.charType(value.charCodeAt(i))]=1;
		
		var level=0;
		for(var i in tmp) level+=tmp[i];
		
		$("#levellen").css('background',msg[level][1]);
		$("#levellen").css('width',msg[level][0] + 'px');
		$("#levelmsg").html( msg[level][2] );
	},
	
	charType:function(c){
		if(c>=48 && c<=57) return 1;//数字		
		if(c>=65 && c<=90) return 2;//大写字母		
		if(c>=97 && c<=122) return 3;//小写字母		
		return 4;//其余为特殊字符
	},
	
	resetPasswd:function(){
		$("#pwdmsg").html(this.msg[2][0]);
	},
	
	underRule:function (str){
		for(i=0;i<str.length;i++) {
			ecStr=str.substr(i,1);
			ascStr=ecStr.charCodeAt()
			if(ascStr>127) { 
				return false;
			}
     	}
		return true
	} 
};
