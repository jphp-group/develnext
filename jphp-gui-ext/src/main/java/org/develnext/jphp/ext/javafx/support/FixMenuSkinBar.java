package org.develnext.jphp.ext.javafx.support;

import javafx.scene.control.MenuBar;
import javafx.scene.control.skin.MenuBarSkin;

import java.lang.reflect.Field;

public class FixMenuSkinBar extends MenuBarSkin {
    public FixMenuSkinBar(MenuBar control) {
        super(control);

        try {
            Field field = null;
            field = getClass().getSuperclass().getDeclaredField("firstMenuRunnable");

            field.setAccessible(true);
            field.set(this, new Runnable() {
                @Override
                public void run() {
                    ;
                }
            });
        } catch (NoSuchFieldException | IllegalAccessException e) {
            // nop
        }
    }
}
