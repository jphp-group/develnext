package org.develnext.lexer.jphp;

import org.antlr.v4.runtime.CommonToken;
import org.antlr.v4.runtime.Lexer;
import org.antlr.v4.runtime.Token;
import org.antlr.v4.runtime.tree.pattern.RuleTagToken;
import org.antlr.v4.runtime.tree.pattern.TokenTagToken;
import org.develnext.lexer.css.CSSLexer;
import org.develnext.lexer.jphp.classes.*;
import org.develnext.lexer.json.JSONLexer;
import org.develnext.lexer.php.PHPLexer;
import php.runtime.env.CompileScope;
import php.runtime.ext.support.Extension;
import php.runtime.memory.support.MemoryOperation;

public class DevelNextLexerExtension extends Extension {
    public static final String NS = "develnext\\lexer";

    @Override
    public Status getStatus() {
        return Status.EXPERIMENTAL;
    }

    @Override
    public void onRegister(CompileScope scope) {
        registerWrapperClass(scope, Token.class, PToken.class);
        MemoryOperation.registerWrapper(CommonToken.class, PToken.class);
        MemoryOperation.registerWrapper(RuleTagToken.class, PToken.class);
        MemoryOperation.registerWrapper(TokenTagToken.class, PToken.class);

        registerWrapperClass(scope, Lexer.class, PAbstractLexer.class);
        registerWrapperClass(scope, JSONLexer.class, PJSONLexer.class);
        registerWrapperClass(scope, CSSLexer.class, PCSSLexer.class);
        registerWrapperClass(scope, PHPLexer.class, PPHPLexer.class);
    }
}
