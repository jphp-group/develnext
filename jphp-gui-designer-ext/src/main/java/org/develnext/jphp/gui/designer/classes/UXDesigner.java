package org.develnext.jphp.gui.designer.classes;

import javafx.beans.value.ChangeListener;
import javafx.beans.value.ObservableValue;
import javafx.collections.ObservableList;
import javafx.event.EventHandler;
import javafx.geometry.BoundingBox;
import javafx.geometry.Bounds;
import javafx.geometry.Point2D;
import javafx.scene.Cursor;
import javafx.scene.Node;
import javafx.scene.SnapshotParameters;
import javafx.scene.canvas.Canvas;
import javafx.scene.canvas.GraphicsContext;
import javafx.scene.control.Label;
import javafx.scene.image.ImageView;
import javafx.scene.input.MouseEvent;
import javafx.scene.layout.Pane;
import javafx.scene.layout.Region;
import javafx.scene.paint.Color;
import javafx.scene.paint.Paint;
import javafx.scene.shape.Rectangle;
import org.develnext.jphp.gui.designer.GuiDesignerExtension;
import php.runtime.annotation.Reflection.Getter;
import php.runtime.annotation.Reflection.Namespace;
import php.runtime.annotation.Reflection.Setter;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.lang.BaseObject;
import php.runtime.reflection.ClassEntity;

import java.util.Collection;
import java.util.LinkedHashMap;
import java.util.Map;
import java.util.Set;

@Namespace(GuiDesignerExtension.NS)
public class UXDesigner extends BaseObject {
    private Pane area;

    private Point2D startPoint;

    protected int snapSize = 8;
    protected boolean snapEnabled = true;
    protected boolean helpersEnabled = true;

    protected boolean dragged = false;
    protected boolean resizing = false;

    private Canvas dots;

    private Map<Node, Item> nodes = new LinkedHashMap<>();
    private Map<Node, Selection> selections = new LinkedHashMap<>();

