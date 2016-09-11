package org.develnext.jphp.ext.javafx.support.control;

import javafx.application.Platform;
import javafx.beans.value.ChangeListener;
import javafx.beans.value.ObservableValue;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.event.Event;
import javafx.event.EventHandler;
import javafx.event.EventType;
import javafx.scene.control.ListView;

public class ListViewEx<T> extends ListView<T> {
    protected EventHandler<Event> onAction = null;

    public ListViewEx() {
        this(FXCollections.<T>observableArrayList());
    }

    public ListViewEx(ObservableList<T> items) {
        super(items);

        getSelectionModel().selectedIndexProperty().addListener(new ChangeListener<Object>() {
            @Override
            public void changed(ObservableValue observable, Object oldValue, Object newValue) {
                if (onAction != null) {
                    Platform.runLater(new Runnable() {
                        @Override
                        public void run() {
                            onAction.handle(new Event(ListViewEx.this, ListViewEx.this, EventType.ROOT));
                        }
                    });
                }
            }
        });
    }

    public EventHandler<Event> getOnAction() {
        return onAction;
    }

    public void setOnAction(EventHandler<Event> onAction) {
        this.onAction = onAction;
    }
}
