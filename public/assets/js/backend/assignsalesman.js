function getQueryString(name) {  
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");  
    var r = window.location.search.substr(1).match(reg);  
    if (r != null) return decodeURI(r[2]);
    return null;  
}
var customer_ids = getQueryString('customer_ids');
$(document).find('.submit_btn').click(function(){
	let adviser_id = ''
	if($('.layout_table tbody tr.selected').length != 1){
		layer.msg('请选择一个销售员')
		return false
	} else {
		adviser_id = $('.layout_table tbody tr.selected').find('td').eq(1).text()
	}
	assignsalesman(adviser_id)
})
function assignsalesman(adviser_id) {
	$.ajax({
		type: "post",
		url: "customer/assignsalesman",
		data: {"customer_ids": customer_ids,'adviser_id': adviser_id},
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
