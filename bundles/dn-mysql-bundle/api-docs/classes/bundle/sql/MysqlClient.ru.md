# MysqlClient

- **класс** `MysqlClient` (`bundle\sql\MysqlClient`) **унаследован от** `SqlClient` (`bundle\sql\SqlClient`)
- **исходники** `bundle/sql/MysqlClient.php`

---

#### Свойства

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

#### Методы

- `->`[`buildClient()`](#method-buildclient)

---
# Методы

<a name="method-buildclient"></a>

### buildClient()
```php
buildClient(): SqlConnection
```