package org.develnext.jphp.gui.designer.classes;

import javafx.scene.control.ScrollPane;
import org.develnext.jphp.ext.javafx.classes.layout.UXRegion;
import org.develnext.jphp.ext.javafx.classes.layout.UXScrollPane;
import org.develnext.jphp.gui.designer.GuiDesignerExtension;
import org.develnext.jphp.gui.designer.editor.syntax.AbstractCodeArea;
import org.fxmisc.flowless.VirtualizedScrollPane;
import org.fxmisc.richtext.InlineCssTextArea;
import org.fxmisc.richtext.StyledTextArea;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Getter;
import php.runtime.annotation.Reflection.Namespace;
import php.runtime.annotation.Reflection.Setter;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.lang.IObject;
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
    public void __construct(Object codeArea) {
        if (codeArea instanceof StyledTextArea) {
            __wrappedObject = new VirtualizedScrollPane<>((StyledTextArea) codeArea);
        } else {
            throw new IllegalArgumentException("Invalid text area");
        }
    }

    @Getter
    public double getScrollX() {
        return getWrappedObject().estimatedScrollXProperty().getValue();
    }

    @Setter
    public void setScrollX(double value) {
        getWrappedObject().estimatedScrollXProperty().setValue(value);
    }

    @Getter
    public double getScrollY() {
        return getWrappedObject().estimatedScrollYProperty().getValue();
    }

    @Setter
    public void setScrollY(double value) {
        getWrappedObject().estimatedScrollYProperty().setValue(value);
    }
}
