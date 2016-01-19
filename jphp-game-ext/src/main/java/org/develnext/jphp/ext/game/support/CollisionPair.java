package org.develnext.jphp.ext.game.support;

final class CollisionPair extends Pair<GameEntity> {

    private CollisionHandler handler;

    CollisionPair(GameEntity a, GameEntity b, CollisionHandler handler) {
        // we check the order here so that we won't have to do that every time
        // when triggering collision between A and B
        // this ensures that client gets back entities in the same order
        // he registered the handler with
        super(a.isType(handler.getA()) ? a : b, b.isType(handler.getB()) ? b : a);
        this.handler = handler;
    }

    CollisionHandler getHandler() {
        return handler;
    }
}
