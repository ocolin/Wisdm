# WISDM

## What is it?

This is a PHP REST client for the WISDM API interface. WISDM is a service for wireless ISPs that provide the ability to determine which areas are serviceable from a wireless access point. 

---
## Table of Contents

- [What is it?](#What-is-it?)
- [Requirements](#Requirements)
- [Installation](#Installation)
- [Configuration](#Configuration)
  - [Instantiation with environment variables](#Instantiation-with-environment-variables)
  - [Instantiation with constructor arguments](#Instantiation-with-constructor-arguments)
  - [Optional arguments](#Optional-arguments)
- [Response](#Response)
- [Path Parameter Interpolation](#Path-Parameter-Interpolation)
- [Methods](#Methods)
  - [GET](#GET)
  - [POST](#POST)
  - [PATCH](#PATCH)
  - [DELETE](#DELETE)
  - [REQUEST](#REQUEST)
  - [UPLOAD](#UPLOAD)



---
## Requirements

- ^PHP 8.2
- guzzlehttp/guzzle ^7.10
- ocolin/global-type ^2.0

---
## Installation

```
composer require ocolin/wisdm
```
---
## Configuration

Wisdm requires two pieces of information. The server hostname, and your authentication token/key. These can be configured via environment or though constructor arguments using a Config data object.

|Environment name| Constructor argument | Type   | Description              |
|----------------|----------------------|--------|--------------------------|
|WISDM_API_HOST| $host                | string | Server hostname          |
|WISDM_API_KEY| $token               | string | Authentication key/token |

### Instantiation with environment variables

```php
// Manual variables for demonstration
$_ENV['WISDM_API_HOST'] = 'https://wisdm.wirelesscoverage.com/api';
$_ENV['WISDM_API_KEY'] = 'abcdefg';
$wisdm = new Ocolin\Wisdm\Wisdm();
```

### Instantiation with constructor arguments

```php
$wisdm = new Ocolin\Wisdm\Wisdm(
    config: new Ocolin\Wisdm\Config(
         host: 'https://wisdm.wirelesscoverage.com/api',
        token: 'abcdefg'
    );
);
```

### Optional arguments

You can also pass along Guzzle HTTP configuration options such as HTTP timeouts, verification of SSL, etc different from the defaults.

```php
// Manual variables for demonstration
$_ENV['WISDM_API_HOST'] = 'https://wisdm.wirelesscoverage.com/api';
$_ENV['WISDM_API_KEY'] = 'abcdefg';
$wisdm = new Ocolin\Wisdm\Wisdm(
    config: new Ocolin\Wisdm\Config( options: [ 'timeout' => 60 ] )
);

```
---
## Response

The client responds with an object containing the following properties:

| Name          | Type          | Description            |
|---------------|---------------|------------------------|
| status        | integer       | HTTP status code       |
| statusMessage | string        | HTTP status message    |
| headers       | array         | HTTP response headers  |
| contentType   | string        | HTTP body content type |
| body          | object\|array | API payload body       |

---
## Path Parameter Interpolation

Any elements or properties of a \$query argument that match variable tokens in the URI endpoint path will be replaced with the values for those elements in the $query array or object. Please see some of the method functions for examples.

---
## Methods

### GET

Get a resource from the API.

```php
$output = $wisdm->get( 
    endpoint: '/availability/{id}/check', query: [ 'id' => 1234 ] 
);
```

### POST

Create a resource on the API.

```php
$output = $wisdm->post(
    endpoint: '/networks',
    body: [
        'name'  => 'MySiteName',
        'color' => '#808080'
    ]
);
```

### PATCH

Update a resource on the API.

```php
$output = $widnm->patch(
    endpoint: '/networks/{id}',
    query: [ 'id' => 1234 ],
    body: [ 'name' => 'MyNewSiteName' ]
);
```

### DELETE

Delete a resource on the API.

```php
$output = $wisdm->delete(
    endpoint: '/networks/{id}',
    query: [ 'id' => 1234 ]
);
```

### REQUEST

In case changes are made to the API, a generic request function is available.

```php
$output = $wisdm->request(
     endpoint: '/availability/{id}/check',
       method: 'GET',
        query: [ 'id' => 1234 ] 
);
```

### UPLOAD

Several API calls use a multi-part request body for uploading files and data. The uploads() function can be used for those.

```php
$output = $wisdm->upload(
    endpoint: '/networks/import-networks',
    filePath: __DIR__ . '/sites_template.csv',
    body: [
        'colour' => '#808080',
        'site_name_field' => 'Site_Name'
    ]
);
```