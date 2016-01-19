package org.develnext.jphp.ext.game.support;

public abstract class CollisionHandler extends Pair<String> {

    /**
     * The order of types determines the order of entities in callbacks.
     *
     * @param aType entity type of the first entity
     * @param bType entity type of the second entity
     */
    public CollisionHandler(String aType, String bType) {
        super(aType, bType);
    }

    /**
     * Called once per collision during the same tick when collision occurred.
     * Only the first hit box in the collision is passed.
     *
     * @param a first entity
     * @param b second entity
     * @param boxA hit box of first entity
     * @param boxB hit box of second entity
     */
    protected void onHitBoxTrigger(GameEntity a, GameEntity b, HitBox boxA, HitBox boxB) {
    }

    /**
     * Called when entities A and B have just collided and weren't colliding in the last tick.
     *
     * @param a first entity
     * @param b second entity
     */
    protected void onCollisionBegin(GameEntity a, GameEntity b) {
    }

    /**
     * Called if entities A and B are currently colliding.
     * <p>
     * This is called one tick after {@link #onCollisionBegin(GameEntity, GameEntity)}
     * if the entities are still colliding
     *
     * @param a first entity
     * @param b second entity
     */
    protected void onCollision(GameEntity a, GameEntity b) {
    }

    /**
     * Called when entities A and B have just stopped colliding and were colliding in the last tick.
     *
     * @param a first entity
     * @param b second entity
     */
    protected void onCollisionEnd(GameEntity a, GameEntity b) {
    }

    /**
     * Returns a copy of the collision handler with different entity types.
     * This allows convenient use of the same handler code for
     * multiple entity types.
     *
     * @param aType entity type A
     * @param bType entity type B
     * @return copy of collision handler
     */
    public final CollisionHandler copyFor(String aType, String bType) {
        final CollisionHandler copy = this;

        return new CollisionHandler(aType, bType) {
            @Override
            protected void onHitBoxTrigger(GameEntity a, GameEntity b, HitBox boxA, HitBox boxB) {
                copy.onHitBoxTrigger(a, b, boxA, boxB);
            }

            @Override
            protected void onCollisionBegin(GameEntity a, GameEntity b) {
                copy.onCollisionBegin(a, b);
            }

            @Override
            protected void onCollision(GameEntity a, GameEntity b) {
                copy.onCollision(a, b);
            }

            @Override
            protected void onCollisionEnd(GameEntity a, GameEntity b) {
                copy.onCollisionEnd(a, b);
            }
        };
    }
}
