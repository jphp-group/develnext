package org.develnext.jphp.ext.game.support;

import javafx.animation.AnimationTimer;
import javafx.scene.Scene;
import javafx.scene.layout.Pane;

import java.util.ArrayList;
import java.util.List;

public class GameRoom {
    protected final Scene scene;
    protected final GamePhysics physics;
    protected List<GameObject> entities = new ArrayList<>();

    private AnimationTimer timer = new AnimationTimer() {
        @Override
        public void handle(long internalTime) {
            processUpdate(internalTime);
        }
    };

    public GameRoom(Scene scene) {
        this.scene = scene;
        this.physics = new GamePhysics(this);
    }

    public void addEntity(GameObject entity) {
        entities.add(entity);
        physics.createBody(entity);

        ((Pane)scene.getRoot()).getChildren().add(entity);
    }

    protected void processUpdate(long now) {
        physics.onUpdate(now);

        for (GameObject entity : entities) {
            entity.update(now);
        }
    }

    public void start() {
        timer.start();
    }

    public void pause() {
        timer.stop();
    }

    public void resume() {
        timer.start();
    }

    public double getAppHeight() {
        return scene.getHeight();
    }

    public double getAppWidth() {
        return scene.getWidth();
    }

    public GamePhysics getPhysics() {
        return physics;
    }
}
