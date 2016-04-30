package org.develnext.jphp.gui.designer.editor.inspect;

import java.io.File;
import java.util.Set;
import java.util.TreeSet;

abstract public class AbstractInspector {
    protected Set<File> files = new TreeSet<>();

    public void addFile(File file) {
        files.add(file);
    }
}
