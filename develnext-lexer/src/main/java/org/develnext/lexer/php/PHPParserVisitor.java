// Generated from org\develnext\lexer\php\PHPParser.g4 by ANTLR 4.5.3
package org.develnext.lexer.php;
import org.antlr.v4.runtime.tree.ParseTreeVisitor;

/**
 * This interface defines a complete generic visitor for a parse tree produced
 * by {@link PHPParser}.
 *
 * @param <T> The return type of the visit operation. Use {@link Void} for
 * operations with no return type.
 */
public interface PHPParserVisitor<T> extends ParseTreeVisitor<T> {
	/**
	 * Visit a parse tree produced by {@link PHPParser#htmlDocument}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitHtmlDocument(PHPParser.HtmlDocumentContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#htmlElementOrPhpBlock}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitHtmlElementOrPhpBlock(PHPParser.HtmlElementOrPhpBlockContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#htmlElement}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitHtmlElement(PHPParser.HtmlElementContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#htmlContent}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitHtmlContent(PHPParser.HtmlContentContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#htmlAttribute}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitHtmlAttribute(PHPParser.HtmlAttributeContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#htmlQuotePhpBlockOrString}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitHtmlQuotePhpBlockOrString(PHPParser.HtmlQuotePhpBlockOrStringContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#htmlDoubleQuotePhpBlockOrString}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitHtmlDoubleQuotePhpBlockOrString(PHPParser.HtmlDoubleQuotePhpBlockOrStringContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#scriptText}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitScriptText(PHPParser.ScriptTextContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#scriptTextPart}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitScriptTextPart(PHPParser.ScriptTextPartContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#phpBlock}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitPhpBlock(PHPParser.PhpBlockContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#importStatement}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitImportStatement(PHPParser.ImportStatementContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#topStatement}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitTopStatement(PHPParser.TopStatementContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#useDeclaration}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitUseDeclaration(PHPParser.UseDeclarationContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#useDeclarationContentList}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitUseDeclarationContentList(PHPParser.UseDeclarationContentListContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#useDeclarationContent}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitUseDeclarationContent(PHPParser.UseDeclarationContentContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#namespaceDeclaration}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitNamespaceDeclaration(PHPParser.NamespaceDeclarationContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#namespaceStatement}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitNamespaceStatement(PHPParser.NamespaceStatementContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#functionDeclaration}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitFunctionDeclaration(PHPParser.FunctionDeclarationContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#classDeclaration}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitClassDeclaration(PHPParser.ClassDeclarationContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#classEntryType}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitClassEntryType(PHPParser.ClassEntryTypeContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#interfaceList}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitInterfaceList(PHPParser.InterfaceListContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#typeParameterListInBrackets}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitTypeParameterListInBrackets(PHPParser.TypeParameterListInBracketsContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#typeParameterList}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitTypeParameterList(PHPParser.TypeParameterListContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#typeParameterWithDefaultsList}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitTypeParameterWithDefaultsList(PHPParser.TypeParameterWithDefaultsListContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#typeParameterDecl}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitTypeParameterDecl(PHPParser.TypeParameterDeclContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#typeParameterWithDefaultDecl}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitTypeParameterWithDefaultDecl(PHPParser.TypeParameterWithDefaultDeclContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#genericDynamicArgs}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitGenericDynamicArgs(PHPParser.GenericDynamicArgsContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#attributes}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitAttributes(PHPParser.AttributesContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#attributesGroup}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitAttributesGroup(PHPParser.AttributesGroupContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#attribute}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitAttribute(PHPParser.AttributeContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#attributeArgList}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitAttributeArgList(PHPParser.AttributeArgListContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#attributeNamedArgList}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitAttributeNamedArgList(PHPParser.AttributeNamedArgListContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#attributeNamedArg}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitAttributeNamedArg(PHPParser.AttributeNamedArgContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#innerStatementList}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitInnerStatementList(PHPParser.InnerStatementListContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#innerStatement}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitInnerStatement(PHPParser.InnerStatementContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#statement}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitStatement(PHPParser.StatementContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#emptyStatement}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitEmptyStatement(PHPParser.EmptyStatementContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#nonEmptyStatement}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitNonEmptyStatement(PHPParser.NonEmptyStatementContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#blockStatement}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitBlockStatement(PHPParser.BlockStatementContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#ifStatement}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitIfStatement(PHPParser.IfStatementContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#elseIfStatement}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitElseIfStatement(PHPParser.ElseIfStatementContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#elseIfColonStatement}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitElseIfColonStatement(PHPParser.ElseIfColonStatementContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#elseStatement}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitElseStatement(PHPParser.ElseStatementContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#elseColonStatement}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitElseColonStatement(PHPParser.ElseColonStatementContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#whileStatement}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitWhileStatement(PHPParser.WhileStatementContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#doWhileStatement}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitDoWhileStatement(PHPParser.DoWhileStatementContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#forStatement}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitForStatement(PHPParser.ForStatementContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#forInit}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitForInit(PHPParser.ForInitContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#forUpdate}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitForUpdate(PHPParser.ForUpdateContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#switchStatement}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitSwitchStatement(PHPParser.SwitchStatementContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#switchBlock}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitSwitchBlock(PHPParser.SwitchBlockContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#breakStatement}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitBreakStatement(PHPParser.BreakStatementContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#continueStatement}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitContinueStatement(PHPParser.ContinueStatementContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#returnStatement}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitReturnStatement(PHPParser.ReturnStatementContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#expressionStatement}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitExpressionStatement(PHPParser.ExpressionStatementContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#unsetStatement}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitUnsetStatement(PHPParser.UnsetStatementContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#foreachStatement}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitForeachStatement(PHPParser.ForeachStatementContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#tryCatchFinally}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitTryCatchFinally(PHPParser.TryCatchFinallyContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#catchClause}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitCatchClause(PHPParser.CatchClauseContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#finallyStatement}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitFinallyStatement(PHPParser.FinallyStatementContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#throwStatement}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitThrowStatement(PHPParser.ThrowStatementContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#gotoStatement}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitGotoStatement(PHPParser.GotoStatementContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#declareStatement}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitDeclareStatement(PHPParser.DeclareStatementContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#inlineHtml}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitInlineHtml(PHPParser.InlineHtmlContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#declareList}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitDeclareList(PHPParser.DeclareListContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#formalParameterList}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitFormalParameterList(PHPParser.FormalParameterListContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#formalParameter}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitFormalParameter(PHPParser.FormalParameterContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#typeHint}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitTypeHint(PHPParser.TypeHintContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#globalStatement}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitGlobalStatement(PHPParser.GlobalStatementContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#globalVar}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitGlobalVar(PHPParser.GlobalVarContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#echoStatement}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitEchoStatement(PHPParser.EchoStatementContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#staticVariableStatement}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitStaticVariableStatement(PHPParser.StaticVariableStatementContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#classStatement}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitClassStatement(PHPParser.ClassStatementContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#traitAdaptations}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitTraitAdaptations(PHPParser.TraitAdaptationsContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#traitAdaptationStatement}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitTraitAdaptationStatement(PHPParser.TraitAdaptationStatementContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#traitPrecedence}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitTraitPrecedence(PHPParser.TraitPrecedenceContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#traitAlias}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitTraitAlias(PHPParser.TraitAliasContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#traitMethodReference}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitTraitMethodReference(PHPParser.TraitMethodReferenceContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#baseCtorCall}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitBaseCtorCall(PHPParser.BaseCtorCallContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#methodBody}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitMethodBody(PHPParser.MethodBodyContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#propertyModifiers}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitPropertyModifiers(PHPParser.PropertyModifiersContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#memberModifiers}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitMemberModifiers(PHPParser.MemberModifiersContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#variableInitializer}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitVariableInitializer(PHPParser.VariableInitializerContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#identifierInititalizer}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitIdentifierInititalizer(PHPParser.IdentifierInititalizerContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#globalConstantDeclaration}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitGlobalConstantDeclaration(PHPParser.GlobalConstantDeclarationContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#expressionList}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitExpressionList(PHPParser.ExpressionListContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#parenthesis}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitParenthesis(PHPParser.ParenthesisContext ctx);
	/**
	 * Visit a parse tree produced by the {@code ChainExpression}
	 * labeled alternative in {@link PHPParser#expression}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitChainExpression(PHPParser.ChainExpressionContext ctx);
	/**
	 * Visit a parse tree produced by the {@code UnaryOperatorExpression}
	 * labeled alternative in {@link PHPParser#expression}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitUnaryOperatorExpression(PHPParser.UnaryOperatorExpressionContext ctx);
	/**
	 * Visit a parse tree produced by the {@code SpecialWordExpression}
	 * labeled alternative in {@link PHPParser#expression}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitSpecialWordExpression(PHPParser.SpecialWordExpressionContext ctx);
	/**
	 * Visit a parse tree produced by the {@code ArrayCreationExpression}
	 * labeled alternative in {@link PHPParser#expression}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitArrayCreationExpression(PHPParser.ArrayCreationExpressionContext ctx);
	/**
	 * Visit a parse tree produced by the {@code NewExpression}
	 * labeled alternative in {@link PHPParser#expression}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitNewExpression(PHPParser.NewExpressionContext ctx);
	/**
	 * Visit a parse tree produced by the {@code ParenthesisExpression}
	 * labeled alternative in {@link PHPParser#expression}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitParenthesisExpression(PHPParser.ParenthesisExpressionContext ctx);
	/**
	 * Visit a parse tree produced by the {@code BackQuoteStringExpression}
	 * labeled alternative in {@link PHPParser#expression}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitBackQuoteStringExpression(PHPParser.BackQuoteStringExpressionContext ctx);
	/**
	 * Visit a parse tree produced by the {@code ConditionalExpression}
	 * labeled alternative in {@link PHPParser#expression}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitConditionalExpression(PHPParser.ConditionalExpressionContext ctx);
	/**
	 * Visit a parse tree produced by the {@code IndexerExpression}
	 * labeled alternative in {@link PHPParser#expression}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitIndexerExpression(PHPParser.IndexerExpressionContext ctx);
	/**
	 * Visit a parse tree produced by the {@code ScalarExpression}
	 * labeled alternative in {@link PHPParser#expression}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitScalarExpression(PHPParser.ScalarExpressionContext ctx);
	/**
	 * Visit a parse tree produced by the {@code PrefixIncDecExpression}
	 * labeled alternative in {@link PHPParser#expression}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitPrefixIncDecExpression(PHPParser.PrefixIncDecExpressionContext ctx);
	/**
	 * Visit a parse tree produced by the {@code BinaryOperatorExpression}
	 * labeled alternative in {@link PHPParser#expression}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitBinaryOperatorExpression(PHPParser.BinaryOperatorExpressionContext ctx);
	/**
	 * Visit a parse tree produced by the {@code PrintExpression}
	 * labeled alternative in {@link PHPParser#expression}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitPrintExpression(PHPParser.PrintExpressionContext ctx);
	/**
	 * Visit a parse tree produced by the {@code AssignmentExpression}
	 * labeled alternative in {@link PHPParser#expression}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitAssignmentExpression(PHPParser.AssignmentExpressionContext ctx);
	/**
	 * Visit a parse tree produced by the {@code PostfixIncDecExpression}
	 * labeled alternative in {@link PHPParser#expression}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitPostfixIncDecExpression(PHPParser.PostfixIncDecExpressionContext ctx);
	/**
	 * Visit a parse tree produced by the {@code CastExpression}
	 * labeled alternative in {@link PHPParser#expression}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitCastExpression(PHPParser.CastExpressionContext ctx);
	/**
	 * Visit a parse tree produced by the {@code InstanceOfExpression}
	 * labeled alternative in {@link PHPParser#expression}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitInstanceOfExpression(PHPParser.InstanceOfExpressionContext ctx);
	/**
	 * Visit a parse tree produced by the {@code LambdaFunctionExpression}
	 * labeled alternative in {@link PHPParser#expression}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitLambdaFunctionExpression(PHPParser.LambdaFunctionExpressionContext ctx);
	/**
	 * Visit a parse tree produced by the {@code CloneExpression}
	 * labeled alternative in {@link PHPParser#expression}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitCloneExpression(PHPParser.CloneExpressionContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#newExpr}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitNewExpr(PHPParser.NewExprContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#assignmentOperator}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitAssignmentOperator(PHPParser.AssignmentOperatorContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#yieldExpression}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitYieldExpression(PHPParser.YieldExpressionContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#arrayItemList}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitArrayItemList(PHPParser.ArrayItemListContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#arrayItem}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitArrayItem(PHPParser.ArrayItemContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#lambdaFunctionUseVars}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitLambdaFunctionUseVars(PHPParser.LambdaFunctionUseVarsContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#lambdaFunctionUseVar}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitLambdaFunctionUseVar(PHPParser.LambdaFunctionUseVarContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#qualifiedStaticTypeRef}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitQualifiedStaticTypeRef(PHPParser.QualifiedStaticTypeRefContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#typeRef}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitTypeRef(PHPParser.TypeRefContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#indirectTypeRef}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitIndirectTypeRef(PHPParser.IndirectTypeRefContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#qualifiedNamespaceName}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitQualifiedNamespaceName(PHPParser.QualifiedNamespaceNameContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#namespaceNameList}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitNamespaceNameList(PHPParser.NamespaceNameListContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#qualifiedNamespaceNameList}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitQualifiedNamespaceNameList(PHPParser.QualifiedNamespaceNameListContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#arguments}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitArguments(PHPParser.ArgumentsContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#actualArgument}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitActualArgument(PHPParser.ActualArgumentContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#constantInititalizer}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitConstantInititalizer(PHPParser.ConstantInititalizerContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#constantArrayItemList}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitConstantArrayItemList(PHPParser.ConstantArrayItemListContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#constantArrayItem}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitConstantArrayItem(PHPParser.ConstantArrayItemContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#constant}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitConstant(PHPParser.ConstantContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#literalConstant}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitLiteralConstant(PHPParser.LiteralConstantContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#classConstant}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitClassConstant(PHPParser.ClassConstantContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#stringConstant}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitStringConstant(PHPParser.StringConstantContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#string}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitString(PHPParser.StringContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#chainList}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitChainList(PHPParser.ChainListContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#chain}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitChain(PHPParser.ChainContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#memberAccess}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitMemberAccess(PHPParser.MemberAccessContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#functionCall}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitFunctionCall(PHPParser.FunctionCallContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#functionCallName}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitFunctionCallName(PHPParser.FunctionCallNameContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#actualArguments}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitActualArguments(PHPParser.ActualArgumentsContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#chainBase}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitChainBase(PHPParser.ChainBaseContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#keyedFieldName}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitKeyedFieldName(PHPParser.KeyedFieldNameContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#keyedSimpleFieldName}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitKeyedSimpleFieldName(PHPParser.KeyedSimpleFieldNameContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#keyedVariable}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitKeyedVariable(PHPParser.KeyedVariableContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#squareCurlyExpression}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitSquareCurlyExpression(PHPParser.SquareCurlyExpressionContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#assignmentList}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitAssignmentList(PHPParser.AssignmentListContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#assignmentListElement}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitAssignmentListElement(PHPParser.AssignmentListElementContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#modifier}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitModifier(PHPParser.ModifierContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#identifier}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitIdentifier(PHPParser.IdentifierContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#memberModifier}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitMemberModifier(PHPParser.MemberModifierContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#magicConstant}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitMagicConstant(PHPParser.MagicConstantContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#magicMethod}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitMagicMethod(PHPParser.MagicMethodContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#primitiveType}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitPrimitiveType(PHPParser.PrimitiveTypeContext ctx);
	/**
	 * Visit a parse tree produced by {@link PHPParser#castOperation}.
	 * @param ctx the parse tree
	 * @return the visitor result
	 */
	T visitCastOperation(PHPParser.CastOperationContext ctx);
}