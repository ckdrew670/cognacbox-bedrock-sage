<?php

namespace App\Controllers;

use Sober\Controller\Controller;

class SocialLinks extends Controller
{

	function __construct(string $tags = "")
	{
		$this->tags = $this->sanitiseTags($tags);
	}

	protected $tags;

	public function facebookShare()
	{
		$url = $this->getCurrentURL();
		return "https://www.facebook.com/sharer/sharer.php?u={$url}";
	}

	public function twitterShare()
	{
		$url = $this->getCurrentURL();
		$tags = implode(',', $this->tags);
		$link = "http://twitter.com/share?url={$url}";
		if($tags){
			$link .= "&hashtags={$tags}";
		}
		return $link;
	}

	public function linkedinShare()
	{
		$url = $this->getCurrentURL();
		return "https://www.linkedin.com/sharing/share-offsite/?url={$url}";
	}

	public function whatsappShare()
	{
		$url = $this->getCurrentURL();
		return "whatsapp://send/?text={$url}";
	}

	private function getCurrentURL()
	{
		global $wp;
		return home_url( $wp->request );
	}

	private function sanitiseTags(string $tags)
	{
		return explode(',', preg_replace('/[^A-Za-z0-9,]*/','', $tags));
	}
}