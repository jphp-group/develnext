package org.develnext.jphp.ext.javafx.classes.print;

import javafx.print.Paper;
import javafx.print.PrintResolution;
import javafx.print.Printer;
import javafx.print.PrinterAttributes;
import org.develnext.jphp.ext.javafx.JavaFXExtension;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Getter;
import php.runtime.annotation.Reflection.Property;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.env.TraceInfo;
import php.runtime.lang.BaseWrapper;
import php.runtime.memory.ArrayMemory;
import php.runtime.memory.support.MemoryOperation;
import php.runtime.reflection.ClassEntity;

import java.util.List;
import java.util.Set;

@Reflection.Name(JavaFXExtension.NS + "print\\UXPrinter")
public class UXPrinter extends BaseWrapper<Printer> {
    interface WrappedInterface {
        @Property String name();
    }

    private final MemoryOperation<Paper> paperConverter = MemoryOperation.get(Paper.class, null);
    private final MemoryOperation<PrintResolution> resolutionConverter = MemoryOperation.get(PrintResolution.class, null);

    public UXPrinter(Environment env, Printer wrappedObject) {
        super(env, wrappedObject);
    }

    public UXPrinter(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    private void __construct() {
    }

    @Signature
    public static Printer getDefault() {
        return Printer.getDefaultPrinter();
    }

    @Signature
    public static Set<Printer> getAll() {
        return Printer.getAllPrinters();
    }

    @Getter
    public ArrayMemory getAttributes(Environment env, TraceInfo trace) throws Throwable {
        PrinterAttributes attributes = getWrappedObject().getPrinterAttributes();

        ArrayMemory r = new ArrayMemory();
        r.refOfIndex("defaultCopies").assign(attributes.getDefaultCopies());
        r.refOfIndex("maxCopies").assign(attributes.getMaxCopies());
        r.refOfIndex("defaultCollation").assign(attributes.getDefaultCollation().name());
        r.refOfIndex("defaultPageOrientation").assign(attributes.getDefaultPageOrientation().name());
        r.refOfIndex("defaultPaper").assign(paperConverter.unconvert(env, trace, attributes.getDefaultPaper()));

        r.refOfIndex("defaultPaperSource").assign(attributes.getDefaultPaperSource().getName());
        r.refOfIndex("defaultPrintColor").assign(attributes.getDefaultPrintColor().name());
        r.refOfIndex("defaultPrintQuality").assign(attributes.getDefaultPrintQuality().name());

        // print resolution
        ArrayMemory resolution = new ArrayMemory();
        resolution.refOfIndex("crossFeedResolution").assign(attributes.getDefaultPrintResolution().getCrossFeedResolution());
        resolution.refOfIndex("feedResolution").assign(attributes.getDefaultPrintResolution().getFeedResolution());

        r.refOfIndex("defaultPrintResolution").assign();

        r.refOfIndex("f").assign(attributes.getprint)
    }
}
