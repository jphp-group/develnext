package org.develnext.jphp.ext.game.support;

import javafx.animation.AnimationTimer;
import javafx.beans.property.DoubleProperty;
import javafx.beans.property.SimpleDoubleProperty;

import java.util.ArrayList;
import java.util.List;

public class GameScene2D {
    private static final float TIME_STEP = 1 / 60.0f;

    private AnimationTimer timer = new AnimationTimer() {
        @Override
        public void handle(long internalTime) {
            processUpdate(internalTime);
        }
    };

    protected List<GameEntity2D> entities = new ArrayList<>();

    DoubleProperty width = new SimpleDoubleProperty(0);
    DoubleProperty height = new SimpleDoubleProperty(0);

    public void play() {
        timer.start();
    }

    public void pause() {
        timer.stop();
    }

    public DoubleProperty widthProperty() {
        return width;
    }

    public DoubleProperty heightProperty() {
        return height;
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

        for (GameEntity2D entity : entities) {
            entity.update(TIME_STEP);
        }
    }

    public void addEntity(GameEntity2D entity) {
        entities.add(entity);
    }

    public void removeEntity(GameEntity2D entity) {
        entities.remove(entity);
    }
}
