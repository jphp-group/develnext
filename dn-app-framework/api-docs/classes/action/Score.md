# Score

- **class** `Score` (`action\Score`)
- **package** `framework`
- **source** `action/Score.php`

**Description**

Class Score

---

#### Properties

- `->`[`values`](#prop-values) : `mixed`
- `->`[`handlers`](#prop-handlers) : `array`

---

#### Static Methods

- `Score ::`[`set()`](#method-set)
- `Score ::`[`inc()`](#method-inc)
- `Score ::`[`get()`](#method-get)
- `Score ::`[`on()`](#method-on) - _Event variants: beforeChange and afterChange._
- `Score ::`[`bind()`](#method-bind) - _Event variants: beforeChange and afterChange._
- `Score ::`[`off()`](#method-off) - _Event variants: beforeChange and afterChange._
- `Score ::`[`trigger()`](#method-trigger) - _Event variants: beforeChange and afterChange._

---
# Static Methods

<a name="method-set"></a>

### set()
```php
Score::set(string $name, mixed $value): void
```

---

<a name="method-inc"></a>

### inc()
```php
Score::inc(mixed $name, int $value): void
```

---

<a name="method-get"></a>

### get()
```php
Score::get(mixed $name): int
```

---

<a name="method-on"></a>

### on()
```php
Score::on(string $event, callable $handler, string $group): void
```
Event variants: beforeChange and afterChange.

---

<a name="method-bind"></a>

### bind()
```php
Score::bind(string $event, callable $handler): string
```
Event variants: beforeChange and afterChange.

---

<a name="method-off"></a>

### off()
```php
Score::off(string $event, string|null $group): void
```
Event variants: beforeChange and afterChange.

---

<a name="method-trigger"></a>

### trigger()
```php
Score::trigger(string $event, array $args): mixed
```
Event variants: beforeChange and afterChange.