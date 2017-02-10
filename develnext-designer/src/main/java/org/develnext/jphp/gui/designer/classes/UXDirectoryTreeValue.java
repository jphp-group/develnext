package org.develnext.jphp.gui.designer.classes;

import javafx.scene.Node;
import org.develnext.jphp.gui.designer.GuiDesignerExtension;
import org.develnext.jphp.gui.designer.editor.tree.DirectoryTreeValue;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Property;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.lang.BaseWrapper;
import php.runtime.reflection.ClassEntity;

@Reflection.Namespace(GuiDesignerExtension.NS)
public class UXDirectoryTreeValue extends BaseWrapper<DirectoryTreeValue> {
    interface WrappedInterface {
        @Property String path();
        @Property String code();
        @Property String text();
        @Property Node icon();
        @Property Node expandIcon();
        @Property boolean folder();
    }

    public UXDirectoryTreeValue(Environment env, DirectoryTreeValue wrappedObject) {
        super(env, wrappedObject);
    }

    public UXDirectoryTreeValue(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct(String path, String code, String text, @Reflection.Nullable  Node icon, @Reflection.Nullable Node expandIcon, boolean folder) {
        __wrappedObject = new DirectoryTreeValue(path, code, text, icon, expandIcon, folder);
    }
}
