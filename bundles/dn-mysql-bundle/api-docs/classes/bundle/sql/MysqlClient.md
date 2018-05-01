# MysqlClient

- **class** `MysqlClient` (`bundle\sql\MysqlClient`) **extends** `SqlClient` (`bundle\sql\SqlClient`)
- **source** `bundle/sql/MysqlClient.php`

---

#### Properties

- `->`[`host`](#prop-host) : `string`
- `->`[`port`](#prop-port) : `int`
- `->`[`database`](#prop-database) : `string`
- `->`[`username`](#prop-username) : `string`
- `->`[`password`](#prop-password) : `string`
- `->`[`useCompression`](#prop-usecompression) : `bool`
- `->`[`useSSL`](#prop-usessl) : `bool`
- `->`[`connectTimeout`](#prop-connecttimeout) : `int`
- `->`[`socketTimeout`](#prop-sockettimeout) : `int`
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