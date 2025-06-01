<!DOCTYPE html>
<html>
<body>
<p>Hi <?= $user->nameOrEmail() ?>,</p>
<p>You recently requested a password reset code for <?= $site ?>.</p>
<p style="font-size:1.5em;font-weight:bold;"><?= $code ?></p>
<p>This code will be valid for <?= $timeout ?> minutes.</p>
<p>If you did not request a password reset code, please ignore this email.</p>
</body>
</html>
