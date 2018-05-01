# MacroScript

- **class** `MacroScript` (`script\MacroScript`) **extends** [`AbstractScript`](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/AbstractScript.md)
- **package** `framework`
- **source** `script/MacroScript.php`

**Description**

Class MacroScript

---

#### Properties

- `->`[`runOnce`](#prop-runonce) : `bool`
- `->`[`runOnApply`](#prop-runonapply) : `bool`
- `->`[`description`](#prop-description) : `string`
- `->`[`runCount`](#prop-runcount) : `int`
- `->`[`alreadyRun`](#prop-alreadyrun) : `array`
- *See also in the parent class* [AbstractScript](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/AbstractScript.md).

---

#### Methods

- `->`[`applyImpl()`](#method-applyimpl)
- `->`[`call()`](#method-call) - _Simple call._
- `->`[`callUiLater()`](#method-calluilater) - _Call in UI Thread._
- `->`[`callAsync()`](#method-callasync) - _Call in thread._
- `->`[`callAfter()`](#method-callafter) - _Call after time period as in Timer::after()._
- `->`[`callEvery()`](#method-callevery) - _Call every time period as in Timer::every()._
- `->`[`setEnabled()`](#method-setenabled)
- `->`[`getEnabled()`](#method-getenabled)
- See also in the parent class [AbstractScript](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/AbstractScript.md)

---
# Methods

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
Simple call.

---

<a name="method-calluilater"></a>

### callUiLater()
```php
callUiLater(): void
```
Call in UI Thread.

---

<a name="method-callasync"></a>

### callAsync()
```php
callAsync(callable|null $callback): mixed
```
Call in thread.

---

<a name="method-callafter"></a>

### callAfter()
```php
callAfter(string $period, callable $callback): Timer
```
Call after time period as in Timer::after().

---

<a name="method-callevery"></a>

### callEvery()
```php
callEvery(string $period, callable|null $callback): Timer
```
Call every time period as in Timer::every().

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