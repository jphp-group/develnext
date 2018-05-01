# FormBehaviourManager

- **class** `FormBehaviourManager` (`php\gui\framework\behaviour\custom\FormBehaviourManager`) **extends** [`BehaviourManager`](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/behaviour/custom/BehaviourManager.md)
- **package** `framework`
- **source** `php/gui/framework/behaviour/custom/FormBehaviourManager.php`

**Description**

Class FormBehaviourManager

---

#### Properties

- `->`[`form`](#prop-form) : [`AbstractForm`](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/AbstractForm.md)
- `->`[`behaviours`](#prop-behaviours) : `array`
- *See also in the parent class* [BehaviourManager](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/behaviour/custom/BehaviourManager.md).

---

#### Methods

- `->`[`__construct()`](#method-__construct) - _FormBehaviourManager constructor._
- `->`[`apply()`](#method-apply)
- `->`[`applyForInstance()`](#method-applyforinstance)
- See also in the parent class [BehaviourManager](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/behaviour/custom/BehaviourManager.md)

---
# Methods

<a name="method-__construct"></a>

### __construct()
```php
__construct(php\gui\framework\AbstractForm $form): void
```
FormBehaviourManager constructor.

---

<a name="method-apply"></a>

### apply()
```php
apply(mixed $targetId, php\gui\framework\behaviour\custom\AbstractBehaviour $behaviour): void
```

---

<a name="method-applyforinstance"></a>

### applyForInstance()
```php
applyForInstance(mixed $id, mixed $target): void
```