package org.develnext.jphp.ext.javafx.classes;

import javafx.collections.ObservableList;
import javafx.geometry.Orientation;
import javafx.scene.Node;
import javafx.scene.control.*;
import javafx.util.Callback;
import org.develnext.jphp.ext.javafx.JavaFXExtension;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.*;
import php.runtime.env.Environment;
import php.runtime.invoke.Invoker;
import php.runtime.reflection.ClassEntity;

import java.util.List;

@Reflection.Name(JavaFXExtension.NS + "UXListView")
public class UXListView extends UXControl<ListView> {
    interface WrappedInterface {
        @Property boolean editable();
        @Property double fixedCellSize();
        @Property @Nullable Node placeholder();
        @Property Orientation orientation();
        @Property ObservableList items();
        @Property int editingIndex();

        void scrollTo(int index);
        void edit(int index);
    }

    public UXListView(Environment env, ListView wrappedObject) {
        super(env, wrappedObject);
    }

    public UXListView(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct() {
        __wrappedObject = new ListView<>();

    }

    @Getter
    public boolean getMultipleSelection() {
        return getWrappedObject().getSelectionModel().getSelectionMode() == SelectionMode.MULTIPLE;
    }

    @Setter
    public void setMultipleSelection(boolean value) {
        getWrappedObject().getSelectionModel().setSelectionMode(value ? SelectionMode.MULTIPLE : SelectionMode.SINGLE);
    }

    @Getter
    public List<Integer> getSelectedIndexes() {
        return getWrappedObject().getSelectionModel().getSelectedIndices();
    }

    @Setter
    public void setSelectedIndexes(int[] indexes) {
        MultipleSelectionModel selectionModel = getWrappedObject().getSelectionModel();

        for (int index : indexes) {
            selectionModel.select(index);
        }
    }

    @Getter
    public Object getFocusedItem() {
        return getWrappedObject().getFocusModel().getFocusedItem();
    }

    @Getter
    public List getSelectedItems() {
        return getWrappedObject().getSelectionModel().getSelectedItems();
    }

    @Getter
    public Object getFocusedIndex() {
        return getWrappedObject().getFocusModel().getFocusedIndex();
    }

    @Setter
    public void setFocusedIndex(int index) {
        getWrappedObject().getFocusModel().focus(index);
    }

    @Signature
    @SuppressWarnings("unchecked")
    public void setCellFactory(final Environment env, @Nullable final Invoker invoker) {
        if (invoker == null) {
            getWrappedObject().setCellFactory(null);
            return;
        }

        getWrappedObject().setCellFactory(new Callback<ListView, ListCell>() {
            @Override
            public ListCell call(ListView param) {
                return new ListCell() {
                    @Override
                    protected void updateItem(Object item, boolean empty) {
                        super.updateItem(item, empty);

                        invoker.callAny(new UXListCell(env, this), item, empty);
                    }
                };
            }
        });
    }
}
