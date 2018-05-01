# SqliteStorage

- **класс** `SqliteStorage` (`bundle\sql\SqliteStorage`) **унаследован от** `SqlStorage` (`bundle\sql\SqlStorage`)
- **исходники** `bundle/sql/SqliteStorage.php`

**Описание**

Class SqliteStorage

---

#### Методы

- `->`[`getPath()`](#method-getpath)
- `->`[`setPath()`](#method-setpath)
- `->`[`buildClient()`](#method-buildclient)
- `->`[`getInitialSql()`](#method-getinitialsql) - _Return create table sql script, table name must be `storage` with fields name, value, section._

---
# Методы

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