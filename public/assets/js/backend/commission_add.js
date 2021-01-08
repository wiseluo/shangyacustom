function GetChildUserValue(name, id){
	$('#c-user_name').val(name)
	$('#c-user_id').val(id)
}
function GetChildCustomerValue(name, id, housename,houseid){
	$('#c-customer_name').val(name)
	$('#c-customer_id').val(id)
	$('#c-house_name').val(housename)
	$('#c-house_id').val(houseid)
}

$('#c-house_price').keyup(function(){
	if($(this).val() != 0 && $('#c-commission_rate').val() != 0){
		let a = +$(this).val()
				b = +$('#c-commission_rate').val()
				console.log(a)
				console.log(b)
		$('#c-commission').val(count(a,b))
	}
})

$('#c-commission_rate').keyup(function(){
	if($(this).val() != 0 && $('#c-house_price').val() != 0){
		let a = +$(this).val()
				b = +$('#c-house_price').val()
		$('#c-commission').val(count(a,b))
	}
})

function count(a,b){
	return a * b / 100
}