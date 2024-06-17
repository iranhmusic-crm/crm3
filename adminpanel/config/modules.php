<?php

return [
	'bootstrap' => [
		'aaa',
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
				'idField' => 'ofpid',
			],
		],
		'mha' => [
			'class' => \iranhmusic\shopack\mha\frontend\adminpanel\Module::class,
		],
	],
];
