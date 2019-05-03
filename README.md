# sendy_smtp_bounce

**WARNING : Work in progress ! Do NOT use in production**

Bounce program for Sendy SMTP method.
Written for https://sendy.co/ version 4.0

This program collects all bounce messages from a given mailbox and updates subscribers statuses :

- Bounced
- Soft bounced

## Install

1. Create a `bounce@yourdomain` mailbox to collect bounce messages
2. Copy/merge `bounce.php` and `includes` into /path/to/sendy/
3. Add the following lines to `includes/config.php` in the optional settings section
to configure your bounce mailbox credentials :
```
	/* SMTP bounce settings (use only if you send by SMTP) */
	$bounceHost = '{yourdomain:143/novalidate-cert}';
	$bounceUser = 'bounce@yourdomain';
	$bouncePass = 'bouncepassword';
```
4. Edit `scheduled.php` around line 587, after line `$mail->Password = $smtp_password;`
add the following line :

`$mail->Sender = $bounceUser; // SMTP bounce : Return-Path = bounce mailbox`

5. Create a cron task on your server like `*/15 * * * * /usr/bin/php /path/to/sendy/bounce.php`
6. Enjoy !
