<?php
class BookCates {
	protected $ci;
	public function __construct($ci) {
       $this->ci = $ci;
	}
	public function BookCates($request, $response, $args) {
		//获取BookCates列表
		//get
		$bookcate=$request->getQueryParams();
		if($data['data'] = $this->ci->db->table('book_cates')->get()) {
			$data['status']='success';
		} else {
			$data['status']='error';
			$data['data']='还没有添加数据';
		}
		if($bookcate['type']=="filter") {
			foreach($data['data'] as $cate) {
				$cate_filter[$cate->id]=$cate->name;
			}
			$data['data']=$cate_filter;
		}
		$response->withJson($data);
	}
	public function BookCateGet($request, $response, $args) {
		//获取单个BookCates信息
		//get
		if($data['data'] = $this->ci->db->table('book_cates')->where('id',$args['id'])->first()) {
			$data['status']='success';
		} else {
			$data['status']='error';
			$data['data']='还没有添加数据';
		}
		$response->withJson($data);
	}
	public function CateAdd($request, $response, $args) {
		//添加单个BookCates信息
		//post
		$bookcate=$request->getParsedBody();
		if(!$bookcate['name']) {
			$data['status']='error';
			$data['data']='请输入分类名称';
		} else {
			if($data['data'] = $this->ci->db->table('book_cates')->insertGetId($bookcate)) {
				$data['status']='success';
				$data['data']='添加成功！';
			} else {
				$data['status']='error';
				$data['data']='添加失败，请重试！';
			}
		}
		$response->withJson($data);
	}
	public function BookCatesUpdate($request, $response, $args) {
		//更新单个BookCates信息
		//put
		$bookcate=$request->getParsedBody();
		if(!$bookcate['name']) {
			$data['status']='error';
			$data['data']='请输入分类名称';
		} else {
			if($data['data'] = $this->ci->db->table('book_cates')->where('id',$args['id'])->update($bookcate)) {
				$data['status']='success';
				$data['data']='更新成功！';
			} else {
				$data['status']='error';
				$data['data']='更新失败，请重试！';
			}
		}
		$response->withJson($data);
	}
	public function BookCatesDelete($request, $response, $args) {
		//删除单个BookCates信息
		//delete
		$bookcates=$request->getParsedBody();
		if($bookcates['ids']) {
			$ids=json_decode($bookcates['ids']);
			foreach($ids as $id) {
				if($data['status']!='error') {
					if(!$this->ci->db->table('book_cates')->where('id',$id)->delete()) {
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