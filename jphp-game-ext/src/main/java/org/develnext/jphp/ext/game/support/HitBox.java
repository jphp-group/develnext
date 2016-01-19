package org.develnext.jphp.ext.game.support;

import javafx.geometry.BoundingBox;
import javafx.geometry.Bounds;

public final class HitBox {
    private Bounds bounds;
    private String name;

    public HitBox(String name, BoundingBox bounds) {
        this.name = name;
        this.bounds = bounds;
    }

    /**
     * Computes new bounds based on translated X and Y
     * of an entity.
     *
     * @param x entity x
     * @param y entity y
     * @return computed bounds
     */
    public Bounds translate(double x, double y) {
        return new BoundingBox(x + bounds.getMinX(), y + bounds.getMinY(),
                bounds.getWidth(), bounds.getHeight());
    }

    /**
     * Computes new bounds based on translated X and Y
     * of an entity with X axis being flipped.
     *
     * @param x entity x
     * @param y entity y
     * @param entityWidth entity width
     * @return computed bounds
     */
    public Bounds translateXFlipped(double x, double y, double entityWidth) {
        return new BoundingBox(x + entityWidth - bounds.getMinX() - bounds.getWidth(), y + bounds.getMinY(),
                bounds.getWidth(), bounds.getHeight());
    }

    /**
     *
     * @return maxX of internal bounds (x + width)
     */
    public double getMaxX() {
        return bounds.getMaxX();
    }

    /**
     *
     * @return maxY of internal bounds (y + height)
     */
    public double getMaxY() {
        return bounds.getMaxY();
    }

    /**
     *
     * @return hit box name
     */
    public String getName() {
        return name;
    }
}
