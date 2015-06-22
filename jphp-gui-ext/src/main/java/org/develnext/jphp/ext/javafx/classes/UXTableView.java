package org.develnext.jphp.ext.javafx.classes;

import javafx.collections.ObservableList;
import javafx.scene.control.TableView;
import org.develnext.jphp.ext.javafx.JavaFXExtension;
import php.runtime.annotation.Reflection.Name;
import php.runtime.annotation.Reflection.Property;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.reflection.ClassEntity;

import javax.swing.table.TableColumn;

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
}
