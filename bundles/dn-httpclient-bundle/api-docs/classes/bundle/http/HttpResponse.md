# HttpResponse

- **class** `HttpResponse` (`bundle\http\HttpResponse`)
- **package** `httpclient`
- **source** `bundle/http/HttpResponse.php`

**Description**

Class HttpResponse

---

#### Properties

- `->`[`body`](#prop-body) : `mixed`
- `->`[`responseCode`](#prop-responsecode) : `mixed`
- `->`[`statusMessage`](#prop-statusmessage) : `mixed`
- `->`[`headers`](#prop-headers) : `mixed`
- `->`[`cookies`](#prop-cookies) : `mixed`

---

#### Methods

- `->`[`body()`](#method-body)
- `->`[`statusCode()`](#method-statuscode)
- `->`[`statusMessage()`](#method-statusmessage)
- `->`[`headers()`](#method-headers)
- `->`[`header()`](#method-header) - _Returns header value._
- `->`[`contentType()`](#method-contenttype) - _Returns Content-Type header value._
- `->`[`contentLength()`](#method-contentlength) - _Content-Length header value, returns -1 if it does not exist._
- `->`[`cookie()`](#method-cookie)
- `->`[`cookies()`](#method-cookies) - _Return array of Set-Cookie header._
- `->`[`isSuccess()`](#method-issuccess) - _Check http code >= 200 and <= 399_
- `->`[`isFail()`](#method-isfail) - _Check http code >= 400_
- `->`[`isError()`](#method-iserror) - _Check http code >= 400_
- `->`[`isNotFound()`](#method-isnotfound) - _Check http code is 404_
- `->`[`isAccessDenied()`](#method-isaccessdenied) - _Check http code is 403_
- `->`[`isInvalidMethod()`](#method-isinvalidmethod) - _Check http code is 405_
- `->`[`isServerError()`](#method-isservererror) - _Check http code >= 500_

---
# Methods

<a name="method-body"></a>

### body()
```php
body(mixed $data): mixed|string|array|Stream
```

---

<a name="method-statuscode"></a>

### statusCode()
```php
statusCode(int $responseCode): int
```

---

<a name="method-statusmessage"></a>

### statusMessage()
```php
statusMessage(string $statusMessage): string
```

---

<a name="method-headers"></a>

### headers()
```php
headers(array $headerFields): array
```

---

<a name="method-header"></a>

### header()
```php
header(string $name): mixed
```
Returns header value.

---

<a name="method-contenttype"></a>

### contentType()
```php
contentType(string $contentType): string
```
Returns Content-Type header value.

---

<a name="method-contentlength"></a>

### contentLength()
```php
contentLength(int $size): int
```
Content-Length header value, returns -1 if it does not exist.

---

<a name="method-cookie"></a>

### cookie()
```php
cookie(string $name): string|array
```

---

<a name="method-cookies"></a>

### cookies()
```php
cookies(array $data): array
```
Return array of Set-Cookie header.

---

<a name="method-issuccess"></a>

### isSuccess()
```php
isSuccess(): bool
```
Check http code >= 200 and <= 399

---

<a name="method-isfail"></a>

### isFail()
```php
isFail(): bool
```
Check http code >= 400

---

<a name="method-iserror"></a>

### isError()
```php
isError(): bool
```
Check http code >= 400

---

<a name="method-isnotfound"></a>

### isNotFound()
```php
isNotFound(): bool
```
Check http code is 404

---

<a name="method-isaccessdenied"></a>

### isAccessDenied()
```php
isAccessDenied(): bool
```
Check http code is 403

---

<a name="method-isinvalidmethod"></a>

### isInvalidMethod()
```php
isInvalidMethod(): bool
```
Check http code is 405

---

<a name="method-isservererror"></a>

### isServerError()
```php
isServerError(): bool
```
Check http code >= 500