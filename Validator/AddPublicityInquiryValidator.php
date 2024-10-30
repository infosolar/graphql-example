<?php

declare(strict_types=1);

class AddPublicityInquiryValidator extends Validator
{
    public function rules(): array
    {
        $typePublicityPositionValidator = [];

        foreach ($this->arg('type_publicity_orders') as $key => $typePublicity) {
            $typePublicityPositionValidator["type_publicity_orders.{$key}.position"] = [
                "sometimes",
                "integer",
                "min:1",
                "max:" . (
                TypePublicity::query()
                    ->find($typePublicity['type_publicity_id'])
                    ?->typePublicityPosition?->positions_count ?: 1
                ),
            ];
        }

        return array_merge(
            [
                'language' => ['required', 'exists:languages,slug'],
                'type_publicity_orders' => ['required', 'array', 'min:1'],
                'type_publicity_orders.*.type_publicity_id' => [
                    'required',
                    'exists:type_publicities,id',
                ],
                'sender_name' => ['required', 'string', 'min:3'],
                'email' => ['required', 'email'],
                'text_message' => [
                    'required',
                    'string',
                    'min:10',
                    "unique:feedback,text_message,NULL,id,sender_name,{$this->arg('sender_name')},email,{$this->arg('email')}",
                ],
            ],
            $typePublicityPositionValidator
        );
    }

    public function messages(): array
    {
        return [
            'text_message.unique' => 'Duplicate content.',
        ];
    }
}
