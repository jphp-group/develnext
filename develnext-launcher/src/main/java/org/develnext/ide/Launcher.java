package org.develnext.ide;

import javafx.application.Platform;
import javafx.embed.swing.SwingNode;

import javax.swing.*;
import java.io.*;
import java.net.URISyntaxException;
import java.util.*;

public class Launcher {
    public static final String[] defaultJvmArgs = {
            "-Xms256M", "-XX:ReservedCodeCacheSize=150m", "-XX:+UseConcMarkSweepGC", "-Dsun.io.useCanonCaches=false",
            "-Djava.net.preferIPv4Stack=true", "-Dfile.encoding=UTF-8", "-Ddevelnext.launcher=root"
    };

    protected ProcessBuilder processBuilder;
    protected Process process;
    private File rootDir;

    protected String[] fetchJvmArgs() {
        String[] jvmArgs = defaultJvmArgs;

        if (new File(rootDir, "/DevelNext.l4j.ini").exists()) {
            try {
                Scanner scanner = new Scanner(new FileInputStream(new File(rootDir, "/DevelNext.l4j.ini")), "UTF-8");

                Set<String> newJvmArgs = new TreeSet<String>();

                while (scanner.hasNextLine()) {
                    String line = scanner.nextLine().trim();

                    if (line.isEmpty() || line.startsWith("#")) {
                        continue;
                    }

                    newJvmArgs.add(line);
                }

                jvmArgs = newJvmArgs.toArray(new String[newJvmArgs.size()]);

            } catch (FileNotFoundException e) {
                e.printStackTrace();
            }
        }

        return jvmArgs;
    }

    private static String[] concatArrays(String[] first, String[] second) {
        List<String> both = new ArrayList<String>(first.length + second.length);
        Collections.addAll(both, first);
        Collections.addAll(both, second);
        return both.toArray(new String[both.size()]);
    }

    public boolean isJava8FxExists() {
        try {
            Class.forName("javafx.application.Platform");
            Class.forName("javafx.embed.swing.SwingNode");
            return true;
        } catch (ClassNotFoundException e) {
            return false;
        }
    }

    public void start() throws URISyntaxException, IOException {
        if (!isJava8FxExists()) {
            JOptionPane.showMessageDialog(null, "Oracle/Open Java Runtime 8+ required with JavaFX", "Error", JOptionPane.ERROR_MESSAGE);
            return;
        }

        rootDir = new File(Launcher.class.getProtectionDomain().getCodeSource().getLocation().toURI().getPath());

        String[] jvmArgs = fetchJvmArgs();

        jvmArgs = concatArrays(new String[]{"java"}, jvmArgs);

        String[] args = concatArrays(jvmArgs, new String[]{
                "-Ddevelnext.home=" + rootDir.getAbsolutePath(), "-cp", rootDir.getAbsolutePath() + "/lib/*", "php.runtime.launcher.Launcher"
        });

        processBuilder = new ProcessBuilder(args);
        processBuilder.start();
    }

    public static void main(String[] args) throws URISyntaxException, IOException {
        new Launcher().start();
    }
}
