# FactoryBehaviourManager

- **class** `FactoryBehaviourManager` (`php\gui\framework\behaviour\custom\FactoryBehaviourManager`) **extends** [`BehaviourManager`](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/behaviour/custom/BehaviourManager.md)
- **package** `framework`
- **source** `php/gui/framework/behaviour/custom/FactoryBehaviourManager.php`

**Description**

Class FactoryBehaviourManager

---

#### Properties

- `->`[`factory`](#prop-factory) : [`AbstractFactory`](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/AbstractFactory.md)
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
__construct(php\gui\framework\AbstractFactory $factory): void
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