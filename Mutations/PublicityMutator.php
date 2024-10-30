<?php

namespace App\GraphQL\Mutations\Content;

class PublicityMutator
{
    public function __construct(
        private PublicityRepository $publicityRepository,
    ) {}

    public function addPublicityInquiry($_, array $args): Feedback
    {
        $language = $args['language'];
        $typePublicityOrders = $args['type_publicity_orders'];
        $senderName = $args['sender_name'];
        $email = $args['email'];
        $textMessage = $args['text_message'];
        $reference = $args['reference'] ?? null;

        return $this
            ->publicityRepository
            ->makeNewInquiry($language, $typePublicityOrders, $senderName, $email, $textMessage, $reference);
    }
}
