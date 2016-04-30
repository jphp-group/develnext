package org.develnext.lexer;

import java.util.ArrayList;
import java.util.List;
import java.util.Map;

abstract public class AbstractProgramModule {
    protected String path;
    protected List<AbstractProgramEntry> entries;

    public String getPath() {
        return path;
    }

    public void setPath(String path) {
        this.path = path;
    }

    public void addEntry(AbstractProgramEntry entry) {
        entries.add(entry);
    }

    public List<AbstractProgramEntry> findEntry(String name, int type) {
        ArrayList<AbstractProgramEntry> result = new ArrayList<>();

        for (AbstractProgramEntry entry : entries) {
            if (name.startsWith(entry.getName()) && entry.getType() == type) {
                result.add(entry);
            }
        }

        return result;
    }
}
