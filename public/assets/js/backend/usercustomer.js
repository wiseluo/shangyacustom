$('.submit_btn').click(function(){
	let customername = ''
	let customerid = ''
	let housename = ''
	let houseid = ''
	if($('.table tbody tr.selected').length != 1){
		layer.msg('请选择一个客户')
		return false
	} else {
		customername = $('.table tbody tr.selected').find('td').eq(2).text()
		customerid = $('.table tbody tr.selected').find('td').eq(1).text()
		housename = $('.table tbody tr.selected').find('td').eq(4).text()
		houseid = $('.table tbody tr.selected').find('td').eq(6).text()
	}
	parent.GetChildCustomerValue(customername,customerid,housename,houseid);
	Fast.api.close()
})
