package org.develnext.jphp.ext.javafx.controlfx;

import org.controlsfx.control.Rating;
import org.develnext.jphp.ext.javafx.controlfx.classes.UXRating;
import php.runtime.env.CompileScope;
import php.runtime.ext.support.Extension;

public class ControlFXExtension extends Extension {
    public final static String NS = "php\\gui";

    @Override
    public Status getStatus() {
        return Status.EXPERIMENTAL;
    }

    @Override
    public void onRegister(CompileScope scope) {
        registerWrapperClass(scope, Rating.class, UXRating.class);
    }
}
