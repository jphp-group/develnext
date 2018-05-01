# TimerScript

- **класс** `TimerScript` (`script\TimerScript`) **унаследован от** [`AbstractScript`](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/AbstractScript.ru.md)
- **пакет** `framework`
- **исходники** `script/TimerScript.php`

**Описание**

Class TimerScript

---

#### Свойства

- `->`[`interval`](#prop-interval) : `int`
- `->`[`repeatable`](#prop-repeatable) : `bool`
- `->`[`autoStart`](#prop-autostart) : `bool`
- `->`[`stopped`](#prop-stopped) : `bool`
- `->`[`th`](#prop-th) : `Timer`
- *См. также в родительском классе* [AbstractScript](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/AbstractScript.ru.md).

---

#### Статичные Методы

- `TimerScript ::`[`executeAfter()`](#method-executeafter) **common.deprecated**
- `TimerScript ::`[`executeWhile()`](#method-executewhile)
- См. также в родительском классе [AbstractScript](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/AbstractScript.ru.md)

---

#### Методы

- `->`[`__construct()`](#method-__construct) - _TimerScript constructor._
- `->`[`applyImpl()`](#method-applyimpl)
- `->`[`start()`](#method-start) - _Запустить таймер._
- `->`[`stop()`](#method-stop) - _Остановить таймер._
- `->`[`free()`](#method-free) - _Уничтожить и остановить таймер._
- `->`[`isStopped()`](#method-isstopped) - _Таймер остановлен или нет._
- `->`[`isRunning()`](#method-isrunning)
- `->`[`doInterval()`](#method-dointerval)
- `->`[`getInterval()`](#method-getinterval)
- `->`[`setInterval()`](#method-setinterval)
- `->`[`isRepeatable()`](#method-isrepeatable)
- `->`[`setRepeatable()`](#method-setrepeatable)
- `->`[`setEnabled()`](#method-setenabled)
- `->`[`getEnabled()`](#method-getenabled)
- `->`[`setEnable()`](#method-setenable)
- `->`[`getEnable()`](#method-getenable)
- `->`[`getObjectValue()`](#method-getobjectvalue)
- `->`[`setObjectValue()`](#method-setobjectvalue)
- `->`[`appendObjectValue()`](#method-appendobjectvalue)
- См. также в родительском классе [AbstractScript](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/AbstractScript.ru.md)

---
# Статичные Методы

<a name="method-executeafter"></a>

### executeAfter()
```php
TimerScript::executeAfter(string $period, callable $callback): TimerScript
```

---

<a name="method-executewhile"></a>

### executeWhile()
```php
TimerScript::executeWhile(callable $condition, callable $callback, mixed $checkInterval): void
```

---
# Методы

<a name="method-__construct"></a>

### __construct()
```php
__construct(int $interval, bool $repeatable, callable $action): void
```
TimerScript constructor.

---

<a name="method-applyimpl"></a>

### applyImpl()
```php
applyImpl(mixed $target): mixed
```

---

<a name="method-start"></a>

### start()
```php
start(bool $force): void
```
Запустить таймер.

---

<a name="method-stop"></a>

### stop()
```php
stop(): void
```
Остановить таймер.

---

<a name="method-free"></a>

### free()
```php
free(): void
```
Уничтожить и остановить таймер.

---

<a name="method-isstopped"></a>

### isStopped()
```php
isStopped(): bool
```
Таймер остановлен или нет.

---

<a name="method-isrunning"></a>

### isRunning()
```php
isRunning(): void
```

---

<a name="method-dointerval"></a>

### doInterval()
```php
doInterval(): void
```

---

<a name="method-getinterval"></a>

### getInterval()
```php
getInterval(): int
```

---

<a name="method-setinterval"></a>

### setInterval()
```php
setInterval(int $interval): void
```

---

<a name="method-isrepeatable"></a>

### isRepeatable()
```php
isRepeatable(): bool
```

---

<a name="method-setrepeatable"></a>

### setRepeatable()
```php
setRepeatable(bool $repeatable): void
```

---

<a name="method-setenabled"></a>

### setEnabled()
```php
setEnabled(mixed $value): void
```

---

<a name="method-getenabled"></a>

### getEnabled()
```php
getEnabled(): void
```

---

<a name="method-setenable"></a>

### setEnable()
```php
setEnable(mixed $value): void
```

---

<a name="method-getenable"></a>

### getEnable()
```php
getEnable(): void
```

---

<a name="method-getobjectvalue"></a>

### getObjectValue()
```php
getObjectValue(): void
```

---

<a name="method-setobjectvalue"></a>

### setObjectValue()
```php
setObjectValue(mixed $value): void
```

---

<a name="method-appendobjectvalue"></a>

### appendObjectValue()
```php
appendObjectValue(mixed $value): void
```