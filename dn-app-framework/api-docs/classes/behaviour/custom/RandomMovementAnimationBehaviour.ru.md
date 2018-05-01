# RandomMovementAnimationBehaviour

- **класс** `RandomMovementAnimationBehaviour` (`behaviour\custom\RandomMovementAnimationBehaviour`) **унаследован от** [`AnimationBehaviour`](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/behaviour/custom/AnimationBehaviour.ru.md)
- **пакет** `framework`
- **исходники** `behaviour/custom/RandomMovementAnimationBehaviour.php`

**Описание**

Class RandomMovementAnimationBehaviour

---

#### Свойства

- `->`[`animated`](#prop-animated) : `bool`
- `->`[`direction`](#prop-direction) : `string` - _ANY, HORIZONTAL, VERTICAL_
- `->`[`animationSpeed`](#prop-animationspeed) : `int`
- `->`[`_animTimer`](#prop-_animtimer) : `null|TimerScript`
- `->`[`_currentDirection`](#prop-_currentdirection) : `null`
- `->`[`_busy`](#prop-_busy) : `SharedValue`
- *См. также в родительском классе* [AnimationBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/behaviour/custom/AnimationBehaviour.ru.md).

---

#### Методы

- `->`[`applyImpl()`](#method-applyimpl)
- `->`[`getCode()`](#method-getcode)
- `->`[`disable()`](#method-disable)
- `->`[`getNewRandomPosition()`](#method-getnewrandomposition)
- См. также в родительском классе [AnimationBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/behaviour/custom/AnimationBehaviour.ru.md)

---
# Методы

<a name="method-applyimpl"></a>

### applyImpl()
```php
applyImpl(mixed $target): void
```

---

<a name="method-getcode"></a>

### getCode()
```php
getCode(): void
```

---

<a name="method-disable"></a>

### disable()
```php
disable(): void
```

---

<a name="method-getnewrandomposition"></a>

### getNewRandomPosition()
```php
getNewRandomPosition(): void
```