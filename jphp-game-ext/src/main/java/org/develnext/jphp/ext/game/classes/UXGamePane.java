package org.develnext.jphp.ext.game.classes;

import javafx.scene.Node;
import org.develnext.jphp.ext.game.GameExtension;
import org.develnext.jphp.ext.game.support.GamePane;
import org.develnext.jphp.ext.javafx.classes.layout.UXScrollPane;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Namespace;
import php.runtime.annotation.Reflection.Property;
import php.runtime.env.Environment;
import php.runtime.reflection.ClassEntity;

@Namespace(GameExtension.NS)
public class UXGamePane extends UXScrollPane<GamePane> {
    interface WrappedInterface {
        @Property("scrollY") double vvalue();
        @Property("scrollX") double hvalue();

        @Property Node watchingNode();
        void scrollTo(Node node);
    }

    public UXGamePane(Environment env, GamePane wrappedObject) {
        super(env, wrappedObject);
    }

    public UXGamePane(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Override
    public void __construct() {
        __wrappedObject = new GamePane();
    }

    @Override
    public void __construct(@Reflection.Nullable Node content) {
        throw new IllegalStateException();
    }
}
