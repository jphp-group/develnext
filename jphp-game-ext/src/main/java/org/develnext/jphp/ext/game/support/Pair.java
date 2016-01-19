package org.develnext.jphp.ext.game.support;

class Pair<T> {
    private T a, b;

    Pair(T a, T b) {
        if (a == null || b == null)
            throw new IllegalArgumentException("Objects must not be null");
        this.a = a;
        this.b = b;
    }

    T getA() {
        return a;
    }

    T getB() {
        return b;
    }

    @Override
    public boolean equals(Object o) {
        Pair<?> pair = (Pair<?>) o;
        return (pair.a == a && pair.b == b)
                || (pair.a == b && pair.b == a);
    }

    @Override
    public int hashCode() {
        return a.hashCode() + b.hashCode();
    }
}
