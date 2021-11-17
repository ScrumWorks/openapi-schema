<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\Casing\NativeFunctionCasingFixer;
use PhpCsFixer\Fixer\ClassNotation\ProtectedToPrivateFixer;
use PhpCsFixer\Fixer\FunctionNotation\NativeFunctionInvocationFixer;
use PhpCsFixer\Fixer\Import\FullyQualifiedStrictTypesFixer;
use PhpCsFixer\Fixer\Import\GlobalNamespaceImportFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitStrictFixer;
use PhpCsFixer\Fixer\Phpdoc\GeneralPhpdocAnnotationRemoveFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocLineSpanFixer;
use PhpCsFixer\Fixer\Whitespace\IndentationTypeFixer;
use PhpCsFixer\Fixer\Whitespace\MethodChainingIndentationFixer;
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
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\CodingStandard\Fixer\ArrayNotation\StandaloneLineInMultilineArrayFixer;
use Symplify\CodingStandard\Fixer\Commenting\ParamReturnAndVarTagMalformsFixer;
use Symplify\CodingStandard\Fixer\LineLength\LineLengthFixer;
use Symplify\EasyCodingStandard\ValueObject\Option;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

# see https://github.com/symplify/easy-coding-standard
return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();

    $parameters->set(Option::PARALLEL, true);

    $parameters->set(Option::PATHS, [
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

    // pick anything from https://github.com/symplify/easy-coding-standard#use-prepared-checker-sets
    $containerConfigurator->import(SetList::PSR_12);
    $containerConfigurator->import(SetList::COMMON);
    $containerConfigurator->import(SetList::CLEAN_CODE);

    $parameters->set(Option::SKIP, [
        // allowed
        \PHP_CodeSniffer\Standards\Generic\Sniffs\CodeAnalysis\AssignmentInConditionSniff::class . '.FoundInWhileCondition',

        // we can't use FQN in doctrine annotations
        ReferenceUsedNamesOnlySniff::class . '.' . ReferenceUsedNamesOnlySniff::CODE_REFERENCE_VIA_FULLY_QUALIFIED_NAME => [
            __DIR__ . '/src/Annotation/ArrayValue.php',
            __DIR__ . '/src/Annotation/HashmapValue.php',
            __DIR__ . '/src/Annotation/Union.php',
            __DIR__ . '/src/Annotation/HashmapValue.php',
        ],

        // compare 2 object contents
        PhpUnitStrictFixer::class => [
            __DIR__ . '/tests/ValueSchema/Builder',
            __DIR__ . '/tests/SchemaParserTest.php',
            __DIR__ . '/tests/OpenApiTranslatorTest.php',
        ],
    ]);

    $services = $containerConfigurator->services();

    $services->set(ProtectedToPrivateFixer::class);
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

    # use 4 spaces to indent
    $services->set(IndentationTypeFixer::class);

    # native functions should be casted in lowercase
    $services->set(NativeFunctionCasingFixer::class);

    # import namespaces
    $services->set(FullyQualifiedStrictTypesFixer::class);
    $services->set(GlobalNamespaceImportFixer::class);

    // slevomat rules
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
