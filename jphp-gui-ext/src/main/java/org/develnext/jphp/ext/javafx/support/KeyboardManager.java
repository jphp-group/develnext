package org.develnext.jphp.ext.javafx.support;

import javafx.animation.AnimationTimer;
import javafx.event.EventHandler;
import javafx.scene.input.KeyCode;
import javafx.scene.input.KeyCodeCombination;
import javafx.scene.input.KeyCombination;
import javafx.scene.input.KeyEvent;
import javafx.stage.Window;

import java.util.*;

public class KeyboardManager {
    private final Window window;
    protected boolean altDown = false;
    protected boolean controlDown = false;
    protected boolean shiftDown = false;

    protected Map<KeyCode, KeyEvent> keys = new LinkedHashMap<>();

    protected Set<KeyCombination> keyHits = new HashSet<>();
    protected boolean keyGlobalHit = false;

    protected Map<KeyCombination, EventHandler<KeyEvent>> pressHandlers = new LinkedHashMap<>();
    protected Map<KeyCombination, EventHandler<KeyEvent>> downHandlers = new LinkedHashMap<>();
    protected Map<KeyCombination, EventHandler<KeyEvent>> upHandlers = new LinkedHashMap<>();

    private final EventHandler<KeyEvent> downEventFilter;
    private final EventHandler<KeyEvent> upEventFilter;

    protected AnimationTimer timer = new AnimationTimer() {
        @Override
        public void handle(long now) {
            trigger(true);
        }
    };

    public KeyboardManager(Window window) {
        this.window = window;

        downEventFilter = new EventHandler<KeyEvent>() {
            @Override
            public void handle(KeyEvent event) {
                triggerDown(event);
            }
        };
        window.addEventFilter(KeyEvent.KEY_PRESSED, downEventFilter);

        upEventFilter = new EventHandler<KeyEvent>() {
            @Override
            public void handle(KeyEvent event) {
                triggerUp(event);
            }
        };
        window.addEventFilter(KeyEvent.KEY_RELEASED, upEventFilter);

        timer.start();
    }

    public void free() {
        window.removeEventFilter(KeyEvent.KEY_PRESSED, downEventFilter);
        window.removeEventFilter(KeyEvent.KEY_RELEASED, upEventFilter);
    }

    public void setOnPress(KeyCombination keyCode, EventHandler<KeyEvent> handler) {
        pressHandlers.put(keyCode, handler);
    }

    public EventHandler<KeyEvent> getOnPress(KeyCombination keyCode) {
        return pressHandlers.get(keyCode);
    }

    public void setOnDown(KeyCombination keyCode, EventHandler<KeyEvent> handler) {
        downHandlers.put(keyCode, handler);
    }

    public EventHandler<KeyEvent> getOnDown(KeyCombination keyCode) {
        return downHandlers.get(keyCode);
    }

    public void setOnUp(KeyCombination keyCode, EventHandler<KeyEvent> handler) {
        upHandlers.put(keyCode, handler);
    }

    public EventHandler<KeyEvent> getOnUp(KeyCombination keyCode) {
        return upHandlers.get(keyCode);
    }

    protected void triggerDown(KeyEvent event) {
        if (event.isAltDown()) {
            altDown = true;
        }

        if (event.isControlDown()) {
            controlDown = true;
        }

        if (event.isShiftDown()) {
            shiftDown = true;
        }

        keys.put(event.getCode(), event);
    }

    protected void triggerUp(KeyEvent event) {
        trigger(false);

        if (event.isAltDown()) {
            altDown = false;
        }

        if (event.isControlDown()) {
            controlDown = false;
        }

        if (event.isShiftDown()) {
            shiftDown = false;
        }

        keys.remove(event.getCode());
    }

    protected void trigger(boolean down) {
        List<KeyCombination.Modifier> modifiers = new ArrayList<>();

        if (shiftDown) {
            modifiers.add(KeyCombination.SHIFT_DOWN);
        }

        if (controlDown) {
            modifiers.add(KeyCombination.CONTROL_DOWN);

        }
        if (altDown) {
            modifiers.add(KeyCombination.ALT_DOWN);
        }

        KeyCombination.Modifier[] modifiersArray = modifiers.toArray(new KeyCombination.Modifier[modifiers.size()]);

        for (Map.Entry<KeyCode, KeyEvent> entry : keys.entrySet()) {
            KeyCode code = entry.getKey();

            KeyCombination keyCombination = new KeyCodeCombination(code, modifiersArray);

            if (down) {
                if (keyHits.add(keyCombination)) {
                    trigger(keyCombination, code, downHandlers);
                }

                if (!keyGlobalHit) {
                    keyGlobalHit = true;
                    trigger(null, code, downHandlers);
                }

                trigger(keyCombination, code, pressHandlers);
                trigger(null, code, pressHandlers);
            } else {
                keyHits.remove(keyCombination);

                trigger(keyCombination, code, upHandlers);
                trigger(null, code, upHandlers);
            }
        }
    }

    protected void trigger(KeyCombination keyCombination, KeyCode code, Map<KeyCombination, EventHandler<KeyEvent>> handlers) {
        EventHandler<KeyEvent> eventHandler = handlers.get(keyCombination);

        if (eventHandler != null) {
            KeyEvent event = new KeyEvent(
                    window, window, KeyEvent.KEY_PRESSED, null, null, code, shiftDown, controlDown, altDown, false
            );

            eventHandler.handle(event);
        }
    }
}
