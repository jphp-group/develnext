package org.develnext.jphp.gui.designer.editor.tree;

import javafx.scene.Node;
import javafx.scene.image.Image;
import javafx.scene.image.ImageView;

import java.io.File;
import java.util.HashMap;
import java.util.Map;

public class DirectoryTreeUtils {
    private final static Map<String, Image> fileIcons = new HashMap<>();

    private final static Image folderIcon = makeIcon("folder");
    private final static Image folderOpenIcon = makeIcon("folder-open");

    private final static Image fileAnyIcon = makeIcon("file");
    private final static Image fileImageIcon = makeIcon("image-file");
    private final static Image fileAudioIcon = makeIcon("audio-file");
    private final static Image fileVideoIcon = makeIcon("video-file");
    private final static Image fileTextIcon = makeIcon("text-file");
    private final static Image fileSourceIcon = makeIcon("source-file");
    private final static Image fileArchiveIcon = makeIcon("archive-file");
    private final static Image fileExecutableIcon = makeIcon("executable-file");

    static {
        // images
        fileIcons.put("jpg", makeIcon("jpeg-file"));
        fileIcons.put("jpeg", makeIcon("jpeg-file"));
        fileIcons.put("png", makeIcon("png-file"));
        fileIcons.put("gif", makeIcon("gif-file"));
        fileIcons.put("ico", fileImageIcon);
        fileIcons.put("bmp", makeIcon("bmp-file"));
        fileIcons.put("tiff", makeIcon("tif-file"));
        fileIcons.put("tif", makeIcon("tif-file"));
        fileIcons.put("psd", makeIcon("psd-file"));
        fileIcons.put("eps", makeIcon("eps-file"));
        fileIcons.put("cdr", makeIcon("cdr-file"));

        // audio
        fileIcons.put("mp3", makeIcon("wav-file"));
        fileIcons.put("wav", makeIcon("wav-file"));
        fileIcons.put("wave", makeIcon("wav-file"));
        fileIcons.put("aif", makeIcon("aif-file"));
        fileIcons.put("aiff", makeIcon("aif-file"));
        fileIcons.put("ogg", makeIcon("ogg-file"));
        fileIcons.put("mid", makeIcon("mid-file"));

        // video
        fileIcons.put("mp4", makeIcon("mp4-file"));
        fileIcons.put("avi", makeIcon("mp4-file"));
        fileIcons.put("flv", makeIcon("flv-file"));
        fileIcons.put("3gp", makeIcon("3gp-file"));
        fileIcons.put("webm", makeIcon("mp4-file"));

        // text
        fileIcons.put("txt", makeIcon("txt-file"));
        fileIcons.put("log", makeIcon("log-file"));
        fileIcons.put("conf", fileTextIcon);
        fileIcons.put("ini", fileTextIcon);
        fileIcons.put("svg", fileTextIcon);
        fileIcons.put("xml", fileTextIcon);
        fileIcons.put("json", fileTextIcon);
        fileIcons.put("fxml", fileTextIcon);

        // source
        fileIcons.put("php", makeIcon("php-file"));
        fileIcons.put("java", fileSourceIcon);
        fileIcons.put("js", fileSourceIcon);
        fileIcons.put("html", fileSourceIcon);
        fileIcons.put("htm", fileSourceIcon);
        fileIcons.put("css", fileSourceIcon);
        fileIcons.put("less", fileSourceIcon);
        fileIcons.put("scss", fileSourceIcon);
        fileIcons.put("sass", fileSourceIcon);

        // archive
        fileIcons.put("zip", makeIcon("zip-file"));
        fileIcons.put("jar", makeIcon("jar-file"));
        fileIcons.put("rar", makeIcon("rar-file"));
        fileIcons.put("7z", makeIcon("7z-file"));
        fileIcons.put("tar", makeIcon("tgz-file"));
        fileIcons.put("gz", makeIcon("gz-file"));
        fileIcons.put("bz2", makeIcon("gz-file"));
        fileIcons.put("war", makeIcon("zip-file"));
        fileIcons.put("iso", makeIcon("iso-file"));

        // executable
        fileIcons.put("exe", makeIcon("exe-file"));
        fileIcons.put("appx", fileExecutableIcon);
        fileIcons.put("sh", fileExecutableIcon);
        fileIcons.put("bat", makeIcon("bat-file"));


        fileIcons.put("ai", makeIcon("ai-file"));
        fileIcons.put("asx", makeIcon("asx-file"));
        fileIcons.put("bin", makeIcon("bin-file"));
        fileIcons.put("cab", makeIcon("cab-file"));
        fileIcons.put("cbr", makeIcon("cbr-file"));
        fileIcons.put("chm", makeIcon("chm-file"));
        fileIcons.put("dat", makeIcon("dat-file"));
        fileIcons.put("db", makeIcon("dat-file"));
        fileIcons.put("dll", makeIcon("dll-file"));
        fileIcons.put("so", makeIcon("dll-file"));
        fileIcons.put("dmg", makeIcon("dmg-file"));
        fileIcons.put("doc", makeIcon("doc-file"));
        fileIcons.put("docx", makeIcon("doc-file"));
        fileIcons.put("eml", makeIcon("eml-file"));
        fileIcons.put("eps", makeIcon("eps-file"));
        fileIcons.put("flv", makeIcon("flv-file"));
        fileIcons.put("m4a", makeIcon("m4a-file"));
        fileIcons.put("mov", makeIcon("mov-file"));
        fileIcons.put("mpeg", makeIcon("mpeg-file"));
        fileIcons.put("msi", makeIcon("msi-file"));
        fileIcons.put("pdf", makeIcon("pdf-file"));
        fileIcons.put("pps", makeIcon("pps-file"));
        fileIcons.put("ps", makeIcon("ps-file"));
        fileIcons.put("psd", makeIcon("psd-file"));
        fileIcons.put("pst", makeIcon("pst-file"));
        fileIcons.put("rtf", makeIcon("rtf-file"));
        fileIcons.put("ses", makeIcon("ses-file"));
        fileIcons.put("swf", makeIcon("swf-file"));
        fileIcons.put("tgz", makeIcon("tgz-file"));
        fileIcons.put("tmp", makeIcon("tmp-file"));
        fileIcons.put("torrent", makeIcon("torrent-file"));
        fileIcons.put("ttf", makeIcon("ttf-file"));
        fileIcons.put("vcd", makeIcon("vcd-file"));
        fileIcons.put("wps", makeIcon("wps-file"));
        fileIcons.put("xpi", makeIcon("xpi-file"));

    }

    public static Node getIconOfFile(File file, boolean expanded) {
        if (file.isDirectory()) {
            return new ImageView(!expanded ? folderIcon : folderOpenIcon);
        }

        if (expanded) {
            return null;
        }

        String name = file.getName();

        int index = name.lastIndexOf('.');

        if (index == -1) {
            return new ImageView(fileAnyIcon);
        }

        String ext = name.substring(index + 1).toLowerCase();

        if (fileIcons.containsKey(ext)) {
            return new ImageView(fileIcons.get(ext));
        }

        return new ImageView(fileAnyIcon);
    }

    private static Image makeIcon(String fileName) {
        return new Image("/org/develnext/jphp/gui/designer/editor/tree/" + fileName + ".png");
    }
}
