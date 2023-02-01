<?php

namespace CachedComponent\Sniffs\Formatting;

use CachedComponent\Sniffs\BaseSniff;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;

class InitCacheGetListSniff implements Sniff {
    public function register() {
        return Tokens::$functionNameTokens;
    }
    public function process( File $phpcsFile, $stackPtr ) {
        //$a - T_VARIABLE
        //:: - T_DOUBLE_COLON
        //-> - T_OBJECT_OPERATOR
        //{ - PHPCS_T_OPEN_CURLY_BRACKET
        //} - PHPCS_T_CLOSE_CURLY_BRACKET
        // start of if [parenthesis_opener]
        // end of if [parenthesis_closer]
        // причастность токена к ифу [nested_parenthesis]
        // $functionNameTokens
        // T_OBJECT_OPERATOR

        $tokens   = $phpcsFile->getTokens();
        

        if($tokens[$stackPtr]['content'] == 'getList') {

            foreach ($tokens[$stackPtr]['conditions'] as $key => $value)
                $startIf = $key;

            if($tokens[$startIf]['type'] === 'T_IF') {
                $method = $phpcsFile->findNext( Tokens::$functionNameTokens, $startIf, null, false);

                if($tokens[$method]['content'] != 'initCache'){
                    $phpcsFile->addError(
                        sprintf(
                            'uncorrect if($value->initCache())',
                         $tokens[ $stackPtr ]['line'] - 1
                        ),
                        $stackPtr,
                        'Add if($value->initCache())'
                    );
                }

            }
            else {
                $phpcsFile->addError(
                    sprintf(
                        'using getList out of if($value->initCache())',
                        $tokens[ $stackPtr ]['line'] - 1
                    ),
                    $stackPtr,
                    'Add if($value->initCache())'
                );
            }
        }
    }
}
