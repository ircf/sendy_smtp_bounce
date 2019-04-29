# sendy_smtp_bounce

**WARNING : Work in progress ! Do NOT use in production**

Bounce program for Sendy SMTP method.
Written for https://sendy.co/ version 4.0

This program collects all bounce messages from a given mailbox and updates subscribers statuses :

- Bounced
- Soft bounced

## Install

1. Create a `bounce@yourdomain` mailbox to collect bounce messages
2. Configure Sendy's MAIL FROM with `bounce@yourdomain`
3. Copy/merge `bounce.php` and `includes` into /path/to/sendy/
4. Edit `bounce.php` and configure the bounce mailbox credentials : `$bounce_host`, `$bounce_user` and `$bounce_pass`
5. Create a cron task on your server like `*/5 * * * * /usr/bin/php /path/to/sendy/bounce.php`
6. Enjoy !
