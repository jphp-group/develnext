package org.develnext.jphp.gui.designer.classes;

import javafx.application.Platform;
import javafx.beans.value.ChangeListener;
import javafx.beans.value.ObservableValue;
import javafx.collections.ObservableList;
import javafx.event.ActionEvent;
import javafx.event.EventHandler;
import javafx.geometry.BoundingBox;
import javafx.geometry.Bounds;
import javafx.geometry.Point2D;
import javafx.scene.Cursor;
import javafx.scene.Node;
import javafx.scene.Scene;
import javafx.scene.SnapshotParameters;
import javafx.scene.canvas.Canvas;
import javafx.scene.canvas.GraphicsContext;
import javafx.scene.control.*;
import javafx.scene.image.ImageView;
import javafx.scene.input.DragEvent;
import javafx.scene.input.KeyEvent;
import javafx.scene.input.MouseButton;
import javafx.scene.input.MouseEvent;
import javafx.scene.layout.AnchorPane;
import javafx.scene.layout.Pane;
import javafx.scene.layout.Region;
import javafx.scene.paint.Color;
import javafx.scene.paint.Paint;
import javafx.scene.shape.Circle;
import javafx.scene.shape.Ellipse;
import javafx.scene.shape.Polygon;
import javafx.scene.shape.Rectangle;
import javafx.scene.web.WebView;
import javafx.stage.Stage;
import javafx.stage.StageStyle;
import org.develnext.jphp.ext.javafx.classes.shape.UXPolygon;
import org.develnext.jphp.gui.designer.GuiDesignerExtension;
import php.runtime.annotation.Reflection.*;
import php.runtime.env.Environment;
import php.runtime.invoke.Invoker;
import php.runtime.lang.BaseObject;
import php.runtime.reflection.ClassEntity;

import java.util.*;

@Namespace(GuiDesignerExtension.NS)
public class UXDesigner extends BaseObject {
    private Pane area;

    private Point2D startPoint;

    protected int snapSize = 8;
    protected boolean snapEnabled = true;
    protected boolean helpersEnabled = true;
    protected boolean selectionEnabled = true;

    protected boolean dragged = false;
    protected boolean resizing = false;

    private Canvas dots;

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

