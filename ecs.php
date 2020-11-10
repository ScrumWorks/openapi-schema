<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\Basic\Psr4Fixer;
use PhpCsFixer\Fixer\Casing\NativeFunctionCasingFixer;
use PhpCsFixer\Fixer\FunctionNotation\NativeFunctionInvocationFixer;
use PhpCsFixer\Fixer\Import\FullyQualifiedStrictTypesFixer;
use PhpCsFixer\Fixer\Import\GlobalNamespaceImportFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitStrictFixer;
use PhpCsFixer\Fixer\Phpdoc\GeneralPhpdocAnnotationRemoveFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocLineSpanFixer;
use PhpCsFixer\Fixer\Strict\DeclareStrictTypesFixer;
use PhpCsFixer\Fixer\Strict\StrictComparisonFixer;
use PhpCsFixer\Fixer\Strict\StrictParamFixer;
use PhpCsFixer\Fixer\Whitespace\IndentationTypeFixer;
use PhpCsFixer\Fixer\Whitespace\MethodChainingIndentationFixer;
use SlevomatCodingStandard\Helpers\PropertyTypeHint;
use SlevomatCodingStandard\Sniffs\Arrays\DisallowImplicitArrayCreationSniff;
use SlevomatCodingStandard\Sniffs\Classes\DisallowLateStaticBindingForConstantsSniff;
use SlevomatCodingStandard\Sniffs\Classes\UnusedPrivateElementsSniff;
use SlevomatCodingStandard\Sniffs\Classes\UselessLateStaticBindingSniff;
use SlevomatCodingStandard\Sniffs\ControlStructures\RequireNullCoalesceOperatorSniff;
use SlevomatCodingStandard\Sniffs\Exceptions\DeadCatchSniff;
use SlevomatCodingStandard\Sniffs\Exceptions\ReferenceThrowableOnlySniff;
use SlevomatCodingStandard\Sniffs\Functions\StaticClosureSniff;
use SlevomatCodingStandard\Sniffs\Namespaces\ReferenceUsedNamesOnlySniff;
use SlevomatCodingStandard\Sniffs\Namespaces\UnusedUsesSniff;
use SlevomatCodingStandard\Sniffs\Namespaces\UseFromSameNamespaceSniff;
use SlevomatCodingStandard\Sniffs\PHP\OptimizedFunctionsWithoutUnpackingSniff;
use SlevomatCodingStandard\Sniffs\PHP\UselessParenthesesSniff;
use SlevomatCodingStandard\Sniffs\PHP\UselessSemicolonSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\PropertyTypeHintSniff;
use SlevomatCodingStandard\Sniffs\Variables\DuplicateAssignmentToVariableSniff;
use SlevomatCodingStandard\Sniffs\Variables\UnusedVariableSniff;
use SlevomatCodingStandard\Sniffs\Variables\UselessVariableSniff;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\CodingStandard\Fixer\ArrayNotation\StandaloneLineInMultilineArrayFixer;
use Symplify\CodingStandard\Fixer\Commenting\ParamReturnAndVarTagMalformsFixer;
use Symplify\CodingStandard\Fixer\LineLength\LineLengthFixer;
use Symplify\CodingStandard\Fixer\Spacing\RemoveSpacingAroundModifierAndConstFixer;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

# see https://github.com/symplify/easy-coding-standard
return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();

    $parameters->set('paths', [
        __DIR__ . '/src',
    ]);

    $parameters->set('sets', [
        # pick anything from https://github.com/symplify/easy-coding-standard#use-prepared-checker-sets
        SetList::PSR_12,
        SetList::COMMON,
        SetList::PHP_70,
        SetList::PHP_71,
        SetList::CLEAN_CODE,
        SetList::DEAD_CODE,
    ]);

    $parameters->set('skip', [
        ReferenceUsedNamesOnlySniff::class . '.' . ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME_WITHOUT_NAMESPACE => null,
        ReferenceUsedNamesOnlySniff::class . '.' . ReferenceUsedNamesOnlySniff::CODE_PARTIAL_USE => null,

        # resolve later with strict_types
        DeclareStrictTypesFixer::class => null,
        StrictComparisonFixer::class => null,
        PhpUnitStrictFixer::class => null,
        StrictParamFixer::class => null,
        # breaks code
        ReferenceThrowableOnlySniff::class . '.' . ReferenceThrowableOnlySniff::CODE_REFERENCED_GENERAL_EXCEPTION => null,
        Psr4Fixer::class => null,
        UnusedUsesSniff::class . '.' . UnusedUsesSniff::CODE_MISMATCHING_CASE => [
            __DIR__ . '/tests/*',
        ],
    ]);

    $services = $containerConfigurator->services();

    // @todo fix
    // $services->set(ProtectedToPrivateFixer::class);

    $services->set(MethodChainingIndentationFixer::class);

    $services->set(GeneralPhpdocAnnotationRemoveFixer::class)
        ->call('configure', [[
            'annotations' => ['author', 'package', 'group', 'autor', 'covers']
        ]]);

    # add preslash to every native function, to speedup process, e.g. \count()
    $services->set(NativeFunctionInvocationFixer::class);

    # limit line length to 120 chars
    $services->set(LineLengthFixer::class);

    # imports FQN names
    $services->set(ReferenceUsedNamesOnlySniff::class)
        ->property('searchAnnotations', true)
        ->property('allowFullyQualifiedGlobalFunctions', true)
        ->property('allowFullyQualifiedGlobalConstants', true)
        ->property('allowPartialUses', false);

    # make @var annotation into doc block
    $services->set(PhpdocLineSpanFixer::class);

    $services->set(MethodChainingIndentationFixer::class);

    # array - item per line
    $services->set(StandaloneLineInMultilineArrayFixer::class);

    # make @param, @return and @var format united
    $services->set(ParamReturnAndVarTagMalformsFixer::class);

    # spaces
    $services->set(RemoveSpacingAroundModifierAndConstFixer::class);

    # use 4 spaces to indent
    $services->set(IndentationTypeFixer::class);

    # native functions should be casted in lowercase
    $services->set(NativeFunctionCasingFixer::class);

    # import namespaces
    $services->set(FullyQualifiedStrictTypesFixer::class);

    $services->set(GlobalNamespaceImportFixer::class);

    # slevomat rules from ruleset.xml
    $services->set(UseFromSameNamespaceSniff::class);

    $services->set(DuplicateAssignmentToVariableSniff::class);

    $services->set(OptimizedFunctionsWithoutUnpackingSniff::class);

    $services->set(UselessSemicolonSniff::class);

    $services->set(DeadCatchSniff::class);

    $services->set(UselessVariableSniff::class);

    $services->set(UselessParenthesesSniff::class);

    $services->set(DisallowLateStaticBindingForConstantsSniff::class);

    $services->set(UselessLateStaticBindingSniff::class);

    $services->set(RequireNullCoalesceOperatorSniff::class);

    $services->set(StaticClosureSniff::class);

    $services->set(DisallowImplicitArrayCreationSniff::class);
};
