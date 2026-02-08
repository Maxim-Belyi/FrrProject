<?php

namespace Xpage\Orm;

use Bitrix\Main\Config\Configuration;

abstract class XpageDataManager extends \Bitrix\Main\Entity\DataManager
{
    const PREFIX = 'custom';
    private IndexRegistrationService $indexRegistrationService;

    /**
     * Создает таблицу БД и индексы для неё
     */
    final public static function createTableAndIndexes(bool $agreedToReinstall = false): void
    {
        if(self::isInstalled() && !$agreedToReinstall)
        {
            throw new \Exception('Таблица '.self::getTableName().' уже установлена. Подтвердите согласие на переустановку');
        }
        $entity = static::getEntity();
        $Connection = $entity->getConnection();
        $tableName = self::getTableName();
        if ($Connection->isTableExists($tableName))
        {
            $Connection->dropTable($tableName);
        }
        $entity->createDbTable();

        $table = new static;
        $table->internalIndexesRegistration();
    }

    /**
     * Удаляет таблицу БД
     */
    final public static function dropTable(string $command='')
    {
        $command = trim(mb_strtolower($command));
        if($command!=='удалить')
        {
            throw new \Exception('вы должны руками вписать в параметр слово "удалить"');
        }
        $entity = static::getEntity();
        $tableName = static::getTableName();
        $entity->getConnection()->dropTable($tableName);

    }

    abstract protected function registerIndexes(IndexRegistrationService $simpleIndexRegistration ): void;

    private function internalIndexesRegistration()
    {
        $this->indexRegistrationService = new IndexRegistrationService();

        $this->indexRegistrationService->setTableName(self::getTableName());

        static::registerIndexes($this->indexRegistrationService);
        $this->indexRegistrationService->commit();
    }

    abstract protected function getName(): string;

    /**
     *  Вернуть поля будущей таблицы. Будет использован в getMap
     */
    abstract protected function getFields(): array;

    final public static function getTableName(): string
    {
        return self::PREFIX . '_' . (new static())->getName();
    }

    final public static function getMap(): array
    {
        $fieldsMap = (new static())->getFields();
        return $fieldsMap;
    }




    private static function isInstalled():bool
    {
        $settingsKey = 'created_tables_' . self::PREFIX;
        $config = Configuration::getValue($settingsKey) ?: [];
        return !empty($config[ static::getTableName() ]);
    }
}