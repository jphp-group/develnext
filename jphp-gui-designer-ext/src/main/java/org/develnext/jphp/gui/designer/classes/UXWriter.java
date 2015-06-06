package org.develnext.jphp.gui.designer.classes;

import org.develnext.jphp.gui.designer.GuiDesignerExtension;
import php.runtime.annotation.Reflection.Namespace;
import php.runtime.env.Environment;
import php.runtime.lang.BaseObject;
import php.runtime.reflection.ClassEntity;

@Namespace(GuiDesignerExtension.NS)
public class UXWriter extends BaseObject {
    public UXWriter(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }
}
