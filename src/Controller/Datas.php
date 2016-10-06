<?php
class Datas {
	protected $ci;
	public function __construct($ci) {
       $this->ci = $ci;
	}
	public function Datas($request, $response, $args) {
		//获取GetData数据
		//get
		$argv=$request->getQueryParams();
		$sells=$this->ci->db->table('sells');
		$rents=$this->ci->db->table('rents');
		$users=$this->ci->db->table('users');
		$books=$this->ci->db->table('books');
		$data['data']['sell_money']= $sells->sum("z_real_money");
		$data['data']['sell_count']= $sells->count("id");
		$data['data']['rent_num']= $rents->sum("num");
		$data['data']['rent_count']= $rents->count("id");
		$data['data']['user_money']= $users->sum("user_money");
		$data['data']['user_count']= $users->count("id");
		$data['data']['book_num']= $books->sum("num");
		$data['data']['book_count']= $books->count("id");
		$num=$argv['num']+1;
		while($num--) {
			$i=$argv['num']-$num;
			$time=gettime('month',$i);
			$data['data']['sell']['month']['sell_money'][]=$sells->where('time','>',$time['start'])->where('time','<',$time['end'])->sum("z_real_money");
			$data['data']['sell']['month']['sell_count'][]=$sells->where('time','>',$time['start'])->where('time','<',$time['end'])->count("id");
			$data['data']['rent']['month']['rent_num'][]=$rents->where('time','>',$time['start'])->where('time','<',$time['end'])->sum("num");
			$data['data']['rent']['month']['rent_count'][]=$rents->where('time','>',$time['start'])->where('time','<',$time['end'])->count("id");
			$data['data']['user']['month']['user_money'][]=$users->where('join_time','>',$time['start'])->where('join_time','<',$time['end'])->sum("user_money");
			$data['data']['user']['month']['user_count'][]=$users->where('join_time','>',$time['start'])->where('join_time','<',$time['end'])->count("id");
			$data['data']['book']['month']['book_num'][]=$books->where('in_time','>',$time['start'])->where('in_time','<',$time['end'])->sum("num");
			$data['data']['book']['month']['book_count'][]=$books->where('in_time','>',$time['start'])->where('in_time','<',$time['end'])->count("id");
			$time=gettime('day',$i);
			$data['data']['sell']['day']['sell_money'][]=$sells->where('time','>',$time['start'])->where('time','<',$time['end'])->sum("z_real_money");
			$data['data']['sell']['day']['sell_count'][]=$sells->where('time','>',$time['start'])->where('time','<',$time['end'])->count("id");
			$data['data']['rent']['day']['rent_num'][]=$rents->where('time','>',$time['start'])->where('time','<',$time['end'])->sum("num");
			$data['data']['rent']['day']['rent_count'][]=$rents->where('time','>',$time['start'])->where('time','<',$time['end'])->count("id");
			$data['data']['user']['day']['user_money'][]=$users->where('join_time','>',$time['start'])->where('join_time','<',$time['end'])->sum("user_money");
			$data['data']['user']['day']['user_count'][]=$users->where('join_time','>',$time['start'])->where('join_time','<',$time['end'])->count("id");
			$data['data']['book']['day']['book_num'][]=$books->where('in_time','>',$time['start'])->where('in_time','<',$time['end'])->sum("num");
			$data['data']['book']['day']['book_count'][]=$books->where('in_time','>',$time['start'])->where('in_time','<',$time['end'])->count("id");
			$time=gettime('year',$i);
			$data['data']['sell']['year']['sell_money'][]=$sells->where('time','>',$time['start'])->where('time','<',$time['end'])->sum("z_real_money");
			$data['data']['sell']['year']['sell_count'][]=$sells->where('time','>',$time['start'])->where('time','<',$time['end'])->count("id");
			$data['data']['rent']['year']['rent_num'][]=$rents->where('time','>',$time['start'])->where('time','<',$time['end'])->sum("num");
			$data['data']['rent']['year']['rent_count'][]=$rents->where('time','>',$time['start'])->where('time','<',$time['end'])->count("id");
			$data['data']['user']['year']['user_money'][]=$users->where('join_time','>',$time['start'])->where('join_time','<',$time['end'])->sum("user_money");
			$data['data']['user']['year']['user_count'][]=$users->where('join_time','>',$time['start'])->where('join_time','<',$time['end'])->count("id");
			$data['data']['book']['year']['book_num'][]=$books->where('in_time','>',$time['start'])->where('in_time','<',$time['end'])->sum("num");
			$data['data']['book']['year']['book_count'][]=$books->where('in_time','>',$time['start'])->where('in_time','<',$time['end'])->count("id");
			}
		$data['status']='success';
		$response->withJson($data);
	}
	public function MonthDatas($request, $response, $args ) {
		
	}
}