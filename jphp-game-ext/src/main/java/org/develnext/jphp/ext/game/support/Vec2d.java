package org.develnext.jphp.ext.game.support;

import java.io.Serializable;

public class Vec2d implements Serializable {
    public static final Vec2d ZERO = new Vec2d(0, 0);

    /** A "close to zero" float epsilon value for use */
    public static final float EPSILON = 1.1920928955078125E-7f;

    private static final long serialVersionUID = 1L;

    public double x, y;

    public Vec2d() {
        this(0, 0);
    }

    public Vec2d(double x, double y) {
        this.x = x;
        this.y = y;
    }

    public Vec2d(Vec2d toCopy) {
        this(toCopy.x, toCopy.y);
    }

    /** Zero out this vector. */
    public final void setZero() {
        x = 0.0f;
        y = 0.0f;
    }

    /** Set the vector component-wise. */
    public final Vec2d set(float x, float y) {
        this.x = x;
        this.y = y;
        return this;
    }

    /** Set this vector to another vector. */
    public final Vec2d set(Vec2d v) {
        this.x = v.x;
        this.y = v.y;
        return this;
    }

    /** Return the sum of this vector and another; does not alter either one. */
    public final Vec2d add(Vec2d v) {
        return new Vec2d(x + v.x, y + v.y);
    }



    /** Return the difference of this vector and another; does not alter either one. */
    public final Vec2d sub(Vec2d v) {
        return new Vec2d(x - v.x, y - v.y);
    }

    /** Return this vector multiplied by a scalar; does not alter this vector. */
    public final Vec2d mul(float a) {
        return new Vec2d(x * a, y * a);
    }

    /** Return the negation of this vector; does not alter this vector. */
    public final Vec2d negate() {
        return new Vec2d(-x, -y);
    }

    /** Flip the vector and return it - alters this vector. */
    public final Vec2d negateLocal() {
        x = -x;
        y = -y;
        return this;
    }

    /** Add another vector to this one and returns result - alters this vector. */
    public final Vec2d addLocal(Vec2d v) {
        x += v.x;
        y += v.y;
        return this;
    }

    /** Adds values to this vector and returns result - alters this vector. */
    public final Vec2d addLocal(float x, float y) {
        this.x += x;
        this.y += y;
        return this;
    }

    /** Subtract another vector from this one and return result - alters this vector. */
    public final Vec2d subLocal(Vec2d v) {
        x -= v.x;
        y -= v.y;
        return this;
    }

    /** Multiply this vector by a number and return result - alters this vector. */
    public final Vec2d mulLocal(float a) {
        x *= a;
        y *= a;
        return this;
    }

    /** Get the skew vector such that dot(skew_vec, other) == cross(vec, other) */
    public final Vec2d skew() {
        return new Vec2d(-y, x);
    }

    /** Get the skew vector such that dot(skew_vec, other) == cross(vec, other) */
    public final void skew(Vec2d out) {
        out.x = -y;
        out.y = x;
    }

    /** Return the length of this vector. */
    public final double length() {
        return Math.sqrt(x * x + y * y);
    }

    /** Return the squared length of this vector. */
    public final double lengthSquared() {
        return (x * x + y * y);
    }

    /** Normalize this vector and return the length before normalization. Alters this vector. */
    public final double normalize() {
        double length = length();
        if (length < EPSILON) {
            return 0f;
        }

        double invLength = 1.0f / length;
        x *= invLength;
        y *= invLength;
        return length;
    }

    /** True if the vector represents a pair of valid, non-infinite floating point numbers. */
    public final boolean isValid() {
        return !Double.isNaN(x) && !Double.isInfinite(x) && !Double.isNaN(y) && !Double.isInfinite(y);
    }

    /** Return a new vector that has positive components. */
    public final Vec2d abs() {
        return new Vec2d(Math.abs(x), Math.abs(y));
    }

    public final void absLocal() {
        x = Math.abs(x);
        y = Math.abs(y);
    }

    // @Override // annotation omitted for GWT-compatibility
    /** Return a copy of this vector. */
    public final Vec2d clone() {
        return new Vec2d(x, y);
    }

    @Override
    public final String toString() {
        return "(" + x + "," + y + ")";
    }

  /*
   * Static
   */

    public final static Vec2d abs(Vec2d a) {
        return new Vec2d(Math.abs(a.x), Math.abs(a.y));
    }

    public final static void absToOut(Vec2d a, Vec2d out) {
        out.x = Math.abs(a.x);
        out.y = Math.abs(a.y);
    }

    public final static double dot(Vec2d a, Vec2d b) {
        return a.x * b.x + a.y * b.y;
    }

    public final static double cross(Vec2d a, Vec2d b) {
        return a.x * b.y - a.y * b.x;
    }

    public final static Vec2d cross(Vec2d a, double s) {
        return new Vec2d(s * a.y, -s * a.x);
    }

    public final static void crossToOut(Vec2d a, double s, Vec2d out) {
        final double tempy = -s * a.x;
        out.x = s * a.y;
        out.y = tempy;
    }

    public final static void crossToOutUnsafe(Vec2d a, float s, Vec2d out) {
        assert (out != a);
        out.x = s * a.y;
        out.y = -s * a.x;
    }

    public final static Vec2d cross(float s, Vec2d a) {
        return new Vec2d(-s * a.y, s * a.x);
    }

    public final static void crossToOut(float s, Vec2d a, Vec2d out) {
        final double tempY = s * a.x;
        out.x = -s * a.y;
        out.y = tempY;
    }

    public final static void crossToOutUnsafe(float s, Vec2d a, Vec2d out) {
        assert (out != a);
        out.x = -s * a.y;
        out.y = s * a.x;
    }

    public final static void negateToOut(Vec2d a, Vec2d out) {
        out.x = -a.x;
        out.y = -a.y;
    }

    public final static Vec2d min(Vec2d a, Vec2d b) {
        return new Vec2d(a.x < b.x ? a.x : b.x, a.y < b.y ? a.y : b.y);
    }

    public final static Vec2d max(Vec2d a, Vec2d b) {
        return new Vec2d(a.x > b.x ? a.x : b.x, a.y > b.y ? a.y : b.y);
    }

    public final static void minToOut(Vec2d a, Vec2d b, Vec2d out) {
        out.x = a.x < b.x ? a.x : b.x;
        out.y = a.y < b.y ? a.y : b.y;
    }

    public final static void maxToOut(Vec2d a, Vec2d b, Vec2d out) {
        out.x = a.x > b.x ? a.x : b.x;
        out.y = a.y > b.y ? a.y : b.y;
    }

    /**
     * @see java.lang.Object#hashCode()
     */
    @Override
    public int hashCode() { // automatically generated by Eclipse
        final int prime = 31;
        long result = 1;
        result = prime * result + Double.doubleToLongBits(x);
        result = prime * result + Double.doubleToLongBits(y);
        return (int) result;
    }

    /**
     * @see java.lang.Object#equals(java.lang.Object)
     */
    @Override
    public boolean equals(Object obj) { // automatically generated by Eclipse
        if (this == obj) return true;
        if (obj == null) return false;
        if (getClass() != obj.getClass()) return false;
        Vec2d other = (Vec2d) obj;
        if (Double.doubleToLongBits(x) != Double.doubleToLongBits(other.x)) return false;
        if (Double.doubleToLongBits(y) != Double.doubleToLongBits(other.y)) return false;
        return true;
    }
}