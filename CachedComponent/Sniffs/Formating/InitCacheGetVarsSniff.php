<?php

namespace someStandart\Sniffs\Formatting;

use someStandart\Sniffs\BaseSniff;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Util\Tokens;

class InitCacheGetVarsSniff implements Sniff {
    public function register() {
        return [
            T_IF,
        ];
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
        $nextfunc = $phpcsFile->findNext( Tokens::$functionNameTokens, $stackPtr, null, false, 'initCache');

        $ifOwner = $tokens[$stackPtr]['parenthesis_owner'];
        $indexIfToken = $tokens[$stackPtr]['parenthesis_opener'];
        if(isset($tokens[$nextfunc]['nested_parenthesis']))
        foreach($tokens[$nextfunc]['nested_parenthesis'] as $key => $value)
            $indexFuncToken = $key;


        if($tokens[$nextfunc]['content'] == 'initCache' && $indexIfToken == $indexFuncToken) {
            $objName = $phpcsFile->findPrevious( T_VARIABLE, $nextfunc, null, false);
            $check = $phpcsFile->findNext( Tokens::$functionNameTokens, $nextfunc, null, false, 'GetVars');
            

            foreach ($tokens[$check]['conditions'] as $key => $value) {
                $indexIfTokenChecked = $key;
            }

            if(isset($indexIfTokenChecked) && $indexIfTokenChecked == $ifOwner){
               $secondObjName = $phpcsFile->findPrevious( T_VARIABLE, $check, null, false);
               if($tokens[$secondObjName]['content'] != $tokens[$objName]['content']){
                $phpcsFile->addError(
                        sprintf(
                            'Call GetVars() by other object',
                         $tokens[ $stackPtr ]['line'] - 1
                        ),
                        $stackPtr,
                        'Add if($value->initCache())'
                    );
               }
            }else{
                $phpcsFile->addError(
                        sprintf(
                            'No Call GetVars()',
                         $tokens[ $stackPtr ]['line'] - 1
                        ),
                        $stackPtr,
                        'Add if($value->initCache())'
                    );
            }
        }
    }
}
