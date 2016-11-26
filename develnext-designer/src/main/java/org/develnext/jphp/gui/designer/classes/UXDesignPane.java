package org.develnext.jphp.gui.designer.classes;

import javafx.application.Platform;
import javafx.beans.value.ChangeListener;
import javafx.beans.value.ObservableValue;
import javafx.collections.ListChangeListener;
import javafx.collections.ObservableList;
import javafx.event.EventHandler;
import javafx.geometry.Bounds;
import javafx.geometry.Point2D;
import javafx.scene.Cursor;
import javafx.scene.Node;
import javafx.scene.Scene;
import javafx.scene.input.MouseEvent;
import javafx.scene.layout.AnchorPane;
import javafx.scene.layout.Background;
import javafx.scene.layout.Pane;
import javafx.scene.transform.Scale;
import org.develnext.jphp.ext.javafx.classes.layout.UXAnchorPane;
import org.develnext.jphp.gui.designer.GuiDesignerExtension;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.*;
import php.runtime.env.Environment;
import php.runtime.invoke.Invoker;
import php.runtime.reflection.ClassEntity;

@NotWrapper
@Namespace(GuiDesignerExtension.NS)
public class UXDesignPane extends UXAnchorPane {
    protected boolean resizing = false;

    protected int borderWidth = 8;
    protected int snapSize = 8;
    protected String borderColor = "gray";

    protected double startWidth;
    protected double startHeight;
    protected Point2D startDragPoint = null;

    protected Invoker onResize = null;

    protected AnchorPane topBorders = new AnchorPane();
    private double zoom = 1.0;
    private Scale scale;

    public UXDesignPane(Environment env, AnchorPane wrappedObject) {
        super(env, wrappedObject);
    }

    public UXDesignPane(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Override
    public void __construct() {
        super.__construct();

        updateStyle();

        getWrappedObject().getChildren().add(topBorders);
        UXAnchorPane.setAnchor(topBorders, -borderWidth);

        //topBorders.setLayoutX(-borderWidth/2);
        //topBorders.setLayoutY(-borderWidth/2);

        scale = new Scale();
        getWrappedObject().getTransforms().add(scale);

        getChildren().addListener(new ListChangeListener() {
            @Override
            public void onChanged(Change c) {
                setZoom(zoom);
            }
        });

        getWrappedObject().getChildren().addListener((ListChangeListener<Node>) c -> {
            Platform.runLater(this::update);
        });

        getWrappedObject().setOnMouseExited(new EventHandler<MouseEvent>() {
            @Override
            public void handle(MouseEvent event) {
                if (!resizing) {
                    getWrappedObject().getScene().setCursor(Cursor.DEFAULT);
                }
            }
        });

        getWrappedObject().setOnMouseMoved(event -> {
            double x = event.getX();
            double y = event.getY();

            Scene scene = getWrappedObject().getScene();

            boolean blockWidth = getWrappedObject().getMaxWidth() == getWrappedObject().getPrefWidth() &&
                    getWrappedObject().getMinWidth() == getWrappedObject().getPrefWidth();

            boolean blockHeight = getWrappedObject().getMaxHeight() == getWrappedObject().getPrefHeight() &&
                    getWrappedObject().getMinHeight() == getWrappedObject().getPrefHeight();

            if ((!blockWidth && isHResize(x, y)) && (!blockHeight && isVResize(x, y))) {
                scene.setCursor(Cursor.SE_RESIZE);
            } else if (!blockWidth && isHResize(x, y)) {
                scene.setCursor(Cursor.H_RESIZE);
            } else if (!blockHeight && isVResize(x, y)) {
                scene.setCursor(Cursor.V_RESIZE);
            } else {
                scene.setCursor(Cursor.DEFAULT);
            }
        });

        getWrappedObject().setOnMousePressed(event -> {
            double x = event.getX();
            double y = event.getY();

            if (isHResize(x, y) && isVResize(x, y)) {
                startDragPoint = new Point2D(event.getScreenX(), event.getScreenY());
            } else if (isHResize(x, y)) {
                startDragPoint = new Point2D(event.getScreenX(), 0.0);
            } else if (isVResize(x, y)) {
                startDragPoint = new Point2D(0.0, event.getScreenY());
            } else {
                startDragPoint = null;
            }

            Pane node = (Pane) getWrappedObject().getChildren().get(0);

            if (startDragPoint != null) {
                startWidth = node.getPrefWidth();
                startHeight = node.getPrefHeight();

                update();
                event.consume();
            }
        });

        getWrappedObject().setOnMouseDragged(event -> {
            if (startDragPoint != null) {
                double hOffset = (event.getScreenX() - startDragPoint.getX()) / zoom;
                double vOffset = (event.getScreenY() - startDragPoint.getY()) / zoom;

                Pane node = (Pane) getWrappedObject().getChildren().get(0);

                if (startDragPoint.getX() > 0) {
                    double value = startWidth + hOffset;

                    if (snapSize > 1) {
                        value = Math.round(Math.round(value / snapSize) * snapSize);
                    }

                    node.setPrefWidth(value);
                }

                if (startDragPoint.getY() > 0) {
                    double value = startHeight + vOffset;

                    if (snapSize > 1) {
                        value = Math.round(Math.round(value / snapSize) * snapSize);
                    }

                    node.setPrefHeight(value);
                }

                resizing = true;

                if (onResize != null) {
                    onResize.callAny();
                }

                update();
                event.consume();
            }
        });

        getWrappedObject().addEventFilter(MouseEvent.MOUSE_RELEASED, new EventHandler<MouseEvent>() {
            @Override
            public void handle(MouseEvent event) {
                if (resizing) {
                    resizing = false;
                    update();
                    event.consume();
                }
            }
        });
    }

    protected boolean isHResize(double x, double y) {
        double width = getWidth();

        return x > width - borderWidth && x < width;
    }

    protected boolean isVResize(double x, double y) {
        double height = getHeight();

        return y > height - borderWidth && y < height;
    }

    @Setter
    public void setZoom(double zoom) {
        this.zoom = zoom;

        scale.setX(zoom);
        scale.setY(zoom);
    }

    @Getter
    public double getZoom() {
        return this.zoom;
    }

    @Getter
    public int getSnapSize() {
        return snapSize;
    }

    @Setter
    public void setSnapSize(int snapSize) {
        this.snapSize = snapSize;
    }

    @Getter
    public boolean getEditing() {
        return resizing;
    }

    @Getter
    public int getBorderWidth() {
        return borderWidth;
    }

    @Setter
    public void setBorderWidth(int borderWidth) {
        this.borderWidth = borderWidth;
        updateStyle();
    }

    @Getter
    public String getBorderColor() {
        return borderColor;
    }

    @Setter
    public void setBorderColor(String borderColor) {
        this.borderColor = borderColor;
        updateStyle();
    }

    @Signature
    public void onResize(@Reflection.Nullable Invoker onResize) {
        this.onResize = onResize;
    }

    @Signature
    public void update() {
        topBorders.toFront();
    }

    protected void updateStyle() {
        getWrappedObject().setStyle("-fx-border-color: " + borderColor + "; -fx-border-width: " + borderWidth + "px; -fx-border-radius: 4px;");
        topBorders.setStyle("-fx-border-color: " + borderColor + "; -fx-border-width: " + borderWidth + "px; -fx-border-radius: 4px;");
        topBorders.setMouseTransparent(true);
        topBorders.setBackground(Background.EMPTY);
        topBorders.setOpacity(0.5);
    }
}
