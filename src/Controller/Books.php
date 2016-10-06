<?php
class Books {
	protected $ci;
	public function __construct($ci) {
       $this->ci = $ci;
	}
	public function Books($request, $response, $args) {
		//获取Books列表
		//get
		$book=$request->getQueryParams();
		$query= $this->ci->db->table('books');
		if($book['ser']) {
			if(!$book['ser_type']) {
				$book['ser_type']='isbn';
			}
			switch($book['ser_type']) {
				case 'isbn':
					$data['data'] = $query
					->where('books.isbn','=', $book['ser'])
					->select('books.*')
					->get();
				break;
				case 'book_name':
					$data['data'] = $query
					->where('books.book_name','like', '%'.$book['ser'].'%')
					->select('books.*')
					->get();
				break;
				case 'local':
					$data['data'] = $query
					->where('books.local','like', '%'.$book['ser'].'%')
					->select('books.*')
					->get();
				break;
				case 'num':
					$data['data'] = $query
					->where('books.num','<=', $book['ser'])
					->select('books.*')
					->get();
				break;
			}
		}
		if(!$data['data']) {
			if($book['page']&&$book['page']>=1) {
				$query=$query->skip(($book['page']-1)*5);
			}
			if($data['data']=$query
				->select('books.*')
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
	public function BookGet($request, $response, $args) {
		//获取单个Books信息
		//get
		if($data['data'] = $this->ci->db->table('books')->where('id',$args['id'])->first()) {
			$data['status']='success';
		} else {
			$data['status']='error';
			$data['data']='还没有添加数据';
		}
		$response->withJson($data);
	}
	public function BookAdd($request, $response, $args) {
		//添加单个Books信息
		//post
		$book=$request->getParsedBody();
		if(!$book['book_name']) {
			$data['status']='error';
			$data['data']='请输入书名';
		}
		if($data['status']!='error') {
			if(!$book['isbn']) {
				$data['status']='error';
				$data['data']='请输入ISBN编号';
			}
		}
		if($data['status']!='error') {
			if(!$book['bcate_id']) {
				$data['status']='error';
				$data['data']='请选择书类型';
			}
		}
		if($data['status']!='error') {
			if(!$book['book_money']) {
				$data['status']='error';
				$data['data']='请输入售价';
			}
		}
		if($data['status']!='error') {
			if(!$book['num']) {
				$data['status']='error';
				$data['data']='请输入进货数量';
			}
		}
		if($data['status']!='error') {
			if(!$book['in_money']) {
				$data['status']='error';
				$data['data']='请输入进价';
			}
		}
		if($data['status']!='error') {
			if(!$book_cates = $this->ci->db->table('book_cates')->where('id',$book['bcate_id'])->first()) {
				$data['status']='error';
				$data['data']='还没有此图书分类';
			}
		}
		if($data['status']!='error') {
			$book['in_time']=time();			
		}
		if($data['status']!='error'){
			if($data['data'] = $this->ci->db->table('books')->insertGetId($book)) {
				$data['status']='success';
				$data['data']='添加成功！';
			} else {
				$data['status']='error';
				$data['data']='添加失败，请重试！';
			}
		}
		$response->withJson($data);
	}
	public function BookUpdate($request, $response, $args) {
		//更新单个Books信息
		//put
		if(!$oldbook = $this->ci->db->table('books')->where('id',$args['id'])->first()) {
				$data['status']='error';
				$data['data']='还没有此图书';
		}
		$book=$request->getParsedBody();
		if($data['status']!='error') {
			$book['bcate_id']=$book['bcate_id']?$book['bcate_id']:$oldbook->bcate_id;
			if(!$book_cates = $this->ci->db->table('book_cates')->where('id',$book['bcate_id'])->first()) {
				$data['status']='error';
				$data['data']='还没有此图书分类';
			}
		}
		if($data['status']!='error') {
			$book['book_name']=$book['book_name']?$book['book_name']:$oldbook->book_name;
			$book['isbn']=$book['isbn']?$book['isbn']:$oldbook->isbn;
			$book['book_money']=$book['book_money']?$book['book_money']:$oldbook->book_money;
			$book['num']=$book['num']?$book['num']:$oldbook->num;
			$book['in_money']=$book['in_money']?$book['in_money']:$oldbook->in_money;
		}
		if($data['status']!='error'){
			if($data['data'] = $this->ci->db->table('books')->where('id',$args['id'])->update($book)) {
				$data['status']='success';
				$data['data']='更新成功！';
			} else {
				$data['status']='error';
				$data['data']='更新失败，请重试！';
			}
		}
		$response->withJson($data);
	}
	public function BookDelete($request, $response, $args) {
		//删除单个Books信息
		//delete
		if($data['data'] = $this->ci->db->table('books')->where('id',$args['id'])->delete()) {
			$data['status']='success';
			$data['data']='删除成功！';
		} else {
			$data['status']='error';
			$data['data']='删除失败，请重试！';
		}
		$response->withJson($data);
	}
}