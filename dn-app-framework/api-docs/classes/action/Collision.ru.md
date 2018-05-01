# Collision

- **класс** `Collision` (`action\Collision`)
- **пакет** `framework`
- **исходники** `action/Collision.php`

**Описание**

Утилитный класс для обработки столкновений.

---

#### Статичные Методы

- `Collision ::`[`bounce()`](#method-bounce) - _Отскок исходя из нормали столкновения._

---
# Статичные Методы

<a name="method-bounce"></a>

### bounce()
```php
Collision::bounce(mixed $object, array $normal, float|int $bounciness): void
```
Отскок исходя из нормали столкновения.

Например: Collision::bounce($this->object, $event->normal)