# MediaPlayerScript

- **class** `MediaPlayerScript` (`script\MediaPlayerScript`) **extends** [`AbstractScript`](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/AbstractScript.md)
- **package** `framework`
- **source** `script/MediaPlayerScript.php`

**Description**

Class MediaPlayerScript

---

#### Properties

- `->`[`autoplay`](#prop-autoplay) : `bool`
- `->`[`_source`](#prop-_source) : `string`
- `->`[`_volume`](#prop-_volume) : `double`
- `->`[`_rate`](#prop-_rate) : `double`
- `->`[`_mute`](#prop-_mute) : `bool`
- `->`[`_balance`](#prop-_balance) : `float`
- `->`[`_loop`](#prop-_loop) : `bool`
- `->`[`_player`](#prop-_player) : `UXMediaPlayer`
- `->`[`_stateTimer`](#prop-_statetimer) : [`TimerScript`](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/script/TimerScript.md)
- `->`[`_view`](#prop-_view) : `UXMediaViewBox`
- *See also in the parent class* [AbstractScript](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/AbstractScript.md).

---

#### Methods

- `->`[`__construct()`](#method-__construct) - _MediaPlayerScript constructor._
- `->`[`_init()`](#method-_init)
- `->`[`applyImpl()`](#method-applyimpl)
- `->`[`getSource()`](#method-getsource)
- `->`[`setSource()`](#method-setsource)
- `->`[`getVolume()`](#method-getvolume)
- `->`[`setVolume()`](#method-setvolume)
- `->`[`getRate()`](#method-getrate)
- `->`[`setRate()`](#method-setrate)
- `->`[`getMute()`](#method-getmute)
- `->`[`setMute()`](#method-setmute)
- `->`[`getLoop()`](#method-getloop)
- `->`[`setLoop()`](#method-setloop)
- `->`[`getBalance()`](#method-getbalance)
- `->`[`setBalance()`](#method-setbalance)
- `->`[`getStatus()`](#method-getstatus)
- `->`[`setPosition()`](#method-setposition)
- `->`[`getPosition()`](#method-getposition)
- `->`[`setPositionMs()`](#method-setpositionms)
- `->`[`getPositionMs()`](#method-getpositionms)
- `->`[`getMedia()`](#method-getmedia)
- `->`[`open()`](#method-open)
- `->`[`stop()`](#method-stop)
- `->`[`pause()`](#method-pause)
- `->`[`play()`](#method-play)
- `->`[`triggerTick()`](#method-triggertick)
- `->`[`getObjectText()`](#method-getobjecttext)
- `->`[`loadContentForObject()`](#method-loadcontentforobject)
- `->`[`applyContentToObject()`](#method-applycontenttoobject)
- `->`[`getObjectValue()`](#method-getobjectvalue)
- `->`[`setObjectValue()`](#method-setobjectvalue)
- `->`[`appendObjectValue()`](#method-appendobjectvalue)
- `->`[`getView()`](#method-getview)
- `->`[`setView()`](#method-setview)
- See also in the parent class [AbstractScript](https://github.com/jphp-compiler/develnext/blob/master/dn-app-framework/api-docs/classes/php/gui/framework/AbstractScript.md)

---
# Methods

<a name="method-__construct"></a>

### __construct()
```php
__construct(string $source): void
```
MediaPlayerScript constructor.

---

<a name="method-_init"></a>

### _init()
```php
_init(mixed $reopen): void
```

---

<a name="method-applyimpl"></a>

### applyImpl()
```php
applyImpl(mixed $target): mixed
```

---

<a name="method-getsource"></a>

### getSource()
```php
getSource(): string
```

---

<a name="method-setsource"></a>

### setSource()
```php
setSource(string $source): void
```

---

<a name="method-getvolume"></a>

### getVolume()
```php
getVolume(): float
```

---

<a name="method-setvolume"></a>

### setVolume()
```php
setVolume(float $volume): void
```

---

<a name="method-getrate"></a>

### getRate()
```php
getRate(): float
```

---

<a name="method-setrate"></a>

### setRate()
```php
setRate(float $rate): void
```

---

<a name="method-getmute"></a>

### getMute()
```php
getMute(): boolean
```

---

<a name="method-setmute"></a>

### setMute()
```php
setMute(boolean $mute): void
```

---

<a name="method-getloop"></a>

### getLoop()
```php
getLoop(): boolean
```

---

<a name="method-setloop"></a>

### setLoop()
```php
setLoop(boolean $loop): void
```

---

<a name="method-getbalance"></a>

### getBalance()
```php
getBalance(): float
```

---

<a name="method-setbalance"></a>

### setBalance()
```php
setBalance(float $balance): void
```

---

<a name="method-getstatus"></a>

### getStatus()
```php
getStatus(): void
```

---

<a name="method-setposition"></a>

### setPosition()
```php
setPosition(mixed $value): void
```

---

<a name="method-getposition"></a>

### getPosition()
```php
getPosition(): void
```

---

<a name="method-setpositionms"></a>

### setPositionMs()
```php
setPositionMs(mixed $value): void
```

---

<a name="method-getpositionms"></a>

### getPositionMs()
```php
getPositionMs(): void
```

---

<a name="method-getmedia"></a>

### getMedia()
```php
getMedia(): UXMedia
```

---

<a name="method-open"></a>

### open()
```php
open(mixed $file): void
```

---

<a name="method-stop"></a>

### stop()
```php
stop(): void
```

---

<a name="method-pause"></a>

### pause()
```php
pause(): void
```

---

<a name="method-play"></a>

### play()
```php
play(): void
```

---

<a name="method-triggertick"></a>

### triggerTick()
```php
triggerTick(): void
```

---

<a name="method-getobjecttext"></a>

### getObjectText()
```php
getObjectText(): void
```

---

<a name="method-loadcontentforobject"></a>

### loadContentForObject()
```php
loadContentForObject(mixed $path): mixed
```

---

<a name="method-applycontenttoobject"></a>

### applyContentToObject()
```php
applyContentToObject(mixed $content): mixed
```

---

<a name="method-getobjectvalue"></a>

### getObjectValue()
```php
getObjectValue(): void
```

---

<a name="method-setobjectvalue"></a>

### setObjectValue()
```php
setObjectValue(mixed $value): void
```

---

<a name="method-appendobjectvalue"></a>

### appendObjectValue()
```php
appendObjectValue(mixed $value): void
```

---

<a name="method-getview"></a>

### getView()
```php
getView(): UXMediaViewBox
```

---

<a name="method-setview"></a>

### setView()
```php
setView(UXMediaViewBox $view): void
```