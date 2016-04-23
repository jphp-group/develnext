package org.develnext.jphp.gui.designer.editor.syntax;

import java.util.*;
import java.util.function.IntFunction;

import javafx.geometry.Insets;
import javafx.scene.Node;
import javafx.scene.control.Label;
import javafx.scene.control.Tooltip;
import javafx.scene.layout.HBox;
import javafx.scene.paint.Color;
import javafx.scene.paint.Paint;
import javafx.scene.text.Font;
import javafx.scene.text.FontPosture;

import org.fxmisc.richtext.StyledTextArea;
import org.reactfx.collection.LiveList;
import org.reactfx.value.Val;

/**
 * Graphic factory that produces labels containing line numbers.
 * To customize appearance, use {@code .lineno} style class in CSS stylesheets.
 */
public class CodeAreaGutter implements IntFunction<Node> {
    private static final Insets DEFAULT_INSETS = new Insets(0.0, 5.0, 0.0, 5.0);
    private static final Paint DEFAULT_TEXT_FILL = Color.web("#666");
    private static final Font DEFAULT_FONT = Font.font("monospace", FontPosture.ITALIC, 13);
    private final StyledTextArea<?> area;

    public static CodeAreaGutter get(StyledTextArea<?> area) {
        return get(area, digits -> "%0" + digits + "d");
    }

    public static CodeAreaGutter get(StyledTextArea<?> area, IntFunction<String> format) {
        return new CodeAreaGutter(area, format);
    }

    private final Val<Integer> nParagraphs;
    private final IntFunction<String> format;
    private final Map<Integer, List<CodeAreaGutterNote>> notes = new HashMap<>();
    private int gutterNoteMax = 1;

    private CodeAreaGutter(StyledTextArea<?> area, IntFunction<String> format) {
        this.area = area;
        nParagraphs = LiveList.sizeOf(area.getParagraphs());
        this.format = format;
    }

    public CodeAreaGutter duplicate() {
        CodeAreaGutter factory = new CodeAreaGutter(area, format);
        factory.notes.putAll(notes);
        factory.gutterNoteMax = gutterNoteMax;

        return factory;
    }

    public void clearNotes() {
        notes.clear();
        gutterNoteMax = 1;
    }

    public void addNote(int line, CodeAreaGutterNote note) {
        List<CodeAreaGutterNote> noteList = notes.get(line);

        if (noteList == null) {
            notes.put(line, noteList = new ArrayList<CodeAreaGutterNote>());
        }

        noteList.add(note);

        if (noteList.size() > gutterNoteMax) {
            gutterNoteMax = noteList.size();
        }
    }

    public List<CodeAreaGutterNote> getNotes(int line) {
        List<CodeAreaGutterNote> noteList = notes.get(line);

        if (noteList == null) {
            return Collections.emptyList();
        }

        return Collections.unmodifiableList(noteList);
    }

    @Override
    public Node apply(int idx) {
        Val<String> formatted = nParagraphs.map(n -> format(idx + 1, n));

        List<CodeAreaGutterNote> notes = getNotes(idx + 1);

        Label lineNo = new Label();
        lineNo.setFont(DEFAULT_FONT);
        lineNo.setTextFill(DEFAULT_TEXT_FILL);
        lineNo.setPadding(DEFAULT_INSETS);
        lineNo.getStyleClass().add("lineno");

        // bind label's text to a Val that stops observing area's paragraphs
        // when lineNo is removed from scene
        lineNo.textProperty().bind(formatted.conditionOnShowing(lineNo));

        HBox box = new HBox(lineNo);
        box.getStyleClass().add("gutter");

        for (int i = 0; i < gutterNoteMax; i++) {
            CodeAreaGutterNote note = i <= notes.size() - 1 ? notes.get(i) : null;

            Label label = new Label();
            label.getStyleClass().add("note");

            if (note != null) {
                label.setTooltip(new Tooltip(note.getHint()));
                label.getStyleClass().addAll(note.getStyleClass());
            } else {
                label.getStyleClass().add("empty");
            }

            box.getChildren().add(label);
        }

        return box;
    }

    private String format(int x, int max) {
        int digits = (int) Math.floor(Math.log10(max)) + 1;
        return String.format(format.apply(digits), x);
    }
}
