# Application

- **class** `Application` (`php\gui\framework\Application`)
- **package** `framework`
- **source** `php/gui/framework/Application.php`

**Description**

Class Application

---

#### Properties

- `->`[`instance`](#prop-instance) : [`Application`](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/Application.md)
- `->`[`namespace`](#prop-namespace) : `string`
- `->`[`launched`](#prop-launched) : `bool`
- `->`[`mainForm`](#prop-mainform) : [`AbstractForm`](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/AbstractForm.md)
- `->`[`mainFormClass`](#prop-mainformclass) : `string`
- `->`[`splashFormClass`](#prop-splashformclass) : `string`
- `->`[`forms`](#prop-forms) : `AbstractForm[]`
- `->`[`splash`](#prop-splash) : [`AbstractForm`](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/AbstractForm.md)
- `->`[`startTime`](#prop-starttime) : `Time`
- `->`[`factories`](#prop-factories) : `AbstractFactory[]`
- `->`[`modules`](#prop-modules) : `AbstractModule[]`
- `->`[`styles`](#prop-styles) : `string[]`
- `->`[`appModule`](#prop-appmodule) : `null|AbstractModule`
- `->`[`config`](#prop-config) : `Configuration`
- `->`[`shutdown`](#prop-shutdown) : `bool`
- `->`[`formCache`](#prop-formcache) : `AbstractForm[]`
- `->`[`formOriginCache`](#prop-formorigincache) : `mixed`

---

#### Static Methods

- `Application ::`[`isCreated()`](#method-iscreated)
- `Application ::`[`get()`](#method-get)

---

#### Methods

- `->`[`__construct()`](#method-__construct)
- `->`[`getName()`](#method-getname)
- `->`[`getInstanceId()`](#method-getinstanceid)
- `->`[`getUuid()`](#method-getuuid)
- `->`[`getVersion()`](#method-getversion)
- `->`[`isSnapshotVersion()`](#method-issnapshotversion)
- `->`[`getVersionHash()`](#method-getversionhash)
- `->`[`getNamespace()`](#method-getnamespace)
- `->`[`getConfig()`](#method-getconfig)
- `->`[`getStartTime()`](#method-getstarttime)
- `->`[`getUserHome()`](#method-getuserhome) - _Returns user home directory of this app._
- `->`[`__cleanCacheForm()`](#method-__cleancacheform)
- `->`[`minimizeForm()`](#method-minimizeform)
- `->`[`restoreForm()`](#method-restoreform)
- `->`[`getForm()`](#method-getform)
- `->`[`form()`](#method-form)
- `->`[`getOriginForm()`](#method-getoriginform)
- `->`[`originForm()`](#method-originform)
- `->`[`getNewForm()`](#method-getnewform)
- `->`[`showForm()`](#method-showform)
- `->`[`showFormAndWait()`](#method-showformandwait)
- `->`[`showNewForm()`](#method-shownewform)
- `->`[`showNewFormAndWait()`](#method-shownewformandwait)
- `->`[`hideForm()`](#method-hideform)
- `->`[`create()`](#method-create)
- `->`[`factory()`](#method-factory)
- `->`[`appModule()`](#method-appmodule)
- `->`[`getMainForm()`](#method-getmainform)
- `->`[`setMainFormClass()`](#method-setmainformclass)
- `->`[`hideSplash()`](#method-hidesplash) - _Скрыть заставку._
- `->`[`setSplashFormClass()`](#method-setsplashformclass)
- `->`[`addStyle()`](#method-addstyle)
- `->`[`getStyles()`](#method-getstyles)
- `->`[`removeStyle()`](#method-removestyle)
- `->`[`loadModules()`](#method-loadmodules)
- `->`[`loadConfig()`](#method-loadconfig)
- `->`[`module()`](#method-module)
- `->`[`isLaunched()`](#method-islaunched)
- `->`[`launch()`](#method-launch)
- `->`[`shutdown()`](#method-shutdown) - _Exit from application._
- `->`[`isShutdown()`](#method-isshutdown)

---
# Static Methods

<a name="method-iscreated"></a>

### isCreated()
```php
Application::isCreated(): bool
```

---

<a name="method-get"></a>

### get()
```php
Application::get(): Application
```

---
# Methods

<a name="method-__construct"></a>

### __construct()
```php
__construct(string $configPath): void
```

---

<a name="method-getname"></a>

### getName()
```php
getName(): string
```

---

<a name="method-getinstanceid"></a>

### getInstanceId()
```php
getInstanceId(): string
```

---

<a name="method-getuuid"></a>

### getUuid()
```php
getUuid(): string
```

---

<a name="method-getversion"></a>

### getVersion()
```php
getVersion(): string
```

---

<a name="method-issnapshotversion"></a>

### isSnapshotVersion()
```php
isSnapshotVersion(): bool
```

---

<a name="method-getversionhash"></a>

### getVersionHash()
```php
getVersionHash(): int
```

---

<a name="method-getnamespace"></a>

### getNamespace()
```php
getNamespace(): string
```

---

<a name="method-getconfig"></a>

### getConfig()
```php
getConfig(): php\util\Configuration
```

---

<a name="method-getstarttime"></a>

### getStartTime()
```php
getStartTime(): php\time\Time
```

---

<a name="method-getuserhome"></a>

### getUserHome()
```php
getUserHome(): string
```
Returns user home directory of this app.

---

<a name="method-__cleancacheform"></a>

### __cleanCacheForm()
```php
__cleanCacheForm(string $name): void
```

---

<a name="method-minimizeform"></a>

### minimizeForm()
```php
minimizeForm(mixed $name): AbstractForm
```

---

<a name="method-restoreform"></a>

### restoreForm()
```php
restoreForm(mixed $name): AbstractForm
```

---

<a name="method-getform"></a>

### getForm()
```php
getForm(string $name, php\gui\UXForm $origin): AbstractForm
```

---

<a name="method-form"></a>

### form()
```php
form(string $name, php\gui\UXForm $origin): AbstractForm
```

---

<a name="method-getoriginform"></a>

### getOriginForm()
```php
getOriginForm(mixed $name, php\gui\UXForm $origin): AbstractForm
```

---

<a name="method-originform"></a>

### originForm()
```php
originForm(string $name, php\gui\UXForm $origin): AbstractForm
```

---

<a name="method-getnewform"></a>

### getNewForm()
```php
getNewForm(mixed $name, php\gui\UXForm $origin, bool $loadEvents, bool $loadBehaviours, bool $cache): AbstractForm
```

---

<a name="method-showform"></a>

### showForm()
```php
showForm(mixed $name): AbstractForm
```

---

<a name="method-showformandwait"></a>

### showFormAndWait()
```php
showFormAndWait(mixed $name): AbstractForm
```

---

<a name="method-shownewform"></a>

### showNewForm()
```php
showNewForm(mixed $name): AbstractForm
```

---

<a name="method-shownewformandwait"></a>

### showNewFormAndWait()
```php
showNewFormAndWait(mixed $name): AbstractForm
```

---

<a name="method-hideform"></a>

### hideForm()
```php
hideForm(mixed $name): AbstractForm
```

---

<a name="method-create"></a>

### create()
```php
create(mixed $prototype): UXNode
```

---

<a name="method-factory"></a>

### factory()
```php
factory(mixed $name): AbstractFactory
```

---

<a name="method-appmodule"></a>

### appModule()
```php
appModule(): null|AbstractModule
```

---

<a name="method-getmainform"></a>

### getMainForm()
```php
getMainForm(): AbstractForm
```

---

<a name="method-setmainformclass"></a>

### setMainFormClass()
```php
setMainFormClass(mixed $class): void
```

---

<a name="method-hidesplash"></a>

### hideSplash()
```php
hideSplash(): void
```
Скрыть заставку.

---

<a name="method-setsplashformclass"></a>

### setSplashFormClass()
```php
setSplashFormClass(mixed $class): void
```

---

<a name="method-addstyle"></a>

### addStyle()
```php
addStyle(mixed $resource): void
```

---

<a name="method-getstyles"></a>

### getStyles()
```php
getStyles(): void
```

---

<a name="method-removestyle"></a>

### removeStyle()
```php
removeStyle(mixed $resource): void
```

---

<a name="method-loadmodules"></a>

### loadModules()
```php
loadModules(array $classes): void
```

---

<a name="method-loadconfig"></a>

### loadConfig()
```php
loadConfig(mixed $configPath): void
```

---

<a name="method-module"></a>

### module()
```php
module(mixed $id): AbstractModule
```

---

<a name="method-islaunched"></a>

### isLaunched()
```php
isLaunched(): void
```

---

<a name="method-launch"></a>

### launch()
```php
launch(callable $handler, callable $after): void
```

---

<a name="method-shutdown"></a>

### shutdown()
```php
shutdown(): void
```
Exit from application.

---

<a name="method-isshutdown"></a>

### isShutdown()
```php
isShutdown(): void
```