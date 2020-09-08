function topfunc() {
	alert('已经是第一行了');
}
function bottomfunc() {
	alert('已经是最后一行了');
}
function orderUp() {
	var arr = a();
	if (!arr)
		return;
	tbOrder.up(arr[0], arr[1]);
}
function orderDown() {
	var arr = a();
	if (!arr)
		return;
	tbOrder.down(arr[0], arr[1]);
}
function a() {
	if (!temptr) {
		alert('请选择要处理的项目！');
		return false;
	}
	arr = temptr.split('_');
	if (g('item_id' + arr[1]).checked == false) {
		alert('请选择要处理的项目！');
		return false;
	}
	return arr;
}