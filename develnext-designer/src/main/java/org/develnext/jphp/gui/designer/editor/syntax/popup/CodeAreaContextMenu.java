package org.develnext.jphp.gui.designer.editor.syntax.popup;

import javafx.event.ActionEvent;
import javafx.event.EventHandler;
import javafx.scene.control.ContextMenu;
import javafx.scene.control.MenuItem;
import javafx.scene.control.SeparatorMenuItem;
import org.develnext.jphp.gui.designer.editor.syntax.AbstractCodeArea;

public class CodeAreaContextMenu extends ContextMenu {
    protected final MenuItem pasteItem = new MenuItem("Вставить");
    protected final MenuItem copyItem = new MenuItem("Копировать");
    protected final MenuItem cutItem = new MenuItem("Вырезать");
    protected final MenuItem selectAllItem = new MenuItem("Выделить все");

    private final AbstractCodeArea codeArea;

    public CodeAreaContextMenu(AbstractCodeArea codeArea) {
        super();
        getItems().addAll(cutItem, copyItem, pasteItem, new SeparatorMenuItem(), selectAllItem);

        this.codeArea = codeArea;

        cutItem.setOnAction(event -> {
            codeArea.cut();
        });

        copyItem.setOnAction(event -> {
            codeArea.copy();
        });

        pasteItem.setOnAction(event -> {
            codeArea.paste();
        });

        selectAllItem.setOnAction(event -> {
            codeArea.selectAll();
        });
    }

    public MenuItem getPasteItem() {
        return pasteItem;
    }

    public MenuItem getCopyItem() {
        return copyItem;
    }

    public MenuItem getCutItem() {
        return cutItem;
    }

    public MenuItem getSelectAllItem() {
        return selectAllItem;
    }
}
