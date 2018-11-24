<?php
namespace BD\EzPlatformGraphQLPage\GraphQL\Schema\Worker;

class BaseWorker
{
    /**
     * @var \BD\EzPlatformGraphQLPage\GraphQL\Schema\Worker\NameHelper
     */
    private $nameHelper;

    public function setNameHelper(NameHelper $nameHelper)
    {
        $this->nameHelper = $nameHelper;
    }

    protected function getNameHelper()
    {
        return $this->nameHelper;
    }
}