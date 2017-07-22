package org.develnext.jphp.gui.designer.classes;

import javafx.animation.AnimationTimer;
import javafx.application.Platform;
import javafx.beans.value.ChangeListener;
import javafx.beans.value.ObservableValue;
import javafx.collections.ObservableList;
import javafx.event.ActionEvent;
import javafx.event.Event;
import javafx.event.EventHandler;
import javafx.geometry.*;
import javafx.geometry.Insets;
import javafx.scene.*;
import javafx.scene.Cursor;
import javafx.scene.canvas.Canvas;
import javafx.scene.canvas.GraphicsContext;
import javafx.scene.control.*;
import javafx.scene.control.Label;
import javafx.scene.control.MenuItem;
import javafx.scene.control.ScrollPane;
import javafx.scene.effect.BlendMode;
import javafx.scene.effect.Effect;
import javafx.scene.image.*;
import javafx.scene.image.Image;
import javafx.scene.input.*;
import javafx.scene.layout.*;
import javafx.scene.media.MediaView;
import javafx.scene.paint.Color;
import javafx.scene.paint.Paint;
import javafx.scene.shape.*;
import javafx.scene.shape.Polygon;
import javafx.scene.shape.Rectangle;
import javafx.scene.text.Text;
import javafx.scene.web.WebView;
import javafx.stage.Stage;
import javafx.stage.StageStyle;
import org.develnext.jphp.ext.javafx.classes.UXImage;
import org.develnext.jphp.ext.javafx.classes.layout.UXPanel;
import org.develnext.jphp.ext.javafx.classes.shape.UXPolygon;
import org.develnext.jphp.ext.javafx.support.control.*;
import org.develnext.jphp.gui.designer.GuiDesignerExtension;
import php.runtime.annotation.Reflection.*;
import php.runtime.env.Environment;
import php.runtime.invoke.Invoker;
import php.runtime.lang.BaseObject;
import php.runtime.reflection.ClassEntity;

import java.awt.*;
import java.util.*;
import java.util.List;

@Namespace(GuiDesignerExtension.NS)
public class UXDesigner extends BaseObject {
    public static final String SYSTEM_ELEMENT_CSS_CLASS = "x-system-designer-element";
    public static final int AREA_BLOCK_SIZE = 1024;

    public static final Image doubleArrowLeftIcon = (new Image(UXDesigner.class.getResourceAsStream("/org/develnext/jphp/gui/designer/double_arrow_left.png")));
    public static final Image doubleArrowRightIcon = (new Image(UXDesigner.class.getResourceAsStream("/org/develnext/jphp/gui/designer/double_arrow_right.png")));

    public enum SnapType {DOTS, GRID, HIDDEN}

    private Pane area;

    private Point2D startPoint;

    protected SnapType snapType = SnapType.DOTS;
    protected int snapSizeX = 8;
    protected int snapSizeY = 8;
    protected boolean snapEnabled = true;
    protected boolean helpersEnabled = true;
    protected boolean selectionEnabled = true;

    protected boolean dragged = false;
    protected boolean resizing = false;

    private AnchorPane dots;

    private Stage selectionRectangle = null;
    private Point2D selectionRectanglePoint = null;

    private Node picked = null;
    private Map<Node, Item> nodes = new LinkedHashMap<>();
    private Map<Node, Selection> selections = new LinkedHashMap<>();

    protected Invoker onAreaMouseDown;
    protected Invoker onAreaMouseUp;
    protected Invoker onNodeClick;
    protected Invoker onNodePick;
    protected Invoker onChanged;

    protected ContextMenu contextMenu;

    protected boolean tmpLock = false;

    protected boolean disabled = false;

    protected double zoom = 1.0;

