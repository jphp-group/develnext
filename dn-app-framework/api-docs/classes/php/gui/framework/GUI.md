# GUI

- **class** `GUI` (`php\gui\framework\GUI`)
- **package** `framework`
- **source** `php/gui/framework/GUI.php`

**Description**

Class GUI

---

#### Static Methods

- `GUI ::`[`getValues()`](#method-getvalues)
- `GUI ::`[`setValues()`](#method-setvalues)
- `GUI ::`[`getValue()`](#method-getvalue)
- `GUI ::`[`setValue()`](#method-setvalue)

---
# Static Methods

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