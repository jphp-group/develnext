# SqlClient

- **класс** `SqlClient` (`bundle\sql\SqlClient`) **унаследован от** `AbstractScript` (`php\gui\framework\AbstractScript`)
- **исходники** `bundle/sql/SqlClient.php`

**Описание**

Class SqlClient

---

#### Свойства

- `->`[`client`](#prop-client) : `SqlConnection`
- `->`[`closed`](#prop-closed) : `bool`
- `->`[`autoOpen`](#prop-autoopen) : `bool`
- `->`[`catchErrors`](#prop-catcherrors) : `bool`
- `->`[`autoCommit`](#prop-autocommit) : `bool`
- `->`[`transactionIsolation`](#prop-transactionisolation) : `int`
- `->`[`logSql`](#prop-logsql) : `bool`

---

#### Методы

- `->`[`buildClient()`](#method-buildclient)
- `->`[`applyImpl()`](#method-applyimpl)
- `->`[`open()`](#method-open) - _Open database._
- `->`[`isOpened()`](#method-isopened)
- `->`[`close()`](#method-close) - _Close connection._
- `->`[`query()`](#method-query)
- `->`[`processSqlException()`](#method-processsqlexception)
- `->`[`commit()`](#method-commit) - _Makes all changes made since the previous_
- `->`[`rollback()`](#method-rollback) - _Undoes all changes made in the current transaction_
- `->`[`identifier()`](#method-identifier)
- `->`[`getTransactionIsolation()`](#method-gettransactionisolation) - _See SqlConnection::TRANSACTION_* constants._
- `->`[`setTransactionIsolation()`](#method-settransactionisolation)
- `->`[`isAutoCommit()`](#method-isautocommit)
- `->`[`setAutoCommit()`](#method-setautocommit)
- `->`[`getCatalogs()`](#method-getcatalogs)
- `->`[`getMetaData()`](#method-getmetadata)
- `->`[`getSchemas()`](#method-getschemas)
- `->`[`__destruct()`](#method-__destruct)
- `->`[`free()`](#method-free)

---
# Методы

<a name="method-buildclient"></a>

### buildClient()
```php
buildClient(): SqlConnection
```

---

<a name="method-applyimpl"></a>

### applyImpl()
```php
applyImpl(mixed $target): mixed
```

---

<a name="method-open"></a>

### open()
```php
open(): void
```
Open database.

---

<a name="method-isopened"></a>

### isOpened()
```php
isOpened(): bool
```

---

<a name="method-close"></a>

### close()
```php
close(): void
```
Close connection.

---

<a name="method-query"></a>

### query()
```php
query(string $sql, array $arguments): SqlStatement
```

---

<a name="method-processsqlexception"></a>

### processSqlException()
```php
processSqlException(php\sql\SqlException $e): void
```

---

<a name="method-commit"></a>

### commit()
```php
commit(): void
```
Makes all changes made since the previous
commit/rollback permanent and releases any database locks
currently held by this Connection object.

---

<a name="method-rollback"></a>

### rollback()
```php
rollback(): void
```
Undoes all changes made in the current transaction
and releases any database locks currently held
by this Connection object.

---

<a name="method-identifier"></a>

### identifier()
```php
identifier(string $name): string
```

---

<a name="method-gettransactionisolation"></a>

### getTransactionIsolation()
```php
getTransactionIsolation(): int
```
See SqlConnection::TRANSACTION_* constants.

---

<a name="method-settransactionisolation"></a>

### setTransactionIsolation()
```php
setTransactionIsolation(int $value): void
```

---

<a name="method-isautocommit"></a>

### isAutoCommit()
```php
isAutoCommit(): boolean
```

---

<a name="method-setautocommit"></a>

### setAutoCommit()
```php
setAutoCommit(boolean $autoCommit): void
```

---

<a name="method-getcatalogs"></a>

### getCatalogs()
```php
getCatalogs(): array
```

---

<a name="method-getmetadata"></a>

### getMetaData()
```php
getMetaData(): array
```

---

<a name="method-getschemas"></a>

### getSchemas()
```php
getSchemas(): array
```

---

<a name="method-__destruct"></a>

### __destruct()
```php
__destruct(): void
```

---

<a name="method-free"></a>

### free()
```php
free(): void
```