<?php
use Kirby\Toolkit\I18n;
/**
 * Plain text login code email
 *
 * @var Kirby\Cms\User $user
 * @var string $site
 * @var string $code
 * @var int $timeout
 */
echo I18n::template(
    'login.email.login.body',
    null,
    compact('user', 'site', 'code', 'timeout'),
    $user->language()
);
