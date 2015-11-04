package org.develnext.jphp.ext.game.support;

import javafx.event.ActionEvent;
import javafx.event.Event;
import javafx.event.EventHandler;
import javafx.geometry.Point2D;
import javafx.scene.canvas.Canvas;
import javafx.scene.canvas.GraphicsContext;
import org.jbox2d.common.Vec2;
import org.jbox2d.dynamics.*;

import java.util.ArrayList;
import java.util.List;

public class GameObject extends Canvas {
    private boolean collidable = false;

    /*package-private*/ FixtureDef fixtureDef = new FixtureDef();
    /*package-private*/ BodyDef bodyDef = new BodyDef();

    /*package-private*/ Body body;
    /*package-private*/ Fixture fixture;

    private boolean raycastIgnored = false;

    private List<Control> controls = new ArrayList<>();

    protected Sprite sprite;
    protected GameScene gameScene;

    protected EventHandler<Event> onUpdate;
    protected EventHandler<Event> onCreate;
    protected EventHandler<Event> onDestroy;

    public GameObject(Sprite sprite) {
        setSprite(sprite);
    }

    public void setSprite(Sprite sprite) {
        this.sprite = sprite == null ? null : new Sprite(sprite);

        if (sprite == null) {
            GraphicsContext gc = getGraphicsContext2D();
            gc.clearRect(0, 0, getWidth(), getHeight());
            setHeight(0);
            setWidth(0);
        } else {
            setWidth(this.sprite.getFrameWidth());
            setHeight(this.sprite.getFrameHeight());
            this.sprite.drawNext(this);
        }
    }

    public GameScene getGameScene() {
        return gameScene;
    }

    public void setGameScene(GameScene gameScene) {
        this.gameScene = gameScene;
    }

    public EventHandler<Event> getOnCreate() {
        return onCreate;
    }

    public void setOnCreate(EventHandler<Event> onCreate) {
        this.onCreate = onCreate;
    }

    public EventHandler<Event> getOnUpdate() {
        return onUpdate;
    }

    public void setOnUpdate(EventHandler<Event> doUpdate) {
        this.onUpdate = doUpdate;
    }

    public EventHandler<Event> getOnDestroy() {
        return onDestroy;
    }

    public void setOnDestroy(EventHandler<Event> onDestroy) {
        this.onDestroy = onDestroy;
    }

    public final void update(final long now) {
        if (onUpdate != null) {
            onUpdate.handle(new ActionEvent(this, this));
        }

        for (Control control : controls) {
            control.onUpdate(GameObject.this, now);
        }

        onUpdate(now);
    }

    protected void onUpdate(long now) {
        if (sprite != null) {
            sprite.drawByTime(this, now);
        }
    }

    public final GameObject setCollidable(boolean b) {
        collidable = b;
        return this;
    }

    public final Point2D getCenter() {
        return getPosition().add(getWidth() / 2, getHeight() / 2);
    }

    public final Point2D getPosition() {
        return new Point2D(getTranslateX(), getTranslateY());
    }

    public GameObject setFixtureDef(FixtureDef def) {
        fixtureDef = def;
        return this;
    }

    public GameObject setBodyDef(BodyDef def) {
        bodyDef = def;
        return this;
    }

    public void setBodyType(BodyType type) {
        bodyDef.type = type;
    }

    public BodyType getBodyType() {
        return bodyDef.type;
    }

    public GameObject setLinearVelocity(Point2D vector) {
        return setBodyLinearVelocity(GamePhysics.toVector(vector).mulLocal(60));
    }

    public GameObject applyForce(Point2D vector) {
        return applyBodyForceToCenter(GamePhysics.toVector(vector).mulLocal(60));
    }

    public GameObject applyForce(double x, double y) {
        return applyForce(new Point2D(x, y));
    }

    public GameObject setLinearVelocity(double x, double y) {
        return setLinearVelocity(new Point2D(x, y));
    }

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

    public Point2D getLinearVelocity() {
        if (body == null)
            throw new IllegalStateException("PhysicsEntity has not been added to the world yet! Call addEntities(entity) first");

        return GamePhysics.toVector(body.getLinearVelocity().mul(1/60f));
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

    public Sprite getSprite() {
        return sprite;
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
}
