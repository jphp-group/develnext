package org.develnext.jphp.gui.designer.classes;

import org.develnext.jphp.gui.designer.GuiDesignerExtension;
import org.develnext.jphp.gui.designer.SyntaxTextArea;
import org.fife.ui.autocomplete.*;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Property;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.lang.BaseWrapper;
import php.runtime.reflection.ClassEntity;

import javax.swing.*;

@Reflection.Namespace(GuiDesignerExtension.NS)
public class UXSyntaxAutoCompletion extends BaseWrapper<AutoCompletion> {
    interface WrappedInterface {
        @Property int autoActivationDelay();
        @Property boolean autoActivationEnabled();
        @Property boolean autoCompleteEnabled();

        void uninstall();
        void doCompletion();
    }

    static class CompilationProvider extends DefaultCompletionProvider {
        public void addBasicCompletion(String text, String desc) {
            addCompletion(new BasicCompletion(this, text, desc));
        }

        public void addVariableCompletion(String text, String type) {
            addCompletion(new VariableCompletion(this, text, type));
        }

        public void addShorthandCompletion(String text, String replacement, String desc) {
            addCompletion(new ShorthandCompletion(this, text, replacement, desc));
        }
    }

    public UXSyntaxAutoCompletion(Environment env, AutoCompletion wrappedObject) {
        super(env, wrappedObject);
    }

    public UXSyntaxAutoCompletion(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    private CompilationProvider getProvider() {
        return (CompilationProvider) getWrappedObject().getCompletionProvider();
    }

    @Signature
    public void __construct() {
        __wrappedObject = new AutoCompletion(new CompilationProvider());
        getWrappedObject().setAutoActivationDelay(200);
        getWrappedObject().setAutoCompleteEnabled(true);
        getWrappedObject().setAutoActivationEnabled(true);

        getProvider().setAutoActivationRules(true, "$");

        getWrappedObject().setTriggerKey(KeyStroke.getKeyStroke("control SPACE"));
    }

    @Signature
    public void install(SyntaxTextArea textArea) {
        getWrappedObject().install(textArea.getSyntaxTextArea());
    }

    @Signature
    public void clearCompletions() {
        getProvider().clear();
    }

    @Signature
    public void addCompletion(String text, String desc) {
        getProvider().addBasicCompletion(text, desc);
    }

    @Signature
    public void addShorthandCompletion(String text, String replacement, String desc) {
        getProvider().addShorthandCompletion(text, replacement, desc);
    }

    @Signature
    public void addVariableCompletion(String text, String type) {
        getProvider().addVariableCompletion(text, type);
    }
}
