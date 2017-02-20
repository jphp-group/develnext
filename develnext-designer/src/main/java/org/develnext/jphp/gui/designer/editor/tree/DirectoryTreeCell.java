package org.develnext.jphp.gui.designer.editor.tree;

import javafx.event.EventHandler;
import javafx.geometry.Insets;
import javafx.scene.Node;
import javafx.scene.SnapshotParameters;
import javafx.scene.canvas.Canvas;
import javafx.scene.canvas.GraphicsContext;
import javafx.scene.control.Label;
import javafx.scene.control.Tooltip;
import javafx.scene.control.TreeView;
import javafx.scene.control.cell.TextFieldTreeCell;
import javafx.scene.image.ImageView;
import javafx.scene.input.*;
import javafx.scene.paint.Color;
import javafx.scene.text.Text;
import javafx.scene.text.TextFlow;
import javafx.util.StringConverter;
import org.develnext.jphp.ext.javafx.classes.text.UXFont;
import org.develnext.jphp.ext.javafx.support.control.LabelEx;

import java.io.File;
import java.util.ArrayList;
import java.util.List;

public class DirectoryTreeCell extends TextFieldTreeCell<DirectoryTreeValue> {
    private boolean dragging = false;

    public DirectoryTreeCell() {
        super(new StringConverter<DirectoryTreeValue>() {
            private DirectoryTreeValue editItem;

            @Override
            public String toString(DirectoryTreeValue object) {
                editItem = object;
                return object.getText();
            }

            @Override
            public DirectoryTreeValue fromString(String string) {
                String path = editItem.getPath();
                return new DirectoryTreeValue(path, editItem.getCode(), string, editItem.getIcon(), editItem.getExpandIcon(), editItem.isFolder());
            }
        });

        setOnDragDetected(new EventHandler<MouseEvent>() {
            @Override
            public void handle(MouseEvent event) {
                DirectoryTreeView treeView = (DirectoryTreeView) getTreeView();

                Object dragContent = treeView.getTreeSource().getDragContent(getTreeItem().getValue().getPath());

                if (dragContent != null) {
                    Node source = (Node) event.getSource();

                    if (dragContent instanceof List) {
                        Dragboard dragboard = source.startDragAndDrop(TransferMode.MOVE);
                        SnapshotParameters parameters = new SnapshotParameters();
                        parameters.setFill(Color.TRANSPARENT);

                        Text text = new Text(getText());
                        text.setFont(getFont());

                        TextFlow textFlow = new TextFlow(text);
                        textFlow.setPadding(new Insets(3));
                        dragboard.setDragView(textFlow.snapshot(parameters, null));
                        dragboard.setDragViewOffsetY(-15);
                        dragboard.setDragViewOffsetX(-10);

                        ClipboardContent content = new ClipboardContent();
                        content.putFiles((List<File>) dragContent);

                        dragboard.setContent(content);
                    }
                }
            }
        });

        setOnDragEntered(new EventHandler<DragEvent>() {
            @Override
            public void handle(DragEvent event) {
                getTreeView().requestFocus();
                getTreeView().getFocusModel().focus(getTreeView().getRow(getTreeItem()));

                if (!getTreeItem().isExpanded()) {
                    getTreeItem().setExpanded(true);
                }
            }
        });
    }

    @Override
    public void updateItem(DirectoryTreeValue item, boolean empty) {
        super.updateItem(item, empty);

        if (empty) {
            setText(null);
            setGraphic(null);
            setTooltip(null);
        } else {
            setText(item.getText());
            setTooltip(new Tooltip(item.getText()));

            Node icon = item.getIcon();

            /*if (item.getExpandIcon() != null) {
                icon = item.getExpandIcon();
            }*/

            if (item.getExpandIcon() != null && getChildren().size() > 0) {
                if (getTreeItem().isExpanded()) {
                    icon = item.getExpandIcon();
                }
            }

            setGraphic(icon);
        }
    }
}
