# PgSqlClient

- **class** `PgSqlClient` (`bundle\sql\PgSqlClient`) **extends** `SqlClient` (`bundle\sql\SqlClient`)
- **source** `bundle/sql/PgSqlClient.php`

---

#### Properties

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

#### Methods

- `->`[`buildClient()`](#method-buildclient)

---
# Methods

<a name="method-buildclient"></a>

### buildClient()
```php
buildClient(): SqlConnection
```