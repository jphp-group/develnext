package org.develnext.jphp.gui.designer.editor.syntax.popup;

import javafx.collections.ListChangeListener;
import javafx.scene.control.ListView;
import javafx.stage.Popup;
import javafx.stage.WindowEvent;

public class CodeAreaPopup extends Popup {
    protected ListView<String> list = new ListView<>();

    public CodeAreaPopup() {
        addEventHandler(WindowEvent.WINDOW_SHOWING, e -> list.getSelectionModel().select(0));

        list.setFixedCellSize(24);
        list.getStyleClass().addAll("code-area-popup");

        getContent().setAll(list);

        list.getItems().addListener((ListChangeListener<String>) c -> {
            list.setPrefHeight(list.getItems().size() * list.getFixedCellSize() + 2);
        });

        //list.getItems().addAll("-fx-cursor", "-fx-font", "-fx-font-size", "-fx-font-family", "-fx-background-color");
    }

    public ListView<String> getList() {
        return list;
    }
}
