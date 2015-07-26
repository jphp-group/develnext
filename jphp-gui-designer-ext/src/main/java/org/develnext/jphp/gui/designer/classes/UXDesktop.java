package org.develnext.jphp.gui.designer.classes;

import org.develnext.jphp.gui.designer.GuiDesignerExtension;
import php.runtime.annotation.Reflection.Namespace;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.lang.BaseWrapper;
import php.runtime.reflection.ClassEntity;

import java.awt.*;
import java.io.File;
import java.io.IOException;
import java.net.URI;

@Namespace(GuiDesignerExtension.NS)
public class UXDesktop extends BaseWrapper<Desktop> {
    public UXDesktop(Environment env, Desktop wrappedObject) {
        super(env, wrappedObject);
    }

    public UXDesktop(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct() {
        __wrappedObject = Desktop.getDesktop();
    }

    @Signature
    public void open(File file) throws IOException {
        getWrappedObject().open(file);
    }

    @Signature
    public void browse(URI uri) throws IOException {
        getWrappedObject().browse(uri);
    }

    @Signature
    public void edit(File file) throws IOException {
        getWrappedObject().edit(file);
    }
}
