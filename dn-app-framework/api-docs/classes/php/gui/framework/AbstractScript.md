# AbstractScript

- **class** `AbstractScript` (`php\gui\framework\AbstractScript`)
- **package** `framework`
- **source** `php/gui/framework/AbstractScript.php`

**Child Classes**

> [AbstractModule](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/AbstractModule.md), [DirectoryChooserScript](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/script/DirectoryChooserScript.md), [FileChooserScript](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/script/FileChooserScript.md), [FileScript](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/script/FileScript.md), [MacroScript](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/script/MacroScript.md), [MediaPlayerScript](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/script/MediaPlayerScript.md), [PrinterScript](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/script/PrinterScript.md), [RobotScript](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/script/RobotScript.md), [ScoreScript](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/script/ScoreScript.md), [AbstractStorage](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/script/storage/AbstractStorage.md), [TimerScript](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/script/TimerScript.md)

**Description**

Class AbstractScript

---

#### Properties

- `->`[`applied`](#prop-applied) : `bool`
- `->`[`_enabledSetters`](#prop-_enabledsetters) : `bool`
- `->`[`_enabledGetters`](#prop-_enabledgetters) : `bool`
- `->`[`_context`](#prop-_context) : `mixed`
- `->`[`_owner`](#prop-_owner) : `mixed`
- `->`[`id`](#prop-id) : `string`
- `->`[`disabled`](#prop-disabled) : `bool`
- `->`[`handlers`](#prop-handlers) : `callable[]`

---

#### Methods

- `->`[`form()`](#method-form)
- `->`[`apply()`](#method-apply)
- `->`[`isApplied()`](#method-isapplied)
- `->`[`getOwner()`](#method-getowner)
- `->`[`data()`](#method-data)
- `->`[`applyImpl()`](#method-applyimpl)
- `->`[`trigger()`](#method-trigger)
- `->`[`on()`](#method-on)
- `->`[`off()`](#method-off)
- `->`[`__set()`](#method-__set)
- `->`[`__get()`](#method-__get)
- `->`[`__isset()`](#method-__isset)
- `->`[`free()`](#method-free)
- `->`[`isFree()`](#method-isfree)

---
# Methods

<a name="method-form"></a>

### form()
```php
form(mixed $name): AbstractForm
```

---

<a name="method-apply"></a>

### apply()
```php
apply(mixed $target): void
```

---

<a name="method-isapplied"></a>

### isApplied()
```php
isApplied(): boolean
```

---

<a name="method-getowner"></a>

### getOwner()
```php
getOwner(): mixed
```

---

<a name="method-data"></a>

### data()
```php
data(mixed $args): 1
```

---

<a name="method-applyimpl"></a>

### applyImpl()
```php
applyImpl(mixed $target): mixed
```

---

<a name="method-trigger"></a>

### trigger()
```php
trigger(string $eventType, array $args): ScriptEvent
```

---

<a name="method-on"></a>

### on()
```php
on(mixed $event, callable $handler, mixed $group): void
```

---

<a name="method-off"></a>

### off()
```php
off(mixed $event): void
```

---

<a name="method-__set"></a>

### __set()
```php
__set(mixed $name, mixed $value): void
```

---

<a name="method-__get"></a>

### __get()
```php
__get(mixed $name): void
```

---

<a name="method-__isset"></a>

### __isset()
```php
__isset(mixed $name): void
```

---

<a name="method-free"></a>

### free()
```php
free(): void
```

---

<a name="method-isfree"></a>

### isFree()
```php
isFree(): void
```