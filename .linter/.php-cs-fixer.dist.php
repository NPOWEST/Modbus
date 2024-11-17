<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('var')
;

return (new PhpCsFixer\Config())
    ->setParallelConfig(PhpCsFixer\Runner\Parallel\ParallelConfigFactory::detect())
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
        '@DoctrineAnnotation' => true,

        'header_comment' => ['header' => "@see https://npowest.ru\n\n@license Shareware\n@copyright (c) 2019-2024 NPOWest", 'comment_type' => 'PHPDoc', 'location' => 'after_open', 'separate' => 'both'],

        /** Alias */

        // Replace non multibyte-safe functions with corresponding mb function. (risky)
        'mb_str_functions' => true,

        /* Array Notation */

        // If the function explicitly returns an array, and has the return type iterable, then yield from must be used instead of return.
        'return_to_yield_from' => true,
        // Yield from array must be unpacked to series of yields.
        'yield_from_array_to_yields' => true,

        /** Attribute Notation */

        // PHP attributes declared without arguments must (not) be followed by empty parentheses.
        'attribute_empty_parentheses' => ['use_parentheses' => false],
        // Sorts attributes using the configured sort algorithm.
        'ordered_attributes' => true,


        /** Basic */

        // Braces must be placed as configured.
        'braces_position' => [
            // Allow anonymous functions to have opening and closing braces on the same line.
            'allow_single_line_anonymous_functions' => true,
            // Allow anonymous classes to have opening and closing braces on the same line.
            'allow_single_line_empty_anonymous_classes' => true,
            // The position of the opening brace of anonymous classes‘ body.
            // Allowed values: 'next_line_unless_newline_at_signature_end' and 'same_line'
            'anonymous_classes_opening_brace' => 'next_line_unless_newline_at_signature_end',
            // The position of the opening brace of anonymous functions‘ body.
            // Allowed values: 'next_line_unless_newline_at_signature_end' and 'same_line'
            'anonymous_functions_opening_brace' => 'next_line_unless_newline_at_signature_end',
            // The position of the opening brace of classes‘ body.
            // Allowed values: 'next_line_unless_newline_at_signature_end' and 'same_line'
            'classes_opening_brace' => 'next_line_unless_newline_at_signature_end',
            // The position of the opening brace of control structures‘ body.
            // Allowed values: 'next_line_unless_newline_at_signature_end' and 'same_line'
            'control_structures_opening_brace' => 'next_line_unless_newline_at_signature_end',
            // The position of the opening brace of functions‘ body.
            // Allowed values: 'next_line_unless_newline_at_signature_end' and 'same_line'
            'functions_opening_brace' => 'next_line_unless_newline_at_signature_end',
        ],
        // Adds separators to numeric literals of any kind.
        'numeric_literal_separator' => ['strategy' => 'use_separator', 'override_existing' => true],
        // Literal octal must be in 0o notation.
        'octal_notation' => true,
        // Empty body of class, interface, trait, enum or function must be abbreviated as {} and placed on the same line as the previous symbol, separated by a single space.
        'single_line_empty_body' => true,

        /** Casing */

        /** Cast Notation */

        /** Class Notation */

        // Class, trait and interface elements must be separated with one or none blank line.
        'class_attributes_separation' => ['elements' => ['const' => 'one', 'method' => 'one', 'property' => 'one', 'trait_import' => 'one', 'case' => 'one']],
        // Whitespace around the keywords of a class, trait, enum or interfaces definition should be one space.
        'class_definition' => [
            // Whether constructor argument list in anonymous classes should be single line.
            'inline_constructor_arguments' => true,
            // Whether definitions should be multiline.
            'multi_line_extends_each_single_line' => false,
            // Whether definitions should be single line when including a single item.
            'single_item_single_line' => true,
            // Whether definitions should be single line.
            'single_line' => true,
            // Whether there should be a single space after the parenthesis of anonymous class (PSR12) or not.
            'space_before_parenthesis' => true,
        ],
        // All classes must be final, except abstract ones and Doctrine entities. (risky)
        'final_class' => true,
        // Internal classes should be final. (risky)
        'final_internal_class' =>  ['exclude' => ['@not-fix', 'final', 'Entity', 'ORM\\Entity', 'ORM\\Mapping\\Entity', 'Mapping\\Entity', 'Document', 'ODM\\Document']],
        // // All public methods of abstract classes should be final. (risky)
        // 'final_public_method_for_abstract_class' => true,
        // Orders the interfaces in an implements or interface extends clause.
        'ordered_interfaces' => true,
        // Sort union types and intersection types using configured order.
        'ordered_types' => ['sort_algorithm' => 'alpha', 'null_adjustment' => 'always_last'],
        // Converts protected variables and methods to private where possible.
        'protected_to_private' => true,
        // Inside an enum or final/anonymous class, self should be preferred over static.
        'self_static_accessor' => true,

        /** Comment */

        // Comments with annotation should be docblock when used on structural elements. (risky)
        'comment_to_phpdoc' => ['ignored_tags' => []],
        // DocBlocks must start with two asterisks, multiline comments must start with a single asterisk, after the opening slash. Both must end with a single asterisk before the closing slash.
        'multiline_comment_opening_closing' => true,
        // Single-line comments and multi-line comments with only one line of actual content should use the // syntax.
        'single_line_comment_spacing' => false,

        /** Control Structure */

        // Control structure continuation keyword must be on the configured line.
        'control_structure_continuation_position' => ['position' => 'next_line'],
        // There should not be useless else cases.
        'no_useless_else' => true,
        // Simplify if control structures that return the boolean result of their condition.
        'simplified_if_return' => true,

        /** Function Notation */

        // The first argument of DateTime::createFromFormat method must start with !. (risky)
        'date_time_create_from_format_call' => true,
        // In method arguments and method call, there MUST NOT be a space before each comma and there MUST be one space after each comma. Argument lists MAY be split across multiple lines, where each subsequent line is indented once. When doing so, the first item in the list MUST be on the next line, and there MUST be only one argument per line.
        'method_argument_space' => ['on_multiline' => 'ensure_fully_multiline', 'keep_multiple_spaces_after_comma' => false],
        // Takes @param annotations of non-mixed types and adjusts accordingly the function signature. Requires PHP >= 7.0. (experimental, risky
        'phpdoc_to_param_type' => true,
        // Takes @var annotation of non-mixed types and adjusts accordingly the property signature. Requires PHP >= 7.4. (experimental, risky)
        'phpdoc_to_property_type' => true,
        // Takes @return annotation of non-mixed types and adjusts accordingly the function signature. (experimental, risky)
        'phpdoc_to_return_type' => true,
        // Callables must be called without using call_user_func* when possible. (risky)
        'regular_callable_call' => true,
        // Lambdas not (indirectly) referencing $this must be declared static. (risky)
        'static_lambda' => true,
        // Anonymous functions with one-liner return statement must use arrow functions. (risky)
        'use_arrow_functions' => true,
        // Add void return type to functions with missing or empty return statements, but priority is given to @return annotations. Requires PHP >= 7.1. (risky)
        'void_return' => true,

        /** Import */

        // Removes the leading part of fully qualified symbol references if a given symbol is imported or belongs to the current namespace.
        'fully_qualified_strict_types' =>  ['import_symbols' => true],
        // Imports or fully qualifies global classes/functions/constants.
        'global_namespace_import' => ['import_classes' => true, 'import_constants' => true, 'import_functions' => true],
        // There MUST be group use for the same namespaces.
        'group_import' => true,
        // Ordering use statements.
        'ordered_imports' => ['sort_algorithm' => 'alpha', 'imports_order' => ['class', 'function', 'const']],
        // There MUST be one use keyword per declaration.
        'single_import_per_statement' => false,

        /** Language Construct */

        // Converts FQCN strings to *::class keywords. (experimental, risky)
        'class_keyword' => true,
        // Using isset($var) && multiple times should be done in one call.
        'combine_consecutive_issets' => true,
        // Calling unset on multiple items should be done in one call.
        'combine_consecutive_unsets' => true,
        // Error control operator should be added to deprecation notices and/or removed from other cases. (risky)
        'error_suppression' => true,
        // Add curly braces to indirect variables to make them clear to understand. Requires PHP >= 7.0.
        'explicit_indirect_variable' => true,
        // Properties should be set to null instead of using unset. (risky)
        'no_unset_on_property' => true,

        /** List Notation */

        // List (array destructuring) assignment should be declared using the configured syntax. Requires PHP >= 7.1.
        'list_syntax' => ['syntax' => 'short'],

        /** Namespace Notation */

        /** Operator */

        // Use the null coalescing assignment operator ??= where possible.
        'assign_null_coalescing_to_coalesce_equal' => true,
        // Binary operators should be surrounded by space as configured.
        'binary_operator_spaces' => [
            'default'   => 'align_single_space_minimal',
            'operators' => ['??' => null, '*' => null, '/' => null, '+' => 'single_space', '-' => 'single_space', '=>' => 'align_single_space_minimal_by_scope']
        ],
        // There should not be useless concat operations.
        'no_useless_concat_operator' => ['juggle_simple_strings' => true],
        // Logical NOT operators (!) should have one trailing whitespace.
        'not_operator_with_successor_space' => true,
        // Use null coalescing operator ?? where possible. Requires PHP >= 7.0.
        'ternary_to_null_coalescing' => true,

        /** PHP Tag */

        /** PHPDoc */

        // Each line of multi-line DocComments must have an asterisk [PSR-5] and must be aligned with the first one.
        'align_multiline_comment' => ['comment_type' => 'phpdocs_like'],
        // Renames PHPDoc tags.
        'general_phpdoc_tag_rename' => ['replacements' => ['inheritDocs' => 'inheritDoc', 'inherit' => 'inheritDoc', 'inhebit' => 'inheritDoc']],
        // PHPDoc should contain @param for all params.
        'phpdoc_add_missing_param_annotation' => ['only_untyped' => false],
        // Changes doc blocks from single to multi line, or reversed. Works for class constants, properties and methods only.
        'phpdoc_line_span' => ['const' => 'single', 'method' => 'multi', 'property' => 'single'],
        // @return void and @return null annotations should be omitted from PHPDoc.
        'phpdoc_no_empty_return' => true,
        // Order PHPDoc tags by value.
        'phpdoc_order_by_value' => true,
        // Annotations in PHPDoc should be ordered in defined sequence.
        'phpdoc_order' => ['order' => ['package', 'link', 'license', 'copyright', 'param', 'return', 'throws']],
        // Orders all @param annotations in DocBlocks according to method signature.
        'phpdoc_param_order' => true,
        // Annotations in PHPDoc should be grouped together so that annotations of the same type immediately follow each other. Annotations of a different type are separated by a single blank line.
        'phpdoc_separation' => ['groups' => [['Annotation', 'NamedArgumentConstructor', 'Target'], ['copyright', 'license'], ['param'], ['return'], ['property', 'property-read', 'property-write'], ['deprecated', 'link', 'see', 'since']], 'skip_unlisted_annotations' => true],
        // Docblocks should only be used on structural elements.
        'phpdoc_to_comment' => ['ignored_tags' => ['todo', 'var', 'psalm-suppress', 'SuppressWarnings']],
        // Sorts PHPDoc types.
        'phpdoc_types_order' => ['sort_algorithm' => 'alpha', 'null_adjustment' => 'always_last'],
        // @var and @type annotations must have type and name in the correct order.
        'phpdoc_var_annotation_correct_order' => true,

        /** Return Notation */

        // There should not be an empty return statement at the end of a function.
        'no_useless_return' => true,
        // Local, dynamic and directly referenced variables should not be assigned and directly returned by a function or method.
        'return_assignment' => true,
        // A return statement wishing to return void should not return null.
        'simplified_null_return' => true,

        /** Semicolon */

        // Forbid multi-line whitespace before the closing semicolon or move the semicolon to the new line for chained calls.
        'multiline_whitespace_before_semicolons' => ['strategy' => 'new_line_for_chained_calls'],

        /** Strict */

        // Force strict types declaration in all files. Requires PHP >= 7.0.
        'declare_strict_types' => true,

        /** String Notation */

        // Converts implicit variables into explicit ones in double-quoted strings or heredoc syntax.
        'explicit_string_variable' => true,

        /** Whitespace */

        // An empty line feed must precede any configured statement.
        'blank_line_before_statement' => ['statements' => ['break', 'continue', 'declare', 'return', 'throw', 'try', 'case']],
        // Method chaining MUST be properly indented. Method chaining with different levels of indentation is not supported.
        'method_chaining_indentation' => true,

        // // 'braces' => [
        // //     'position_after_anonymous_constructs'         => 'next',
        // //     'position_after_control_structures'           => 'next',
        // //     'position_after_functions_and_oop_constructs' => 'next'
        // // ],
    ])
    ->setFinder($finder)
;
