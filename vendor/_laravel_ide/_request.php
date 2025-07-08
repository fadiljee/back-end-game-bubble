<?php

namespace Illuminate\Http;

interface Request
{
    /**
     * @return \|null
     */
    public function user($guard = null);
}