package org.develnext.jphp.gui.designer.classes;

import org.develnext.jphp.gui.designer.GuiDesignerExtension;
import org.develnext.jphp.gui.designer.editor.tree.AbstractDirectoryTreeSource;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Namespace;
import php.runtime.env.Environment;
import php.runtime.lang.BaseWrapper;
import php.runtime.reflection.ClassEntity;

@Namespace(GuiDesignerExtension.NS)
abstract public class UXAbstractDirectoryTreeSource extends BaseWrapper<AbstractDirectoryTreeSource> {
    public UXAbstractDirectoryTreeSource(Environment env, AbstractDirectoryTreeSource wrappedObject) {
        super(env, wrappedObject);
    }

    public UXAbstractDirectoryTreeSource(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }
}
