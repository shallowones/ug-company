<?php
/**
 * Created by PhpStorm.
 * User: agr
 * Date: 16.02.2017
 * Time: 19:27
 */

namespace UW\Modules\Notify\Entities;


use UW\Modules\Notify\Exceptions\EntityException;

class EntityStorage
{
    /**
     * Создание сущности
     *
     * @param $entityType
     * @param $entityId
     * @return IEntity
     * @throws EntityException
     */
    public function get($entityType, $entityId)
    {
        if (Statements::ENTITY_CODE == $entityType) {
            $data = [
                'id' => $entityId,
                'status' => 'status',
                'ownerId' => 1,
                'number' => 2
            ];

            return new Statements($data['id'], $data['status'], $data['ownerId'], $data['number'], $data);
        }

        if (Test::ENTITY_CODE == $entityType) {
            $data = [
                'id' => $entityId,
                'status' => 'status',
                'ownerId' => 1,
                'number' => 2
            ];

            return new Test($data['id'], $data['status'], $data['ownerId'], $data['number'], $data);
        }

        throw new EntityException("Не верный тип сущности {$entityType}", EntityException::ENTITY_WRONG_TYPE);
    }

}