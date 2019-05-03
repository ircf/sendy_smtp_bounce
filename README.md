# sendy_smtp_bounce

**WARNING : Work in progress ! Do NOT use in production**

Bounce program for Sendy SMTP method.
Written for https://sendy.co/ version 4.0

This program collects all bounce messages from a given mailbox and updates subscribers statuses :

- Bounced
- Soft bounced

## Install

1. Create a `bounce@yourdomain` mailbox to collect bounce messages
2. Copy/merge `sendy_4_0.patch`, `bounce.php` and `includes` into `/path/to/sendy/`
3. Apply patch : `cd /path/to/sendy/ && patch -p0 < sendy_4_0.patch`
4. Uncomment and customize the following lines in `includes/config.php` :
```
	/* SMTP bounce settings (use only if you send by SMTP) */
	//$bounceHost = '{yourdomain:143/novalidate-cert}';
	//$bounceUser = 'bounce@yourdomain';
	//$bouncePass = 'bouncepassword';
```
5. Create a cron task on your server like `*/15 * * * * /usr/bin/php /path/to/sendy/bounce.php`
6. Enjoy !
