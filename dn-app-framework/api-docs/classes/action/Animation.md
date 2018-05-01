# Animation

- **class** `Animation` (`action\Animation`)
- **package** `framework`
- **source** `action/Animation.php`

**Description**

Class Animation

---

#### Static Methods

- `Animation ::`[`fadeTo()`](#method-fadeto) - _Fade animation_
- `Animation ::`[`fadeIn()`](#method-fadein)
- `Animation ::`[`fadeOut()`](#method-fadeout)
- `Animation ::`[`scaleTo()`](#method-scaleto) - _Scale animation._
- `Animation ::`[`stopScale()`](#method-stopscale)
- `Animation ::`[`stopMove()`](#method-stopmove)
- `Animation ::`[`displace()`](#method-displace) - _Displace animation._
- `Animation ::`[`moveTo()`](#method-moveto) - _Move to point animation._

---
# Static Methods

<a name="method-fadeto"></a>

### fadeTo()
```php
Animation::fadeTo(mixed $object, mixed $duration, mixed $value, callable|null $callback): null|UXAnimationTimer
```
Fade animation

---

<a name="method-fadein"></a>

### fadeIn()
```php
Animation::fadeIn(mixed $object, mixed $duration, callable $callback): void
```

---

<a name="method-fadeout"></a>

### fadeOut()
```php
Animation::fadeOut(mixed $object, mixed $duration, callable $callback): void
```

---

<a name="method-scaleto"></a>

### scaleTo()
```php
Animation::scaleTo(php\gui\UXNode $object, int $duration, double $value, callable $callback): UXAnimationTimer
```
Scale animation.

---

<a name="method-stopscale"></a>

### stopScale()
```php
Animation::stopScale(php\gui\UXNode $object): void
```

---

<a name="method-stopmove"></a>

### stopMove()
```php
Animation::stopMove(UXNode|UXWindow $object): void
```

---

<a name="method-displace"></a>

### displace()
```php
Animation::displace(UXNode|UXWindow $object, int $duration, double $x, double $y, callable $callback): UXAnimationTimer
```
Displace animation.

---

<a name="method-moveto"></a>

### moveTo()
```php
Animation::moveTo(UXNode|UXWindow $object, int $duration, double $x, double $y, callable|null $callback): array|null|UXAnimationTimer
```
Move to point animation.