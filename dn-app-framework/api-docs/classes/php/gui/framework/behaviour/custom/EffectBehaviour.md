# EffectBehaviour

- **class** `EffectBehaviour` (`php\gui\framework\behaviour\custom\EffectBehaviour`) **extends** [`AbstractBehaviour`](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/behaviour/custom/AbstractBehaviour.md)
- **package** `framework`
- **source** `php/gui/framework/behaviour/custom/EffectBehaviour.php`

**Child Classes**

> [BloomEffectBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/BloomEffectBehaviour.md), [ColorAdjustEffectBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/ColorAdjustEffectBehaviour.md), [DropShadowEffectBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/DropShadowEffectBehaviour.md), [GaussianBlurEffectBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/GaussianBlurEffectBehaviour.md), [GlowEffectBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/GlowEffectBehaviour.md), [InnerShadowEffectBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/InnerShadowEffectBehaviour.md), [LightingEffectBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/LightingEffectBehaviour.md), [ReflectionEffectBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/ReflectionEffectBehaviour.md), [SepiaToneEffectBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/SepiaToneEffectBehaviour.md)

**Description**

Class EffectBehaviour

---

#### Properties

- `->`[`_effect`](#prop-_effect) : `UXEffect`
- `->`[`when`](#prop-when) : `string`
- *See also in the parent class* [AbstractBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/behaviour/custom/AbstractBehaviour.md).

---

#### Methods

- `->`[`makeEffect()`](#method-makeeffect)
- `->`[`updateEffect()`](#method-updateeffect)
- `->`[`apply()`](#method-apply)
- `->`[`__apply()`](#method-__apply)
- `->`[`applyImpl()`](#method-applyimpl)
- `->`[`getWhenEventTypes()`](#method-getwheneventtypes)
- `->`[`__set()`](#method-__set)
- `->`[`disable()`](#method-disable)
- `->`[`enable()`](#method-enable)
- `->`[`free()`](#method-free)
- `->`[`restore()`](#method-restore)
- See also in the parent class [AbstractBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/behaviour/custom/AbstractBehaviour.md)

---
# Methods

<a name="method-makeeffect"></a>

### makeEffect()
```php
makeEffect(): UXEffect
```

---

<a name="method-updateeffect"></a>

### updateEffect()
```php
updateEffect(php\gui\effect\UXEffect $effect): void
```

---

<a name="method-apply"></a>

### apply()
```php
apply(mixed $target): void
```

---

<a name="method-__apply"></a>

### __apply()
```php
__apply(mixed $target): void
```

---

<a name="method-applyimpl"></a>

### applyImpl()
```php
applyImpl(mixed $target): void
```

---

<a name="method-getwheneventtypes"></a>

### getWhenEventTypes()
```php
getWhenEventTypes(): void
```

---

<a name="method-__set"></a>

### __set()
```php
__set(mixed $name, mixed $value): void
```

---

<a name="method-disable"></a>

### disable()
```php
disable(): void
```

---

<a name="method-enable"></a>

### enable()
```php
enable(): void
```

---

<a name="method-free"></a>

### free()
```php
free(): void
```

---

<a name="method-restore"></a>

### restore()
```php
restore(): void
```