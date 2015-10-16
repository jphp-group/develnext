package org.develnext.jphp.ext.game.support;

import com.almasb.fxgl.physics.PhysicsManager;
import javafx.geometry.Point2D;
import javafx.scene.Node;
import javafx.scene.Parent;
import javafx.scene.shape.Circle;
import org.jbox2d.common.Vec2;
import org.jbox2d.dynamics.Body;
import org.jbox2d.dynamics.BodyDef;
import org.jbox2d.dynamics.BodyType;
import org.jbox2d.dynamics.FixtureDef;

public class GameObject extends Parent {
    private boolean collidable = false;

    /*package-private*/ FixtureDef fixtureDef = new FixtureDef();
    /*package-private*/ BodyDef bodyDef = new BodyDef();

    /*package-private*/ Body body;
    /*package-private*/ org.jbox2d.dynamics.Fixture fixture;

    private boolean raycastIgnored = false;

    protected Sprite sprite;

    public GameObject(Sprite sprite) {
        this.sprite = sprite;
        setGraphics(sprite);
    }

    /**
     * Do NOT call manually. It is called automatically
     * by FXGL GameApplication
     *
     * @param now
     */
    public final void update(long now) {
        onUpdate(now);
    }

    /**
     * Can be overridden to provide subclass implementation.
     */
    protected void onUpdate(long now) {
        if (sprite != null) {
            sprite.drawByTime(now);
        }
    }

    private GameObject setGraphics(Node graphics) {
        getChildren().clear();

        if (graphics instanceof Circle) {
            Circle c = (Circle) graphics;
            c.setCenterX(c.getRadius());
            c.setCenterY(c.getRadius());
        }

        getChildren().add(graphics);
        return this;
    }

    /**
     * Allow this entity to participate in collision detection
     *
     * @param b
     */
    public final GameObject setCollidable(boolean b) {
        collidable = b;
        return this;
    }

    // TODO: check various rotations and angles
    /**
     *
     * @return width of the bounding box of this entity
     */
    public final double getWidth() {
        return getLayoutBounds().getWidth();
    }

    /**
     *
     * @return height of the bounding box of this entity
     */
    public final double getHeight() {
        return getLayoutBounds().getHeight();
    }


    /**
     *
     * @return center point of this entity
     */
    public final Point2D getCenter() {
        return getPosition().add(getWidth() / 2, getHeight() / 2);
    }

    /**
     *
     * @return entity position - translation from the parent's origin
     */
    public final Point2D getPosition() {
        return new Point2D(getTranslateX(), getTranslateY());
    }

    /**
     * Set custom fixture definition to describe a generated
     * fixture for this physics entity
     *
     * @param def
     * @return this object
     */
    public GameObject setFixtureDef(FixtureDef def) {
        fixtureDef = def;
        return this;
    }

    /**
     * Set custom body definition to describe a generated
     * body for this physics entity
     *
     * @param def
     * @return this entity
     */
    public GameObject setBodyDef(BodyDef def) {
        bodyDef = def;
        return this;
    }

    /**
     * A convenience method to avoid setting body definition
     * if only a change of body type is required
     *
     * @param type
     * @return this entity
     */
    public GameObject setBodyType(BodyType type) {
        bodyDef.type = type;
        return this;
    }

    /**
     * Set linear velocity for a physics entity
     *
     * Use this method to move a physics entity
     * Please note that the vector x and y are in pixels
     *
     * @param vector x and y in pixels
     * @return this entity
     */
    public GameObject setLinearVelocity(Point2D vector) {
        return setBodyLinearVelocity(GamePhysics.toVector(vector).mulLocal(60));
    }

    /**
     *
     * @param vector x and y in pixels
     * @return this
     */
    public GameObject applyForce(Point2D vector) {
        return applyBodyForceToCenter(GamePhysics.toVector(vector).mulLocal(60));
    }

    public GameObject applyForce(double x, double y) {
        return applyForce(new Point2D(x, y));
    }

    /**
     * Set linear velocity for a physics entity
     *
     * Use this method to move a physics entity
     * Please note that x and y are in pixels
     *
     * @param x and y in pixels
     * @return this entity
     */
    public GameObject setLinearVelocity(double x, double y) {
        return setLinearVelocity(new Point2D(x, y));
    }

    /**
     * Set linear velocity for a physics entity
     *
     * Similar to {@link #setLinearVelocity(Point2D)} but
     * x and y of the argument are in meters
     *
     * @param vector x and y in meters
     * @return
     */
    public GameObject setBodyLinearVelocity(Vec2 vector) {
        if (body == null)
            throw new IllegalStateException("PhysicsEntity has not been added to the world yet! Call addEntities(entity) first");

        body.setLinearVelocity(vector);
        return this;
    }

    public GameObject applyBodyForceToCenter(Vec2 vector) {
        if (body == null)
            throw new IllegalStateException("PhysicsEntity has not been added to the world yet! Call addEntities(entity) first");

        body.applyForceToCenter(vector);
        return this;
    }

    /**
     *
     * @return linear velocity in pxels
     */
    public Point2D getLinearVelocity() {
        if (body == null)
            throw new IllegalStateException("PhysicsEntity has not been added to the world yet! Call addEntities(entity) first");

        return PhysicsManager.toVector(body.getLinearVelocity().mul(1/60f));
    }

    /**
     * Set true to make raycast ignore this entity
     *
     * @param b
     */
    public void setRaycastIgnored(boolean b) {
        raycastIgnored = b;
    }

    /**
     *
     * @return true if raycast should ignore this entity,
     *          false otherwise
     */
    public boolean isRaycastIgnored() {
        return raycastIgnored;
    }

    public boolean isCollidable() {
        return collidable;
    }

    public Sprite getSprite() {
        return sprite;
    }

    public void wakeup() {
        body.setActive(true);
    }
}
