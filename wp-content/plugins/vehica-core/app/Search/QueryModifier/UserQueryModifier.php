<?php

namespace Vehica\Search\QueryModifier;


/**
 * Class UserQueryModifier
 * @package Vehica\Search\QueryModifier
 */
class UserQueryModifier implements QueryModifier
{
    /**
     * @param array $queryParams
     * @param array $requestParams
     * @return array
     */
    public function modifyQuery($queryParams, $requestParams = [])
    {
        if (isset($requestParams['user_id'])) {
            $queryParams['author'] = (int)$requestParams['user_id'];
        }

        return $queryParams;
    }

}