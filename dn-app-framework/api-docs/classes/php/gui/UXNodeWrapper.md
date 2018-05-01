# UXNodeWrapper

- **class** `UXNodeWrapper` (`php\gui\UXNodeWrapper`)
- **source** `php/gui/UXNodeWrapper.php`

**Child Classes**

> [UXFragmentPaneWrapper](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/layout/UXFragmentPaneWrapper.md), [UXDatePickerWrapper](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/UXDatePickerWrapper.md), [UXImageAreaWrapper](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/UXImageAreaWrapper.md), [UXImageViewWrapper](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/UXImageViewWrapper.md), [UXLabeledWrapper](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/UXLabeledWrapper.md), [UXListViewWrapper](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/UXListViewWrapper.md), [UXPaginationWrapper](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/UXPaginationWrapper.md), [UXScrollPaneWrapper](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/UXScrollPaneWrapper.md), [UXTableViewWrapper](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/UXTableViewWrapper.md), [UXTabPaneWrapper](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/UXTabPaneWrapper.md), [UXWebViewWrapper](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/UXWebViewWrapper.md)

---

#### Properties

- `->`[`node`](#prop-node) : `AbstractScript|UXNode`

---

#### Static Methods

- `UXNodeWrapper ::`[`get()`](#method-get)

---

#### Methods

- `->`[`__construct()`](#method-__construct) - _UXNodeWrapper constructor._
- `->`[`applyData()`](#method-applydata)
- `->`[`bindGlobalKey()`](#method-bindglobalkey)
- `->`[`bind()`](#method-bind)

---
# Static Methods

<a name="method-get"></a>

### get()
```php
UXNodeWrapper::get(AbstractForm|UXNode $node): AbstractFormWrapper|UXNodeWrapper
```

---
# Methods

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