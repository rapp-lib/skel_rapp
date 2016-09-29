
-- 製品 
DROP TABLE IF EXISTS Product;
CREATE TABLE Product (
	`name` text COMMENT '名称',
	`img` text COMMENT '写真',
	`category` text COMMENT 'カテゴリ',
	`open_date` text COMMENT '公開日時',
	`id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
	`reg_date` datetime COMMENT '登録日時',
	`update_date` timestamp COMMENT '最終更新日時',
	`del_flg` int(11) DEFAULT 0 COMMENT '削除フラグ',	PRIMARY KEY  (`id`)) 	;

-- 会員 
DROP TABLE IF EXISTS Customer;
CREATE TABLE Customer (
	`name` text COMMENT '氏名',
	`mail` text COMMENT 'メールアドレス',
	`login_pw` text COMMENT 'パスワード',
	`imgs` text COMMENT '画像',
	`id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
	`reg_date` datetime COMMENT '登録日時',
	`update_date` timestamp COMMENT '最終更新日時',
	`del_flg` int(11) DEFAULT 0 COMMENT '削除フラグ',	PRIMARY KEY  (`id`)) 	;

-- お気に入り製品 
DROP TABLE IF EXISTS FavoliteProductAssoc;
CREATE TABLE FavoliteProductAssoc (
	`customer_id` int(11) COMMENT '会員ID',
	`product_id` int(11) COMMENT '製品ID',
	`id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',	PRIMARY KEY  (`id`)) 	;
