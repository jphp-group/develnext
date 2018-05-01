# Preloader

- **класс** `Preloader` (`php\gui\framework\Preloader`) **унаследован от** `UXAnchorPane` (`php\gui\layout\UXAnchorPane`)
- **пакет** `framework`
- **исходники** `php/gui/framework/Preloader.php`

**Описание**

Class Preloader

---

#### Свойства

- `->`[`pane`](#prop-pane) : `UXNode`
- `->`[`label`](#prop-label) : `UXLabel`
- `->`[`indicator`](#prop-indicator) : `UXProgressIndicator`

---

#### Статичные Методы

- `Preloader ::`[`hidePreloader()`](#method-hidepreloader)
- `Preloader ::`[`getPreloader()`](#method-getpreloader)

---

#### Методы

- `->`[`__construct()`](#method-__construct) - _Preloader constructor._
- `->`[`setText()`](#method-settext)
- `->`[`show()`](#method-show)

---
# Статичные Методы

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
# Методы

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