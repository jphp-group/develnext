// Generated from org\develnext\lexer\php\PHPParser.g4 by ANTLR 4.5.3
package org.develnext.lexer.php;
import org.antlr.v4.runtime.tree.ParseTreeListener;

/**
 * This interface defines a complete listener for a parse tree produced by
 * {@link PHPParser}.
 */
public interface PHPParserListener extends ParseTreeListener {
	/**
	 * Enter a parse tree produced by {@link PHPParser#htmlDocument}.
	 * @param ctx the parse tree
	 */
	void enterHtmlDocument(PHPParser.HtmlDocumentContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#htmlDocument}.
	 * @param ctx the parse tree
	 */
	void exitHtmlDocument(PHPParser.HtmlDocumentContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#htmlElementOrPhpBlock}.
	 * @param ctx the parse tree
	 */
	void enterHtmlElementOrPhpBlock(PHPParser.HtmlElementOrPhpBlockContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#htmlElementOrPhpBlock}.
	 * @param ctx the parse tree
	 */
	void exitHtmlElementOrPhpBlock(PHPParser.HtmlElementOrPhpBlockContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#htmlElement}.
	 * @param ctx the parse tree
	 */
	void enterHtmlElement(PHPParser.HtmlElementContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#htmlElement}.
	 * @param ctx the parse tree
	 */
	void exitHtmlElement(PHPParser.HtmlElementContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#htmlContent}.
	 * @param ctx the parse tree
	 */
	void enterHtmlContent(PHPParser.HtmlContentContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#htmlContent}.
	 * @param ctx the parse tree
	 */
	void exitHtmlContent(PHPParser.HtmlContentContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#htmlAttribute}.
	 * @param ctx the parse tree
	 */
	void enterHtmlAttribute(PHPParser.HtmlAttributeContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#htmlAttribute}.
	 * @param ctx the parse tree
	 */
	void exitHtmlAttribute(PHPParser.HtmlAttributeContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#htmlQuotePhpBlockOrString}.
	 * @param ctx the parse tree
	 */
	void enterHtmlQuotePhpBlockOrString(PHPParser.HtmlQuotePhpBlockOrStringContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#htmlQuotePhpBlockOrString}.
	 * @param ctx the parse tree
	 */
	void exitHtmlQuotePhpBlockOrString(PHPParser.HtmlQuotePhpBlockOrStringContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#htmlDoubleQuotePhpBlockOrString}.
	 * @param ctx the parse tree
	 */
	void enterHtmlDoubleQuotePhpBlockOrString(PHPParser.HtmlDoubleQuotePhpBlockOrStringContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#htmlDoubleQuotePhpBlockOrString}.
	 * @param ctx the parse tree
	 */
	void exitHtmlDoubleQuotePhpBlockOrString(PHPParser.HtmlDoubleQuotePhpBlockOrStringContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#scriptText}.
	 * @param ctx the parse tree
	 */
	void enterScriptText(PHPParser.ScriptTextContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#scriptText}.
	 * @param ctx the parse tree
	 */
	void exitScriptText(PHPParser.ScriptTextContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#scriptTextPart}.
	 * @param ctx the parse tree
	 */
	void enterScriptTextPart(PHPParser.ScriptTextPartContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#scriptTextPart}.
	 * @param ctx the parse tree
	 */
	void exitScriptTextPart(PHPParser.ScriptTextPartContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#phpBlock}.
	 * @param ctx the parse tree
	 */
	void enterPhpBlock(PHPParser.PhpBlockContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#phpBlock}.
	 * @param ctx the parse tree
	 */
	void exitPhpBlock(PHPParser.PhpBlockContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#importStatement}.
	 * @param ctx the parse tree
	 */
	void enterImportStatement(PHPParser.ImportStatementContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#importStatement}.
	 * @param ctx the parse tree
	 */
	void exitImportStatement(PHPParser.ImportStatementContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#topStatement}.
	 * @param ctx the parse tree
	 */
	void enterTopStatement(PHPParser.TopStatementContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#topStatement}.
	 * @param ctx the parse tree
	 */
	void exitTopStatement(PHPParser.TopStatementContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#useDeclaration}.
	 * @param ctx the parse tree
	 */
	void enterUseDeclaration(PHPParser.UseDeclarationContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#useDeclaration}.
	 * @param ctx the parse tree
	 */
	void exitUseDeclaration(PHPParser.UseDeclarationContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#useDeclarationContentList}.
	 * @param ctx the parse tree
	 */
	void enterUseDeclarationContentList(PHPParser.UseDeclarationContentListContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#useDeclarationContentList}.
	 * @param ctx the parse tree
	 */
	void exitUseDeclarationContentList(PHPParser.UseDeclarationContentListContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#useDeclarationContent}.
	 * @param ctx the parse tree
	 */
	void enterUseDeclarationContent(PHPParser.UseDeclarationContentContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#useDeclarationContent}.
	 * @param ctx the parse tree
	 */
	void exitUseDeclarationContent(PHPParser.UseDeclarationContentContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#namespaceDeclaration}.
	 * @param ctx the parse tree
	 */
	void enterNamespaceDeclaration(PHPParser.NamespaceDeclarationContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#namespaceDeclaration}.
	 * @param ctx the parse tree
	 */
	void exitNamespaceDeclaration(PHPParser.NamespaceDeclarationContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#namespaceStatement}.
	 * @param ctx the parse tree
	 */
	void enterNamespaceStatement(PHPParser.NamespaceStatementContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#namespaceStatement}.
	 * @param ctx the parse tree
	 */
	void exitNamespaceStatement(PHPParser.NamespaceStatementContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#functionDeclaration}.
	 * @param ctx the parse tree
	 */
	void enterFunctionDeclaration(PHPParser.FunctionDeclarationContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#functionDeclaration}.
	 * @param ctx the parse tree
	 */
	void exitFunctionDeclaration(PHPParser.FunctionDeclarationContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#classDeclaration}.
	 * @param ctx the parse tree
	 */
	void enterClassDeclaration(PHPParser.ClassDeclarationContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#classDeclaration}.
	 * @param ctx the parse tree
	 */
	void exitClassDeclaration(PHPParser.ClassDeclarationContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#classEntryType}.
	 * @param ctx the parse tree
	 */
	void enterClassEntryType(PHPParser.ClassEntryTypeContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#classEntryType}.
	 * @param ctx the parse tree
	 */
	void exitClassEntryType(PHPParser.ClassEntryTypeContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#interfaceList}.
	 * @param ctx the parse tree
	 */
	void enterInterfaceList(PHPParser.InterfaceListContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#interfaceList}.
	 * @param ctx the parse tree
	 */
	void exitInterfaceList(PHPParser.InterfaceListContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#typeParameterListInBrackets}.
	 * @param ctx the parse tree
	 */
	void enterTypeParameterListInBrackets(PHPParser.TypeParameterListInBracketsContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#typeParameterListInBrackets}.
	 * @param ctx the parse tree
	 */
	void exitTypeParameterListInBrackets(PHPParser.TypeParameterListInBracketsContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#typeParameterList}.
	 * @param ctx the parse tree
	 */
	void enterTypeParameterList(PHPParser.TypeParameterListContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#typeParameterList}.
	 * @param ctx the parse tree
	 */
	void exitTypeParameterList(PHPParser.TypeParameterListContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#typeParameterWithDefaultsList}.
	 * @param ctx the parse tree
	 */
	void enterTypeParameterWithDefaultsList(PHPParser.TypeParameterWithDefaultsListContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#typeParameterWithDefaultsList}.
	 * @param ctx the parse tree
	 */
	void exitTypeParameterWithDefaultsList(PHPParser.TypeParameterWithDefaultsListContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#typeParameterDecl}.
	 * @param ctx the parse tree
	 */
	void enterTypeParameterDecl(PHPParser.TypeParameterDeclContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#typeParameterDecl}.
	 * @param ctx the parse tree
	 */
	void exitTypeParameterDecl(PHPParser.TypeParameterDeclContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#typeParameterWithDefaultDecl}.
	 * @param ctx the parse tree
	 */
	void enterTypeParameterWithDefaultDecl(PHPParser.TypeParameterWithDefaultDeclContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#typeParameterWithDefaultDecl}.
	 * @param ctx the parse tree
	 */
	void exitTypeParameterWithDefaultDecl(PHPParser.TypeParameterWithDefaultDeclContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#genericDynamicArgs}.
	 * @param ctx the parse tree
	 */
	void enterGenericDynamicArgs(PHPParser.GenericDynamicArgsContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#genericDynamicArgs}.
	 * @param ctx the parse tree
	 */
	void exitGenericDynamicArgs(PHPParser.GenericDynamicArgsContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#attributes}.
	 * @param ctx the parse tree
	 */
	void enterAttributes(PHPParser.AttributesContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#attributes}.
	 * @param ctx the parse tree
	 */
	void exitAttributes(PHPParser.AttributesContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#attributesGroup}.
	 * @param ctx the parse tree
	 */
	void enterAttributesGroup(PHPParser.AttributesGroupContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#attributesGroup}.
	 * @param ctx the parse tree
	 */
	void exitAttributesGroup(PHPParser.AttributesGroupContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#attribute}.
	 * @param ctx the parse tree
	 */
	void enterAttribute(PHPParser.AttributeContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#attribute}.
	 * @param ctx the parse tree
	 */
	void exitAttribute(PHPParser.AttributeContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#attributeArgList}.
	 * @param ctx the parse tree
	 */
	void enterAttributeArgList(PHPParser.AttributeArgListContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#attributeArgList}.
	 * @param ctx the parse tree
	 */
	void exitAttributeArgList(PHPParser.AttributeArgListContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#attributeNamedArgList}.
	 * @param ctx the parse tree
	 */
	void enterAttributeNamedArgList(PHPParser.AttributeNamedArgListContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#attributeNamedArgList}.
	 * @param ctx the parse tree
	 */
	void exitAttributeNamedArgList(PHPParser.AttributeNamedArgListContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#attributeNamedArg}.
	 * @param ctx the parse tree
	 */
	void enterAttributeNamedArg(PHPParser.AttributeNamedArgContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#attributeNamedArg}.
	 * @param ctx the parse tree
	 */
	void exitAttributeNamedArg(PHPParser.AttributeNamedArgContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#innerStatementList}.
	 * @param ctx the parse tree
	 */
	void enterInnerStatementList(PHPParser.InnerStatementListContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#innerStatementList}.
	 * @param ctx the parse tree
	 */
	void exitInnerStatementList(PHPParser.InnerStatementListContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#innerStatement}.
	 * @param ctx the parse tree
	 */
	void enterInnerStatement(PHPParser.InnerStatementContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#innerStatement}.
	 * @param ctx the parse tree
	 */
	void exitInnerStatement(PHPParser.InnerStatementContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#statement}.
	 * @param ctx the parse tree
	 */
	void enterStatement(PHPParser.StatementContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#statement}.
	 * @param ctx the parse tree
	 */
	void exitStatement(PHPParser.StatementContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#emptyStatement}.
	 * @param ctx the parse tree
	 */
	void enterEmptyStatement(PHPParser.EmptyStatementContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#emptyStatement}.
	 * @param ctx the parse tree
	 */
	void exitEmptyStatement(PHPParser.EmptyStatementContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#nonEmptyStatement}.
	 * @param ctx the parse tree
	 */
	void enterNonEmptyStatement(PHPParser.NonEmptyStatementContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#nonEmptyStatement}.
	 * @param ctx the parse tree
	 */
	void exitNonEmptyStatement(PHPParser.NonEmptyStatementContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#blockStatement}.
	 * @param ctx the parse tree
	 */
	void enterBlockStatement(PHPParser.BlockStatementContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#blockStatement}.
	 * @param ctx the parse tree
	 */
	void exitBlockStatement(PHPParser.BlockStatementContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#ifStatement}.
	 * @param ctx the parse tree
	 */
	void enterIfStatement(PHPParser.IfStatementContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#ifStatement}.
	 * @param ctx the parse tree
	 */
	void exitIfStatement(PHPParser.IfStatementContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#elseIfStatement}.
	 * @param ctx the parse tree
	 */
	void enterElseIfStatement(PHPParser.ElseIfStatementContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#elseIfStatement}.
	 * @param ctx the parse tree
	 */
	void exitElseIfStatement(PHPParser.ElseIfStatementContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#elseIfColonStatement}.
	 * @param ctx the parse tree
	 */
	void enterElseIfColonStatement(PHPParser.ElseIfColonStatementContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#elseIfColonStatement}.
	 * @param ctx the parse tree
	 */
	void exitElseIfColonStatement(PHPParser.ElseIfColonStatementContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#elseStatement}.
	 * @param ctx the parse tree
	 */
	void enterElseStatement(PHPParser.ElseStatementContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#elseStatement}.
	 * @param ctx the parse tree
	 */
	void exitElseStatement(PHPParser.ElseStatementContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#elseColonStatement}.
	 * @param ctx the parse tree
	 */
	void enterElseColonStatement(PHPParser.ElseColonStatementContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#elseColonStatement}.
	 * @param ctx the parse tree
	 */
	void exitElseColonStatement(PHPParser.ElseColonStatementContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#whileStatement}.
	 * @param ctx the parse tree
	 */
	void enterWhileStatement(PHPParser.WhileStatementContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#whileStatement}.
	 * @param ctx the parse tree
	 */
	void exitWhileStatement(PHPParser.WhileStatementContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#doWhileStatement}.
	 * @param ctx the parse tree
	 */
	void enterDoWhileStatement(PHPParser.DoWhileStatementContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#doWhileStatement}.
	 * @param ctx the parse tree
	 */
	void exitDoWhileStatement(PHPParser.DoWhileStatementContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#forStatement}.
	 * @param ctx the parse tree
	 */
	void enterForStatement(PHPParser.ForStatementContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#forStatement}.
	 * @param ctx the parse tree
	 */
	void exitForStatement(PHPParser.ForStatementContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#forInit}.
	 * @param ctx the parse tree
	 */
	void enterForInit(PHPParser.ForInitContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#forInit}.
	 * @param ctx the parse tree
	 */
	void exitForInit(PHPParser.ForInitContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#forUpdate}.
	 * @param ctx the parse tree
	 */
	void enterForUpdate(PHPParser.ForUpdateContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#forUpdate}.
	 * @param ctx the parse tree
	 */
	void exitForUpdate(PHPParser.ForUpdateContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#switchStatement}.
	 * @param ctx the parse tree
	 */
	void enterSwitchStatement(PHPParser.SwitchStatementContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#switchStatement}.
	 * @param ctx the parse tree
	 */
	void exitSwitchStatement(PHPParser.SwitchStatementContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#switchBlock}.
	 * @param ctx the parse tree
	 */
	void enterSwitchBlock(PHPParser.SwitchBlockContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#switchBlock}.
	 * @param ctx the parse tree
	 */
	void exitSwitchBlock(PHPParser.SwitchBlockContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#breakStatement}.
	 * @param ctx the parse tree
	 */
	void enterBreakStatement(PHPParser.BreakStatementContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#breakStatement}.
	 * @param ctx the parse tree
	 */
	void exitBreakStatement(PHPParser.BreakStatementContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#continueStatement}.
	 * @param ctx the parse tree
	 */
	void enterContinueStatement(PHPParser.ContinueStatementContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#continueStatement}.
	 * @param ctx the parse tree
	 */
	void exitContinueStatement(PHPParser.ContinueStatementContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#returnStatement}.
	 * @param ctx the parse tree
	 */
	void enterReturnStatement(PHPParser.ReturnStatementContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#returnStatement}.
	 * @param ctx the parse tree
	 */
	void exitReturnStatement(PHPParser.ReturnStatementContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#expressionStatement}.
	 * @param ctx the parse tree
	 */
	void enterExpressionStatement(PHPParser.ExpressionStatementContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#expressionStatement}.
	 * @param ctx the parse tree
	 */
	void exitExpressionStatement(PHPParser.ExpressionStatementContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#unsetStatement}.
	 * @param ctx the parse tree
	 */
	void enterUnsetStatement(PHPParser.UnsetStatementContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#unsetStatement}.
	 * @param ctx the parse tree
	 */
	void exitUnsetStatement(PHPParser.UnsetStatementContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#foreachStatement}.
	 * @param ctx the parse tree
	 */
	void enterForeachStatement(PHPParser.ForeachStatementContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#foreachStatement}.
	 * @param ctx the parse tree
	 */
	void exitForeachStatement(PHPParser.ForeachStatementContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#tryCatchFinally}.
	 * @param ctx the parse tree
	 */
	void enterTryCatchFinally(PHPParser.TryCatchFinallyContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#tryCatchFinally}.
	 * @param ctx the parse tree
	 */
	void exitTryCatchFinally(PHPParser.TryCatchFinallyContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#catchClause}.
	 * @param ctx the parse tree
	 */
	void enterCatchClause(PHPParser.CatchClauseContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#catchClause}.
	 * @param ctx the parse tree
	 */
	void exitCatchClause(PHPParser.CatchClauseContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#finallyStatement}.
	 * @param ctx the parse tree
	 */
	void enterFinallyStatement(PHPParser.FinallyStatementContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#finallyStatement}.
	 * @param ctx the parse tree
	 */
	void exitFinallyStatement(PHPParser.FinallyStatementContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#throwStatement}.
	 * @param ctx the parse tree
	 */
	void enterThrowStatement(PHPParser.ThrowStatementContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#throwStatement}.
	 * @param ctx the parse tree
	 */
	void exitThrowStatement(PHPParser.ThrowStatementContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#gotoStatement}.
	 * @param ctx the parse tree
	 */
	void enterGotoStatement(PHPParser.GotoStatementContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#gotoStatement}.
	 * @param ctx the parse tree
	 */
	void exitGotoStatement(PHPParser.GotoStatementContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#declareStatement}.
	 * @param ctx the parse tree
	 */
	void enterDeclareStatement(PHPParser.DeclareStatementContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#declareStatement}.
	 * @param ctx the parse tree
	 */
	void exitDeclareStatement(PHPParser.DeclareStatementContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#inlineHtml}.
	 * @param ctx the parse tree
	 */
	void enterInlineHtml(PHPParser.InlineHtmlContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#inlineHtml}.
	 * @param ctx the parse tree
	 */
	void exitInlineHtml(PHPParser.InlineHtmlContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#declareList}.
	 * @param ctx the parse tree
	 */
	void enterDeclareList(PHPParser.DeclareListContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#declareList}.
	 * @param ctx the parse tree
	 */
	void exitDeclareList(PHPParser.DeclareListContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#formalParameterList}.
	 * @param ctx the parse tree
	 */
	void enterFormalParameterList(PHPParser.FormalParameterListContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#formalParameterList}.
	 * @param ctx the parse tree
	 */
	void exitFormalParameterList(PHPParser.FormalParameterListContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#formalParameter}.
	 * @param ctx the parse tree
	 */
	void enterFormalParameter(PHPParser.FormalParameterContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#formalParameter}.
	 * @param ctx the parse tree
	 */
	void exitFormalParameter(PHPParser.FormalParameterContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#typeHint}.
	 * @param ctx the parse tree
	 */
	void enterTypeHint(PHPParser.TypeHintContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#typeHint}.
	 * @param ctx the parse tree
	 */
	void exitTypeHint(PHPParser.TypeHintContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#globalStatement}.
	 * @param ctx the parse tree
	 */
	void enterGlobalStatement(PHPParser.GlobalStatementContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#globalStatement}.
	 * @param ctx the parse tree
	 */
	void exitGlobalStatement(PHPParser.GlobalStatementContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#globalVar}.
	 * @param ctx the parse tree
	 */
	void enterGlobalVar(PHPParser.GlobalVarContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#globalVar}.
	 * @param ctx the parse tree
	 */
	void exitGlobalVar(PHPParser.GlobalVarContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#echoStatement}.
	 * @param ctx the parse tree
	 */
	void enterEchoStatement(PHPParser.EchoStatementContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#echoStatement}.
	 * @param ctx the parse tree
	 */
	void exitEchoStatement(PHPParser.EchoStatementContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#staticVariableStatement}.
	 * @param ctx the parse tree
	 */
	void enterStaticVariableStatement(PHPParser.StaticVariableStatementContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#staticVariableStatement}.
	 * @param ctx the parse tree
	 */
	void exitStaticVariableStatement(PHPParser.StaticVariableStatementContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#classStatement}.
	 * @param ctx the parse tree
	 */
	void enterClassStatement(PHPParser.ClassStatementContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#classStatement}.
	 * @param ctx the parse tree
	 */
	void exitClassStatement(PHPParser.ClassStatementContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#traitAdaptations}.
	 * @param ctx the parse tree
	 */
	void enterTraitAdaptations(PHPParser.TraitAdaptationsContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#traitAdaptations}.
	 * @param ctx the parse tree
	 */
	void exitTraitAdaptations(PHPParser.TraitAdaptationsContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#traitAdaptationStatement}.
	 * @param ctx the parse tree
	 */
	void enterTraitAdaptationStatement(PHPParser.TraitAdaptationStatementContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#traitAdaptationStatement}.
	 * @param ctx the parse tree
	 */
	void exitTraitAdaptationStatement(PHPParser.TraitAdaptationStatementContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#traitPrecedence}.
	 * @param ctx the parse tree
	 */
	void enterTraitPrecedence(PHPParser.TraitPrecedenceContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#traitPrecedence}.
	 * @param ctx the parse tree
	 */
	void exitTraitPrecedence(PHPParser.TraitPrecedenceContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#traitAlias}.
	 * @param ctx the parse tree
	 */
	void enterTraitAlias(PHPParser.TraitAliasContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#traitAlias}.
	 * @param ctx the parse tree
	 */
	void exitTraitAlias(PHPParser.TraitAliasContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#traitMethodReference}.
	 * @param ctx the parse tree
	 */
	void enterTraitMethodReference(PHPParser.TraitMethodReferenceContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#traitMethodReference}.
	 * @param ctx the parse tree
	 */
	void exitTraitMethodReference(PHPParser.TraitMethodReferenceContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#baseCtorCall}.
	 * @param ctx the parse tree
	 */
	void enterBaseCtorCall(PHPParser.BaseCtorCallContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#baseCtorCall}.
	 * @param ctx the parse tree
	 */
	void exitBaseCtorCall(PHPParser.BaseCtorCallContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#methodBody}.
	 * @param ctx the parse tree
	 */
	void enterMethodBody(PHPParser.MethodBodyContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#methodBody}.
	 * @param ctx the parse tree
	 */
	void exitMethodBody(PHPParser.MethodBodyContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#propertyModifiers}.
	 * @param ctx the parse tree
	 */
	void enterPropertyModifiers(PHPParser.PropertyModifiersContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#propertyModifiers}.
	 * @param ctx the parse tree
	 */
	void exitPropertyModifiers(PHPParser.PropertyModifiersContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#memberModifiers}.
	 * @param ctx the parse tree
	 */
	void enterMemberModifiers(PHPParser.MemberModifiersContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#memberModifiers}.
	 * @param ctx the parse tree
	 */
	void exitMemberModifiers(PHPParser.MemberModifiersContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#variableInitializer}.
	 * @param ctx the parse tree
	 */
	void enterVariableInitializer(PHPParser.VariableInitializerContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#variableInitializer}.
	 * @param ctx the parse tree
	 */
	void exitVariableInitializer(PHPParser.VariableInitializerContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#identifierInititalizer}.
	 * @param ctx the parse tree
	 */
	void enterIdentifierInititalizer(PHPParser.IdentifierInititalizerContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#identifierInititalizer}.
	 * @param ctx the parse tree
	 */
	void exitIdentifierInititalizer(PHPParser.IdentifierInititalizerContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#globalConstantDeclaration}.
	 * @param ctx the parse tree
	 */
	void enterGlobalConstantDeclaration(PHPParser.GlobalConstantDeclarationContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#globalConstantDeclaration}.
	 * @param ctx the parse tree
	 */
	void exitGlobalConstantDeclaration(PHPParser.GlobalConstantDeclarationContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#expressionList}.
	 * @param ctx the parse tree
	 */
	void enterExpressionList(PHPParser.ExpressionListContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#expressionList}.
	 * @param ctx the parse tree
	 */
	void exitExpressionList(PHPParser.ExpressionListContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#parenthesis}.
	 * @param ctx the parse tree
	 */
	void enterParenthesis(PHPParser.ParenthesisContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#parenthesis}.
	 * @param ctx the parse tree
	 */
	void exitParenthesis(PHPParser.ParenthesisContext ctx);
	/**
	 * Enter a parse tree produced by the {@code ChainExpression}
	 * labeled alternative in {@link PHPParser#expression}.
	 * @param ctx the parse tree
	 */
	void enterChainExpression(PHPParser.ChainExpressionContext ctx);
	/**
	 * Exit a parse tree produced by the {@code ChainExpression}
	 * labeled alternative in {@link PHPParser#expression}.
	 * @param ctx the parse tree
	 */
	void exitChainExpression(PHPParser.ChainExpressionContext ctx);
	/**
	 * Enter a parse tree produced by the {@code UnaryOperatorExpression}
	 * labeled alternative in {@link PHPParser#expression}.
	 * @param ctx the parse tree
	 */
	void enterUnaryOperatorExpression(PHPParser.UnaryOperatorExpressionContext ctx);
	/**
	 * Exit a parse tree produced by the {@code UnaryOperatorExpression}
	 * labeled alternative in {@link PHPParser#expression}.
	 * @param ctx the parse tree
	 */
	void exitUnaryOperatorExpression(PHPParser.UnaryOperatorExpressionContext ctx);
	/**
	 * Enter a parse tree produced by the {@code SpecialWordExpression}
	 * labeled alternative in {@link PHPParser#expression}.
	 * @param ctx the parse tree
	 */
	void enterSpecialWordExpression(PHPParser.SpecialWordExpressionContext ctx);
	/**
	 * Exit a parse tree produced by the {@code SpecialWordExpression}
	 * labeled alternative in {@link PHPParser#expression}.
	 * @param ctx the parse tree
	 */
	void exitSpecialWordExpression(PHPParser.SpecialWordExpressionContext ctx);
	/**
	 * Enter a parse tree produced by the {@code ArrayCreationExpression}
	 * labeled alternative in {@link PHPParser#expression}.
	 * @param ctx the parse tree
	 */
	void enterArrayCreationExpression(PHPParser.ArrayCreationExpressionContext ctx);
	/**
	 * Exit a parse tree produced by the {@code ArrayCreationExpression}
	 * labeled alternative in {@link PHPParser#expression}.
	 * @param ctx the parse tree
	 */
	void exitArrayCreationExpression(PHPParser.ArrayCreationExpressionContext ctx);
	/**
	 * Enter a parse tree produced by the {@code NewExpression}
	 * labeled alternative in {@link PHPParser#expression}.
	 * @param ctx the parse tree
	 */
	void enterNewExpression(PHPParser.NewExpressionContext ctx);
	/**
	 * Exit a parse tree produced by the {@code NewExpression}
	 * labeled alternative in {@link PHPParser#expression}.
	 * @param ctx the parse tree
	 */
	void exitNewExpression(PHPParser.NewExpressionContext ctx);
	/**
	 * Enter a parse tree produced by the {@code ParenthesisExpression}
	 * labeled alternative in {@link PHPParser#expression}.
	 * @param ctx the parse tree
	 */
	void enterParenthesisExpression(PHPParser.ParenthesisExpressionContext ctx);
	/**
	 * Exit a parse tree produced by the {@code ParenthesisExpression}
	 * labeled alternative in {@link PHPParser#expression}.
	 * @param ctx the parse tree
	 */
	void exitParenthesisExpression(PHPParser.ParenthesisExpressionContext ctx);
	/**
	 * Enter a parse tree produced by the {@code BackQuoteStringExpression}
	 * labeled alternative in {@link PHPParser#expression}.
	 * @param ctx the parse tree
	 */
	void enterBackQuoteStringExpression(PHPParser.BackQuoteStringExpressionContext ctx);
	/**
	 * Exit a parse tree produced by the {@code BackQuoteStringExpression}
	 * labeled alternative in {@link PHPParser#expression}.
	 * @param ctx the parse tree
	 */
	void exitBackQuoteStringExpression(PHPParser.BackQuoteStringExpressionContext ctx);
	/**
	 * Enter a parse tree produced by the {@code ConditionalExpression}
	 * labeled alternative in {@link PHPParser#expression}.
	 * @param ctx the parse tree
	 */
	void enterConditionalExpression(PHPParser.ConditionalExpressionContext ctx);
	/**
	 * Exit a parse tree produced by the {@code ConditionalExpression}
	 * labeled alternative in {@link PHPParser#expression}.
	 * @param ctx the parse tree
	 */
	void exitConditionalExpression(PHPParser.ConditionalExpressionContext ctx);
	/**
	 * Enter a parse tree produced by the {@code IndexerExpression}
	 * labeled alternative in {@link PHPParser#expression}.
	 * @param ctx the parse tree
	 */
	void enterIndexerExpression(PHPParser.IndexerExpressionContext ctx);
	/**
	 * Exit a parse tree produced by the {@code IndexerExpression}
	 * labeled alternative in {@link PHPParser#expression}.
	 * @param ctx the parse tree
	 */
	void exitIndexerExpression(PHPParser.IndexerExpressionContext ctx);
	/**
	 * Enter a parse tree produced by the {@code ScalarExpression}
	 * labeled alternative in {@link PHPParser#expression}.
	 * @param ctx the parse tree
	 */
	void enterScalarExpression(PHPParser.ScalarExpressionContext ctx);
	/**
	 * Exit a parse tree produced by the {@code ScalarExpression}
	 * labeled alternative in {@link PHPParser#expression}.
	 * @param ctx the parse tree
	 */
	void exitScalarExpression(PHPParser.ScalarExpressionContext ctx);
	/**
	 * Enter a parse tree produced by the {@code PrefixIncDecExpression}
	 * labeled alternative in {@link PHPParser#expression}.
	 * @param ctx the parse tree
	 */
	void enterPrefixIncDecExpression(PHPParser.PrefixIncDecExpressionContext ctx);
	/**
	 * Exit a parse tree produced by the {@code PrefixIncDecExpression}
	 * labeled alternative in {@link PHPParser#expression}.
	 * @param ctx the parse tree
	 */
	void exitPrefixIncDecExpression(PHPParser.PrefixIncDecExpressionContext ctx);
	/**
	 * Enter a parse tree produced by the {@code BinaryOperatorExpression}
	 * labeled alternative in {@link PHPParser#expression}.
	 * @param ctx the parse tree
	 */
	void enterBinaryOperatorExpression(PHPParser.BinaryOperatorExpressionContext ctx);
	/**
	 * Exit a parse tree produced by the {@code BinaryOperatorExpression}
	 * labeled alternative in {@link PHPParser#expression}.
	 * @param ctx the parse tree
	 */
	void exitBinaryOperatorExpression(PHPParser.BinaryOperatorExpressionContext ctx);
	/**
	 * Enter a parse tree produced by the {@code PrintExpression}
	 * labeled alternative in {@link PHPParser#expression}.
	 * @param ctx the parse tree
	 */
	void enterPrintExpression(PHPParser.PrintExpressionContext ctx);
	/**
	 * Exit a parse tree produced by the {@code PrintExpression}
	 * labeled alternative in {@link PHPParser#expression}.
	 * @param ctx the parse tree
	 */
	void exitPrintExpression(PHPParser.PrintExpressionContext ctx);
	/**
	 * Enter a parse tree produced by the {@code AssignmentExpression}
	 * labeled alternative in {@link PHPParser#expression}.
	 * @param ctx the parse tree
	 */
	void enterAssignmentExpression(PHPParser.AssignmentExpressionContext ctx);
	/**
	 * Exit a parse tree produced by the {@code AssignmentExpression}
	 * labeled alternative in {@link PHPParser#expression}.
	 * @param ctx the parse tree
	 */
	void exitAssignmentExpression(PHPParser.AssignmentExpressionContext ctx);
	/**
	 * Enter a parse tree produced by the {@code PostfixIncDecExpression}
	 * labeled alternative in {@link PHPParser#expression}.
	 * @param ctx the parse tree
	 */
	void enterPostfixIncDecExpression(PHPParser.PostfixIncDecExpressionContext ctx);
	/**
	 * Exit a parse tree produced by the {@code PostfixIncDecExpression}
	 * labeled alternative in {@link PHPParser#expression}.
	 * @param ctx the parse tree
	 */
	void exitPostfixIncDecExpression(PHPParser.PostfixIncDecExpressionContext ctx);
	/**
	 * Enter a parse tree produced by the {@code CastExpression}
	 * labeled alternative in {@link PHPParser#expression}.
	 * @param ctx the parse tree
	 */
	void enterCastExpression(PHPParser.CastExpressionContext ctx);
	/**
	 * Exit a parse tree produced by the {@code CastExpression}
	 * labeled alternative in {@link PHPParser#expression}.
	 * @param ctx the parse tree
	 */
	void exitCastExpression(PHPParser.CastExpressionContext ctx);
	/**
	 * Enter a parse tree produced by the {@code InstanceOfExpression}
	 * labeled alternative in {@link PHPParser#expression}.
	 * @param ctx the parse tree
	 */
	void enterInstanceOfExpression(PHPParser.InstanceOfExpressionContext ctx);
	/**
	 * Exit a parse tree produced by the {@code InstanceOfExpression}
	 * labeled alternative in {@link PHPParser#expression}.
	 * @param ctx the parse tree
	 */
	void exitInstanceOfExpression(PHPParser.InstanceOfExpressionContext ctx);
	/**
	 * Enter a parse tree produced by the {@code LambdaFunctionExpression}
	 * labeled alternative in {@link PHPParser#expression}.
	 * @param ctx the parse tree
	 */
	void enterLambdaFunctionExpression(PHPParser.LambdaFunctionExpressionContext ctx);
	/**
	 * Exit a parse tree produced by the {@code LambdaFunctionExpression}
	 * labeled alternative in {@link PHPParser#expression}.
	 * @param ctx the parse tree
	 */
	void exitLambdaFunctionExpression(PHPParser.LambdaFunctionExpressionContext ctx);
	/**
	 * Enter a parse tree produced by the {@code CloneExpression}
	 * labeled alternative in {@link PHPParser#expression}.
	 * @param ctx the parse tree
	 */
	void enterCloneExpression(PHPParser.CloneExpressionContext ctx);
	/**
	 * Exit a parse tree produced by the {@code CloneExpression}
	 * labeled alternative in {@link PHPParser#expression}.
	 * @param ctx the parse tree
	 */
	void exitCloneExpression(PHPParser.CloneExpressionContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#newExpr}.
	 * @param ctx the parse tree
	 */
	void enterNewExpr(PHPParser.NewExprContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#newExpr}.
	 * @param ctx the parse tree
	 */
	void exitNewExpr(PHPParser.NewExprContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#assignmentOperator}.
	 * @param ctx the parse tree
	 */
	void enterAssignmentOperator(PHPParser.AssignmentOperatorContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#assignmentOperator}.
	 * @param ctx the parse tree
	 */
	void exitAssignmentOperator(PHPParser.AssignmentOperatorContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#yieldExpression}.
	 * @param ctx the parse tree
	 */
	void enterYieldExpression(PHPParser.YieldExpressionContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#yieldExpression}.
	 * @param ctx the parse tree
	 */
	void exitYieldExpression(PHPParser.YieldExpressionContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#arrayItemList}.
	 * @param ctx the parse tree
	 */
	void enterArrayItemList(PHPParser.ArrayItemListContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#arrayItemList}.
	 * @param ctx the parse tree
	 */
	void exitArrayItemList(PHPParser.ArrayItemListContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#arrayItem}.
	 * @param ctx the parse tree
	 */
	void enterArrayItem(PHPParser.ArrayItemContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#arrayItem}.
	 * @param ctx the parse tree
	 */
	void exitArrayItem(PHPParser.ArrayItemContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#lambdaFunctionUseVars}.
	 * @param ctx the parse tree
	 */
	void enterLambdaFunctionUseVars(PHPParser.LambdaFunctionUseVarsContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#lambdaFunctionUseVars}.
	 * @param ctx the parse tree
	 */
	void exitLambdaFunctionUseVars(PHPParser.LambdaFunctionUseVarsContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#lambdaFunctionUseVar}.
	 * @param ctx the parse tree
	 */
	void enterLambdaFunctionUseVar(PHPParser.LambdaFunctionUseVarContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#lambdaFunctionUseVar}.
	 * @param ctx the parse tree
	 */
	void exitLambdaFunctionUseVar(PHPParser.LambdaFunctionUseVarContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#qualifiedStaticTypeRef}.
	 * @param ctx the parse tree
	 */
	void enterQualifiedStaticTypeRef(PHPParser.QualifiedStaticTypeRefContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#qualifiedStaticTypeRef}.
	 * @param ctx the parse tree
	 */
	void exitQualifiedStaticTypeRef(PHPParser.QualifiedStaticTypeRefContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#typeRef}.
	 * @param ctx the parse tree
	 */
	void enterTypeRef(PHPParser.TypeRefContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#typeRef}.
	 * @param ctx the parse tree
	 */
	void exitTypeRef(PHPParser.TypeRefContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#indirectTypeRef}.
	 * @param ctx the parse tree
	 */
	void enterIndirectTypeRef(PHPParser.IndirectTypeRefContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#indirectTypeRef}.
	 * @param ctx the parse tree
	 */
	void exitIndirectTypeRef(PHPParser.IndirectTypeRefContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#qualifiedNamespaceName}.
	 * @param ctx the parse tree
	 */
	void enterQualifiedNamespaceName(PHPParser.QualifiedNamespaceNameContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#qualifiedNamespaceName}.
	 * @param ctx the parse tree
	 */
	void exitQualifiedNamespaceName(PHPParser.QualifiedNamespaceNameContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#namespaceNameList}.
	 * @param ctx the parse tree
	 */
	void enterNamespaceNameList(PHPParser.NamespaceNameListContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#namespaceNameList}.
	 * @param ctx the parse tree
	 */
	void exitNamespaceNameList(PHPParser.NamespaceNameListContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#qualifiedNamespaceNameList}.
	 * @param ctx the parse tree
	 */
	void enterQualifiedNamespaceNameList(PHPParser.QualifiedNamespaceNameListContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#qualifiedNamespaceNameList}.
	 * @param ctx the parse tree
	 */
	void exitQualifiedNamespaceNameList(PHPParser.QualifiedNamespaceNameListContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#arguments}.
	 * @param ctx the parse tree
	 */
	void enterArguments(PHPParser.ArgumentsContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#arguments}.
	 * @param ctx the parse tree
	 */
	void exitArguments(PHPParser.ArgumentsContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#actualArgument}.
	 * @param ctx the parse tree
	 */
	void enterActualArgument(PHPParser.ActualArgumentContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#actualArgument}.
	 * @param ctx the parse tree
	 */
	void exitActualArgument(PHPParser.ActualArgumentContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#constantInititalizer}.
	 * @param ctx the parse tree
	 */
	void enterConstantInititalizer(PHPParser.ConstantInititalizerContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#constantInititalizer}.
	 * @param ctx the parse tree
	 */
	void exitConstantInititalizer(PHPParser.ConstantInititalizerContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#constantArrayItemList}.
	 * @param ctx the parse tree
	 */
	void enterConstantArrayItemList(PHPParser.ConstantArrayItemListContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#constantArrayItemList}.
	 * @param ctx the parse tree
	 */
	void exitConstantArrayItemList(PHPParser.ConstantArrayItemListContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#constantArrayItem}.
	 * @param ctx the parse tree
	 */
	void enterConstantArrayItem(PHPParser.ConstantArrayItemContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#constantArrayItem}.
	 * @param ctx the parse tree
	 */
	void exitConstantArrayItem(PHPParser.ConstantArrayItemContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#constant}.
	 * @param ctx the parse tree
	 */
	void enterConstant(PHPParser.ConstantContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#constant}.
	 * @param ctx the parse tree
	 */
	void exitConstant(PHPParser.ConstantContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#literalConstant}.
	 * @param ctx the parse tree
	 */
	void enterLiteralConstant(PHPParser.LiteralConstantContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#literalConstant}.
	 * @param ctx the parse tree
	 */
	void exitLiteralConstant(PHPParser.LiteralConstantContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#classConstant}.
	 * @param ctx the parse tree
	 */
	void enterClassConstant(PHPParser.ClassConstantContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#classConstant}.
	 * @param ctx the parse tree
	 */
	void exitClassConstant(PHPParser.ClassConstantContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#stringConstant}.
	 * @param ctx the parse tree
	 */
	void enterStringConstant(PHPParser.StringConstantContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#stringConstant}.
	 * @param ctx the parse tree
	 */
	void exitStringConstant(PHPParser.StringConstantContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#string}.
	 * @param ctx the parse tree
	 */
	void enterString(PHPParser.StringContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#string}.
	 * @param ctx the parse tree
	 */
	void exitString(PHPParser.StringContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#chainList}.
	 * @param ctx the parse tree
	 */
	void enterChainList(PHPParser.ChainListContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#chainList}.
	 * @param ctx the parse tree
	 */
	void exitChainList(PHPParser.ChainListContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#chain}.
	 * @param ctx the parse tree
	 */
	void enterChain(PHPParser.ChainContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#chain}.
	 * @param ctx the parse tree
	 */
	void exitChain(PHPParser.ChainContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#memberAccess}.
	 * @param ctx the parse tree
	 */
	void enterMemberAccess(PHPParser.MemberAccessContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#memberAccess}.
	 * @param ctx the parse tree
	 */
	void exitMemberAccess(PHPParser.MemberAccessContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#functionCall}.
	 * @param ctx the parse tree
	 */
	void enterFunctionCall(PHPParser.FunctionCallContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#functionCall}.
	 * @param ctx the parse tree
	 */
	void exitFunctionCall(PHPParser.FunctionCallContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#functionCallName}.
	 * @param ctx the parse tree
	 */
	void enterFunctionCallName(PHPParser.FunctionCallNameContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#functionCallName}.
	 * @param ctx the parse tree
	 */
	void exitFunctionCallName(PHPParser.FunctionCallNameContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#actualArguments}.
	 * @param ctx the parse tree
	 */
	void enterActualArguments(PHPParser.ActualArgumentsContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#actualArguments}.
	 * @param ctx the parse tree
	 */
	void exitActualArguments(PHPParser.ActualArgumentsContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#chainBase}.
	 * @param ctx the parse tree
	 */
	void enterChainBase(PHPParser.ChainBaseContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#chainBase}.
	 * @param ctx the parse tree
	 */
	void exitChainBase(PHPParser.ChainBaseContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#keyedFieldName}.
	 * @param ctx the parse tree
	 */
	void enterKeyedFieldName(PHPParser.KeyedFieldNameContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#keyedFieldName}.
	 * @param ctx the parse tree
	 */
	void exitKeyedFieldName(PHPParser.KeyedFieldNameContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#keyedSimpleFieldName}.
	 * @param ctx the parse tree
	 */
	void enterKeyedSimpleFieldName(PHPParser.KeyedSimpleFieldNameContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#keyedSimpleFieldName}.
	 * @param ctx the parse tree
	 */
	void exitKeyedSimpleFieldName(PHPParser.KeyedSimpleFieldNameContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#keyedVariable}.
	 * @param ctx the parse tree
	 */
	void enterKeyedVariable(PHPParser.KeyedVariableContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#keyedVariable}.
	 * @param ctx the parse tree
	 */
	void exitKeyedVariable(PHPParser.KeyedVariableContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#squareCurlyExpression}.
	 * @param ctx the parse tree
	 */
	void enterSquareCurlyExpression(PHPParser.SquareCurlyExpressionContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#squareCurlyExpression}.
	 * @param ctx the parse tree
	 */
	void exitSquareCurlyExpression(PHPParser.SquareCurlyExpressionContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#assignmentList}.
	 * @param ctx the parse tree
	 */
	void enterAssignmentList(PHPParser.AssignmentListContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#assignmentList}.
	 * @param ctx the parse tree
	 */
	void exitAssignmentList(PHPParser.AssignmentListContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#assignmentListElement}.
	 * @param ctx the parse tree
	 */
	void enterAssignmentListElement(PHPParser.AssignmentListElementContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#assignmentListElement}.
	 * @param ctx the parse tree
	 */
	void exitAssignmentListElement(PHPParser.AssignmentListElementContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#modifier}.
	 * @param ctx the parse tree
	 */
	void enterModifier(PHPParser.ModifierContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#modifier}.
	 * @param ctx the parse tree
	 */
	void exitModifier(PHPParser.ModifierContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#identifier}.
	 * @param ctx the parse tree
	 */
	void enterIdentifier(PHPParser.IdentifierContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#identifier}.
	 * @param ctx the parse tree
	 */
	void exitIdentifier(PHPParser.IdentifierContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#memberModifier}.
	 * @param ctx the parse tree
	 */
	void enterMemberModifier(PHPParser.MemberModifierContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#memberModifier}.
	 * @param ctx the parse tree
	 */
	void exitMemberModifier(PHPParser.MemberModifierContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#magicConstant}.
	 * @param ctx the parse tree
	 */
	void enterMagicConstant(PHPParser.MagicConstantContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#magicConstant}.
	 * @param ctx the parse tree
	 */
	void exitMagicConstant(PHPParser.MagicConstantContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#magicMethod}.
	 * @param ctx the parse tree
	 */
	void enterMagicMethod(PHPParser.MagicMethodContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#magicMethod}.
	 * @param ctx the parse tree
	 */
	void exitMagicMethod(PHPParser.MagicMethodContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#primitiveType}.
	 * @param ctx the parse tree
	 */
	void enterPrimitiveType(PHPParser.PrimitiveTypeContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#primitiveType}.
	 * @param ctx the parse tree
	 */
	void exitPrimitiveType(PHPParser.PrimitiveTypeContext ctx);
	/**
	 * Enter a parse tree produced by {@link PHPParser#castOperation}.
	 * @param ctx the parse tree
	 */
	void enterCastOperation(PHPParser.CastOperationContext ctx);
	/**
	 * Exit a parse tree produced by {@link PHPParser#castOperation}.
	 * @param ctx the parse tree
	 */
	void exitCastOperation(PHPParser.CastOperationContext ctx);
}