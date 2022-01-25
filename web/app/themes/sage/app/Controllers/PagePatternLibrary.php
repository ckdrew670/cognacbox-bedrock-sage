<?php

declare(strict_types=1);

namespace App\Controllers;

use Sober\Controller\Controller;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Illuminate\Support\Collection;
use SplFileInfo;

/**
 * PagePatternLibrary
 *
 * This controller locates templates and
 * outputs them all onto a single page
 * that can be used to work on elements in
 * isolation.
 */
class PagePatternLibrary extends Controller
{
	private $files;

	// elements will be rendered in this order
	// any elements not on this list will be
	// rendered at the end
	private $renderOrder = [
		'Homepage Hero'
	];

	public function __construct()
	{
		// scan for files in the patterns folder
		$directoryPath = get_stylesheet_directory() . '/views/patterns';

		// recurse over directories
		$directoryIterator = new RecursiveDirectoryIterator(
			$directoryPath,
			// ignore hidden files
			RecursiveDirectoryIterator::SKIP_DOTS
		);
		$iterator = new RecursiveIteratorIterator($directoryIterator);

		// make a collection to store the files in
		$files = collect();

		foreach($iterator as $file) {
			// add each file into the collection
			$files->push($file);
		}

		$this->files = $files;

		$this->setComponents();
	}

	private function sortingFn($a, $b)
	{

		if (!isset($a['data']) || !isset($b['data'])) {
			return 0;
		}

		$aPath = $a['data']->getPathname();
		$aData = json_decode(file_get_contents($aPath));
		if (!isset($aData->title)) {
			return -1;
		}
		$aTitle = $aData->title;

		$bPath = $b['data']->getPathname();
		$bData = json_decode(file_get_contents($bPath));
		if (!isset($bData->title)) {
			return 1;
		}
		$bTitle = $bData->title;

		$aPos = array_search($aTitle, $this->renderOrder);
		$aPos = $aPos === false ? INF : $aPos;
		$bPos = array_search($bTitle, $this->renderOrder);
		$bPos = $bPos === false ? INF : $bPos;

		return $aPos > $bPos ? 1 : -1;
	}

	/**
	 * @param component - {Collection} in this format:
	 * [
	 *   'template' => {SplFileInfo},
	 *   'data' => {SplFileInfo}
	 * ],
	 */
	private function renderElement(Collection $component) : string
	{

		$dataPath = $component['data']->getPathname();
		// get the json contents from the data file
		$data = json_decode(file_get_contents($dataPath), true);

		$title = isset($data['title']) ? $data['title'] : '';
		$desc = isset($data['description']) ? $data['description'] : '';
		$exampleContainerCSS = isset($data['exampleContainerCSS']) ? $data['exampleContainerCSS'] : '';

		$html = "<section id='{$component['name']}' class='pattern-library__section'>";
		$html .= '<article>';
		$html .= $title ? "<h2><a class='pattern-library__anchor' href='?element={$component['name']}'>$title</a></h2>" : '';

		$html .= $desc ? collect($desc)->reduce(function($html, $para) {
			$para = preg_replace('/\//', '/&#8203;', $para);
			$para = preg_replace('/`([^`]*)`/', '<code class="pattern-library__code--inline">\1</code>', $para);
			return $html . "<p>$para</p>";
		}, ''): '';
		$html .= '</article>';

		$instanceData = collect(isset($data['instances']) ? $data['instances'] : []);

		$file = $component['template']->getPathname();

		// iterate over the data for instances
		// of this entity (atom, molecule, etc.)
		// and run it through the blade templating engine
		$instances = $instanceData->map(function($datum) use($file, $exampleContainerCSS) {

			$baseDir = get_stylesheet_directory() . '/views/';
			$fileName = str_replace([$baseDir, '/', '.blade.php'], ['', '.', ''], $file);

			// make the JSON look like PHP associative array
			$toReplace = [
				/* JSON formatting */
				'": "',
				/* replace spaces with tabs */
				'/    /',
				/* craziness to get existing forward slashes to output correctly */
				'/\\\\\//',
				/* opening and closing braces */
				'/\{/',
				'/\}/'
			];
			$replaceWith = [" => ", '	', '/', '[', ']'];

			$json = htmlspecialchars(json_encode($datum, JSON_PRETTY_PRINT));
			$json = preg_replace($toReplace, $replaceWith, $json);

			// transform the inputs for this element into an example
			$usageDetails = '<details class="pattern-library__details"><summary class="pattern-library__summary">Usage details:</summary>';
			$usageDetails .= "<p class='pattern-library__p'>To use this element: <code class='pattern-library__usage'>@include('$fileName', \$options)</code></p>";

			$usageDetails .= '<p class="pattern-library__p">Options used for this example:</p>';
			$usageDetails .= '<pre class="pattern-library__pre"><code class="pattern-library__code">' . $json . '</code></pre>';
			$usageDetails .= '</details>';

			$result = "<section class='pattern-library__instance'>";
			$result .= $usageDetails;
			$result .= "<div class='pattern-library__example' style='$exampleContainerCSS'>";
			$result .= \App\sage('blade')->render($file, (array) $datum);
			$result .= '</div>';
			$result .= '</section>';

			return $result;
		});

		// render nothing if empty
		if (!$instances->count()) {
			return '';
		}

		// concatenate all the rendered HTML into a string
		$html .= $instances->reduce(function($html, $instance) {
			return $html . $instance;
		}, '');

		$html .= '</section>';
		$html .= '<hr class="pattern-library__hr" />';

		return $html;
	}

	public function setComponents() : void
	{
		 /**
		 * iterate over the files and group the template and
		 * the json file together making a structure like this:
		 * [
		 *   'path/to/folder/containing/files1' => [
		 *     'name' => {string}
		 *     'template' => {SplFileInfo},
		 *     'data' => {SplFileInfo}
		 *   ],
		 *   'path/to/folder/containing/files2' => [
		 *     'name' => {string}
		 *     'template' => {SplFileInfo},
		 *     'data' => {SplFileInfo}
		 *   ],
		 * ]
		 * Where {SplFileInfo} is an object representing the file
		 * https://www.php.net/manual/en/class.splfileinfo.php
		 */
		$this->components = $this->files->reduce(

			// callback
			function(Collection $components, SplFileInfo $file) {
				$path = $file->getPath();
				$extension = $file->getExtension() === 'json' ? 'data' : 'template';

				if (!isset($components[$path])) {
					$components[$path] = collect();
				}

				$components[$path][$extension] = $file;
				$components[$path]['name'] = preg_split('/\./', $file->getFilename())[0];

				return $components;
			},

			// starting value
			collect()
		)->sort(function($a, $b) {
			return $this->sortingFn($a, $b);
		})->filter(function($component) {
			return isset($component['data']);
		});
	}

	public function templates() : Collection
	{

		// render each of these elements into html
		// for consumption by template.
		return $this->components
		->filter(function($component) {
			if (isset($_GET['element'])) {
				return $_GET['element'] === $component['name'];
			}
			return true;
		})
		->map(function($component) {
			return $this->renderElement($component);
		});
	}

	public function anchors() : Collection
	{
		return $this->components->map(function($component) {
			$dataPath = $component['data']->getPathname();
			// get the json contents from the data file
			$data = json_decode(file_get_contents($dataPath), true);

			$title = isset($data['title']) ? $data['title'] : '';

			return [
				'url' => $component['name'],
				'title' => !empty($data['instances']) ? $title : '',
			];
		})->filter(function($component) {
			return $component['title'] !== '';
		});

	}
}
