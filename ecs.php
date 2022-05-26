<?php

declare(strict_types=1);

use PHP_CodeSniffer\Standards\Generic\Sniffs\CodeAnalysis\AssignmentInConditionSniff;
use PhpCsFixer\Fixer\Import\FullyQualifiedStrictTypesFixer;
use PhpCsFixer\Fixer\Phpdoc\GeneralPhpdocAnnotationRemoveFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocLineSpanFixer;
use PhpCsFixer\Fixer\Strict\StrictParamFixer;
use PhpCsFixer\Fixer\Whitespace\IndentationTypeFixer;
use SlevomatCodingStandard\Sniffs\Arrays\DisallowImplicitArrayCreationSniff;
use SlevomatCodingStandard\Sniffs\Classes\DisallowLateStaticBindingForConstantsSniff;
use SlevomatCodingStandard\Sniffs\Classes\UselessLateStaticBindingSniff;
use SlevomatCodingStandard\Sniffs\ControlStructures\RequireNullCoalesceOperatorSniff;
use SlevomatCodingStandard\Sniffs\Exceptions\DeadCatchSniff;
use SlevomatCodingStandard\Sniffs\Functions\StaticClosureSniff;
use SlevomatCodingStandard\Sniffs\Namespaces\ReferenceUsedNamesOnlySniff;
use SlevomatCodingStandard\Sniffs\Namespaces\UseFromSameNamespaceSniff;
use SlevomatCodingStandard\Sniffs\PHP\OptimizedFunctionsWithoutUnpackingSniff;
use SlevomatCodingStandard\Sniffs\PHP\UselessParenthesesSniff;
use SlevomatCodingStandard\Sniffs\PHP\UselessSemicolonSniff;
use SlevomatCodingStandard\Sniffs\Variables\DuplicateAssignmentToVariableSniff;
use SlevomatCodingStandard\Sniffs\Variables\UselessVariableSniff;
use Symplify\CodingStandard\Fixer\ArrayNotation\StandaloneLineInMultilineArrayFixer;
use Symplify\CodingStandard\Fixer\Commenting\ParamReturnAndVarTagMalformsFixer;
use Symplify\CodingStandard\Fixer\LineLength\LineLengthFixer;
use Symplify\CodingStandard\Fixer\Spacing\MethodChainingNewlineFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

# see https://github.com/symplify/easy-coding-standard
return static function (ECSConfig $ecsConfig): void {
    $ecsConfig->paths([__DIR__ . '/ecs.php', __DIR__ . '/src', __DIR__ . '/tests']);

    $ecsConfig->sets([
        # pick anything from https://github.com/symplify/easy-coding-standard#use-prepared-checker-sets
        SetList::PSR_12,
        SetList::COMMON,
        SetList::CLEAN_CODE,
        SetList::SYMPLIFY,
    ]);

    $ecsConfig->skip([
        ReferenceUsedNamesOnlySniff::class . '.' . ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME,
        AssignmentInConditionSniff::class . '.Found',
        MethodChainingNewlineFixer::class,
        StrictParamFixer::class,
    ]);

    $ecsConfig->ruleWithConfiguration(GeneralPhpdocAnnotationRemoveFixer::class, [
        'annotations' => ['author', 'package', 'group', 'autor', 'covers'],
    ]);

    # imports FQN names
    $ecsConfig->ruleWithConfiguration(ReferenceUsedNamesOnlySniff::class, [
        'searchAnnotations' => true,
        'allowFullyQualifiedGlobalFunctions' => true,
        'allowFullyQualifiedGlobalConstants' => true,
        'allowPartialUses' => true,
    ]);

    $ecsConfig->rules([
        PhpdocLineSpanFixer::class,
        LineLengthFixer::class,
        StandaloneLineInMultilineArrayFixer::class,
        ParamReturnAndVarTagMalformsFixer::class,
        IndentationTypeFixer::class,
        FullyQualifiedStrictTypesFixer::class,
        UseFromSameNamespaceSniff::class,
        DuplicateAssignmentToVariableSniff::class,
        OptimizedFunctionsWithoutUnpackingSniff::class,
        UselessSemicolonSniff::class,
        DeadCatchSniff::class,
        UselessVariableSniff::class,
        UselessParenthesesSniff::class,
        DisallowLateStaticBindingForConstantsSniff::class,
        UselessLateStaticBindingSniff::class,
        RequireNullCoalesceOperatorSniff::class,
        StaticClosureSniff::class,
        DisallowImplicitArrayCreationSniff::class,
    ]);
};
