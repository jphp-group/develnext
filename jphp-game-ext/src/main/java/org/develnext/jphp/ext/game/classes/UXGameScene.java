package org.develnext.jphp.ext.game.classes;

import javafx.scene.layout.AnchorPane;
import org.develnext.jphp.ext.game.GameExtension;
import org.develnext.jphp.ext.game.support.GameScene;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Property;
import php.runtime.env.Environment;
import php.runtime.lang.BaseWrapper;
import php.runtime.reflection.ClassEntity;

@Reflection.Namespace(GameExtension.NS)
public class UXGameScene extends BaseWrapper<GameScene> {
    interface WrappedInterface {
        @Property boolean physicsEnabled();
        @Property AnchorPane content();
    }

    public UXGameScene(Environment env, GameScene wrappedObject) {
        super(env, wrappedObject);
    }

    public UXGameScene(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Reflection.Signature
    public void __construct() {
        __wrappedObject = new GameScene();
    }
}
