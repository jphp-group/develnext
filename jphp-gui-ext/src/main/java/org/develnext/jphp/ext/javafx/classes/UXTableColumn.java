package org.develnext.jphp.ext.javafx.classes;

import javafx.beans.property.SimpleStringProperty;
import javafx.beans.value.ObservableValue;
import javafx.scene.Node;
import javafx.scene.control.TableCell;
import javafx.scene.control.TableColumn;
import javafx.scene.control.TableColumnBase;
import javafx.util.Callback;
import org.develnext.jphp.ext.javafx.JavaFXExtension;
import php.runtime.Memory;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Nullable;
import php.runtime.annotation.Reflection.Property;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.invoke.Invoker;
import php.runtime.lang.BaseWrapper;
import php.runtime.memory.ObjectMemory;
import php.runtime.reflection.ClassEntity;
import php.runtime.reflection.support.ReflectionUtils;

@Reflection.Name(JavaFXExtension.NS + "UXTableColumn")
public class UXTableColumn extends BaseWrapper<TableColumnBase> {
    interface WrappedInterface {
        @Property
        int width();

        @Property
        int maxWidth();

        @Property
        int minWidth();

        @Property
        boolean editable();

        @Property
        boolean resizable();

        @Property
        boolean sortable();

        @Property
        String text();

        @Property
        String style();

        @Property
        String id();

        @Property
        Node graphic();

        @Property
        boolean visible();
    }

    public UXTableColumn(Environment env, TableColumn wrappedObject) {
        super(env, wrappedObject);
    }

    public UXTableColumn(Environment env, TableColumnBase wrappedObject) {
        super(env, wrappedObject);
    }

    public UXTableColumn(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct() {
        __wrappedObject = new TableColumn();
    }

    @Signature
    @SuppressWarnings("unchecked")
    public void setCellValueFactory(final Environment env, @Nullable final Invoker invoker) {
        if (invoker == null) {
            ((TableColumn) getWrappedObject()).setCellValueFactory(null);
            return;
        }

        ((TableColumn) getWrappedObject()).setCellValueFactory(new Callback<TableColumn.CellDataFeatures, ObservableValue>() {
            @Override
            public ObservableValue call(TableColumn.CellDataFeatures param) {
                Memory result = invoker.callNoThrow(
                        Memory.wrap(env, param.getValue()),
                        ObjectMemory.valueOf(new UXTableColumn(env, param.getTableColumn()))
                );

                return new SimpleStringProperty(result.toString());
            }
        });
    }

    @Signature
    @SuppressWarnings("unchecked")
    public void setCellFactory(final Environment env, @Nullable final Invoker invoker) {
        if (invoker == null) {
            ((TableColumn) getWrappedObject()).setCellFactory(null);
            return;
        }

        ((TableColumn) getWrappedObject()).setCellFactory(new Callback<TableColumn, TableCell>() {
            @Override
            public TableCell call(TableColumn param) {
                Memory result = invoker.callNoThrow(
                        ObjectMemory.valueOf(new UXTableColumn(env, param))
                );

                if (!result.instanceOf(UXTableCell.class)) {
                    env.exception("The factory of tableColumn must return an instance of " + ReflectionUtils.getClassName(UXTableCell.class));
                    return null;
                }

                return result.toObject(UXTableCell.class).getWrappedObject();
            }
        });
    }
}
