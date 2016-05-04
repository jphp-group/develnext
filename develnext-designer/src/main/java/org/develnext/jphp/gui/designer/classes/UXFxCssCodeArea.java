package org.develnext.jphp.gui.designer.classes;

import org.develnext.jphp.gui.designer.GuiDesignerExtension;
import org.develnext.jphp.gui.designer.editor.syntax.impl.CssCodeArea;
import org.develnext.jphp.gui.designer.editor.syntax.impl.FxCssCodeArea;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.reflection.ClassEntity;

@Reflection.Namespace(GuiDesignerExtension.NS)
public class UXFxCssCodeArea extends UXCssCodeArea<FxCssCodeArea> {
    interface WrappedInterface {
    }

    public UXFxCssCodeArea(Environment env, FxCssCodeArea wrappedObject) {
        super(env, wrappedObject);
    }

    public UXFxCssCodeArea(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct() {
        __wrappedObject = new FxCssCodeArea();
    }
}
