package org.develnext.jphp.gui.designer.editor.tree;

import javafx.scene.Node;

public class DirectoryTreeValue {
    boolean alreadyLoaded = false;

    private String path;
    private String code;
    private String text;
    private Node icon;
    private Node expandIcon;

    private boolean folder;

    public DirectoryTreeValue(String path, String code, String text, Node icon, Node expandIcon, boolean folder) {
        this.path = path;
        this.code = code;
        this.text = text;
        this.icon = icon;
        this.expandIcon = expandIcon;
        this.folder = folder;
    }

    public String getCode() {
        return code;
    }

    public String getText() {
        return text;
    }

    public Node getIcon() {
        return icon;
    }

    public void setIcon(Node icon) {
        this.icon = icon;
    }

    public void setCode(String code) {
        this.code = code;
    }

    public void setText(String text) {
        this.text = text;
    }

    public Node getExpandIcon() {
        return expandIcon;
    }

    public void setExpandIcon(Node expandIcon) {
        this.expandIcon = expandIcon;
    }

    public boolean isFolder() {
        return folder;
    }

    public void setFolder(boolean folder) {
        this.folder = folder;
    }

    boolean isAlreadyLoaded() {
        return alreadyLoaded;
    }

    void setAlreadyLoaded(boolean alreadyLoaded) {
        this.alreadyLoaded = alreadyLoaded;
    }

    public String getPath() {
        return path;
    }

    public void setPath(String path) {
        this.path = path;
    }
}
