package org.develnext.jphp.ext.game.support;

import javafx.geometry.Point2D;
import org.jbox2d.callbacks.RayCastCallback;
import org.jbox2d.collision.shapes.PolygonShape;
import org.jbox2d.common.Vec2;
import org.jbox2d.dynamics.Body;
import org.jbox2d.dynamics.Fixture;
import org.jbox2d.dynamics.World;

public class GamePhysics {
    private static final float TIME_STEP = 1 / 60.0f;

    private final GameScene scene;
    private World world = new World(new Vec2(0, 0));

   // private List<CollisionHandler> collisionHandlers = new ArrayList<>();
   // private Map<CollisionPair, Long> collisions = new HashMap<>();


    public GamePhysics(GameScene scene) {
        this.scene = scene;

        /*world.setContactListener(new ContactListener() {
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
        }); */
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
                            toMeters(scene.getAppHeight()) - body.getPosition().y
                                    - toMeters(e.getLayoutBounds().getHeight() / 2))));

            e.setRotate(-Math.toDegrees(body.getAngle()));
        }
    }

    public void setGravity(double x, double y) {
        world.setGravity(new Vec2().addLocal((float) x, -(float) y));
    }

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

        e.bodyDef.position.set(toMeters(x + w / 2), toMeters(scene.getAppHeight() - (y + h / 2)));
        e.body = world.createBody(e.bodyDef);
        e.fixture = e.body.createFixture(e.fixtureDef);
        e.body.setUserData(e);
    }

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
            GamePhysics e = (GamePhysics) fixture.getBody().getUserData();
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
