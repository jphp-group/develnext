# PgSqlClient

- **класс** `PgSqlClient` (`bundle\sql\PgSqlClient`) **унаследован от** `SqlClient` (`bundle\sql\SqlClient`)
- **исходники** `bundle/sql/PgSqlClient.php`

---

#### Свойства

- `->`[`host`](#prop-host) : `string`
- `->`[`port`](#prop-port) : `int`
- `->`[`database`](#prop-database) : `string`
- `->`[`username`](#prop-username) : `string`
- `->`[`password`](#prop-password) : `string`
- `->`[`useSSL`](#prop-usessl) : `bool`
- `->`[`loginTimeout`](#prop-logintimeout) : `int`
- `->`[`connectTimeout`](#prop-connecttimeout) : `int`
- `->`[`socketTimeout`](#prop-sockettimeout) : `int`
- `->`[`currentSchema`](#prop-currentschema) : `string`
- `->`[`options`](#prop-options) : `array`

---

#### Методы

- `->`[`buildClient()`](#method-buildclient)

---
# Методы

<a name="method-buildclient"></a>

### buildClient()
```php
buildClient(): SqlConnection
```