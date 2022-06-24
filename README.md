<p align="center">
    <p align="center">
        <a href="https://github.com/ikurcubic/wolt-api/actions"><img alt="GitHub Workflow Status (master)" src="https://img.shields.io/github/workflow/status/ikurcubic/wolt-api/Tests/master"></a>
        <a href="https://packagist.org/packages/ikurcubic/wolt-api"><img alt="Total Downloads" src="https://img.shields.io/packagist/dt/ikurcubic/wolt-api"></a>
        <a href="https://packagist.org/packages/ikurcubic/wolt-api"><img alt="Latest Version" src="https://img.shields.io/packagist/v/ikurcubic/wolt-api"></a>
        <a href="https://packagist.org/packages/ikurcubic/wolt-api"><img alt="License" src="https://img.shields.io/packagist/l/ikurcubic/wolt-api"></a>
    </p>
</p>

------
This package provides Wolt API PHP Client for Items and Inventory update

# Installation
`composer require ikurcubic/wolt-api`

# Usage
```php
    <?php
    use GuzzleHttp\Exception\ClientException;
    use IvanKurcubic\WoltAPI\Client;
    use IvanKurcubic\WoltAPI\Exceptions\HttpResponseException;

    $username = 'test';
    $password = 'b77b7a63cd5176154ca2802f9927ee598dc';
    $venueId = '62b041691ce47b414960c712'
    
    $api = new Client($username, $password, Client::ENV_STAGE);
    
    $data = [
        "data" => [
            ['sku'=>'1234', 'price'=>10000, 'enabled'=>true, 'vat_percentage'=>20.00],
            ['sku'=>'5678', 'price'=>20000, 'enabled'=>true, 'vat_percentage'=>20.00],
            ...
        ]   
    ];
    
    try {
        $api->updateItems($venueId, $data);
    } catch (HttpResponseException|ClientException $exception) {
        if ($exception->getResponse()->getStatusCode() == 429) {
            $retryAfter = $exception->getResponse()->getHeader('retry-after');
            if (is_array($retryAfter)) {
                $retryAfter = $retryAfter[0] ?? 0;
            }
            echo "Too many requests, need to wait $retryAfter seconds.";
            sleep($retryAfter + 2);
            $api->updateItems($venueId, $data);
        } else {
            throw $exception;
        }
    }    
```


**Wolt API PHP Client** was created by **Ivan Kurcubic** under the **[MIT license](https://opensource.org/licenses/MIT)**.
