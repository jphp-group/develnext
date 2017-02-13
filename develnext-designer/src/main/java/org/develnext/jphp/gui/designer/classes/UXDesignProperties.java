package org.develnext.jphp.gui.designer.classes;

import javafx.application.Platform;
import javafx.beans.binding.Bindings;
import javafx.beans.property.SimpleObjectProperty;
import javafx.beans.property.SimpleStringProperty;
import javafx.beans.value.ChangeListener;
import javafx.beans.value.ObservableValue;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.event.EventHandler;
import javafx.geometry.Insets;
import javafx.geometry.Orientation;
import javafx.geometry.Pos;
import javafx.scene.Cursor;
import javafx.scene.Node;
import javafx.scene.control.*;
import javafx.scene.input.MouseEvent;
import javafx.scene.layout.HBox;
import javafx.scene.layout.Pane;
import javafx.scene.paint.Color;
import javafx.util.Callback;
import org.develnext.jphp.ext.javafx.classes.UXTableCell;
import org.develnext.jphp.gui.designer.GuiDesignerExtension;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Namespace;
import php.runtime.annotation.Reflection.Nullable;
import php.runtime.annotation.Reflection.Property;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.invoke.Invoker;
import php.runtime.lang.BaseObject;
import php.runtime.memory.ObjectMemory;
import php.runtime.memory.TrueMemory;
import php.runtime.reflection.ClassEntity;

import java.util.ArrayList;
import java.util.LinkedHashMap;
import java.util.List;
import java.util.Map;

@Namespace(GuiDesignerExtension.NS)
public class UXDesignProperties extends BaseObject {
    @Property
    public Object target = null;

    protected Map<String, TitledPane> groups = new LinkedHashMap<>();
    protected Map<String, ObservableList<PropertyValue>> properties = new LinkedHashMap<>();
    protected Invoker onChangeHandler;

