package org.develnext.jphp.ext.game.support;

public final class CollisionResult {

    /**
     * Constant for reporting no collision.
     * {@link CollisionResult#getBoxA()} and {@link CollisionResult#getBoxB()}
     * return null and {@link CollisionResult#hasCollided()} returns false.
     */
    public static final CollisionResult NO_COLLISION = new CollisionResult();

    private HitBox boxA;
    private HitBox boxB;

    private boolean collided = false;

    private CollisionResult() {
    }

    /**
     * Constructs CollisionResult with positive result, i.e.
     * {@link CollisionResult#hasCollided()} returns true.
     *
     * @param boxA hit box of first entity
     * @param boxB hit box of second entity
     */
    public CollisionResult(HitBox boxA, HitBox boxB) {
        this.boxA = boxA;
        this.boxB = boxB;
        collided = true;
    }

    /**
     *
     * @return hit box of first entity
     */
    public final HitBox getBoxA() {
        return boxA;
    }

    /**
     *
     * @return hit box of second entity
     */
    public final HitBox getBoxB() {
        return boxB;
    }

    /**
     * @return true if collision occurred, false otherwise
     */
    public final boolean hasCollided() {
        return collided;
    }
}