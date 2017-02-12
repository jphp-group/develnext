package org.develnext.jphp.gui.designer.classes;

import javafx.collections.ObservableList;
import javafx.scene.control.TreeItem;
import javafx.scene.control.TreeView;
import org.develnext.jphp.ext.javafx.classes.UXTreeItem;
import org.develnext.jphp.ext.javafx.classes.UXTreeView;
import org.develnext.jphp.gui.designer.GuiDesignerExtension;
import org.develnext.jphp.gui.designer.editor.tree.AbstractDirectoryTreeSource;
import org.develnext.jphp.gui.designer.editor.tree.DirectoryTreeValue;
import org.develnext.jphp.gui.designer.editor.tree.DirectoryTreeView;
import org.develnext.jphp.gui.designer.editor.tree.FileDirectoryTreeSource;
import php.runtime.Memory;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.*;
import php.runtime.env.Environment;
import php.runtime.memory.ArrayMemory;
import php.runtime.reflection.ClassEntity;

import java.io.File;
import java.util.*;

@Namespace(GuiDesignerExtension.NS)
public class UXDirectoryTreeView extends UXTreeView {
    interface WrappedInterface {
        @Property @Nullable AbstractDirectoryTreeSource treeSource();
    }

    public UXDirectoryTreeView(Environment env, TreeView wrappedObject) {
        super(env, wrappedObject);
    }

    public UXDirectoryTreeView(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct() {
        __wrappedObject = new DirectoryTreeView();
    }

    @Signature
    public void __construct(AbstractDirectoryTreeSource source) {
        __wrappedObject = new DirectoryTreeView(source);
    }


    private void _getExpandedPaths(Environment env, TreeItem item, ArrayMemory result) {
        if (item.isExpanded()) {
            DirectoryTreeValue value = (DirectoryTreeValue) item.getValue();
            result.add(value.getPath());
        }

        ObservableList<TreeItem> children = item.getChildren();

        for (TreeItem one : children) {
            _getExpandedPaths(env, one, result);
        }
    }

    @Getter
    public Memory getExpandedPaths(Environment env) {
        TreeItem root = getWrappedObject().getRoot();

        ArrayMemory result = new ArrayMemory();

        if (root != null) {
            _getExpandedPaths(env, root, result);
        }

        return result.toConstant();
    }

    @Setter
    public void setExpandedPaths(List<String> paths) {
        collapseAll();

        TreeView treeView = getWrappedObject();
        TreeItem root = treeView.getRoot();

        if (!paths.isEmpty()) {
            root.setExpanded(true);
        }

        for (String path : paths) {
            String[] strings = path.split("/");

            TreeItem<DirectoryTreeValue> item = root;

            for (int i = 1; i < strings.length; i++) {
                boolean found = false;

                for (TreeItem<DirectoryTreeValue> one : item.getChildren()) {
                    DirectoryTreeValue value = one.getValue();

                    if (Objects.equals(value.getCode(), strings[i])) {
                        one.setExpanded(true);
                        item = one;
                        found = true;
                        break;
                    }
                }

                if (!found) {
                    break;
                }
            }
        }
    }
}
