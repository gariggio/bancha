<?php
/**
 * Comments save script
 *
 * Blog detail - Sandbox Theme
 * Includes the Akismet support
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011-2012, Squallstar
 * @license		GNU/GPL (General Public License)
 *
 */

$author = $this->input->post('author');
$message = $this->input->post('message');
$email = $this->input->post('email');

$is_spam = FALSE;

$akismet_key = $this->settings->get('akismet_key', 'Services');
if (strlen($akismet_key))
{	
	$this->load->library('external/akismet', array(
		'key'		=> $akismet_key,
		'author'	=> $author,
		'message'	=> $message,
		'email'		=> $email
	));

	$is_spam = $this->akismet->is_spam();
}

if (!$is_spam)
{
	$comment = new Record('Comments');
	$comment->set('content', $message))
			->set('author', $author)
			->set('email', $email)
			->set('date_insert', time())
			->set('post_id', $record->id);

	//We save the comment
	$pkey = $this->records->save($comment);

	//And here we publish it
	if ($pkey) {
		$this->records->publish($pkey, 'Comments');
	}
}

/* End of file comments-save.php */
/* Location: /extra/comments-save.php */