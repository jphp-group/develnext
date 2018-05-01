# SystemTrayScript

- **класс** `SystemTrayScript` (`script\SystemTrayScript`) **унаследован от** `AbstractScript` (`php\gui\framework\AbstractScript`)
- **исходники** `script/SystemTrayScript.php`

---

#### Свойства

- `->`[`_visible`](#prop-_visible) : `string`
- `->`[`_alwaysShowing`](#prop-_alwaysshowing) : `bool`
- `->`[`_icon`](#prop-_icon) : `string`
- `->`[`_tooltip`](#prop-_tooltip) : `string`
- `->`[`_trayIcon`](#prop-_trayicon) : `TrayIcon`
- `->`[`_manualExit`](#prop-_manualexit) : `bool`

---

#### Методы

- `->`[`applyImpl()`](#method-applyimpl)
- `->`[`makeTrayIcon()`](#method-maketrayicon)
- `->`[`setIcon()`](#method-seticon)
- `->`[`getIcon()`](#method-geticon)
- `->`[`getVisible()`](#method-getvisible)
- `->`[`setVisible()`](#method-setvisible)
- `->`[`isSupported()`](#method-issupported)
- `->`[`getTooltip()`](#method-gettooltip)
- `->`[`setTooltip()`](#method-settooltip)
- `->`[`displayMessage()`](#method-displaymessage) - _Displays a popup message near the tray icon.  The message will_
- `->`[`show()`](#method-show) - _Show tray icon._
- `->`[`hide()`](#method-hide) - _Hide tray icon._
- `->`[`free()`](#method-free)
- `->`[`getManualExit()`](#method-getmanualexit)
- `->`[`setManualExit()`](#method-setmanualexit)
- `->`[`getAlwaysShowing()`](#method-getalwaysshowing)
- `->`[`setAlwaysShowing()`](#method-setalwaysshowing)

---
# Методы

<a name="method-applyimpl"></a>

### applyImpl()
```php
applyImpl(mixed $target): mixed
```

---

<a name="method-maketrayicon"></a>

### makeTrayIcon()
```php
makeTrayIcon(): void
```

---

<a name="method-seticon"></a>

### setIcon()
```php
setIcon(string $icon): null
```

---

<a name="method-geticon"></a>

### getIcon()
```php
getIcon(): string
```

---

<a name="method-getvisible"></a>

### getVisible()
```php
getVisible(): string
```

---

<a name="method-setvisible"></a>

### setVisible()
```php
setVisible(string $visible): void
```

---

<a name="method-issupported"></a>

### isSupported()
```php
isSupported(): void
```

---

<a name="method-gettooltip"></a>

### getTooltip()
```php
getTooltip(): string
```

---

<a name="method-settooltip"></a>

### setTooltip()
```php
setTooltip(string $tooltip): void
```

---

<a name="method-displaymessage"></a>

### displayMessage()
```php
displayMessage(string $title, string $text, string $type): void
```
Displays a popup message near the tray icon.  The message will
disappear after a time or if the user clicks on it.  Clicking
on the message may trigger an ActionEvent.

---

<a name="method-show"></a>

### show()
```php
show(): void
```
Show tray icon.

---

<a name="method-hide"></a>

### hide()
```php
hide(): void
```
Hide tray icon.

---

<a name="method-free"></a>

### free()
```php
free(): void
```

---

<a name="method-getmanualexit"></a>

### getManualExit()
```php
getManualExit(): boolean
```

---

<a name="method-setmanualexit"></a>

### setManualExit()
```php
setManualExit(boolean $manualExit): void
```

---

<a name="method-getalwaysshowing"></a>

### getAlwaysShowing()
```php
getAlwaysShowing(): boolean
```

---

<a name="method-setalwaysshowing"></a>

### setAlwaysShowing()
```php
setAlwaysShowing(boolean $alwaysShowing): void
```