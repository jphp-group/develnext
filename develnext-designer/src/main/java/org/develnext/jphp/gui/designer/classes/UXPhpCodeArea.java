package org.develnext.jphp.gui.designer.classes;

import org.develnext.jphp.gui.designer.GuiDesignerExtension;
import org.develnext.jphp.gui.designer.editor.syntax.impl.CssCodeArea;
import org.develnext.jphp.gui.designer.editor.syntax.impl.PhpCodeArea;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.reflection.ClassEntity;

@Reflection.Namespace(GuiDesignerExtension.NS)
public class UXPhpCodeArea<T extends PhpCodeArea> extends UXAbstractCodeArea<PhpCodeArea> {
    interface WrappedInterface {
    }

    public UXPhpCodeArea(Environment env, T wrappedObject) {
        super(env, wrappedObject);
    }

    public UXPhpCodeArea(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct() {
        __wrappedObject = new PhpCodeArea();
    }
}
