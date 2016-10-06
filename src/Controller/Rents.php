<?php
class Rents {
	protected $ci;
	public function __construct($ci) {
		$this->ci = $ci;
	}
	public function Rents($request, $response, $args) {
		//获取Rents列表
		//get
		$rent=$request->getQueryParams();
		$query= $this->ci->db->table('rents');
		if(!$rent['status']) {
			$query=$query->where('rents.status','=', 1);
		} else {
			$query=$query->where('rents.status','=', 2);
		}
		if($rent['ser']) {
			if(!$rent['ser_type']) {
				$rent['ser_type']='card_id';
			}
			switch($rent['ser_type']) {
				case 'card_id':
					$data['data'] = $query
					->join('users',function($join) use ($rent){
						$join->on('rents.uid','=','users.id')
							->where('users.card_id','=', $rent['ser']);
					})
					->join('books','rents.bid','=','books.id')
					->select('rents.id','rents.time','books.isbn','rents.num','books.book_name','books.book_money','books.bcate_id','users.phone','users.user_name','users.card_id','users.ucate_id')
					->get();
				break;
				case 'user_name':
					$data['data'] = $query
					->join('users',function($join) use ($rent){
						$join->on('rents.uid','=','users.id')
							->where('users.user_name','like', '%'.$rent['ser'].'%');
					})
					->join('books','rents.bid','=','books.id')
					->select('rents.id','rents.time','books.isbn','rents.num','books.book_name','books.book_money','books.bcate_id','users.phone','users.user_name','users.card_id','users.ucate_id')
					->get();
				break;
				case 'isbn':
					$data['data'] = $query
					->join('users','rents.uid','=','users.id')
					->join('books',function($join) use ($rent){
						$join->on('rents.bid','=','books.id')
							->where('books.isbn','=', $rent['ser']);
					})
					->select('rents.id','rents.time','books.isbn','rents.num','books.book_name','books.book_money','books.bcate_id','users.phone','users.user_name','users.card_id','users.ucate_id')
					->get();
				break;
				case 'book_name':
					$data['data'] = $query
					->join('users','rents.uid','=','users.id')
					->join('books',function($join) use ($rent){
						$join->on('rents.bid','=','books.id')
							->where('books.book_name','like', '%'.$rent['ser'].'%');
					})
					->select('rents.id','rents.time','books.isbn','rents.num','books.book_name','books.book_money','books.bcate_id','users.phone','users.user_name','users.card_id','users.ucate_id')
					->get();
				break;
			}
		}
		if(!$data['data']) {
			if($rent['page']&&$rent['page']>=1) {
				$query=$query->skip(($rent['page']-1)*5);
			}
			if($data['data']=$query
				->join('users','rents.uid','=','users.id')
				->join('books','rents.bid','=','books.id')
				->select('rents.id','rents.time','books.isbn','rents.num','books.book_name','books.book_money','books.bcate_id','users.phone','users.user_name','users.card_id','users.ucate_id')
				->take(5)->get()
				) {
				$data['status']='success';
			} else {
				$data['status']='error';
				$data['data']='还没有添加数据';
			}
		} else {
			$data['status']='success';
		}
		$response->withJson($data);
	}
	public function RentGet($request, $response, $args) {
		//获取单个Rents信息
		//get
		if($data['data'] = $this->ci->db->table('rents')->where('id',$args['id'])->first()) {
			$data['status']='success';
		} else {
			$data['status']='error';
			$data['data']='还没有添加数据';
		}
		$response->withJson($data);
	}
	public function RentAdd($request, $response, $args) {
		//添加单个Rents信息
		//post
		$rent=$request->getParsedBody();
		if(!$rent['uid']) {
			$data['status']='error';
			$data['data']='没有选择会员';
		}
		if($data['status']!='error') {
			if(!$user = $this->ci->db->table('users')->where('id',$rent['uid'])->first()) {
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
		if($data['status']!='error') {
			$rent_first = $this->ci->db->table('rents')->where('uid',$rent['uid'])->where('status',1)->orderBy('time','asc')->first();
			if(((time()-$rent_first->time)>($user_cates->max_days*86400))&&($user_cates->max_days>0)) {
				$data['status']='error';
				$data['data']='此用户有超期的书未还';
			}
		}
		if($data['status']!='error') {
			$rent_count = $this->ci->db->table('rents')->where('uid',$rent['uid'])->count();
			if(($rent_count>=$user_cates->max_num)&&($user_cates->max_num>0)) {
				$data['status']='error';
				$data['data']='此用户已经借书超过'.$user_cates->max_num.'上限';
			}
		}
		if($data['status']!='error') {
			$rent_last = $this->ci->db->table('rents')->where('uid',$rent['uid'])->orderBy('time','desc')->first();
			if(((time()-$rent_last->time)<($user_cates->limit_days*86400))&&($user_cates->limit_days>0)) {
				$data['status']='error';
				$data['data']='此用户距离上次借书还没有超过'.$user_cates->limit_days.'天';
			}
		}
		if($data['status']!='error') {
			if(!$rent['books']) {
				$data['status']='error';
				$data['data']='没有选中的图书';
			} else {
				$rent['books']=json_decode($rent['books'],true);
				if(count($rent['books'])>$user_cates->limit_num&&$user_cates->limit_num>0) {
					$data['status']='error';
					$data['data']='单次借书数量不能超过'.$user_cates->limit_num.'本';
				}
			}
		}
		if($data['status']!='error') {
			$time=time();
			foreach($rent['books'] as $key=>$book) {
				$insert['uid']=$rent['uid'];
				$insert['bid']=$book['id'];
				$insert['num']=$book['num'];
				$insert['time']=$time;
				$insert['note']=$rent['note'];
				$insert['status']=1;
				if($data['status']!='error'){
					if(!$data['data'] = $this->ci->db->table('rents')->insertGetId($insert)) {
						$data['status']='error';
						$data['data']='添加失败，请重试！';
						foreach($rent['books'] as $key=>$book) {
							$data['data'] = $this->ci->db->table('rents')->where('uid',$rent['uid'])->where('bid',$book['id'])->where('time',$time)->delete();
						}
						break;
					}
				}
			}
			if($data['status']!='error') {
				$data['status']='success';
				$data['data']='租赁成功！';
			}
		}
		$response->withJson($data);
	}
	public function RentUpdate($request, $response, $args) {
		//更新单个Rents信息
		//put
		$rent=$request->getParsedBody();
		if(!$oldrent = $this->ci->db->table('rents')->where('id',$args['id'])->first()) {
				$data['status']='error';
				$data['data']='还没有此租赁记录';
		}
		if($data['status']!='error') {
			$rent['time']=$rent['time']?time():$oldrent->time;
			$rent['note']=$rent['note']?$rent['note']:$oldrent->note;
			$rent['status']=$rent['status']?$rent['status']:$oldrent->status;
		}
		if($data['status']!='error'){
			if($data['data'] = $this->ci->db->table('rents')->where('id',$args['id'])->update($rent)) {
				$data['status']='success';
				$data['data']='更新成功！';
			} else {
				$data['status']='error';
				$data['data']='更新失败，请重试！';
			}
		}
		$response->withJson($data);
	}
	public function RentsUpdate($request, $response, $args) {
		//更新单个Rents信息
		//put
		$rent_data=$request->getParsedBody();
		if(!$rent_data['ids']) {
			$data['status']='error';
			$data['data']='没有id数据';
		}
		if($data['status']!='error'){
			$ids=json_decode($rent_data['ids'],true);
			$rent=$rent_data['data'];
			foreach($ids as $id) {
				if(!$oldrent = $this->ci->db->table('rents')->where('id',$id)->first()) {
						$data['status']='error';
						$data['data']='还没有此租赁记录';
				}
				if($data['status']!='error') {
					$rent['time']=$rent['time']?time():$oldrent->time;
					$rent['note']=$rent['note']?$rent['note']:$oldrent->note;
					$rent['status']=$rent['status']?$rent['status']:$oldrent->status;
				}
				if($data['status']!='error'){
					if($data['data'] = $this->ci->db->table('rents')->where('id',$id)->update($rent)) {
						$data['status']='success';
						$data['data']='更新成功！';
					} else {
						$data['status']='error';
						$data['data']='更新失败，请重试！'.$rent['status'];
					}
				}
			}
		}

		$response->withJson($data);
	}
	public function RentDelete($request, $response, $args) {
		//删除单个Rents信息
		//delete
		if($data['data'] = $this->ci->db->table('rents')->where('id',$args['id'])->delete()) {
			$data['status']='success';
			$data['data']='删除成功！';
		} else {
			$data['status']='error';
			$data['data']='删除失败，请重试！';
		}
		$response->withJson($data);
	}
}