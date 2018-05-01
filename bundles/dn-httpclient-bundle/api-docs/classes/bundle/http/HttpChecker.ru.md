# HttpChecker

- **класс** `HttpChecker` (`bundle\http\HttpChecker`) **унаследован от** `AbstractScript` (`php\gui\framework\AbstractScript`)
- **пакет** `httpclient`
- **исходники** `bundle/http/HttpChecker.php`

**Описание**

Class HttpChecker

---

#### Свойства

- `->`[`url`](#prop-url) : `string`
- `->`[`autoStart`](#prop-autostart) : `bool`
- `->`[`_checkInterval`](#prop-_checkinterval) : `int`
- `->`[`client`](#prop-client) : [`HttpClient`](https://github.com/jphp-compiler/develnext/blob/master/bundles/dn-httpclient-bundle/api-docs/classes/bundle/http/HttpClient.ru.md)
- `->`[`checkTimer`](#prop-checktimer) : `AccurateTimer`
- `->`[`available`](#prop-available) : `boolean`

---

#### Методы

- `->`[`__construct()`](#method-__construct) - _..._
- `->`[`applyImpl()`](#method-applyimpl)
- `->`[`doInterval()`](#method-dointerval)
- `->`[`ping()`](#method-ping) - _Check status of url._
- `->`[`start()`](#method-start) - _Start checker worker._
- `->`[`stop()`](#method-stop) - _Stop checker worker._
- `->`[`client()`](#method-client)
- `->`[`getCheckInterval()`](#method-getcheckinterval)
- `->`[`setCheckInterval()`](#method-setcheckinterval)
- `->`[`isOffline()`](#method-isoffline)
- `->`[`isOnline()`](#method-isonline)
- `->`[`isUnknown()`](#method-isunknown)

---
# Методы

<a name="method-__construct"></a>

### __construct()
```php
__construct(): void
```
...

---

<a name="method-applyimpl"></a>

### applyImpl()
```php
applyImpl(mixed $target): mixed
```

---

<a name="method-dointerval"></a>

### doInterval()
```php
doInterval(): void
```

---

<a name="method-ping"></a>

### ping()
```php
ping(): void
```
Check status of url.

---

<a name="method-start"></a>

### start()
```php
start(): void
```
Start checker worker.

---

<a name="method-stop"></a>

### stop()
```php
stop(): void
```
Stop checker worker.

---

<a name="method-client"></a>

### client()
```php
client(): HttpClient
```

---

<a name="method-getcheckinterval"></a>

### getCheckInterval()
```php
getCheckInterval(): int
```

---

<a name="method-setcheckinterval"></a>

### setCheckInterval()
```php
setCheckInterval(int $checkInterval): void
```

---

<a name="method-isoffline"></a>

### isOffline()
```php
isOffline(): bool
```

---

<a name="method-isonline"></a>

### isOnline()
```php
isOnline(): bool
```

---

<a name="method-isunknown"></a>

### isUnknown()
```php
isUnknown(): void
```