package org.develnext.jphp.ext.desktop.hotkey;

import org.develnext.jphp.ext.desktop.hotkey.classes.HotKeyManager;
import php.runtime.env.CompileScope;
import php.runtime.ext.support.Extension;

public class HotkeyExtension extends Extension {
    public static final String NS = "php\\desktop";

    @Override
    public Status getStatus() {
        return Status.EXPERIMENTAL;
    }

    @Override
    public String[] getPackageNames() {
        return new String[] { "gui", "desktop" };
    }

    @Override
    public void onRegister(CompileScope scope) {
        registerClass(scope, HotKeyManager.class);
    }
}
