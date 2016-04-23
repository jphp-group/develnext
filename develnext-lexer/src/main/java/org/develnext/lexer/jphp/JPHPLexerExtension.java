package org.develnext.lexer.jphp;

import php.runtime.env.CompileScope;
import php.runtime.ext.support.Extension;

public class JPHPLexerExtension extends Extension {
    @Override
    public Status getStatus() {
        return Status.EXPERIMENTAL;
    }

    @Override
    public void onRegister(CompileScope scope) {

    }
}
