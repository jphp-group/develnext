# RandomMovementAnimationBehaviour

- **class** `RandomMovementAnimationBehaviour` (`behaviour\custom\RandomMovementAnimationBehaviour`) **extends** [`AnimationBehaviour`](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/behaviour/custom/AnimationBehaviour.md)
- **package** `framework`
- **source** `behaviour/custom/RandomMovementAnimationBehaviour.php`

**Description**

Class RandomMovementAnimationBehaviour

---

#### Properties

- `->`[`animated`](#prop-animated) : `bool`
- `->`[`direction`](#prop-direction) : `string` - _ANY, HORIZONTAL, VERTICAL_
- `->`[`animationSpeed`](#prop-animationspeed) : `int`
- `->`[`_animTimer`](#prop-_animtimer) : `null|TimerScript`
- `->`[`_currentDirection`](#prop-_currentdirection) : `null`
- `->`[`_busy`](#prop-_busy) : `SharedValue`
- *See also in the parent class* [AnimationBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/behaviour/custom/AnimationBehaviour.md).

---

#### Methods

- `->`[`applyImpl()`](#method-applyimpl)
- `->`[`getCode()`](#method-getcode)
- `->`[`disable()`](#method-disable)
- `->`[`getNewRandomPosition()`](#method-getnewrandomposition)
- See also in the parent class [AnimationBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/behaviour/custom/AnimationBehaviour.md)

---
# Methods

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