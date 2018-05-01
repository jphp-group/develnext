# AbstractBehaviour

- **class** `AbstractBehaviour` (`php\gui\framework\behaviour\custom\AbstractBehaviour`)
- **package** `framework`
- **source** `php/gui/framework/behaviour/custom/AbstractBehaviour.php`

**Child Classes**

> [CameraSnapBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/CameraSnapBehaviour.md), [CameraTargetBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/CameraTargetBehaviour.md), [CursorBindBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/CursorBindBehaviour.md), [DraggingBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/DraggingBehaviour.md), [DraggingFormBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/DraggingFormBehaviour.md), [EscapeShutdownBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/EscapeShutdownBehaviour.md), [GameEntityBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/GameEntityBehaviour.md), [GameSceneBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/GameSceneBehaviour.md), [GridMovementBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/GridMovementBehaviour.md), [KeyInputRuleBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/KeyInputRuleBehaviour.md), [LimitedMovementBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/LimitedMovementBehaviour.md), [WatchMakerBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/WatchMakerBehaviour.md), [WidgetFormBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/WidgetFormBehaviour.md), [WrapScreenBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/WrapScreenBehaviour.md), [AnimationBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/behaviour/custom/AnimationBehaviour.md), [EffectBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/behaviour/custom/EffectBehaviour.md)

**Description**

Class AbstractBehaviour

---

#### Properties

- `->`[`enabled`](#prop-enabled) : `bool`
- `->`[`_manager`](#prop-_manager) : [`BehaviourManager`](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/behaviour/custom/BehaviourManager.md)
- `->`[`_target`](#prop-_target) : `mixed`
- `->`[`__timers`](#prop-__timers) : `TimerScript[]`

---

#### Static Methods

- `AbstractBehaviour ::`[`get()`](#method-get)

---

#### Methods

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
# Static Methods

<a name="method-get"></a>

### get()
```php
AbstractBehaviour::get(mixed $target): $this
```

---
# Methods

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