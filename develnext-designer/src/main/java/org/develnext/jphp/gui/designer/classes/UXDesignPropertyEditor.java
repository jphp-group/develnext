package org.develnext.jphp.gui.designer.classes;

import org.develnext.jphp.ext.javafx.classes.UXTableCell;
import org.develnext.jphp.gui.designer.GuiDesignerExtension;
import php.runtime.Memory;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Abstract;
import php.runtime.annotation.Reflection.Arg;
import php.runtime.annotation.Reflection.Getter;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.lang.BaseObject;
import php.runtime.reflection.ClassEntity;

@Abstract
@Reflection.Namespace(GuiDesignerExtension.NS)
abstract public class UXDesignPropertyEditor extends BaseObject {
    protected String groupCode;
    protected String code;
    protected String name;

    protected UXDesignProperties properties;

    public UXDesignPropertyEditor(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature({
            @Arg(value = "cell", nativeType = UXTableCell.class),
            @Arg("empty")
    })
    abstract public Memory update(Environment env, Memory... args);

    @Getter
    public String getGroupCode() {
        return groupCode;
    }

    public void setGroupCode(String groupCode) {
        this.groupCode = groupCode;
    }

    @Getter
    public String getCode() {
        return code;
    }

    public void setCode(String code) {
        this.code = code;
    }

    @Getter
    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    @Getter
    public UXDesignProperties getDesignProperties() {
        return properties;
    }

    public void setDesignProperties(UXDesignProperties properties) {
        this.properties = properties;
    }
}
