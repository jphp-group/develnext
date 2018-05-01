# HttpAsyncResponse

- **класс** `HttpAsyncResponse` (`bundle\http\HttpAsyncResponse`)
- **пакет** `httpclient`
- **исходники** `bundle/http/HttpAsyncResponse.php`

**Описание**

Class HttpAsyncResponse

---

#### Свойства

- `->`[`onSuccess`](#prop-onsuccess) : `mixed`
- `->`[`onError`](#prop-onerror) : `mixed`
- `->`[`onDone`](#prop-ondone) : `mixed`

---

#### Методы

- `->`[`whenDone()`](#method-whendone)
- `->`[`whenSuccess()`](#method-whensuccess)
- `->`[`whenError()`](#method-whenerror)
- `->`[`trigger()`](#method-trigger)

---
# Методы

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