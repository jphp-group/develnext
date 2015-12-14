package org.develnext.jphp.ext.javafx.support.tray.models;

import javafx.beans.property.SimpleDoubleProperty;
import javafx.geometry.Rectangle2D;
import javafx.scene.layout.AnchorPane;
import javafx.stage.Screen;
import javafx.stage.Stage;
import javafx.stage.StageStyle;

public class CustomStage extends Stage {

    private final Location bottomRight;
    private final Location bottomLeft;
    private final Location topLeft;
    private final Location topRight;

    private Location location;

    public CustomStage(AnchorPane ap, StageStyle style) {
        initStyle(style);

        setSize(ap.getPrefWidth(), ap.getPrefHeight());

        Rectangle2D screenBounds = Screen.getPrimary().getVisualBounds();
        double x = screenBounds.getMinX() + screenBounds.getWidth() - ap.getPrefWidth() - 2;
        double y = screenBounds.getMinY() + screenBounds.getHeight() - ap.getPrefHeight() - 2;

        bottomRight = new Location(x, y);
        bottomLeft = new Location(2, y);
        topLeft = new Location(2, 2);
        topRight = new Location(x, 2);
    }

    public Location getBottomRight(int viewIndex) {
        if (viewIndex > 0) {
            return new Location(bottomRight.getX(), bottomRight.getY() - (viewIndex * (getHeight() + 2)));
        }

        return bottomRight;
    }

    public Location getBottomLeft(int viewIndex) {
        if (viewIndex > 0) {
            return new Location(bottomLeft.getX(), bottomLeft.getY() - (viewIndex * (getHeight() + 2)));
        }

        return bottomLeft;
    }

    public Location getTopLeft(int viewIndex) {
        if (viewIndex > 0) {
            return new Location(topLeft.getX(), topLeft.getY() - (viewIndex * (getHeight() + 2)));
        }

        return topLeft;
    }

    public Location getTopRight(int viewIndex) {
        if (viewIndex > 0) {
            return new Location(topRight.getX(), topRight.getY() + (viewIndex * (getHeight() + 2)));
        }

        return topRight;
    }

    public void setSize(double width, double height) {
        setWidth(width);
        setHeight(height);
    }

    public Location getOffScreenBounds() {
        Location loc = getBottomRight(0);

        return new Location(loc.getX() + this.getWidth(), loc.getY());
    }

    public void setLocation(Location loc) {
        setX(loc.getX());
        setY(loc.getY());

        location = loc;
    }

    public Location getLocation() {
        return location;
    }

    private SimpleDoubleProperty xLocationProperty = new SimpleDoubleProperty() {
        @Override
        public void set(double newValue) {
            setX(newValue);
        }

        @Override
        public double get() {
            return getX();
        }
    };

    public SimpleDoubleProperty xLocationProperty() {
        return xLocationProperty;
    }

    private SimpleDoubleProperty yLocationProperty = new SimpleDoubleProperty() {
        @Override
        public void set(double newValue) {
            setY(newValue);
        }

        @Override
        public double get() {
            return getY();
        }
    };

    public SimpleDoubleProperty yLocationProperty() {
        return yLocationProperty;
    }
}
