function getQueryString(name) {  
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");  
    var r = window.location.search.substr(1).match(reg);  
    if (r != null) return decodeURI(r[2]);
    return null;  
}
var client_ids = getQueryString('client_ids');
$(document).find('.submit_btn').click(function(){
	let user_id = ''
	if($('.layout_table tbody tr.selected').length != 1){
		layer.msg('请选择一个销售员')
		return false
	} else {
		user_id = $('.layout_table tbody tr.selected').find('td').eq(1).text()
	}
	assignsalesman(user_id)
})
function assignsalesman(user_id) {
	$.ajax({
		type: "post",
		url: "sale/client/assignsalesman",
		data: {"client_ids": client_ids,'user_id': user_id},
		dataType: "json",
		async: false,
		success: function (res) {
			//从服务器获取数据进行绑定
			console.log(res)
			layer.msg(res.msg)
			if(res.code == 200) {
				parent.location.reload()
			}

		},
		error: function () { alert("Error"); }
	});
}
