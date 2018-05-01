# ScoreScript

- **class** `ScoreScript` (`script\ScoreScript`) **extends** [`AbstractScript`](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/AbstractScript.md)
- **package** `framework`
- **source** `script/ScoreScript.php`

**Description**

Class ScoreScript

---

#### Properties

- `->`[`name`](#prop-name) : `string`
- `->`[`initialValue`](#prop-initialvalue) : `int`
- `->`[`bEventUid`](#prop-beventuid) : `mixed`
- `->`[`aEventUid`](#prop-aeventuid) : `mixed`
- *See also in the parent class* [AbstractScript](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/AbstractScript.md).

---

#### Methods

- `->`[`applyImpl()`](#method-applyimpl)
- `->`[`free()`](#method-free) - _Destroy component._
- `->`[`getValue()`](#method-getvalue) - _Score value._
- `->`[`setValue()`](#method-setvalue)
- `->`[`reset()`](#method-reset) - _Set score to initial value._
- `->`[`setTextBehaviour()`](#method-settextbehaviour)
- `->`[`appendTextBehaviour()`](#method-appendtextbehaviour)
- `->`[`getObjectValue()`](#method-getobjectvalue)
- `->`[`setObjectValue()`](#method-setobjectvalue)
- `->`[`appendObjectValue()`](#method-appendobjectvalue)
- `->`[`loadContentForObject()`](#method-loadcontentforobject)
- `->`[`applyContentToObject()`](#method-applycontenttoobject)
- See also in the parent class [AbstractScript](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/AbstractScript.md)

---
# Methods

<a name="method-applyimpl"></a>

### applyImpl()
```php
applyImpl(mixed $target): mixed
```

---

<a name="method-free"></a>

### free()
```php
free(): void
```
Destroy component.

---

<a name="method-getvalue"></a>

### getValue()
```php
getValue(): int
```
Score value.

---

<a name="method-setvalue"></a>

### setValue()
```php
setValue(int $value): void
```

---

<a name="method-reset"></a>

### reset()
```php
reset(): void
```
Set score to initial value.

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