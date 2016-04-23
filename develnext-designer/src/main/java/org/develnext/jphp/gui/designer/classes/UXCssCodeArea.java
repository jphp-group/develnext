package org.develnext.jphp.gui.designer.classes;

import org.develnext.jphp.gui.designer.GuiDesignerExtension;
import org.develnext.jphp.gui.designer.editor.syntax.impl.CssCodeArea;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.reflection.ClassEntity;

@Reflection.Namespace(GuiDesignerExtension.NS)
public class UXCssCodeArea extends UXAbstractCodeArea<CssCodeArea> {
    interface WrappedInterface {
    }

    public UXCssCodeArea(Environment env, CssCodeArea wrappedObject) {
        super(env, wrappedObject);
    }

    public UXCssCodeArea(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct() {
        __wrappedObject = new CssCodeArea();
    }
}
