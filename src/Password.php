<?php

namespace VSPW;

class Password {
    /**
     * Asserts that a password has been set.
     *
     * @return bool
     */
    public function hasPassword()
    {
        return ! empty($this->getPassword());
    }

    /**
     * Returns the password.
     *
     * @return bool
     */
    public function getPassword()
    {
        return get_option('vspw_password');
    }
}