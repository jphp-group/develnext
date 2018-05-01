# AbstractFactory

- **класс** `AbstractFactory` (`php\gui\framework\AbstractFactory`)
- **пакет** `framework`
- **исходники** `php/gui/framework/AbstractFactory.php`

**Классы наследники**

> [StandaloneFactory](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/StandaloneFactory.ru.md)

**Описание**

Class AbstractFactory

---

#### Свойства

- `->`[`prototypes`](#prop-prototypes) : `string[]` - _xml_
- `->`[`prototypeElements`](#prop-prototypeelements) : `DomElement[]`
- `->`[`prototypeData`](#prop-prototypedata) : `DomElement[]`
- `->`[`prototypeInstances`](#prop-prototypeinstances) : `UXNode[]`
- `->`[`prototypeImports`](#prop-prototypeimports) : `DomElement[]`
- `->`[`eventBinder`](#prop-eventbinder) : [`EventBinder`](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/EventBinder.ru.md)
- `->`[`behaviourManager`](#prop-behaviourmanager) : [`BehaviourManager`](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/behaviour/custom/BehaviourManager.ru.md)
- `->`[`factoryName`](#prop-factoryname) : `string`
- `->`[`loader`](#prop-loader) : `UXLoader`

---

#### Методы

- `->`[`__construct()`](#method-__construct)
- `->`[`__get()`](#method-__get)
- `->`[`getAllInstances()`](#method-getallinstances)
- `->`[`makeNode()`](#method-makenode)
- `->`[`create()`](#method-create)
- `->`[`getResourceName()`](#method-getresourcename)
- `->`[`loadPrototypes()`](#method-loadprototypes)
- `->`[`makeXmlForLoader()`](#method-makexmlforloader)

---
# Методы

<a name="method-__construct"></a>

### __construct()
```php
__construct(null|string $path): void
```

---

<a name="method-__get"></a>

### __get()
```php
__get(mixed $name): Instances
```

---

<a name="method-getallinstances"></a>

### getAllInstances()
```php
getAllInstances(): Instances
```

---

<a name="method-makenode"></a>

### makeNode()
```php
makeNode(mixed $id): void
```

---

<a name="method-create"></a>

### create()
```php
create(string $id): UXNode
```

---

<a name="method-getresourcename"></a>

### getResourceName()
```php
getResourceName(): void
```

---

<a name="method-loadprototypes"></a>

### loadPrototypes()
```php
loadPrototypes(mixed $path): void
```

---

<a name="method-makexmlforloader"></a>

### makeXmlForLoader()
```php
makeXmlForLoader(php\xml\DomNode $node, mixed $imports): void
```