package org.develnext.lexer.php;

import org.develnext.lexer.AbstractProgramEntry;

public class PhpProgramFunction extends AbstractProgramEntry {
    public PhpProgramFunction() {
        super(PhpProgramModule.ENTRY_FUNCTION_TYPE);
    }
}
