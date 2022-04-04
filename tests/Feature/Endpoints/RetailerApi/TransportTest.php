<?php
namespace Budgetlens\BolRetailerApi\Tests\Feature\Endpoints\RetailerApi;

use Budgetlens\BolRetailerApi\Exceptions\ValidationException;
use Budgetlens\BolRetailerApi\Resources\ProcessStatus;
use Budgetlens\BolRetailerApi\Tests\TestCase;
use Budgetlens\BolRetailerApi\Types\TransporterCode;
use Illuminate\Support\Collection;

class TransportTest extends TestCase
{
    /** @test */
    public function addTransportInformation()
    {
        $this->useMock('200-add-transport-information.json');

        $transportId = '358612589';
        $transporterCode = TransporterCode::TNT;
        $trackAndTrace = '3SAOLD1234567';

        $status = $this->client->transports->addInformation($transportId, $transporterCode, $trackAndTrace);
        $this->assertInstanceOf(ProcessStatus::class, $status);
        $this->assertSame('1', $status->processStatusId);
        $this->assertSame('358612589', $status->entityId);
        $this->assertSame('CHANGE_TRANSPORT', $status->eventType);
        $this->assertSame('PENDING', $status->status);
        $this->assertInstanceOf(Collection::class, $status->links);
        $this->assertInstanceOf(ProcessStatus\Link::class, $status->links->first());
    }

    /** @test */
    public function addTransportInfoMissingTransporterCode()
    {
        $this->useMock('400-add-transport-information-missing-transporter-code.json', 400);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Validation Failed, See violations');

        $transportId = '358612589';
        $transporterCode = '';
        $trackAndTrace = '3SAOLD1234567';

        try {
            $this->client->transports->addInformation($transportId, $transporterCode, $trackAndTrace);
        } catch (ValidationException $e) {
            $violations = $e->getViolations();
            $this->assertSame("transporterCode", $violations->first()->name);
            $this->assertSame("Request contains invalid value(s): '', allowed values: TNT, DHL, UPS, TNT-EXTRA, COURIER, DPD-BE, DPD-NL, BPOST_BE, BPOST_BRIEF, BRIEFPOST, DHLFORYOU, DYL, FEDEX_BE, FEDEX_NL, GLS, OTHER, TNT_BRIEF, TSN, FIEGE, DHL_DE, DHL-GLOBAL-MAIL, TRANSMISSION, TNT-EXPRESS, PARCEL-NL, LOGOIX, PACKS, PES.", $violations->first()->reason);
            throw $e;
        }
    }
}
