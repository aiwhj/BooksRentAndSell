<?php
class UserCates {
	protected $ci;
	public function __construct($ci) {
       $this->ci = $ci;
	}
	public function UserCates($request, $response, $args) {
		//获取UserCates列表
		//get
		$usercate=$request->getQueryParams();
		if($data['data'] = $this->ci->db->table('user_cates')->get()) {
			$data['status']='success';
		} else {
			$data['status']='error';
			$data['data']='还没有添加数据';
		}
		if($usercate['type']=="filter") {
			foreach($data['data'] as $cate) {
				$cate_filter[$cate->id]=$cate->name;
			}
			$data['data']=$cate_filter;
		}
		$response->withJson($data);
	}
	public function UserCateGet($request, $response, $args) {
		//获取单个UserCates信息
		//get
		if($data['data'] = $this->ci->db->table('user_cates')->where('id',$args['id'])->first()) {
			$data['status']='success';
		} else {
			$data['status']='error';
			$data['data']='还没有添加数据';
		}
		$response->withJson($data);
	}
	public function CateAdd($request, $response, $args) {
		//添加单个UserCates信息
		//post
		$usercate=$request->getParsedBody();
		if(!$usercate['name']) {
			$data['status']='error';
			$data['data']='请输入分类名称';
		} else {
			if($data['data'] = $this->ci->db->table('user_cates')->insertGetId($usercate)) {
				$data['status']='success';
				$data['data']='添加成功！';
			} else {
				$data['status']='error';
				$data['data']='添加失败，请重试！';
			}
		}
		$response->withJson($data);
	}
	public function UserCatesUpdate($request, $response, $args) {
		//更新单个UserCates信息
		//put
		$usercate=$request->getParsedBody();
		if(!$usercate['name']) {
			$data['status']='error';
			$data['data']='请输入分类名称';
		} else {
			if($data['data'] = $this->ci->db->table('user_cates')->where('id',$args['id'])->update($usercate)) {
				$data['status']='success';
				$data['data']='更新成功！';
			} else {
				$data['status']='error';
				$data['data']='更新失败，请重试！';
			}
		}
		$response->withJson($data);
	}
	public function UserCatesDelete($request, $response, $args) {
		//删除单个UserCates信息
		//delete
		$usercates=$request->getParsedBody();
		if($usercates['ids']) {
			$ids=json_decode($usercates['ids']);
			foreach($ids as $id) {
				if($data['status']!='error') {
					if(!$this->ci->db->table('user_cates')->where('id',$id)->delete()) {
						$data['status']='error';
						$data['data']='删除错误！';
					}
				}
			}
		}
		if($data['status']!='error') {
			$data['status']='success';
			$data['data']='删除成功！';
		}
		$response->withJson($data);
	}
}