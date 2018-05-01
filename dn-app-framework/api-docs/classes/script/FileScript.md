# FileScript

- **class** `FileScript` (`script\FileScript`) **extends** [`AbstractScript`](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/AbstractScript.md)
- **package** `framework`
- **source** `script/FileScript.php`

**Description**

Class FileScript

---

#### Properties

- `->`[`watch`](#prop-watch) : `bool`
- `->`[`_path`](#prop-_path) : `string`
- `->`[`_content`](#prop-_content) : `string`
- `->`[`_exists`](#prop-_exists) : `bool`
- `->`[`_upd`](#prop-_upd) : `int`
- *See also in the parent class* [AbstractScript](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/AbstractScript.md).

---

#### Methods

- `->`[`applyImpl()`](#method-applyimpl)
- `->`[`getPath()`](#method-getpath)
- `->`[`setPath()`](#method-setpath)
- `->`[`getContent()`](#method-getcontent)
- `->`[`setContent()`](#method-setcontent)
- `->`[`mkdirs()`](#method-mkdirs) - _Создать директорию для файла._
- `->`[`getObjectText()`](#method-getobjecttext)
- `->`[`loadContentForObject()`](#method-loadcontentforobject)
- `->`[`applyContentToObject()`](#method-applycontenttoobject)
- `->`[`setTextBehaviour()`](#method-settextbehaviour)
- `->`[`appendTextBehaviour()`](#method-appendtextbehaviour)
- `->`[`getObjectValue()`](#method-getobjectvalue)
- `->`[`setObjectValue()`](#method-setobjectvalue)
- `->`[`appendObjectValue()`](#method-appendobjectvalue)
- See also in the parent class [AbstractScript](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/AbstractScript.md)

---
# Methods

<a name="method-applyimpl"></a>

### applyImpl()
```php
applyImpl(mixed $target): mixed
```

---

<a name="method-getpath"></a>

### getPath()
```php
getPath(): string
```

---

<a name="method-setpath"></a>

### setPath()
```php
setPath(string $path): void
```

---

<a name="method-getcontent"></a>

### getContent()
```php
getContent(): void
```

---

<a name="method-setcontent"></a>

### setContent()
```php
setContent(mixed $value): void
```

---

<a name="method-mkdirs"></a>

### mkdirs()
```php
mkdirs(): void
```
Создать директорию для файла.

---

<a name="method-getobjecttext"></a>

### getObjectText()
```php
getObjectText(): void
```

---

<a name="method-loadcontentforobject"></a>

### loadContentForObject()
```php
loadContentForObject(mixed $path): mixed
```

---

<a name="method-applycontenttoobject"></a>

### applyContentToObject()
```php
applyContentToObject(mixed $content): mixed
```

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

---

<a name="method-getobjectvalue"></a>

### getObjectValue()
```php
getObjectValue(): void
```

---

<a name="method-setobjectvalue"></a>

### setObjectValue()
```php
setObjectValue(mixed $value): void
```

---

<a name="method-appendobjectvalue"></a>

### appendObjectValue()
```php
appendObjectValue(mixed $value): void
```