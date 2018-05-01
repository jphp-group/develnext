# FormBehaviourManager

- **класс** `FormBehaviourManager` (`php\gui\framework\behaviour\custom\FormBehaviourManager`) **унаследован от** [`BehaviourManager`](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/behaviour/custom/BehaviourManager.ru.md)
- **пакет** `framework`
- **исходники** `php/gui/framework/behaviour/custom/FormBehaviourManager.php`

**Описание**

Class FormBehaviourManager

---

#### Свойства

- `->`[`form`](#prop-form) : [`AbstractForm`](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/AbstractForm.ru.md)
- `->`[`behaviours`](#prop-behaviours) : `array`
- *См. также в родительском классе* [BehaviourManager](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/behaviour/custom/BehaviourManager.ru.md).

---

#### Методы

- `->`[`__construct()`](#method-__construct) - _FormBehaviourManager constructor._
- `->`[`apply()`](#method-apply)
- `->`[`applyForInstance()`](#method-applyforinstance)
- См. также в родительском классе [BehaviourManager](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/behaviour/custom/BehaviourManager.ru.md)

---
# Методы

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