    public UXDesigner(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    public static Color getContrastColor(Color color) {
        double y = (299 * color.getRed() + 587 * color.getGreen() + 114 * color.getBlue()) / 1000;
        return y >= 128 ? Color.BLACK.brighter() : Color.WHITE.darker();
    }

    protected void updateHelpers() {
        if (dots != null) {
            area.getChildren().removeAll(dots);
        }

        if (snapSizeX > 2 && snapSizeY > 2 && snapType != SnapType.HIDDEN) {
            double width = area.getWidth();
            double height = area.getHeight();

            dots = new AnchorPane();
            dots.getStyleClass().add(SYSTEM_ELEMENT_CSS_CLASS);
            dots.resize(width, height);
            dots.setMouseTransparent(true);

            int widthBlocks = (int) Math.ceil(width / AREA_BLOCK_SIZE);
            int heightBlocks = (int) Math.ceil(height / AREA_BLOCK_SIZE);

            WritableImage snapshot = null;

            for (int I = 0; I < widthBlocks; I++) {
                for (int J = 0; J < heightBlocks; J++) {
                    if (snapshot == null) {
                        Canvas canvas = new Canvas(AREA_BLOCK_SIZE, AREA_BLOCK_SIZE);
                        canvas.getStyleClass().add(SYSTEM_ELEMENT_CSS_CLASS);

                        if (J == heightBlocks - 1) {
                            canvas.setHeight(height % AREA_BLOCK_SIZE);

                            if (canvas.getHeight() <= 0) canvas.setHeight(AREA_BLOCK_SIZE);
                        }

                        if (I == widthBlocks - 1) {
                            canvas.setWidth(width % AREA_BLOCK_SIZE);

                            if (canvas.getWidth() <= 0) canvas.setWidth(AREA_BLOCK_SIZE);
                        }

                        canvas.setMouseTransparent(true);
                        canvas.setBlendMode(BlendMode.DIFFERENCE);

                        GraphicsContext context2D = canvas.getGraphicsContext2D();

                        int w = (int) canvas.getWidth();
                        int h = (int) canvas.getHeight();

                        context2D.setFill(Color.GRAY);
                        context2D.setLineDashes(2, 2);

                        if (snapType == SnapType.DOTS) {
                            for (int i = 0; i < (w / snapSizeX) + 1; i++) {
                                for (int j = 0; j < (h / snapSizeY) + 1; j++) {
                                    context2D.fillRect(i * snapSizeX, j * snapSizeY, 1, 1);
                                }
                            }
                        } else {
                            for (int i = 0; i < (w / snapSizeX) + 1; i++) {
                                context2D.fillRect(i * snapSizeX, 0, 1, h);
                            }

                            for (int i = 0; i < (h / snapSizeY) + 1; i++) {
                                context2D.fillRect(0, i * snapSizeY, w, 1);
                            }
                        }

                        SnapshotParameters snapParams = new SnapshotParameters();
                        snapParams.setFill(Color.TRANSPARENT);
                        snapshot = canvas.snapshot(snapParams, null);

                        canvas.relocate(I * AREA_BLOCK_SIZE, J * AREA_BLOCK_SIZE);
                        dots.getChildren().add(canvas);
                    } else {
                        ImageView imageView = new ImageView(snapshot);
                        imageView.getStyleClass().add(SYSTEM_ELEMENT_CSS_CLASS);
                        imageView.setMouseTransparent(true);
                        imageView.setPreserveRatio(false);
                        Rectangle2D viewPort = new Rectangle2D(0, 0, AREA_BLOCK_SIZE, AREA_BLOCK_SIZE);

                        //imageView.setFitWidth(AREA_BLOCK_SIZE);
                        // imageView.setFitHeight(AREA_BLOCK_SIZE);

                        if (J == heightBlocks - 1) {
                            double hh = height % AREA_BLOCK_SIZE;
                            hh = hh == 0 ? AREA_BLOCK_SIZE : hh;

                            viewPort = new Rectangle2D(0, 0, viewPort.getWidth(), hh);
                        }

                        if (I == widthBlocks - 1) {
                            double ww = width % AREA_BLOCK_SIZE;
                            ww = ww == 0 ? AREA_BLOCK_SIZE : ww;

                            viewPort = new Rectangle2D(0, 0, ww, viewPort.getHeight());
                        }

                        imageView.setClip(new Rectangle(viewPort.getWidth(), viewPort.getHeight()));

                        imageView.relocate(I * AREA_BLOCK_SIZE, J * AREA_BLOCK_SIZE);
                        dots.getChildren().addAll(imageView);
                    }
                }
            }

            area.getChildren().addAll(dots);
            dots.toBack();
        }
    }

    @Signature
    public void update() {
        if (dots != null) {
            dots.toBack();
        }

        for (Selection selection : selections.values()) {
            selection.update();
        }
    }


    @Signature
    public void addSelectionControl(Node node) {
        node.addEventHandler(MouseEvent.MOUSE_PRESSED, new EventHandler<MouseEvent>() {
            @Override
            public void handle(MouseEvent event) {
                if (disabled) return;

                if (isEditing()) {
                    return;
                }

                if (contextMenu != null && event.getButton() == MouseButton.SECONDARY) {
                    if (contextMenu.isShowing()) {
                        contextMenu.setX(event.getSceneX());
                        contextMenu.setY(event.getSceneY());
                    } else {
                        contextMenu.show(area, event.getScreenX(), event.getScreenY());
                    }

                    event.consume();
                    return;
                }

                if (selectionEnabled) {
                    selectionRectangle.setX(event.getScreenX());
                    selectionRectangle.setY(event.getScreenY());

                    selectionRectanglePoint = new Point2D(event.getScreenX(), event.getScreenY());

                    selectionRectangle.setWidth(1);
                    selectionRectangle.setHeight(1);
                }
                //event.consume();
            }
        });


        node.addEventHandler(MouseEvent.MOUSE_DRAGGED, new EventHandler<MouseEvent>() {
            @Override
            public void handle(MouseEvent event) {
                if (disabled) return;

                if (isEditing()) {
                    return;
                }

                if (selectionEnabled && selectionRectanglePoint != null) {
                    double width = event.getScreenX() - selectionRectanglePoint.getX();
                    double height = event.getScreenY() - selectionRectanglePoint.getY();

                    selectionRectangle.setWidth(Math.abs(width));
                    selectionRectangle.setHeight(Math.abs(height));

                    if (width < 0) {
                        selectionRectangle.setX(selectionRectanglePoint.getX() + width);
                    } else {
                        selectionRectangle.setX(selectionRectanglePoint.getX());
                    }

                    if (height < 0) {
                        selectionRectangle.setY(selectionRectanglePoint.getY() + height);
                    } else {
                        selectionRectangle.setY(selectionRectanglePoint.getY());
                    }

                    if (Math.abs(width) > 2 && Math.abs(height) > 2) {
                        selectionRectangle.show();
                    }

                    //event.consume();
                }
            }
        });

        node.addEventHandler(MouseEvent.MOUSE_RELEASED, new EventHandler<MouseEvent>() {
            @Override
            public void handle(MouseEvent event) {
                if (disabled) return;

                if (isEditing()) {
                    return;
                }

                if (tmpLock) {
                    tmpLock = false;
                    return;
                }

                selectionRectangle.hide();
                selectionRectanglePoint = null;

                if (!event.isShiftDown() || !event.isControlDown() || !(node.getParent() instanceof AnchorPane)) {
                    unselectAll();
                }

                if (selectionEnabled) {
                    Point2D fromXY = area.screenToLocal(selectionRectangle.getX(), selectionRectangle.getY());
                    double width = selectionRectangle.getWidth();
                    double height = selectionRectangle.getHeight();

                    for (Node node : getNodesInArea(fromXY.getX(), fromXY.getY(), width, height)) {
                        selectNode(node);
                    }
                }
            }
        });
    }

    @Signature
    public void requestFocus() {
        area.requestFocus();
    }

    @Getter
    public boolean getDisabled() {
        return disabled;
    }

    @Setter
    public void setDisabled(boolean disabled) {
        this.disabled = disabled;
    }

    @Getter
    public double getZoom() {
        return zoom;
    }

    @Setter
    public void setZoom(double zoom) {
        this.zoom = zoom;
    }

    @Getter
    public Node getPickedNode() {
        if (selections.containsKey(picked)) {
            return picked;
        } else {
            Iterator<Node> iterator = selections.keySet().iterator();

            if (iterator.hasNext()) {
                return iterator.next();
            }

            return null;
        }
    }

    @Getter
    public ContextMenu getContextMenu() {
        return contextMenu;
    }

    @Setter
    public void setContextMenu(@Nullable ContextMenu contextMenu) {
        this.contextMenu = contextMenu;

        contextMenu.setOnAction(new EventHandler<ActionEvent>() {
            @Override
            public void handle(ActionEvent event) {
                if (disabled) {
                    event.consume();
                }
            }
        });

        for (Node node : nodes.keySet()) {
            if (node instanceof Control) {
                ((Control) node).setContextMenu(contextMenu);
            }
        }
    }

    @Getter
    protected boolean isEditing() {
        return dragged || resizing;
    }

    @Getter
    protected boolean isDragged() {
        return dragged;
    }

    @Getter
    public boolean isResizing() {
        return resizing;
    }

    @Getter
    protected boolean getSelectionEnabled() {
        return selectionEnabled;
    }

    @Setter
    protected void setSelectionEnabled(boolean value) {
        selectionEnabled = value;
    }

    @Getter
    protected boolean getSnapEnabled() {
        return snapEnabled;
    }

    @Setter
    protected void setSnapEnabled(boolean value) {
        snapEnabled = value;
    }

    @Getter
    protected boolean getHelpersEnabled() {
        return helpersEnabled;
    }

    @Setter
    protected void setHelpersEnabled(boolean value) {
        helpersEnabled = value;
    }

    @Getter
    protected int getSnapSizeX() {
        return snapSizeX;
    }

    @Setter
    protected void setSnapSizeX(int size) {
        snapSizeX = size;
        updateHelpers();
    }

    @Getter
    protected int getSnapSizeY() {
        return snapSizeY;
    }

    @Setter
    protected void setSnapSizeY(int size) {
        snapSizeY = size;
        updateHelpers();
    }

    @Getter
    public SnapType getSnapType() {
        return snapType;
    }

    @Setter
    public void setSnapType(SnapType snapType) {
        this.snapType = snapType;
        updateHelpers();
    }

    @Signature
    public void __construct(final Pane area) {
        this.area = area;
        ChangeListener<Number> resizeListener = new ChangeListener<Number>() {
            @Override
            public void changed(ObservableValue<? extends Number> observable, Number oldValue, Number newValue) {
                updateHelpers();
            }
        };

        area.widthProperty().addListener(resizeListener);
        area.heightProperty().addListener(resizeListener);

        selectionRectangle = new Stage(StageStyle.UNDECORATED);
        selectionRectangle.setTitle("...");
        final AnchorPane selectionRectangleLayout = new AnchorPane();
        selectionRectangleLayout.setMouseTransparent(true);
        selectionRectangle.setScene(new Scene(selectionRectangleLayout));
        selectionRectangle.setOpacity(0.5);

        if (area.getScene() != null) {
            selectionRectangle.initOwner(area.getScene().getWindow());
        }

        area.sceneProperty().addListener((observable, oldValue, newValue) -> {
            if (newValue != null) {
                if (selectionRectangle.getOwner() == null) {
                    selectionRectangle.hide();

                    try {
                        selectionRectangle.initOwner(newValue.getWindow());
                    } catch (IllegalStateException e) {
                        e.printStackTrace();
                    }
                }
            }
        });

        selectionRectangleLayout.setStyle("-fx-background-color: black;");

        area.setOnKeyPressed(new EventHandler<KeyEvent>() {
            @Override
            public void handle(KeyEvent event) {
                if (contextMenu != null) {
                    for (MenuItem menuItem : contextMenu.getItems()) {
                        if (menuItem != null /*&& !menuItem.isDisable() */
                                && menuItem.getAccelerator() != null && menuItem.getAccelerator().match(event)) {
                            menuItem.getOnAction().handle(new ActionEvent(menuItem, null));

                            event.consume();
                            return;
                        }
                    }
                }
            }
        });

        area.setOnMouseClicked(new EventHandler<MouseEvent>() {
            @Override
            public void handle(MouseEvent event) {
                if (disabled) return;

                event.consume();
            }
        });

        area.setOnMousePressed(new EventHandler<MouseEvent>() {
            @Override
            public void handle(final MouseEvent event) {
                if (disabled) return;

                if (event.getX() > area.getPrefWidth() || event.getY() > area.getPrefHeight()) {
                    return;
                }

                if (isEditing()) {
                    return;
                }

                if (onAreaMouseDown != null && onAreaMouseDown.callAny(event).toBoolean()) {
                    event.consume();
                    return;
                }

                if (selectionEnabled) {
                    selectionRectangle.setX(event.getScreenX());
                    selectionRectangle.setY(event.getScreenY());

                    selectionRectanglePoint = new Point2D(event.getScreenX(), event.getScreenY());

                    selectionRectangle.setWidth(1);
                    selectionRectangle.setHeight(1);
                }

                event.consume();
            }
        });

        area.setOnMouseDragged(new EventHandler<MouseEvent>() {
            @Override
            public void handle(MouseEvent event) {
                if (disabled) return;

                /*if (event.getX() > area.getPrefWidth() || event.getY() > area.getPrefHeight()) {
                    return;
                } */

                if (isEditing()) {
                    return;
                }

                if (selectionEnabled && selectionRectanglePoint != null) {
                    double width = event.getScreenX() - selectionRectanglePoint.getX();
                    double height = event.getScreenY() - selectionRectanglePoint.getY();

                    selectionRectangle.setWidth(Math.abs(width));
                    selectionRectangle.setHeight(Math.abs(height));

                    if (width < 0) {
                        selectionRectangle.setX(selectionRectanglePoint.getX() + width);
                    } else {
                        selectionRectangle.setX(selectionRectanglePoint.getX());
                    }

                    if (height < 0) {
                        selectionRectangle.setY(selectionRectanglePoint.getY() + height);
                    } else {
                        selectionRectangle.setY(selectionRectanglePoint.getY());
                    }

                    if (Math.abs(width) > 2 && Math.abs(height) > 2) {
                        selectionRectangle.show();
                    }

                    event.consume();
                }
            }
        });

        area.setOnDragDetected(new EventHandler<MouseEvent>() {
            @Override
            public void handle(MouseEvent event) {
                event.consume();
            }
        });

        area.setOnDragDone(new EventHandler<DragEvent>() {
            @Override
            public void handle(DragEvent event) {
                event.consume();
            }
        });

        area.setOnMouseReleased(new EventHandler<MouseEvent>() {
            @Override
            public void handle(MouseEvent event) {
                if (disabled) return;

                /*if (event.getX() > area.getPrefWidth() || event.getY() > area.getPrefHeight()) {
                    return;
                } */

                if (isEditing()) {
                    return;
                }

                if (tmpLock) {
                    tmpLock = false;
                    return;
                }

                if (onAreaMouseUp != null && onAreaMouseUp.callAny(event).toBoolean()) {
                    event.consume();
                    return;
                }

                selectionRectangle.hide();
                selectionRectanglePoint = null;

                if (!event.isShiftDown()) {
                    unselectAll();
                }

                checkContextMenu(event, area);

                if (selectionEnabled) {
                    Point2D fromXY = area.screenToLocal(selectionRectangle.getX(), selectionRectangle.getY());
                    double width = selectionRectangle.getWidth();
                    double height = selectionRectangle.getHeight();

                    for (Node node : getNodesInArea(fromXY.getX(), fromXY.getY(), width, height)) {
                        selectNode(node);
                    }

                    event.consume();
                }

                event.consume();
            }
        });
    }

    @Signature
    public Stage getSelectionRectangle() {
        return selectionRectangle;
    }

    @Signature
    public void onAreaMouseDown(@Nullable Invoker invoker) {
        onAreaMouseDown = invoker;
    }

    @Signature
    public void onAreaMouseUp(@Nullable Invoker invoker) {
        onAreaMouseUp = invoker;
    }

    @Signature
    public void onNodeClick(@Nullable Invoker invoker) {
        onNodeClick = invoker;
    }

    @Signature
    public void onNodePick(@Nullable Invoker invoker) {
        onNodePick = invoker;
    }

    @Signature
    public void onChanged(@Nullable Invoker invoker) {
        onChanged = invoker;
    }

    protected double getCenterX(Node node) {
        if (node instanceof Circle) {
            return ((Circle) node).getRadius();
        }

        if (node instanceof Ellipse) {
            return ((Ellipse) node).getRadiusX();
        }

        if (node instanceof Polygon) {
            return UXPolygon.getWidth((Polygon) node) / 2;
        }

        return 0.0;
    }

    protected double getCenterY(Node node) {
        if (node instanceof Circle) {
            return ((Circle) node).getRadius();
        }

        if (node instanceof Ellipse) {
            return ((Ellipse) node).getRadiusY();
        }

        if (node instanceof Polygon) {
            return UXPolygon.getHeight((Polygon) node) / 2;
        }

        return 0.0;
    }

    protected void resizeNode(Node node, double width, double height) {


        if (node instanceof Region) {
            ((Region) node).setPrefSize(width, height);
            return;
        }

        if (node instanceof ImageView) {
            ((ImageView) node).setFitWidth(width);
            ((ImageView) node).setFitHeight(height);
            return;
        }

        if (node instanceof MediaView) {
            ((MediaView) node).setFitWidth(width);
            ((MediaView) node).setFitHeight(height);
            return;
        }

        if (node instanceof Rectangle) {
            ((Rectangle) node).setWidth(width);
            ((Rectangle) node).setHeight(height);
            return;
        }

        if (node instanceof Circle) {
            double radius = (width < height ? width : height) / 2;
            ((Circle) node).setRadius(radius);
            return;
        }

        if (node instanceof Ellipse) {
            ((Ellipse) node).setRadiusX(width / 2);
            ((Ellipse) node).setRadiusY(height / 2);
            return;
        }

        if (node instanceof Polygon) {
            UXPolygon.setWidth((Polygon) node, width);
            UXPolygon.setHeight((Polygon) node, height);
            return;
        }

        if (node instanceof WebView) {
            ((WebView) node).setPrefSize(width, height);
            return;
        }

        if (node instanceof Canvas) {
            ((Canvas) node).setWidth(width);
            ((Canvas) node).setHeight(height);
            return;
        }

        node.resize(width, height);
    }

    @Signature
    public void relocateNode(Node node, double x, double y) {
        Border border = node.getParent() instanceof Region ? ((Region) node.getParent()).getBorder() : null;
        Insets borderInsets = border == null ? Insets.EMPTY : border.getInsets();

        if (AnchorPane.getLeftAnchor(node) != null) {
            AnchorPane.setLeftAnchor(node, x - borderInsets.getLeft());
        }

        if (AnchorPane.getTopAnchor(node) != null) {
            AnchorPane.setTopAnchor(node, y - borderInsets.getTop());
        }

        if (AnchorPane.getRightAnchor(node) != null) {
            double offset = x - node.getLayoutX();
            AnchorPane.setRightAnchor(node, AnchorPane.getRightAnchor(node) - offset);
        }

        if (AnchorPane.getBottomAnchor(node) != null) {
            double offset = y - node.getLayoutY();
            AnchorPane.setBottomAnchor(node, AnchorPane.getBottomAnchor(node) - offset);
        }

        x = x + getCenterX(node);
        y = y + getCenterY(node);

        node.relocate(x, y);
    }

    @Signature
    public List<Node> getNodesInArea(double x, double y, double w, double h) {
        List<Node> result = new ArrayList<>();

        Point2D pt = area.localToScreen(x, y);

        for (Node node : nodes.keySet()) {
            Point2D nodePt = node.getParent().localToScreen(node.getLayoutX(), node.getLayoutY());

            double nx = nodePt.getX() - getCenterX(node);
            double ny = nodePt.getY() - getCenterY(node);
            double nw = node.getBoundsInLocal().getWidth();
            double nh = node.getBoundsInLocal().getHeight();

            Point2D center = new Point2D(pt.getX() + Math.round(w / 2), pt.getY() + Math.round(h / 2));
            Point2D nCenter = new Point2D(nx + Math.round(nw / 2), ny + Math.round(nh / 2));

            double _w, _h = 0;

            _w = Math.abs(center.getX() - nCenter.getX());
            _h = Math.abs(center.getY() - nCenter.getY());

            boolean checkW = _w < (w / 2 + nw / 2);
            boolean checkH = _h < (h / 2 + nh / 2);

            if (checkW && checkH) {
                result.add(node);
            }
        }

        return result;
    }

    @Signature
    public Set<Node> getSelectedNodes() {
        return selections.keySet();
    }

    @Signature
    public boolean isNodeParentSelected(Node node) {
        Node parent = node;

        do {
            parent = parent.getParent();

            if (selections.containsKey(parent)) {
                return true;
            }

        } while (parent != null && parent != area);

        return false;
    }

    @Signature
    public Set<Node> getNodes() {
        return nodes.keySet();
    }

    @Signature
    public void selectNode(Node node) {
        selectNode(node, null);
    }

    @Signature
    public boolean isSelectedNode(Node node) {
        if (!nodes.containsKey(node)) {
            throw new RuntimeException("Node is not registered");
        }

        Selection selection = selections.get(node);

        return selection != null;
    }

    @Signature
    public void selectNode(Node node, MouseEvent e) {
        if (!nodes.containsKey(node)) {
            throw new RuntimeException("Node is not registered");
        }

        if (node.getParent() == null) {
            return;
        }

        Selection selection = selections.get(node);

        if (selection == null) {
            selections.put(node, selection = new Selection(node));

            picked = node;

            if (onNodePick != null) {
                onNodePick.callAny(e);
            }
        } else {
            selection.update();
        }

        Item item = nodes.get(node);

        if (item != null) {
            selection.setLocked(item.isLocked());
            selection.setDots(item.isSimpled());
        }
    }

    @Signature
    public void unselectAll() {
        for (Selection selection : selections.values()) {
            selection.destroy();
        }

        picked = null;
        selections.clear();
    }

    @Signature
    public void unselectNode(Node node) {
        Selection selection = selections.get(node);

        if (selection != null) {
            selection.destroy();
            selections.remove(node);
        }
    }

    @Signature
    public void unregisterNode(final Node node) {
        if (nodes.remove(node) != null) {
            node.setOnDragDetected(null);
            node.setOnMouseDragged(null);
            node.setOnMousePressed(null);
            node.setOnMouseReleased(null);
        }
    }

    @Signature
    public void setNodeLock(Node node, boolean lock) {
        if (nodes.containsKey(node)) {
            nodes.get(node).setLocked(lock);

            if (selections.containsKey(node)) {
                selections.get(node).setLocked(lock);
            }
        }
    }

    @Signature
    public void setNodeSimple(Node node, boolean value) {
        if (nodes.containsKey(node)) {
            nodes.get(node).setSimpled(value);

            if (selections.containsKey(node)) {
                selections.get(node).setDots(value);
            }
        }
    }

    @Signature
    public boolean getNodeLock(Node node) {
        if (nodes.containsKey(node)) {
            return nodes.get(node).isLocked();
        }

        return false;
    }

    @Signature
    public boolean getNodeSimple(Node node) {
        if (nodes.containsKey(node)) {
            return nodes.get(node).isSimpled();
        }

        return false;
    }

    @Signature
    public boolean isRegisteredNode(final Node node) {
        return nodes.containsKey(node);
    }

    protected boolean checkContextMenu(final MouseEvent e, Node node) {
        if (e.getButton() == MouseButton.SECONDARY) {
            if (contextMenu != null && !(node instanceof Control)) {
                Platform.runLater(new Runnable() {
                    @Override
                    public void run() {
                        PointerInfo pointerInfo = MouseInfo.getPointerInfo();

                        contextMenu.setX(pointerInfo.getLocation().x);
                        contextMenu.setY(pointerInfo.getLocation().y);
                    }
                });

                if (contextMenu.isShowing()) {
                    contextMenu.hide();
                }

                PointerInfo pointerInfo = MouseInfo.getPointerInfo();

                contextMenu.show(node.getScene().getWindow(), pointerInfo.getLocation().x, pointerInfo.getLocation().y);
            }

            return true;
        } else {
            if (contextMenu != null) {
                contextMenu.hide();
            }

            return false;
        }
    }

    protected boolean isWithChildrenNode(Node node) {
        return node instanceof AnchorPane || node instanceof VBox || node instanceof HBox || node instanceof FlowPane
                || node instanceof TitledPane || node instanceof TilePane || node instanceof StackPane || node instanceof GridPane
                || node instanceof TabPane || node instanceof ScrollPane;
    }

    @Signature
    public void unregisterAll() {
        nodes.clear();
    }

    private Selection getSelection(Dragboard dragboard) {
        if (dragboard.hasRtf()) {
            String id = dragboard.getRtf();

            for (Selection selection : selections.values()) {
                if (id.equals(selection.node.getId())) {
                    return selection;
                }
            }
        }

        return null;
    }

    @Signature
    public void registerNode(final Node node) {
        if (nodes.containsKey(node)) {
            throw new RuntimeException("Node already registered");
        }

        //node.setFocusTraversable(false);

        nodes.put(node, new Item(node));

        if (node instanceof Control) {
            ((Control) node).setContextMenu(contextMenu);

            if (node instanceof Spinner) {
                ((Spinner) node).getEditor().setContextMenu(contextMenu);
            }
        }

        node.setOnKeyPressed(area.getOnKeyPressed());

        //if (!isWithChildrenNode(node)) {
            node.addEventFilter(DragEvent.DRAG_OVER, event -> {
                Dragboard dragboard = event.getDragboard();

                if (getSelection(dragboard) != null) {
                    event.acceptTransferModes(TransferMode.ANY);
                }
            });

            node.addEventFilter(DragEvent.DRAG_ENTERED, event -> {
               // if (event.isAccepted()) {
                    Selection selection = getSelection(event.getDragboard());

                    if (selection != null && node != selection.node) {
                        Bounds nodeBounds = node.getLayoutBounds();
                        Bounds nodeScreenBounds = node.localToScreen(nodeBounds);

                        Rectangle rectangle = new Rectangle(nodeBounds.getWidth(), nodeBounds.getHeight());
                        rectangle.getStyleClass().add(SYSTEM_ELEMENT_CSS_CLASS);
                        rectangle.setStrokeWidth(3);
                        rectangle.setStroke(Color.LIGHTBLUE);
                        rectangle.setStrokeType(StrokeType.INSIDE);
                        rectangle.setStrokeLineCap(StrokeLineCap.SQUARE);
                        rectangle.setFill(new Color(1, 1, 1, 0.3));
                        rectangle.setMouseTransparent(true);

                        node.setUserData(rectangle);

                        Bounds pt = area.screenToLocal(nodeScreenBounds);
                        rectangle.relocate(pt.getMinX(), pt.getMinY());

                        area.getChildren().add(rectangle);
                        return;
                    }

                    event.getDragboard().setDragView(null);
               // }
            });

            node.addEventFilter(DragEvent.DRAG_EXITED, event -> {
                if (node.getUserData() instanceof Rectangle) {
                    area.getChildren().remove(node.getUserData());
                }
            });

            node.addEventFilter(DragEvent.DRAG_DONE, Event::consume);
            node.addEventFilter(DragEvent.DRAG_DROPPED, event -> {
                Dragboard dragboard = event.getDragboard();

                Selection selection = getSelection(dragboard);

                if (selection != null) {
                    if (node == selection.node) {
                        return;
                    }

                    ObservableList<Node> children = selection.parent.getChildren();
                    int index = children.indexOf(node);

                    if (index > -1) {
                        children.remove(selection.node);
                        children.add(index, selection.node);

                        selection.update();
                        event.consume();

                        if (onChanged != null) {
                            onChanged.callAny();
                        }
                    }
                }

                tmpLock = true;
                Platform.runLater(() -> tmpLock = false);

                dragged = false;
            });
        /*} else {
            node.addEventFilter(DragEvent.DRAG_DONE, event -> {
                dragged = false;
            });
        }*/

        EventHandler<MouseEvent> onDragDetected = new EventHandler<MouseEvent>() {
            public void handle(MouseEvent e) {
                if (e.getButton() != MouseButton.PRIMARY) {
                    return;
                }

                for (final Selection selection : selections.values()) {
                    if (getNodeLock(selection.getNode()) || isNodeParentSelected(selection.getNode())) {
                        continue;
                    }

                    dragged = true;

                    SnapshotParameters snapParams = new SnapshotParameters();
                    snapParams.setFill(Color.TRANSPARENT);

                    final Effect effect = selection.node.getEffect();
                    selection.node.setEffect(null);

                    selection.dragView.getChildren().setAll(new ImageView(selection.node.snapshot(snapParams, null)));
                    selection.dragView.setStyle("-fx-opacity: 0.7; -fx-border-width: 1px; -fx-border-color: black; -fx-border-style: dashed; -fx-background-color: transparent");

                    if (selection.parent instanceof AnchorPane) {
                        selection.parent.getChildren().add(selection.dragView);
                    } else {
                        ObservableList<Node> children = selection.parent.getChildren();
                        //int index = children.indexOf(selection.node);

                        //children.add(index, selection.dragView);
                        //children.remove(index + 1);

                        Dragboard dragboard = selection.node.startDragAndDrop(TransferMode.ANY);

                        ClipboardContent content = new ClipboardContent();
                        content.putRtf(selection.node.getId());
                        dragboard.setContent(content);
                    }

                    selection.node.setEffect(effect);
                }

                e.consume();
            }
        };
        //node.setOnDragDetected(onDragDetected);
        node.addEventFilter(MouseEvent.DRAG_DETECTED, onDragDetected);

        EventHandler<MouseEvent> onMouseDragged = new EventHandler<MouseEvent>() {
            public void handle(MouseEvent e) {
                if (e.getButton() != MouseButton.PRIMARY) {
                    return;
                }

                for (Selection sel : selections.values()) {
                    if (getNodeLock(sel.getNode()) || isNodeParentSelected(sel.getNode())) {
                        continue;
                    }

                    Point2D localPoint = new Point2D(e.getScreenX(), e.getScreenY());

                    double dx = localPoint.getX() - startPoint.getX();
                    double dy = localPoint.getY() - startPoint.getY();

                    Bounds bounds = sel.node.getBoundsInParent();
                    Bounds layoutBounds = sel.node.getLayoutBounds();

                    double diffW = bounds.getWidth() - layoutBounds.getWidth();
                    double diffH = bounds.getHeight() - layoutBounds.getHeight();

                    if (sel.node.getEffect() == null) {
                        sel.dragView.setUserData(new Insets(diffH, 0, 0, diffW));
                    }

                    double x = sel.node.getLayoutX() + dx/zoom;
                    double y = sel.node.getLayoutY() + dy/zoom;

                    if (!e.isAltDown() && snapSizeX > 1 && snapSizeY > 1 && snapEnabled) {
                        x = Math.round((x / snapSizeX)) * snapSizeX;
                        y = Math.round((y / snapSizeY)) * snapSizeY;
                    }

                    sel.drag(x, y);
                }

                e.consume();
            }
        };
        //node.setOnMouseDragged(onMouseDragged);
        node.addEventFilter(MouseEvent.MOUSE_DRAGGED, onMouseDragged);

        EventHandler<MouseEvent> onMousePressed = new EventHandler<MouseEvent>() {
            public void handle(final MouseEvent e) {
                final Node node = (Node) e.getSource();

                /*Node intersectedNode = e.getPickResult().getIntersectedNode();

                if (intersectedNode != null && intersectedNode != node && isWithChildrenNode(intersectedNode)) {
                    //return;
                } else { */
                if (onNodeClick != null) {
                    if (onNodeClick.callAny(e).toBoolean()) {
                        return;
                    }
                }
                //}

                if (!dragged) {
                    boolean isSelected = selections.get(node) != null;

                    {
                        if (!isSelected && !e.isShiftDown()) {
                            UXDesigner.this.unselectAll();
                        }

                        if (!isSelected) {
                            UXDesigner.this.selectNode(node, e);
                        } else {
                            if (e.isShiftDown()) {
                                UXDesigner.this.unselectNode(node);
                            }
                        }
                    }
                }

                Pane parent = (Pane) node.getParent();
                startPoint = new Point2D(e.getScreenX(), e.getScreenY()); // parent.sceneToLocal(new Point2D(e.getSceneX(), e.getSceneY()));

                for (Selection selection : selections.values()) {
                    selection.dragView.setMouseTransparent(true);
                    selection.drag(selection.node.getLayoutX(), selection.node.getLayoutY(), false);
                }

                if (!isWithChildrenNode(node)) {
                    e.consume();
                }

                picked = node;
            }
        };

        //node.setOnMousePressed(onMousePressed);
        node.addEventFilter(MouseEvent.MOUSE_PRESSED, onMousePressed);

        EventHandler<MouseEvent> onMouseReleased = new EventHandler<MouseEvent>() {
            public void handle(MouseEvent e) {
                if (dragged) {
                    List<Selection> skipSelections = new ArrayList<>();

                    for (Selection selection : selections.values()) {
                        selection.node.setMouseTransparent(false);
                        selection.node.setCursor(Cursor.DEFAULT);

                        if (isNodeParentSelected(selection.node)) {
                            skipSelections.add(selection);
                            continue;
                        }

                        if (!getNodeLock(selection.node) && !isNodeParentSelected(selection.node)) {
                            if (selection.parent instanceof AnchorPane) {
                                Object userData = selection.dragView.getUserData();

                                double x = selection.dragView.getLayoutX();
                                double y = selection.dragView.getLayoutY();

                                if (userData instanceof Insets) {
                                    x += ((Insets) userData).getLeft();
                                    y += ((Insets) userData).getTop();
                                }

                                selection.drag(x, y, false);
                                relocateNode(selection.node, x - getCenterX(node), y - getCenterY(node));
                            } else {
                                int index = selection.parent.getChildren().indexOf(selection.dragView);
                                selection.parent.getChildren().add(index, selection.node);
                            }
                        }

                        selection.update();
                        selection.parent.getChildren().remove(selection.dragView);
                    }

                    for (Selection skipSelection : skipSelections) {
                        skipSelection.update();
                    }

                    if (onChanged != null) {
                        onChanged.callAny();
                    }

                    //if (e.getButton() == MouseButton.PRIMARY) {
                    e.consume();
                    //}
                } else {
                    for (Selection selection : selections.values()) {
                        selection.update();
                    }
                }

                checkContextMenu(e, node);

                //if (e.getButton() == MouseButton.PRIMARY) {
                e.consume();
                //}

                tmpLock = true;

                Platform.runLater(new Runnable() {
                    @Override
                    public void run() {
                        tmpLock = false;
                    }
                });

                dragged = false;
            }
        };

        //node.setOnMouseReleased(onMouseReleased);
        node.addEventFilter(MouseEvent.MOUSE_RELEASED, onMouseReleased);
    }

    protected void runLater(Runnable runnable, long wait) {
        new Thread(() -> {
            try {
                Thread.sleep(wait);
            } catch (InterruptedException e) {
                throw new RuntimeException(e);
            }

            Platform.runLater(runnable);
        }).start();
    }

    static public class Item {
        public final Node node;

        protected boolean locked = false;
        protected boolean simpled = false;

        public Item(Node node) {
            this.node = node;
        }

        public boolean isLocked() {
            return locked;
        }

        public void setLocked(boolean locked) {
            this.locked = locked;
        }

        public boolean isSimpled() {
            return simpled;
        }

        public void setSimpled(boolean simpled) {
            this.simpled = simpled;
        }
    }

    public class Selection {
        public static final int POINT_SIZE = 6;

        //public final ImageView dragImageView = new ImageView();
        public final HBox dragView = new HBox();

        protected boolean locked = false;
        protected boolean dots = false;

        protected Rectangle ltPoint;
        protected Rectangle tPoint;
        protected Rectangle lbPoint;
        protected Rectangle rPoint;
        protected Rectangle rtPoint;
        protected Rectangle bPoint;
        protected Rectangle rbPoint;
        protected Rectangle lPoint;

        protected Rectangle border;
        protected Label sizeText;

        protected Rectangle resizePoint = null;

        protected int startX = 0;
        protected int startY = 0;
        protected int startW = 0;
        protected int startH = 0;

        protected int nodeX, nodeY, nodeW, nodeH;

        protected int resizeX = 0;
        protected int resizeY = 0;
        protected int resizeW = 0;
        protected int resizeH = 0;

        protected final Node node;
        protected Pane parent;

        public Selection(Node node) {
            this.node = node;

            buildHelpers();
            update();
        }

        public void rebuildHelpers() {
            destroy();
            buildHelpers();
            update();
        }

        public void buildHelpers() {
            ltPoint = buildPoint();
            lbPoint = buildPoint();
            rtPoint = buildPoint();
            rbPoint = buildPoint();

            tPoint = buildPoint();
            bPoint = buildPoint();
            lPoint = buildPoint();
            rPoint = buildPoint();

            ltPoint.setCursor(Cursor.NW_RESIZE);
            lbPoint.setCursor(Cursor.SW_RESIZE);
            rtPoint.setCursor(Cursor.NE_RESIZE);
            rbPoint.setCursor(Cursor.SE_RESIZE);

            tPoint.setCursor(Cursor.N_RESIZE);
            bPoint.setCursor(Cursor.S_RESIZE);
            lPoint.setCursor(Cursor.W_RESIZE);
            rPoint.setCursor(Cursor.E_RESIZE);

            parent = (Pane) node.getParent();

            area.getChildren().addAll(ltPoint, lbPoint, rtPoint, rbPoint);
            area.getChildren().addAll(tPoint, bPoint, lPoint, rPoint);

            border = new Rectangle();
            border.getStyleClass().add(SYSTEM_ELEMENT_CSS_CLASS);
            border.setVisible(isWithChildrenNode(node));
            border.setMouseTransparent(true);
            border.setFill(Color.TRANSPARENT);
            border.setStroke(Color.GRAY);
            border.getStrokeDashArray().addAll(2d);
            border.setStrokeType(StrokeType.CENTERED);
            border.setBlendMode(BlendMode.MULTIPLY);

            area.getChildren().addAll(border);

            sizeText = new Label();
            sizeText.getStyleClass().add(SYSTEM_ELEMENT_CSS_CLASS);
            //sizeText.setMouseTransparent(true);
            sizeText.setStyle("-fx-background-color: #fffe85; -fx-padding: 1px 7px; -fx-font-size: 1em; -fx-text-fill: black;");
            sizeText.setPrefHeight(20);

            sizeText.addEventFilter(MouseEvent.MOUSE_ENTERED, event -> sizeText.setOpacity(0.01));
            sizeText.addEventFilter(MouseEvent.MOUSE_EXITED, event -> sizeText.setOpacity(1.0));
            sizeText.addEventFilter(MouseEvent.MOUSE_CLICKED, event -> sizeText.setVisible(false));

            area.getChildren().addAll(sizeText);

            if (!locked) {
                bindEvents();
            }
        }

        public void setLocked(boolean locked) {
            this.locked = locked;
            rebuildHelpers();
        }

        public void setDots(boolean dots) {
            this.dots = dots;
            rebuildHelpers();
        }

        public Node getNode() {
            return node;
        }

        public void update() {
            Bounds bounds = node.getLayoutBounds();

            Point2D point2D = parent == null ? null : parent.localToScreen(node.getLayoutX(), node.getLayoutY());

            showHelperText(node.getId());

            if (point2D != null) {
                update(point2D.getX(), point2D.getY(), bounds);

                showHelperText(node.getId());
            } else {
                destroy();
            }
        }

        protected void updateResized() {
            Point2D point2D = parent.localToScreen(resizeX, resizeY);

            if (point2D != null) {
                border.setStroke(Color.BLACK);
                update(point2D.getX(), point2D.getY(), new BoundingBox(0, 0, resizeW, resizeH));
            } else {
                destroy();
            }
        }

        /**
         * @param x      screen x
         * @param y      screen y
         * @param bounds
         */
        public void update(double x, double y, Bounds bounds) {
            Point2D local = area.screenToLocal(x, y);

            x = local.getX() - getCenterX(this.node);
            y = local.getY() - getCenterY(this.node);

            int ptW = (int) ltPoint.getWidth();
            int ptH = (int) ltPoint.getHeight();

            int padding = ((ptW + ptH) / 2) / 2;

            int w = (int) bounds.getWidth();
            int h = (int) bounds.getHeight();

            int halfW = (int) (bounds.getWidth() / 2);
            int halfH = (int) (bounds.getHeight() / 2);

            tPoint.relocate(x + halfW - ptW / 2, y - ptH / 2 - padding);
            bPoint.relocate(x + halfW - ptW / 2, y + h - ptH / 2 + padding);

            lPoint.relocate(x - ptW / 2 - padding, y + halfH - ptH / 2);
            rPoint.relocate(x + w - ptW / 2 + padding, y + halfH - ptH / 2);

            ltPoint.relocate(x - ptW / 2 - padding, y - ptH / 2 - padding);
            lbPoint.relocate(x - ptW / 2 - padding, y + h - ptH / 2 + padding);
            rtPoint.relocate(x + w - ptW / 2 + padding, y - ptH / 2 - padding);
            rbPoint.relocate(x + w - ptW / 2 + padding, y + h - ptH / 2 + padding);

            border.relocate(ltPoint.getLayoutX() + ptW, ltPoint.getLayoutY() + ptH);
            border.setWidth(rtPoint.getLayoutX() - ltPoint.getLayoutX() - ptW - 1);
            border.setHeight(rbPoint.getLayoutY() - ltPoint.getLayoutY() - ptH - 1);

            double sizeTextY = y - sizeText.getPrefHeight() - POINT_SIZE;

            if (sizeTextY < 0) {
                sizeTextY = y + border.getHeight() + POINT_SIZE;
            }

            sizeText.relocate(x, sizeTextY);

            border.toFront();

            ltPoint.toFront();
            lbPoint.toFront();
            rtPoint.toFront();
            rbPoint.toFront();

            tPoint.toFront();
            bPoint.toFront();
            rPoint.toFront();
            lPoint.toFront();

            sizeText.toFront();

            sizeText.setVisible(resizing);

            if (resizing && helpersEnabled) {
                sizeText.setText("W: " + (int) bounds.getWidth() + ", H: " + (int) bounds.getHeight());
            }
        }

        public void destroy() {
            ObservableList<Node> children = area.getChildren();

            children.removeAll(tPoint, bPoint, rPoint, lPoint);
            children.removeAll(ltPoint, rtPoint, lbPoint, rbPoint);

            children.removeAll(border, sizeText);

            dragView.getChildren().clear();
            parent.getChildren().remove(dragView);
        }

        public Rectangle buildPoint() {
            int pointSize = POINT_SIZE;

            if (dots) {
                pointSize = POINT_SIZE - 1;
            }

            Rectangle rectangle = new Rectangle(
                    pointSize, pointSize,
                    Paint.valueOf(locked ? "gray" : (dots ? "blue" : "black"))
            );
            rectangle.getStyleClass().addAll(SYSTEM_ELEMENT_CSS_CLASS);

            if (dots) {
                rectangle.setMouseTransparent(true);
            }

            return rectangle;
        }

        @Override
        public boolean equals(Object o) {
            if (this == o) return true;
            if (!(o instanceof Selection)) return false;

            Selection selection = (Selection) o;

            if (!node.equals(selection.node)) return false;

            return true;
        }

        @Override
        public int hashCode() {
            return node.hashCode();
        }

        protected int normalizeX(int value, MouseEvent e) {
            if (e.isAltDown() || snapSizeX == 1 || !snapEnabled) {
                return value;
            }

            return value - (value % snapSizeX);
        }

        protected int normalizeY(int value, MouseEvent e) {
            if (e.isAltDown() || snapSizeY == 1 || !snapEnabled) {
                return value;
            }

            return value - (value % snapSizeY);
        }

        protected void bindEvents() {
            EventHandler<MouseEvent> mousePressed = new EventHandler<MouseEvent>() {
                @Override
                public void handle(MouseEvent event) {
                    UXDesigner.this.resizing = true;

                    border.setVisible(true);
                    resizePoint = (Rectangle) event.getSource();

                    Point2D localPoint = parent.sceneToLocal(new Point2D(event.getSceneX(), event.getSceneY()));

                    nodeX = (int) node.getLayoutX();
                    nodeY = (int) node.getLayoutY();
                    nodeW = (int) node.getLayoutBounds().getWidth();
                    nodeH = (int) node.getLayoutBounds().getHeight();

                    startX = (int) localPoint.getX();
                    startY = (int) localPoint.getY();

                    resizeX = (int) node.getLayoutX();
                    resizeY = (int) node.getLayoutY();
                    resizeW = (int) node.getLayoutBounds().getWidth();
                    resizeH = (int) node.getLayoutBounds().getHeight();

                    startW = resizeW;
                    startH = resizeH;

                    event.consume();
                }
            };

            EventHandler<MouseEvent> mouseReleased = event -> {
                UXDesigner.this.resizing = false;

                border.setStroke(Color.GRAY);
                border.setVisible(isWithChildrenNode(node));

                resizePoint = null;

                Effect effect = node.getEffect();
                node.setEffect(null);

                try {
                    final double centerX = getCenterX(node);
                    final double centerY = getCenterY(node);

                    Bounds bounds = node.getBoundsInLocal();
                    Bounds borderBounds = border.getBoundsInLocal();

                    if (resizeW != bounds.getWidth()) {
                        if (resizeX == nodeX) {
                            if (AnchorPane.getRightAnchor(node) != null) {
                                double offset = resizeW - bounds.getWidth();
                                AnchorPane.setRightAnchor(node, AnchorPane.getRightAnchor(node) - offset);
                            }
                        } else {
                            if (AnchorPane.getLeftAnchor(node) != null) {
                                AnchorPane.setLeftAnchor(node, (double) resizeX);
                            }
                        }
                    }

                    if (resizeH != bounds.getHeight()) {
                        if (resizeY == nodeY) {
                            if (AnchorPane.getBottomAnchor(node) != null) {
                                double offset = resizeH - bounds.getHeight();
                                AnchorPane.setBottomAnchor(node, AnchorPane.getBottomAnchor(node) - offset);
                            }
                        } else {
                            if (AnchorPane.getTopAnchor(node) != null) {
                                AnchorPane.setTopAnchor(node, (double) resizeY);
                            }
                        }
                    }

                    resizeNode(node, resizeW, resizeH);
                    node.relocate(resizeX - centerX, resizeY - centerY);
                } finally {
                    node.setEffect(effect);
                }

                //if (!(parent instanceof AnchorPane)) {
                    runLater(Selection.this::update, 100);
                //}

                if (onChanged != null) {
                    onChanged.callAny();
                }

                event.consume();
            };

            tPoint.addEventFilter(MouseEvent.MOUSE_PRESSED, mousePressed);
            bPoint.addEventFilter(MouseEvent.MOUSE_PRESSED, mousePressed);
            lPoint.addEventFilter(MouseEvent.MOUSE_PRESSED, mousePressed);
            rPoint.addEventFilter(MouseEvent.MOUSE_PRESSED, mousePressed);
            lbPoint.addEventFilter(MouseEvent.MOUSE_PRESSED, mousePressed);
            ltPoint.addEventFilter(MouseEvent.MOUSE_PRESSED, mousePressed);
            rbPoint.addEventFilter(MouseEvent.MOUSE_PRESSED, mousePressed);
            rtPoint.addEventFilter(MouseEvent.MOUSE_PRESSED, mousePressed);

            rbPoint.setOnMouseReleased(mouseReleased);
            rPoint.setOnMouseReleased(mouseReleased);
            bPoint.setOnMouseReleased(mouseReleased);
            tPoint.setOnMouseReleased(mouseReleased);
            rtPoint.setOnMouseReleased(mouseReleased);
            lPoint.setOnMouseReleased(mouseReleased);
            ltPoint.setOnMouseReleased(mouseReleased);
            lbPoint.setOnMouseReleased(mouseReleased);

            rbPoint.setOnMouseDragged(new EventHandler<MouseEvent>() {
                @Override
                public void handle(MouseEvent e) {
                    Point2D localPoint = parent.sceneToLocal(new Point2D(e.getSceneX(), e.getSceneY()));

                    resizeW = normalizeX((int) (nodeW + (localPoint.getX() - startX)), e);
                    resizeH = normalizeY((int) (nodeH + (localPoint.getY() - startY)), e);

                    updateResized();
                    e.consume();
                }
            });

            rPoint.setOnMouseDragged(new EventHandler<MouseEvent>() {
                @Override
                public void handle(MouseEvent e) {
                    Point2D localPoint = parent.sceneToLocal(new Point2D(e.getSceneX(), e.getSceneY()));

                    resizeW = normalizeX((int) (nodeW + (localPoint.getX() - startX)), e);

                    updateResized();
                    e.consume();
                }
            });

            bPoint.setOnMouseDragged(new EventHandler<MouseEvent>() {
                @Override
                public void handle(MouseEvent e) {
                    Point2D localPoint = parent.sceneToLocal(new Point2D(e.getSceneX(), e.getSceneY()));

                    resizeH = normalizeY((int) (nodeH + (localPoint.getY() - startY)), e);

                    updateResized();
                    e.consume();
                }
            });

            tPoint.setOnMouseDragged(new EventHandler<MouseEvent>() {
                @Override
                public void handle(MouseEvent e) {
                    Point2D localPoint = parent.sceneToLocal(new Point2D(e.getSceneX(), e.getSceneY()));

                    resizeH = normalizeY(startH + (int) (startY - localPoint.getY()), e);
                    resizeY = normalizeY((int) (node.getLayoutY() - (resizeH - startH)), e);

                    updateResized();
                    e.consume();
                }
            });

            lPoint.setOnMouseDragged(new EventHandler<MouseEvent>() {
                @Override
                public void handle(MouseEvent e) {
                    Point2D localPoint = parent.sceneToLocal(new Point2D(e.getSceneX(), e.getSceneY()));

                    resizeW = normalizeX(startW + (int) (startX - localPoint.getX()), e);
                    resizeX = normalizeX((int) (node.getLayoutX() - (resizeW - startW)), e);

                    updateResized();

                    e.consume();
                }
            });

            rtPoint.setOnMouseDragged(new EventHandler<MouseEvent>() {
                @Override
                public void handle(MouseEvent e) {
                    Point2D localPoint = parent.sceneToLocal(new Point2D(e.getSceneX(), e.getSceneY()));

                    resizeW = normalizeX((int) (nodeW + (localPoint.getX() - startX)), e);

                    resizeH = normalizeY(startH + (int) (startY - localPoint.getY()), e);
                    resizeY = normalizeY((int) (node.getLayoutY() - (resizeH - startH)), e);

                    updateResized();

                    e.consume();
                }
            });

            ltPoint.setOnMouseDragged(new EventHandler<MouseEvent>() {
                @Override
                public void handle(MouseEvent e) {
                    Point2D localPoint = parent.sceneToLocal(new Point2D(e.getSceneX(), e.getSceneY()));

                    resizeW = normalizeX(startW + (int) (startX - localPoint.getX()), e);
                    resizeX = normalizeX((int) (node.getLayoutX() - (resizeW - startW)), e);

                    resizeH = normalizeY(startH + (int) (startY - localPoint.getY()), e);
                    resizeY = normalizeY((int) (node.getLayoutY() - (resizeH - startH)), e);

                    updateResized();

                    e.consume();
                }
            });

            lbPoint.setOnMouseDragged(new EventHandler<MouseEvent>() {
                @Override
                public void handle(MouseEvent e) {
                    Point2D localPoint = parent.sceneToLocal(new Point2D(e.getSceneX(), e.getSceneY()));

                    resizeW = normalizeX(startW + (int) (startX - localPoint.getX()), e);
                    resizeX = normalizeX((int) (node.getLayoutX() - (resizeW - startW)), e);

                    resizeH = normalizeY((int) (nodeH + (localPoint.getY() - startY)), e);

                    updateResized();

                    e.consume();
                }
            });
        }

        public void drag(double x, double y) {
            drag(x, y, false);
        }

        public void drag(double x, double y, boolean updateUi) {
            dragView.relocate(x - getCenterX(node), y - getCenterY(node));

            if (updateUi) {
                sizeText.setVisible(dragged && helpersEnabled);

                if (dragged) {
                    sizeText.setText("X: " + (int) x + ", Y: " + (int) y);
                }
            }
        }

        public void showHelperText(String text) {
            if (helpersEnabled && text != null && !text.isEmpty()) {
                sizeText.setText(text);
                sizeText.setVisible(true);

            }
        }
    }
}
