package org.develnext.jphp.ext.game.support;

import javafx.beans.property.BooleanProperty;
import javafx.beans.property.DoubleProperty;
import javafx.beans.property.SimpleBooleanProperty;
import javafx.beans.property.SimpleDoubleProperty;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.event.ActionEvent;
import javafx.event.Event;
import javafx.event.EventHandler;
import javafx.geometry.BoundingBox;
import javafx.geometry.Bounds;
import javafx.geometry.Point2D;
import javafx.scene.Node;
import org.jbox2d.common.Vec2;
import org.jbox2d.dynamics.*;

import java.util.ArrayList;
import java.util.List;
import java.util.function.Predicate;

public class GameEntity {
    private boolean collidable = false;
    private boolean physics = false;

    /*package-private*/ FixtureDef fixtureDef = new FixtureDef();
    /*package-private*/ BodyDef bodyDef = new BodyDef();

    /*package-private*/ Body body;
    /*package-private*/ Fixture fixture;

    private boolean raycastIgnored = false;

    private List<Control> controls = new ArrayList<>();

    protected GameScene gameScene;

    protected final Node node;

    protected EventHandler<Event> onUpdate;

    protected final String entityType;

    public GameEntity(String entityType, Node node) {
        this.entityType = entityType;
        this.node = node;
    }

    public void init(GameScene world) {
        if (generateHitBoxesFromView) {
            addHitBox(new HitBox("__BODY__", new BoundingBox(0, 0,
                    world.getSceneWidth(),
                    world.getSceneHeight())
            ));
        }
    }

    public GameScene getGameScene() {
        return gameScene;
    }

    public void setGameScene(GameScene gameScene) {
        this.gameScene = gameScene;
    }

    public EventHandler<Event> getOnUpdate() {
        return onUpdate;
    }

    public void setOnUpdate(EventHandler<Event> doUpdate) {
        this.onUpdate = doUpdate;
    }

    public final void update(final long now) {
        if (onUpdate != null) {
            onUpdate.handle(new ActionEvent(this, null));
        }

        for (Control control : controls) {
            control.onUpdate(GameEntity.this, now);
        }

        onUpdate(now);
    }

    protected void onUpdate(long now) {
        /*if (sprite != null) {
            sprite.drawByTime(this, now);
        }*/
    }

    /**
     * Computes width of entity based on its hit boxes.
     *
     * @return width
     * @implNote it computes the rightmost point in X, so
     * it will return incorrect width if hit box doesn't start from 0
     */
    private double computeWidth() {
        double max = 0;

        for (HitBox box : hitBoxes) {
            if (box.getMaxX() > max) {
                max = box.getMaxX();
            }
        }

        return max;
    }

    /**
     * Computes height of entity based on its hit boxes.
     *
     * @return height
     * @implNote it computes the highest point in Y, so
     * it will return incorrect height if hit box doesn't start from 0
     */
    private double computeHeight() {
        double max = 0;

        for (HitBox box : hitBoxes) {
            if (box.getMaxY() > max) {
                max = box.getMaxY();
            }
        }

        return max;
    }

    /**
     * @return width of the bounding box of this entity
     */
    public final double getWidth() {
        return computeWidth();
    }

    /**
     * @return height of the bounding box of this entity
     */
    public final double getHeight() {
        return computeHeight();
    }

    public final GameEntity setCollidable(boolean b) {
        collidable = b;
        return this;
    }

    public boolean isPhysics() {
        return physics;
    }

    public void setPhysics(boolean physics) {
        this.physics = physics;
    }

    public final Point2D getCenter() {
        Bounds bounds = node.getLayoutBounds();
        return getPosition().add(bounds.getWidth() / 2, bounds.getHeight() / 2);
    }

    public GameEntity setFixtureDef(FixtureDef def) {
        fixtureDef = def;
        return this;
    }

    public GameEntity setBodyDef(BodyDef def) {
        bodyDef = def;
        return this;
    }

    public void setBodyType(BodyType type) {
        bodyDef.type = type;
    }

    public BodyType getBodyType() {
        return bodyDef.type;
    }

    public GameEntity setLinearVelocity(Point2D vector) {
        return setBodyLinearVelocity(GameWorld.toVector(vector).mulLocal(60));
    }

    public GameEntity applyForce(Point2D vector) {
        return applyBodyForceToCenter(GameWorld.toVector(vector).mulLocal(60));
    }

