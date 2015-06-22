package org.develnext.jphp.ext.javafx.classes;

import javafx.scene.Node;
import javafx.scene.control.ContextMenu;
import javafx.scene.control.Tab;
import javafx.scene.control.Tooltip;
import org.develnext.jphp.ext.javafx.JavaFXExtension;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Nullable;
import php.runtime.annotation.Reflection.Property;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.lang.BaseWrapper;
import php.runtime.reflection.ClassEntity;

@Reflection.Name(JavaFXExtension.NS + "UXTab")
public class UXTab extends BaseWrapper<Tab> {
    interface WrappedInterface {
        @Property boolean closable();
        @Property boolean disable();
        @Property boolean disabled();

        @Property @Nullable Node content();
        @Property @Nullable Node graphic();
        @Property String id();
        @Property boolean selected();
        @Property String style();

        @Property @Nullable Tooltip tooltip();
        @Property @Nullable ContextMenu contextMenu();

        @Property String text();
    }

    public UXTab(Environment env, Tab wrappedObject) {
        super(env, wrappedObject);
    }

    public UXTab(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct() {
        __wrappedObject = new Tab();
    }

    @Signature
    public void __construct(String title) {
        __wrappedObject = new Tab(title);
    }

    @Signature
    public void __construct(String title, Node content) {
        __wrappedObject = new Tab(title, content);
    }
}
