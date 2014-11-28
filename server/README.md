Bootstrap 
========

##### IN DEVELOPMENT

## Installation

* Run **composer install**
* Run **npm install**
* Run **gulp**
* Create a configuration file — [protected/config/local/config.php](https://gist.github.com/rkit/8145662)
* Directory **protected/runtime** and **assets** must be writable by your webserver
* Load base schema **protected/migrations/base-schema.sql**
* Create a new administrator account

~~~~
yiic createadmin <login> <password>
~~~~

## Debug Mode

~~~~
SetEnv APPLICATION_ENV "development"
~~~~

~~~~
gulp --debug=true
~~~~

## BrowserSync
~~~~
gulp --sync=true
~~~~

## WebRoot

If there is no code outside webroot, set:

~~~
define('WEBROOT', __DIR__); 
~~~
> in index.php

and extract the web folder to the root of the site.

## Standards

### [PHP Coding Style: PSR-2](http://www.php-fig.org/psr/psr-2)
### [Git Flow](https://gist.github.com/rkit/8145655)