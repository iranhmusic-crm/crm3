<?php

return [
	'bootstrap' => [
		'aaa',
		'cmn',
		'mha',
	],
	'modules' => [
		'aaa' => [
			'class' => \shopack\aaa\frontend\adminpanel\Module::class,

			'ownerUserLabel' => ['mha', 'Member'],

			'userViewUrl' => [
				'url' => '/mha/member/view',
				'idField' => 'id',
			],

			'searchUserForSelect2ListUrl' => '/mha/member/select2-list',

			'offlinePaymentAfterAcceptUrl' => [
				'url' => '/mha/accounting/membership/renew-via-invoice',
				'idField' => 'ofpID',
			],
		],
		'cmn' => [
			'class' => \shopack\cmn\frontend\adminpanel\Module::class,
		],
		'mha' => [
			'class' => \iranhmusic\shopack\mha\frontend\adminpanel\Module::class,
		],
	],
];
