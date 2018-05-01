# GUI

- **класс** `GUI` (`php\gui\framework\GUI`)
- **пакет** `framework`
- **исходники** `php/gui/framework/GUI.php`

**Описание**

Class GUI

---

#### Статичные Методы

- `GUI ::`[`getValues()`](#method-getvalues)
- `GUI ::`[`setValues()`](#method-setvalues)
- `GUI ::`[`getValue()`](#method-getvalue)
- `GUI ::`[`setValue()`](#method-setvalue)

---
# Статичные Методы

<a name="method-getvalues"></a>

### getValues()
```php
GUI::getValues(Traversable|array $nodes, string $prefix): array
```

---

<a name="method-setvalues"></a>

### setValues()
```php
GUI::setValues(Traversable|array $nodes, array $values, string $prefix): void
```

---

<a name="method-getvalue"></a>

### getValue()
```php
GUI::getValue(php\gui\UXNode $node): bool|int[]|mixed|null|string
```

---

<a name="method-setvalue"></a>

### setValue()
```php
GUI::setValue(php\gui\UXNode $node, mixed $value): void
```