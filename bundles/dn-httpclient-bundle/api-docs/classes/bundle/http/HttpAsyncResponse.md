# HttpAsyncResponse

- **class** `HttpAsyncResponse` (`bundle\http\HttpAsyncResponse`)
- **package** `httpclient`
- **source** `bundle/http/HttpAsyncResponse.php`

**Description**

Class HttpAsyncResponse

---

#### Properties

- `->`[`onSuccess`](#prop-onsuccess) : `mixed`
- `->`[`onError`](#prop-onerror) : `mixed`
- `->`[`onDone`](#prop-ondone) : `mixed`

---

#### Methods

- `->`[`whenDone()`](#method-whendone)
- `->`[`whenSuccess()`](#method-whensuccess)
- `->`[`whenError()`](#method-whenerror)
- `->`[`trigger()`](#method-trigger)

---
# Methods

<a name="method-whendone"></a>

### whenDone()
```php
whenDone(callable $callback): HttpAsyncResponse
```

---

<a name="method-whensuccess"></a>

### whenSuccess()
```php
whenSuccess(callable $callback): HttpAsyncResponse
```

---

<a name="method-whenerror"></a>

### whenError()
```php
whenError(callable $callback): HttpAsyncResponse
```

---

<a name="method-trigger"></a>

### trigger()
```php
trigger(bundle\http\HttpResponse $response): void
```