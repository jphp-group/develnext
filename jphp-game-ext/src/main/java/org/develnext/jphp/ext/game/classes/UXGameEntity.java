package org.develnext.jphp.ext.game.classes;

import javafx.scene.Node;
import org.develnext.jphp.ext.game.GameExtension;
import org.develnext.jphp.ext.game.support.GameEntity2D;
import org.develnext.jphp.ext.game.support.Vec2d;
import php.runtime.annotation.Reflection.*;
import php.runtime.env.Environment;
import php.runtime.lang.BaseWrapper;
import php.runtime.reflection.ClassEntity;

@Namespace(GameExtension.NS)
public class UXGameEntity extends BaseWrapper<GameEntity2D> {
    interface WrappedInterface {
        @Property GameEntity2D.BodyType bodyType();
        @Property @Nullable Vec2d gravity();

        @Property double gravityX();
        @Property double gravityY();

        @Property double velocityX();
        @Property double velocityY();

        @Property Vec2d velocity();

        @Property Vec2d angleSpeed();
        @Property double speed();
        @Property double direction();
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
