<?php

declare(strict_types=1);



class BrokerLogQuery
{
    public function __construct(
        private BrokerLogService $brokerLogService,
    ) {}

    #[ArrayShape([
        'id' => "int",
        'broker_id' => "int|null",
        'account_type_id' => "int|null",
        'type_log' => "string",
        'updated_at' => "datetime",
        'user_type' => "string",
        'account_type_name' => "string",
        'account_type_deleted_status' => "bool",
        'deleting_mode' => "bool",
        'changes' => "string|null",
        'log_data' => "\App\DTO\GraphQL\Queries\View\AbstractLogDataView",
    ])]
    public function getBrokerLog($_, array $args): array
    {
        return $this->brokerLogService->getBrokerLogById($args['brokerLogId']);
    }

    public function getBrokerLogs($_, array $args): array
    {
        return $this->brokerLogService->getAllBrokerLogsWithChangesList($args['brokerId']);
    }
}
