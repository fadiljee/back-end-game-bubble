<?php

namespace Illuminate\Contracts\Auth;

interface Guard
{
    /**
     * @return \|null
     */
    public function user();
}