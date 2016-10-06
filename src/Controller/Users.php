<?php
class Users {
	protected $ci;
	public function __construct($ci) {
       $this->ci = $ci;
	}
	public function Users($request, $response, $args) {
		//获取Users列表
		//get
		$user=$request->getQueryParams();
		if(!$user['take']) {
			$take=5;
		} else {
			$take=$user['take'];
		}
		$query= $this->ci->db->table('users');
		if(!$user['status']) {
			$query=$query->where('users.status','=', 1);
		} else {
			$query=$query->where('users.status','=', 2);
		}
		if($user['ser']) {
			if(!$user['ser_type']) {
				$user['ser_type']='card_id';
			}
			switch($user['ser_type']) {
				case 'card_id':
					$data['data'] = $query
					->where('users.card_id','=', $user['ser'])
					->select('users.*')
					->get();
				break;
				case 'user_name':
					$data['data'] = $query
					->where('users.user_name','like', '%'.$user['ser'].'%')
					->select('users.*')
					->get();
				break;
				case 'phone':
					$data['data'] = $query
					->where('users.phone','like', '%'.$user['ser'].'%')
					->select('users.*')
					->get();
				break;
				case 'idcard':
					$data['data'] = $query
					->where('users.idcard','like', '%'.$user['ser'].'%')
					->select('users.*')
					->get();
				break;
			}
		}
		if(!$data['data']) {
			if($user['page']&&$user['page']>=1) {
				$query=$query->skip(($user['page']-1)*5);
			}
			if($data['data']=$query
				->select('users.*')
				->take($take)->get()
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
	public function UserGet($request, $response, $args) {
		//获取单个Users信息
		//get
		if($data['data'] = $this->ci->db->table('users')->where('id',$args['id'])->first()) {
			$data['status']='success';
		} else {
			$data['status']='error';
			$data['data']='还没有添加数据';
		}
		$response->withJson($data);
	}
	public function UserAdd($request, $response, $args) {
		//添加单个Users信息
		//post
		$user=$request->getParsedBody();
		if(!$user['user_name']) {
			$data['status']='error';
			$data['data']='请输入会员姓名';
		}
		if($data['status']!='error') {
			if(!$user['card_id']) {
				$data['status']='error';
				$data['data']='请输入会员卡号';
			}
		}
		if($data['status']!='error') {
			if(!$user['ucate_id']) {
				$data['status']='error';
				$data['data']='请选择办卡类型';
			}
		}
		if($data['status']!='error') {
			if(!$user_cates = $this->ci->db->table('user_cates')->where('id',$user['ucate_id'])->first()) {
				$data['status']='error';
				$data['data']='还没有此卡类型';
			}
		}
		if($data['status']!='error') {
			$user['user_money']=$user['user_money']?$user['user_money']:$user_cates->yuchun;				
			$user['zengsong']=$user['zengsong']?$user['zengsong']:$user_cates->zengsong;				
			$user['limit_days']=$user['limit_days']?$user['limit_days']:$user_cates->limit_days;				
			$user['banka_money']=$user['banka_money']?$user['banka_money']:$user_cates->zengsong+$user_cates->yuchun;
			$user['join_time']=time();			
			$user['status']=1;			
		}
		if($data['status']!='error'){
			if($data['data'] = $this->ci->db->table('users')->insertGetId($user)) {
				$data['status']='success';
				$data['data']='添加成功！';
			} else {
				$data['status']='error';
				$data['data']='添加失败，请重试！';
			}
		}
		$response->withJson($data);
	}
	public function UserUpdate($request, $response, $args) {
		//更新单个Users信息
		//put
		if(!$olduser = $this->ci->db->table('users')->where('id',$args['id'])->first()) {
				$data['status']='error';
				$data['data']='还没有此会员';
		}
		$user=$request->getParsedBody();
		if($data['status']!='error') {
			$user['ucate_id']=$user['ucate_id']?$user['ucate_id']:$olduser->ucate_id;
			if(!$user_cates = $this->ci->db->table('user_cates')->where('id',$user['ucate_id'])->first()) {
				$data['status']='error';
				$data['data']='还没有此卡类型';
			}
		}
		if($data['status']!='error') {
			$user['user_name']=$user['user_name']?$user['user_name']:$olduser->user_name;
			$user['card_id']=$user['card_id']?$user['card_id']:$olduser->card_id;
			$user['ucate_id']=$user['ucate_id']?$user['ucate_id']:$olduser->ucate_id;
		}
		if($data['status']!='error'){
			if($data['data'] = $this->ci->db->table('users')->where('id',$args['id'])->update($user)) {
				$data['status']='success';
				$data['data']='更新成功！';
			} else {
				$data['status']='error';
				$data['data']='更新失败，请重试！';
			}
		}
		$response->withJson($data);
	}
	public function UserDelete($request, $response, $args) {
		//删除单个Users信息
		//delete
		if($data['data'] = $this->ci->db->table('users')->where('id',$args['id'])->delete()) {
			$data['status']='success';
			$data['data']='删除成功！';
		} else {
			$data['status']='error';
			$data['data']='删除失败，请重试！';
		}
		$response->withJson($data);
	}
}