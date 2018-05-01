# ScriptEvent

- **класс** `ScriptEvent` (`php\gui\framework\ScriptEvent`) **унаследован от** `stdClass` (`stdClass`)
- **пакет** `framework`
- **исходники** `php/gui/framework/ScriptEvent.php`

**Описание**

Class ScriptEvent

---

#### Свойства

- `->`[`sender`](#prop-sender) : [`AbstractScript`](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/AbstractScript.ru.md)
- `->`[`target`](#prop-target) : `AbstractScript|mixed`
- `->`[`usage`](#prop-usage) : `int`
- `->`[`consumed`](#prop-consumed) : `bool`

---

#### Методы

- `->`[`__construct()`](#method-__construct) - _ScriptEvent constructor._
- `->`[`done()`](#method-done)
- `->`[`isDone()`](#method-isdone)
- `->`[`consume()`](#method-consume) - _Consume event._
- `->`[`isConsumed()`](#method-isconsumed)

---
# Методы

<a name="method-__construct"></a>

### __construct()
```php
__construct(php\gui\framework\AbstractScript $sender, null $target): void
```
ScriptEvent constructor.

---

<a name="method-done"></a>

### done()
```php
done(): void
```

---

<a name="method-isdone"></a>

### isDone()
```php
isDone(): void
```

---

<a name="method-consume"></a>

### consume()
```php
consume(): void
```
Consume event.

---

<a name="method-isconsumed"></a>

### isConsumed()
```php
isConsumed(): bool
```