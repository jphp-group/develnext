package org.develnext.jphp.gui.designer.editor.syntax;

import javafx.application.Platform;
import javafx.concurrent.Task;
import javafx.event.EventHandler;
import javafx.scene.input.KeyCode;
import javafx.scene.input.KeyCombination;
import javafx.scene.input.KeyEvent;
import org.develnext.jphp.gui.designer.editor.syntax.hotkey.AbstractHotkey;
import org.develnext.jphp.gui.designer.editor.syntax.hotkey.AddTabsHotkey;
import org.develnext.jphp.gui.designer.editor.syntax.hotkey.DuplicateSelectionHotkey;
import org.develnext.jphp.gui.designer.editor.syntax.hotkey.RemoveTabsHotkey;
import org.fxmisc.richtext.CodeArea;
import org.fxmisc.richtext.StyleSpans;
import org.fxmisc.richtext.StyleSpansBuilder;
import org.fxmisc.wellbehaved.event.EventHandlerHelper;
import php.runtime.common.StringUtils;

import java.time.Duration;
import java.util.*;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;

import static org.fxmisc.wellbehaved.event.EventPattern.keyPressed;

abstract public class AbstractCodeArea extends CodeArea {
    private ExecutorService executor;

    private int tabSize;
    private boolean showGutter;
    private String stylesheet;

    private final Set<AbstractHotkey> hotkeys = new HashSet<>();
    private final Map<AbstractHotkey, EventHandler<KeyEvent>> hotkeyHandlers = new LinkedHashMap<>();

    private CodeAreaGutter gutter = CodeAreaGutter.get(this);

    public AbstractCodeArea() {
        super();
        setTabSize(2);
        setShowGutter(true);

        getStyleClass().addAll("syntax-text-area");

        executor = Executors.newSingleThreadExecutor();

        richChanges()
                .filter(ch -> !ch.getInserted().equals(ch.getRemoved())) // XXX
                .successionEnds(Duration.ofMillis(10))
                .supplyTask(this::computeHighlightingAsync)
                .awaitLatest(richChanges())
                .filterMap(t -> {
                    if (t.isSuccess()) {
                        return Optional.of(t.get());
                    } else {
                        t.getFailure().printStackTrace();
                        return Optional.empty();
                    }
                })
                .subscribe(this::applyHighlighting);


        registerHotkey(new AddTabsHotkey());
        registerHotkey(new RemoveTabsHotkey());
        registerHotkey(new DuplicateSelectionHotkey());

        setStylesheet(null);
    }

    abstract protected void computeHighlighting(StyleSpansBuilder<Collection<String>> spansBuilder, String text);

    public void registerHotkey(KeyCode keyCode, KeyCombination.Modifier[] keyModifiers, AbstractHotkey hotkey) {
        if (hotkey == null) {
            throw new NullPointerException("Hotkey is null");
        }

        if (!hotkeys.add(hotkey)) {
            throw new IllegalArgumentException("Hotkey already registered");
        }

        EventHandler<KeyEvent> handler = EventHandlerHelper.on(keyPressed(keyCode, keyModifiers)).act(keyEvent -> {
            hotkey.apply(this, keyEvent);

            if (hotkey.isAffectsUndoManager()) {
                this.getUndoManager().mark();
            }
        }).create();

        EventHandlerHelper.install(onKeyPressedProperty(), handler);
        hotkeyHandlers.put(hotkey, handler);
    }

    public void registerHotkey(AbstractHotkey hotkey) {
        registerHotkey(hotkey.getDefaultKeyCode(), hotkey.getDefaultKeyCombination(), hotkey);
    }

    public void unregisterHotkey(AbstractHotkey hotkey) {
        EventHandler<KeyEvent> handler = hotkeyHandlers.get(hotkey);

        if (handler == null) {
            throw new IllegalArgumentException("Hotkey is not registered");
        }

        EventHandlerHelper.remove(onKeyPressedProperty(), handler);
    }

    public void setStylesheet(String resource) {
        getStylesheets().clear();
        getStylesheets().add(AbstractCodeArea.class.getResource("AbstractCodeArea.css").toExternalForm());

        if (resource != null && !resource.isEmpty()) {
            getStylesheets().add(resource);
        }

        stylesheet = resource;
    }

    public String getStylesheet() {
        return stylesheet;
    }

    public void setText(String text) {
        replaceText(0, 0, text);
    }

    public int getTabSize() {
        return tabSize;
    }

    public void setTabSize(int tabSize) {
        this.tabSize = tabSize;
    }

    public boolean isShowGutter() {
        return showGutter;
    }

    public CodeAreaGutter getGutter() {
        return gutter;
    }

    public void setShowGutter(boolean showGutter) {
        this.showGutter = showGutter;
        if (showGutter) {
            setParagraphGraphicFactory(gutter);
        } else {
            setParagraphGraphicFactory(null);
        }
    }

    public void refreshGutter() {
        setParagraphGraphicFactory(null);
        gutter = getGutter().duplicate();
        setParagraphGraphicFactory(gutter);
    }

    private void applyHighlighting(StyleSpans<Collection<String>> highlighting) {
        try {
            setStyleSpans(0, highlighting);
        } catch (IllegalArgumentException e) {
            System.err.println(e.getMessage());
        }
    }

    private Task<StyleSpans<Collection<String>>> computeHighlightingAsync() {
        String text = getText();
        Task<StyleSpans<Collection<String>>> task = new Task<StyleSpans<Collection<String>>>() {
            @Override
            protected StyleSpans<Collection<String>> call() throws Exception {
                return computeHighlighting(text);
            }
        };
        executor.execute(task);
        return task;
    }

    private StyleSpans<Collection<String>> computeHighlighting(String text) {
        StyleSpansBuilder<Collection<String>> spansBuilder = new StyleSpansBuilder<>();

        getGutter().clearNotes();

        if(text.length() > 0){
            spansBuilder.add(Collections.emptyList(), 0);

            computeHighlighting(spansBuilder, text);
        } else {
            spansBuilder.add(Collections.emptyList(), 0);
        }

        Platform.runLater(this::refreshGutter);

        return spansBuilder.create();
    }
}
