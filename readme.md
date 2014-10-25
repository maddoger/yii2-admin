Yii2 Admin Panel Module by maddoger

#Installation

##Migration

```
./yii migrate --migrationPath="@maddoger/admin/migrations"
```

##Configuration

```
...
'modules' => [
    ...
    'admin' => [
            'class' => 'maddoger\admin\AdminModule',
            'superUserId' => 1,
        ],
    ],
    ...
],

'defaultRoute' => 'admin/site/index',
'layout' => '@maddoger/admin/views/layouts/main.php',
...
```

##Components

```
'components' => [

    //Admin
    'urlManager' => [
        'rules' => [
            //Admin
            '<action:(index|login|logout|captcha|request-password-reset|reset-password|search)>' => 'admin/site/<action>',
        ]
    ],
    'mailer' => [
        'class' => 'yii\swiftmailer\Mailer',
        'viewPath' => '@maddoger/admin/mail',
        // send all mails to a file by default. You have to set
        // 'useFileTransport' to false and configure a transport
        // for the mailer to send real emails.
        'useFileTransport' => true,
    ],

    //User
    'user' => [
        'identityClass' => 'maddoger\admin\models\User',
        'loginUrl' => ['admin/site/login'],
        'enableAutoLogin' => true,
        'on afterLogin'   => ['maddoger\admin\models\User', 'updateLastVisit'],
        'on afterLogout'   => ['maddoger\admin\models\User', 'updateLastVisit'],
    ],
    'session' => [
        'class' => 'yii\web\DbSession',
        'sessionTable' => 'admin_session',
    ],
...
]
```

