# FireBirdSqlClient

- **класс** `FireBirdSqlClient` (`bundle\sql\FireBirdSqlClient`) **унаследован от** `SqlClient` (`bundle\sql\SqlClient`)
- **исходники** `bundle/sql/FireBirdSqlClient.php`

---

#### Свойства

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

#### Методы

- `->`[`buildClient()`](#method-buildclient)

---
# Методы

<a name="method-buildclient"></a>

### buildClient()
```php
buildClient(): SqlConnection
```