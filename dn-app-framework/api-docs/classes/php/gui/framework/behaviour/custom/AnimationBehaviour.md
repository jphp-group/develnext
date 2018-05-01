# AnimationBehaviour

- **class** `AnimationBehaviour` (`php\gui\framework\behaviour\custom\AnimationBehaviour`) **extends** [`AbstractBehaviour`](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/behaviour/custom/AbstractBehaviour.md)
- **package** `framework`
- **source** `php/gui/framework/behaviour/custom/AnimationBehaviour.php`

**Child Classes**

> [AutoDestroyBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/AutoDestroyBehaviour.md), [BlinkAnimationBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/BlinkAnimationBehaviour.md), [ChatterAnimationBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/ChatterAnimationBehaviour.md), [FadeAnimationBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/FadeAnimationBehaviour.md), [PulseAnimationBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/PulseAnimationBehaviour.md), [RandomMovementAnimationBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/RandomMovementAnimationBehaviour.md), [RotateAnimationBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/RotateAnimationBehaviour.md), [ScaleAnimationBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/ScaleAnimationBehaviour.md), [VibrationAnimationBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/VibrationAnimationBehaviour.md)

**Description**

Class AnimationBehaviour

---

#### Properties

- `->`[`delay`](#prop-delay) : `int`
- `->`[`duration`](#prop-duration) : `int`
- `->`[`when`](#prop-when) : `string`
- `->`[`repeatCount`](#prop-repeatcount) : `int` - _Сколько раз повторить анимацию, -1 значит бесконечно раз_
- `->`[`__animTimers`](#prop-__animtimers) : `UXAnimationTimer[]`
- *See also in the parent class* [AbstractBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/behaviour/custom/AbstractBehaviour.md).

---

#### Methods

- `->`[`apply()`](#method-apply)
- `->`[`__apply()`](#method-__apply)
- `->`[`getWhenEventTypes()`](#method-getwheneventtypes)
- `->`[`checkRepeatLimits()`](#method-checkrepeatlimits)
- `->`[`animTimer()`](#method-animtimer)
- `->`[`free()`](#method-free)
- `->`[`restore()`](#method-restore)
- See also in the parent class [AbstractBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/behaviour/custom/AbstractBehaviour.md)

---
# Methods

<a name="method-apply"></a>

### apply()
```php
apply(mixed $target): void
```

---

<a name="method-__apply"></a>

### __apply()
```php
__apply(mixed $target): void
```

---

<a name="method-getwheneventtypes"></a>

### getWhenEventTypes()
```php
getWhenEventTypes(): void
```

---

<a name="method-checkrepeatlimits"></a>

### checkRepeatLimits()
```php
checkRepeatLimits(): bool
```

---

<a name="method-animtimer"></a>

### animTimer()
```php
animTimer(callable $func): void
```

---

<a name="method-free"></a>

### free()
```php
free(): void
```

---

<a name="method-restore"></a>

### restore()
```php
restore(): void
```