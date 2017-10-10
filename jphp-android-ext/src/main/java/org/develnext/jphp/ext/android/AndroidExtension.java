package org.develnext.jphp.ext.android;

import php.runtime.env.CompileScope;
import php.runtime.ext.support.Extension;

public class AndroidExtension extends Extension {
    @Override
    public Status getStatus() {
        return Status.EXPERIMENTAL;
    }

    @Override
    public void onRegister(CompileScope compileScope) {

    }
}
