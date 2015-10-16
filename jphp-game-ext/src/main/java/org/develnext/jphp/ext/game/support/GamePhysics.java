package org.develnext.jphp.ext.game.support;

import com.almasb.fxgl.physics.*;
import javafx.geometry.Point2D;
import javafx.util.Pair;
import org.jbox2d.callbacks.ContactImpulse;
import org.jbox2d.callbacks.ContactListener;
import org.jbox2d.callbacks.RayCastCallback;
import org.jbox2d.collision.Manifold;
import org.jbox2d.collision.shapes.PolygonShape;
import org.jbox2d.common.Vec2;
import org.jbox2d.dynamics.Body;
import org.jbox2d.dynamics.Fixture;
import org.jbox2d.dynamics.World;
import org.jbox2d.dynamics.contacts.Contact;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

public class GamePhysics {
    private static final float TIME_STEP = 1 / 60.0f;

    private final GameRoom room;
    private World world = new World(new Vec2(0, 0));


    private List<CollisionHandler> collisionHandlers = new ArrayList<>();
    private Map<CollisionPair, Long> collisions = new HashMap<>();

    private double appHeight;

    public GamePhysics(GameRoom room) {
        this.room = room;
        this.appHeight = room.getAppHeight();

        world.setContactListener(new ContactListener() {
            @Override
            public void beginContact(Contact contact) {
                GameObject e1 = (GameObject) contact.getFixtureA().getBody().getUserData();
                GameObject e2 = (GameObject) contact.getFixtureB().getBody().getUserData();

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
                PhysicsEntity e1 = (PhysicsEntity) contact.getFixtureA().getBody().getUserData();
                PhysicsEntity e2 = (PhysicsEntity) contact.getFixtureB().getBody().getUserData();

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
            public void preSolve(Contact contact, Manifold oldManifold) {}
            @Override
            public void postSolve(Contact contact, ContactImpulse impulse) {}
        });
    }

    public void onUpdate(long now) {
        world.step(TIME_STEP, 8, 3);

        for (Body body = world.getBodyList(); body != null; body = body.getNext()) {
            GameObject e = (GameObject) body.getUserData();

            e.setTranslateX(
                    Math.round(toPixels(
                            body.getPosition().x
                                    - toMeters(e.getLayoutBounds().getWidth() / 2))));

            e.setTranslateY(
                    Math.round(toPixels(
                            toMeters(appHeight) - body.getPosition().y
                                    - toMeters(e.getLayoutBounds().getHeight() / 2))));

            e.setRotate(-Math.toDegrees(body.getAngle()));
        }
    }

    /**
     * Set gravity for the physics world
     *
     * @param x
     * @param y
     */
    public void setGravity(double x, double y) {
        world.setGravity(new Vec2().addLocal((float) x, -(float) y));
    }

    /**
     * Do NOT call manually. This is called by FXGL Application
     * to create a physics body in physics space (world)
     *
     * @param e
     */
    public void createBody(GameObject e) {
        double x = e.getTranslateX(),
                y = e.getTranslateY(),
                w = e.getLayoutBounds().getWidth(),
                h = e.getLayoutBounds().getHeight();

        if (e.fixtureDef.shape == null) {
            PolygonShape rectShape = new PolygonShape();
            rectShape.setAsBox(toMeters(w / 2), toMeters(h / 2));
            e.fixtureDef.shape = rectShape;
        }

        e.bodyDef.position.set(toMeters(x + w / 2), toMeters(appHeight - (y + h / 2)));
        e.body = world.createBody(e.bodyDef);
        e.fixture = e.body.createFixture(e.fixtureDef);
        e.body.setUserData(e);
    }

    /**
     * Do NOT call manually. This is called by FXGL Application
     * to destroy a physics body in physics space (world)
     *
     * @param e
     */
    public void destroyBody(GameObject e) {
        world.destroyBody(e.body);
    }


    /*private EdgeCallback raycastCallback = new EdgeCallback();*/

    /**
     * Performs raycast from start to end
     *
     *
     * @param start world point in pixels
     * @param end world point in pixels
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

    /**
     * Converts pixels to meters
     *
     * @param pixels
     * @return
     */
    public static float toMeters(double pixels) {
        return (float)pixels * 0.05f;
    }

    /**
     * Converts meters to pixels
     *
     * @param meters
     * @return
     */
    public static float toPixels(double meters) {
        return (float)meters * 20f;
    }

    /**
     * Converts a vector of type Point2D to vector of type Vec2
     *
     * @param v
     * @return
     */
    public static Vec2 toVector(Point2D v) {
        return new Vec2(toMeters(v.getX()), toMeters(-v.getY()));
    }

    /**
     * Converts a vector of type Vec2 to vector of type Point2D
     *
     * @param v
     * @return
     */
    public static Point2D toVector(Vec2 v) {
        return new Point2D(toPixels(v.x), toPixels(-v.y));
    }

    /**
     * Converts a point of type Point2D to point of type Vec2
     *
     * @param p
     * @return
     */
    public Vec2 toPoint(Point2D p) {
        return new Vec2(toMeters(p.getX()), toMeters(appHeight - p.getY()));
    }

    /**
     * Converts a point of type Vec2 to point of type Point2D
     *
     * @param p
     * @return
     */
    public Point2D toPoint(Vec2 p) {
        return new Point2D(toPixels(p.x), toPixels(toMeters(appHeight) - p.y));
    }

    private static class EdgeCallback implements RayCastCallback {
        Fixture fixture;
        Vec2 point;
        //Vec2 normal;
        float bestFraction = 1.0f;

        @Override
        public float reportFixture(Fixture fixture, Vec2 point, Vec2 normal, float fraction) {
            PhysicsEntity e = (PhysicsEntity) fixture.getBody().getUserData();
            if (e.isRaycastIgnored())
                return 1;

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
