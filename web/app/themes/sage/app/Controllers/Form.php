<?php

namespace App\Controllers;

use Sober\Controller\Controller;

class Form extends Controller
{
	public static function getFields()
	{
		return get_fields();
	}
}
