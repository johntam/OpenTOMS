<?php 
if (!empty($attachments)) {
	foreach ($attachments as $attach) {
		echo $this->Html->link(	$attach['Attachment']['name'],
								array('controller'=>'pages', 'action'=>'showattach', $attach['Attachment']['id']));
		echo '</br>';
	}
}