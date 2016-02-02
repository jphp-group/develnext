package org.develnext.jphp.ext.gui.desktop.classes;

import org.develnext.jphp.ext.gui.desktop.GuiDesktopExtension;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.lang.BaseObject;
import php.runtime.reflection.ClassEntity;

import java.awt.*;

@Reflection.Namespace(GuiDesktopExtension.NS)
public class Mouse extends BaseObject {
    public Mouse(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    private void __construct() {
    }

    @Signature
    public static long x() {
        return java.awt.MouseInfo.getPointerInfo().getLocation().x;
    }

    @Signature
    public static long y() {
        return MouseInfo.getPointerInfo().getLocation().y;
    }
}
