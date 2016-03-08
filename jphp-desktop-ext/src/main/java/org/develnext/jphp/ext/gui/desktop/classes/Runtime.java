package org.develnext.jphp.ext.gui.desktop.classes;


import org.develnext.jphp.ext.gui.desktop.GuiDesktopExtension;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Namespace;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.lang.BaseObject;
import php.runtime.lang.BaseWrapper;
import php.runtime.reflection.ClassEntity;

@Namespace(GuiDesktopExtension.NS)
public class Runtime extends BaseObject {
    public Runtime(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public static int availableProcessors() {
        return java.lang.Runtime.getRuntime().availableProcessors();
    }

    @Signature
    public static long freeMemory() {
        return java.lang.Runtime.getRuntime().freeMemory();
    }

    @Signature
    public static long maxMemory() {
        return java.lang.Runtime.getRuntime().maxMemory();
    }

    @Signature
    public static long totalMemory() {
        return java.lang.Runtime.getRuntime().totalMemory();
    }
}
