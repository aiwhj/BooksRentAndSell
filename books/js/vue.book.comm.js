var gui = require('nw.gui');
var win = gui.Window.get();
var app = gui.App;
var tray = new gui.Tray({ title: '永豪图书管理系统', icon: 'logo.png' });
tray.tooltip = '永豪图书管理系统';
$("#rent").click(function(){
	var win = gui.Window.open('rent.html', {
		position: 'center',
		width: 980,
		height: 500
	});
});
$("#sell").click(function(){
	var win = gui.Window.open('sell.html', {
		position: 'center',
		width: 980,
		height: 500
	});
});
$("#user_add").click(function(){
	var win = gui.Window.open('user_add.html', {
		position: 'center',
		width: 980,
		height: 500
	});
});
$("#book_add").click(function(){
	var win = gui.Window.open('book_add.html', {
		position: 'center',
		width: 980,
		height: 500
	});
});
$("#book_cate").click(function(){
	var win = gui.Window.open('book_cate.html', {
		position: 'center',
		width: 980,
		height: 500
	});
});
$("#user_cate").click(function(){
	var win = gui.Window.open('user_cate.html', {
		position: 'center',
		width: 980,
		height: 500
	});
});
$("#quit").click(function(){
	app.closeAllWindows();
});