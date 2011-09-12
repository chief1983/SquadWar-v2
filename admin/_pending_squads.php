<?php /*
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell 
   or otherwise commercially exploit the source or things you created based on the 
   source.
*/
include('../bootstrap.php');

$rec = squad_api::new_search_record();
$rec->set_SquadID($_GET['squadid']);
$ret = squad_api::search($rec);
$ret = squad_api::populate_info($ret);
$squad = reset($ret->get_results());
$info = $squad->get_info();

$squad->set_Active(1);
$squad->save();

$info->set_Approved(1);
$info->save();

$to = $info->get_Squad_Email();
$from = SUPPORT_EMAIL;
$admin = ADMIN_NAME;
$headers = 'From: SquadWar <' . $from . ">\r\n" .
	'Cc: ' . SUPPORT_EMAIL . "\r\n" .
    'Reply-To: ' . $from . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
$subject = "Squad Approved";
$message = <<<EOT

Your squad has been approved for participation.

For more information on the SquadWar rules, visit the rules page at:
http://www.squadwar.com/rules/

Thanks,

- {$admin}
- SquadWar Administrator
- {$from}

**Please leave all correspondence intact in your reply**

EOT;

mail($to, $subject, $message, $headers);

util::location(RELATIVEPATH.'admin/pending_squads.php');

?>
