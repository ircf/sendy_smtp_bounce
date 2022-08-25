# sendy_smtp_bounce

#Update 8-25-2022 Uploaded new updated for send version 5.2.6 and 6.0.2
Instructions are the same for both versions.

Sendy Bounce SMTP for each brand.

1. You will have to go into your mysql database and run the mysql code below.
It will create the columns needed for the bounce to work.

ALTER TABLE apps
ADD COLUMN bounce_host VARCHAR(100) NOT NULL AFTER smtp_password,
ADD COLUMN bounce_port VARCHAR(100) NOT NULL AFTER bounce_host,
ADD COLUMN bounce_username VARCHAR(100) NOT NULL AFTER bounce_port,
ADD COLUMN bounce_password VARCHAR(100)NOT NULL AFTER bounce_username;

2. Upload all the files included and overwrite the old files. 
3. Create a bounce email like mail@yourdomain.com.  This will catch all the bounced emails.
4. Create or Edit your brand under smtp fill out the form with your bounce email details.

Create cron to run every 15 minutes
usr/bin/php /path/to/sendy/bounce.php > /dev/null 2>&1
=====================================================================================================


Bounce program for Sendy SMTP method.
Written for https://sendy.co/ version 4.0

This program collects all bounce messages from a given mailbox and updates subscribers statuses :

- Bounced
- Soft bounced

## Install

1. Create a `bounce@yourdomain` mailbox to collect bounce messages
2. Copy/merge `sendy_4_0.patch`, `bounce.php` and `includes` into `/path/to/sendy/`
3. Apply patch : `cd /path/to/sendy/ && patch -p1 < sendy_4_0.patch`
4. Uncomment and customize the following lines in `includes/config.php` :
```
	/* SMTP bounce settings (use only if you send by SMTP) */
	//$bounceHost = '{yourdomain:143/novalidate-cert}';
	//$bounceUser = 'bounce@yourdomain';
	//$bouncePass = 'bouncepassword';
```
5. Create a cron task on your server like `*/15 * * * * /usr/bin/php /path/to/sendy/bounce.php`
6. Enjoy !
