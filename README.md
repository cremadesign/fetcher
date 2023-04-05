# PHP Curl Wrapper
Stephen Ginn at Crema Design Studio

### Installation
You can install the package via composer:
```shell
composer config repositories.crema/fetcher git https://github.com/cremadesign/fetcher
composer require crema/fetcher:@dev
```

### Curl Request Example
Add this code to your PHP file:
```php
require_once '../vendor/autoload.php';
use Crema\CurlRequest;

$curl = new CurlRequest();
$response = $curl->get("https://example.com/sample.json", $data, $token);

header('Content-Type: application/json');
echo json_encode($response->json(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
```

### Fetcher Example
Add this code to your PHP file:
```php
require_once '../vendor/autoload.php';
use Crema\Fetcher;

$fetcher = new Fetcher();
$response = $fetcher->request("https://dummyjson.com/products/1");

header('Content-Type: application/json');
echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
```
