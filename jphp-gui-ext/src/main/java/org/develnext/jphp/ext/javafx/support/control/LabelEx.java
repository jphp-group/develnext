package org.develnext.jphp.ext.javafx.support.control;

import javafx.application.Platform;
import javafx.beans.property.BooleanProperty;
import javafx.beans.property.SimpleBooleanProperty;
import javafx.beans.value.ChangeListener;
import javafx.beans.value.ObservableValue;
import javafx.scene.Node;
import javafx.scene.control.Label;
import javafx.scene.text.Font;
import org.develnext.jphp.ext.javafx.classes.text.UXFont;

public class LabelEx extends Label {
    protected BooleanProperty autoSize;

    public LabelEx() {
        this("");
    }

    public LabelEx(String text) {
        this(text, null);
    }

    public LabelEx(String text, Node graphic) {
        super(text, graphic);

        textProperty().addListener(new ChangeListener<String>() {
            @Override
            public void changed(ObservableValue<? extends String> observable, String oldValue, String newValue) {
                LabelEx.this.updateAutoSize();
            }
        });

        fontProperty().addListener(new ChangeListener<Font>() {
            @Override
            public void changed(ObservableValue<? extends Font> observable, Font oldValue, Font newValue) {
                LabelEx.this.updateAutoSize();
            }
        });

        prefWidthProperty().addListener(new ChangeListener<Number>() {
            @Override
            public void changed(ObservableValue<? extends Number> observable, Number oldValue, Number newValue) {
                LabelEx.this.updateAutoSize();
            }
        });

        prefHeightProperty().addListener(new ChangeListener<Number>() {
            @Override
            public void changed(ObservableValue<? extends Number> observable, Number oldValue, Number newValue) {
                LabelEx.this.updateAutoSize();
            }
        });
    }

    void updateAutoSize() {
        if (isAutoSize()) {
            Platform.runLater(new Runnable() {
                @Override
                public void run() {
                    setPrefWidth(UXFont.calculateTextWidth(getText(), LabelEx.this.getFont()));
                    setPrefHeight(UXFont.getLineHeight(LabelEx.this.getFont()));
                }
            });
        }
    }

    public final BooleanProperty autoSizeProperty() {
        if (autoSize == null) {
            autoSize = new SimpleBooleanProperty(this, "autoSize", false);
        }

        return autoSize;
    }

    public final void setAutoSize(boolean value) {
        autoSizeProperty().setValue(value);
        updateAutoSize();
    }

    public final boolean isAutoSize() {
        return autoSize == null ? false : autoSize.getValue();
    }
}