    public GameEntity applyForce(double x, double y) {
        return applyForce(new Point2D(x, y));
    }

    public GameEntity setLinearVelocity(double x, double y) {
        return setLinearVelocity(new Point2D(x, y));
    }

    public GameEntity setBodyLinearVelocity(Vec2 vector) {
        if (body == null)
            throw new IllegalStateException("PhysicsEntity has not been added to the world yet! Call addEntities(entity) first");

        body.setLinearVelocity(vector);
        return this;
    }

    public GameEntity applyBodyForceToCenter(Vec2 vector) {
        if (body == null)
            throw new IllegalStateException("PhysicsEntity has not been added to the world yet! Call addEntities(entity) first");

        body.applyForceToCenter(vector);
        return this;
    }

    public Point2D getLinearVelocity() {
        if (body == null)
            throw new IllegalStateException("PhysicsEntity has not been added to the world yet! Call addEntities(entity) first");

        return GameWorld.toVector(body.getLinearVelocity().mul(1 / 60f));
    }

    public void setRaycastIgnored(boolean b) {
        raycastIgnored = b;
    }

    public boolean isRaycastIgnored() {
        return raycastIgnored;
    }

    public boolean isCollidable() {
        return collidable;
    }

    public void wakeup() {
        body.setActive(true);
    }

    @SuppressWarnings("unchecked")
    public final <T extends Control> T getControl(Class<T> controlType) {
        for (Control c : controls) {
            if (controlType.isAssignableFrom(c.getClass())) {
                return (T) c;
            }
        }
        return null;
    }

    /**
     * Add behavior to entity
     */
    public final void addControl(Control control) {
        controls.add(control);
    }

    /**
     * Remove behavior from entity
     */
    public final void removeControl(Control control) {
        controls.remove(control);
    }

    public String getEntityType() {
        return entityType;
    }

    public boolean isType(String a) {
        return entityType.equals(a);
    }

    private DoubleProperty x = new SimpleDoubleProperty();

    /**
     * @return x property
     */
    public final DoubleProperty xProperty() {
        return x;
    }

    /**
     * Returns x coordinate of the entity's position.
     * Note: transformations like rotation may affect
     * the visual position but will not affect the value retrieved.
     *
     * @return x coordinate
     */
    public final double getX() {
        return xProperty().get();
    }

    /**
     * Set x position
     *
     * @param x coordinate of entity position
     */
    public final void setX(double x) {
        xProperty().set(x);
    }

    private DoubleProperty y = new SimpleDoubleProperty();

    /**
     * @return y property
     */
    public final DoubleProperty yProperty() {
        return y;
    }

    /**
     * Returns y coordinate of the entity's position.
     * Note: transformations like rotation may affect
     * the visual position but will not affect the value retrieved.
     *
     * @return y coordinate
     */
    public final double getY() {
        return yProperty().get();
    }

    /**
     * Set y position.
     *
     * @param y coordinate of entity position
     */
    public final void setY(double y) {
        yProperty().set(y);
    }

    /**
     * @return absolute position of entity
     */
    public final Point2D getPosition() {
        return new Point2D(getX(), getY());
    }

    /**
     * Set absolute position of entity to given point.
     *
     * @param x coordinate of entity position
     * @param y coordinate of entity position
     */
    public final void setPosition(double x, double y) {
        setX(x);
        setY(y);
    }

    /**
     * Set absolute position of entity to given point.
     *
     * @param position absolute position in game world
     */
    public final void setPosition(Point2D position) {
        setPosition(position.getX(), position.getY());
    }

    /**
     * Translate (move) entity by vector (x, y).
     *
     * @param x units
     * @param y units
     */
    public final void translate(double x, double y) {
        setX(getX() + x);
        setY(getY() + y);
    }

    /**
     * Translate (move) entity by vector.
     *
     * @param vector translate vector
     */
    public final void translate(Point2D vector) {
        translate(vector.getX(), vector.getY());
    }

    private DoubleProperty rotation = new SimpleDoubleProperty();

    /**
     *
     * @return rotation
     */
    public final DoubleProperty rotationProperty() {
        return rotation;
    }

    /**
     * Returns absolute angle of the entity rotation
     * in degrees.
     *
     * @return rotation angle
     */
    public final double getRotation() {
        return rotationProperty().get();
    }

    /**
     * Set absolute rotation of the entity view in
     * degrees.
     * Note: this doesn't affect hit boxes. For more accurate
     *
     * @param angle the new rotation angle
     */
    public final void setRotation(double angle) {
        rotationProperty().set(angle);
    }

