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

			'offlinePaymentAfterAcceptUrl' => [
				'/mha/accounting/membership/renew-by-offline-payment',
				'ofpID' => '{ofpID}',
			],
		],
		'mha' => [
			'class' => \iranhmusic\shopack\mha\frontend\adminpanel\Module::class,
		],
	],
];