    public UXDesigner(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    protected void updateHelpers() {
        if (dots != null) {
            area.getChildren().removeAll(dots);
        }

        if (snapSize > 2) {
            dots = new Canvas(area.getWidth(), area.getHeight());
            dots.setMouseTransparent(true);

            GraphicsContext context2D = dots.getGraphicsContext2D();

            int w = (int) dots.getWidth();
            int h = (int) dots.getHeight();

            context2D.setFill(Color.GRAY);

            for (int i = 0; i < (w / snapSize) + 1; i++) {
                for (int j = 0; j < (h / snapSize) + 1; j++) {
                    context2D.fillRect(i * snapSize, j * snapSize, 1, 1);
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
    public Node getPickedNode() {
        return picked;
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
    protected int getSnapSize() {
        return snapSize;
    }

    @Setter
    protected void setSnapSize(int size) {
        snapSize = size;
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

        selectionRectangleLayout.setStyle("-fx-background-color: black;");

        area.setOnKeyPressed(new EventHandler<KeyEvent>() {
            @Override
            public void handle(KeyEvent event) {
                if (contextMenu != null) {
                    for (MenuItem menuItem : contextMenu.getItems()) {
                        if (menuItem != null && !menuItem.isDisable()
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
            public void handle(MouseEvent event) {
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

                if (contextMenu != null && event.getButton() == MouseButton.SECONDARY) {
                    contextMenu.show(area, event.getScreenX(), event.getScreenY());
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

        node.resize(width, height);
    }

    protected void relocateNode(Node node, double x, double y) {
        if (AnchorPane.getLeftAnchor(node) != null) {
            AnchorPane.setLeftAnchor(node, x);
        }

        if (AnchorPane.getTopAnchor(node) != null) {
            AnchorPane.setTopAnchor(node, y);
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

        for (Node node : nodes.keySet()) {
            double nx = node.getLayoutX() - getCenterX(node);
            double ny = node.getLayoutY() - getCenterY(node);
            double nw = node.getBoundsInLocal().getWidth();
            double nh = node.getBoundsInLocal().getHeight();

            Point2D center = new Point2D(x + Math.round(w / 2), y + Math.round(h / 2));
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
    public Set<Node> getNodes() {
        return nodes.keySet();
    }

    @Signature
    public void selectNode(Node node) {
        selectNode(node, null);
    }

    @Signature
    public void selectNode(Node node, MouseEvent e) {
        if (!nodes.containsKey(node)) {
            throw new RuntimeException("Node is not registered");
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
    public boolean getNodeLock(Node node) {
        if (nodes.containsKey(node)) {
            return nodes.get(node).isLocked();
        }

        return false;
    }

    @Signature
    public boolean isRegisteredNode(final Node node) {
        return nodes.containsKey(node);
    }

    @Signature
    public void registerNode(final Node node) {
        if (nodes.containsKey(node)) {
            throw new RuntimeException("Node already registered");
        }

        node.setFocusTraversable(false);

        nodes.put(node, new Item(node));

        if (node instanceof Control) {
            ((Control) node).setContextMenu(contextMenu);
        }

        node.setOnKeyPressed(area.getOnKeyPressed());

        EventHandler<MouseEvent> onDragDetected = new EventHandler<MouseEvent>() {
            public void handle(MouseEvent e) {
                if (getNodeLock(node)) {
                    return;
                }

                dragged = true;

                for (Selection selection : selections.values()) {
                    SnapshotParameters snapParams = new SnapshotParameters();
                    snapParams.setFill(Color.TRANSPARENT);

                    selection.dragImageView.setImage(selection.node.snapshot(snapParams, null));
                    selection.dragImageView.setStyle("-fx-opacity: 0.7; -fx-border-width: 1px; -fx-border-color: gray; -fx-border-style: dashed");

                    selection.parent.getChildren().add(selection.dragImageView);

                    selection.dragImageView.startFullDrag();
                }

                e.consume();
            }
        };
        //node.setOnDragDetected(onDragDetected);
        node.addEventFilter(MouseEvent.DRAG_DETECTED, onDragDetected);

        EventHandler<MouseEvent> onMouseDragged = new EventHandler<MouseEvent>() {
            public void handle(MouseEvent e) {
                if (getNodeLock(node)) {
                    return;
                }

                for (Selection sel : selections.values()) {
                    Point2D localPoint = sel.parent.sceneToLocal(new Point2D(e.getSceneX(), e.getSceneY()));

                    if (localPoint != null) {
                        double dx = localPoint.getX() - startPoint.getX();
                        double dy = localPoint.getY() - startPoint.getY();

                        double x = sel.node.getLayoutX() + dx;
                        double y = sel.node.getLayoutY() + dy;

                        if (!e.isAltDown() && snapSize > 1 && snapEnabled) {
                            x = Math.round((x / snapSize)) * snapSize;
                            y = Math.round((y / snapSize)) * snapSize;
                        }

                        sel.drag(x, y);
                    }
                }

                e.consume();
            }
        };
        //node.setOnMouseDragged(onMouseDragged);
        node.addEventFilter(MouseEvent.MOUSE_DRAGGED, onMouseDragged);

        EventHandler<MouseEvent> onMousePressed = new EventHandler<MouseEvent>() {
            public void handle(MouseEvent e) {
                Node node = (Node) e.getSource();

                if (onNodeClick != null) {
                    if (onNodeClick.callAny(e).toBoolean()) {
                        return;
                    }
                }

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
                startPoint = parent.sceneToLocal(new Point2D(e.getSceneX(), e.getSceneY()));

                for (Selection selection : selections.values()) {
                    selection.dragImageView.setMouseTransparent(true);
                    selection.drag(selection.node.getLayoutX(), selection.node.getLayoutY(), false);
                }

                if (e.getButton() == MouseButton.SECONDARY) {
                    if (contextMenu != null && !(node instanceof Control)) {
                        contextMenu.show(node, e.getScreenX(), e.getScreenY());
                    }
                }

                if (!(node instanceof TitledPane)
                        && !(node instanceof TabPane)
                        && !(node instanceof ScrollPane)
                        && !(node instanceof AnchorPane)) {
                    e.consume();
                }

                picked = node;
            }
        };

        //node.setOnMousePressed(onMousePressed);
        node.addEventFilter(MouseEvent.MOUSE_PRESSED, onMousePressed);

        EventHandler<MouseEvent> onMouseReleased = new EventHandler<MouseEvent>() {
            public void handle(MouseEvent e) {
                if (!getNodeLock(node) && dragged) {
                    for (Selection selection : selections.values()) {
                        selection.node.setMouseTransparent(false);
                        selection.node.setCursor(Cursor.DEFAULT);

                        selection.drag(selection.dragImageView.getLayoutX(), selection.dragImageView.getLayoutY(), false);
                        relocateNode(selection.node, selection.dragImageView.getLayoutX(), selection.dragImageView.getLayoutY());

                        selection.update();

                        selection.parent.getChildren().remove(selection.dragImageView);
                    }

                    if (onChanged != null) {
                        onChanged.callAny();
                    }

                    if (e.getButton() == MouseButton.PRIMARY) {
                        e.consume();
                    }
                } else {
                    for (Selection selection : selections.values()) {
                        selection.update();
                    }
                }


                if (e.getButton() == MouseButton.PRIMARY) {
                    e.consume();
                }

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

    public class Item {
        public final Node node;

        protected boolean locked = false;

        public Item(Node node) {
            this.node = node;
        }

        public boolean isLocked() {
            return locked;
        }

        public void setLocked(boolean locked) {
            this.locked = locked;
        }
    }

    public class Selection {
        public static final int POINT_SIZE = 6;
        public static final int POINT_SIZE_HALF = POINT_SIZE / 2;

        public final ImageView dragImageView = new ImageView();

        protected boolean locked = false;

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
            border.setVisible(false);
            border.setMouseTransparent(true);
            border.setFill(Color.TRANSPARENT);
            border.setStroke(Color.BLACK);
            border.getStrokeDashArray().addAll(2d);

            area.getChildren().addAll(border);

            sizeText = new Label();
            sizeText.setMouseTransparent(true);
            sizeText.setStyle("-fx-background-color: #fffe85; -fx-opacity: 0.9; -fx-padding: 1px 10px;");

            area.getChildren().addAll(sizeText);

            if (!locked) {
                bindEvents();
            }
        }

        public void setLocked(boolean locked) {
            this.locked = locked;
            rebuildHelpers();
        }

        public Node getNode() {
            return node;
        }

        public void update() {
            Bounds bounds = node.getLayoutBounds();

            Point2D point2D = parent.localToScreen(node.getLayoutX(), node.getLayoutY());

            if (point2D != null) {
                update(point2D.getX(), point2D.getY(), bounds);
            } else {
                destroy();
            }
        }

        protected void updateResized() {
            Point2D point2D = parent.localToScreen(resizeX, resizeY);

            if (point2D != null) {
                update(point2D.getX(), point2D.getY(), new BoundingBox(0, 0, resizeW, resizeH));
            } else {
                destroy();
            }
        }

        /**
         * @param x screen x
         * @param y screen y
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

            double sizeTextY = y - sizeText.getBoundsInLocal().getHeight() - POINT_SIZE;

            if (sizeTextY < 0) {
                sizeTextY = y;
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
        }

        public Rectangle buildPoint() {
            Rectangle rectangle = new Rectangle(
                    POINT_SIZE, POINT_SIZE,
                    Paint.valueOf(locked ? "gray" : "black")
            );

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

        protected int normalize(int value, MouseEvent e) {
            if (e.isAltDown() || snapSize == 1 || !snapEnabled) {
                return value;
            }

            return value - (value % snapSize);
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

            EventHandler<MouseEvent> mouseReleased = new EventHandler<MouseEvent>() {
                @Override
                public void handle(MouseEvent event) {
                    UXDesigner.this.resizing = false;

                    border.setVisible(false);
                    resizePoint = null;

                    final double centerX = getCenterX(node);
                    final double centerY = getCenterY(node);

                    Bounds bounds = node.getBoundsInLocal();

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


                    if (onChanged != null) {
                        onChanged.callAny();
                    }

                    event.consume();
                }
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

                    resizeW = normalize((int) (nodeW + (localPoint.getX() - startX)), e);
                    resizeH = normalize((int) (nodeH + (localPoint.getY() - startY)), e);

                    updateResized();
                    e.consume();
                }
            });

            rPoint.setOnMouseDragged(new EventHandler<MouseEvent>() {
                @Override
                public void handle(MouseEvent e) {
                    Point2D localPoint = parent.sceneToLocal(new Point2D(e.getSceneX(), e.getSceneY()));

                    resizeW = normalize((int) (nodeW + (localPoint.getX() - startX)), e);

                    updateResized();
                    e.consume();
                }
            });

            bPoint.setOnMouseDragged(new EventHandler<MouseEvent>() {
                @Override
                public void handle(MouseEvent e) {
                    Point2D localPoint = parent.sceneToLocal(new Point2D(e.getSceneX(), e.getSceneY()));

                    resizeH = normalize((int) (nodeH + (localPoint.getY() - startY)), e);

                    updateResized();
                    e.consume();
                }
            });

            tPoint.setOnMouseDragged(new EventHandler<MouseEvent>() {
                @Override
                public void handle(MouseEvent e) {
                    Point2D localPoint = parent.sceneToLocal(new Point2D(e.getSceneX(), e.getSceneY()));

                    resizeH = normalize(startH + (int) (startY - localPoint.getY()), e);
                    resizeY = normalize((int) (node.getLayoutY() - (resizeH - startH)), e);

                    updateResized();
                    e.consume();
                }
            });

            lPoint.setOnMouseDragged(new EventHandler<MouseEvent>() {
                @Override
                public void handle(MouseEvent e) {
                    Point2D localPoint = parent.sceneToLocal(new Point2D(e.getSceneX(), e.getSceneY()));

                    resizeW = normalize(startW + (int) (startX - localPoint.getX()), e);
                    resizeX = normalize((int) (node.getLayoutX() - (resizeW - startW)), e);

                    updateResized();

                    e.consume();
                }
            });

            rtPoint.setOnMouseDragged(new EventHandler<MouseEvent>() {
                @Override
                public void handle(MouseEvent e) {
                    Point2D localPoint = parent.sceneToLocal(new Point2D(e.getSceneX(), e.getSceneY()));

                    resizeW = normalize((int) (nodeW + (localPoint.getX() - startX)), e);

                    resizeH = normalize(startH + (int) (startY - localPoint.getY()), e);
                    resizeY = normalize((int) (node.getLayoutY() - (resizeH - startH)), e);

                    updateResized();

                    e.consume();
                }
            });

            ltPoint.setOnMouseDragged(new EventHandler<MouseEvent>() {
                @Override
                public void handle(MouseEvent e) {
                    Point2D localPoint = parent.sceneToLocal(new Point2D(e.getSceneX(), e.getSceneY()));

                    resizeW = normalize(startW + (int) (startX - localPoint.getX()), e);
                    resizeX = normalize((int) (node.getLayoutX() - (resizeW - startW)), e);

                    resizeH = normalize(startH + (int) (startY - localPoint.getY()), e);
                    resizeY = normalize((int) (node.getLayoutY() - (resizeH - startH)), e);

                    updateResized();

                    e.consume();
                }
            });

            lbPoint.setOnMouseDragged(new EventHandler<MouseEvent>() {
                @Override
                public void handle(MouseEvent e) {
                    Point2D localPoint = parent.sceneToLocal(new Point2D(e.getSceneX(), e.getSceneY()));

                    resizeW = normalize(startW + (int) (startX - localPoint.getX()), e);
                    resizeX = normalize((int) (node.getLayoutX() - (resizeW - startW)), e);

                    resizeH = normalize((int) (nodeH + (localPoint.getY() - startY)), e);

                    updateResized();

                    e.consume();
                }
            });
        }

        public void drag(double x, double y) {
            drag(x, y, false);
        }

        public void drag(double x, double y, boolean updateUi) {
            dragImageView.relocate(x - getCenterX(node), y - getCenterY(node));

            if (updateUi) {
                sizeText.setVisible(dragged && helpersEnabled);

                if (dragged) {
                    sizeText.setText("X: " + (int) x + ", Y: " + (int) y);
                }
            }
        }
    }
}
