var str=1;
//侧栏显示与隐藏
function Menuhidden( userpath ){
	if(str==1){
		document.getElementById('main').cols='0,12,*';
		ctrlFrame.document.getElementById('icon').src="/images/admin/face/"+userpath+"/02.gif";		
		str=0;
	}else{
		document.getElementById('main').cols='200,12,*';
		ctrlFrame.document.getElementById('icon').src="/images/admin/face/"+userpath+"/01.gif";
		str=1;
	}
}

//容错处理
function clearErrors(){
    return true;
}

window.onerror = clearErrors;