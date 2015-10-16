package org.develnext.jphp.ext.game;

import php.runtime.env.CompileScope;
import php.runtime.ext.support.Extension;

public class GameExtension extends Extension {
    public static final String NS = "php\\game";

    @Override
    public Status getStatus() {
        return Status.EXPERIMENTAL;
    }

    @Override
    public void onRegister(CompileScope scope) {

    }
}
