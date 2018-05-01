# ZipFileScript

- **class** `ZipFileScript` (`bundle\zip\ZipFileScript`) **extends** `AbstractScript` (`php\gui\framework\AbstractScript`)
- **source** `bundle/zip/ZipFileScript.php`

---

#### Properties

- `->`[`file`](#prop-file) : `ZipFile`
- `->`[`path`](#prop-path) : `string`
- `->`[`autoCreate`](#prop-autocreate) : `bool`
- `->`[`_stopped`](#prop-_stopped) : `bool`

---

#### Methods

- `->`[`applyImpl()`](#method-applyimpl)
- `->`[`stop()`](#method-stop) - _Stop make pack or unpack._
- `->`[`getPath()`](#method-getpath)
- `->`[`setPath()`](#method-setpath)
- `->`[`clear()`](#method-clear) - _Clear zip archive._
- `->`[`createFile()`](#method-createfile) - _Create empty zip archive._
- `->`[`deleteFile()`](#method-deletefile) - _Delete zip file._
- `->`[`unpack()`](#method-unpack) - _Extract zip archive content to directory._
- `->`[`unpackAsync()`](#method-unpackasync)
- `->`[`read()`](#method-read) - _Read one zip entry from archive._
- `->`[`readAll()`](#method-readall) - _Read all zip entries from archive._
- `->`[`stat()`](#method-stat) - _Returns stat of one zip entry by path._
- `->`[`statAll()`](#method-statall) - _Returns all stats of zip archive._
- `->`[`has()`](#method-has) - _Checks zip entry exist by path._
- `->`[`add()`](#method-add) - _Add stream or file to archive._
- `->`[`addDirectory()`](#method-adddirectory) - _Add all files of directory to archive._
- `->`[`addDirectoryAsync()`](#method-adddirectoryasync)
- `->`[`addFromString()`](#method-addfromstring) - _Add zip entry from string._
- `->`[`remove()`](#method-remove) - _Remove zip entry by its path._
- `->`[`free()`](#method-free)
- `->`[`zipFile()`](#method-zipfile)
- `->`[`__call()`](#method-__call)

---
# Methods

<a name="method-applyimpl"></a>

### applyImpl()
```php
applyImpl(mixed $target): mixed
```

---

<a name="method-stop"></a>

### stop()
```php
stop(): void
```
Stop make pack or unpack.

---

<a name="method-getpath"></a>

### getPath()
```php
getPath(): string
```

---

<a name="method-setpath"></a>

### setPath()
```php
setPath(string $path): void
```

---

<a name="method-clear"></a>

### clear()
```php
clear(): void
```
Clear zip archive.

---

<a name="method-createfile"></a>

### createFile()
```php
createFile(bool $rewrite): void
```
Create empty zip archive.

---

<a name="method-deletefile"></a>

### deleteFile()
```php
deleteFile(): bool
```
Delete zip file.

---

<a name="method-unpack"></a>

### unpack()
```php
unpack(string $toDirectory, string $charset, callable $callback): void
```
Extract zip archive content to directory.

---

<a name="method-unpackasync"></a>

### unpackAsync()
```php
unpackAsync(mixed $toDirectory, null $charset, callable|null $callback): Thread
```

---

<a name="method-read"></a>

### read()
```php
read(string $path, callable $reader): void
```
Read one zip entry from archive.

---

<a name="method-readall"></a>

### readAll()
```php
readAll(callable $reader): void
```
Read all zip entries from archive.

---

<a name="method-stat"></a>

### stat()
```php
stat(string $path): array
```
Returns stat of one zip entry by path.
[name, size, compressedSize, time, crc, comment, method]

---

<a name="method-statall"></a>

### statAll()
```php
statAll(): array[]
```
Returns all stats of zip archive.

---

<a name="method-has"></a>

### has()
```php
has(mixed $path): bool
```
Checks zip entry exist by path.

---

<a name="method-add"></a>

### add()
```php
add(string $path, Stream|File|string $source, int $compressLevel): void
```
Add stream or file to archive.

---

<a name="method-adddirectory"></a>

### addDirectory()
```php
addDirectory(string $dir, int $compressLevel, callable $callback): void
```
Add all files of directory to archive.

---

<a name="method-adddirectoryasync"></a>

### addDirectoryAsync()
```php
addDirectoryAsync(mixed $dir, int $compressLevel, callable|null $callback): Thread
```

---

<a name="method-addfromstring"></a>

### addFromString()
```php
addFromString(string $path, string $string, int $compressLevel): void
```
Add zip entry from string.

---

<a name="method-remove"></a>

### remove()
```php
remove(string|array $path): void
```
Remove zip entry by its path.

---

<a name="method-free"></a>

### free()
```php
free(): void
```

---

<a name="method-zipfile"></a>

### zipFile()
```php
zipFile(): ZipFile
```

---

<a name="method-__call"></a>

### __call()
```php
__call(mixed $method, array $args): void
```