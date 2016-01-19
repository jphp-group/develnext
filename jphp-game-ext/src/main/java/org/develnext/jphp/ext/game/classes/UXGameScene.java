package org.develnext.jphp.ext.game.classes;

import javafx.scene.layout.AnchorPane;
import org.develnext.jphp.ext.game.GameExtension;
import org.develnext.jphp.ext.game.support.GameEntity;
import org.develnext.jphp.ext.game.support.GameScene;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Property;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.lang.BaseWrapper;
import php.runtime.reflection.ClassEntity;

@Reflection.Namespace(GameExtension.NS)
public class UXGameScene extends BaseWrapper<GameScene> {
    interface WrappedInterface {
        @Property boolean physicsEnabled();
        @Property AnchorPane content();

        void play();
        void pause();

        void setGravity(double x, double y);
    }

    public UXGameScene(Environment env, GameScene wrappedObject) {
        super(env, wrappedObject);
    }

    public UXGameScene(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct(AnchorPane region) {
        __wrappedObject = new GameScene(region);
    }

    @Signature
    public void add(GameEntity entity) {
        getWrappedObject().addEntity(entity);
    }

    @Signature
    public void remove(GameEntity entity) {
        getWrappedObject().removeEntity(entity);
    }
}
