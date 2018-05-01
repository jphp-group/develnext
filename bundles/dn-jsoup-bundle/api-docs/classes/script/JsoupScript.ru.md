# JsoupScript

- **класс** `JsoupScript` (`script\JsoupScript`) **унаследован от** `AbstractScript` (`php\gui\framework\AbstractScript`)
- **исходники** `script/JsoupScript.php`

---

#### Свойства

- `->`[`_url`](#prop-_url) : `string`
- `->`[`encoding`](#prop-encoding) : `string`
- `->`[`method`](#prop-method) : `string`
- `->`[`proxyType`](#prop-proxytype) : `string` - _HTTP, SOCKS,_
- `->`[`proxy`](#prop-proxy) : `string`
- `->`[`userAgent`](#prop-useragent) : `string`
- `->`[`followRedirects`](#prop-followredirects) : `bool`
- `->`[`referrer`](#prop-referrer) : `string`
- `->`[`data`](#prop-data) : `array`
- `->`[`cookies`](#prop-cookies) : `array`
- `->`[`headers`](#prop-headers) : `array`
- `->`[`timeout`](#prop-timeout) : `int`
- `->`[`ignoreHttpErrors`](#prop-ignorehttperrors) : `bool`
- `->`[`ignoreContentType`](#prop-ignorecontenttype) : `bool`
- `->`[`autoParse`](#prop-autoparse) : `bool`
- `->`[`autoCookies`](#prop-autocookies) : `bool`
- `->`[`_document`](#prop-_document) : `null|Document`
- `->`[`_response`](#prop-_response) : `null|ConnectionResponse`
- `->`[`_loaded`](#prop-_loaded) : `mixed`

---

#### Статичные Методы

- `JsoupScript ::`[`textToArray()`](#method-texttoarray)

---

#### Методы

- `->`[`applyImpl()`](#method-applyimpl)
- `->`[`prepareConnection()`](#method-prepareconnection)
- `->`[`parse()`](#method-parse)
- `->`[`parseAsync()`](#method-parseasync)
- `->`[`isReady()`](#method-isready)
- `->`[`getReady()`](#method-getready)
- `->`[`getResponseCookies()`](#method-getresponsecookies)
- `->`[`getResponseHeaders()`](#method-getresponseheaders)
- `->`[`getContent()`](#method-getcontent)
- `->`[`getCharset()`](#method-getcharset)
- `->`[`getStatusCode()`](#method-getstatuscode)
- `->`[`getStatusMessage()`](#method-getstatusmessage)
- `->`[`getContentType()`](#method-getcontenttype)
- `->`[`getTitle()`](#method-gettitle)
- `->`[`getBody()`](#method-getbody)
- `->`[`getHead()`](#method-gethead)
- `->`[`find()`](#method-find)
- `->`[`findFirst()`](#method-findfirst)
- `->`[`findLast()`](#method-findlast)
- `->`[`getUrl()`](#method-geturl)
- `->`[`setUrl()`](#method-seturl)

---
# Статичные Методы

<a name="method-texttoarray"></a>

### textToArray()
```php
JsoupScript::textToArray(mixed $text, mixed $trimValues): void
```

---
# Методы

<a name="method-applyimpl"></a>

### applyImpl()
```php
applyImpl(mixed $target): mixed
```

---

<a name="method-prepareconnection"></a>

### prepareConnection()
```php
prepareConnection(php\jsoup\Connection $connection): void
```

---

<a name="method-parse"></a>

### parse()
```php
parse(string $relativePath): null
```

---

<a name="method-parseasync"></a>

### parseAsync()
```php
parseAsync(string $relativePath, callable|null $callback): void
```

---

<a name="method-isready"></a>

### isReady()
```php
isReady(): bool
```

---

<a name="method-getready"></a>

### getReady()
```php
getReady(): bool
```

---

<a name="method-getresponsecookies"></a>

### getResponseCookies()
```php
getResponseCookies(): array
```

---

<a name="method-getresponseheaders"></a>

### getResponseHeaders()
```php
getResponseHeaders(): array
```

---

<a name="method-getcontent"></a>

### getContent()
```php
getContent(): string
```

---

<a name="method-getcharset"></a>

### getCharset()
```php
getCharset(): string
```

---

<a name="method-getstatuscode"></a>

### getStatusCode()
```php
getStatusCode(): int
```

---

<a name="method-getstatusmessage"></a>

### getStatusMessage()
```php
getStatusMessage(): int
```

---

<a name="method-getcontenttype"></a>

### getContentType()
```php
getContentType(): int
```

---

<a name="method-gettitle"></a>

### getTitle()
```php
getTitle(): string
```

---

<a name="method-getbody"></a>

### getBody()
```php
getBody(): Element
```

---

<a name="method-gethead"></a>

### getHead()
```php
getHead(): Element
```

---

<a name="method-find"></a>

### find()
```php
find(mixed $query): Elements
```

---

<a name="method-findfirst"></a>

### findFirst()
```php
findFirst(mixed $query): Element
```

---

<a name="method-findlast"></a>

### findLast()
```php
findLast(mixed $query): Element
```

---

<a name="method-geturl"></a>

### getUrl()
```php
getUrl(): string
```

---

<a name="method-seturl"></a>

### setUrl()
```php
setUrl(string $url): void
```