<?php
/**
 * @copyright 2017 Roman Parpalak
 * @license   MIT
 */

namespace S2\Rose\Entity;

use S2\Rose\Exception\RuntimeException;

/**
 * Class SnippetLine
 */
class SnippetLine
{
	/**
	 * @var string[]
	 */
	protected $foundWords = array();

	/**
	 * @var string
	 */
	protected $line = '';

	/**
	 * SnippetLine constructor.
	 *
	 * @param string   $line
	 * @param string[] $foundWords
	 */
	public function __construct($line, array $foundWords)
	{
		$this->line       = $line;
		$this->foundWords = $foundWords;
	}

	/**
	 * @return string
	 */
	public function getLine()
	{
		return $this->line;
	}

	/**
	 * @return int
	 */
	public function getWordCount()
	{
		return count($this->foundWords);
	}

	/**
	 * @return string[]
	 */
	public function getFoundWords()
	{
		return $this->foundWords;
	}

	/**
	 * @param string $highlightTemplate
	 *
	 * @return string
	 */
	public function getHighlighted($highlightTemplate)
	{
		if (strpos($highlightTemplate, '%s') === false) {
			throw new RuntimeException('Highlight template must contain "%s" substring.');
		}

		$line       = $this->line;
		$quoteStyle = defined('ENT_HTML5') ? (ENT_HTML5 | ENT_NOQUOTES) : ENT_NOQUOTES;
		$line       = html_entity_decode($line, $quoteStyle, 'UTF-8');

		// prev versions
		//$snippet[$lineNum] = strtr($lines[$lineNum], $replace);
		// Cleaning up HTML entites TODO $word may be undefined
		//$snippet[$lineNum] = preg_replace('#&[^;]{0,10}(?:<i>' . preg_quote($word, '#') . '</i>[^;]{0,15})+;#ue', 'str_replace(array("<i>", "</i>"), "", "\\0")', $snippet[$lineNum]);

		$replacedLine = preg_replace_callback(
			'#\b(' . implode('|', $this->foundWords) . ')\b#su',
			function ($matches) use ($highlightTemplate) {
				return sprintf($highlightTemplate, $matches[1]);
			},
			$line,
			-1,
			$count
		);

		return $replacedLine;
	}
}
