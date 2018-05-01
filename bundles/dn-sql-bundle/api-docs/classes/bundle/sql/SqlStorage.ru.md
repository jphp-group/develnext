# SqlStorage

- **класс** `SqlStorage` (`bundle\sql\SqlStorage`) **унаследован от** `AbstractStorage` (`script\storage\AbstractStorage`)
- **исходники** `bundle/sql/SqlStorage.php`

---

#### Свойства

- `->`[`client`](#prop-client) : `SqliteClient`
- `->`[`options`](#prop-options) : `array`

---

#### Методы

- `->`[`__construct()`](#method-__construct) - _SqlStorage constructor._
- `->`[`buildClient()`](#method-buildclient)
- `->`[`getInitialSql()`](#method-getinitialsql) - _Return create table sql script, table name must be `storage` with fields name, value, section._
- `->`[`load()`](#method-load)
- `->`[`save()`](#method-save)

---
# Методы

<a name="method-__construct"></a>

### __construct()
```php
__construct(): void
```
SqlStorage constructor.

---

<a name="method-buildclient"></a>

### buildClient()
```php
buildClient(): SqlClient
```

---

<a name="method-getinitialsql"></a>

### getInitialSql()
```php
getInitialSql(): string
```
Return create table sql script, table name must be `storage` with fields name, value, section.

---

<a name="method-load"></a>

### load()
```php
load(): void
```

---

<a name="method-save"></a>

### save()
```php
save(): void
```