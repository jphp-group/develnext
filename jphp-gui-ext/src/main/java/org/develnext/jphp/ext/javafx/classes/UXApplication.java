package org.develnext.jphp.ext.javafx.classes;

import com.sun.javafx.css.StyleManager;
import javafx.application.Application;
import javafx.application.Platform;
import javafx.embed.swing.JFXPanel;
import javafx.scene.control.Button;
import javafx.stage.Stage;
import org.develnext.jphp.ext.javafx.JavaFXExtension;
import php.runtime.Memory;
import php.runtime.annotation.Reflection.Abstract;
import php.runtime.annotation.Reflection.Name;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.exceptions.CriticalException;
import php.runtime.ext.core.classes.stream.ResourceStream;
import php.runtime.ext.core.classes.stream.Stream;
import php.runtime.invoke.Invoker;
import php.runtime.lang.BaseWrapper;
import php.runtime.reflection.ClassEntity;

@Abstract
@Name(JavaFXExtension.NS + "UXApplication")
public class UXApplication extends BaseWrapper<Application> {
    private static Invoker onStart;

    public UXApplication(Environment env, Application wrappedObject) {
        super(env, wrappedObject);
    }

    public UXApplication(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public static void setTheme(Memory value) {
        StyleManager styleManager = StyleManager.getInstance();

        if (value.instanceOf(Stream.class)) {
            if (value.instanceOf(ResourceStream.class)) {
                styleManager.setDefaultUserAgentStylesheet(value.toObject(ResourceStream.class).getUrl().toExternalForm());
            } else {
                styleManager.setDefaultUserAgentStylesheet(value.toObject(Stream.class).getPath());
            }
        } else {
            styleManager.setDefaultUserAgentStylesheet(value.toString());
        }
    }

    @Signature
    public static void runLater(final Invoker callback) {
        new JFXPanel();

        Platform.runLater(new Runnable() {
            @Override
            public void run() {
                try {
                    callback.callNoThrow();
                } catch (Exception e) {
                    callback.getEnvironment().catchUncaught(e);
                }
            }
        });
    }

    @Signature
    public static void launch(Invoker onStart) {
        Environment.addThreadSupport();

        UXApplication.onStart = onStart;

        Application.launch(CustomApplication.class);
    }

    public static class CustomApplication extends Application {
        @Override
        public void start(Stage stage) throws Exception {
            Environment.addThreadSupport();

            Thread thread = Thread.currentThread();
            ClassLoader old = thread.getContextClassLoader();

            //thread.setUncaughtExceptionHandler(buildUncaughtExceptionHandler(onStart.getEnvironment()));

            new Button();  // fix.
            try {
                thread.setContextClassLoader(onStart.getEnvironment().scope.getClassLoader());
                UXApplication.onStart.callAny(stage);
            } catch (Exception throwable) {
                if (throwable instanceof CriticalException) {
                    throwable.printStackTrace();
                }

                onStart.getEnvironment().catchUncaught(throwable);
            } catch (Throwable throwable) {
                throw throwable;
            } finally {
                thread.setContextClassLoader(old);
            }
        }
    }
}
