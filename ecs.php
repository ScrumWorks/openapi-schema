<?php

declare(strict_types=1);

use PHP_CodeSniffer\Standards\Generic\Sniffs\CodeAnalysis\AssignmentInConditionSniff;
use PhpCsFixer\Fixer\Import\FullyQualifiedStrictTypesFixer;
use PhpCsFixer\Fixer\Phpdoc\GeneralPhpdocAnnotationRemoveFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocLineSpanFixer;
use PhpCsFixer\Fixer\Strict\StrictParamFixer;
use PhpCsFixer\Fixer\StringNotation\SimpleToComplexStringVariableFixer;
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
use Symplify\CodingStandard\Fixer\Annotation\RemoveMethodNameDuplicateDescriptionFixer;
use Symplify\CodingStandard\Fixer\Annotation\RemovePHPStormAnnotationFixer;
use Symplify\CodingStandard\Fixer\Annotation\RemovePropertyVariableNameDescriptionFixer;
use Symplify\CodingStandard\Fixer\ArrayNotation\ArrayListItemNewlineFixer;
use Symplify\CodingStandard\Fixer\ArrayNotation\ArrayOpenerAndCloserNewlineFixer;
use Symplify\CodingStandard\Fixer\ArrayNotation\StandaloneLineInMultilineArrayFixer;
use Symplify\CodingStandard\Fixer\Commenting\RemoveUselessDefaultCommentFixer;
use Symplify\CodingStandard\Fixer\LineLength\LineLengthFixer;
use Symplify\CodingStandard\Fixer\Spacing\SpaceAfterCommaHereNowDocFixer;
use Symplify\CodingStandard\Fixer\Spacing\StandaloneLinePromotedPropertyFixer;
use Symplify\CodingStandard\Fixer\Strict\BlankLineAfterStrictTypesFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;

# see https://github.com/easy-coding-standard/easy-coding-standard
return ECSConfig::configure()
    ->withPaths([__DIR__ . '/ecs.php', __DIR__ . '/src', __DIR__ . '/tests'])
    ->withPreparedSets(psr12: true, common: true, cleanCode: true)
    ->withSkip([
        ReferenceUsedNamesOnlySniff::class . '.' . ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME,
        AssignmentInConditionSniff::class . '.Found',
        StrictParamFixer::class,
    ])
    ->withConfiguredRule(GeneralPhpdocAnnotationRemoveFixer::class, [
        'annotations' => ['author', 'package', 'group', 'autor', 'covers'],
    ])
    # imports FQN names
    ->withConfiguredRule(ReferenceUsedNamesOnlySniff::class, [
        'searchAnnotations' => true,
        'allowFullyQualifiedGlobalFunctions' => true,
        'allowFullyQualifiedGlobalConstants' => true,
        'allowPartialUses' => true,
    ])
    ->withRules([
        # former "symplify" set (dropped in ECS 13), minus the skipped MethodChainingNewlineFixer
        RemovePHPStormAnnotationFixer::class,
        RemoveUselessDefaultCommentFixer::class,
        RemoveMethodNameDuplicateDescriptionFixer::class,
        RemovePropertyVariableNameDescriptionFixer::class,
        ArrayListItemNewlineFixer::class,
        ArrayOpenerAndCloserNewlineFixer::class,
        StandaloneLinePromotedPropertyFixer::class,
        SpaceAfterCommaHereNowDocFixer::class,
        BlankLineAfterStrictTypesFixer::class,
        LineLengthFixer::class,

        PhpdocLineSpanFixer::class,
        StandaloneLineInMultilineArrayFixer::class,
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
        SimpleToComplexStringVariableFixer::class,
    ]);
