<?php

declare(strict_types=1);


class LicenseQuery
{
    public function __construct(
        private LicenseRepository $licenseRepository
    ) {}

    public function getManyModels($_, array $args): array
    {
        $language = $args['language'] ?? null;

        return $this->licenseRepository
            ->getManyModels($language);
    }
}
