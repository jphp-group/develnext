package org.develnext.jphp.ext.game.support;

import javafx.beans.property.DoubleProperty;
import javafx.beans.property.SimpleDoubleProperty;
import javafx.geometry.Bounds;
import javafx.scene.Node;

public class GameEntity2D {
    public enum BodyType { STATIC, DYNAMIC, KINEMATIC }

    private static final float TIME = 1 / 60.0f;

    protected BodyType bodyType = BodyType.STATIC;

    protected double mass = 1;
    protected Vec2d velocity = new Vec2d(0, 0);
    protected Vec2d gravity = null;

    private final String entityType;
    private final Node node;

    protected DoubleProperty x = new SimpleDoubleProperty(0);
    protected DoubleProperty y = new SimpleDoubleProperty(0);

    GameScene2D scene;

    public GameEntity2D(String entityType, Node node) {
        this.entityType = entityType;
        this.node = node;

        setX(node.getLayoutX());
        setY(node.getLayoutY());

        //node.layoutXProperty().bindBidirectional(x);
        //node.layoutYProperty().bindBidirectional(y);
    }

    public DoubleProperty xProperty() {
        return x;
    }

    public DoubleProperty yProperty() {
        return y;
    }

    public void setX(double v) {
        xProperty().set(v);
    }

    public void setY(double y) {
        yProperty().set(y);
    }

    public double getX() {
        return xProperty().get();
    }

    public double getY() {
        return yProperty().get();
    }

    public Vec2d getVelocity() {
        return velocity;
    }

    public void setVelocity(Vec2d velocity) {
        this.velocity = velocity == null ? new Vec2d(0, 0) : velocity;
    }

    public double getHorizontalVelocity() {
        return velocity.x;
    }

    public double getVerticalVelocity() {
        return velocity.y;
    }

    public void setHorizontalVelocity(double value) {
        this.velocity = new Vec2d(value, velocity.y);
    }

    public void setVerticalVelocity(double value) {
        this.velocity = new Vec2d(velocity.x, value);
    }

    public String getEntityType() {
        return entityType;
    }

    public Node getNode() {
        return node;
    }

    public double getWidth() {
        Bounds bounds = node.getBoundsInParent();
        return bounds.getWidth();
    }

    public double getHeight() {
        Bounds bounds = node.getBoundsInParent();
        return bounds.getHeight();
    }

    void update(float dt, GameScene2D scene) {
        switch (bodyType) {
            case DYNAMIC:
            case KINEMATIC:
                Vec2d gravity = this.gravity;

                if (gravity == null) {
                    gravity = scene.gravity;
                }

                if (gravity != null) {
                    velocity.x += gravity.x * dt;
                    velocity.y += gravity.y * dt;
                }

                if (velocity.x > 0.00001 || velocity.x < -00000.1) {
                    x.set(x.get() + GameScene2D.toPixels(velocity.x * dt));
                }

                if (velocity.y > 0.00001 || velocity.y < -00000.1) {
                    y.set(y.get() + GameScene2D.toPixels(velocity.y * dt));
                }

                break;
        }

        node.setLayoutX(x.get());
        node.setLayoutY(y.get());
    }

    public GameScene2D getScene() {
        return scene;
    }

    public BodyType getBodyType() {
        return bodyType;
    }

    public void setBodyType(BodyType bodyType) {
        this.bodyType = bodyType;
    }

    public Vec2d getGravity() {
        return gravity;
    }

    public void setGravity(Vec2d gravity) {
        this.gravity = gravity;
    }
}
