package org.develnext.jphp.ext.javafx.classes;

import javafx.fxml.FXMLLoader;
import org.develnext.jphp.ext.javafx.JavaFXExtension;
import php.runtime.Memory;
import php.runtime.annotation.Reflection.Name;
import php.runtime.annotation.Reflection.Property;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.lang.BaseWrapper;
import php.runtime.reflection.ClassEntity;

import java.io.IOException;
import java.io.InputStream;
import java.net.URL;

@Name(JavaFXExtension.NS + "UXLoader")
public class UXLoader extends BaseWrapper<FXMLLoader> {
    interface WrappedInterface {
        @Property URL location();
    }

    public UXLoader(Environment env, FXMLLoader wrappedObject) {
        super(env, wrappedObject);
    }

    public UXLoader(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct() {
        __wrappedObject = new FXMLLoader();
    }

    @Signature
    public void __construct(URL source) {
        __wrappedObject = new FXMLLoader(source);
    }

    @Signature
    public Memory load(Environment env) throws IOException {
        return Memory.wrap(env, getWrappedObject().load());
    }

    @Signature
    public Memory load(Environment env, InputStream stream) throws IOException {
        return Memory.wrap(env, getWrappedObject().load(stream));
    }
}
