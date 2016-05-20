package org.develnext.jphp.gui.designer.classes;

import javafx.scene.control.ContextMenu;
import javafx.scene.control.IndexRange;
import javafx.scene.input.KeyCode;
import javafx.scene.input.MouseButton;
import javafx.stage.PopupWindow;
import org.develnext.jphp.ext.javafx.classes.layout.UXRegion;
import org.develnext.jphp.gui.designer.GuiDesignerExtension;
import org.develnext.jphp.gui.designer.editor.syntax.popup.CodeAreaContextMenu;
import org.fxmisc.richtext.InlineCssTextArea;
import org.fxmisc.wellbehaved.event.InputMap;
import org.fxmisc.wellbehaved.event.Nodes;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.*;
import php.runtime.env.Environment;
import php.runtime.reflection.ClassEntity;

import java.util.HashMap;
import java.util.Map;

import static org.fxmisc.wellbehaved.event.EventPattern.keyPressed;
import static org.fxmisc.wellbehaved.event.EventPattern.mouseClicked;

@Namespace(GuiDesignerExtension.NS)
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


    @Override
    @Signature
    public void __construct() {
        __wrappedObject = new InlineCssTextArea();

        CodeAreaContextMenu contextMenu = new CodeAreaContextMenu(getWrappedObject());

        getWrappedObject().setOnContextMenuRequested(e -> {
            contextMenu.show(getWrappedObject(), e.getScreenX(), e.getScreenY());
        });

        Nodes.addInputMap(getWrappedObject(), InputMap.consume(keyPressed(KeyCode.ESCAPE), e -> contextMenu.hide()));
        Nodes.addInputMap(getWrappedObject(), InputMap.consume(mouseClicked(MouseButton.PRIMARY), e -> contextMenu.hide()));

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

    @Setter
    public void setPopupWindow(@Reflection.Nullable PopupWindow popupWindow) {
        getWrappedObject().setPopupWindow(popupWindow);
    }

    @Setter
    public PopupWindow getPopupWindow() {
        return getWrappedObject().getPopupWindow();
    }

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
        getWrappedObject().positionCaret(value);
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

    @Signature
    public void jumpToLine(int line, int pos) {
        getWrappedObject().moveTo(getWrappedObject().position(line, pos).toOffset());
    }

    @Signature
    public void jumpToEndLine() {
        jumpToEndLine(0, 0);
    }

    @Signature
    public void jumpToEndLine(int pos) {
        jumpToEndLine(pos, 0);
    }

    @Signature
    public void jumpToEndLine(int pos, int offset) {
        jumpToLine(getWrappedObject().getParagraphs().size() - 1 - offset, pos);
    }
}
