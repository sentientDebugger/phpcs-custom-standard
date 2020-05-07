<?php

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Remove whitespace surrounding object operators "->".
 * e.g. from:
 * ```php
 * $exampleObject -> func();
 * $exampleObject -> chain()
 *     -> chain();
 * ```
 * to:
 * ```php
 * $exampleObject->func();
 * $exampleObject->chain()
 *     ->chain();
 * ```
 * @author Carlos Enrique Sifuentes <me@carlos-sifuentes.com>
 */
class DisallowSpacesAroundObjectOperatorSniff implements Sniff
{
	/**
	 * Returns an array of tokens this test wants to listen for.
	 * In this case the object operator "->"
	 *
	 * @return array
	 */
	public function register()
	{
		return [
			T_OBJECT_OPERATOR,
		];
	}

	/**
	 * Processes this sniff, when one of its tokens is encountered.
	 *
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile The current file being checked.
	 * @param int                         $stackPtr  The position of the current token in
	 *                                               the stack passed in $tokens.
	 *
	 * @return void
	 */
	public function process(File $phpcsFile, $stackPtr)
	{
		$tokens = $phpcsFile->getTokens();

		if (isset($tokens[$stackPtr + 1]) && $tokens[$stackPtr + 1]['code'] === T_WHITESPACE) {
			// If the next token is a whitespace: remove it
			$this->processWhitespaceRight($phpcsFile, $stackPtr + 1);
		}

		if (isset($tokens[$stackPtr - 1]) && $tokens[$stackPtr - 1]['code'] === T_WHITESPACE) {
			/**
			 * if the previous token is a whitespace, remove it only if this
			 * token isn't the first non-whitespace token on the current line.
			 * Example where the space(s) before the object operator shouldn't
			 * be removed:
			 * ```php
			 * $exampleObject->chainFunc1()
			 *     ->chainFunc2()
			 *     ->chainFunc3();
			 * ```
			 */
			$prevNonWhitespace = $phpcsFile->findPrevious(T_WHITESPACE, $stackPtr - 1, null, true);
			if ($prevNonWhitespace === false || $tokens[$prevNonWhitespace]['line'] !== $tokens[$stackPtr]['line']) {
				return;
			}
			$this->processWhitespaceLeft($phpcsFile, $stackPtr - 1);
		}
	}

	/**
	 * @uses DisallowSpacesAroundObjectOperatorSniff::processWhitespace
	 */
	private function processWhitespaceRight($phpcsFile, $ptr)
	{
		$this->processWhitespace($phpcsFile, $ptr, 'after');
	}
	/**
	 * @uses DisallowSpacesAroundObjectOperatorSniff::processWhitespace
	 */
	private function processWhitespaceLeft($phpcsFile, $ptr)
	{
		$this->processWhitespace($phpcsFile, $ptr, 'before');
	}

	/**
	 * Add the error and remove the whitespace
	 * @param int $ptr The ptr to the whitespace token that will be removed
	 * @param string $orientation Allowed values: before|after
	 */
	private function processWhitespace($phpcsFile, $ptr, $orientation)
	{
		$fix = $phpcsFile->addFixableError(
			"There should not be spaces {$orientation} \"->\"",
			$ptr,
			'Space'. ucfirst($orientation) . 'ObjectOperator'
		);
		if ($fix) {
			$phpcsFile->fixer->beginChangeset();
			// Could be multiple spaces.
			// Safe to find them using findPrevious(T_WHITESPACE, $ptr, null, true)+1?
			$phpcsFile->fixer->replaceToken($ptr, '');
			$phpcsFile->fixer->endChangeset();
		}
	}
}
