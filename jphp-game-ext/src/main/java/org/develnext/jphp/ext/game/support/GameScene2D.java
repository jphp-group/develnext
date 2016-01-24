package org.develnext.jphp.ext.game.support;

import javafx.animation.AnimationTimer;
import javafx.beans.property.DoubleProperty;
import javafx.beans.property.ObjectProperty;
import javafx.beans.property.SimpleDoubleProperty;
import javafx.beans.property.SimpleObjectProperty;
import org.dyn4j.dynamics.Capacity;
import org.dyn4j.dynamics.CollisionAdapter;
import org.dyn4j.dynamics.World;
import org.dyn4j.dynamics.contact.ContactConstraint;

import java.util.ArrayList;
import java.util.List;

public class GameScene2D {
    public interface ScrollHandler {
        void scrollTo(double x, double y);
    }

    protected World world;


    private static final float TIME_STEP = 1 / 60.0f;

    private AnimationTimer timer = new AnimationTimer() {
        @Override
        public void handle(long internalTime) {
            processUpdate(internalTime);
        }
    };

    protected List<GameEntity2D> entities = new ArrayList<>();

    protected Vec2d gravity = new Vec2d(0, 0);

    protected DoubleProperty width = new SimpleDoubleProperty(0);
    protected DoubleProperty height = new SimpleDoubleProperty(0);

    protected ObjectProperty<GameEntity2D> observedObject = new SimpleObjectProperty<>(null);
    protected ObjectProperty<ScrollHandler> scrollHandler = new SimpleObjectProperty<>(null);

    public GameScene2D() {
        world = new World(Capacity.DEFAULT_CAPACITY);

        world.addListener(new CollisionAdapter() {
            @Override
            public boolean collision(ContactConstraint contactConstraint) {
                GameEntity2D e1 = (GameEntity2D) contactConstraint.getBody1().getUserData();
                GameEntity2D e2 = (GameEntity2D) contactConstraint.getBody2().getUserData();

                boolean consume1 = e1.triggerCollision(e2, contactConstraint);
                boolean consume2 = e2.triggerCollision(e1, contactConstraint);

                return consume1 || consume2;
            }
        });
    }

    public void play() {
        timer.start();
    }

    public void pause() {
        timer.stop();
    }

    public ObjectProperty<GameEntity2D> observedObjectProperty() {
        return observedObject;
    }

    public DoubleProperty widthProperty() {
        return width;
    }

    public DoubleProperty heightProperty() {
        return height;
    }

    public GameEntity2D getObservedObject() {
        return observedObject.get();
    }

    public void setObservedObject(GameEntity2D observedObject) {
        this.observedObject.set(observedObject);
    }

    public ScrollHandler getScrollHandler() {
        return scrollHandler.get();
    }

    public ObjectProperty<ScrollHandler> scrollHandlerProperty() {
        return scrollHandler;
    }

    public void setScrollHandler(ScrollHandler scrollHandler) {
        this.scrollHandler.set(scrollHandler);
    }

    public void setWidth(double v) {
        widthProperty().set(v);
    }

    public void setHeight(double v) {
        heightProperty().set(v);
    }

    public double getWidth() {
        return widthProperty().get();
    }

    public double getHeight() {
        return heightProperty().get();
    }

    public static float toMeters(double pixels) {
        return (float)pixels * 0.005f;
    }

    public static float toPixels(double meters) {
        return (float)meters * 20f;
    }

    private long previousTime = 0;

    private void processUpdate(long internalTime) {
        if (previousTime == 0) {
            previousTime = internalTime;
            return;
        }

        long delta = internalTime - previousTime;

        world.update(TIME_STEP);

        for (GameEntity2D entity : entities) {
            entity.update(TIME_STEP, this);

            GameEntity2D entity2D = observedObject.get();

            if (entity2D != null) {
                ScrollHandler scrollHandler = this.scrollHandler.get();

                if (scrollHandler != null) {
                    scrollHandler.scrollTo(entity.getCenterX(), entity.getCenterY());
                }
            }
        }
    }

    public void addEntity(GameEntity2D entity) {
        if (entity.getScene() == null) {
            entity.scene = this;
            entities.add(entity);
            world.addBody(entity.body);
        }
    }

    public void removeEntity(GameEntity2D entity) {
        entities.remove(entity);
        world.removeBody(entity.body);
        entity.scene = null;
    }

    public void clear() {
        world.removeAllBodies();

        for (GameEntity2D entity : entities) {
            entity.scene = null;
        }

        entities.clear();
    }

    public Vec2d getGravity() {
        return gravity;
    }

    public void setGravity(Vec2d gravity) {
        this.gravity = gravity;
    }

    public double getGravityX() {
        return gravity.x;
    }

    public void setGravityX(double x) {
        gravity.x = x;
    }

    public double getGravityY() {
        return gravity.y;
    }

    public void setGravityY(double y) {
        gravity.y = y;
    }
}
