# FileChooserScript

- **класс** `FileChooserScript` (`script\FileChooserScript`) **унаследован от** [`AbstractScript`](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/AbstractScript.ru.md)
- **пакет** `framework`
- **исходники** `script/FileChooserScript.php`

**Описание**

Class TimerScript

---

#### Свойства

- `->`[`_dialog`](#prop-_dialog) : `UXFileChooser`
- `->`[`file`](#prop-file) : `File`
- `->`[`files`](#prop-files) : `File[]`
- `->`[`filterExtensions`](#prop-filterextensions) : `null`
- `->`[`filterName`](#prop-filtername) : `null`
- `->`[`filterAny`](#prop-filterany) : `bool`
- `->`[`inputNode`](#prop-inputnode) : `string`
- `->`[`actionNode`](#prop-actionnode) : `string`
- `->`[`multiple`](#prop-multiple) : `bool`
- `->`[`initialDirectory`](#prop-initialdirectory) : `string`
- `->`[`initialFileName`](#prop-initialfilename) : `string`
- `->`[`saveDialog`](#prop-savedialog) : `bool`
- *См. также в родительском классе* [AbstractScript](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/AbstractScript.ru.md).

---

#### Методы

- `->`[`__construct()`](#method-__construct)
- `->`[`execute()`](#method-execute)
- `->`[`applyImpl()`](#method-applyimpl)
- `->`[`getObjectText()`](#method-getobjecttext)
- `->`[`getObjectValue()`](#method-getobjectvalue)
- `->`[`setObjectValue()`](#method-setobjectvalue)
- `->`[`setTextBehaviour()`](#method-settextbehaviour)
- `->`[`appendTextBehaviour()`](#method-appendtextbehaviour)
- `->`[`appendObjectValue()`](#method-appendobjectvalue)
- См. также в родительском классе [AbstractScript](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/AbstractScript.ru.md)

---
# Методы

<a name="method-__construct"></a>

### __construct()
```php
__construct(): void
```

---

<a name="method-execute"></a>

### execute()
```php
execute(): File
```

---

<a name="method-applyimpl"></a>

### applyImpl()
```php
applyImpl(mixed $target): mixed
```

---

<a name="method-getobjecttext"></a>

### getObjectText()
```php
getObjectText(): void
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

<a name="method-appendobjectvalue"></a>

### appendObjectValue()
```php
appendObjectValue(mixed $value): void
```