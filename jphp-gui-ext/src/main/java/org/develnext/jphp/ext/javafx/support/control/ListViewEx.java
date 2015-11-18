package org.develnext.jphp.ext.javafx.support.control;

import javafx.beans.value.ChangeListener;
import javafx.beans.value.ObservableValue;
import javafx.collections.ObservableList;
import javafx.event.Event;
import javafx.event.EventHandler;
import javafx.event.EventType;
import javafx.scene.control.ListView;

public class ListViewEx<T> extends ListView<T> {
    protected EventHandler<Event> onAction = null;

    public ListViewEx() {
        super();
    }

    public ListViewEx(ObservableList<T> items) {
        super(items);

        getSelectionModel().selectedIndexProperty().addListener(new ChangeListener<Number>() {
            @Override
            public void changed(ObservableValue<? extends Number> observable, Number oldValue, Number newValue) {
                if (onAction != null) {
                    onAction.handle(new Event(ListViewEx.this, ListViewEx.this, EventType.ROOT));
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
