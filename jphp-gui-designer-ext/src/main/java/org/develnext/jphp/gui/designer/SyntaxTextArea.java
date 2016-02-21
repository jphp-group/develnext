package org.develnext.jphp.gui.designer;

import javafx.embed.swing.SwingNode;
import javafx.event.EventHandler;
import javafx.geometry.Point2D;
import javafx.scene.input.KeyCode;
import javafx.scene.input.MouseEvent;
import javafx.scene.text.Font;
import org.fife.rsta.ui.search.*;
import org.fife.ui.rsyntaxtextarea.RSyntaxTextArea;
import org.fife.ui.rsyntaxtextarea.RSyntaxTextAreaDefaultInputMap;
import org.fife.ui.rsyntaxtextarea.Theme;
import org.fife.ui.rtextarea.RTextScrollPane;
import org.fife.ui.rtextarea.SearchContext;
import org.fife.ui.rtextarea.SearchEngine;
import org.fife.ui.rtextarea.SearchResult;

import javax.swing.*;
import javax.swing.text.BadLocationException;
import java.awt.*;
import java.awt.event.KeyAdapter;
import java.awt.event.KeyEvent;
import java.awt.event.WindowAdapter;
import java.awt.event.WindowEvent;

public class SyntaxTextArea extends SwingNode implements SearchListener {
    private final static String OS = System.getProperty("os.name").toLowerCase();

    protected final RSyntaxTextArea content;
    protected final RTextScrollPane scrollPane;

    protected static java.awt.Font monoFont;
    protected static Theme theme;

    protected EventHandler<javafx.scene.input.KeyEvent> onKeyDown;
    protected EventHandler<javafx.scene.input.KeyEvent> onKeyUp;
    protected EventHandler<javafx.scene.input.KeyEvent> onKeyPress;

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

