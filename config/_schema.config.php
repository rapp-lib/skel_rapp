<?php
	
	// Schama created from csv-file.
	registry(array(
		'Schema.tables.Product' =>array('label' =>'製品'),
		'Schema.cols.Product' =>array(
			'name' =>array('label' =>'名称', 'def.type' =>'text', 'type' =>'text'),
			'img' =>array('label' =>'写真', 'def.type' =>'text', 'type' =>'file'),
			'category' =>array('label' =>'カテゴリ', 'def.type' =>'text', 'type' =>'select', 'list' =>'product_category'),
			'open_date' =>array('label' =>'公開日時', 'def.type' =>'datetime', 'type' =>'date'),
			'id' =>array('label' =>'ID', 'def.type' =>'integer', 'def.id' =>true, 'def.autoincrement' =>true),
			'reg_date' =>array('label' =>'登録日時', 'def.type' =>'datetime'),
			'del_flg' =>array('label' =>'削除フラグ', 'def.type' =>'integer', 'def.del_flg' =>true, 'def.default' =>0),
		),
		'Schema.tables.Member' =>array('label' =>'会員'),
		'Schema.cols.Member' =>array(
			'name' =>array('label' =>'氏名', 'def.type' =>'text', 'type' =>'text'),
			'mail' =>array('label' =>'メールアドレス', 'def.type' =>'text', 'type' =>'text'),
			'login_pw' =>array('label' =>'パスワード', 'def.type' =>'text', 'type' =>'password'),
			'favorite_producs' =>array('label' =>'お気に入り製品', 'type' =>'checklist', 'list' =>'product'),
			'imgs' =>array('label' =>'画像', 'def.type' =>'text'),
			'id' =>array('label' =>'ID', 'def.type' =>'integer', 'def.id' =>true, 'def.autoincrement' =>true),
			'reg_date' =>array('label' =>'登録日時', 'def.type' =>'datetime'),
			'del_flg' =>array('label' =>'削除フラグ', 'def.type' =>'integer', 'def.del_flg' =>true, 'def.default' =>0),
		),
		'Schema.tables.FavoliteProducts' =>array('label' =>'お気に入り製品'),
		'Schema.cols.FavoliteProducts' =>array(
			'member_id' =>array('label' =>'会員ID', 'def.type' =>'integer'),
			'product_id' =>array('label' =>'製品ID', 'def.type' =>'integer'),
			'id' =>array('label' =>'ID', 'def.type' =>'integer', 'def.id' =>true, 'def.autoincrement' =>true),
		),
		'Schema.controller' =>array(
			'index' =>array('type' =>'index', 'label' =>'トップページ', 'accessor' =>'member'),
			'member_register' =>array(
				'type' =>'master',
				'label' =>'会員登録',
				'table' =>'Member',
				'accessor' =>'member',
				'usage' =>'form',
			),
			'member_login' =>array(
				'type' =>'login',
				'label' =>'会員ログイン',
				'table' =>'Member',
				'accessor' =>'member',
				'account' =>'member',
			),
			'member_index' =>array(
				'type' =>'index',
				'label' =>'会員トップ',
				'table' =>'Member',
				'accessor' =>'member',
				'auth' =>'member',
			),
			'admin_login' =>array('type' =>'login', 'label' =>'管理者ログイン', 'accessor' =>'admin', 'account' =>'admin'),
			'admin_product_master' =>array(
				'type' =>'master',
				'label' =>'製品管理',
				'table' =>'Product',
				'accessor' =>'admin',
				'auth' =>'admin',
				'use_csv' =>1,
			),
			'admin_member_master' =>array(
				'type' =>'master',
				'label' =>'会員管理',
				'table' =>'Member',
				'accessor' =>'admin',
				'auth' =>'admin',
			),
		),
	));
	