package org.develnext.jphp.ext.game.classes;

import org.develnext.jphp.ext.game.GameExtension;
import org.develnext.jphp.ext.game.support.GameObject;
import org.develnext.jphp.ext.game.support.Sprite;
import org.develnext.jphp.ext.javafx.classes.UXNode;
import org.jbox2d.dynamics.BodyType;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Getter;
import php.runtime.annotation.Reflection.Nullable;
import php.runtime.annotation.Reflection.Property;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.reflection.ClassEntity;

@Reflection.Namespace(GameExtension.NS)
public class UXGameObject extends UXNode<GameObject> {
    interface WrappedInterface {
        @Property BodyType bodyType();
    }

    public UXGameObject(Environment env, GameObject wrappedObject) {
        super(env, wrappedObject);
    }

    public UXGameObject(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct() {
        __construct(null);
    }

    @Signature
    public void __construct(@Nullable Sprite sprite) {
        __wrappedObject = new GameObject(sprite);
    }

    @Getter
    public UXGameScene getGameScene(Environment env) {
        return getWrappedObject().getGameScene() == null ? null : new UXGameScene(env, getWrappedObject().getGameScene());
    }
}
