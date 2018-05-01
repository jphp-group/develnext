# WidgetFormBehaviour

- **class** `WidgetFormBehaviour` (`behaviour\custom\WidgetFormBehaviour`) **extends** [`AbstractBehaviour`](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/behaviour/custom/AbstractBehaviour.md)
- **package** `framework`
- **source** `behaviour/custom/WidgetFormBehaviour.php`

**Description**

Class WidgetFormBehaviour

---

#### Properties

- `->`[`popup`](#prop-popup) : `UXPopupWindow`
- `->`[`offsetX`](#prop-offsetx) : `int`
- `->`[`offsetY`](#prop-offsety) : `int`
- `->`[`position`](#prop-position) : `string`
- *See also in the parent class* [AbstractBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/behaviour/custom/AbstractBehaviour.md).

---

#### Methods

- `->`[`applyImpl()`](#method-applyimpl)
- `->`[`getPosition()`](#method-getposition)
- `->`[`resetPosition()`](#method-resetposition) - _Reset the position of the widget to the default value._
- `->`[`setPosition()`](#method-setposition) - _Set position of the widget on screen._
- `->`[`getOffsetX()`](#method-getoffsetx) - _Offset by Y._
- `->`[`setOffsetX()`](#method-setoffsetx)
- `->`[`getOffsetY()`](#method-getoffsety) - _Offset by X._
- `->`[`setOffsetY()`](#method-setoffsety)
- `->`[`getCode()`](#method-getcode)
- See also in the parent class [AbstractBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/behaviour/custom/AbstractBehaviour.md)

---
# Methods

<a name="method-applyimpl"></a>

### applyImpl()
```php
applyImpl(mixed $target): void
```

---

<a name="method-getposition"></a>

### getPosition()
```php
getPosition(): string
```

---

<a name="method-resetposition"></a>

### resetPosition()
```php
resetPosition(): void
```
Reset the position of the widget to the default value.

---

<a name="method-setposition"></a>

### setPosition()
```php
setPosition(string $position): void
```
Set position of the widget on screen.

---

<a name="method-getoffsetx"></a>

### getOffsetX()
```php
getOffsetX(): int
```
Offset by Y.

---

<a name="method-setoffsetx"></a>

### setOffsetX()
```php
setOffsetX(int $offsetX): void
```

---

<a name="method-getoffsety"></a>

### getOffsetY()
```php
getOffsetY(): int
```
Offset by X.

---

<a name="method-setoffsety"></a>

### setOffsetY()
```php
setOffsetY(int $offsetY): void
```

---

<a name="method-getcode"></a>

### getCode()
```php
getCode(): void
```