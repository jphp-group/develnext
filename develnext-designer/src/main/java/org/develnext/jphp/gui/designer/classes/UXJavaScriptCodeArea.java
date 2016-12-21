package org.develnext.jphp.gui.designer.classes;

import org.develnext.jphp.gui.designer.GuiDesignerExtension;
import org.develnext.jphp.gui.designer.editor.syntax.impl.JavaScriptCodeArea;
import org.develnext.jphp.gui.designer.editor.syntax.impl.PhpCodeArea;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.reflection.ClassEntity;

@Reflection.Namespace(GuiDesignerExtension.NS)
public class UXJavaScriptCodeArea<T extends JavaScriptCodeArea> extends UXAbstractCodeArea<JavaScriptCodeArea> {
    interface WrappedInterface {
    }

    public UXJavaScriptCodeArea(Environment env, T wrappedObject) {
        super(env, wrappedObject);
    }

    public UXJavaScriptCodeArea(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct() {
        __wrappedObject = new JavaScriptCodeArea();
    }
}
