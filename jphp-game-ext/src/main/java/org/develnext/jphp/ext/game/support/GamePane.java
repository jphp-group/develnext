package org.develnext.jphp.ext.game.support;

import javafx.geometry.Bounds;
import javafx.scene.Node;
import javafx.scene.control.ScrollPane;

public class GamePane extends ScrollPane {
    protected GameScene scene = null;
    protected Node watchingNode = null;

    public GamePane() {
        setVbarPolicy(ScrollBarPolicy.NEVER);
        setHbarPolicy(ScrollBarPolicy.NEVER);
        setFocusTraversable(false);
    }

    public void setGameScene(GameScene scene) {
        if (this.scene != null) {
            this.scene.removeControl(WatchingControl.class);
            this.scene.pause();
        }

        this.scene = scene;

        if (scene != null) {
            scene.addControl(new WatchingControl());
            setContent(scene.content);
            scene.play();
        }
    }

    public GameScene getGameScene() {
        return scene;
    }

    public Node getWatchingNode() {
        return watchingNode;
    }

    public void setWatchingNode(Node watchingNode) {
        this.watchingNode = watchingNode;
    }

    public void scrollTo(Node node) {
        Bounds bounds = node.getLayoutBounds();

        double x = Math.round(node.getLayoutX() + bounds.getWidth() / 2);
        double y = Math.round(node.getLayoutY() + bounds.getHeight() / 2);

        setVvalue(y);
        setHvalue(x);
    }

    class WatchingControl implements Control {
        @Override
        public void onUpdate(GameObject entity, long now) {
            if (watchingNode != null) {
                scrollTo(watchingNode);
            }
        }
    }
}
