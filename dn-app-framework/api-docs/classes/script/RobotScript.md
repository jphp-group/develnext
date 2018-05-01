# RobotScript

- **class** `RobotScript` (`script\RobotScript`) **extends** [`AbstractScript`](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/AbstractScript.md)
- **package** `framework`
- **source** `script/RobotScript.php`

**Description**

Class RobotScript

---

#### Properties

- `->`[`robot`](#prop-robot) : `Robot`
- *See also in the parent class* [AbstractScript](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/AbstractScript.md).

---

#### Methods

- `->`[`__construct()`](#method-__construct) - _RobotScript constructor._
- `->`[`applyImpl()`](#method-applyimpl)
- `->`[`getX()`](#method-getx)
- `->`[`getY()`](#method-gety)
- `->`[`setX()`](#method-setx)
- `->`[`setY()`](#method-sety)
- `->`[`getPosition()`](#method-getposition)
- `->`[`setPosition()`](#method-setposition)
- `->`[`mouseClick()`](#method-mouseclick)
- `->`[`mouseDown()`](#method-mousedown)
- `->`[`mouseUp()`](#method-mouseup)
- `->`[`mouseScroll()`](#method-mousescroll)
- `->`[`type()`](#method-type)
- `->`[`keyDown()`](#method-keydown)
- `->`[`keyUp()`](#method-keyup)
- `->`[`keyPress()`](#method-keypress)
- `->`[`screenshot()`](#method-screenshot) - _Make screenshot of screen (primary if null passed)._
- `->`[`setTextBehaviour()`](#method-settextbehaviour)
- `->`[`appendTextBehaviour()`](#method-appendtextbehaviour)
- See also in the parent class [AbstractScript](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/AbstractScript.md)

---
# Methods

<a name="method-__construct"></a>

### __construct()
```php
__construct(): void
```
RobotScript constructor.

---

<a name="method-applyimpl"></a>

### applyImpl()
```php
applyImpl(mixed $target): mixed
```

---

<a name="method-getx"></a>

### getX()
```php
getX(): void
```

---

<a name="method-gety"></a>

### getY()
```php
getY(): void
```

---

<a name="method-setx"></a>

### setX()
```php
setX(mixed $x): void
```

---

<a name="method-sety"></a>

### setY()
```php
setY(mixed $y): void
```

---

<a name="method-getposition"></a>

### getPosition()
```php
getPosition(): void
```

---

<a name="method-setposition"></a>

### setPosition()
```php
setPosition(array $xy): void
```

---

<a name="method-mouseclick"></a>

### mouseClick()
```php
mouseClick(string $button): void
```

---

<a name="method-mousedown"></a>

### mouseDown()
```php
mouseDown(string $button): void
```

---

<a name="method-mouseup"></a>

### mouseUp()
```php
mouseUp(string $button): void
```

---

<a name="method-mousescroll"></a>

### mouseScroll()
```php
mouseScroll(int $wheelAmt): void
```

---

<a name="method-type"></a>

### type()
```php
type(string $text): void
```

---

<a name="method-keydown"></a>

### keyDown()
```php
keyDown(string $keyCombination): void
```

---

<a name="method-keyup"></a>

### keyUp()
```php
keyUp(string $keyCombination): void
```

---

<a name="method-keypress"></a>

### keyPress()
```php
keyPress(string $keyCombination): void
```

---

<a name="method-screenshot"></a>

### screenshot()
```php
screenshot(array $bounds, php\gui\UXScreen $screen): UXImage
```
Make screenshot of screen (primary if null passed).

---

<a name="method-settextbehaviour"></a>

### setTextBehaviour()
```php
setTextBehaviour(mixed $text): void
```

---

<a name="method-appendtextbehaviour"></a>

### appendTextBehaviour()
```php
appendTextBehaviour(mixed $text): void
```