package org.develnext.jphp.gui.designer.editor.tree;

import javafx.scene.Node;

import java.io.File;
import java.io.FileFilter;
import java.io.IOException;
import java.nio.file.*;
import java.util.*;
import java.util.concurrent.TimeUnit;

public class FileDirectoryTreeSource extends AbstractDirectoryTreeSource {
    public interface ValueCreator {
        DirectoryTreeValue create(String path, File file);
    }


    private final File directory;
    private Map<String, DirectoryTreeListener> watchers = new HashMap<>();

    private boolean showHidden = false;
    private List<FileFilter> fileFilters = new ArrayList<>();
    private List<ValueCreator> valueCreators = new ArrayList<>();

    public FileDirectoryTreeSource(File directory) {
        this.directory = directory;
    }

    public boolean isShowHidden() {
        return showHidden;
    }

    public void setShowHidden(boolean showHidden) {
        this.showHidden = showHidden;
    }

    @Override
    public void shutdown() {
        Collection<DirectoryTreeListener> values = new ArrayList<>(watchers.values());

        for (DirectoryTreeListener listener : values) {
            listener.shutdown();
        }
    }

    @Override
    public String rename(String path, String newName) {
        File file = new File(directory, "/" + path);

        if (file.exists()) {
            File newFile = new File(file.getParent(), "/" + newName);
            boolean success = file.renameTo(newFile);

            if (success) {
                return new File(new File(path).getParent(), newName).getPath().replace("\\", "/");
            }
        }

        return null;
    }

    public File getDirectory() {
        return directory;
    }

    public void addFileFilter(FileFilter filter) {
        fileFilters.add(filter);
    }

    public void addValueCreator(ValueCreator creator) {
        valueCreators.add(creator);
    }

    private boolean isShowingInTree(File file) {
        if (!showHidden && file.isHidden()) {
            return false;
        }

        for (FileFilter filter : fileFilters) {
            if (!filter.accept(file)) {
                return false;
            }
        }

        return true;
    }

    public boolean isEmpty(String path) {
        try (DirectoryStream<Path> dirStream = Files.newDirectoryStream(Paths.get(directory.getPath(), path))) {
            for (Path el : dirStream) {
                File file = el.toFile();

                if (isShowingInTree(file)) {
                    return false;
                }
            }

            return true;
        } catch (IOException e) {
            return true;
        }
    }

    public List<DirectoryTreeValue> list(String path) {
        File[] files = new File(directory, path).listFiles(this::isShowingInTree);

        if (files == null) {
            return Collections.emptyList();
        } else {
            ArrayList<DirectoryTreeValue> list = new ArrayList<>();

            for (File file : files) {
                if (file.isDirectory()) {
                    list.add(createValue(path + "/" + file.getName()));
                }
            }

            for (File file : files) {
                if (!file.isDirectory()) {
                    list.add(createValue(path + "/" + file.getName()));
                }
            }

            return list;
        }
    }

    @Override
    public DirectoryTreeListener listener(String path) {
        if (watchers.containsKey(path)) {
            return watchers.get(path);
        }

        try {
            WatchService watchService = FileSystems.getDefault().newWatchService();
            Path p = Paths.get(new File(directory, "/" + path).getPath());
            p.register(
                    watchService, StandardWatchEventKinds.ENTRY_CREATE, StandardWatchEventKinds.ENTRY_DELETE
            );

            Listener listener = new Listener(path, watchService);
            watchers.put(path, listener);
            return listener;
        } catch (IOException e) {
            return null;
        }
    }

    public DirectoryTreeValue createValue(String path) {
        File file = new File(directory, "/" + path);

        if (file.getPath().endsWith(File.separator)) {
            file = new File(file.getPath().substring(0, file.getPath().length() - 1));
        }

        for (ValueCreator valueCreator : valueCreators) {
            DirectoryTreeValue value = valueCreator.create(path, file);

            if (value != null) {
                return value;
            }
        }

        String text = file.getName();
        String code = file.getName();

        Node icon = DirectoryTreeUtils.getIconOfFile(file, false);
        Node expandIcon = DirectoryTreeUtils.getIconOfFile(file, true);

        return new DirectoryTreeValue(path, code, text, icon, expandIcon, file.isDirectory());
    }

    private class Listener extends DirectoryTreeListener {
        private boolean shutdown = false;

        public Listener(String path, WatchService watcher) {
            super(path);

            new Thread(() -> {
                while (true) {
                    WatchKey poll = null;
                    try {
                        poll = watcher.poll(1, TimeUnit.SECONDS);

                        if (shutdown) {
                            watcher.close();
                            break;
                        }

                        if (poll != null) {
                            poll.pollEvents();
                            trigger();

                            poll.reset();
                        }
                    } catch (InterruptedException | IOException e) {
                        //watchers.remove(getPath());
                        break;
                    }
                }
            }).start();
        }

        @Override
        public void shutdown() {
            super.shutdown();

            watchers.remove(getPath());
            shutdown = true;
        }
    }
}