    /**
     * Rotate entity view by given angle.
     * Note: this doesn't affect hit boxes. For more accurate
     *
     * @param byAngle rotation angle in degrees
     */
    public final void rotateBy(double byAngle) {
        setRotation(getRotation() + byAngle);
    }

    /**
     * Set absolute rotation of the entity view to angle
     * between vector and positive X axis.
     * This is useful for projectiles (bullets, arrows, etc)
     * which rotate depending on their current velocity.
     *
     * @param vector the rotation vector / velocity vector
     */
    public final void rotateToVector(Point2D vector) {
        double angle = Math.toDegrees(Math.atan2(vector.getY(), vector.getX()));
        setRotation(angle);
    }

    private BooleanProperty xFlipped = new SimpleBooleanProperty(false);
    private double xFlipLine = 0;

    /**
     * Line to flip around. E.g. an entity with texture 200x100 as scene view
     * with xFlipLine = 100 will be mirrored perfectly.
     *
     * @return vertical line at X point to use as pivot point for flip
     */
    public final double getXFlipLine() {
        return xFlipLine;
    }

    /**
     *
     * @return x flipped property
     */
    public final BooleanProperty xFlippedProperty() {
        return xFlipped;
    }

    /**
     *
     * @return true iff x axis is flipped
     */
    public final boolean isXFlipped() {
        return xFlippedProperty().get();
    }

    /**
     * Flip X axis of the entity. If set to true, the scene view
     * will be drawn from right to left. This also affects hit boxes
     *
     * @param b x flipped flag
     * @defaultValue false
     */
    public final void setXFlipped(boolean b) {
        xFlippedProperty().set(b);
    }

    /**
     * Flip X axis of the entity. If set to true, the scene view
     * will be drawn from right to left. This also affects hit boxes
     *
     * @param b x flipped flag
     * @param xFlipLine x flip line (pivot line)
     * @defaultValue false
     */
    public final void setXFlipped(boolean b, double xFlipLine) {
        this.xFlipLine = xFlipLine;
        xFlippedProperty().set(b);
    }


    /**
     * Contains all hit boxes (collision bounding boxes) for this entity.
     */
    private ObservableList<HitBox> hitBoxes = FXCollections.observableArrayList();

    public final ObservableList<HitBox> hitBoxesProperty() {
        return FXCollections.unmodifiableObservableList(hitBoxes);
    }

    /**
     * Add a hit (collision) bounding box.
     *
     * @param hitBox the bounding box
     */
    public final void addHitBox(HitBox hitBox) {
        hitBoxes.add(hitBox);
    }

    public final void removeHitBox(final String name) {
        hitBoxes.removeIf(new Predicate<HitBox>() {
            @Override
            public boolean test(HitBox h) {
                return h.getName().equals(name);
            }
        });
    }

    private boolean generateHitBoxesFromView = true;

    /**
     * Set to false if hit boxes have been added manually.
     * Otherwise, FXGL will attempt to generate another hit box
     * from the scene view.
     *
     * @param b flag
     * @defaultValue true
     */
    public final void setGenerateHitBoxesFromView(boolean b) {
        generateHitBoxesFromView = b;
    }


    /**
     * Checks for collision with another entity. Returns collision result
     * containing the first hit box that triggered collision.
     * If no collision - {@link CollisionResult#NO_COLLISION} will be returned.
     *
     * @param other entity to check collision against
     * @return collision result
     */
    public final CollisionResult checkCollision(GameEntity other) {
        for (HitBox box1 : hitBoxes) {
            Bounds b = isXFlipped() ? box1.translateXFlipped(getX(), getY(), getWidth()) : box1.translate(getX(), getY());
            for (HitBox box2 : other.hitBoxes) {
                Bounds b2 = other.isXFlipped()
                        ? box2.translateXFlipped(other.getX(), other.getY(), other.getWidth())
                        : box2.translate(other.getX(), other.getY());
                if (b.intersects(b2)) {
                    return new CollisionResult(box1, box2);
                }
            }
        }

        return CollisionResult.NO_COLLISION;
    }

    /**
     * @param other the other entity
     * @return true iff this entity is colliding with other based on
     * their hit boxes, in current frame
     */
    public final boolean isCollidingWith(GameEntity other) {
        return checkCollision(other) != CollisionResult.NO_COLLISION;
    }
}
