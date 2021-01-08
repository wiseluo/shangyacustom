$(document).find('.submit_btn').click(function(){
	let house_id = $('.house_id').val()
	let layout_ids = ''
	$('.layout_table tbody tr').each(function(item,i){
		if($(this).hasClass('selected')){
			if(layout_ids == ''){
				layout_ids += $(this).find('td').eq(1).text()
			} else {
				layout_ids += ',' + $(this).find('td').eq(1).text()
			}
		}
	})
	relationLayout(house_id, layout_ids)
})
function relationLayout(house_id,layout_ids) {
	$.ajax({
		type: "post",
		url: "houselayout/save",
		data: {"house_id": house_id,'layout_ids': layout_ids},
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
