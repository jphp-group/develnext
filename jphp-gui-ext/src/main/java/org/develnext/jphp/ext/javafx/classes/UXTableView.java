package org.develnext.jphp.ext.javafx.classes;

import javafx.collections.ObservableList;
import javafx.scene.control.MultipleSelectionModel;
import javafx.scene.control.SelectionMode;
import javafx.scene.control.TableView;
import org.develnext.jphp.ext.javafx.JavaFXExtension;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Name;
import php.runtime.annotation.Reflection.Property;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.reflection.ClassEntity;

import javax.swing.table.TableColumn;
import java.util.List;

@Name(JavaFXExtension.NS + "UXTableView")
public class UXTableView extends UXControl<TableView> {
    interface WrappedInterface {
        @Property boolean editable();
        @Property ObservableList items();
        @Property ObservableList<TableColumn> columns();
    }

    public UXTableView(Environment env, TableView wrappedObject) {
        super(env, wrappedObject);
    }

    public UXTableView(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct() {
        __wrappedObject = new TableView<>();
    }

    @Signature
    public void update() {
        ObservableList items = getWrappedObject().getItems();

        getWrappedObject().setItems(null);
        getWrappedObject().setItems(items);
    }

    @Reflection.Getter
    public boolean getMultipleSelection() {
        return getWrappedObject().getSelectionModel().getSelectionMode() == SelectionMode.MULTIPLE;
    }

    @Reflection.Setter
    public void setMultipleSelection(boolean value) {
        getWrappedObject().getSelectionModel().setSelectionMode(value ? SelectionMode.MULTIPLE : SelectionMode.SINGLE);
    }

    @Reflection.Getter
    public List<Integer> getSelectedIndexes() {
        return getWrappedObject().getSelectionModel().getSelectedIndices();
    }

    @Reflection.Setter
    public void setSelectedIndexes(int[] indexes) {
        MultipleSelectionModel selectionModel = getWrappedObject().getSelectionModel();

        selectionModel.clearSelection();

        for (int index : indexes) {
            selectionModel.select(index);
        }
    }

    @Reflection.Getter
    public int getSelectedIndex() {
        return getWrappedObject().getSelectionModel().getSelectedIndex();
    }

    @Reflection.Setter
    public void setSelectedIndex(int index) {
        MultipleSelectionModel selectionModel = getWrappedObject().getSelectionModel();

        selectionModel.clearSelection();
        selectionModel.select(index);
    }

    @Reflection.Getter
    public Object getFocusedItem() {
        return getWrappedObject().getFocusModel().getFocusedItem();
    }

    @Reflection.Getter
    public List<Object> getSelectedItems() {
        return getWrappedObject().getSelectionModel().getSelectedItems();
    }

    @Reflection.Getter
    public Object getSelectedItem() {
        ObservableList selectedItems = getWrappedObject().getSelectionModel().getSelectedItems();
        return selectedItems.isEmpty() ? null : selectedItems.get(0);
    }

    @Reflection.Getter
    public Object getFocusedIndex() {
        return getWrappedObject().getFocusModel().getFocusedIndex();
    }

    @Reflection.Setter
    public void setFocusedIndex(int index) {
        getWrappedObject().getFocusModel().focus(index);
    }

}