    public UXDesigner(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    protected void updateHelpers() {
        if (dots != null) {
            area.getChildren().removeAll(dots);
        }

        if (helpersEnabled) {
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
    public void __construct(Pane area) {
        this.area = area;
        ChangeListener<Number> resizeListener = new ChangeListener<Number>() {
            @Override
            public void changed(ObservableValue<? extends Number> observable, Number oldValue, Number newValue) {
                updateHelpers();
            }
        };

        area.widthProperty().addListener(resizeListener);
        area.heightProperty().addListener(resizeListener);
    }

    @Signature
    public Set<Node> getSelectedNodes() {
        return null;
    }

    @Signature
    public Collection<Node> getNodes() {
        return nodes.keySet();
    }

    @Signature
    public void selectNode(Node node) {
        if (!nodes.containsKey(node)) {
            throw new RuntimeException("Node is not registered");
        }

        Selection selection = selections.get(node);

        if (selection == null) {
            selections.put(node, selection = new Selection(node));
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
    public void registerNode(final Node node) {
        if (nodes.containsKey(node)) {
            throw new RuntimeException("Node already registered");
        }

        nodes.put(node, new Item(node));

        node.setOnDragDetected(new EventHandler<MouseEvent>() {
            public void handle(MouseEvent e) {
                if (getNodeLock(node)) {
                    return;
                }

                dragged = true;

                for (Selection selection : selections.values()) {
                    SnapshotParameters snapParams = new SnapshotParameters();
                    snapParams.setFill(Color.TRANSPARENT);

                    selection.dragImageView.setImage(selection.node.snapshot(snapParams, null));
                    selection.dragImageView.setStyle("-fx-opacity: 0.7");

                    area.getChildren().add(selection.dragImageView);

                    selection.dragImageView.startFullDrag();
                }

                e.consume();
            }
        });

        node.setOnMouseDragged(new EventHandler<MouseEvent>() {
            public void handle(MouseEvent e) {
                if (getNodeLock(node)) {
                    return;
                }

                Point2D localPoint = area.sceneToLocal(new Point2D(e.getSceneX(), e.getSceneY()));

                double dx = localPoint.getX() - startPoint.getX();
                double dy = localPoint.getY() - startPoint.getY();

                for (Selection sel : selections.values()) {
                    double x = sel.node.getLayoutX() + dx;
                    double y = sel.node.getLayoutY() + dy;

                    if (!e.isAltDown() && snapSize > 1 && snapEnabled) {
                        x = Math.round((x / snapSize)) * snapSize;
                        y = Math.round((y / snapSize)) * snapSize;
                    }

                    sel.drag(x, y);
                }

                e.consume();
            }
        });

        node.setOnMousePressed(new EventHandler<MouseEvent>() {
            public void handle(MouseEvent e) {
                Node node = (Node) e.getSource();

                if (!dragged) {
                    boolean isSelected = selections.get(node) != null;

                    {
                        if (!isSelected && !e.isShiftDown()) {
                            UXDesigner.this.unselectAll();
                        }

                        if (!isSelected) {
                            UXDesigner.this.selectNode(node);
                        } else {
                            if (e.isShiftDown()) {
                                UXDesigner.this.unselectNode(node);
                            }
                        }
                    }
                }

                startPoint = area.sceneToLocal(new Point2D(e.getSceneX(), e.getSceneY()));

                for (Selection selection : selections.values()) {
                    selection.dragImageView.setMouseTransparent(true);
                    selection.dragImageView.relocate(selection.node.getLayoutX(), selection.node.getLayoutY());
                }
            }
        });

        node.setOnMouseReleased(new EventHandler<MouseEvent>() {
            public void handle(MouseEvent e) {
                if (!getNodeLock(node) && dragged) {
                    for (Selection selection : selections.values()) {
                        selection.node.setMouseTransparent(false);
                        selection.node.setCursor(Cursor.DEFAULT);

                        selection.node.relocate(selection.dragImageView.getLayoutX(), selection.dragImageView.getLayoutY());

                        selection.update();

                        area.getChildren().remove(selection.dragImageView);
                    }
                }

                dragged = false;
            }
        });
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

        protected int resizeX = 0;
        protected int resizeY = 0;
        protected int resizeW = 0;
        protected int resizeH = 0;

        protected final Node node;

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

            area.getChildren().addAll(ltPoint, lbPoint, rtPoint, rbPoint);
            area.getChildren().addAll(tPoint, bPoint, lPoint, rPoint);

            border = new Rectangle();
            border.setVisible(false);
            border.setMouseTransparent(true);
            border.setFill(Color.TRANSPARENT);
            border.setStroke(Color.GRAY);
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
            update(node.getLayoutX(), node.getLayoutY(), node.getLayoutBounds());
        }

        protected void updateResized() {
            update(resizeX, resizeY, new BoundingBox(0, 0, resizeW, resizeH));
        }

        public void update(double x, double y, Bounds bounds) {
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

            if (resizing) {
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

                    startX = (int) node.getLayoutX();
                    startY = (int) node.getLayoutY();

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

                    node.relocate(resizeX, resizeY);

                    if (node instanceof Region) {
                        ((Region) node).setPrefSize(resizeW, resizeH);
                    }

                    event.consume();
                }
            };

            tPoint.setOnMousePressed(mousePressed);
            bPoint.setOnMousePressed(mousePressed);
            lPoint.setOnMousePressed(mousePressed);
            rPoint.setOnMousePressed(mousePressed);
            lbPoint.setOnMousePressed(mousePressed);
            ltPoint.setOnMousePressed(mousePressed);
            rbPoint.setOnMousePressed(mousePressed);
            rtPoint.setOnMousePressed(mousePressed);

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
                    Point2D localPoint = area.sceneToLocal(new Point2D(e.getSceneX(), e.getSceneY()));

                    resizeW = normalize((int) (localPoint.getX() - startX - POINT_SIZE_HALF), e);
                    resizeH = normalize((int) (localPoint.getY() - startY - POINT_SIZE_HALF), e);

                    updateResized();
                }
            });

            rPoint.setOnMouseDragged(new EventHandler<MouseEvent>() {
                @Override
                public void handle(MouseEvent e) {
                    Point2D localPoint = area.sceneToLocal(new Point2D(e.getSceneX(), e.getSceneY()));

                    resizeW = normalize((int) (localPoint.getX() - startX - POINT_SIZE_HALF), e);

                    updateResized();
                }
            });

            bPoint.setOnMouseDragged(new EventHandler<MouseEvent>() {
                @Override
                public void handle(MouseEvent e) {
                    Point2D localPoint = area.sceneToLocal(new Point2D(e.getSceneX(), e.getSceneY()));

                    resizeH = normalize((int) (localPoint.getY() - startY - POINT_SIZE_HALF), e);

                    updateResized();
                }
            });

            tPoint.setOnMouseDragged(new EventHandler<MouseEvent>() {
                @Override
                public void handle(MouseEvent e) {
                    Point2D localPoint = area.sceneToLocal(new Point2D(e.getSceneX(), e.getSceneY()));

                    resizeH = normalize(startH + (int) (startY - localPoint.getY() - POINT_SIZE_HALF), e);
                    resizeY = normalize(startY - (resizeH - startH - POINT_SIZE_HALF), e);

                    updateResized();
                }
            });

            lPoint.setOnMouseDragged(new EventHandler<MouseEvent>() {
                @Override
                public void handle(MouseEvent e) {
                    Point2D localPoint = area.sceneToLocal(new Point2D(e.getSceneX(), e.getSceneY()));

                    resizeW = normalize(startW + (int) (startX - localPoint.getX() - POINT_SIZE_HALF), e);
                    resizeX = normalize(startX - (resizeW - startW - POINT_SIZE_HALF), e);

                    updateResized();
                }
            });

            rtPoint.setOnMouseDragged(new EventHandler<MouseEvent>() {
                @Override
                public void handle(MouseEvent e) {
                    Point2D localPoint = area.sceneToLocal(new Point2D(e.getSceneX(), e.getSceneY()));

                    resizeW = normalize((int) (localPoint.getX() - startX - POINT_SIZE_HALF), e);

                    resizeH = normalize(startH + (int) (startY - localPoint.getY() - POINT_SIZE_HALF), e);
                    resizeY = normalize(startY - (resizeH - startH - POINT_SIZE_HALF), e);

                    updateResized();
                }
            });

            ltPoint.setOnMouseDragged(new EventHandler<MouseEvent>() {
                @Override
                public void handle(MouseEvent e) {
                    Point2D localPoint = area.sceneToLocal(new Point2D(e.getSceneX(), e.getSceneY()));

                    resizeW = normalize(startW + (int) (startX - localPoint.getX() - POINT_SIZE_HALF), e);
                    resizeX = normalize(startX - (resizeW - startW - POINT_SIZE_HALF), e);

                    resizeH = normalize(startH + (int) (startY - localPoint.getY() - POINT_SIZE_HALF), e);
                    resizeY = normalize(startY - (resizeH - startH - POINT_SIZE_HALF), e);

                    updateResized();
                }
            });

            lbPoint.setOnMouseDragged(new EventHandler<MouseEvent>() {
                @Override
                public void handle(MouseEvent e) {
                    Point2D localPoint = area.sceneToLocal(new Point2D(e.getSceneX(), e.getSceneY()));

                    resizeW = normalize(startW + (int) (startX - localPoint.getX() - POINT_SIZE_HALF), e);
                    resizeX = normalize(startX - (resizeW - startW - POINT_SIZE_HALF), e);

                    resizeH = normalize((int) (localPoint.getY() - startY - POINT_SIZE_HALF), e);

                    updateResized();
                }
            });
        }

        public void drag(double x, double y) {
            dragImageView.relocate(x, y);

            sizeText.setVisible(dragged);

            if (dragged) {
                sizeText.setText("X: " + (int) x + ", Y: " + (int) y);
            }
        }
    }
}
