<?php

	// Fields$B3HD%5!G=@_Dj(B/$B30It%F!<%V%k$H$N4XO"@-Dj5A(B
	registry("Model.extends.fields",array(
	
		// $B!JNc!K(BBridge$B4X78$N%5%s%W%k(B
		/*
		"Product.categories" =>array(
			"auto_load" =>false,
			"type" =>"assoc_bridge",
			"table" =>"ProductCategoryAssoc",
			"conditions" =>array_escape(array(
				"ProductCategoryAssoc.status" =>1,
			)),
			"parent_pk" =>"Product.id",
			"connect_by" =>"ProductCategoryAssoc.product_id",
			"reduce_by" =>"ProductCategoryAssoc.category_id",
		),
	    */
		// $B!JNc!K(BMeta$B4X78$N%5%s%W%k(B
		/*
		"Product.imgs" =>array(
			"auto_load" =>false,
			"type" =>"assoc_meta",
			"table" =>"ProductMeta",
			"parent_pk" =>"Product.id",
			"connect_by" =>"ProductMeta.parent_id",
		),
		*/
	));