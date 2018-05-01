# AbstractForm

- **класс** `AbstractForm` (`php\gui\framework\AbstractForm`) **унаследован от** `UXForm` (`php\gui\UXForm`)
- **пакет** `framework`
- **исходники** `php/gui/framework/AbstractForm.php`

**Описание**

Class AbstractForm

---

#### Свойства

- `->`[`_app`](#prop-_app) : [`Application`](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/Application.ru.md)
- `->`[`_config`](#prop-_config) : `Configuration`
- `->`[`_modules`](#prop-_modules) : `AbstractModule[]`
- `->`[`behaviourManager`](#prop-behaviourmanager) : [`BehaviourManager`](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/behaviour/custom/BehaviourManager.ru.md)
- `->`[`eventBinder`](#prop-eventbinder) : [`EventBinder`](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/EventBinder.ru.md)
- `->`[`standaloneFactory`](#prop-standalonefactory) : [`StandaloneFactory`](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/StandaloneFactory.ru.md)
- `->`[`widget`](#prop-widget) : `UXPopupWindow`
- `->`[`__fragmentPane`](#prop-__fragmentpane) : `UXFragmentPane`
- `->`[`resourcePath`](#prop-resourcepath) : `string`

---

#### Статичные Методы

- `AbstractForm ::`[`getResourcePathByClassName()`](#method-getresourcepathbyclassname)

---

#### Методы

- `->`[`__construct()`](#method-__construct)
- `->`[`makeAsWidget()`](#method-makeaswidget)
- `->`[`showInFragment()`](#method-showinfragment)
- `->`[`isFragment()`](#method-isfragment)
- `->`[`widget()`](#method-widget)
- `->`[`loadBehaviours()`](#method-loadbehaviours)
- `->`[`behaviour()`](#method-behaviour)
- `->`[`getFactory()`](#method-getfactory)
- `->`[`instance()`](#method-instance)
- `->`[`create()`](#method-create) - _Create a clone from prototype ($id)._
- `->`[`instances()`](#method-instances)
- `->`[`getConfig()`](#method-getconfig)
- `->`[`getName()`](#method-getname)
- `->`[`show()`](#method-show)
- `->`[`loadForm()`](#method-loadform)
- `->`[`free()`](#method-free)
- `->`[`isFree()`](#method-isfree)
- `->`[`getContextForm()`](#method-getcontextform)
- `->`[`getContextFormName()`](#method-getcontextformname)
- `->`[`init()`](#method-init)
- `->`[`module()`](#method-module)
- `->`[`getResourcePath()`](#method-getresourcepath) - _For overriding._
- `->`[`applyConfig()`](#method-applyconfig)
- `->`[`loadConfig()`](#method-loadconfig)
- `->`[`loadCustomNode()`](#method-loadcustomnode)
- `->`[`loadClones()`](#method-loadclones)
- `->`[`loadDesign()`](#method-loaddesign)
- `->`[`toast()`](#method-toast)
- `->`[`showPreloader()`](#method-showpreloader)
- `->`[`hidePreloader()`](#method-hidepreloader)
- `->`[`loadBindings()`](#method-loadbindings)
- `->`[`bind()`](#method-bind)
- `->`[`__get()`](#method-__get)
- `->`[`__isset()`](#method-__isset)
- `->`[`__call()`](#method-__call)

---
# Статичные Методы

<a name="method-getresourcepathbyclassname"></a>

### getResourcePathByClassName()
```php
AbstractForm::getResourcePathByClassName(): void
```

---
# Методы

<a name="method-__construct"></a>

### __construct()
```php
__construct(php\gui\UXForm $origin, bool $loadEvents, bool $loadBehaviours): void
```

---

<a name="method-makeaswidget"></a>

### makeAsWidget()
```php
makeAsWidget(): void
```

---

<a name="method-showinfragment"></a>

### showInFragment()
```php
showInFragment(php\gui\layout\UXFragmentPane $fragmentPane): void
```

---

<a name="method-isfragment"></a>

### isFragment()
```php
isFragment(): bool
```

---

<a name="method-widget"></a>

### widget()
```php
widget(): UXPopupWindow
```

---

<a name="method-loadbehaviours"></a>

### loadBehaviours()
```php
loadBehaviours(): void
```

---

<a name="method-behaviour"></a>

### behaviour()
```php
behaviour(mixed $target, mixed $class): void
```

---

<a name="method-getfactory"></a>

### getFactory()
```php
getFactory(): void
```

---

<a name="method-instance"></a>

### instance()
```php
instance(mixed $id, null|int $x, null|int $y): UXNode
```

---

<a name="method-create"></a>

### create()
```php
create(mixed $id, UXNode|UXWindow $initiator, int $offsetX, int $offsetY, bool $relative): UXNode
```
Create a clone from prototype ($id).

---

<a name="method-instances"></a>

### instances()
```php
instances(mixed $id): Instances
```

---

<a name="method-getconfig"></a>

### getConfig()
```php
getConfig(): Configuration
```

---

<a name="method-getname"></a>

### getName()
```php
getName(): string
```

---

<a name="method-show"></a>

### show()
```php
show(): void
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

---

<a name="method-isfree"></a>

### isFree()
```php
isFree(): void
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

<a name="method-init"></a>

### init()
```php
init(): void
```

---

<a name="method-module"></a>

### module()
```php
module(mixed $id): AbstractModule
```

---

<a name="method-getresourcepath"></a>

### getResourcePath()
```php
getResourcePath(): string
```
For overriding.

---

<a name="method-applyconfig"></a>

### applyConfig()
```php
applyConfig(): void
```

---

<a name="method-loadconfig"></a>

### loadConfig()
```php
loadConfig(mixed $path, mixed $applyConfig): void
```

---

<a name="method-loadcustomnode"></a>

### loadCustomNode()
```php
loadCustomNode(php\gui\UXCustomNode $node): void
```

---

<a name="method-loadclones"></a>

### loadClones()
```php
loadClones(): void
```

---

<a name="method-loaddesign"></a>

### loadDesign()
```php
loadDesign(): void
```

---

<a name="method-toast"></a>

### toast()
```php
toast(string $message, int $timeout): void
```

---

<a name="method-showpreloader"></a>

### showPreloader()
```php
showPreloader(mixed $text): void
```

---

<a name="method-hidepreloader"></a>

### hidePreloader()
```php
hidePreloader(): void
```

---

<a name="method-loadbindings"></a>

### loadBindings()
```php
loadBindings(object $handler): void
```

---

<a name="method-bind"></a>

### bind()
```php
bind(mixed $event, callable $handler, string $group): void
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