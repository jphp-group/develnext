package org.develnext.jphp.gui.designer.classes;

import org.develnext.jphp.ext.javafx.classes.layout.UXRegion;
import org.develnext.jphp.ext.javafx.classes.layout.UXScrollPane;
import org.develnext.jphp.gui.designer.GuiDesignerExtension;
import org.develnext.jphp.gui.designer.editor.syntax.AbstractCodeArea;
import org.fxmisc.flowless.VirtualizedScrollPane;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Namespace;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.reflection.ClassEntity;

@Namespace(GuiDesignerExtension.NS)
public class UXCodeAreaScrollPane extends UXRegion<VirtualizedScrollPane<AbstractCodeArea>> {
    public UXCodeAreaScrollPane(Environment env, VirtualizedScrollPane<AbstractCodeArea> wrappedObject) {
        super(env, wrappedObject);
    }

    public UXCodeAreaScrollPane(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct(AbstractCodeArea codeArea) {
        __wrappedObject = new VirtualizedScrollPane<>(codeArea);
    }
}
