// Generated from src/main/antlr/js/ECMAScript.g4 by ANTLR 4.6
package org.develnext.lexer.js;
import org.antlr.v4.runtime.tree.ParseTreeListener;

/**
 * This interface defines a complete listener for a parse tree produced by
 * {@link ECMAScriptParser}.
 */
public interface ECMAScriptListener extends ParseTreeListener {
	/**
	 * Enter a parse tree produced by {@link ECMAScriptParser#program}.
	 * @param ctx the parse tree
	 */
	void enterProgram(ECMAScriptParser.ProgramContext ctx);
	/**
	 * Exit a parse tree produced by {@link ECMAScriptParser#program}.
	 * @param ctx the parse tree
	 */
	void exitProgram(ECMAScriptParser.ProgramContext ctx);
	/**
	 * Enter a parse tree produced by {@link ECMAScriptParser#sourceElements}.
	 * @param ctx the parse tree
	 */
	void enterSourceElements(ECMAScriptParser.SourceElementsContext ctx);
	/**
	 * Exit a parse tree produced by {@link ECMAScriptParser#sourceElements}.
	 * @param ctx the parse tree
	 */
	void exitSourceElements(ECMAScriptParser.SourceElementsContext ctx);
	/**
	 * Enter a parse tree produced by {@link ECMAScriptParser#sourceElement}.
	 * @param ctx the parse tree
	 */
	void enterSourceElement(ECMAScriptParser.SourceElementContext ctx);
	/**
	 * Exit a parse tree produced by {@link ECMAScriptParser#sourceElement}.
	 * @param ctx the parse tree
	 */
	void exitSourceElement(ECMAScriptParser.SourceElementContext ctx);
	/**
	 * Enter a parse tree produced by {@link ECMAScriptParser#statement}.
	 * @param ctx the parse tree
	 */
	void enterStatement(ECMAScriptParser.StatementContext ctx);
	/**
	 * Exit a parse tree produced by {@link ECMAScriptParser#statement}.
	 * @param ctx the parse tree
	 */
	void exitStatement(ECMAScriptParser.StatementContext ctx);
	/**
	 * Enter a parse tree produced by {@link ECMAScriptParser#block}.
	 * @param ctx the parse tree
	 */
	void enterBlock(ECMAScriptParser.BlockContext ctx);
	/**
	 * Exit a parse tree produced by {@link ECMAScriptParser#block}.
	 * @param ctx the parse tree
	 */
	void exitBlock(ECMAScriptParser.BlockContext ctx);
	/**
	 * Enter a parse tree produced by {@link ECMAScriptParser#statementList}.
	 * @param ctx the parse tree
	 */
	void enterStatementList(ECMAScriptParser.StatementListContext ctx);
	/**
	 * Exit a parse tree produced by {@link ECMAScriptParser#statementList}.
	 * @param ctx the parse tree
	 */
	void exitStatementList(ECMAScriptParser.StatementListContext ctx);
	/**
	 * Enter a parse tree produced by {@link ECMAScriptParser#variableStatement}.
	 * @param ctx the parse tree
	 */
	void enterVariableStatement(ECMAScriptParser.VariableStatementContext ctx);
	/**
	 * Exit a parse tree produced by {@link ECMAScriptParser#variableStatement}.
	 * @param ctx the parse tree
	 */
	void exitVariableStatement(ECMAScriptParser.VariableStatementContext ctx);
	/**
	 * Enter a parse tree produced by {@link ECMAScriptParser#variableDeclarationList}.
	 * @param ctx the parse tree
	 */
	void enterVariableDeclarationList(ECMAScriptParser.VariableDeclarationListContext ctx);
	/**
	 * Exit a parse tree produced by {@link ECMAScriptParser#variableDeclarationList}.
	 * @param ctx the parse tree
	 */
	void exitVariableDeclarationList(ECMAScriptParser.VariableDeclarationListContext ctx);
	/**
	 * Enter a parse tree produced by {@link ECMAScriptParser#variableDeclaration}.
	 * @param ctx the parse tree
	 */
	void enterVariableDeclaration(ECMAScriptParser.VariableDeclarationContext ctx);
	/**
	 * Exit a parse tree produced by {@link ECMAScriptParser#variableDeclaration}.
	 * @param ctx the parse tree
	 */
	void exitVariableDeclaration(ECMAScriptParser.VariableDeclarationContext ctx);
	/**
	 * Enter a parse tree produced by {@link ECMAScriptParser#initialiser}.
	 * @param ctx the parse tree
	 */
	void enterInitialiser(ECMAScriptParser.InitialiserContext ctx);
	/**
	 * Exit a parse tree produced by {@link ECMAScriptParser#initialiser}.
	 * @param ctx the parse tree
	 */
	void exitInitialiser(ECMAScriptParser.InitialiserContext ctx);
	/**
	 * Enter a parse tree produced by {@link ECMAScriptParser#emptyStatement}.
	 * @param ctx the parse tree
	 */
	void enterEmptyStatement(ECMAScriptParser.EmptyStatementContext ctx);
	/**
	 * Exit a parse tree produced by {@link ECMAScriptParser#emptyStatement}.
	 * @param ctx the parse tree
	 */
	void exitEmptyStatement(ECMAScriptParser.EmptyStatementContext ctx);
	/**
	 * Enter a parse tree produced by {@link ECMAScriptParser#expressionStatement}.
	 * @param ctx the parse tree
	 */
	void enterExpressionStatement(ECMAScriptParser.ExpressionStatementContext ctx);
	/**
	 * Exit a parse tree produced by {@link ECMAScriptParser#expressionStatement}.
	 * @param ctx the parse tree
	 */
	void exitExpressionStatement(ECMAScriptParser.ExpressionStatementContext ctx);
	/**
	 * Enter a parse tree produced by {@link ECMAScriptParser#ifStatement}.
	 * @param ctx the parse tree
	 */
	void enterIfStatement(ECMAScriptParser.IfStatementContext ctx);
	/**
	 * Exit a parse tree produced by {@link ECMAScriptParser#ifStatement}.
	 * @param ctx the parse tree
	 */
	void exitIfStatement(ECMAScriptParser.IfStatementContext ctx);
	/**
	 * Enter a parse tree produced by the {@code DoStatement}
	 * labeled alternative in {@link ECMAScriptParser#iterationStatement}.
	 * @param ctx the parse tree
	 */
	void enterDoStatement(ECMAScriptParser.DoStatementContext ctx);
	/**
	 * Exit a parse tree produced by the {@code DoStatement}
	 * labeled alternative in {@link ECMAScriptParser#iterationStatement}.
	 * @param ctx the parse tree
	 */
	void exitDoStatement(ECMAScriptParser.DoStatementContext ctx);
	/**
	 * Enter a parse tree produced by the {@code WhileStatement}
	 * labeled alternative in {@link ECMAScriptParser#iterationStatement}.
	 * @param ctx the parse tree
	 */
	void enterWhileStatement(ECMAScriptParser.WhileStatementContext ctx);
	/**
	 * Exit a parse tree produced by the {@code WhileStatement}
	 * labeled alternative in {@link ECMAScriptParser#iterationStatement}.
	 * @param ctx the parse tree
	 */
	void exitWhileStatement(ECMAScriptParser.WhileStatementContext ctx);
	/**
	 * Enter a parse tree produced by the {@code ForStatement}
	 * labeled alternative in {@link ECMAScriptParser#iterationStatement}.
	 * @param ctx the parse tree
	 */
	void enterForStatement(ECMAScriptParser.ForStatementContext ctx);
	/**
	 * Exit a parse tree produced by the {@code ForStatement}
	 * labeled alternative in {@link ECMAScriptParser#iterationStatement}.
	 * @param ctx the parse tree
	 */
	void exitForStatement(ECMAScriptParser.ForStatementContext ctx);
	/**
	 * Enter a parse tree produced by the {@code ForVarStatement}
	 * labeled alternative in {@link ECMAScriptParser#iterationStatement}.
	 * @param ctx the parse tree
	 */
	void enterForVarStatement(ECMAScriptParser.ForVarStatementContext ctx);
	/**
	 * Exit a parse tree produced by the {@code ForVarStatement}
	 * labeled alternative in {@link ECMAScriptParser#iterationStatement}.
	 * @param ctx the parse tree
	 */
	void exitForVarStatement(ECMAScriptParser.ForVarStatementContext ctx);
	/**
	 * Enter a parse tree produced by the {@code ForInStatement}
	 * labeled alternative in {@link ECMAScriptParser#iterationStatement}.
	 * @param ctx the parse tree
	 */
	void enterForInStatement(ECMAScriptParser.ForInStatementContext ctx);
	/**
	 * Exit a parse tree produced by the {@code ForInStatement}
	 * labeled alternative in {@link ECMAScriptParser#iterationStatement}.
	 * @param ctx the parse tree
	 */
	void exitForInStatement(ECMAScriptParser.ForInStatementContext ctx);
	/**
	 * Enter a parse tree produced by the {@code ForVarInStatement}
	 * labeled alternative in {@link ECMAScriptParser#iterationStatement}.
	 * @param ctx the parse tree
	 */
	void enterForVarInStatement(ECMAScriptParser.ForVarInStatementContext ctx);
	/**
	 * Exit a parse tree produced by the {@code ForVarInStatement}
	 * labeled alternative in {@link ECMAScriptParser#iterationStatement}.
	 * @param ctx the parse tree
	 */
	void exitForVarInStatement(ECMAScriptParser.ForVarInStatementContext ctx);
	/**
	 * Enter a parse tree produced by {@link ECMAScriptParser#continueStatement}.
	 * @param ctx the parse tree
	 */
	void enterContinueStatement(ECMAScriptParser.ContinueStatementContext ctx);
	/**
	 * Exit a parse tree produced by {@link ECMAScriptParser#continueStatement}.
	 * @param ctx the parse tree
	 */
	void exitContinueStatement(ECMAScriptParser.ContinueStatementContext ctx);
	/**
	 * Enter a parse tree produced by {@link ECMAScriptParser#breakStatement}.
	 * @param ctx the parse tree
	 */
	void enterBreakStatement(ECMAScriptParser.BreakStatementContext ctx);
	/**
	 * Exit a parse tree produced by {@link ECMAScriptParser#breakStatement}.
	 * @param ctx the parse tree
	 */
	void exitBreakStatement(ECMAScriptParser.BreakStatementContext ctx);
	/**
	 * Enter a parse tree produced by {@link ECMAScriptParser#returnStatement}.
	 * @param ctx the parse tree
	 */
	void enterReturnStatement(ECMAScriptParser.ReturnStatementContext ctx);
	/**
	 * Exit a parse tree produced by {@link ECMAScriptParser#returnStatement}.
	 * @param ctx the parse tree
	 */
	void exitReturnStatement(ECMAScriptParser.ReturnStatementContext ctx);
	/**
	 * Enter a parse tree produced by {@link ECMAScriptParser#withStatement}.
	 * @param ctx the parse tree
	 */
	void enterWithStatement(ECMAScriptParser.WithStatementContext ctx);
	/**
	 * Exit a parse tree produced by {@link ECMAScriptParser#withStatement}.
	 * @param ctx the parse tree
	 */
	void exitWithStatement(ECMAScriptParser.WithStatementContext ctx);
	/**
	 * Enter a parse tree produced by {@link ECMAScriptParser#switchStatement}.
	 * @param ctx the parse tree
	 */
	void enterSwitchStatement(ECMAScriptParser.SwitchStatementContext ctx);
	/**
	 * Exit a parse tree produced by {@link ECMAScriptParser#switchStatement}.
	 * @param ctx the parse tree
	 */
	void exitSwitchStatement(ECMAScriptParser.SwitchStatementContext ctx);
	/**
	 * Enter a parse tree produced by {@link ECMAScriptParser#caseBlock}.
	 * @param ctx the parse tree
	 */
	void enterCaseBlock(ECMAScriptParser.CaseBlockContext ctx);
	/**
	 * Exit a parse tree produced by {@link ECMAScriptParser#caseBlock}.
	 * @param ctx the parse tree
	 */
	void exitCaseBlock(ECMAScriptParser.CaseBlockContext ctx);
	/**
	 * Enter a parse tree produced by {@link ECMAScriptParser#caseClauses}.
	 * @param ctx the parse tree
	 */
	void enterCaseClauses(ECMAScriptParser.CaseClausesContext ctx);
	/**
	 * Exit a parse tree produced by {@link ECMAScriptParser#caseClauses}.
	 * @param ctx the parse tree
	 */
	void exitCaseClauses(ECMAScriptParser.CaseClausesContext ctx);
	/**
	 * Enter a parse tree produced by {@link ECMAScriptParser#caseClause}.
	 * @param ctx the parse tree
	 */
	void enterCaseClause(ECMAScriptParser.CaseClauseContext ctx);
	/**
	 * Exit a parse tree produced by {@link ECMAScriptParser#caseClause}.
	 * @param ctx the parse tree
	 */
	void exitCaseClause(ECMAScriptParser.CaseClauseContext ctx);
	/**
	 * Enter a parse tree produced by {@link ECMAScriptParser#defaultClause}.
	 * @param ctx the parse tree
	 */
	void enterDefaultClause(ECMAScriptParser.DefaultClauseContext ctx);
	/**
	 * Exit a parse tree produced by {@link ECMAScriptParser#defaultClause}.
	 * @param ctx the parse tree
	 */
	void exitDefaultClause(ECMAScriptParser.DefaultClauseContext ctx);
	/**
	 * Enter a parse tree produced by {@link ECMAScriptParser#labelledStatement}.
	 * @param ctx the parse tree
	 */
	void enterLabelledStatement(ECMAScriptParser.LabelledStatementContext ctx);
	/**
	 * Exit a parse tree produced by {@link ECMAScriptParser#labelledStatement}.
	 * @param ctx the parse tree
	 */
	void exitLabelledStatement(ECMAScriptParser.LabelledStatementContext ctx);
	/**
	 * Enter a parse tree produced by {@link ECMAScriptParser#throwStatement}.
	 * @param ctx the parse tree
	 */
	void enterThrowStatement(ECMAScriptParser.ThrowStatementContext ctx);
	/**
	 * Exit a parse tree produced by {@link ECMAScriptParser#throwStatement}.
	 * @param ctx the parse tree
	 */
	void exitThrowStatement(ECMAScriptParser.ThrowStatementContext ctx);
	/**
	 * Enter a parse tree produced by {@link ECMAScriptParser#tryStatement}.
	 * @param ctx the parse tree
	 */
	void enterTryStatement(ECMAScriptParser.TryStatementContext ctx);
	/**
	 * Exit a parse tree produced by {@link ECMAScriptParser#tryStatement}.
	 * @param ctx the parse tree
	 */
	void exitTryStatement(ECMAScriptParser.TryStatementContext ctx);
	/**
	 * Enter a parse tree produced by {@link ECMAScriptParser#catchProduction}.
	 * @param ctx the parse tree
	 */
	void enterCatchProduction(ECMAScriptParser.CatchProductionContext ctx);
	/**
	 * Exit a parse tree produced by {@link ECMAScriptParser#catchProduction}.
	 * @param ctx the parse tree
	 */
	void exitCatchProduction(ECMAScriptParser.CatchProductionContext ctx);
	/**
	 * Enter a parse tree produced by {@link ECMAScriptParser#finallyProduction}.
	 * @param ctx the parse tree
	 */
	void enterFinallyProduction(ECMAScriptParser.FinallyProductionContext ctx);
	/**
	 * Exit a parse tree produced by {@link ECMAScriptParser#finallyProduction}.
	 * @param ctx the parse tree
	 */
	void exitFinallyProduction(ECMAScriptParser.FinallyProductionContext ctx);
	/**
	 * Enter a parse tree produced by {@link ECMAScriptParser#debuggerStatement}.
	 * @param ctx the parse tree
	 */
	void enterDebuggerStatement(ECMAScriptParser.DebuggerStatementContext ctx);
	/**
	 * Exit a parse tree produced by {@link ECMAScriptParser#debuggerStatement}.
	 * @param ctx the parse tree
	 */
	void exitDebuggerStatement(ECMAScriptParser.DebuggerStatementContext ctx);
	/**
	 * Enter a parse tree produced by {@link ECMAScriptParser#functionDeclaration}.
	 * @param ctx the parse tree
	 */
	void enterFunctionDeclaration(ECMAScriptParser.FunctionDeclarationContext ctx);
	/**
	 * Exit a parse tree produced by {@link ECMAScriptParser#functionDeclaration}.
	 * @param ctx the parse tree
	 */
	void exitFunctionDeclaration(ECMAScriptParser.FunctionDeclarationContext ctx);
	/**
	 * Enter a parse tree produced by {@link ECMAScriptParser#formalParameterList}.
	 * @param ctx the parse tree
	 */
	void enterFormalParameterList(ECMAScriptParser.FormalParameterListContext ctx);
	/**
	 * Exit a parse tree produced by {@link ECMAScriptParser#formalParameterList}.
	 * @param ctx the parse tree
	 */
	void exitFormalParameterList(ECMAScriptParser.FormalParameterListContext ctx);
	/**
	 * Enter a parse tree produced by {@link ECMAScriptParser#functionBody}.
	 * @param ctx the parse tree
	 */
	void enterFunctionBody(ECMAScriptParser.FunctionBodyContext ctx);
	/**
	 * Exit a parse tree produced by {@link ECMAScriptParser#functionBody}.
	 * @param ctx the parse tree
	 */
	void exitFunctionBody(ECMAScriptParser.FunctionBodyContext ctx);
	/**
	 * Enter a parse tree produced by {@link ECMAScriptParser#arrayLiteral}.
	 * @param ctx the parse tree
	 */
	void enterArrayLiteral(ECMAScriptParser.ArrayLiteralContext ctx);
	/**
	 * Exit a parse tree produced by {@link ECMAScriptParser#arrayLiteral}.
	 * @param ctx the parse tree
	 */
	void exitArrayLiteral(ECMAScriptParser.ArrayLiteralContext ctx);
	/**
	 * Enter a parse tree produced by {@link ECMAScriptParser#elementList}.
	 * @param ctx the parse tree
	 */
	void enterElementList(ECMAScriptParser.ElementListContext ctx);
	/**
	 * Exit a parse tree produced by {@link ECMAScriptParser#elementList}.
	 * @param ctx the parse tree
	 */
	void exitElementList(ECMAScriptParser.ElementListContext ctx);
	/**
	 * Enter a parse tree produced by {@link ECMAScriptParser#elision}.
	 * @param ctx the parse tree
	 */
	void enterElision(ECMAScriptParser.ElisionContext ctx);
	/**
	 * Exit a parse tree produced by {@link ECMAScriptParser#elision}.
	 * @param ctx the parse tree
	 */
	void exitElision(ECMAScriptParser.ElisionContext ctx);
	/**
	 * Enter a parse tree produced by {@link ECMAScriptParser#objectLiteral}.
	 * @param ctx the parse tree
	 */
	void enterObjectLiteral(ECMAScriptParser.ObjectLiteralContext ctx);
	/**
	 * Exit a parse tree produced by {@link ECMAScriptParser#objectLiteral}.
	 * @param ctx the parse tree
	 */
	void exitObjectLiteral(ECMAScriptParser.ObjectLiteralContext ctx);
	/**
	 * Enter a parse tree produced by {@link ECMAScriptParser#propertyNameAndValueList}.
	 * @param ctx the parse tree
	 */
	void enterPropertyNameAndValueList(ECMAScriptParser.PropertyNameAndValueListContext ctx);
	/**
	 * Exit a parse tree produced by {@link ECMAScriptParser#propertyNameAndValueList}.
	 * @param ctx the parse tree
	 */
	void exitPropertyNameAndValueList(ECMAScriptParser.PropertyNameAndValueListContext ctx);
	/**
	 * Enter a parse tree produced by the {@code PropertyExpressionAssignment}
	 * labeled alternative in {@link ECMAScriptParser#propertyAssignment}.
	 * @param ctx the parse tree
	 */
	void enterPropertyExpressionAssignment(ECMAScriptParser.PropertyExpressionAssignmentContext ctx);
	/**
	 * Exit a parse tree produced by the {@code PropertyExpressionAssignment}
	 * labeled alternative in {@link ECMAScriptParser#propertyAssignment}.
	 * @param ctx the parse tree
	 */
	void exitPropertyExpressionAssignment(ECMAScriptParser.PropertyExpressionAssignmentContext ctx);
	/**
	 * Enter a parse tree produced by the {@code PropertyGetter}
	 * labeled alternative in {@link ECMAScriptParser#propertyAssignment}.
	 * @param ctx the parse tree
	 */
	void enterPropertyGetter(ECMAScriptParser.PropertyGetterContext ctx);
	/**
	 * Exit a parse tree produced by the {@code PropertyGetter}
	 * labeled alternative in {@link ECMAScriptParser#propertyAssignment}.
	 * @param ctx the parse tree
	 */
	void exitPropertyGetter(ECMAScriptParser.PropertyGetterContext ctx);
	/**
	 * Enter a parse tree produced by the {@code PropertySetter}
	 * labeled alternative in {@link ECMAScriptParser#propertyAssignment}.
	 * @param ctx the parse tree
	 */
	void enterPropertySetter(ECMAScriptParser.PropertySetterContext ctx);
	/**
	 * Exit a parse tree produced by the {@code PropertySetter}
	 * labeled alternative in {@link ECMAScriptParser#propertyAssignment}.
	 * @param ctx the parse tree
	 */
	void exitPropertySetter(ECMAScriptParser.PropertySetterContext ctx);
	/**
	 * Enter a parse tree produced by {@link ECMAScriptParser#propertyName}.
	 * @param ctx the parse tree
	 */
	void enterPropertyName(ECMAScriptParser.PropertyNameContext ctx);
	/**
	 * Exit a parse tree produced by {@link ECMAScriptParser#propertyName}.
	 * @param ctx the parse tree
	 */
	void exitPropertyName(ECMAScriptParser.PropertyNameContext ctx);
	/**
	 * Enter a parse tree produced by {@link ECMAScriptParser#propertySetParameterList}.
	 * @param ctx the parse tree
	 */
	void enterPropertySetParameterList(ECMAScriptParser.PropertySetParameterListContext ctx);
	/**
	 * Exit a parse tree produced by {@link ECMAScriptParser#propertySetParameterList}.
	 * @param ctx the parse tree
	 */
	void exitPropertySetParameterList(ECMAScriptParser.PropertySetParameterListContext ctx);
	/**
	 * Enter a parse tree produced by {@link ECMAScriptParser#arguments}.
	 * @param ctx the parse tree
	 */
	void enterArguments(ECMAScriptParser.ArgumentsContext ctx);
	/**
	 * Exit a parse tree produced by {@link ECMAScriptParser#arguments}.
	 * @param ctx the parse tree
	 */
	void exitArguments(ECMAScriptParser.ArgumentsContext ctx);
	/**
	 * Enter a parse tree produced by {@link ECMAScriptParser#argumentList}.
	 * @param ctx the parse tree
	 */
	void enterArgumentList(ECMAScriptParser.ArgumentListContext ctx);
	/**
	 * Exit a parse tree produced by {@link ECMAScriptParser#argumentList}.
	 * @param ctx the parse tree
	 */
	void exitArgumentList(ECMAScriptParser.ArgumentListContext ctx);
	/**
	 * Enter a parse tree produced by {@link ECMAScriptParser#expressionSequence}.
	 * @param ctx the parse tree
	 */
	void enterExpressionSequence(ECMAScriptParser.ExpressionSequenceContext ctx);
	/**
	 * Exit a parse tree produced by {@link ECMAScriptParser#expressionSequence}.
	 * @param ctx the parse tree
	 */
	void exitExpressionSequence(ECMAScriptParser.ExpressionSequenceContext ctx);
	/**
	 * Enter a parse tree produced by the {@code TernaryExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void enterTernaryExpression(ECMAScriptParser.TernaryExpressionContext ctx);
	/**
	 * Exit a parse tree produced by the {@code TernaryExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void exitTernaryExpression(ECMAScriptParser.TernaryExpressionContext ctx);
	/**
	 * Enter a parse tree produced by the {@code BitOrExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void enterBitOrExpression(ECMAScriptParser.BitOrExpressionContext ctx);
	/**
	 * Exit a parse tree produced by the {@code BitOrExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void exitBitOrExpression(ECMAScriptParser.BitOrExpressionContext ctx);
	/**
	 * Enter a parse tree produced by the {@code AssignmentExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void enterAssignmentExpression(ECMAScriptParser.AssignmentExpressionContext ctx);
	/**
	 * Exit a parse tree produced by the {@code AssignmentExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void exitAssignmentExpression(ECMAScriptParser.AssignmentExpressionContext ctx);
	/**
	 * Enter a parse tree produced by the {@code LogicalAndExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void enterLogicalAndExpression(ECMAScriptParser.LogicalAndExpressionContext ctx);
	/**
	 * Exit a parse tree produced by the {@code LogicalAndExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void exitLogicalAndExpression(ECMAScriptParser.LogicalAndExpressionContext ctx);
	/**
	 * Enter a parse tree produced by the {@code InstanceofExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void enterInstanceofExpression(ECMAScriptParser.InstanceofExpressionContext ctx);
	/**
	 * Exit a parse tree produced by the {@code InstanceofExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void exitInstanceofExpression(ECMAScriptParser.InstanceofExpressionContext ctx);
	/**
	 * Enter a parse tree produced by the {@code ObjectLiteralExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void enterObjectLiteralExpression(ECMAScriptParser.ObjectLiteralExpressionContext ctx);
	/**
	 * Exit a parse tree produced by the {@code ObjectLiteralExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void exitObjectLiteralExpression(ECMAScriptParser.ObjectLiteralExpressionContext ctx);
	/**
	 * Enter a parse tree produced by the {@code PreDecreaseExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void enterPreDecreaseExpression(ECMAScriptParser.PreDecreaseExpressionContext ctx);
	/**
	 * Exit a parse tree produced by the {@code PreDecreaseExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void exitPreDecreaseExpression(ECMAScriptParser.PreDecreaseExpressionContext ctx);
	/**
	 * Enter a parse tree produced by the {@code ArrayLiteralExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void enterArrayLiteralExpression(ECMAScriptParser.ArrayLiteralExpressionContext ctx);
	/**
	 * Exit a parse tree produced by the {@code ArrayLiteralExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void exitArrayLiteralExpression(ECMAScriptParser.ArrayLiteralExpressionContext ctx);
	/**
	 * Enter a parse tree produced by the {@code InExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void enterInExpression(ECMAScriptParser.InExpressionContext ctx);
	/**
	 * Exit a parse tree produced by the {@code InExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void exitInExpression(ECMAScriptParser.InExpressionContext ctx);
	/**
	 * Enter a parse tree produced by the {@code ArgumentsExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void enterArgumentsExpression(ECMAScriptParser.ArgumentsExpressionContext ctx);
	/**
	 * Exit a parse tree produced by the {@code ArgumentsExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void exitArgumentsExpression(ECMAScriptParser.ArgumentsExpressionContext ctx);
	/**
	 * Enter a parse tree produced by the {@code MemberDotExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void enterMemberDotExpression(ECMAScriptParser.MemberDotExpressionContext ctx);
	/**
	 * Exit a parse tree produced by the {@code MemberDotExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void exitMemberDotExpression(ECMAScriptParser.MemberDotExpressionContext ctx);
	/**
	 * Enter a parse tree produced by the {@code NotExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void enterNotExpression(ECMAScriptParser.NotExpressionContext ctx);
	/**
	 * Exit a parse tree produced by the {@code NotExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void exitNotExpression(ECMAScriptParser.NotExpressionContext ctx);
	/**
	 * Enter a parse tree produced by the {@code DeleteExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void enterDeleteExpression(ECMAScriptParser.DeleteExpressionContext ctx);
	/**
	 * Exit a parse tree produced by the {@code DeleteExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void exitDeleteExpression(ECMAScriptParser.DeleteExpressionContext ctx);
	/**
	 * Enter a parse tree produced by the {@code IdentifierExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void enterIdentifierExpression(ECMAScriptParser.IdentifierExpressionContext ctx);
	/**
	 * Exit a parse tree produced by the {@code IdentifierExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void exitIdentifierExpression(ECMAScriptParser.IdentifierExpressionContext ctx);
	/**
	 * Enter a parse tree produced by the {@code BitAndExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void enterBitAndExpression(ECMAScriptParser.BitAndExpressionContext ctx);
	/**
	 * Exit a parse tree produced by the {@code BitAndExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void exitBitAndExpression(ECMAScriptParser.BitAndExpressionContext ctx);
	/**
	 * Enter a parse tree produced by the {@code UnaryMinusExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void enterUnaryMinusExpression(ECMAScriptParser.UnaryMinusExpressionContext ctx);
	/**
	 * Exit a parse tree produced by the {@code UnaryMinusExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void exitUnaryMinusExpression(ECMAScriptParser.UnaryMinusExpressionContext ctx);
	/**
	 * Enter a parse tree produced by the {@code PreIncrementExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void enterPreIncrementExpression(ECMAScriptParser.PreIncrementExpressionContext ctx);
	/**
	 * Exit a parse tree produced by the {@code PreIncrementExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void exitPreIncrementExpression(ECMAScriptParser.PreIncrementExpressionContext ctx);
	/**
	 * Enter a parse tree produced by the {@code FunctionExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void enterFunctionExpression(ECMAScriptParser.FunctionExpressionContext ctx);
	/**
	 * Exit a parse tree produced by the {@code FunctionExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void exitFunctionExpression(ECMAScriptParser.FunctionExpressionContext ctx);
	/**
	 * Enter a parse tree produced by the {@code BitShiftExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void enterBitShiftExpression(ECMAScriptParser.BitShiftExpressionContext ctx);
	/**
	 * Exit a parse tree produced by the {@code BitShiftExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void exitBitShiftExpression(ECMAScriptParser.BitShiftExpressionContext ctx);
	/**
	 * Enter a parse tree produced by the {@code LogicalOrExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void enterLogicalOrExpression(ECMAScriptParser.LogicalOrExpressionContext ctx);
	/**
	 * Exit a parse tree produced by the {@code LogicalOrExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void exitLogicalOrExpression(ECMAScriptParser.LogicalOrExpressionContext ctx);
	/**
	 * Enter a parse tree produced by the {@code VoidExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void enterVoidExpression(ECMAScriptParser.VoidExpressionContext ctx);
	/**
	 * Exit a parse tree produced by the {@code VoidExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void exitVoidExpression(ECMAScriptParser.VoidExpressionContext ctx);
	/**
	 * Enter a parse tree produced by the {@code ParenthesizedExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void enterParenthesizedExpression(ECMAScriptParser.ParenthesizedExpressionContext ctx);
	/**
	 * Exit a parse tree produced by the {@code ParenthesizedExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void exitParenthesizedExpression(ECMAScriptParser.ParenthesizedExpressionContext ctx);
	/**
	 * Enter a parse tree produced by the {@code UnaryPlusExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void enterUnaryPlusExpression(ECMAScriptParser.UnaryPlusExpressionContext ctx);
	/**
	 * Exit a parse tree produced by the {@code UnaryPlusExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void exitUnaryPlusExpression(ECMAScriptParser.UnaryPlusExpressionContext ctx);
	/**
	 * Enter a parse tree produced by the {@code LiteralExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void enterLiteralExpression(ECMAScriptParser.LiteralExpressionContext ctx);
	/**
	 * Exit a parse tree produced by the {@code LiteralExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void exitLiteralExpression(ECMAScriptParser.LiteralExpressionContext ctx);
	/**
	 * Enter a parse tree produced by the {@code BitNotExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void enterBitNotExpression(ECMAScriptParser.BitNotExpressionContext ctx);
	/**
	 * Exit a parse tree produced by the {@code BitNotExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void exitBitNotExpression(ECMAScriptParser.BitNotExpressionContext ctx);
	/**
	 * Enter a parse tree produced by the {@code PostIncrementExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void enterPostIncrementExpression(ECMAScriptParser.PostIncrementExpressionContext ctx);
	/**
	 * Exit a parse tree produced by the {@code PostIncrementExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void exitPostIncrementExpression(ECMAScriptParser.PostIncrementExpressionContext ctx);
	/**
	 * Enter a parse tree produced by the {@code TypeofExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void enterTypeofExpression(ECMAScriptParser.TypeofExpressionContext ctx);
	/**
	 * Exit a parse tree produced by the {@code TypeofExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void exitTypeofExpression(ECMAScriptParser.TypeofExpressionContext ctx);
	/**
	 * Enter a parse tree produced by the {@code AssignmentOperatorExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void enterAssignmentOperatorExpression(ECMAScriptParser.AssignmentOperatorExpressionContext ctx);
	/**
	 * Exit a parse tree produced by the {@code AssignmentOperatorExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void exitAssignmentOperatorExpression(ECMAScriptParser.AssignmentOperatorExpressionContext ctx);
	/**
	 * Enter a parse tree produced by the {@code NewExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void enterNewExpression(ECMAScriptParser.NewExpressionContext ctx);
	/**
	 * Exit a parse tree produced by the {@code NewExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void exitNewExpression(ECMAScriptParser.NewExpressionContext ctx);
	/**
	 * Enter a parse tree produced by the {@code PostDecreaseExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void enterPostDecreaseExpression(ECMAScriptParser.PostDecreaseExpressionContext ctx);
	/**
	 * Exit a parse tree produced by the {@code PostDecreaseExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void exitPostDecreaseExpression(ECMAScriptParser.PostDecreaseExpressionContext ctx);
	/**
	 * Enter a parse tree produced by the {@code RelationalExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void enterRelationalExpression(ECMAScriptParser.RelationalExpressionContext ctx);
	/**
	 * Exit a parse tree produced by the {@code RelationalExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void exitRelationalExpression(ECMAScriptParser.RelationalExpressionContext ctx);
	/**
	 * Enter a parse tree produced by the {@code EqualityExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void enterEqualityExpression(ECMAScriptParser.EqualityExpressionContext ctx);
	/**
	 * Exit a parse tree produced by the {@code EqualityExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void exitEqualityExpression(ECMAScriptParser.EqualityExpressionContext ctx);
	/**
	 * Enter a parse tree produced by the {@code BitXOrExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void enterBitXOrExpression(ECMAScriptParser.BitXOrExpressionContext ctx);
	/**
	 * Exit a parse tree produced by the {@code BitXOrExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void exitBitXOrExpression(ECMAScriptParser.BitXOrExpressionContext ctx);
	/**
	 * Enter a parse tree produced by the {@code AdditiveExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void enterAdditiveExpression(ECMAScriptParser.AdditiveExpressionContext ctx);
	/**
	 * Exit a parse tree produced by the {@code AdditiveExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void exitAdditiveExpression(ECMAScriptParser.AdditiveExpressionContext ctx);
	/**
	 * Enter a parse tree produced by the {@code MemberIndexExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void enterMemberIndexExpression(ECMAScriptParser.MemberIndexExpressionContext ctx);
	/**
	 * Exit a parse tree produced by the {@code MemberIndexExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void exitMemberIndexExpression(ECMAScriptParser.MemberIndexExpressionContext ctx);
	/**
	 * Enter a parse tree produced by the {@code ThisExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void enterThisExpression(ECMAScriptParser.ThisExpressionContext ctx);
	/**
	 * Exit a parse tree produced by the {@code ThisExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void exitThisExpression(ECMAScriptParser.ThisExpressionContext ctx);
	/**
	 * Enter a parse tree produced by the {@code MultiplicativeExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void enterMultiplicativeExpression(ECMAScriptParser.MultiplicativeExpressionContext ctx);
	/**
	 * Exit a parse tree produced by the {@code MultiplicativeExpression}
	 * labeled alternative in {@link ECMAScriptParser#singleExpression}.
	 * @param ctx the parse tree
	 */
	void exitMultiplicativeExpression(ECMAScriptParser.MultiplicativeExpressionContext ctx);
	/**
	 * Enter a parse tree produced by {@link ECMAScriptParser#assignmentOperator}.
	 * @param ctx the parse tree
	 */
	void enterAssignmentOperator(ECMAScriptParser.AssignmentOperatorContext ctx);
	/**
	 * Exit a parse tree produced by {@link ECMAScriptParser#assignmentOperator}.
	 * @param ctx the parse tree
	 */
	void exitAssignmentOperator(ECMAScriptParser.AssignmentOperatorContext ctx);
	/**
	 * Enter a parse tree produced by {@link ECMAScriptParser#literal}.
	 * @param ctx the parse tree
	 */
	void enterLiteral(ECMAScriptParser.LiteralContext ctx);
	/**
	 * Exit a parse tree produced by {@link ECMAScriptParser#literal}.
	 * @param ctx the parse tree
	 */
	void exitLiteral(ECMAScriptParser.LiteralContext ctx);
	/**
	 * Enter a parse tree produced by {@link ECMAScriptParser#numericLiteral}.
	 * @param ctx the parse tree
	 */
	void enterNumericLiteral(ECMAScriptParser.NumericLiteralContext ctx);
	/**
	 * Exit a parse tree produced by {@link ECMAScriptParser#numericLiteral}.
	 * @param ctx the parse tree
	 */
	void exitNumericLiteral(ECMAScriptParser.NumericLiteralContext ctx);
	/**
	 * Enter a parse tree produced by {@link ECMAScriptParser#identifierName}.
	 * @param ctx the parse tree
	 */
	void enterIdentifierName(ECMAScriptParser.IdentifierNameContext ctx);
	/**
	 * Exit a parse tree produced by {@link ECMAScriptParser#identifierName}.
	 * @param ctx the parse tree
	 */
	void exitIdentifierName(ECMAScriptParser.IdentifierNameContext ctx);
	/**
	 * Enter a parse tree produced by {@link ECMAScriptParser#reservedWord}.
	 * @param ctx the parse tree
	 */
	void enterReservedWord(ECMAScriptParser.ReservedWordContext ctx);
	/**
	 * Exit a parse tree produced by {@link ECMAScriptParser#reservedWord}.
	 * @param ctx the parse tree
	 */
	void exitReservedWord(ECMAScriptParser.ReservedWordContext ctx);
	/**
	 * Enter a parse tree produced by {@link ECMAScriptParser#keyword}.
	 * @param ctx the parse tree
	 */
	void enterKeyword(ECMAScriptParser.KeywordContext ctx);
	/**
	 * Exit a parse tree produced by {@link ECMAScriptParser#keyword}.
	 * @param ctx the parse tree
	 */
	void exitKeyword(ECMAScriptParser.KeywordContext ctx);
	/**
	 * Enter a parse tree produced by {@link ECMAScriptParser#futureReservedWord}.
	 * @param ctx the parse tree
	 */
	void enterFutureReservedWord(ECMAScriptParser.FutureReservedWordContext ctx);
	/**
	 * Exit a parse tree produced by {@link ECMAScriptParser#futureReservedWord}.
	 * @param ctx the parse tree
	 */
	void exitFutureReservedWord(ECMAScriptParser.FutureReservedWordContext ctx);
	/**
	 * Enter a parse tree produced by {@link ECMAScriptParser#getter}.
	 * @param ctx the parse tree
	 */
	void enterGetter(ECMAScriptParser.GetterContext ctx);
	/**
	 * Exit a parse tree produced by {@link ECMAScriptParser#getter}.
	 * @param ctx the parse tree
	 */
	void exitGetter(ECMAScriptParser.GetterContext ctx);
	/**
	 * Enter a parse tree produced by {@link ECMAScriptParser#setter}.
	 * @param ctx the parse tree
	 */
	void enterSetter(ECMAScriptParser.SetterContext ctx);
	/**
	 * Exit a parse tree produced by {@link ECMAScriptParser#setter}.
	 * @param ctx the parse tree
	 */
	void exitSetter(ECMAScriptParser.SetterContext ctx);
	/**
	 * Enter a parse tree produced by {@link ECMAScriptParser#eos}.
	 * @param ctx the parse tree
	 */
	void enterEos(ECMAScriptParser.EosContext ctx);
	/**
	 * Exit a parse tree produced by {@link ECMAScriptParser#eos}.
	 * @param ctx the parse tree
	 */
	void exitEos(ECMAScriptParser.EosContext ctx);
	/**
	 * Enter a parse tree produced by {@link ECMAScriptParser#eof}.
	 * @param ctx the parse tree
	 */
	void enterEof(ECMAScriptParser.EofContext ctx);
	/**
	 * Exit a parse tree produced by {@link ECMAScriptParser#eof}.
	 * @param ctx the parse tree
	 */
	void exitEof(ECMAScriptParser.EofContext ctx);
}