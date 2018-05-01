# AbstractModule

- **класс** `AbstractModule` (`php\gui\framework\AbstractModule`) **унаследован от** [`AbstractScript`](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/AbstractScript.ru.md)
- **пакет** `framework`
- **исходники** `php/gui/framework/AbstractModule.php`

**Описание**

Class AbstractModule

---

#### Свойства

- `->`[`__behaviourManager`](#prop-__behaviourmanager) : [`ModuleBehaviourManager`](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/behaviour/custom/ModuleBehaviourManager.ru.md)
- `->`[`__scripts`](#prop-__scripts) : `AbstractScript[]`
- `->`[`__modules`](#prop-__modules) : [`AbstractModule`](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/AbstractModule.ru.md)
- `->`[`singleton`](#prop-singleton) : `bool`
- *См. также в родительском классе* [AbstractScript](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/AbstractScript.ru.md).

---

#### Методы

- `->`[`__construct()`](#method-__construct) - _AbstractModule constructor._
- `->`[`loadModule()`](#method-loadmodule)
- `->`[`loadScript()`](#method-loadscript)
- `->`[`getResourcePath()`](#method-getresourcepath)
- `->`[`behaviour()`](#method-behaviour)
- `->`[`loadBinds()`](#method-loadbinds)
- `->`[`bind()`](#method-bind)
- `->`[`applyImpl()`](#method-applyimpl)
- `->`[`getContextForm()`](#method-getcontextform)
- `->`[`getContextFormName()`](#method-getcontextformname)
- `->`[`getScript()`](#method-getscript) **common.deprecated**
- `->`[`__get()`](#method-__get)
- `->`[`__isset()`](#method-__isset)
- `->`[`__call()`](#method-__call)
- `->`[`loadForm()`](#method-loadform)
- `->`[`free()`](#method-free)
- См. также в родительском классе [AbstractScript](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/AbstractScript.ru.md)

---
# Методы

<a name="method-__construct"></a>

### __construct()
```php
__construct(bool $mock): void
```
AbstractModule constructor.

---

<a name="method-loadmodule"></a>

### loadModule()
```php
loadModule(): void
```

---

<a name="method-loadscript"></a>

### loadScript()
```php
loadScript(AbstractScript|Component $script, array $meta): void
```

---

<a name="method-getresourcepath"></a>

### getResourcePath()
```php
getResourcePath(): void
```

---

<a name="method-behaviour"></a>

### behaviour()
```php
behaviour(mixed $target, mixed $class): void
```

---

<a name="method-loadbinds"></a>

### loadBinds()
```php
loadBinds(mixed $handler): void
```

---

<a name="method-bind"></a>

### bind()
```php
bind(mixed $event, callable $handler, mixed $group): void
```

---

<a name="method-applyimpl"></a>

### applyImpl()
```php
applyImpl(mixed $target): mixed
```

---

<a name="method-getcontextform"></a>

### getContextForm()
```php
getContextForm(): void
```

---

<a name="method-getcontextformname"></a>

### getContextFormName()
```php
getContextFormName(): void
```

---

<a name="method-getscript"></a>

### getScript()
```php
getScript(mixed $name): AbstractScript
```

---

<a name="method-__get"></a>

### __get()
```php
__get(mixed $name): void
```

---

<a name="method-__isset"></a>

### __isset()
```php
__isset(mixed $name): void
```

---

<a name="method-__call"></a>

### __call()
```php
__call(mixed $name, array $args): void
```

---

<a name="method-loadform"></a>

### loadForm()
```php
loadForm(mixed $form, mixed $saveSize, mixed $savePosition): void
```

---

<a name="method-free"></a>

### free()
```php
free(): void
```