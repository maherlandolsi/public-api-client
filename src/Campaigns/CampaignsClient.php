<?php

namespace ArrowSphere\PublicApiClient\Campaigns;

use ArrowSphere\PublicApiClient\AbstractClient;
use ArrowSphere\PublicApiClient\Campaigns\Entities\Asset\Asset;
use ArrowSphere\PublicApiClient\Campaigns\Entities\Asset\AssetUploadUrl;
use ArrowSphere\PublicApiClient\Campaigns\Entities\Campaign;
use ArrowSphere\PublicApiClient\Campaigns\Entities\CampaignV2;
use ArrowSphere\PublicApiClient\Exception\EntityValidationException;
use ArrowSphere\PublicApiClient\Exception\NotFoundException;
use ArrowSphere\PublicApiClient\Exception\PublicApiClientException;
use Generator;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Class CampaignsClient
 */
class CampaignsClient extends AbstractClient
{
    /**
     * @var string The path of the List Campaigns end point
     */
    private const FIND_PATH = '/campaigns';
    private const FIND_PATH_V2 = '/campaigns/v2';

    /**
     * Get a single campaign.
     *
     * @param string $reference The reference of the campaign
     *
     * @return Campaign|null
     *
     * @throws GuzzleException
     * @throws EntityValidationException
     * @throws NotFoundException
     * @throws PublicApiClientException
     */
    public function getCampaign(string $reference): ?Campaign
    {
        $response = $this->getCampaignRaw($reference);
        $data = $this->decodeResponse($response);
        $result = null;
        if ($data['data']) {
            $result = new Campaign($data['data']);
        }

        return $result;
    }

    /**
     * Get a single campaign.
     *
     * @param string $reference The reference of the campaign
     *
     * @return string
     *
     * @throws GuzzleException
     * @throws NotFoundException
     * @throws PublicApiClientException
     */
    public function getCampaignRaw(string $reference): string
    {
        $reference = urlencode($reference);
        $this->path = self::FIND_PATH . "/$reference";

        return $this->get();
    }

    /**
     * Get a single V2 campaign.
     *
     * @param string $reference The reference of the campaign
     *
     * @return CampaignV2|null
     *
     * @throws GuzzleException
     * @throws EntityValidationException
     * @throws NotFoundException
     * @throws PublicApiClientException
     */
    public function getCampaignV2(string $reference): ?CampaignV2
    {
        $response = $this->getCampaignV2Raw($reference);
        $data = $this->decodeResponse($response);
        $result = null;
        if ($data['data']) {
            $result = new CampaignV2($data['data']);
        }

        return $result;
    }

    /**
     * Get a single V2 campaign.
     *
     * @param string $reference The reference of the campaign
     *
     * @return string
     *
     * @throws GuzzleException
     * @throws NotFoundException
     * @throws PublicApiClientException
     */
    public function getCampaignV2Raw(string $reference): string
    {
        $reference = urlencode($reference);
        $this->path = self::FIND_PATH_V2 . "/$reference";

        return $this->get();
    }

    /**
     * Get all active campaigns
     *
     * @param string $location
     * @param string|null $customerRef
     * @param bool $includeAssets
     *
     * @return Generator
     *
     * @throws EntityValidationException
     * @throws GuzzleException
     * @throws NotFoundException
     * @throws PublicApiClientException
     */
    public function getActiveCampaigns(
        string $location,
        ?string $customerRef = null,
        bool $includeAssets = false
    ): Generator {
        $response = $this->getActiveCampaignsRaw($location, $customerRef, $includeAssets);
        $data = $this->decodeResponse($response);
        foreach ($data['data'] as $data) {
            yield new CampaignV2($data);
        }
    }

    /**
     * @param string $location example : MCP, ARROWSPHERE
     * @param string|null $customerRef
     * @param bool $includeAssets
     *
     * @return string
     *
     * @throws GuzzleException
     * @throws NotFoundException
     * @throws PublicApiClientException
     */
    public function getActiveCampaignsRaw(
        string $location,
        ?string $customerRef = null,
        bool $includeAssets = false
    ): string {
        $params = ['location' => $location];
        if ($customerRef !== null) {
            $params['customer'] = $customerRef;
        }
        if ($includeAssets) {
            $params['includeAssets'] = 'true';
        }
        $this->path = self::FIND_PATH_V2 . '/active?' . http_build_query($params);

        return $this->get();
    }

    /**
     * Get a single active campaign
     *
     * @param string $customerRef
     *
     * @return Campaign|null
     *
     * @throws GuzzleException
     * @throws EntityValidationException
     * @throws NotFoundException
     * @throws PublicApiClientException
     *
     * @deprecated This method is obsolete. Please use the new version of the same method, getActiveCampaignV2.
     */
    public function getActiveCampaign(string $customerRef): ?Campaign
    {
        $response = $this->getActiveCampaignRaw($customerRef);
        $data = $this->decodeResponse($response);
        $result = null;
        if ($data['data']) {
            $result = new Campaign($data['data']);
        }

        return $result;
    }

    /**
     * @param string $customerRef
     *
     * @return string
     *
     * @throws GuzzleException
     * @throws NotFoundException
     * @throws PublicApiClientException
     *
     * @deprecated This method is obsolete. Please use the new version of the same method, getActiveCampaignRawV2.
     */
    public function getActiveCampaignRaw(string $customerRef): string
    {
        $this->path = self::FIND_PATH . "/active?customer=" . $customerRef;

        return $this->get();
    }

