# MacroScript

- **класс** `MacroScript` (`script\MacroScript`) **унаследован от** [`AbstractScript`](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/AbstractScript.ru.md)
- **пакет** `framework`
- **исходники** `script/MacroScript.php`

**Описание**

Class MacroScript

---

#### Свойства

- `->`[`runOnce`](#prop-runonce) : `bool`
- `->`[`runOnApply`](#prop-runonapply) : `bool`
- `->`[`description`](#prop-description) : `string`
- `->`[`runCount`](#prop-runcount) : `int`
- `->`[`alreadyRun`](#prop-alreadyrun) : `array`
- *См. также в родительском классе* [AbstractScript](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/AbstractScript.ru.md).

---

#### Методы

- `->`[`applyImpl()`](#method-applyimpl)
- `->`[`call()`](#method-call) - _Выполнить скрипт._
- `->`[`callUiLater()`](#method-calluilater) - _Выполнить скрипт в UI потоке._
- `->`[`callAsync()`](#method-callasync) - _Выполнить скрипт в отдельном потоке (фоном)._
- `->`[`callAfter()`](#method-callafter) - _Выполнить скрипт после временного премежутка, см. также Timer::after()._
- `->`[`callEvery()`](#method-callevery) - _Выполнять скрипт каждый временной премежуток, см. также Timer::every()._
- `->`[`setEnabled()`](#method-setenabled)
- `->`[`getEnabled()`](#method-getenabled)
- См. также в родительском классе [AbstractScript](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/AbstractScript.ru.md)

---
# Методы

<a name="method-applyimpl"></a>

### applyImpl()
```php
applyImpl(mixed $target): mixed
```

---

<a name="method-call"></a>

### call()
```php
call(): mixed
```
Выполнить скрипт.

---

<a name="method-calluilater"></a>

### callUiLater()
```php
callUiLater(): void
```
Выполнить скрипт в UI потоке.

---

<a name="method-callasync"></a>

### callAsync()
```php
callAsync(callable|null $callback): mixed
```
Выполнить скрипт в отдельном потоке (фоном).

---

<a name="method-callafter"></a>

### callAfter()
```php
callAfter(string $period, callable $callback): Timer
```
Выполнить скрипт после временного премежутка, см. также Timer::after().

---

<a name="method-callevery"></a>

### callEvery()
```php
callEvery(string $period, callable|null $callback): Timer
```
Выполнять скрипт каждый временной премежуток, см. также Timer::every().

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