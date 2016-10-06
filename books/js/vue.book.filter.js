$.ajax({
	url: 'http://myslim.whj.com/bookcates/bookcates',
	type: 'GET',
	data: {
		type:'filter'
	},
	success: function(data) {
		if(JSON.parse(data).status=='success') {
			if(JSON.parse(data).data=='') {
				alert("暂时没有数据");
			} else {
				book_cates_filter=JSON.parse(data).data;
				console.log(book_cates_filter);
			}
		} else {
			alert("数据出错");
		}
	}
})
$.ajax({
	url: 'http://myslim.whj.com/usercates/usercates',
	type: 'GET',
	data: {
		type:'filter'
	},
	success: function(data) {
		if(JSON.parse(data).status=='success') {
			if(JSON.parse(data).data=='') {
				alert("暂时没有数据");
			} else {
				user_cates_filter=JSON.parse(data).data;
				console.log(user_cates_filter);
			}
		} else {
			alert("数据出错");
		}
	}
})
Vue.filter('todate', function (value) {    
   return new Date(parseInt(value) * 1000).toLocaleString().replace(/:\d{1,2}$/,' ');
})
Vue.filter('sell_user_name', function (value) {    
   return value?value:"零售";
})
Vue.filter('sell_card_id', function (value) {    
   return value?value:"零售";
})
Vue.filter('sell_type', function (value) {    
   return value==1?"会员":"零售";
})
Vue.filter('pay_type', function (value) {    
	//1现金2余额3微信4支付宝
	var pay_type=new Array("未知","现金","余额","微信","支付宝");
	return pay_type[value];
})
Vue.filter('tobookcate', function (value) {    
	return book_cates_filter[value];
})
Vue.filter('tousercate', function (value) {    
	return user_cates_filter[value];
})