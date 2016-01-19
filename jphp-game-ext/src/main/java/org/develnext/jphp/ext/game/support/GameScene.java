package org.develnext.jphp.ext.game.support;

import javafx.animation.AnimationTimer;
import javafx.beans.property.DoubleProperty;
import javafx.scene.layout.AnchorPane;

import java.util.ArrayList;
import java.util.List;

public class GameScene {
    protected AnchorPane content;
    protected final GameWorld world;
    protected List<GameEntity> entities = new ArrayList<>();

    private AnimationTimer timer = new AnimationTimer() {
        @Override
        public void handle(long internalTime) {
            processUpdate(internalTime);
        }
    };

    private List<Control> controls = new ArrayList<>();

    private boolean physicsEnabled = true;

    public GameScene(AnchorPane layout) {
        this.world = new GameWorld(this);

        content = layout;
    }

    public void addEntity(GameEntity entity) {
        entity.setGameScene(this);
        world.createBody(entity);
    }

    public void removeEntity(GameEntity entity) {
        world.destroyBody(entity);
        entity.setGameScene(null);
    }

    public DoubleProperty sceneWidthProperty() {
        return content.prefWidthProperty();
    }

    public DoubleProperty sceneHeightProperty() {
        return content.prefHeightProperty();
    }

    public double getSceneWidth() {
        return content.getPrefWidth();
    }

    public double getSceneHeight() {
        return content.getPrefHeight();
    }

    public double[] getSceneSize() {
        return new double[] { getSceneWidth(), getSceneHeight() };
    }

    public void setSceneWidth(double value) {
        content.setPrefWidth(value);
    }

    public void setSceneHeight(double value) {
        content.setPrefHeight(value);
    }

    public void setSceneSize(double[] wh) {
        if (wh != null && wh.length >= 2) {
            setSceneWidth(wh[0]);
            setSceneHeight(wh[1]);
        }
    }

    public boolean isPhysicsEnabled() {
        return physicsEnabled;
    }

    public void setPhysicsEnabled(boolean physicsEnabled) {
        this.physicsEnabled = physicsEnabled;
    }

    protected void processUpdate(long now) {
        if (physicsEnabled) {
            world.onUpdate(now);
        }

        for (GameEntity entity : entities) {
            entity.update(now);
        }

        for (Control control : controls) {
            control.onUpdate(null, now);
        }
    }

    public void play() {
        timer.start();
    }

    public void pause() {
        timer.stop();
    }

    public double getAppHeight() {
        return content.getHeight();
    }

    public double getAppWidth() {
        return content.getWidth();
    }

    public GameWorld getWorld() {
        return world;
    }

    public AnchorPane getContent() {
        return content;
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

    public final void addControl(Control control) {
        controls.add(control);
    }

    public final <T extends Control> void removeControl(Class<T> controlType) {
        T control = getControl(controlType);

        if (control != null) {
            removeControl(control);
        }
    }

    public final void removeControl(Control control) {
        controls.remove(control);
    }
}
