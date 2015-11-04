package org.develnext.jphp.gui.designer.classes;

import javafx.scene.text.Font;
import org.develnext.jphp.ext.javafx.classes.UXNode;
import org.develnext.jphp.gui.designer.GuiDesignerExtension;
import org.develnext.jphp.gui.designer.SyntaxTextArea;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Property;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.reflection.ClassEntity;

@Reflection.Namespace(GuiDesignerExtension.NS)
public class UXSyntaxTextArea extends UXNode<SyntaxTextArea> {
    interface WrappedInterface {
        @Property String text();
        @Property String syntaxStyle();
        @Property Font font();
        @Property boolean editable();

        void jumpToLine(int line, int pos);
        void showFindDialog();
        void showReplaceDialog();

        void redo();
        void undo();
        void copy();
        void cut();
        void paste();

        boolean canUndo();
        boolean canRedo();
    }
    public UXSyntaxTextArea(Environment env, SyntaxTextArea wrappedObject) {
        super(env, wrappedObject);
    }

    public UXSyntaxTextArea(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct() {
        __wrappedObject = new SyntaxTextArea();
    }
}
