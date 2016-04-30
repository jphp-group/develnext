package org.develnext.lexer;

abstract public class AbstractProgramEntry {
    protected final int type;
    protected String name;

    protected AbstractProgramEntry(int type) {
        this.type = type;
    }

    public int getType() {
        return type;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }
}
