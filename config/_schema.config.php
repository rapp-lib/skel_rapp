<?php
	
	// Schama created from csv-file.
	registry(array(
		'Schema.tables.Product' =>array('label' =>'製品', 'pkey' =>'id'),
		'Schema.cols.Product' =>array(
			'name' =>array('label' =>'名称', 'def.type' =>'text', 'type' =>'text'),
			'img' =>array('label' =>'写真', 'def.type' =>'text', 'type' =>'file'),
			'category' =>array('label' =>'カテゴリ', 'def.type' =>'text', 'type' =>'select', 'list' =>'product_category'),
			'open_date' =>array('label' =>'公開日時', 'def.type' =>'datetime', 'type' =>'date'),
			'id' =>array('label' =>'ID', 'def.type' =>'integer', 'def.id' =>'true', 'def.autoincrement' =>'true'),
			'reg_date' =>array('label' =>'登録日時', 'def.type' =>'datetime'),
			'del_flg' =>array('label' =>'削除フラグ', 'def.type' =>'integer', 'def.del_flg' =>'true', 'def.default' =>0),
		),
		'Schema.tables.Customer' =>array('label' =>'会員', 'pkey' =>'id'),
		'Schema.cols.Customer' =>array(
			'name' =>array('label' =>'氏名', 'def.type' =>'text', 'type' =>'text'),
			'mail' =>array('label' =>'メールアドレス', 'def.type' =>'text', 'type' =>'text'),
			'login_pw' =>array('label' =>'パスワード', 'def.type' =>'text', 'type' =>'password'),
			'favorite_producs' =>array('label' =>'お気に入り製品', 'type' =>'checklist', 'list' =>'product'),
			'imgs' =>array('label' =>'画像', 'def.type' =>'text'),
			'id' =>array('label' =>'ID', 'def.type' =>'integer', 'def.id' =>'true', 'def.autoincrement' =>'true'),
			'reg_date' =>array('label' =>'登録日時', 'def.type' =>'datetime'),
			'del_flg' =>array('label' =>'削除フラグ', 'def.type' =>'integer', 'def.del_flg' =>'true', 'def.default' =>0),
		),
		'Schema.tables.FavoliteProductAssoc' =>array('label' =>'お気に入り製品', 'pkey' =>'id'),
		'Schema.cols.FavoliteProductAssoc' =>array(
			'customer_id' =>array('label' =>'会員ID', 'def.type' =>'integer'),
			'product_id' =>array('label' =>'製品ID', 'def.type' =>'integer'),
			'id' =>array('label' =>'ID', 'def.type' =>'integer', 'def.id' =>'true', 'def.autoincrement' =>'true'),
		),
		'Schema.controller' =>array(
			'index' =>array('label' =>'トップページ', 'type' =>'index', 'wrapper' =>'user'),
			'customer_entry' =>array(
				'label' =>'会員登録フォーム',
				'type' =>'master',
				'table' =>'Customer',
				'wrapper' =>'user',
				'usage' =>'form',
			),
			'customer_login' =>array(
				'label' =>'会員ログイン',
				'type' =>'login',
				'table' =>'Customer',
				'wrapper' =>'user',
				'account' =>'customer',
			),
			'customer_mypage' =>array(
				'label' =>'会員マイページ',
				'type' =>'index',
				'table' =>'Customer',
				'accessor' =>'customer',
				'auth' =>'customer',
				'wrapper' =>'user',
			),
			'admin_login' =>array('label' =>'管理者ログイン', 'type' =>'login', 'wrapper' =>'admin', 'account' =>'admin'),
			'product_master' =>array(
				'label' =>'製品管理',
				'type' =>'master',
				'table' =>'Product',
				'accessor' =>'admin',
				'auth' =>'admin',
				'wrapper' =>'admin',
				'use_csv' =>1,
			),
			'user_master' =>array(
				'label' =>'ユーザ管理',
				'type' =>'master',
				'table' =>'Customer',
				'accessor' =>'admin',
				'auth' =>'admin',
				'wrapper' =>'admin',
			),
		),
	));
	