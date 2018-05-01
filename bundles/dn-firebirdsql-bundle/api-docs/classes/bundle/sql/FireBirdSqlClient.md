# FireBirdSqlClient

- **class** `FireBirdSqlClient` (`bundle\sql\FireBirdSqlClient`) **extends** `SqlClient` (`bundle\sql\SqlClient`)
- **source** `bundle/sql/FireBirdSqlClient.php`

---

#### Properties

- `->`[`connectionType`](#prop-connectiontype) : `string` - _DEFAULT, NATIVE, LOCAL, EMBEDDED_
- `->`[`host`](#prop-host) : `string`
- `->`[`port`](#prop-port) : `int`
- `->`[`database`](#prop-database) : `string`
- `->`[`username`](#prop-username) : `string`
- `->`[`password`](#prop-password) : `string`
- `->`[`encoding`](#prop-encoding) : `string`
- `->`[`charSet`](#prop-charset) : `string`
- `->`[`roleName`](#prop-rolename) : `string`
- `->`[`sqlDialect`](#prop-sqldialect) : `string`
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