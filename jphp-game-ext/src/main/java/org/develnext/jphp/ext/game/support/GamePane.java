package org.develnext.jphp.ext.game.support;

import javafx.beans.property.BooleanProperty;
import javafx.beans.property.DoubleProperty;
import javafx.beans.property.SimpleBooleanProperty;
import javafx.beans.property.SimpleDoubleProperty;
import javafx.beans.value.ChangeListener;
import javafx.beans.value.ObservableValue;
import javafx.geometry.Insets;
import javafx.scene.Node;
import javafx.scene.control.ScrollPane;
import javafx.scene.layout.AnchorPane;
import javafx.scene.layout.Background;
import javafx.scene.layout.BackgroundFill;
import javafx.scene.layout.CornerRadii;
import javafx.scene.paint.Color;
import javafx.scene.paint.Paint;

public class GamePane extends ScrollPane {
    protected GameScene2D scene = null;
    protected AnchorPane area;

    protected BooleanProperty autoSizeProperty = new SimpleBooleanProperty(false);
    protected DoubleProperty areaWidthProperty = new SimpleDoubleProperty();
    protected DoubleProperty areaHeightProperty = new SimpleDoubleProperty();

    public GamePane() {
        setVbarPolicy(ScrollBarPolicy.NEVER);
        setHbarPolicy(ScrollBarPolicy.NEVER);
        setFocusTraversable(false);

        contentProperty().addListener(new ChangeListener<Node>() {
            @Override
            public void changed(ObservableValue<? extends Node> observable, Node oldValue, Node newValue) {
                if (newValue == null || newValue instanceof AnchorPane) {
                    area = (AnchorPane) newValue;
                }
            }
        });

        setContent(new AnchorPane());

        setAreaWidth(800);
        setAreaHeight(600);
        setAreaBackgroundColor(Color.WHITE);

        getStyleClass().add("without-focus");

        widthProperty().addListener(new ChangeListener<Number>() {
            @Override
            public void changed(ObservableValue<? extends Number> observable, Number oldValue, Number newValue) {
                if (autoSizeProperty.get()) {
                    area.setPrefWidth(newValue.doubleValue());
                }
            }
        });

        heightProperty().addListener(new ChangeListener<Number>() {
            @Override
            public void changed(ObservableValue<? extends Number> observable, Number oldValue, Number newValue) {
                if (autoSizeProperty.get()) {
                    area.setPrefHeight(newValue.doubleValue());
                }
            }
        });

        autoSizeProperty().addListener(new ChangeListener<Boolean>() {
            @Override
            public void changed(ObservableValue<? extends Boolean> observable, Boolean oldValue, Boolean newValue) {
                if (newValue) {
                    area.setPrefWidth(getWidth());
                    area.setPrefHeight(getHeight());
                } else {
                    area.setPrefWidth(areaWidthProperty.get());
                    area.setPrefHeight(areaHeightProperty.get());
                }
            }
        });
    }

    public void loadArea(AnchorPane area) {
        if (!getAutoSize()) {
            setAreaWidth(area.getWidth());
            setAreaHeight(area.getHeight());
        } else {
            area.setPrefWidth(getWidth());
            area.setPrefHeight(getHeight());
        }

        setContent(area);
    }

    public boolean getAutoSize() {
        return autoSizeProperty.get();
    }

    public BooleanProperty autoSizeProperty() {
        return autoSizeProperty;
    }

    public void setAutoSize(boolean autoSizeProperty) {
        this.autoSizeProperty.set(autoSizeProperty);
    }

    public void setAreaBackgroundColor(Color color) {
        if (color == null) {
            area.setBackground(null);
        } else {
            area.setBackground(new Background(new BackgroundFill(color, CornerRadii.EMPTY, Insets.EMPTY)));
        }
    }

    public Color getAreaBackgroundColor() {
        Background background = area.getBackground();

        if (background != null && background.getFills().size() > 0) {
            Paint fill = background.getFills().get(0).getFill();
            if (fill instanceof Color) {
                return (Color) fill;
            }
        }

        return null;
    }

    public void setAreaWidth(double value) {
        area.setPrefWidth(value);

        if (!autoSizeProperty.get()) {
            areaWidthProperty.setValue(value);
        }
    }

    public void setAreaHeight(double value) {
        areaHeightProperty.set(value);

        if (!autoSizeProperty.get()) {
            area.setPrefHeight(value);
        }
    }

    public double getAreaWidth() {
        return areaWidthProperty.get();
    }

    public double getAreaHeight() {
        return areaHeightProperty.get();
    }

    public void setAreaSize(double[] value) {
        if (value.length == 2) {
            setAreaWidth(value[0]);
            setAreaHeight(value[1]);
        }
    }

    public double[] getAreaSize() {
        return new double[] { getAreaWidth(), getAreaHeight() };
    }

    public void setGameScene(GameScene2D scene) {
        if (this.scene != null) {
            this.scene.setScrollHandler(null);
        }

        this.scene = scene;

        if (scene != null) {
            this.scene.setScrollHandler(new GameScene2D.ScrollHandler() {
                @Override
                public void scrollTo(double x, double y) {
                    setVvalue(y);
                    setHvalue(x);
                }
            });
        }
    }

    public GameScene2D getGameScene() {
        return scene;
    }

    public DoubleProperty areaWidthPropertyProperty() {
        return areaWidthProperty;
    }

    public DoubleProperty areaHeightPropertyProperty() {
        return areaHeightProperty;
    }
}
