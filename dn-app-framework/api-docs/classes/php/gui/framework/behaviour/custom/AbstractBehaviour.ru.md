# AbstractBehaviour

- **класс** `AbstractBehaviour` (`php\gui\framework\behaviour\custom\AbstractBehaviour`)
- **пакет** `framework`
- **исходники** `php/gui/framework/behaviour/custom/AbstractBehaviour.php`

**Классы наследники**

> [CameraSnapBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/CameraSnapBehaviour.ru.md), [CameraTargetBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/CameraTargetBehaviour.ru.md), [CursorBindBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/CursorBindBehaviour.ru.md), [DraggingBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/DraggingBehaviour.ru.md), [DraggingFormBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/DraggingFormBehaviour.ru.md), [EscapeShutdownBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/EscapeShutdownBehaviour.ru.md), [GameEntityBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/GameEntityBehaviour.ru.md), [GameSceneBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/GameSceneBehaviour.ru.md), [GridMovementBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/GridMovementBehaviour.ru.md), [KeyInputRuleBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/KeyInputRuleBehaviour.ru.md), [LimitedMovementBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/LimitedMovementBehaviour.ru.md), [WatchMakerBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/WatchMakerBehaviour.ru.md), [WidgetFormBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/WidgetFormBehaviour.ru.md), [WrapScreenBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/WrapScreenBehaviour.ru.md), [AnimationBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/behaviour/custom/AnimationBehaviour.ru.md), [EffectBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/behaviour/custom/EffectBehaviour.ru.md)

**Описание**

Class AbstractBehaviour

---

#### Свойства

- `->`[`enabled`](#prop-enabled) : `bool`
- `->`[`_manager`](#prop-_manager) : [`BehaviourManager`](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/behaviour/custom/BehaviourManager.ru.md)
- `->`[`_target`](#prop-_target) : `mixed`
- `->`[`__timers`](#prop-__timers) : `TimerScript[]`

---

#### Статичные Методы

- `AbstractBehaviour ::`[`get()`](#method-get)

---

#### Методы

- `->`[`__construct()`](#method-__construct) - _AbstractBehaviour constructor._
- `->`[`getCode()`](#method-getcode)
- `->`[`applyImpl()`](#method-applyimpl)
- `->`[`setProperties()`](#method-setproperties)
- `->`[`getProperties()`](#method-getproperties)
- `->`[`apply()`](#method-apply)
- `->`[`disable()`](#method-disable)
- `->`[`enable()`](#method-enable)
- `->`[`getSort()`](#method-getsort)
- `->`[`timer()`](#method-timer)
- `->`[`accurateTimer()`](#method-accuratetimer)
- `->`[`free()`](#method-free)
- `->`[`__clone()`](#method-__clone)
- `->`[`__set()`](#method-__set)
- `->`[`__get()`](#method-__get)
- `->`[`__isset()`](#method-__isset)

---
# Статичные Методы

<a name="method-get"></a>

### get()
```php
AbstractBehaviour::get(mixed $target): $this
```

---
# Методы

<a name="method-__construct"></a>

### __construct()
```php
__construct(mixed $target): void
```
AbstractBehaviour constructor.

---

<a name="method-getcode"></a>

### getCode()
```php
getCode(): string
```

---

<a name="method-applyimpl"></a>

### applyImpl()
```php
applyImpl(mixed $target): void
```

---

<a name="method-setproperties"></a>

### setProperties()
```php
setProperties(array $properties): void
```

---

<a name="method-getproperties"></a>

### getProperties()
```php
getProperties(): array
```

---

<a name="method-apply"></a>

### apply()
```php
apply(mixed $target): void
```

---

<a name="method-disable"></a>

### disable()
```php
disable(): void
```

---

<a name="method-enable"></a>

### enable()
```php
enable(): void
```

---

<a name="method-getsort"></a>

### getSort()
```php
getSort(): int
```

---

<a name="method-timer"></a>

### timer()
```php
timer(mixed $interval, callable $callback): void
```

---

<a name="method-accuratetimer"></a>

### accurateTimer()
```php
accurateTimer(mixed $interval, callable $handle): void
```

---

<a name="method-free"></a>

### free()
```php
free(): void
```

---

<a name="method-__clone"></a>

### __clone()
```php
__clone(): void
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