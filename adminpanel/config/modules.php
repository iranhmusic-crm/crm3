<?php

return [
	'bootstrap' => [
		'aaa',
		'mha',
	],
	'modules' => [
		'aaa' => [
			'class' => \shopack\aaa\frontend\adminpanel\Module::class,
			'globalUserViewLink' => [
				'url' => '/mha/member/view',
				'idField' => 'id',
			],
			'globalOwnerUserLabel' => ['mha', 'Member'],
			'globalSearchUserForSelect2ListUrl' => '/mha/member/select2-list',

			'offlinePaymentAfterAcceptLink' => [
				'url' => '/mha/accounting/membership/renew-by-offline-payment',
				'idField' => 'ofpid',
			],
		],
		'mha' => [
			'class' => \iranhmusic\shopack\mha\frontend\adminpanel\Module::class,
		],
	],
];
