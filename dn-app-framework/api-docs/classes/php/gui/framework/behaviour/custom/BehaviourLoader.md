# BehaviourLoader

- **class** `BehaviourLoader` (`php\gui\framework\behaviour\custom\BehaviourLoader`)
- **package** `framework`
- **source** `php/gui/framework/behaviour/custom/BehaviourLoader.php`

**Description**

Class BehaviourLoader

---

#### Properties

- `->`[`documents`](#prop-documents) : `mixed`

---

#### Static Methods

- `BehaviourLoader ::`[`loadOne()`](#method-loadone)
- `BehaviourLoader ::`[`loadFromDocument()`](#method-loadfromdocument)
- `BehaviourLoader ::`[`load()`](#method-load)

---
# Static Methods

<a name="method-loadone"></a>

### loadOne()
```php
BehaviourLoader::loadOne(mixed $targetId, php\xml\DomElement $behaviours, php\gui\framework\behaviour\custom\BehaviourManager $manager, mixed $newTargetId): void
```

---

<a name="method-loadfromdocument"></a>

### loadFromDocument()
```php
BehaviourLoader::loadFromDocument(php\xml\DomDocument $document, php\gui\framework\behaviour\custom\BehaviourManager $manager): void
```

---

<a name="method-load"></a>

### load()
```php
BehaviourLoader::load(mixed $file, php\gui\framework\behaviour\custom\BehaviourManager $manager, mixed $cached): void
```