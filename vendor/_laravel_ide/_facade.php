<?php

namespace Illuminate\Support\Facades;

interface Auth
{
    /**
     * @return \|false
     */
    public static function loginUsingId(mixed $id, bool $remember = false);

    /**
     * @return \|false
     */
    public static function onceUsingId(mixed $id);

    /**
     * @return \|null
     */
    public static function getUser();

    /**
     * @return \
     */
    public static function authenticate();

    /**
     * @return \|null
     */
    public static function user();

    /**
     * @return \|null
     */
    public static function logoutOtherDevices(string $password);

    /**
     * @return \
     */
    public static function getLastAttempted();
}