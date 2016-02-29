package org.develnext.jphp.ext.javafx.richtext;

import org.develnext.jphp.ext.javafx.richtext.classes.UXRichTextArea;
import org.fxmisc.richtext.InlineCssTextArea;
import php.runtime.env.CompileScope;
import php.runtime.ext.support.Extension;

public class RichTextExtension extends Extension {
    public static final String NS = "php\\gui";

    @Override
    public Status getStatus() {
        return Status.EXPERIMENTAL;
    }

    @Override
    public void onRegister(CompileScope scope) {
        registerWrapperClass(scope, InlineCssTextArea.class, UXRichTextArea.class);
    }
}
