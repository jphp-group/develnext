# EventBinder

- **class** `EventBinder` (`php\gui\framework\EventBinder`)
- **package** `framework`
- **source** `php/gui/framework/EventBinder.php`

**Description**

Class EventBinder

---

#### Properties

- `->`[`context`](#prop-context) : `object`
- `->`[`handler`](#prop-handler) : `object`
- `->`[`binds`](#prop-binds) : `array`
- `->`[`lookup`](#prop-lookup) : `null|callable`

---

#### Methods

- `->`[`__construct()`](#method-__construct) - _EventBinder constructor._
- `->`[`setLookup()`](#method-setlookup)
- `->`[`loadBinds()`](#method-loadbinds)
- `->`[`trigger()`](#method-trigger)
- `->`[`loadBind()`](#method-loadbind)
- `->`[`load()`](#method-load)
- `->`[`getNodeWrapper()`](#method-getnodewrapper)
- `->`[`bind()`](#method-bind)

---
# Methods

<a name="method-__construct"></a>

### __construct()
```php
__construct(mixed $context, mixed $handler): void
```
EventBinder constructor.

---

<a name="method-setlookup"></a>

### setLookup()
```php
setLookup(callable|null $lookup): void
```

---

<a name="method-loadbinds"></a>

### loadBinds()
```php
loadBinds(): callable[]
```

---

<a name="method-trigger"></a>

### trigger()
```php
trigger(mixed $target, mixed $id, mixed $code): void
```

---

<a name="method-loadbind"></a>

### loadBind()
```php
loadBind(mixed $target, mixed $id, mixed $group, mixed $ignoreErrors): void
```

---

<a name="method-load"></a>

### load()
```php
load(callable|null $filter): void
```

---

<a name="method-getnodewrapper"></a>

### getNodeWrapper()
```php
getNodeWrapper(UXWindow|UXNode $node): UXNodeWrapper
```

---

<a name="method-bind"></a>

### bind()
```php
bind(mixed $event, callable $handler, mixed $group): void
```