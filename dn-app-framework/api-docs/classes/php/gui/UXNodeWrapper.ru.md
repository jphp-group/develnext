# UXNodeWrapper

- **класс** `UXNodeWrapper` (`php\gui\UXNodeWrapper`)
- **исходники** `php/gui/UXNodeWrapper.php`

**Классы наследники**

> [UXFragmentPaneWrapper](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/layout/UXFragmentPaneWrapper.ru.md), [UXDatePickerWrapper](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/UXDatePickerWrapper.ru.md), [UXImageAreaWrapper](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/UXImageAreaWrapper.ru.md), [UXImageViewWrapper](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/UXImageViewWrapper.ru.md), [UXLabeledWrapper](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/UXLabeledWrapper.ru.md), [UXListViewWrapper](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/UXListViewWrapper.ru.md), [UXPaginationWrapper](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/UXPaginationWrapper.ru.md), [UXScrollPaneWrapper](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/UXScrollPaneWrapper.ru.md), [UXTableViewWrapper](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/UXTableViewWrapper.ru.md), [UXTabPaneWrapper](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/UXTabPaneWrapper.ru.md), [UXWebViewWrapper](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/UXWebViewWrapper.ru.md)

---

#### Свойства

- `->`[`node`](#prop-node) : `AbstractScript|UXNode`

---

#### Статичные Методы

- `UXNodeWrapper ::`[`get()`](#method-get)

---

#### Методы

- `->`[`__construct()`](#method-__construct) - _UXNodeWrapper constructor._
- `->`[`applyData()`](#method-applydata)
- `->`[`bindGlobalKey()`](#method-bindglobalkey)
- `->`[`bind()`](#method-bind)

---
# Статичные Методы

<a name="method-get"></a>

### get()
```php
UXNodeWrapper::get(AbstractForm|UXNode $node): AbstractFormWrapper|UXNodeWrapper
```

---
# Методы

<a name="method-__construct"></a>

### __construct()
```php
__construct(mixed $node): void
```
UXNodeWrapper constructor.

---

<a name="method-applydata"></a>

### applyData()
```php
applyData(php\gui\UXData $data): void
```

---

<a name="method-bindglobalkey"></a>

### bindGlobalKey()
```php
bindGlobalKey(mixed $event, callable $_handler, mixed $group): void
```

---

<a name="method-bind"></a>

### bind()
```php
bind(mixed $event, callable $handler, mixed $group): void
```