<?php

namespace jaminv\LoginBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class jaminvLoginBundle extends Bundle {
    public function getParent() {
        return 'FOSUserBundle';
    }
}
