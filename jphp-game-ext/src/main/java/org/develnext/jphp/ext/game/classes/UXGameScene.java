package org.develnext.jphp.ext.game.classes;

import org.develnext.jphp.ext.game.GameExtension;
import org.develnext.jphp.ext.game.support.GameEntity2D;
import org.develnext.jphp.ext.game.support.GameScene2D;
import org.develnext.jphp.ext.game.support.Vec2d;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Property;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.lang.BaseWrapper;
import php.runtime.reflection.ClassEntity;

@Reflection.Namespace(GameExtension.NS)
public class UXGameScene extends BaseWrapper<GameScene2D> {
    interface WrappedInterface {
        @Property Vec2d gravity();

        void play();
        void pause();
    }

    public UXGameScene(Environment env, GameScene2D wrappedObject) {
        super(env, wrappedObject);
    }

    public UXGameScene(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct() {
        __wrappedObject = new GameScene2D();
    }

    @Signature
    public void add(GameEntity2D entity) {
        getWrappedObject().addEntity(entity);
    }

    @Signature
    public void remove(GameEntity2D entity) {
        getWrappedObject().removeEntity(entity);
    }
}
