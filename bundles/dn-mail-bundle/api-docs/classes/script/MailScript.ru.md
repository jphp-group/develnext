# MailScript

- **класс** `MailScript` (`script\MailScript`) **унаследован от** `AbstractScript` (`php\gui\framework\AbstractScript`)
- **исходники** `script/MailScript.php`

---

#### Свойства

- `->`[`hostName`](#prop-hostname) : `string`
- `->`[`smtpPort`](#prop-smtpport) : `int`
- `->`[`sslOnConnect`](#prop-sslonconnect) : `bool`
- `->`[`login`](#prop-login) : `string`
- `->`[`password`](#prop-password) : `string`
- `->`[`timeout`](#prop-timeout) : `int`
- `->`[`mailCharset`](#prop-mailcharset) : `string`
- `->`[`mailFrom`](#prop-mailfrom) : `string`

---

#### Методы

- `->`[`applyImpl()`](#method-applyimpl)
- `->`[`fetchBackend()`](#method-fetchbackend)
- `->`[`fetchMail()`](#method-fetchmail)
- `->`[`send()`](#method-send)
- `->`[`sendAsync()`](#method-sendasync)

---
# Методы

<a name="method-applyimpl"></a>

### applyImpl()
```php
applyImpl(mixed $target): mixed
```

---

<a name="method-fetchbackend"></a>

### fetchBackend()
```php
fetchBackend(): void
```

---

<a name="method-fetchmail"></a>

### fetchMail()
```php
fetchMail(mixed $email): void
```

---

<a name="method-send"></a>

### send()
```php
send(Email|array $email): null
```

---

<a name="method-sendasync"></a>

### sendAsync()
```php
sendAsync(mixed $email, callable|null $callback): void
```