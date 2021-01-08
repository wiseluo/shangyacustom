$(document).find('.submit_btn').click(function(){
	let house_id = $('.house_id').val()
	let admin_ids = ''
	$('.layout_table tbody tr').each(function(item,i){
		if($(this).hasClass('selected')){
			if( admin_ids == ''){
				admin_ids += $(this).find('td').eq(1).text()
			} else {
				admin_ids += ',' + $(this).find('td').eq(1).text()
			}
		}
	})
	relationAdmin(house_id,  admin_ids)
})
function relationAdmin(house_id, admin_ids) {
	$.ajax({
		type: "post",
		url: "houseadmin/save",
		data: {"house_id": house_id,'admin_ids': admin_ids},
		dataType: "json",
		async: false,
		success: function (res) {
			//从服务器获取数据进行绑定
			console.log(res)
			if(res.code == 200) {
				parent.location.reload()
			}
			layer.msg(res.msg)

		},
		error: function () { alert("Error"); }
	});
}
