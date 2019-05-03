<?php
/**
 * Connect to bounce mailbox and update Sendy's email address statuses
 * @see includes/campaigns/bounces.php
 * @see http://cheesefather.com/2011/12/process-email-bounces-with-php/
 */

// load sendy config and connect to db
require_once('includes/config.php');
if(isset($dbPort)) $mysqli = new mysqli($dbHost, $dbUser, $dbPass, $dbName, $dbPort);
else $mysqli = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
if ($mysqli->connect_error) fail();
mysqli_set_charset($mysqli, isset($charset) ? $charset : "utf8");

// init bounce class
require_once(dirname(__FILE__)."/includes/helpers/bounce_driver.class.php");
$bouncehandler = new Bouncehandler();

// connect to mailbox
$conn = imap_open ($bounce_host, $bounce_user, $bounce_pass) or die(imap_last_error());
$num_msgs = imap_num_msg($conn);

// get the failures
$email_addresses = array();
$delete_addresses = array();
for ($n=1;$n<=$num_msgs;$n++) {
  $bounce = imap_fetchheader($conn, $n).imap_body($conn, $n); //entire message
  $multiArray = $bouncehandler->get_the_facts($bounce);
  if (
    !empty($multiArray[0]['action']) &&
    !empty($multiArray[0]['status']) &&
    !empty($multiArray[0]['recipient']) &&
    $multiArray[0]['action']=='failed'
  ) {
    
    $bounceType = strpos($multiArray[0]['status'], '4.') === 0 ? 'Transient' : 'Permanent';
    $problem_email = $multiArray[0]['recipient'];
    $time = time();
    
    if($bounceType == 'Transient')
      $q = 'UPDATE subscribers SET bounce_soft = bounce_soft+1 WHERE email = "'.$problem_email.'"';
    else if($bounceType == 'Permanent')
      $q = 'UPDATE subscribers SET bounced = 1, timestamp = '.$time.' WHERE email = "'.$problem_email.'"';
    $r = mysqli_query($mysqli, $q);
    if ($r)
    {
      //check if recipient has soft bounced 3 times
      if($bounceType == 'Transient')
      {
        $q2 = 'SELECT bounce_soft FROM subscribers WHERE email = "'.$problem_email.'" LIMIT 1';
        $r2 = mysqli_query($mysqli, $q2);
        if ($r2 && mysqli_num_rows($r2) > 0)
        {
          while($row = mysqli_fetch_array($r2))
          {
            $bounce_soft = $row['bounce_soft'];
          }  
          
          //if soft bounced 3 times or more, set as hard bounce
          if($bounce_soft >= 3)
          {
            $q = 'UPDATE subscribers SET bounced = 1, timestamp = '.$time.' WHERE email = "'.$problem_email.'"';
            $r = mysqli_query($mysqli, $q);
            if($r){}
          }
        }
      }
    }
    imap_delete($conn, $$n);
  } //if delivery failed
}

// delete messages
imap_expunge($conn);

// close
imap_close($conn);
