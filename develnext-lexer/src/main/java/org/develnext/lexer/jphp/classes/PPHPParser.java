package org.develnext.lexer.jphp.classes;

import org.antlr.v4.runtime.CommonTokenStream;
import org.antlr.v4.runtime.Parser;
import org.develnext.lexer.jphp.DevelNextLexerExtension;
import org.develnext.lexer.jphp.classes.PAbstractParser;
import org.develnext.lexer.php.PHPParser;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.reflection.ClassEntity;

@Reflection.Name("PHPParser")
@Reflection.Namespace(DevelNextLexerExtension.NS)
public class PPHPParser extends PAbstractParser<PHPParser> {
    public PPHPParser(Environment env, PHPParser wrappedObject) {
        super(env, wrappedObject);
    }

    public PPHPParser(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct(PAbstractLexer lexer) {
        __wrappedObject = new PHPParser(new CommonTokenStream(lexer.getWrappedObject()));
    }
}
