package org.develnext.jphp.gui.designer;

import javafx.embed.swing.SwingNode;
import javafx.event.EventHandler;
import javafx.scene.input.MouseEvent;
import javafx.scene.text.Font;
import org.fife.rsta.ui.search.FindDialog;
import org.fife.rsta.ui.search.ReplaceDialog;
import org.fife.rsta.ui.search.SearchEvent;
import org.fife.rsta.ui.search.SearchListener;
import org.fife.ui.rsyntaxtextarea.RSyntaxTextArea;
import org.fife.ui.rsyntaxtextarea.Theme;
import org.fife.ui.rtextarea.RTextScrollPane;
import org.fife.ui.rtextarea.SearchContext;
import org.fife.ui.rtextarea.SearchEngine;
import org.fife.ui.rtextarea.SearchResult;

import javax.swing.*;
import javax.swing.text.BadLocationException;
import java.awt.*;
import java.awt.event.WindowAdapter;
import java.awt.event.WindowEvent;

public class SyntaxTextArea extends SwingNode implements SearchListener {
    private final static String OS = System.getProperty("os.name").toLowerCase();

    protected final RSyntaxTextArea content;
    protected final RTextScrollPane scrollPane;

    protected static java.awt.Font monoFont;
    protected static Theme theme;

    static {
        try {
            monoFont = java.awt.Font.createFont(java.awt.Font.TRUETYPE_FONT, SyntaxTextArea.class.getResourceAsStream("/Consolas.ttf"));
        } catch (Exception e) {
            monoFont = new java.awt.Font("Courier New", 0, 14);
        }

        try {
            theme = Theme.load(SyntaxTextArea.class.getResourceAsStream("/dark.xml"));
        } catch (Exception e) {
            theme = null;
        }
    }

    public SyntaxTextArea() {
        super();

        content = new RSyntaxTextArea();
        content.setAntiAliasingEnabled(true);
        content.setAnimateBracketMatching(true);
        content.setCodeFoldingEnabled(true);
        content.setAutoIndentEnabled(true);
        content.setCloseCurlyBraces(true);
        content.setClearWhitespaceLinesEnabled(true);
        content.setHighlightSecondaryLanguages(false);

        if (theme != null) {
            theme.apply(content);
        }

        if (OS.contains("win")){  // FIX, use font only for windows!
            content.setFont(monoFont.deriveFont(14f));
        }

        scrollPane = new RTextScrollPane(content);
        scrollPane.setLineNumbersEnabled(true);
        scrollPane.setFoldIndicatorEnabled(true);
        scrollPane.setIconRowHeaderEnabled(true);

        setContent(scrollPane);

        addEventFilter(MouseEvent.MOUSE_RELEASED, new EventHandler<MouseEvent>() {
            @Override
            public void handle(MouseEvent event) {
                SyntaxTextArea.this.requestFocus();
                SyntaxTextArea.this.content.requestFocusInWindow();
            }
        });
    }

    public RSyntaxTextArea getSyntaxTextArea() {
        return content;
    }

    public void setSyntaxStyle(String name) {
        content.setSyntaxEditingStyle(name);
    }

    public String getSyntaxStyle() {
        return content.getSyntaxEditingStyle();
    }

    public void setText(String text) {
        content.setText(text);
    }

    public String getText() {
        return content.getText();
    }

    public void setFont(Font font) {
        content.setFont(new java.awt.Font(font.getName(), 0, (int) font.getSize()));
    }

    public Font getFont() {
        return new Font(content.getFont().getName(), content.getFont().getSize());
    }

    public void jumpToLine(int line, int pos) throws BadLocationException {
        int offset = content.getLineStartOffset(line);
        content.setCaretPosition(offset + pos);
    }

    public void copy() {
        content.copy();
    }

    public void cut() {
        content.cut();
    }

    public void paste() {
        content.paste();
    }

    public void undo() {
        content.undoLastAction();
    }

    public boolean canUndo() {
        return content.canUndo();
    }

    public void redo() {
        content.redoLastAction();
    }

    public boolean canRedo() {
        return content.canRedo();
    }

    public void setEditable(boolean value) {
        content.setEditable(value);
    }

    public boolean isEditable() {
        return content.isEditable();
    }

    public void showFindDialog() {
        FindDialog findDialog = new FindDialog((Frame) null, this);
        findDialog.setVisible(true);
        findDialog.setDefaultCloseOperation(WindowConstants.DISPOSE_ON_CLOSE);
        findDialog.addWindowListener(new WindowAdapter() {
            @Override
            public void windowClosed(WindowEvent e) {
                try {
                    SearchEngine.markAll(content, null);
                } catch (NullPointerException ex) {
                    ;
                }
            }
        });
    }

    public void showReplaceDialog() {
        ReplaceDialog dialog = new ReplaceDialog((Frame) null, this);
        dialog.setVisible(true);
        dialog.setDefaultCloseOperation(WindowConstants.DISPOSE_ON_CLOSE);
        dialog.addWindowListener(new WindowAdapter() {
            @Override
            public void windowClosed(WindowEvent e) {
                try {
                    SearchEngine.markAll(content, null);
                } catch (NullPointerException ex) {
                    ;
                }
            }
        });
    }

    @Override
    public void searchEvent(SearchEvent e) {
        SearchEvent.Type type = e.getType();
        SearchContext context = e.getSearchContext();
        SearchResult result = null;

        switch (type) {
            default: // Prevent FindBugs warning later
            case MARK_ALL:
                result = SearchEngine.markAll(content, context);
                break;
            case FIND:
                result = SearchEngine.find(content, context);
                if (!result.wasFound()) {
                    UIManager.getLookAndFeel().provideErrorFeedback(content);
                }
                break;
            case REPLACE:
                result = SearchEngine.replace(content, context);
                if (!result.wasFound()) {
                    UIManager.getLookAndFeel().provideErrorFeedback(content);
                }
                break;
            case REPLACE_ALL:
                result = SearchEngine.replaceAll(content, context);
                JOptionPane.showMessageDialog(null, result.getCount() +
                        " occurrences replaced.");
                break;
        }
    }

    @Override
    public String getSelectedText() {
        return content.getSelectedText();
    }
}
