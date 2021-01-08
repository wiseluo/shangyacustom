$('.submit_btn').click(function(){
	let username = ''
	let userid = ''
	if($('.table tbody tr.selected').length != 1){
		layer.msg('请选择一个推介人')
		return false
	} else {
		username = $('.table tbody tr.selected').find('td').eq(2).text()
		userid = $('.table tbody tr.selected').find('td').eq(1).text()
	}
	parent.GetChildUserValue(username,userid);
	Fast.api.close()
})