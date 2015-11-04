package org.develnext.jphp.ext.game.support;

import javafx.event.EventHandler;
import org.develnext.jphp.ext.javafx.support.EventProvider;

public class GameObjectEventProvider extends EventProvider<GameObject> {
    public GameObjectEventProvider() {
        setHandler("update", new Handler() {
            @Override
            public void set(GameObject target, EventHandler eventHandler) {
                target.setOnUpdate(eventHandler);
            }

            @Override
            public EventHandler get(GameObject target) {
                return target.getOnUpdate();
            }
        });

        setHandler("create", new Handler() {
            @Override
            public void set(GameObject target, EventHandler eventHandler) {
                target.setOnCreate(eventHandler);
            }

            @Override
            public EventHandler get(GameObject target) {
                return target.getOnCreate();
            }
        });

        setHandler("destroy", new Handler() {
            @Override
            public void set(GameObject target, EventHandler eventHandler) {
                target.setOnDestroy(eventHandler);
            }

            @Override
            public EventHandler get(GameObject target) {
                return target.getOnDestroy();
            }
        });
    }

    @Override
    public Class<GameObject> getTargetClass() {
        return GameObject.class;
    }
}
