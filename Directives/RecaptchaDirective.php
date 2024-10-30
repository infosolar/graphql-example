<?php

declare(strict_types=1);


class RecaptchaDirective extends BaseDirective implements ArgDirective, FieldMiddleware
{
    public static function definition(): string
    {
        return /** @lang GraphQL */ <<<'GRAPHQL'
                directive @recaptcha(
                  field: String
                ) on FIELD_DEFINITION | INPUT_FIELD_DEFINITION
                GRAPHQL;
    }

    public function handleField(FieldValue $fieldValue, Closure $next)
    {
        $resolver = $fieldValue->getResolver();
        return $next(
            $fieldValue->setResolver(
                function ($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo) use ($resolver) {
                    if ($this->directiveHasArgument('field')) {
                        $fieldName = $this->directiveArgValue('field');
                    } else {
                        $fieldName = 'recaptcha';
                    }

                    $validationFactory = app(ValidationFactory::class);
                    assert($validationFactory instanceof ValidationFactory);
                    $validator = $validationFactory->make(
                        $args,
                        [$fieldName => ['required', new RecaptchaRule()]],
                        ["{$fieldName}.required" => __('exceptions.recaptcha.no_challenge')]
                    );

                    if ($validator->fails()) {
                        $path = implode('.', $resolveInfo->path);
                        throw new ValidationException("Validation failed for the field [$path].", $validator);
                    }

                    $resolveInfo->argumentSet->arguments = collect(
                        $resolveInfo
                            ->argumentSet->arguments
                    )
                        ->filter(fn (Argument $item, string $key) => $key !== $fieldName);

                    return $resolver($root, $args, $context, $resolveInfo);
                }
            )
        );
    }
}
