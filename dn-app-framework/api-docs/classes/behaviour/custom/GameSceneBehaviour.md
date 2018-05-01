# GameSceneBehaviour

- **class** `GameSceneBehaviour` (`behaviour\custom\GameSceneBehaviour`) **extends** [`AbstractBehaviour`](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/behaviour/custom/AbstractBehaviour.md)
- **package** `framework`
- **source** `behaviour/custom/GameSceneBehaviour.php`

**Description**

Class GameSceneBehaviour

---

#### Properties

- `->`[`initialScene`](#prop-initialscene) : `string|null`
- `->`[`autoplay`](#prop-autoplay) : `bool`
- `->`[`gravityType`](#prop-gravitytype) : `string` - _ZERO, EARTH, MARS, MOON_
- `->`[`gravityDirection`](#prop-gravitydirection) : `string` - _DOWN, UP, LEFT, RIGHT_
- `->`[`cacheScenes`](#prop-cachescenes) : `bool`
- `->`[`scene`](#prop-scene) : `UXGameScene`
- `->`[`layout`](#prop-layout) : `UXPane`
- `->`[`loadedScenes`](#prop-loadedscenes) : `AbstractForm[]`
- `->`[`previousForm`](#prop-previousform) : [`AbstractForm`](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/AbstractForm.md)
- *See also in the parent class* [AbstractBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/behaviour/custom/AbstractBehaviour.md).

---

#### Methods

- `->`[`getSort()`](#method-getsort)
- `->`[`applyImpl()`](#method-applyimpl)
- `->`[`loadScene()`](#method-loadscene)
- `->`[`initGravity()`](#method-initgravity)
- `->`[`getScene()`](#method-getscene)
- `->`[`play()`](#method-play)
- `->`[`pause()`](#method-pause)
- `->`[`__get()`](#method-__get)
- `->`[`__set()`](#method-__set)
- `->`[`__call()`](#method-__call)
- `->`[`getCode()`](#method-getcode)
- See also in the parent class [AbstractBehaviour](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/behaviour/custom/AbstractBehaviour.md)

---
# Methods

<a name="method-getsort"></a>

### getSort()
```php
getSort(): void
```

---

<a name="method-applyimpl"></a>

### applyImpl()
```php
applyImpl(mixed $target): void
```

---

<a name="method-loadscene"></a>

### loadScene()
```php
loadScene(mixed $name): void
```

---

<a name="method-initgravity"></a>

### initGravity()
```php
initGravity(): void
```

---

<a name="method-getscene"></a>

### getScene()
```php
getScene(): UXGameScene
```

---

<a name="method-play"></a>

### play()
```php
play(): void
```

---

<a name="method-pause"></a>

### pause()
```php
pause(): void
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