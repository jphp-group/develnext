package org.develnext.jphp.ext.desktop.hotkey.classes;

import com.tulskiy.keymaster.common.HotKeyListener;
import com.tulskiy.keymaster.common.MediaKey;
import com.tulskiy.keymaster.common.Provider;
import javafx.application.Platform;
import org.develnext.jphp.ext.desktop.hotkey.HotkeyExtension;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Namespace;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.common.StringUtils;
import php.runtime.env.Environment;
import php.runtime.invoke.Invoker;
import php.runtime.lang.BaseObject;
import php.runtime.reflection.ClassEntity;

import javax.swing.*;
import java.util.HashMap;
import java.util.Map;

@Namespace(HotkeyExtension.NS)
public class HotKeyManager extends BaseObject {
    private final static Map<String, String> keyAliases = new HashMap<>();

    protected Provider provider;

    public HotKeyManager(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct() {
        provider = Provider.getCurrentProvider(false);
    }

    public static KeyStroke getKeyStroke(String keys) {
        String[] strings = StringUtils.split(keys, '+');

        StringBuilder sb = new StringBuilder();

        for (int i = 0; i < strings.length; i++) {
            String key = strings[i].trim();

            if (key.isEmpty()) {
                continue;
            }

            if (i == strings.length - 1) {
                key = key.toUpperCase();
            } else {
                key = key.toLowerCase();
            }

            if (keyAliases.containsKey(key)) {
                key = keyAliases.get(key);
            }

            sb.append(key).append(" ");
        }

        return KeyStroke.getKeyStroke(sb.toString().trim());
    }

    @Signature
    public void register(String keys, Invoker callback) {
        KeyStroke keyStroke = getKeyStroke(keys);

        if (keyStroke == null) {
            throw new IllegalArgumentException("Invalid key stroke: " + keys);
        }

        provider.register(keyStroke, hotKey -> Platform.runLater(callback::callAny));
    }

    @Signature
    public void registerMedia(MediaKey mediaKey, Invoker callback) {
        provider.register(mediaKey, hotKey -> Platform.runLater(callback::callAny));
    }

    @Signature
    public void reset() {
        provider.reset();
    }

    @Signature
    public void __destruct() {
        provider.stop();
    }

    static {
        keyAliases.put("DEL", "DELETE");
        keyAliases.put("ESC", "ESCAPE");
        keyAliases.put("CTRL", "CONTROL");
        keyAliases.put("CAPSLOCK", "CAPS_LOCK");
        keyAliases.put("BACKSPACE", "BACK_SPACE");
    }
}
