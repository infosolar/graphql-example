<?php

declare(strict_types=1);

class BrokerLogMutator
{
    public function __construct(
        private MutatorService $mutatorService,
    ) {}

    /**
     * @throws Exception
     */
    public function rollback($_, array $args): Broker|AccountType|array
    {
        /** @var BrokerLog $brokerLog */
        $brokerLog = BrokerLog::query()
            ->findOrFail($args['brokerLogId']);

        /** @var Broker|AccountType $entity */
        $entity = match ($brokerLog->type_log) {
            BrokerLog::LOG_MODE_MAIN_DATA => $brokerLog->broker()
                ->withRelations()
                ->first(),
            BrokerLog::LOG_MODE_ACCOUNT_TYPE => $brokerLog->accountType()
                ->withRelations()
                ->first(),
        };

        return $this->mutatorService->rollbackBrokerLog($entity, $args['inputData'], $brokerLog);
    }
}
