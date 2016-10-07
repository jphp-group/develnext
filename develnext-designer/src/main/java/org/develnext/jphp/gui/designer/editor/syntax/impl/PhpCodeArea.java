package org.develnext.jphp.gui.designer.editor.syntax.impl;

import javafx.application.Platform;
import org.antlr.v4.runtime.*;
import org.develnext.jphp.core.compiler.jvm.JvmCompiler;
import org.develnext.jphp.core.syntax.SyntaxAnalyzer;
import org.develnext.jphp.core.tokenizer.TokenMeta;
import org.develnext.jphp.core.tokenizer.Tokenizer;
import org.develnext.jphp.core.tokenizer.token.*;
import org.develnext.jphp.core.tokenizer.token.Token;
import org.develnext.jphp.core.tokenizer.token.expr.BraceExprToken;
import org.develnext.jphp.core.tokenizer.token.expr.CommaToken;
import org.develnext.jphp.core.tokenizer.token.expr.OperatorExprToken;
import org.develnext.jphp.core.tokenizer.token.expr.operator.*;
import org.develnext.jphp.core.tokenizer.token.expr.value.*;
import org.develnext.jphp.core.tokenizer.token.stmt.StmtToken;
import org.develnext.jphp.gui.designer.editor.syntax.AbstractCodeArea;
import org.develnext.jphp.gui.designer.editor.syntax.CodeAreaGutterNote;
import org.develnext.lexer.php.PHPLexer;
import org.develnext.lexer.php.PHPParser;
import org.fxmisc.richtext.model.StyleSpansBuilder;
import php.runtime.env.Context;
import php.runtime.env.Environment;
import php.runtime.env.handler.ExceptionHandler;
import php.runtime.exceptions.ParseException;
import php.runtime.lang.exception.BaseError;
import php.runtime.lang.exception.BaseParseError;

import java.io.File;
import java.io.IOException;
import java.util.Collection;
import java.util.Collections;


public class PhpCodeArea extends AbstractCodeArea {
    private final BaseErrorListener errorListener = new BaseErrorListener() {
        @Override
        public void syntaxError(Recognizer<?, ?> recognizer, Object offendingSymbol, int line, int charPositionInLine, String msg, RecognitionException e) {
            getGutter().addNote(line, new CodeAreaGutterNote("error", msg));
        }
    };
    private Environment environment = new Environment();

    public PhpCodeArea() {
        super();
        setStylesheet(AbstractCodeArea.class.getResource("PhpCodeArea.css").toExternalForm());
    }

    private static Collection<String> getStyleOfToken(Token token) {
        if (token instanceof SemicolonToken) {
            return Collections.singletonList("semicolon");
        }

        if (token instanceof ColonToken || token instanceof CommaToken || token instanceof BraceExprToken) {
            return Collections.singletonList("control");
        }

        if (token instanceof CommentToken) {
            return Collections.singletonList("comment");
        }

        if (token instanceof StringExprToken) {
            return Collections.singletonList("string");
        }

        if (token instanceof IntegerExprToken || token instanceof DoubleExprToken) {
            return Collections.singletonList("number");
        }

        if (token instanceof VariableExprToken) {
            return Collections.singletonList("variable");
        }

        if (token instanceof StmtToken
                || token instanceof BooleanExprToken || token instanceof NullExprToken || token instanceof NewExprToken
                || token instanceof SelfExprToken || token instanceof StaticExprToken || token instanceof ParentExprToken
                || token instanceof EmptyExprToken || token instanceof IssetExprToken || token instanceof DieExprToken
                || token instanceof UnsetExprToken || token instanceof InstanceofExprToken || token instanceof CloneExprToken
                || token instanceof BooleanAnd2ExprToken || token instanceof BooleanOr2ExprToken || token instanceof BooleanXorExprToken
                || token instanceof OpenTagToken) {
            return Collections.singletonList("keyword");
        }

        if (token instanceof NameToken) {
            switch (token.getWord().toLowerCase()) {
                case "array":
                    return Collections.singletonList("keyword");
            }
        }

        if (token instanceof OperatorExprToken) {
            return Collections.singletonList("operator");
        }

        return Collections.emptyList();
    }

    protected Thread lastCheckThread = null;

    @Override
    protected void computeHighlighting(StyleSpansBuilder<Collection<String>> spansBuilder, String text) {
        Tokenizer tokenizer;
        try {
            tokenizer = new Tokenizer(new Context(text));
        } catch (IOException e) {
            return;
        }

        //ANTLRInputStream inputStream = new ANTLRInputStream(text);
        //PHPLexer lex = new PHPLexer(inputStream);
        //lex.addErrorListener(errorListener);

        int lastEnd = 0;
        Token token;
        while ((token = tokenizer.nextToken()) != null) {
            TokenMeta meta = token.getMeta();
            int startIndex = meta.getStartIndex();

            /*if (token.getType() == PHPParser.Comment) {
                if (text.charAt(startIndex - 1) == '#') {
                    startIndex -= 1;
                } else if (text.charAt(startIndex - 1) == '/' && text.charAt(startIndex - 2) == '/') {
                    startIndex -= 2;
                }
            }*/

            int spacer = startIndex - lastEnd;

            if (spacer > 0) {
                spansBuilder.add(Collections.emptyList(), spacer);
            }

            Collection<String> styleOfToken = getStyleOfToken(token);

            int gap = meta.getEndIndex() - startIndex;
            spansBuilder.add(styleOfToken, gap);

            lastEnd = meta.getEndIndex();
        }

        Thread thread = lastCheckThread = new Thread() {
            @Override
            public void run() {
                try {
                    Thread.sleep(1000);
                } catch (InterruptedException e) {
                    return;
                }

                if (lastCheckThread != this) {
                    return;
                }

                try {
                    Environment env = environment;
                    SyntaxAnalyzer analyzer = new SyntaxAnalyzer(env, new Tokenizer(new Context(text, new File("source.php"))));
                    analyzer.getTree();
                } catch (IOException e) {
                    // throw new RuntimeException(e);
                } catch (BaseError e) {
                    Platform.runLater(() ->
                                    getGutter().addNote(
                                            e.getLine(environment).toInteger(),
                                            new CodeAreaGutterNote("error", e.getMessage(environment).toString() + " at pos " + e.getPosition(environment).toInteger())
                                    )
                    );

                    Platform.runLater(PhpCodeArea.this::refreshGutter);
                } catch (Throwable e) {
                    ;
                }

                /*lex.reset();
                PHPParser cssParser = new PHPParser(new CommonTokenStream(lex));
                cssParser.addErrorListener(errorListener);
                cssParser.htmlDocument();*/
            }
        };
        thread.start();
    }
}