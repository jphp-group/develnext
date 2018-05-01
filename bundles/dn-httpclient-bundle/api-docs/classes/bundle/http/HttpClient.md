# HttpClient

- **class** `HttpClient` (`bundle\http\HttpClient`) **extends** `AbstractScript` (`php\gui\framework\AbstractScript`)
- **package** `httpclient`
- **source** `bundle/http/HttpClient.php`

**Description**

Class HttpClient

---

#### Properties

- `->`[`baseUrl`](#prop-baseurl) : `string`
- `->`[`userAgent`](#prop-useragent) : `string`
- `->`[`referrer`](#prop-referrer) : `string`
- `->`[`followRedirects`](#prop-followredirects) : `bool`
- `->`[`connectTimeout`](#prop-connecttimeout) : `int`
- `->`[`readTimeout`](#prop-readtimeout) : `int`
- `->`[`proxyType`](#prop-proxytype) : `string` - _HTTP, SOCKS,_
- `->`[`proxy`](#prop-proxy) : `string`
- `->`[`requestType`](#prop-requesttype) : `string` - _URLENCODE, MULTIPART, JSON, TEXT_
- `->`[`responseType`](#prop-responsetype) : `string` - _JSON, TEXT_
- `->`[`data`](#prop-data) : `array`
- `->`[`encoding`](#prop-encoding) : `string`
- `->`[`cookies`](#prop-cookies) : `array`
- `->`[`headers`](#prop-headers) : `array`
- `->`[`_boundary`](#prop-_boundary) : `string`
- `->`[`_lock`](#prop-_lock) : `mixed`

---

#### Static Methods

- `HttpClient ::`[`textToArray()`](#method-texttoarray)

---

#### Methods

- `->`[`__construct()`](#method-__construct) - _HttpClient constructor._
- `->`[`applyImpl()`](#method-applyimpl)
- `->`[`get()`](#method-get)
- `->`[`post()`](#method-post)
- `->`[`put()`](#method-put)
- `->`[`patch()`](#method-patch)
- `->`[`delete()`](#method-delete)
- `->`[`getAsync()`](#method-getasync)
- `->`[`postAsync()`](#method-postasync)
- `->`[`putAsync()`](#method-putasync)
- `->`[`patchAsync()`](#method-patchasync)
- `->`[`deleteAsync()`](#method-deleteasync)
- `->`[`executeAsync()`](#method-executeasync)
- `->`[`execute()`](#method-execute)
- `->`[`connect()`](#method-connect)
- `->`[`formatUrlencode()`](#method-formaturlencode)
- `->`[`formatMultipart()`](#method-formatmultipart)

---
# Static Methods

<a name="method-texttoarray"></a>

### textToArray()
```php
HttpClient::textToArray(mixed $text, mixed $trimValues): void
```

---
# Methods

<a name="method-__construct"></a>

### __construct()
```php
__construct(): void
```
HttpClient constructor.

---

<a name="method-applyimpl"></a>

### applyImpl()
```php
applyImpl(mixed $target): mixed
```

---

<a name="method-get"></a>

### get()
```php
get(string $url, mixed $data): HttpResponse
```

---

<a name="method-post"></a>

### post()
```php
post(string $url, mixed $data): HttpResponse
```

---

<a name="method-put"></a>

### put()
```php
put(string $url, mixed $data): HttpResponse
```

---

<a name="method-patch"></a>

### patch()
```php
patch(string $url, mixed $data): HttpResponse
```

---

<a name="method-delete"></a>

### delete()
```php
delete(string $url, mixed $data): HttpResponse
```

---

<a name="method-getasync"></a>

### getAsync()
```php
getAsync(mixed $url, mixed $data, callable $callback): HttpAsyncResponse
```

---

<a name="method-postasync"></a>

### postAsync()
```php
postAsync(mixed $url, mixed $data, callable $callback): HttpAsyncResponse
```

---

<a name="method-putasync"></a>

### putAsync()
```php
putAsync(mixed $url, mixed $data, callable $callback): HttpAsyncResponse
```

---

<a name="method-patchasync"></a>

### patchAsync()
```php
patchAsync(mixed $url, mixed $data, callable $callback): HttpAsyncResponse
```

---

<a name="method-deleteasync"></a>

### deleteAsync()
```php
deleteAsync(mixed $url, mixed $data, callable $callback): HttpAsyncResponse
```

---

<a name="method-executeasync"></a>

### executeAsync()
```php
executeAsync(string $url, string $method, array|mixed $data, callable $callback): HttpAsyncResponse
```

---

<a name="method-execute"></a>

### execute()
```php
execute(string $url, string $method, array|mixed $data): HttpResponse
```

---

<a name="method-connect"></a>

### connect()
```php
connect(php\net\URLConnection $connection, mixed $body): void
```

---

<a name="method-formaturlencode"></a>

### formatUrlencode()
```php
formatUrlencode(array $data, string $prefix): string
```

---

<a name="method-formatmultipart"></a>

### formatMultipart()
```php
formatMultipart(array $data, mixed $prefix): void
```