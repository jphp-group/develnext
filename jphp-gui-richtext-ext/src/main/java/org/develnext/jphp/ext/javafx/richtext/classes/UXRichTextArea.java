package org.develnext.jphp.ext.javafx.richtext.classes;

import javafx.scene.control.IndexRange;
import javafx.stage.PopupWindow;
import org.develnext.jphp.ext.javafx.classes.layout.UXRegion;
import org.develnext.jphp.ext.javafx.richtext.RichTextExtension;
import org.fxmisc.richtext.InlineCssTextArea;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.*;
import php.runtime.env.Environment;
import php.runtime.reflection.ClassEntity;

import java.util.HashMap;
import java.util.Map;

@Reflection.Namespace(RichTextExtension.NS)
public class UXRichTextArea extends UXRegion<InlineCssTextArea> {
    interface WrappedInterface {
        @Property boolean useInitialStyleForInsertion();
        @Property String text();
    }

    public UXRichTextArea(Environment env, InlineCssTextArea wrappedObject) {
        super(env, wrappedObject);
    }

    public UXRichTextArea(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct() {
        __wrappedObject = new InlineCssTextArea();
    }

    @Getter
    public boolean getWrapText() {
        return getWrappedObject().isWrapText();
    }

    @Setter
    public void setWrapText(boolean value) {
        getWrappedObject().setWrapText(value);
    }

    @Signature
    public void clear() {
        getWrappedObject().clear();
    }

    /*@Setter
    public void setPopupWindow(@Nullable PopupWindow popupWindow) {
        getWrappedObject().setPopupWindow(popupWindow);
    }

    @Setter
    public PopupWindow getPopupWindow() {
        return getWrappedObject().getPopupWindow();
    }*/

    @Signature
    public void selectLine() {
        getWrappedObject().selectLine();
    }

    @Signature
    public void selectAll() {
        getWrappedObject().selectAll();
    }

    @Signature
    public void selectRange(int anchor, int caret) {
        getWrappedObject().selectRange(anchor, caret);
    }

    @Getter
    public int getCaretPosition() {
        return getWrappedObject().getCaretPosition();
    }

    @Setter
    public void setCaretPosition(int value) {
        getWrappedObject().moveTo(value);
    }

    @Getter
    public String getSelectedText() {
        return getWrappedObject().getSelectedText();
    }

    @Setter
    public void setSelectedText(String text) {
        getWrappedObject().replaceSelection(text);
    }

    @Getter
    public Map<String, Integer> getSelection() {
        IndexRange selection = getWrappedObject().getSelection();
        HashMap<String, Integer> result = new HashMap<>();
        result.put("start", selection.getStart());
        result.put("end", selection.getEnd());
        result.put("length", selection.getLength());

        return result;
    }

    @Signature
    public void insertText(int index, String text) {
        getWrappedObject().insertText(index, text);
    }

    @Signature
    public void appendText(String text) {
        appendText(text, null);
    }

    @Signature
    public void appendText(String text, String style) {
        int length = getWrappedObject().getLength();
        getWrappedObject().appendText(text);

        if (style != null && !style.trim().isEmpty()) {
            getWrappedObject().setStyle(length, length + text.length(), style);
        }
    }

    @Signature
    public void clearStyle(int from, int to) {
        getWrappedObject().clearStyle(from, to);
    }

    @Signature
    public void clearStyleOfParagraph(int paragraph) {
        getWrappedObject().clearStyle(paragraph);
    }

    @Signature
    public void setStyle(int from, int to, String style) {
        getWrappedObject().setStyle(from, to, style);
    }

    @Signature
    public void setStyleOfParagraph(int paragraph, String style) {
        getWrappedObject().setStyle(paragraph, style);
    }

    @Signature
    public String getStyleAtPosition(int position) {
        return getWrappedObject().getStyleAtPosition(position);
    }

    @Signature
    public String getStyleAtParagraph(int paragraph, int offset) {
        return getWrappedObject().getStyleAtPosition(paragraph, offset);
    }
}
