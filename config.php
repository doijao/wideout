<?php

return [

    'jsonURL' => 'https://www.att.com/services/catalogservice/devices?includeFilters=skuState=active&mode=productList',
    'tempFile' => 'test/data/productList.json',
	'ftp' => [
		'host' 		=> 	'flashtalking.exavault.com',
		'username'	=>	'WideOut',
		'password'	=>	'WideOut',
	],
	'excludeColumns' => [
		'mBrand',
		'skuDisplayName',
		'mPrice',
		'mListPrice',
		'mModel',
		'mLargeImage',
		'mId',
		'mProductPageURL',
		'mName',
		'salesRank',
		'mStarRatings',
		'mProductId',
		'deviceType',
		'mMobileProductPageURL',
		'mProductPageURLEs',
		'mDescription',
		'mDueToday',
		'PDPPageURL'
	],
	'excludeCategory'	=>	[
		'SMARTPHONES',
		'WEARABLES',
		'M-CAT-TABLETS'
	],
	'filename'	=>	'Ador_WideOut_Activity.csv',
];
