package org.develnext.jphp.gui.designer.editor.tree;

import java.util.List;

abstract public class AbstractDirectoryTreeSource {
    abstract public boolean isEmpty(String path);
    abstract DirectoryTreeValue createValue(String path);
    abstract List<DirectoryTreeValue> list(String path);

    abstract public DirectoryTreeListener listener(String path);

    abstract public void shutdown();

    abstract public String rename(String path, String newName);
}
