package org.develnext.jphp.gui.designer.editor.tree;

public class DirectoryTreeListener {
    private final String path;
    private Runnable handler;

    public DirectoryTreeListener(String path) {
        this.path = path;
    }

    public String getPath() {
        return path;
    }

    public void bind(Runnable handler) {
        this.handler = handler;
    }

    public void trigger() {
        if (handler != null) handler.run();
    }

    public void shutdown() {
        handler = null;
    }
}
