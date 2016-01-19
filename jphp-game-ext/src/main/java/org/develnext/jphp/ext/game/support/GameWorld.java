package org.develnext.jphp.ext.game.support;

import javafx.beans.property.LongProperty;
import javafx.beans.property.SimpleLongProperty;
import javafx.geometry.Point2D;
import javafx.util.Pair;
import org.jbox2d.callbacks.ContactImpulse;
import org.jbox2d.callbacks.ContactListener;
import org.jbox2d.callbacks.RayCastCallback;
import org.jbox2d.collision.Manifold;
import org.jbox2d.collision.shapes.PolygonShape;
import org.jbox2d.common.Vec2;
import org.jbox2d.dynamics.Body;
import org.jbox2d.dynamics.BodyType;
import org.jbox2d.dynamics.Fixture;
import org.jbox2d.dynamics.World;
import org.jbox2d.dynamics.contacts.Contact;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

public class GameWorld {
    private static final float TIME_STEP = 1 / 60.0f;

    private final GameScene scene;

    private World world = new World(new Vec2(0, 0));

    private List<CollisionHandler> collisionHandlers = new ArrayList<>();
    private Map<CollisionPair, Long> collisions = new HashMap<>();

    private LongProperty tick = new SimpleLongProperty(0);

    public GameWorld(GameScene scene) {
        this.scene = scene;

        world.setContactListener(new ContactListener() {
            @Override
            public void beginContact(Contact contact) {
                GameEntity e1 = (GameEntity) contact.getFixtureA().getBody().getUserData();
                GameEntity e2 = (GameEntity) contact.getFixtureB().getBody().getUserData();

                System.out.println("Collision for " + e1.getEntityType() + " with " + e2.getEntityType());
                System.exit(1);

                if (!e1.isCollidable() || !e2.isCollidable())
                    return;

                int index = collisionHandlers.indexOf(new Pair<>(e1.getEntityType(), e2.getEntityType()));
                if (index != -1) {
                    CollisionPair pair = new CollisionPair(e1, e2, collisionHandlers.get(index));

                    if (!collisions.containsKey(pair)) {
                        collisions.put(pair, tick.get());
                    }
                }

            }

            @Override
            public void endContact(Contact contact) {
                GameEntity e1 = (GameEntity) contact.getFixtureA().getBody().getUserData();
                GameEntity e2 = (GameEntity) contact.getFixtureB().getBody().getUserData();

                if (!e1.isCollidable() || !e2.isCollidable())
                    return;

                int index = collisionHandlers.indexOf(new Pair<>(e1.getEntityType(), e2.getEntityType()));
                if (index != -1) {
                    CollisionPair pair = new CollisionPair(e1, e2, collisionHandlers.get(index));

                    if (collisions.containsKey(pair)) {
                        collisions.put(pair, -1L);
                    }
                }
            }

            @Override
            public void preSolve(Contact contact, Manifold oldManifold) {
            }

            @Override
            public void postSolve(Contact contact, ContactImpulse impulse) {
            }
        });
    }

    /**
     * Perform collision detection for all entities that have
     * setCollidable(true) and if at least one entity is not PhysicsEntity.
     * Subsequently fire collision handlers for all entities that have
     * setCollidable(true).
     */
    private void processCollisions() {
        List<GameEntity> collidables = new ArrayList<>();

        for (GameEntity entity : scene.entities) {
            if (entity.isCollidable()) {
                collidables.add(entity);
            }
        }

        for (int i = 0; i < collidables.size(); i++) {
            GameEntity e1 = collidables.get(i);

            for (int j = i + 1; j < collidables.size(); j++) {
                GameEntity e2 = collidables.get(j);

                if (e1.isPhysics() && e2.isPhysics()) {
                    boolean skip = true;
                    if ((e1.body.getType() == BodyType.KINEMATIC && e2.body.getType() == BodyType.STATIC)
                            || (e2.body.getType() == BodyType.KINEMATIC && e1.body.getType() == BodyType.STATIC)) {
                        skip = false;
                    }

                    if (skip) {
                        continue;
                    }
                }

                int index = collisionHandlers.indexOf(new Pair<>(e1.getEntityType(), e2.getEntityType()));
                if (index != -1) {
                    CollisionPair pair = new CollisionPair(e1, e2, collisionHandlers.get(index));

                    CollisionResult result = e1.checkCollision(e2);

                    if (result.hasCollided()) {
                        if (!collisions.containsKey(pair)) {
                            collisions.put(pair, tick.get());
                            pair.getHandler().onHitBoxTrigger(pair.getA(), pair.getB(), result.getBoxA(), result.getBoxB());
                        }
                    } else {
                        if (collisions.containsKey(pair)) {
                            collisions.put(pair, -1L);
                        }
                    }
                }
            }
        }

        final List<CollisionPair> toRemove = new ArrayList<>();

        for (Map.Entry<CollisionPair, Long> entry : collisions.entrySet()) {
            CollisionPair pair = entry.getKey();
            Long cachedTick = entry.getValue();

            if (/*!pair.getA().isActive() || !pair.getB().isActive()
                    ||*/ !pair.getA().isCollidable() || !pair.getB().isCollidable()) {
                toRemove.add(pair);
                return;
            }

            if (cachedTick == -1L) {
                pair.getHandler().onCollisionEnd(pair.getA(), pair.getB());
                toRemove.add(pair);
            } else if (tick.get() == cachedTick) {
                pair.getHandler().onCollisionBegin(pair.getA(), pair.getB());
            } else if (tick.get() > cachedTick) {
                pair.getHandler().onCollision(pair.getA(), pair.getB());
            }
        }

        for (CollisionPair pair : toRemove) {
            collisions.remove(pair);
        }
    }

