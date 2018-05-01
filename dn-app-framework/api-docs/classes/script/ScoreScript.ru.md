# ScoreScript

- **класс** `ScoreScript` (`script\ScoreScript`) **унаследован от** [`AbstractScript`](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/AbstractScript.ru.md)
- **пакет** `framework`
- **исходники** `script/ScoreScript.php`

**Описание**

Class ScoreScript

---

#### Свойства

- `->`[`name`](#prop-name) : `string`
- `->`[`initialValue`](#prop-initialvalue) : `int`
- `->`[`bEventUid`](#prop-beventuid) : `mixed`
- `->`[`aEventUid`](#prop-aeventuid) : `mixed`
- *См. также в родительском классе* [AbstractScript](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/AbstractScript.ru.md).

---

#### Методы

- `->`[`applyImpl()`](#method-applyimpl)
- `->`[`free()`](#method-free) - _Destroy component._
- `->`[`getValue()`](#method-getvalue) - _Значение счета._
- `->`[`setValue()`](#method-setvalue)
- `->`[`reset()`](#method-reset) - _Установить счет в начальное значение._
- `->`[`setTextBehaviour()`](#method-settextbehaviour)
- `->`[`appendTextBehaviour()`](#method-appendtextbehaviour)
- `->`[`getObjectValue()`](#method-getobjectvalue)
- `->`[`setObjectValue()`](#method-setobjectvalue)
- `->`[`appendObjectValue()`](#method-appendobjectvalue)
- `->`[`loadContentForObject()`](#method-loadcontentforobject)
- `->`[`applyContentToObject()`](#method-applycontenttoobject)
- См. также в родительском классе [AbstractScript](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/AbstractScript.ru.md)

---
# Методы

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
Значение счета.

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
Установить счет в начальное значение.

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