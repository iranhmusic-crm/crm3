<?php

return [
	'bootstrap' => [
		'aaa',
		'mha',
	],
	'modules' => [
		'aaa' => [
			'class' => \shopack\aaa\frontend\userpanel\Module::class,
			'allowSignup' => false,
			'globalOwnerUserLabel' => ['mha', 'Member'],
		],
		'mha' => [
			'class' => \iranhmusic\shopack\mha\frontend\userpanel\Module::class,
		],
	],
];