    public void onUpdate(long now) {
        world.step(TIME_STEP, 8, 3);

        processCollisions();

        for (Body body = world.getBodyList(); body != null; body = body.getNext()) {
            GameEntity e = (GameEntity) body.getUserData();

            System.out.println(e);

            e.setX(Math.round(toPixels(body.getPosition().x - toMeters(e.getWidth() / 2))));
            e.setY(Math.round(toPixels(/*toMeters(scene.getAppHeight())*/ - body.getPosition().y - toMeters(e.getHeight() / 2))));

            e.setRotation(-Math.toDegrees(body.getAngle()));
        }
    }

    public void setGravity(double x, double y) {
        world.setGravity(new Vec2().addLocal((float) x, -(float) y));
    }

    public void createBody(GameEntity e) {
        double x = e.getX(),
                y = e.getY(),
                w = e.getWidth(),
                h = e.getHeight();

        if (e.fixtureDef.shape == null) {
            PolygonShape rectShape = new PolygonShape();
            rectShape.setAsBox(toMeters(w / 2), toMeters(h / 2));
            e.fixtureDef.shape = rectShape;
        }

        e.bodyDef.position.set(toMeters(x + w / 2), toMeters(scene.getAppHeight() - (y + h / 2)));
        e.body = world.createBody(e.bodyDef);
        e.fixture = e.body.createFixture(e.fixtureDef);
        e.body.setUserData(e);
    }

    public void destroyBody(GameEntity e) {
        world.destroyBody(e.body);
    }

    /*private EdgeCallback raycastCallback = new EdgeCallback();*/

    /**
     * Performs raycast from start to end
     * @return result of raycast
     */
   /* public RaycastResult raycast(Point2D start, Point2D end) {
        raycastCallback.reset();
        world.raycast(raycastCallback, toPoint(start), toPoint(end));

        PhysicsEntity entity = null;
        Point2D point = null;

        if (raycastCallback.fixture != null)
            entity = (PhysicsEntity) raycastCallback.fixture.getBody().getUserData();

        if (raycastCallback.point != null)
            point = toPoint(raycastCallback.point);

        return new RaycastResult(Optional.ofNullable(entity), Optional.ofNullable(point));
    }*/

    public static float toMeters(double pixels) {
        return (float)pixels * 0.05f;
    }

    public static float toPixels(double meters) {
        return (float)meters * 20f;
    }

    public static Vec2 toVector(Point2D v) {
        return new Vec2(toMeters(v.getX()), toMeters(-v.getY()));
    }

    public static Point2D toVector(Vec2 v) {
        return new Point2D(toPixels(v.x), toPixels(-v.y));
    }

    public Vec2 toPoint(Point2D p) {
        return new Vec2(toMeters(p.getX()), toMeters(scene.getAppHeight() - p.getY()));
    }

    public Point2D toPoint(Vec2 p) {
        return new Point2D(toPixels(p.x), toPixels(toMeters(scene.getAppHeight()) - p.y));
    }

    private static class EdgeCallback implements RayCastCallback {
        Fixture fixture;
        Vec2 point;
        //Vec2 normal;
        float bestFraction = 1.0f;

        @Override
        public float reportFixture(Fixture fixture, Vec2 point, Vec2 normal, float fraction) {
            GameWorld e = (GameWorld) fixture.getBody().getUserData();
            /*if (e.isRaycastIgnored())
                return 1; */

            if (fraction < bestFraction) {
                this.fixture = fixture;
                this.point = point.clone();
                //this.normal = normal.clone();
                bestFraction = fraction;
            }

            return bestFraction;
        }

        void reset() {
            fixture = null;
            point = null;
            bestFraction = 1.0f;
        }
    }
}
