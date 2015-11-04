package org.develnext.jphp.ext.game.support;

import javafx.animation.AnimationTimer;
import javafx.beans.property.DoubleProperty;
import javafx.collections.ListChangeListener;
import javafx.event.ActionEvent;
import javafx.scene.Node;
import javafx.scene.layout.AnchorPane;

import java.util.ArrayList;
import java.util.List;

public class GameScene {
    protected GamePane pane;
    protected AnchorPane content;
    protected final GamePhysics physics;
    protected List<GameObject> entities = new ArrayList<>();

    private AnimationTimer timer = new AnimationTimer() {
        @Override
        public void handle(long internalTime) {
            processUpdate(internalTime);
        }
    };

    private List<Control> controls = new ArrayList<>();

    private boolean physicsEnabled = true;

    public GameScene() {
        this.physics = new GamePhysics(this);

        content = new AnchorPane();
        content.setPrefSize(800, 600);

        content.getChildren().addListener(new ListChangeListener<Node>() {
            @Override
            public void onChanged(Change<? extends Node> c) {
                if (c.wasAdded()) {
                    for (int i = c.getFrom(); i < c.getTo(); i++) {
                        Node node = c.getList().get(i);

                        if (node instanceof GameObject) {
                            GameScene.this.addEntity((GameObject) node);
                        }
                    }
                } else if (c.wasRemoved()) {
                    for (Node node : c.getRemoved()) {
                        if (node instanceof GameObject) {
                            removeEntity((GameObject) node);
                        }
                    }
                }
            }
        });
    }

    void addEntity(GameObject entity) {
        entity.setGameScene(this);
        physics.createBody(entity);

        if (entity.getOnCreate() != null) {
            entity.getOnCreate().handle(new ActionEvent(entity, entity));
        }
    }

    void removeEntity(GameObject entity) {
        if (entity.getOnDestroy() != null) {
            entity.getOnDestroy().handle(new ActionEvent(entity, entity));
        }

        physics.destroyBody(entity);
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
            physics.onUpdate(now);
        }

        for (GameObject entity : entities) {
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

    public GamePhysics getPhysics() {
        return physics;
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
