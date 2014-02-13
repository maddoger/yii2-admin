Yii2 Admin Panel Module by maddoger

Default layout

```
...
'layout' => '@maddoger/admin/views/layouts/main.php',
...
```

URL helper

/*$man = Yii::$app->getUrlManager();

		$urls = [
			'admin/backend/modules/index',
			'/admin/backend/modules/index',
			['admin/backend/modules/index'],
			['/admin/backend/modules/index'],

			'admin/modules/index',
			'/admin/modules/index',
			['admin/modules/index'],
			['/admin/modules/index'],

			'admin/modules',
			'/admin/modules',
			['admin/modules'],
			['/admin/modules'],


			'config',
			'/config',
			['config'],
			['/config'],
		];

		foreach ($urls as $url) {
			echo (is_array($url) ? 'array ['.$url[0].']' : $url),' -> ',Html::url($url),'<br />';
			echo (is_array($url) ? 'array ['.$url[0].']' : $url),' -> ',
			(is_array($url) ? $man->createUrl($url[0], array_slice($url, 1)) : $man->createUrl($url) ),
			'<br /><br />';
		}
		return false;*/
