<?php
namespace Budgetlens\BolRetailerApi\Endpoints;

use Budgetlens\BolRetailerApi\Resources\ProcessStatus;

class Transports extends BaseEndpoint
{
    /**
     * Add transport information
     * @see https://api.bol.com/retailer/public/redoc/v6#operation/add-transport-information-by-transport-id
     * @param string $transportId
     * @param string $transporterCode
     * @param string $trackAndTrace
     * @return ProcessStatus
     */
    public function addInformation(
        string $transportId,
        ?string $transporterCode = null,
        ?string $trackAndTrace = null
    ): ProcessStatus {
        $payload = collect([
            'transporterCode' => $transporterCode,
            'trackAndTrace' => $trackAndTrace,
        ])->reject(function ($value) {
            return empty($value);
        });

        $response = $this->performApiCall(
            'PUT',
            "transports/{$transportId}",
            $payload->toJson()
        );

        return new ProcessStatus(collect($response));
    }
}
