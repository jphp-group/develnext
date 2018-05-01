# PrinterScript

- **класс** `PrinterScript` (`script\PrinterScript`) **унаследован от** [`AbstractScript`](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/AbstractScript.ru.md)
- **пакет** `framework`
- **исходники** `script/PrinterScript.php`

---

#### Свойства

- `->`[`printerName`](#prop-printername) : `string`
- `->`[`dialogEnabled`](#prop-dialogenabled) : `bool`
- `->`[`jobName`](#prop-jobname) : `string`
- `->`[`copies`](#prop-copies) : `int`
- `->`[`printColor`](#prop-printcolor) : `string` - _Цвет печати.

DEFAULT, COLOR, MONOCHROME_
- `->`[`printQuality`](#prop-printquality) : `string` - _Качество печати.

DEFAULT, DRAFT, LOW, NORMAL, HIGH_
- `->`[`printSides`](#prop-printsides) : `string` - _Тип печати.

DEFAULT, ONE_SIDED, DUPLEX, TUMBLE_
- `->`[`lastPrinterJob`](#prop-lastprinterjob) : `UXPrinterJob`
- *См. также в родительском классе* [AbstractScript](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/AbstractScript.ru.md).

---

#### Методы

- `->`[`__construct()`](#method-__construct) - _RobotScript constructor._
- `->`[`applyImpl()`](#method-applyimpl)
- `->`[`getPrinter()`](#method-getprinter)
- `->`[`isAvailable()`](#method-isavailable)
- `->`[`getLastPrinterJob()`](#method-getlastprinterjob)
- `->`[`print()`](#method-print) - _Отправить UI компонент на печать._
- См. также в родительском классе [AbstractScript](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/AbstractScript.ru.md)

---
# Методы

<a name="method-__construct"></a>

### __construct()
```php
__construct(): void
```
RobotScript constructor.

---

<a name="method-applyimpl"></a>

### applyImpl()
```php
applyImpl(mixed $target): mixed
```

---

<a name="method-getprinter"></a>

### getPrinter()
```php
getPrinter(): UXPrinter
```

---

<a name="method-isavailable"></a>

### isAvailable()
```php
isAvailable(): bool
```

---

<a name="method-getlastprinterjob"></a>

### getLastPrinterJob()
```php
getLastPrinterJob(): UXPrinterJob
```

---

<a name="method-print"></a>

### print()
```php
print(UXNode $object): bool
```
Отправить UI компонент на печать.