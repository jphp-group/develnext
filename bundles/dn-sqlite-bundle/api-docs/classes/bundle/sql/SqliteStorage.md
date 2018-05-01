# SqliteStorage

- **class** `SqliteStorage` (`bundle\sql\SqliteStorage`) **extends** `SqlStorage` (`bundle\sql\SqlStorage`)
- **source** `bundle/sql/SqliteStorage.php`

**Description**

Class SqliteStorage

---

#### Methods

- `->`[`getPath()`](#method-getpath)
- `->`[`setPath()`](#method-setpath)
- `->`[`buildClient()`](#method-buildclient)
- `->`[`getInitialSql()`](#method-getinitialsql) - _Return create table sql script, table name must be `storage` with fields name, value, section._

---
# Methods

<a name="method-getpath"></a>

### getPath()
```php
getPath(): string
```

---

<a name="method-setpath"></a>

### setPath()
```php
setPath(string $file): void
```

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