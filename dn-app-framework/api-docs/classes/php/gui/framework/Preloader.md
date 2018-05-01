# Preloader

- **class** `Preloader` (`php\gui\framework\Preloader`) **extends** `UXAnchorPane` (`php\gui\layout\UXAnchorPane`)
- **package** `framework`
- **source** `php/gui/framework/Preloader.php`

**Description**

Class Preloader

---

#### Properties

- `->`[`pane`](#prop-pane) : `UXNode`
- `->`[`label`](#prop-label) : `UXLabel`
- `->`[`indicator`](#prop-indicator) : `UXProgressIndicator`

---

#### Static Methods

- `Preloader ::`[`hidePreloader()`](#method-hidepreloader)
- `Preloader ::`[`getPreloader()`](#method-getpreloader)

---

#### Methods

- `->`[`__construct()`](#method-__construct) - _Preloader constructor._
- `->`[`setText()`](#method-settext)
- `->`[`show()`](#method-show)

---
# Static Methods

<a name="method-hidepreloader"></a>

### hidePreloader()
```php
Preloader::hidePreloader(php\gui\UXNode $pane): void
```

---

<a name="method-getpreloader"></a>

### getPreloader()
```php
Preloader::getPreloader(php\gui\UXNode $pane): Preloader
```

---
# Methods

<a name="method-__construct"></a>

### __construct()
```php
__construct(UXParent $pane, string $text): void
```
Preloader constructor.

---

<a name="method-settext"></a>

### setText()
```php
setText(mixed $text): void
```

---

<a name="method-show"></a>

### show()
```php
show(): void
```