    public UXDesignProperties(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public TitledPane addGroup(String code, String title) {
        TitledPane value = new TitledPane(title, new PropertyTableView());
        value.setAnimated(false);
        groups.put(code, value);

        return value;
    }

    @Signature
    public TitledPane getGroupPane(String code) {
        return groups.get(code);
    }

    @Signature
    public List<TitledPane> getGroupPanes() {
        ArrayList<TitledPane> result = new ArrayList<>();

        for (TitledPane titledPane : groups.values()) {
            if (((PropertyTableView)titledPane.getContent()).getItems().isEmpty()) {
                continue;
            }

            result.add(titledPane);
        }

        return result;
    }

    @Signature
    public void setColumnTitles(String nameTitle, String valueTitle) {
        for (TitledPane titledPane : groups.values()) {
            PropertyTableView content = (PropertyTableView) titledPane.getContent();

            content.nameColumn.setText(nameTitle);
            content.valueColumn.setText(valueTitle);
        }
    }

    @Signature
    public void update() {
        for (TitledPane titledPane : groups.values()) {
            PropertyTableView content = (PropertyTableView) titledPane.getContent();
            content.update();
        }
    }

    @Signature
    public UXDesignPropertyEditor getEditorByCode(String name) {
        for (TitledPane titledPane : groups.values()) {
            PropertyTableView content = (PropertyTableView) titledPane.getContent();
            UXDesignPropertyEditor editor = content.findEditorByCode(name);

            if (editor != null) {
                return editor;
            }
        }

        return null;
    }

    @Signature
    public void updateOne(String group) {
        TitledPane titledPane = groups.get(group);

        if (titledPane != null) {
            PropertyTableView content = (PropertyTableView) titledPane.getContent();
            content.update();
        }
    }

    @Signature
    public void removeGroup(String groupCode) {
        groups.remove(groupCode);
    }

    @Signature
    public void addProperty(String groupCode, String code, String name, UXDesignPropertyEditor editor) {
        ObservableList<PropertyValue> propertyValues = properties.get(code);

        if (propertyValues == null) {
            propertyValues = FXCollections.observableArrayList();
            properties.put(code, propertyValues);
        }

        editor.setCode(code);
        editor.setName(name);
        editor.setGroupCode(groupCode);
        editor.setDesignProperties(this);

        PropertyValue value = new PropertyValue(name, editor);
        propertyValues.add(value);

        TitledPane pane = groups.get(groupCode);
        PropertyTableView content = (PropertyTableView) pane.getContent();

        content.getItems().add(value);
    }

    @Signature
    public void onChange(@Nullable Invoker invoker) {
        this.onChangeHandler = invoker;
    }

    @Signature
    public void triggerChange() {
        if (onChangeHandler != null) {
            onChangeHandler.callAny();
        }
    }

    public class PropertyValue {
        protected final String name;
        protected final UXDesignPropertyEditor editor;

        public PropertyValue(String name, UXDesignPropertyEditor editor) {
            this.name = name;
            this.editor = editor;
        }

        public String getName() {
            return name;
        }

        public UXDesignPropertyEditor getEditor() {
            return editor;
        }

        public UXDesignProperties getDesignProperties() {
            return UXDesignProperties.this;
        }

        public <S, T> void update(EditingCell stEditingCell, boolean empty) {
            getEnvironment().invokeMethodNoThrow(
                    editor, "update",
                    ObjectMemory.valueOf(new UXTableCell(getEnvironment(), stEditingCell)),
                    TrueMemory.valueOf(empty)
            );
        }
    }

    public static class PropertyTableView extends TableView<PropertyValue> {
        protected final TableColumn nameColumn;
        protected final TableColumn valueColumn;


        public PropertyTableView() {
            super();

            setEditable(true);
            setFixedCellSize(20);

            setFocusTraversable(false);

            setStyle("-fx-font-size: 11px; -fx-font-family: Tahoma;");

            prefHeightProperty().bind(fixedCellSizeProperty().multiply(Bindings.size(getItems())));
            minHeightProperty().bind(prefHeightProperty());
            maxHeightProperty().bind(prefHeightProperty());

            setColumnResizePolicy(CONSTRAINED_RESIZE_POLICY);

            nameColumn = new TableColumn<>("Свойство");
            valueColumn = new TableColumn<>("Значение");

            nameColumn.setSortable(false);
            valueColumn.setSortable(false);

            nameColumn.setEditable(false);
            valueColumn.setEditable(true);

            nameColumn.setCellValueFactory(new Callback<TableColumn.CellDataFeatures, ObservableValue>() {
                @Override
                public ObservableValue call(TableColumn.CellDataFeatures param) {
                    return new SimpleStringProperty(((PropertyValue) param.getValue()).getName());
                }
            });

            valueColumn.setCellValueFactory(new Callback<TableColumn.CellDataFeatures, ObservableValue>() {
                @Override
                public ObservableValue call(TableColumn.CellDataFeatures param) {
                    return new SimpleObjectProperty(param.getValue());
                }
            });

            nameColumn.setCellFactory(new Callback<TableColumn, TableCell>() {
                @Override
                public TableCell call(TableColumn param) {
                    return new NameCell<>();
                }
            });
            valueColumn.setCellFactory(new Callback<TableColumn, TableCell>() {
                @Override
                public TableCell call(TableColumn param) {
                    return new EditingCell<>();
                }
            });

            widthProperty().addListener(new ChangeListener<Number>() {
                @Override
                public void changed(ObservableValue<? extends Number> observable, Number oldValue, Number newValue) {
                    //Don't show header
                    Pane header = (Pane) lookup("TableHeaderRow");

                    if (header.isVisible()){
                        header.setMaxHeight(0);
                        header.setMinHeight(0);
                        header.setPrefHeight(0);
                        header.setVisible(false);
                    }
                }
            });

            getColumns().addAll(nameColumn, valueColumn);
        }

        public void update() {
            List<PropertyValue> items = new ArrayList<>(getItems());

            getItems().clear();

            for (PropertyValue item : items) {
                getItems().add(item);
            }
        }

        public UXDesignPropertyEditor findEditorByCode(String name) {
            for (PropertyValue value : getItems()) {
                if (name.equals(value.getEditor().getCode())) {
                    return value.getEditor();
                }
            }

            return null;
        }
    }

    static class NameCell<S, T> extends TableCell<S, T> {
        @Override
        protected void updateItem(T item, boolean empty) {
            super.updateItem(item, empty);

            setAlignment(Pos.BASELINE_RIGHT);
            setPadding(new Insets(1, 5, 1, 1));
            setText(item == null ? null : item.toString());
            setTextFill(Color.GRAY.darker());
        }
    }

    static class EditingCell<S, T> extends TableCell<S, T> {
        public EditingCell() {
            super();
            setPadding(new Insets(0));
        }

        @Override
        public void updateItem(final T item, final boolean empty) {
            super.updateItem(item, empty);

            setText(null);

            PropertyValue value = (PropertyValue) item;

            if (value != null) {
                value.update(this, empty);
            }
        }
    }
}
