<?php
class Sells {
	protected $ci;
	public function __construct($ci) {
       $this->ci = $ci;
	}
	public function Sells($request, $response, $args) {
		//获取Sells列表
		//get
		$sell=$request->getQueryParams();
		if(($sell['ser_type']=='isbn'||$sell['ser_type']=='book_name')&&$sell['ser']!='') {
			$query= $this->ci->db->table('sells_all');
			if($sell['ser_type']=='isbn') {
				$query=$query->join('books',function($join) use ($sell){
								$join->on('sells_all.bid','=','books.id')
									->where('books.isbn','=', $sell['ser']);
								});
			} else {
				$query=$query->join('books',function($join) use ($sell){
								$join->on('sells_all.bid','=','books.id')
									->where('books.book_name','like', '%'.$sell['ser'].'%');
								});
			}
			$books=$query
			->select('sells_all.sid','sells_all.num','sells_all.money','sells_all.zhekou_money','books.isbn','books.book_name','books.book_money','books.bcate_id')
			->get();
			foreach($books as $k=>$book) {
				$data['data'][$k]=$this->ci->db->table('sells')
				->where('sells.id',$book->sid)
				->join('users','sells.uid','=','users.id')
				->select('sells.*','users.user_name','users.card_id')
				->first();
				$whj[]=$book;
				$data['data'][$k]->data=$whj;
				unset($whj);
			}
		} else {
			$query= $this->ci->db->table('sells');
			if($sell['ser_type']=='card_id'&&$sell['ser']!='') {
				$query=$query->leftJoin('users',function($join) use ($sell){
								$join->on('sells.uid','=','users.id')
									->where('users.card_id','=', $sell['ser']);
								});
			} elseif($sell['ser_type']=='user_name'&&$sell['ser']!='') {
				$query=$query->leftJoin('users',function($join) use ($sell){
								$join->on('sells.uid','=','users.id')
									->where('users.user_name','like', '%'.$sell['ser'].'%');
								});
			} else {
				$query=$query->leftJoin('users','sells.uid','=','users.id');
			}
			$query=$query->select('sells.*','users.user_name','users.card_id');
			if($sell['page']&&$sell['page']>=1) {
				$query=$query->skip(($sell['page']-1)*5);
			}
			$data['data']=$query->take(5)->get();
			foreach($data['data'] as $k=>$sell_all) {
				$data['data'][$k]->data=$this->ci->db->table('sells_all')
				->where('sid',$sell_all->id)
				->join('books','sells_all.bid','=','books.id')
				->select('sells_all.sid','sells_all.num','sells_all.money','sells_all.zhekou_money','books.isbn','books.book_name','books.book_money','books.bcate_id')
				->get();
			}
		}
		if(!$data['data']) {
				$data['status']='error';
				$data['data']='还没有添加数据';
		} else {
			$data['status']='success';
		}
		$response->withJson($data);
	}
	public function SellGet($request, $response, $args) {
		//获取单个Sells信息
		//get
		if($data['data'] = $this->ci->db->table('sells')->where('id',$args['id'])->first()) {
			$data['status']='success';
		} else {
			$data['status']='error';
			$data['data']='还没有添加数据';
		}
		$response->withJson($data);
	}
	public function SellAdd($request, $response, $args) {
		//添加单个Sells信息
		//post
		$sell=$request->getParsedBody();
		if(!$sell['type']) {
			$sell['type']=1; //会员
		}
		if($sell['type']==1) { //会员操作
			if(!$sell['uid']) {
				$data['status']='error';
				$data['data']='没有选择会员';
			}
			if($data['status']!='error') {
				if(!$user = $this->ci->db->table('users')->where('id',$sell['uid'])->first()) {
					$data['status']='error';
					$data['data']='还没有此会员信息';
				}
			}
			if($data['status']!='error') {
				if(!$user_cates = $this->ci->db->table('user_cates')->where('id',$user->ucate_id)->first()) {
					$data['status']='error';
					$data['data']='还没有此卡类型';
				}
			}
		}
		if($data['status']!='error') {
			if(!$sell['books']) {
				$data['status']='error';
				$data['data']='没有选中的图书';
			} else {
				$sell['books']=json_decode($sell['books'],true);
			}
		}
		if($data['status']!='error') {
			$time=time();
			$insert['uid']=$sell['type']==1?$sell['uid']:0;
			$insert['time']=$time;
			$insert['note']=$sell['note']?$sell['note']:'销售';
			$insert['type']=$sell['type'];
			$insert['status']=1;
			if($data['status']!='error'){
				if(!$sid = $this->ci->db->table('sells')->insertGetId($insert)) {
					$data['status']='error';
					$data['data']='添加失败，请重试！';
				}
			}
			if($data['status']!='error'){
				$money=0;
				foreach($sell['books'] as $key=>$book) {
					$insert_all['uid']=$sell['type']==1?$sell['uid']:0;
					$insert_all['num']=$book['num'];
					$insert_all['sid']=$sid;
					if(!$insert_all['bid']=$book['id']) {
						$data['status']='error';
						$data['data']='没有选择这本书。。';
						break;
					} else {
						if(!$book_info = $this->ci->db->table('books')->where('id',$book['id'])->first()) {
							$data['status']='error';
							$data['data']='书库中没有这本书。。';
							break;
						}
					}
					$insert_all['time']=$time;
					$insert_all['money']=$book_info->book_money;
					$insert_all['zhekou_money']=$sell['type']==1?$user_cates->zhekou/100.00*$book_info->book_money:$book_info->book_money; //会员打折
					if($data['status']!='error'){
						if(!$data['data'] = $this->ci->db->table('sells_all')->insertGetId($insert_all)) {
							$data['status']='error';
							$data['data']='添加失败，请重试！';
							break;
						}
					}
					$z_money+=$book_info->book_money*$book['num'];
					$z_zhekou_money+=($sell['type']==1?$user_cates->zhekou/100.00*$book_info->book_money:$book_info->book_money)*$book['num'];
				}
			}
			if($data['status']!='error'){
				$update['z_money']=$z_money;
				$update['status']=2;
				$update['z_zhekou_money']=$z_zhekou_money;
				$update['z_real_money']=$sell['z_real_money']?$sell['z_real_money']:$z_zhekou_money;
				if(!$data['data'] = $this->ci->db->table('sells')->where('id',$sid)->update($update)) {
					$data['status']='error';
					$data['data']='更新失败，请重试！';
				}
			}
			if($data['status']!='error') {
				$data['status']='success';
				$data['data']='租赁成功！';
			} else {
				foreach($sell['books'] as $key=>$book) {
					$this->ci->db->table('sells_all')->where('uid',$sell['type']==1?$sell['uid']:0)->where('bid',$book['id'])->where('time',$time)->delete();
				}
				if($sid) {
					$this->ci->db->table('sells')->where('id',$sid)->delete();
				}
			}
		}
		$response->withJson($data);
	}
	public function SellUpdate($request, $response, $args) {
		//更新单个Sells信息
		//put
		if(!$oldsell = $this->ci->db->table('sells')->where('id',$args['id'])->first()) {
				$data['status']='error';
				$data['data']='还没有此租赁记录';
		}
		$sell=$request->getParsedBody();
		if($data['status']!='error') {
			$sell['time']=$sell['time']?time():$oldsell->time;
			$sell['note']=$sell['note']?$sell['note']:$oldsell->note;
			$sell['status']=$sell['status']?$sell['status']:$oldsell->status;
		}
		if($data['status']!='error'){
			if($data['data'] = $this->ci->db->table('sells')->where('id',$args['id'])->update($sell)) {
				$data['status']='success';
				$data['data']='更新成功！';
			} else {
				$data['status']='error';
				$data['data']='更新失败，请重试！';
			}
		}
		$response->withJson($data);
	}
	public function SellDelete($request, $response, $args) {
		//删除单个Sells信息
		//delete
		if($data['data'] = $this->ci->db->table('sells')->where('id',$args['id'])->delete()) {
			$data['status']='success';
			$data['data']='删除成功！';
		} else {
			$data['status']='error';
			$data['data']='删除失败，请重试！';
		}
		$response->withJson($data);
	}
}