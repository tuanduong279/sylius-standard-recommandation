@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../vendor/doctrine/phpcr-odm/bin/phpcrodm.php
php "%BIN_TARGET%" %*
