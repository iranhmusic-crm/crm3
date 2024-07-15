<?php

return [
	'bootstrap' => [
		'cmn',
		'aaa',
		'mha',
	],
	'modules' => [
		'cmn' => [
			'class' => \shopack\cmn\frontend\userpanel\Module::class,
		],
		'aaa' => [
			'class' => \shopack\aaa\frontend\userpanel\Module::class,
			'allowSignup' => false,
			'ownerUserLabel' => ['mha', 'Member'],
		],
		'mha' => [
			'class' => \iranhmusic\shopack\mha\frontend\userpanel\Module::class,
		],
	],
];
