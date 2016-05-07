package org.develnext.jphp.gui.designer.classes;

import javafx.event.EventHandler;
import javafx.geometry.Point2D;
import javafx.stage.PopupWindow;
import org.develnext.jphp.ext.javafx.classes.UXControl;
import org.develnext.jphp.ext.javafx.classes.layout.UXRegion;
import org.develnext.jphp.gui.designer.GuiDesignerExtension;
import org.develnext.jphp.gui.designer.SyntaxTextArea;
import org.develnext.jphp.gui.designer.editor.syntax.AbstractCodeArea;
import org.fxmisc.richtext.Paragraph;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.*;
import php.runtime.env.Environment;
import php.runtime.lang.BaseWrapper;
import php.runtime.reflection.ClassEntity;

import java.util.Collection;

@Abstract
@Namespace(GuiDesignerExtension.NS)
public class UXAbstractCodeArea<T extends AbstractCodeArea> extends UXRegion<AbstractCodeArea> {
    public static class EventProvider extends org.develnext.jphp.ext.javafx.support.EventProvider<AbstractCodeArea> {
        public EventProvider() {
            setHandler("beforeChange", new Handler() {
                @Override
                public void set(AbstractCodeArea target, EventHandler eventHandler) {
                    target.setOnBeforeChange(eventHandler);
                }

                @Override
                public EventHandler get(AbstractCodeArea target) {
                    return target.getOnBeforeChange();
                }
            });

            setHandler("afterChange", new Handler() {
                @Override
                public void set(AbstractCodeArea target, EventHandler eventHandler) {
                    target.setOnAfterChange(eventHandler);
                }

                @Override
                public EventHandler get(AbstractCodeArea target) {
                    return target.getOnAfterChange();
                }
            });
        }

        @Override
        public Class<AbstractCodeArea> getTargetClass() {
            return AbstractCodeArea.class;
        }
    }

    interface WrappedInterface {
        @Property int tabSize();
        @Property boolean showGutter();
        @Property @Nullable PopupWindow popupWindow();

        void showPopup();
        void hidePopup();
    }

    public UXAbstractCodeArea(Environment env, T wrappedObject) {
        super(env, wrappedObject);
    }

    public UXAbstractCodeArea(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Override
    public AbstractCodeArea getWrappedObject() {
        return super.getWrappedObject();
    }

    @Getter
    public String getSelectedText() {
        return getWrappedObject().getSelectedText();
    }

    @Setter
    public void setSelectedText(String value) {
        getWrappedObject().replaceSelection(value);
    }

    @Getter
    public String getText() {
        return getWrappedObject().getText();
    }

    @Setter
    public void setText(String text) {
        getWrappedObject().setText(text);
    }

    @Getter
    public boolean getEditable() {
        return getWrappedObject().isEditable();
    }

    @Setter
    public void setEditable(boolean value) {
        getWrappedObject().setEditable(value);
    }

    @Getter
    public int getCaretOffset() {
        return getWrappedObject().getCaretColumn();
    }

    @Getter
    public int getCaretLine() {
        return getWrappedObject().getCurrentParagraph();
    }

    @Getter
    public int getCaretPosition() {
        return getWrappedObject().getCaretPosition();
    }

    @Setter
    public void setCaretPosition(int value) {
        getWrappedObject().moveTo(value);
    }

    @Signature
    public void undo() {
        getWrappedObject().undo();
    }

    @Signature
    public void redo() {
        getWrappedObject().redo();
    }

    @Signature
    public void cut() {
        getWrappedObject().cut();
    }

    @Signature
    public void copy() {
        getWrappedObject().copy();
    }

    @Signature
    public void paste() {
        getWrappedObject().paste();
    }

    @Signature
    public boolean canUndo() {
        return getWrappedObject().isUndoAvailable();
    }

    @Signature
    public boolean canRedo() {
        return getWrappedObject().isRedoAvailable();
    }

    @Signature
    public void jumpToLine(int line, int pos) {
        getWrappedObject().moveTo(getWrappedObject().position(line, pos).toOffset());
    }

    @Signature
    public void jumpToLineSpaceOffset(int line) {
        int pos = 0;

        if (getWrappedObject().getParagraphs().size() < line + 1) {
            return;
        }

        Paragraph<Collection<String>, Collection<String>> paragraph = getWrappedObject().getParagraph(line);

        String text = paragraph.getText();

        for (int i = 0; i < text.length(); i++) {
            if (!Character.isSpaceChar(text.charAt(i))) {
                break;
            }

            pos++;
        }

        getWrappedObject().moveTo(getWrappedObject().position(line, pos).toOffset());
    }

    @Signature
    public void insertToCaret(String text) {
        getWrappedObject().insertText(getWrappedObject().getCaretPosition(), text);
    }

    @Signature
    public void select(int position, int length) {
        getWrappedObject().selectRange(position, length);
    }

    @Signature
    public void selectAll() {
        getWrappedObject().selectAll();
    }
}
