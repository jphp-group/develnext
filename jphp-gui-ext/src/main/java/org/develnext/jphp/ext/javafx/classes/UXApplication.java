package org.develnext.jphp.ext.javafx.classes;

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
import php.runtime.exceptions.CustomErrorException;
import php.runtime.ext.core.classes.stream.ResourceStream;
import php.runtime.ext.core.classes.stream.Stream;
import php.runtime.invoke.Invoker;
import php.runtime.lang.BaseWrapper;
import php.runtime.reflection.ClassEntity;

import java.awt.*;
import java.lang.management.ManagementFactory;
import java.net.InetAddress;
import java.net.NetworkInterface;
import java.net.SocketException;
import java.net.UnknownHostException;

@Abstract
@Name(JavaFXExtension.NS + "UXApplication")
public class UXApplication extends BaseWrapper<Application> {
    private static Invoker onStart;
    private static boolean shutdown = false;

    public UXApplication(Environment env, Application wrappedObject) {
        super(env, wrappedObject);
    }

    public UXApplication(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public static String getPid() {
        // Should return something like '<pid>@<hostname>', at least in SUN / Oracle JVMs
        final String jvmName = ManagementFactory.getRuntimeMXBean().getName();
        final int index = jvmName.indexOf('@');

        String pid = jvmName.substring(0, index);
        return pid;
    }

    @Signature
    public static boolean isShutdown() {
        return !Platform.isFxApplicationThread();
    }

    @Signature
    public static String getMacAddress() {
        InetAddress ip;

        try {
            ip = InetAddress.getLocalHost();
            NetworkInterface network = NetworkInterface.getByInetAddress(ip);

            byte[] mac = network.getHardwareAddress();

            StringBuilder sb = new StringBuilder();

            for (int i = 0; i < mac.length; i++) {
                sb.append(String.format("%02X%s", mac[i], (i < mac.length - 1) ? "-" : ""));
            }

            return sb.toString();
        } catch (UnknownHostException e) {
            return null;
        } catch (SocketException e){
            return null;
        }
    }

    @Signature
    public static void setTheme(final Memory value) {
        new JFXPanel();

        Platform.runLater(new Runnable() {
            @Override
            public void run() {
                if (value.instanceOf(Stream.class)) {
                    if (value.instanceOf(ResourceStream.class)) {
                        Application.setUserAgentStylesheet(value.toObject(ResourceStream.class).getUrl().toExternalForm());
                    } else {
                        Application.setUserAgentStylesheet(value.toObject(Stream.class).getPath());
                    }
                } else {
                    Application.setUserAgentStylesheet(value.toString());
                }
            }
        });
    }

    @Signature
    public static void runLater(final Invoker callback) {
        new JFXPanel();

        Platform.runLater(new Runnable() {
            @Override
            public void run() {
                if (!Platform.isFxApplicationThread()) {
                    return;
                }

                try {
                    callback.callNoThrow();
                } catch (Exception e) {
                    if (e instanceof IllegalStateException && "java.lang.IllegalStateException: Platform.exit has been called".equals(e.getMessage())) {
                        return;
                    }

                    callback.getEnvironment().catchUncaught(e);
                }
            }
        });
    }

    @Signature
    public static void shutdown() {
        shutdown = true;
        Platform.exit();

        Window[] windows = Window.getWindows();

        for (Window window : windows) {
            window.dispose();
        }
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

                if (throwable instanceof CustomErrorException) {
                    CustomErrorException error = (CustomErrorException) throwable;
                    onStart.getEnvironment().error(error.getType(), error.getMessage());
                } else {
                    onStart.getEnvironment().catchUncaught(throwable);
                }
            } catch (Throwable throwable) {
                throw throwable;
            } finally {
                thread.setContextClassLoader(old);
            }
        }
    }
}
