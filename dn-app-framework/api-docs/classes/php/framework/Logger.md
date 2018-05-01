# Logger

- **class** `Logger` (`php\framework\Logger`)
- **package** `framework`
- **source** `php/framework/Logger.php`

**Description**

Class Logger

---

#### Properties

- `->`[`level`](#prop-level) : `mixed`
- `->`[`showTime`](#prop-showtime) : `mixed`

---

#### Static Methods

- `Logger ::`[`getLevel()`](#method-getlevel)
- `Logger ::`[`setLevel()`](#method-setlevel)
- `Logger ::`[`isShowTime()`](#method-isshowtime)
- `Logger ::`[`setShowTime()`](#method-setshowtime)
- `Logger ::`[`getLogName()`](#method-getlogname)
- `Logger ::`[`log()`](#method-log)
- `Logger ::`[`info()`](#method-info)
- `Logger ::`[`debug()`](#method-debug)
- `Logger ::`[`warn()`](#method-warn)
- `Logger ::`[`error()`](#method-error)

---
# Static Methods

<a name="method-getlevel"></a>

### getLevel()
```php
Logger::getLevel(): int
```

---

<a name="method-setlevel"></a>

### setLevel()
```php
Logger::setLevel(int $level): void
```

---

<a name="method-isshowtime"></a>

### isShowTime()
```php
Logger::isShowTime(): boolean
```

---

<a name="method-setshowtime"></a>

### setShowTime()
```php
Logger::setShowTime(boolean $showTime): void
```

---

<a name="method-getlogname"></a>

### getLogName()
```php
Logger::getLogName(mixed $level): void
```

---

<a name="method-log"></a>

### log()
```php
Logger::log(mixed $level, mixed $message): void
```

---

<a name="method-info"></a>

### info()
```php
Logger::info(mixed $message): void
```

---

<a name="method-debug"></a>

### debug()
```php
Logger::debug(mixed $message): void
```

---

<a name="method-warn"></a>

### warn()
```php
Logger::warn(mixed $message): void
```

---

<a name="method-error"></a>

### error()
```php
Logger::error(mixed $message): void
```