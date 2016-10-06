<?php
$app->add(new AllRestful());
//BookCates操作
$app->get('/bookcates/bookcates','\BookCates:BookCates')->setName('BookCates');
$app->get('/bookcates/bookcate/{id}','\BookCates:BookCateGet')->setName('BookCateGet');
$app->post('/bookcates/bookcate', '\BookCates:CateAdd')->setName('BookCateAdd');
$app->put('/bookcates/bookcate/{id}', '\BookCates:BookCatesUpdate')->setName('BookCatesUpdate');
$app->delete('/bookcates/bookcates', '\BookCates:BookCatesDelete')->setName('BookCatesDelete');
//Books操作
$app->get('/books/books', '\Books:Books')->setName('Books');
$app->get('/books/book/{id}', '\Books:BookGet')->setName('BookGet');
$app->post('/books/book', '\Books:BookAdd')->setName('BookAdd');
$app->put('/books/book/{id}', '\Books:BookUpdate')->setName('BookUpdate');
$app->delete('/books/book/{id}', '\Books:BookDelete')->setName('BookDelete');
//UserCates操作
$app->get('/usercates/usercates', '\UserCates:UserCates')->setName('UserCates');
$app->get('/usercates/usercate/{id}', '\UserCates:UserCateGet')->setName('UserCateGet');
$app->post('/usercates/usercate', '\UserCates:CateAdd')->setName('UserCateAdd');
$app->put('/usercates/usercate/{id}', '\UserCates:UserCatesUpdate')->setName('UserCatesUpdate');
$app->delete('/usercates/usercates', '\UserCates:UserCatesDelete')->setName('UserCatesDelete');
//Users操作
$app->get('/users/users', '\Users:Users')->setName('Users');
$app->get('/users/user/{id}', '\Users:UserGet')->setName('UserGet');
$app->post('/users/user', '\Users:UserAdd')->setName('UserAdd');
$app->put('/users/user/{id}', '\Users:UserUpdate')->setName('UserUpdate');
$app->delete('/users/users', '\Users:UsersDelete')->setName('UsersDelete');
//Rents操作
$app->get('/rents/rents', '\Rents:Rents')->setName('Rents');
$app->get('/rents/rent/{id}', '\Rents:RentGet')->setName('RentGet');
$app->post('/rents/rent', '\Rents:RentAdd')->setName('RentAdd');
$app->put('/rents/rent/{id}', '\Rents:RentUpdate')->setName('RentUpdate');
$app->put('/rents/rents', '\Rents:RentsUpdate')->setName('RentsUpdate');
$app->delete('/rents/rent/{id}', '\Rents:RentDelete')->setName('RentDelete');
//Sells操作
$app->get('/sells/sells', '\Sells:Sells')->setName('Sells');
$app->get('/sells/sell/{id}', '\Sells:SellGet')->setName('SellGet');
$app->post('/sells/sell', '\Sells:SellAdd')->setName('SellAdd');
$app->put('/sells/sell/{id}', '\Sells:SellUpdate')->setName('SellUpdate');
$app->delete('/sells/sell/{id}', '\Sells:SellDelete')->setName('SellDelete');
//数据报表
$app->get('/datas/datas', '\Datas:Datas')->setName('Datas');