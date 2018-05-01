# Json

- **класс** `Json` (`facade\Json`)
- **исходники** `facade/Json.php`

**Описание**

Class Json

---

#### Статичные Методы

- `Json ::`[`encode()`](#method-encode)
- `Json ::`[`decode()`](#method-decode)
- `Json ::`[`toFile()`](#method-tofile)
- `Json ::`[`fromFile()`](#method-fromfile)

---
# Статичные Методы

<a name="method-encode"></a>

### encode()
```php
Json::encode(mixed $data, bool $prettyPrint): string
```

---

<a name="method-decode"></a>

### decode()
```php
Json::decode(mixed $string): mixed
```

---

<a name="method-tofile"></a>

### toFile()
```php
Json::toFile(mixed $filename, mixed $data): void
```

---

<a name="method-fromfile"></a>

### fromFile()
```php
Json::fromFile(mixed $filename): array|null
```