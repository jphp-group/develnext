# AbstractStorage

- **class** `AbstractStorage` (`script\storage\AbstractStorage`) **extends** [`AbstractScript`](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/AbstractScript.md)
- **package** `framework`
- **source** `script/storage/AbstractStorage.php`

**Child Classes**

> [IniStorage](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/script/storage/IniStorage.md)

**Description**

Class AbstractStorage

---

#### Properties

- `->`[`data`](#prop-data) : `array`
- `->`[`autoSave`](#prop-autosave) : `bool` - _Автосохранение данных при изменении._
- *See also in the parent class* [AbstractScript](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/AbstractScript.md).

---

#### Methods

- `->`[`load()`](#method-load)
- `->`[`save()`](#method-save)
- `->`[`applyImpl()`](#method-applyimpl)
- `->`[`get()`](#method-get) - _Returns value of key and section._
- `->`[`section()`](#method-section) - _Returns an array of section._
- `->`[`sections()`](#method-sections) - _Returns names of sections._
- `->`[`put()`](#method-put) - _Writes a few values into section._
- `->`[`set()`](#method-set) - _Set value of key of section._
- `->`[`remove()`](#method-remove) - _Removes value by key from section._
- `->`[`removeSection()`](#method-removesection) - _Удаляет секцию._
- `->`[`toArray()`](#method-toarray) - _Возвращает все данные в виде массива._
- See also in the parent class [AbstractScript](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/AbstractScript.md)

---
# Methods

<a name="method-load"></a>

### load()
```php
load(): void
```

---

<a name="method-save"></a>

### save()
```php
save(): void
```

---

<a name="method-applyimpl"></a>

### applyImpl()
```php
applyImpl(mixed $target): void
```

---

<a name="method-get"></a>

### get()
```php
get(mixed $key, string $section): mixed
```
Returns value of key and section.

---

<a name="method-section"></a>

### section()
```php
section(string $name): array
```
Returns an array of section.

---

<a name="method-sections"></a>

### sections()
```php
sections(): array
```
Returns names of sections.

---

<a name="method-put"></a>

### put()
```php
put(array $values, string $section): void
```
Writes a few values into section.

---

<a name="method-set"></a>

### set()
```php
set(mixed $key, mixed $value, string $section, bool $checkAutoSave): void
```
Set value of key of section.

---

<a name="method-remove"></a>

### remove()
```php
remove(string $key, string $section): void
```
Removes value by key from section.

---

<a name="method-removesection"></a>

### removeSection()
```php
removeSection(string $section): void
```
Удаляет секцию.

---

<a name="method-toarray"></a>

### toArray()
```php
toArray(): array
```
Возвращает все данные в виде массива.