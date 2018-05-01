# AnimationBehaviour

- **класс** `AnimationBehaviour` (`php\gui\framework\behaviour\custom\AnimationBehaviour`) **унаследован от** [`AbstractBehaviour`](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/behaviour/custom/AbstractBehaviour.ru.md)
- **пакет** `framework`
- **исходники** `php/gui/framework/behaviour/custom/AnimationBehaviour.php`

**Классы наследники**

> [AutoDestroyBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/AutoDestroyBehaviour.ru.md), [BlinkAnimationBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/BlinkAnimationBehaviour.ru.md), [ChatterAnimationBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/ChatterAnimationBehaviour.ru.md), [FadeAnimationBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/FadeAnimationBehaviour.ru.md), [PulseAnimationBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/PulseAnimationBehaviour.ru.md), [RandomMovementAnimationBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/RandomMovementAnimationBehaviour.ru.md), [RotateAnimationBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/RotateAnimationBehaviour.ru.md), [ScaleAnimationBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/ScaleAnimationBehaviour.ru.md), [VibrationAnimationBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/VibrationAnimationBehaviour.ru.md)

**Описание**

Class AnimationBehaviour

---

#### Свойства

- `->`[`delay`](#prop-delay) : `int`
- `->`[`duration`](#prop-duration) : `int`
- `->`[`when`](#prop-when) : `string`
- `->`[`repeatCount`](#prop-repeatcount) : `int` - _Сколько раз повторить анимацию, -1 значит бесконечно раз_
- `->`[`__animTimers`](#prop-__animtimers) : `UXAnimationTimer[]`
- *См. также в родительском классе* [AbstractBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/behaviour/custom/AbstractBehaviour.ru.md).

---

#### Методы

- `->`[`apply()`](#method-apply)
- `->`[`__apply()`](#method-__apply)
- `->`[`getWhenEventTypes()`](#method-getwheneventtypes)
- `->`[`checkRepeatLimits()`](#method-checkrepeatlimits)
- `->`[`animTimer()`](#method-animtimer)
- `->`[`free()`](#method-free)
- `->`[`restore()`](#method-restore)
- См. также в родительском классе [AbstractBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/behaviour/custom/AbstractBehaviour.ru.md)

---
# Методы

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