    private static KeyCode getKeyCode(int k) {
        for (KeyCode keyCode : KeyCode.values()) {
            if (keyCode.impl_getCode() == k) {
                return keyCode;
            }
        }

        return KeyCode.UNDEFINED;
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

        SwingUtilities.replaceUIInputMap(content, JComponent.WHEN_FOCUSED, new SyntaxTextAreaInputMap());

        content.addKeyListener(new KeyAdapter() {
            @Override
            public void keyTyped(KeyEvent e) {
                if (onKeyPress != null) {
                    javafx.scene.input.KeyEvent event = new javafx.scene.input.KeyEvent(
                            SyntaxTextArea.this,
                            SyntaxTextArea.this,
                            javafx.scene.input.KeyEvent.KEY_TYPED, String.valueOf(e.getKeyChar()), e.paramString(), getKeyCode(e.getKeyCode()), e.isShiftDown(), e.isControlDown(), e.isAltDown(), e.isMetaDown());
                    onKeyPress.handle(event);

                    if (event.isConsumed()) {
                        e.consume();
                    }
                }
            }

            @Override
            public void keyPressed(KeyEvent e) {
                if (onKeyDown != null) {
                    javafx.scene.input.KeyEvent event = new javafx.scene.input.KeyEvent(
                            SyntaxTextArea.this,
                            SyntaxTextArea.this,
                            javafx.scene.input.KeyEvent.KEY_PRESSED, String.valueOf(e.getKeyChar()), e.paramString(), getKeyCode(e.getKeyCode()), e.isShiftDown(), e.isControlDown(), e.isAltDown(), e.isMetaDown());
                    onKeyDown.handle(event);

                    if (event.isConsumed()) {
                        e.consume();
                    }
                }
            }

            @Override
            public void keyReleased(KeyEvent e) {
                if (onKeyUp != null) {
                    javafx.scene.input.KeyEvent event = new javafx.scene.input.KeyEvent(
                            SyntaxTextArea.this,
                            SyntaxTextArea.this,
                            javafx.scene.input.KeyEvent.KEY_RELEASED, String.valueOf(e.getKeyChar()), e.paramString(), getKeyCode(e.getKeyCode()), e.isShiftDown(), e.isControlDown(), e.isAltDown(), e.isMetaDown());
                    onKeyUp.handle(event);

                    if (event.isConsumed()) {
                        e.consume();
                    }
                }
            }
        });

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

    public EventHandler<javafx.scene.input.KeyEvent> getOnKeyPress() {
        return onKeyPress;
    }

    public void setOnKeyPress(EventHandler<javafx.scene.input.KeyEvent> onKeyPress) {
        this.onKeyPress = onKeyPress;
    }

    public EventHandler<javafx.scene.input.KeyEvent> getOnKeyDown() {
        return onKeyDown;
    }

    public void setOnKeyDown(EventHandler<javafx.scene.input.KeyEvent> onKeyDown) {
        this.onKeyDown = onKeyDown;
    }

    public EventHandler<javafx.scene.input.KeyEvent> getOnKeyUp() {
        return onKeyUp;
    }

    public void setOnKeyUp(EventHandler<javafx.scene.input.KeyEvent> onKeyUp) {
        this.onKeyUp = onKeyUp;
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
        final FindDialog findDialog = new FindDialog((Frame) null, this) {
            @Override
            public void setVisible(boolean visible) {
                if (visible) {
                    SearchComboBox old = this.findTextCombo;
                    this.findTextCombo = new SearchComboBox(null, false) {
                        @Override
                        public void addItem(Object item) {

                        }
                    };

                    super.setVisible(true);
                    this.findTextCombo = old;
                }
                else {
                    super.setVisible(false);
                }
            }
        };

        findDialog.setVisible(true);
        findDialog.setDefaultCloseOperation(WindowConstants.DISPOSE_ON_CLOSE);
        findDialog.toFront();
        findDialog.addWindowFocusListener(new WindowAdapter() {
            @Override
            public void windowLostFocus(WindowEvent e) {
                findDialog.setVisible(false);

                try {
                    SearchEngine.markAll(content, null);
                } catch (NullPointerException ex) {
                    ;
                }
            }
        });
    }

    public void showReplaceDialog() {
        final ReplaceDialog dialog = new ReplaceDialog((Frame) null, this) {
            @Override
            public void setVisible(boolean visible) {
                if (visible) {
                    SearchComboBox old = this.findTextCombo;
                    this.findTextCombo = new SearchComboBox(null, false) {
                        @Override
                        public void addItem(Object item) {

                        }
                    };

                    super.setVisible(true);
                    this.findTextCombo = old;
                }
                else {
                    super.setVisible(false);
                }
            }
        };

        dialog.setVisible(true);
        dialog.setDefaultCloseOperation(WindowConstants.DISPOSE_ON_CLOSE);
        dialog.toFront();
        dialog.addWindowListener(new WindowAdapter() {
            @Override
            public void windowLostFocus(WindowEvent e) {
                dialog.setVisible(false);
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
        String selectedText = content.getSelectedText();
        return selectedText;
    }

    public void setCaretPosition(int value) {
        content.setCaretPosition(value);
    }

    public int getCaretPosition() {
        return content.getCaretPosition();
    }

    public int getCaretLine() {
        return content.getCaretLineNumber();
    }

    public int getCaretOffset() {
        return content.getCaretOffsetFromLineStart();
    }

    public void insertToCaret(String text) {
        content.insert(text, content.getCaretPosition());
        content.setCaretPosition(content.getCaretPosition() + text.length());
    }

    public Point2D getCaretScreenPosition() {
        try {
            if (content.getCaret() == null) {
                return null;
            }

            Rectangle rectangle = content.modelToView(content.getCaret().getDot());
            Point2D p = new Point2D(rectangle.getX(), rectangle.getY());

            return localToScreen(p);
        } catch (BadLocationException e) {
            return null;
        }
    }
}
