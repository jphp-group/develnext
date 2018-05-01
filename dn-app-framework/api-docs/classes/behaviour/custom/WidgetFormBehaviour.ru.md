# WidgetFormBehaviour

- **класс** `WidgetFormBehaviour` (`behaviour\custom\WidgetFormBehaviour`) **унаследован от** [`AbstractBehaviour`](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/behaviour/custom/AbstractBehaviour.ru.md)
- **пакет** `framework`
- **исходники** `behaviour/custom/WidgetFormBehaviour.php`

**Описание**

Class WidgetFormBehaviour

---

#### Свойства

- `->`[`popup`](#prop-popup) : `UXPopupWindow`
- `->`[`offsetX`](#prop-offsetx) : `int`
- `->`[`offsetY`](#prop-offsety) : `int`
- `->`[`position`](#prop-position) : `string`
- *См. также в родительском классе* [AbstractBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/behaviour/custom/AbstractBehaviour.ru.md).

---

#### Методы

- `->`[`applyImpl()`](#method-applyimpl)
- `->`[`getPosition()`](#method-getposition)
- `->`[`resetPosition()`](#method-resetposition) - _Сбросить позицию виджета к значению по умолчанию._
- `->`[`setPosition()`](#method-setposition) - _Позиция виджета относительно экрана._
- `->`[`getOffsetX()`](#method-getoffsetx) - _Смещение виджета по горизонтали._
- `->`[`setOffsetX()`](#method-setoffsetx)
- `->`[`getOffsetY()`](#method-getoffsety) - _Смещение виджета по вертикали._
- `->`[`setOffsetY()`](#method-setoffsety)
- `->`[`getCode()`](#method-getcode)
- См. также в родительском классе [AbstractBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/behaviour/custom/AbstractBehaviour.ru.md)

---
# Методы

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
Сбросить позицию виджета к значению по умолчанию.

---

<a name="method-setposition"></a>

### setPosition()
```php
setPosition(string $position): void
```
Позиция виджета относительно экрана.

---

<a name="method-getoffsetx"></a>

### getOffsetX()
```php
getOffsetX(): int
```
Смещение виджета по горизонтали.

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
Смещение виджета по вертикали.

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