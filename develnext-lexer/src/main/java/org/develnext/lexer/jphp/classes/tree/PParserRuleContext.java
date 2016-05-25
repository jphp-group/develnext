package org.develnext.lexer.jphp.classes.tree;

import org.antlr.v4.runtime.ParserRuleContext;
import org.antlr.v4.runtime.RuleContext;
import org.develnext.lexer.jphp.DevelNextLexerExtension;
import php.runtime.annotation.Reflection;
import php.runtime.env.Environment;
import php.runtime.lang.BaseWrapper;
import php.runtime.reflection.ClassEntity;

@Reflection.Name("ParserRuleContext")
@Reflection.Namespace(DevelNextLexerExtension.NS + "\\tree")
@Reflection.WrapInterface(value = RuleContext.class, skipConflicts = true)
public class PParserRuleContext extends PRuleContext<ParserRuleContext> {
    public PParserRuleContext(Environment env, ParserRuleContext wrappedObject) {
        super(env, wrappedObject);
    }

    public PParserRuleContext(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }
}
