# Collision

- **class** `Collision` (`action\Collision`)
- **package** `framework`
- **source** `action/Collision.php`

**Description**

Class Collision

---

#### Static Methods

- `Collision ::`[`bounce()`](#method-bounce) - _Отскок исходя из нормали столкновения._

---
# Static Methods

<a name="method-bounce"></a>

### bounce()
```php
Collision::bounce(mixed $object, array $normal, float|int $bounciness): void
```
Отскок исходя из нормали столкновения.

Например: Collision::bounce($this->object, $event->normal)