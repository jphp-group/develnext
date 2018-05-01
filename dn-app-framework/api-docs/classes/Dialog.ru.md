# Dialog

- **класс** `Dialog` (`Dialog`)
- **пакет** `framework`
- **исходники** `Dialog.php`

**Описание**

Class Dialog

---

#### Статичные Методы

- `Dialog ::`[`show()`](#method-show)
- `Dialog ::`[`message()`](#method-message)
- `Dialog ::`[`alert()`](#method-alert)
- `Dialog ::`[`error()`](#method-error)
- `Dialog ::`[`warning()`](#method-warning)
- `Dialog ::`[`confirm()`](#method-confirm)
- `Dialog ::`[`input()`](#method-input)

---
# Статичные Методы

<a name="method-show"></a>

### show()
```php
Dialog::show(string $text, string $type): null|string
```

---

<a name="method-message"></a>

### message()
```php
Dialog::message(mixed $text, string $type): null|string
```

---

<a name="method-alert"></a>

### alert()
```php
Dialog::alert(mixed $text, string $type): null|string
```

---

<a name="method-error"></a>

### error()
```php
Dialog::error(mixed $text): null|string
```

---

<a name="method-warning"></a>

### warning()
```php
Dialog::warning(mixed $text): null|string
```

---

<a name="method-confirm"></a>

### confirm()
```php
Dialog::confirm(mixed $text): bool
```

---

<a name="method-input"></a>

### input()
```php
Dialog::input(mixed $text, string $default): null|string
```