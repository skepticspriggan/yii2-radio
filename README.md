# Yii2 Radio

A simple radio group with live update, low resource usage and fast response time.

## Installation

```bash
composer require "skepticspriggan/yii2-radio:*"
```

## Usage

```php
echo SimpleRadio::widget([
    'name' => 'login_requirement',
    'value' => $user->login_requirement,
    'url' => Url::to(['user/set-login-requirement']),
    'items' => [
        'ip' => 'Trusted IP',
        'totp' => 'Authenticator app',
        'either' => 'Either one',
    ],
]);
```
