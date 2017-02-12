package org.develnext.jphp.gui.designer.editor.tree;

import javafx.scene.Node;
import javafx.scene.control.Tooltip;
import javafx.scene.control.cell.TextFieldTreeCell;
import javafx.util.StringConverter;

public class DirectoryTreeCell extends TextFieldTreeCell<DirectoryTreeValue> {
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
