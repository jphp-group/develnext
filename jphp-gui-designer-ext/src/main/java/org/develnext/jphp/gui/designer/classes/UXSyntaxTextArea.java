package org.develnext.jphp.gui.designer.classes;

import javafx.event.EventHandler;
import javafx.geometry.Point2D;
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
        @Property int caretPosition();
        @Property int caretOffset();
        @Property int caretLine();
        @Property boolean editable();

        void jumpToLine(int line, int pos);
        void showFindDialog();
        void showReplaceDialog();
        void insertToCaret(String text);

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

    @Signature
    public double[] getCaretScreenPosition() {
        Point2D point2D = getWrappedObject().getCaretScreenPosition();

        if (point2D == null) {
            return null;
        }

        return new double[] { point2D.getX(), point2D.getY() };
    }

    public static class EventProvider extends org.develnext.jphp.ext.javafx.support.EventProvider<SyntaxTextArea> {
        public EventProvider() {
            setHandler("keyPress", new Handler() {
                @Override
                public void set(SyntaxTextArea target, EventHandler eventHandler) {
                    target.setOnKeyPress(eventHandler);
                }

                @Override
                public EventHandler get(SyntaxTextArea target) {
                    return target.getOnKeyPress();
                }
            });

            setHandler("keyDown", new Handler() {
                @Override
                public void set(SyntaxTextArea target, EventHandler eventHandler) {
                    target.setOnKeyDown(eventHandler);
                }

                @Override
                public EventHandler get(SyntaxTextArea target) {
                    return target.getOnKeyDown();
                }
            });

            setHandler("keyUp", new Handler() {
                @Override
                public void set(SyntaxTextArea target, EventHandler eventHandler) {
                    target.setOnKeyUp(eventHandler);
                }

                @Override
                public EventHandler get(SyntaxTextArea target) {
                    return target.getOnKeyUp();
                }
            });
        }

        @Override
        public Class<SyntaxTextArea> getTargetClass() {
            return SyntaxTextArea.class;
        }
    }
}