    /**
     * Get a single active campaign
     *
     * @param string $location
     * @param string|null $customerRef
     *
     * @return Campaign|null
     *
     * @throws EntityValidationException
     * @throws GuzzleException
     * @throws NotFoundException
     * @throws PublicApiClientException
     */
    public function getActiveCampaignV2(string $location, ?string $customerRef = null): ?Campaign
    {
        $response = $this->getActiveCampaignRawV2($location, $customerRef);
        $data = $this->decodeResponse($response);
        $result = null;
        if ($data['data']) {
            $result = new Campaign($data['data']);
        }

        return $result;
    }

    /**
     * @param string $location example : MCP, ARROWSPHERE
     * @param string|null $customerRef
     *
     * @return string
     *
     * @throws GuzzleException
     * @throws NotFoundException
     * @throws PublicApiClientException
     */
    public function getActiveCampaignRawV2(string $location, ?string $customerRef = null): string
    {
        $params = ['location' => $location];
        if ($customerRef !== null) {
            $params['customer'] = $customerRef;
        }
        $this->path = self::FIND_PATH . "/active?" . http_build_query($params);

        return $this->get();
    }

    /**
     * Lists all the campaigns.
     * Returns an array (generator) of Campaign
     *
     * @param array $parameters Optional parameters to add to the URL
     *
     * @return Generator<CampaignV2>
     *
     * @throws EntityValidationException
     * @throws GuzzleException
     * @throws NotFoundException
     * @throws PublicApiClientException
     */
    public function getCampaigns(array $parameters = []): Generator
    {
        $this->setPerPage(100);
        $currentPage = 1;
        $lastPage = false;

        while (! $lastPage) {
            $this->setPage($currentPage);
            $rawResponse = $this->getCampaignsRaw($parameters);
            $response = $this->decodeResponse($rawResponse);

            if ($response['pagination']['total_page'] <= $currentPage) {
                $lastPage = true;
            }

            $currentPage++;

            foreach ($response['data'] as $data) {
                yield new CampaignV2($data);
            }
        }
    }

    /**
     * Lists all the campaigns.
     *
     * @param array $parameters
     *
     * @return string
     *
     * @throws GuzzleException
     * @throws NotFoundException
     * @throws PublicApiClientException
     */
    public function getCampaignsRaw(array $parameters = []): string
    {
        $this->path = self::FIND_PATH;

        return $this->get($parameters);
    }

    /**
     * @param string $reference
     *
     * @return Asset[]
     *
     * @throws GuzzleException
     * @throws NotFoundException
     * @throws PublicApiClientException
     */
    public function getCampaignAssets(string $reference): array
    {
        $rawResponse = $this->getCampaignAssetsRaw($reference);

        $data = $this->decodeResponse($rawResponse);

        return array_map(static function (array $asset) {
            return new Asset($asset);
        }, $data['data']['assets']);
    }

    /**
     * @param string $reference
     *
     * @return string
     *
     * @throws GuzzleException
     * @throws NotFoundException
     * @throws PublicApiClientException
     */
    public function getCampaignAssetsRaw(string $reference): string
    {
        $reference = urlencode($reference);
        $this->path = self::FIND_PATH . "/$reference/assets";

        return $this->get();
    }

    /**
     * @param string $reference
     *
     * @return AssetUploadUrl[]
     *
     * @throws GuzzleException
     * @throws NotFoundException
     * @throws PublicApiClientException
     */
    public function getCampaignAssetsUploadUrl(string $reference): array
    {
        $rawResponse = $this->getCampaignAssetsUploadUrlRaw($reference);

        $data = $this->decodeResponse($rawResponse);

        return array_map(static function (array $assetUploadUrl) {
            return new AssetUploadUrl($assetUploadUrl);
        }, $data['data']['assets']);
    }

    /**
     * @param string $reference
     *
     * @return string
     *
     * @throws GuzzleException
     * @throws NotFoundException
     * @throws PublicApiClientException
     */
    public function getCampaignAssetsUploadUrlRaw(string $reference): string
    {
        $reference = urlencode($reference);
        $this->path = self::FIND_PATH . "/$reference/assets/upload";

        return $this->get();
    }

    /**
     * @param array $params
     *
     * @return string
     *
     * @throws GuzzleException
     * @throws NotFoundException
     * @throws PublicApiClientException
     */
    public function createCampaign(array $params): string
    {
        $this->path = self::FIND_PATH;

        return $this->post([
            'category' => $params['category'],
            'name'     => $params['name'],
        ]);
    }

    /**
     * @param array $params
     * @param string $reference
     *
     * @return string
     *
     * @throws GuzzleException
     * @throws NotFoundException
     * @throws PublicApiClientException
     */
    public function saveCampaign(array $params, string $reference): string
    {
        $this->path = self::FIND_PATH . "/$reference";

        return $this->put($params['campaign']);
    }

    /**
     * @param string $reference
     *
     * @return string
     *
     * @throws GuzzleException
     * @throws NotFoundException
     * @throws PublicApiClientException
     */
    public function duplicateCampaign(string $reference): string
    {
        $reference = urlencode($reference);
        $this->path = self::FIND_PATH . "/$reference/duplicate";

        return $this->post([]);
    }

    /**
     * @param string $reference
     *
     * @return string
     *
     * @throws GuzzleException
     * @throws NotFoundException
     * @throws PublicApiClientException
     */
    public function deleteCampaign(string $reference): string
    {
        $reference = urlencode($reference);
        $this->path = self::FIND_PATH . "/$reference";

        return $this->delete();
    }

    /**
     * @param string $campaignRef
     * @param string $assetRef
     *
     * @return string
     *
     * @throws GuzzleException
     * @throws NotFoundException
     * @throws PublicApiClientException
     */
    public function deleteAsset(string $campaignRef, string $assetRef): string
    {
        $campaignRef = urlencode($campaignRef);
        $assetRef = urlencode($assetRef);
        $this->path = self::FIND_PATH . "/$campaignRef/assets/$assetRef";

        return $this->delete();
    }
}
