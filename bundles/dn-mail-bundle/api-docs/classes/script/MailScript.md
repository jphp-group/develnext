# MailScript

- **class** `MailScript` (`script\MailScript`) **extends** `AbstractScript` (`php\gui\framework\AbstractScript`)
- **source** `script/MailScript.php`

---

#### Properties

- `->`[`hostName`](#prop-hostname) : `string`
- `->`[`smtpPort`](#prop-smtpport) : `int`
- `->`[`sslOnConnect`](#prop-sslonconnect) : `bool`
- `->`[`login`](#prop-login) : `string`
- `->`[`password`](#prop-password) : `string`
- `->`[`timeout`](#prop-timeout) : `int`
- `->`[`mailCharset`](#prop-mailcharset) : `string`
- `->`[`mailFrom`](#prop-mailfrom) : `string`

---

#### Methods

- `->`[`applyImpl()`](#method-applyimpl)
- `->`[`fetchBackend()`](#method-fetchbackend)
- `->`[`fetchMail()`](#method-fetchmail)
- `->`[`send()`](#method-send)
- `->`[`sendAsync()`](#method-sendasync)

---
# Methods

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