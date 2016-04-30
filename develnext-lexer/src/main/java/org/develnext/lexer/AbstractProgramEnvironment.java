package org.develnext.lexer;

import java.io.IOException;
import java.util.HashMap;
import java.util.Map;

abstract public class AbstractProgramEnvironment<T extends AbstractProgramModule> {
    protected final Map<String, T> modules = new HashMap<>();

    public void addModule(T module) {
        modules.put(module.getPath(), module);
    }

    public void removeModule(T module) {
        modules.remove(module.getPath());
    }

    public T findModule(String path) {
        return modules.get(path);
    }

    abstract public T makeFromPath(String path) throws IOException;
}
