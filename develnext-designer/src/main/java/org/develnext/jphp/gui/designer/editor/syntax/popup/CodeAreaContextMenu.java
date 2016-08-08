package org.develnext.jphp.gui.designer.editor.syntax.popup;

import javafx.event.ActionEvent;
import javafx.event.EventHandler;
import javafx.scene.control.ContextMenu;
import javafx.scene.control.MenuItem;
import javafx.scene.control.SeparatorMenuItem;
import javafx.scene.input.KeyCombination;
import org.develnext.jphp.gui.designer.editor.syntax.AbstractCodeArea;
import org.fxmisc.richtext.StyledTextArea;

public class CodeAreaContextMenu extends ContextMenu {
    protected final MenuItem pasteItem = new MenuItem("Paste");
    protected final MenuItem copyItem = new MenuItem("Copy");
    protected final MenuItem cutItem = new MenuItem("Cut");
    protected final MenuItem selectAllItem = new MenuItem("Select All");

    private final StyledTextArea codeArea;

    public CodeAreaContextMenu(StyledTextArea codeArea) {
        super();
        getItems().addAll(cutItem, copyItem, pasteItem, new SeparatorMenuItem(), selectAllItem);

        this.codeArea = codeArea;

        cutItem.setAccelerator(KeyCombination.valueOf("Ctrl + X"));
        cutItem.setOnAction(event -> {
            codeArea.cut();
        });

        copyItem.setAccelerator(KeyCombination.valueOf("Ctrl + C"));
        copyItem.setOnAction(event -> {
            codeArea.copy();
        });

        pasteItem.setAccelerator(KeyCombination.valueOf("Ctrl + V"));
        pasteItem.setOnAction(event -> {
            codeArea.paste();
        });


        selectAllItem.setAccelerator(KeyCombination.valueOf("Ctrl + A"));
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
