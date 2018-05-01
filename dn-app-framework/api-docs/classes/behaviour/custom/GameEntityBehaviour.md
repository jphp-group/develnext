# GameEntityBehaviour

- **class** `GameEntityBehaviour` (`behaviour\custom\GameEntityBehaviour`) **extends** [`AbstractBehaviour`](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/behaviour/custom/AbstractBehaviour.md)
- **package** `framework`
- **source** `behaviour/custom/GameEntityBehaviour.php`

**Description**

Class GameEntityBehaviour

---

#### Properties

- `->`[`bodyType`](#prop-bodytype) : `string`
- `->`[`solidType`](#prop-solidtype) : `string`
- `->`[`fixtureType`](#prop-fixturetype) : `string`
- `->`[`solid`](#prop-solid) : `bool`
- `->`[`startVelocity`](#prop-startvelocity) : `array`
- `->`[`entity`](#prop-entity) : `UXGameEntity`
- `->`[`foundScene`](#prop-foundscene) : [`GameSceneBehaviour`](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/GameSceneBehaviour.md)
- `->`[`factoryName`](#prop-factoryname) : `1`
- `->`[`type`](#prop-type) : `1`
- *See also in the parent class* [AbstractBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/behaviour/custom/AbstractBehaviour.md).

---

#### Methods

- `->`[`applyImpl()`](#method-applyimpl)
- `->`[`findTargetSize()`](#method-findtargetsize)
- `->`[`findScene()`](#method-findscene)
- `->`[`free()`](#method-free)
- `->`[`__get()`](#method-__get)
- `->`[`__set()`](#method-__set)
- `->`[`__call()`](#method-__call)
- `->`[`getCode()`](#method-getcode)
- `->`[`getEntity()`](#method-getentity)
- `->`[`__loadFixture()`](#method-__loadfixture)
- `->`[`setCollisionHandler()`](#method-setcollisionhandler)
- See also in the parent class [AbstractBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/behaviour/custom/AbstractBehaviour.md)

---
# Methods

<a name="method-applyimpl"></a>

### applyImpl()
```php
applyImpl(mixed $target): void
```

---

<a name="method-findtargetsize"></a>

### findTargetSize()
```php
findTargetSize(): void
```

---

<a name="method-findscene"></a>

### findScene()
```php
findScene(): void
```

---

<a name="method-free"></a>

### free()
```php
free(): void
```

---

<a name="method-__get"></a>

### __get()
```php
__get(mixed $name): void
```

---

<a name="method-__set"></a>

### __set()
```php
__set(mixed $name, mixed $value): void
```

---

<a name="method-__call"></a>

### __call()
```php
__call(mixed $name, array $args): void
```

---

<a name="method-getcode"></a>

### getCode()
```php
getCode(): void
```

---

<a name="method-getentity"></a>

### getEntity()
```php
getEntity(): UXGameEntity
```

---

<a name="method-__loadfixture"></a>

### __loadFixture()
```php
__loadFixture(): void
```

---

<a name="method-setcollisionhandler"></a>

### setCollisionHandler()
```php
setCollisionHandler(mixed $entityType, callable $handler, mixed $factoryName): void
```