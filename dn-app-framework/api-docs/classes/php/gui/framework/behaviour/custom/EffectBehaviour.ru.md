# EffectBehaviour

- **класс** `EffectBehaviour` (`php\gui\framework\behaviour\custom\EffectBehaviour`) **унаследован от** [`AbstractBehaviour`](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/behaviour/custom/AbstractBehaviour.ru.md)
- **пакет** `framework`
- **исходники** `php/gui/framework/behaviour/custom/EffectBehaviour.php`

**Классы наследники**

> [BloomEffectBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/BloomEffectBehaviour.ru.md), [ColorAdjustEffectBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/ColorAdjustEffectBehaviour.ru.md), [DropShadowEffectBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/DropShadowEffectBehaviour.ru.md), [GaussianBlurEffectBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/GaussianBlurEffectBehaviour.ru.md), [GlowEffectBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/GlowEffectBehaviour.ru.md), [InnerShadowEffectBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/InnerShadowEffectBehaviour.ru.md), [LightingEffectBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/LightingEffectBehaviour.ru.md), [ReflectionEffectBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/ReflectionEffectBehaviour.ru.md), [SepiaToneEffectBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/behaviour/custom/SepiaToneEffectBehaviour.ru.md)

**Описание**

Class EffectBehaviour

---

#### Свойства

- `->`[`_effect`](#prop-_effect) : `UXEffect`
- `->`[`when`](#prop-when) : `string`
- *См. также в родительском классе* [AbstractBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/behaviour/custom/AbstractBehaviour.ru.md).

---

#### Методы

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
- См. также в родительском классе [AbstractBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/behaviour/custom/AbstractBehaviour.ru.md)

---
# Методы

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