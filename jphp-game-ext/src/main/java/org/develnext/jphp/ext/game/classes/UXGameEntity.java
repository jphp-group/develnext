package org.develnext.jphp.ext.game.classes;

import javafx.scene.Node;
import org.develnext.jphp.ext.game.GameExtension;
import org.develnext.jphp.ext.game.support.GameEntity2D;
import php.runtime.annotation.Reflection.Getter;
import php.runtime.annotation.Reflection.Namespace;
import php.runtime.annotation.Reflection.Property;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.lang.BaseWrapper;
import php.runtime.reflection.ClassEntity;

@Namespace(GameExtension.NS)
public class UXGameEntity extends BaseWrapper<GameEntity2D> {
    interface WrappedInterface {
        @Property GameEntity2D.BodyType bodyType();
    }

    public UXGameEntity(Environment env, GameEntity2D wrappedObject) {
        super(env, wrappedObject);
    }

    public UXGameEntity(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct(String entityType, Node node) {
        __wrappedObject = new GameEntity2D(entityType, node);
    }

    @Getter
    public UXGameScene getGameScene(Environment env) {
        return getWrappedObject().getScene() == null ? null : new UXGameScene(env, getWrappedObject().getScene());
    }
}
