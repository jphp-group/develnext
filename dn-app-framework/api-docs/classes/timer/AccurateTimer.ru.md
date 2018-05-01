# AccurateTimer

- **класс** `AccurateTimer` (`timer\AccurateTimer`)
- **пакет** `framework`
- **исходники** `timer/AccurateTimer.php`

**Описание**

Class AccurateTimer

---

#### Свойства

- `->`[`timers`](#prop-timers) : `AccurateTimer[]`
- `->`[`animTimer`](#prop-animtimer) : `UXAnimationTimer`
- `->`[`interval`](#prop-interval) : `int`
- `->`[`id`](#prop-id) : `string`
- `->`[`active`](#prop-active) : `bool`
- `->`[`free`](#prop-free) : `bool`
- `->`[`_lastTick`](#prop-_lasttick) : `int`
- `->`[`handler`](#prop-handler) : `callable`

---

#### Статичные Методы

- `AccurateTimer ::`[`init()`](#method-init)
- `AccurateTimer ::`[`__tick()`](#method-__tick)
- `AccurateTimer ::`[`executeAfter()`](#method-executeafter)

---

#### Методы

- `->`[`__construct()`](#method-__construct) - _AccurateTimer constructor._
- `->`[`trigger()`](#method-trigger)
- `->`[`stop()`](#method-stop)
- `->`[`start()`](#method-start)
- `->`[`isActive()`](#method-isactive)
- `->`[`reset()`](#method-reset)
- `->`[`free()`](#method-free)

---
# Статичные Методы

<a name="method-init"></a>

### init()
```php
AccurateTimer::init(): void
```

---

<a name="method-__tick"></a>

### __tick()
```php
AccurateTimer::__tick(): void
```

---

<a name="method-executeafter"></a>

### executeAfter()
```php
AccurateTimer::executeAfter(mixed $millis, callable $callback): AccurateTimer
```

---
# Методы

<a name="method-__construct"></a>

### __construct()
```php
__construct(int $interval, callable $callback): void
```
AccurateTimer constructor.

---

<a name="method-trigger"></a>

### trigger()
```php
trigger(): void
```

---

<a name="method-stop"></a>

### stop()
```php
stop(): void
```

---

<a name="method-start"></a>

### start()
```php
start(): void
```

---

<a name="method-isactive"></a>

### isActive()
```php
isActive(): bool
```

---

<a name="method-reset"></a>

### reset()
```php
reset(): void
```

---

<a name="method-free"></a>

### free()
```php
free(): void
```