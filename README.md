SymfonyBuilder
==============
Este bundle está en fase experimental y no debe ser utilizado en producción.

### Instalación

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


### TODO
Editor
- Guardar, acabar.
- CodeMirror: http://codemirror.net/

Paleta de Código
- Sistema de snippets inteligentes (ver Sketch)
- Asociación (parsing) de snippets en código existente.
- Parser: https://github.com/nikic/PHP-Parser 

Snippets:
- Handling (Request,Response,Routing)
- Formularios
- Doctrine
- Servicios Propios
