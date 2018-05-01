# AbstractStorage

- **класс** `AbstractStorage` (`script\storage\AbstractStorage`) **унаследован от** [`AbstractScript`](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/AbstractScript.ru.md)
- **пакет** `framework`
- **исходники** `script/storage/AbstractStorage.php`

**Классы наследники**

> [IniStorage](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/script/storage/IniStorage.ru.md)

**Описание**

Class AbstractStorage

---

#### Свойства

- `->`[`data`](#prop-data) : `array`
- `->`[`autoSave`](#prop-autosave) : `bool` - _Автосохранение данных при изменении._
- *См. также в родительском классе* [AbstractScript](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/AbstractScript.ru.md).

---

#### Методы

- `->`[`load()`](#method-load)
- `->`[`save()`](#method-save)
- `->`[`applyImpl()`](#method-applyimpl)
- `->`[`get()`](#method-get) - _Возвращает значение по ключу (и секции, если передать)._
- `->`[`section()`](#method-section) - _Возвращает массив данных секции._
- `->`[`sections()`](#method-sections) - _Возвращает массив имен секций._
- `->`[`put()`](#method-put) - _Записывает сразу несколько значений в секцию._
- `->`[`set()`](#method-set) - _Задает значение ключа секции._
- `->`[`remove()`](#method-remove) - _Удаляет значение по ключу из секции._
- `->`[`removeSection()`](#method-removesection) - _Удаляет секцию._
- `->`[`toArray()`](#method-toarray) - _Возвращает все данные в виде массива._
- См. также в родительском классе [AbstractScript](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/AbstractScript.ru.md)

---
# Методы

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
Возвращает значение по ключу (и секции, если передать).

---

<a name="method-section"></a>

### section()
```php
section(string $name): array
```
Возвращает массив данных секции.

---

<a name="method-sections"></a>

### sections()
```php
sections(): array
```
Возвращает массив имен секций.

---

<a name="method-put"></a>

### put()
```php
put(array $values, string $section): void
```
Записывает сразу несколько значений в секцию.

---

<a name="method-set"></a>

### set()
```php
set(mixed $key, mixed $value, string $section, bool $checkAutoSave): void
```
Задает значение ключа секции.

---

<a name="method-remove"></a>

### remove()
```php
remove(string $key, string $section): void
```
Удаляет значение по ключу из секции.

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