<?php

return [
	'bootstrap' => [
		'aaa',
		'mha',
	],
	'modules' => [
		'aaa' => [
			'class' => \shopack\aaa\frontend\userpanel\Module::class,
		],
		'mha' => [
			'class' => \iranhmusic\shopack\mha\frontend\userpanel\Module::class,
		],
	],
];
