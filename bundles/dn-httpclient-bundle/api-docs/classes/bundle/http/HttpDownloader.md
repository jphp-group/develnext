# HttpDownloader

- **class** `HttpDownloader` (`bundle\http\HttpDownloader`) **extends** `AbstractScript` (`php\gui\framework\AbstractScript`)
- **package** `httpclient`
- **source** `bundle/http/HttpDownloader.php`

**Description**

Class HttpDownloader

---

#### Properties

- `->`[`client`](#prop-client) : [`HttpClient`](https://github.com/jphp-compiler/develnext/blob/master/bundles/dn-httpclient-bundle/api-docs/classes/bundle/http/HttpClient.md)
- `->`[`destDirectory`](#prop-destdirectory) : `string`
- `->`[`threadCount`](#prop-threadcount) : `int`
- `->`[`bufferSize`](#prop-buffersize) : `int`
- `->`[`urls`](#prop-urls) : `array`
- `->`[`breakOnError`](#prop-breakonerror) : `bool`
- `->`[`useTempFile`](#prop-usetempfile) : `bool`
- `->`[`_break`](#prop-_break) : `bool`
- `->`[`_busy`](#prop-_busy) : `bool`
- `->`[`_startTime`](#prop-_starttime) : `int`
- `->`[`_downloadedBytes`](#prop-_downloadedbytes) : `int`
- `->`[`_urlStats`](#prop-_urlstats) : `SharedMap`
- `->`[`_downloadedFiles`](#prop-_downloadedfiles) : `SharedStack`
- `->`[`_progressFiles`](#prop-_progressfiles) : `SharedMap`
- `->`[`_failedFiles`](#prop-_failedfiles) : `SharedStack`
- `->`[`_threadPool`](#prop-_threadpool) : `ThreadPool`
- `->`[`lazyStart`](#prop-lazystart) : `bool`

---

#### Static Methods

- `HttpDownloader ::`[`textToArray()`](#method-texttoarray)

---

#### Methods

- `->`[`__construct()`](#method-__construct) - _HttpDownloader constructor._
- `->`[`checkBreak()`](#method-checkbreak)
- `->`[`download()`](#method-download)
- `->`[`setStatValue()`](#method-setstatvalue)
- `->`[`stop()`](#method-stop) - _Stop all downloads._
- `->`[`getSpeed()`](#method-getspeed)
- `->`[`getBitSpeed()`](#method-getbitspeed)
- `->`[`stopAsync()`](#method-stopasync)
- `->`[`stopAndWait()`](#method-stopandwait)
- `->`[`start()`](#method-start) - _Start downloading._
- `->`[`free()`](#method-free)
- `->`[`__destruct()`](#method-__destruct)
- `->`[`isBusy()`](#method-isbusy)
- `->`[`isBreak()`](#method-isbreak)
- `->`[`getUrlInfo()`](#method-geturlinfo)
- `->`[`getUrlStatus()`](#method-geturlstatus)
- `->`[`getUrlSize()`](#method-geturlsize)
- `->`[`getUrlProgress()`](#method-geturlprogress)
- `->`[`isUrlSuccess()`](#method-isurlsuccess)
- `->`[`isUrlWaiting()`](#method-isurlwaiting)
- `->`[`isUrlDownloading()`](#method-isurldownloading)
- `->`[`isUrlError()`](#method-isurlerror)
- `->`[`isUrlDone()`](#method-isurldone)
- `->`[`getDownloadedUrls()`](#method-getdownloadedurls)
- `->`[`getFailedUrls()`](#method-getfailedurls)
- `->`[`getProgressUrls()`](#method-getprogressurls)
- `->`[`getAllUrls()`](#method-getallurls)
- `->`[`client()`](#method-client)
- `->`[`loadUrls()`](#method-loadurls) - _Load urls from file, url and other stream._
- `->`[`applyImpl()`](#method-applyimpl)

---
# Static Methods

<a name="method-texttoarray"></a>

### textToArray()
```php
HttpDownloader::textToArray(mixed $text, mixed $trimValues): void
```

---
# Methods

<a name="method-__construct"></a>

### __construct()
```php
__construct(): void
```
HttpDownloader constructor.

---

<a name="method-checkbreak"></a>

### checkBreak()
```php
checkBreak(): void
```

---

<a name="method-download"></a>

### download()
```php
download(string $url, string $fileName): HttpResponse
```

---

<a name="method-setstatvalue"></a>

### setStatValue()
```php
setStatValue(mixed $url, string $name, mixed $value): void
```

---

<a name="method-stop"></a>

### stop()
```php
stop(): void
```
Stop all downloads.

---

<a name="method-getspeed"></a>

### getSpeed()
```php
getSpeed(): int
```

---

<a name="method-getbitspeed"></a>

### getBitSpeed()
```php
getBitSpeed(): float
```

---

<a name="method-stopasync"></a>

### stopAsync()
```php
stopAsync(callable $callback): void
```

---

<a name="method-stopandwait"></a>

### stopAndWait()
```php
stopAndWait(): void
```

---

<a name="method-start"></a>

### start()
```php
start(): void
```
Start downloading.

---

<a name="method-free"></a>

### free()
```php
free(): void
```

---

<a name="method-__destruct"></a>

### __destruct()
```php
__destruct(): void
```

---

<a name="method-isbusy"></a>

### isBusy()
```php
isBusy(): bool
```

---

<a name="method-isbreak"></a>

### isBreak()
```php
isBreak(): bool
```

---

<a name="method-geturlinfo"></a>

### getUrlInfo()
```php
getUrlInfo(string $url): mixed
```

---

<a name="method-geturlstatus"></a>

### getUrlStatus()
```php
getUrlStatus(string $url): string
```

---

<a name="method-geturlsize"></a>

### getUrlSize()
```php
getUrlSize(string $url): int
```

---

<a name="method-geturlprogress"></a>

### getUrlProgress()
```php
getUrlProgress(int $url): float|int
```

---

<a name="method-isurlsuccess"></a>

### isUrlSuccess()
```php
isUrlSuccess(string $url): bool
```

---

<a name="method-isurlwaiting"></a>

### isUrlWaiting()
```php
isUrlWaiting(string $url): bool
```

---

<a name="method-isurldownloading"></a>

### isUrlDownloading()
```php
isUrlDownloading(string $url): bool
```

---

<a name="method-isurlerror"></a>

### isUrlError()
```php
isUrlError(string $url): bool
```

---

<a name="method-isurldone"></a>

### isUrlDone()
```php
isUrlDone(string $url): bool
```

---

<a name="method-getdownloadedurls"></a>

### getDownloadedUrls()
```php
getDownloadedUrls(): array
```

---

<a name="method-getfailedurls"></a>

### getFailedUrls()
```php
getFailedUrls(): array
```

---

<a name="method-getprogressurls"></a>

### getProgressUrls()
```php
getProgressUrls(): array
```

---

<a name="method-getallurls"></a>

### getAllUrls()
```php
getAllUrls(): array
```

---

<a name="method-client"></a>

### client()
```php
client(): HttpClient
```

---

<a name="method-loadurls"></a>

### loadUrls()
```php
loadUrls(string $source, string $encoding): void
```
Load urls from file, url and other stream.

---

<a name="method-applyimpl"></a>

### applyImpl()
```php
applyImpl(mixed $target): mixed
```