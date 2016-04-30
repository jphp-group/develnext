package org.develnext.lexer.php;

import org.antlr.v4.runtime.ANTLRFileStream;
import org.antlr.v4.runtime.CommonTokenStream;
import org.develnext.lexer.AbstractProgramEnvironment;

import java.io.IOException;
import java.util.Stack;

public class PhpProgramEnvironment extends AbstractProgramEnvironment<PhpProgramModule> {
    @Override
    public PhpProgramModule makeFromPath(String path) throws IOException {
        PhpProgramModule module = new PhpProgramModule();

        PHPLexer phpLexer = new PHPLexer(new ANTLRFileStream(path, "UTF-8"));
        PHPParser phpParser = new PHPParser(new CommonTokenStream(phpLexer));

        PHPParserBaseListener listener = new PHPParserBaseListener() {
            protected final Stack<PhpProgramFunction> functions = new Stack<>();

            @Override
            public void enterFunctionDeclaration(PHPParser.FunctionDeclarationContext ctx) {
                super.enterFunctionDeclaration(ctx);

                PhpProgramFunction function = new PhpProgramFunction();
                function.setName(ctx.identifier().getText());

                for (PHPParser.FormalParameterContext param : ctx.formalParameterList().formalParameter()) {
                    String name = param.getText();
                    PHPParser.TypeHintContext typeHint = param.typeHint();
                }

                functions.push(function);
            }

            @Override
            public void exitFunctionDeclaration(PHPParser.FunctionDeclarationContext ctx) {
                super.exitFunctionDeclaration(ctx);

                PhpProgramFunction function = functions.pop();
                module.addEntry(function);
            }
        };

        phpParser.addParseListener(listener);
        phpParser.htmlDocument();

        return module;
    }
}
