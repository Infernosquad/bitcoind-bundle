Installation
============
### Add InfernosquadBitcoindBundle to your project

```bash
composer require infernosquad/bitcoind-bundle
```

### Enable the bundle

Enable the bundle in the kernel:

```php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Infernosquad\BitcoindBundle\InfernosquadBitcoindBundle(),
    );
}
```

Configuration
=============

### Configure your bitcoind clients

```yaml
#app/config/config.yml
infernosquad_bitcoind:
    drivers:
      driver1:
          class: 'Nbobtc\Http\Driver\CurlDriver' #default
          options:
              name: value
              name1: value1

    clients:
        client1:
            dsn: 'https://username:password@localhost:18332'
            driver: driver1
```


Usage
=====

Now you can get your clients and drivers from controllers.  
See documentation of bitcoind bundle [https://github.com/nbobtc/bitcoind-php]()

```php
$client = $this->container->get('infernosquad.bitcoind.clients.client1');
```
