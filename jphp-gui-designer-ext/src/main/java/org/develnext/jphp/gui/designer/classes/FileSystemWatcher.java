package org.develnext.jphp.gui.designer.classes;

import org.develnext.jphp.gui.designer.GuiDesignerExtension;
import php.runtime.Memory;
import php.runtime.annotation.Reflection.Abstract;
import php.runtime.annotation.Reflection.Name;
import php.runtime.annotation.Reflection.Namespace;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.lang.BaseObject;
import php.runtime.memory.ArrayMemory;
import php.runtime.reflection.ClassEntity;

import java.io.IOException;
import java.nio.file.*;

@Namespace(GuiDesignerExtension.NS)
public class FileSystemWatcher extends BaseObject {
    protected WatchService watchService;
    protected Path folder;

    public FileSystemWatcher(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct(String path) throws IOException {
        folder = Paths.get(path);
        watchService = FileSystems.getDefault().newWatchService();

        folder.register(watchService,
                StandardWatchEventKinds.ENTRY_CREATE,
                StandardWatchEventKinds.ENTRY_DELETE,
                StandardWatchEventKinds.ENTRY_MODIFY,
                StandardWatchEventKinds.OVERFLOW
        );
    }

    @Signature
    public void close() throws IOException {
        watchService.close();
    }

    @Signature
    public WrapWatchKey take(Environment env) throws InterruptedException {
        return new WrapWatchKey(env, folder, watchService.take());
    }

    @Abstract
    @Name("WatchFileKey")
    @Namespace(GuiDesignerExtension.NS)
    public static class WrapWatchKey extends BaseObject {
        private Path path;
        private WatchKey wrappedObject;

        public WrapWatchKey(Environment env, Path path, WatchKey wrappedObject) {
            super(env);
            this.path = path;
            this.wrappedObject = wrappedObject;
        }

        public WrapWatchKey(Environment env, ClassEntity clazz) {
            super(env, clazz);
        }

        @Signature
        public boolean reset() {
            return wrappedObject.reset();
        }

        @Signature
        public void cancel() {
            wrappedObject.cancel();
        }

        @Signature
        public Memory pollEvents() {
            ArrayMemory result = new ArrayMemory();

            for (WatchEvent<?> event : wrappedObject.pollEvents()) {
                Memory item = result.refOfPush();

                item.refOfIndex("kind").assign(event.kind().name());
                item.refOfIndex("context").assign(path.toString() + "/" + event.context().toString());
                item.refOfIndex("count").assign(event.count());
            }

            return result.toConstant();
        }
    }
}
