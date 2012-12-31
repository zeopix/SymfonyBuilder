### Install

```js
{
    "require": {
        "zeopix/symfony-builder": "*"
    }
}
```

``` bash
$ php composer.phar update zeopix/symfony-builder
```

Recommended to enable this bundle only in dev env.
``` php
<?php
// app/AppKernel.php
public function registerBundles()
{
    $bundles = array(
        // ...
        new Iga\BuilderBundle\IgaBuilderBundle(),
    );
}
```

Add basic routing
``` php
#app/config/routing_dev.yml
iga_builder:
    resource: "@IgaBuilderBundle/Controller/"
    type:     annotation
    prefix:   /builder
```

Check your php.ini has magic quotes off:
``` bash
magic_quotes_gpc = Off
```