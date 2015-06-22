package org.develnext.jphp.gui.designer.classes;

import javafx.event.EventHandler;
import javafx.geometry.Point2D;
import javafx.scene.Cursor;
import javafx.scene.Scene;
import javafx.scene.input.MouseEvent;
import javafx.scene.layout.AnchorPane;
import javafx.scene.layout.Pane;
import org.develnext.jphp.ext.javafx.classes.layout.UXAnchorPane;
import org.develnext.jphp.gui.designer.GuiDesignerExtension;
import php.runtime.annotation.Reflection.Getter;
import php.runtime.annotation.Reflection.Namespace;
import php.runtime.annotation.Reflection.NotWrapper;
import php.runtime.annotation.Reflection.Setter;
import php.runtime.env.Environment;
import php.runtime.reflection.ClassEntity;

@NotWrapper
@Namespace(GuiDesignerExtension.NS)
public class UXDesignPane extends UXAnchorPane {
    protected boolean resizing = false;

    protected int borderWidth = 8;
    protected String borderColor = "gray";

    protected double startWidth;
    protected double startHeight;
    protected Point2D startDragPoint = null;

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

        getWrappedObject().setOnMouseExited(new EventHandler<MouseEvent>() {
            @Override
            public void handle(MouseEvent event) {
                //getWrappedObject().getScene().setCursor(Cursor.DEFAULT);
            }
        });

        getWrappedObject().setOnMouseMoved(new EventHandler<MouseEvent>() {
            @Override
            public void handle(MouseEvent event) {
                double x = event.getX();
                double y = event.getY();

                Scene scene = getWrappedObject().getScene();

                if (isHResize(x, y) && isVResize(x, y)) {
                    scene.setCursor(Cursor.SE_RESIZE);
                } else if (isHResize(x, y)) {
                    scene.setCursor(Cursor.H_RESIZE);
                } else if (isVResize(x, y)) {
                    scene.setCursor(Cursor.V_RESIZE);
                } else {
                    scene.setCursor(Cursor.DEFAULT);
                }
            }
        });

        getWrappedObject().setOnMousePressed(new EventHandler<MouseEvent>() {
            @Override
            public void handle(MouseEvent event) {
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

                    event.consume();
                }
            }
        });

        getWrappedObject().setOnMouseDragged(new EventHandler<MouseEvent>() {
            @Override
            public void handle(MouseEvent event) {
                if (startDragPoint != null) {
                    double hOffset = event.getScreenX() - startDragPoint.getX();
                    double vOffset = event.getScreenY() - startDragPoint.getY();

                    Pane node = (Pane) getWrappedObject().getChildren().get(0);

                    if (startDragPoint.getX() > 0) {
                        node.setPrefWidth(startWidth + hOffset);
                    }

                    if (startDragPoint.getY() > 0) {
                        node.setPrefHeight(startHeight + vOffset);
                    }

                    resizing = true;

                    event.consume();
                }
            }
        });

        getWrappedObject().setOnMouseReleased(new EventHandler<MouseEvent>() {
            @Override
            public void handle(MouseEvent event) {
                resizing = false;
            }
        });
    }

    protected boolean isHResize(double x, double y) {
        double width = getWidth();

        return  x > width - borderWidth && x < width;
    }

    protected boolean isVResize(double x, double y) {
        double height = getHeight();

        return y > height - borderWidth && y < height;
    }

    @Getter
    public boolean isEditing() {
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

    protected void updateStyle() {
        getWrappedObject().setStyle("-fx-border-color: " + borderColor + "; -fx-border-width: " + borderWidth + "px;");
    }
}
