# CloudWS PHP lib

#### Installation
```
composer require cloudws/cloudws-php-api
```

#### Getting started
```php
<?php
require_once __DIR__."/vendor/autoload.php";

$client = new CloudWS\Client($_SERVER['CLOUDWS_TOKEN']);
```

#### Create channel
```php
<?php
$client->createChannel('channel');
```

#### Get list of channels
```php
<?php
$client->getAllChannels();
```

#### Send message
```php
<?php
$client->sendMessage('channel', ['data' => 'my_message']);
```

#### Delete message
```php
<?php
$client->deleteChannel('channel');
```

#### Example
```php
<?php

require_once __DIR__."/vendor/autoload.php";

$client = new CloudWS\Client($_SERVER['CLOUDWS_TOKEN']);

try {
    $client->sendMessage('channel', ['data' => 'my_message']);
} catch (\CloudWS\CloudWSException $e) {
    $client->createChannel('channel');
}
print_r($client->getAllChannels());
$client->deleteChannel('channel');
```

