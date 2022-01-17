# Configuration Scan  

![cover](./cover.png)

Scan for Configuration in very opinionated manner:  

```
parent-folder
    \- name-of-folder
        \- config.json
        \- other.json
    \- other-name-of-folder
        \- config.json  
```  

## Install  

```
composer require "gravatalonga/configuration-scan"  
```

## Requirements   

It was tested on PHP7.4 and PHP8.0  

## How to use  

```php  
<?php

$scan = new \Gravatalonga\ConfigurationScan\Scan("parent-folder");
$containers = $scan->containers(); // get array with "name-of-folder" as key, and config.json as value.  

// .... 

$configurations = $scan->configurations('name-of-folder'); // it will get "other" as key and other.json as value.  
```


## Tests  

```sh  
composer test
```