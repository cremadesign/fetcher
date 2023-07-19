# PHP Curl Wrapper
Stephen Ginn at Crema Design Studio

## Install
You can install the package via composer:
```shell
composer config repositories.crema/fetcher git https://github.com/cremadesign/fetcher
composer require crema/fetcher:@dev
```

## CurlRequest Class

#### Init
Add this code to your PHP file:
```php
require_once '../vendor/autoload.php';
use Crema\CurlRequest;
$curl = new CurlRequest();
header('Content-Type: application/json');

$url = "https://dummyjson.com/products/1";
```

#### Request
```php
$response = $curl->request($type, $url, $payload, $headers);
echo json_encode($response->json(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
```

#### Get
Shortcut to `$curl->request("GET")`
```php
$response = $curl->get($url);
echo json_encode($response->json(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
```

#### Post
Shortcut to `$curl->request("POST")`
```php
$response = $curl->post($url, $data);
echo json_encode($response->json(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
```

#### Add Request Headers
Merges request headers into the current headers
```php
$curl->addRequestHeaders([
	'X-Custom' => 'Lorem Ipsum'
]);
```

#### Get Request Headers
```php
$headers = $curl->getRequestHeaders();
echo json_encode($headers, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
```

#### Remove Request Headers
This feature is useful for removing the default "Content-Type" header, so that the request body isn't encoded to JSON.
```php
$headers = $curl->removeRequestHeaders();
echo json_encode($headers, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
```

### Fetcher Class
Add this code to your PHP file:
```php
require_once '../vendor/autoload.php';
use Crema\Fetcher;

$fetcher = new Fetcher();
$response = $fetcher->request($url);

header('Content-Type: application/json');
echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
```
