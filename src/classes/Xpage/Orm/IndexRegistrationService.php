<?php

namespace Xpage\Orm;

use Bitrix\Main\Application;
use Bitrix\Main\DB\MysqliConnection;

class IndexRegistrationService
{
    private string $tableName;
    private array  $indexesMap;


    public function setTableName(string $tableName):self
    {
        if (!empty($tableName))
        {
            $this->tableName = $tableName;
        }
        $this->checkTableExists();
        return $this;
    }

    /**
     * @return void
     * @throws \Exception
     */
    private function checkTableExists(): void
    {
        //проверка существования таблицы
        if (!\Bitrix\Main\Application::getConnection()->isTableExists($this->tableName))
        {
            throw new \Exception('Table ' . $this->tableName . ' not exists');
        }
    }


    /**
     * Регистрируем необходимость создать индекс
     */
    public function addIndex(array $fields, array $fieldsLengths = []): void
    {
        $this->indexesMap[] = [
            'fields'        => $fields,
            'fieldsLengths' => $fieldsLengths,
        ];
    }

    public function commit(): void
    {
        $this->checkTableExists();
        $this->createIndexes();
    }

    private function createIndexes()
    {
        foreach ($this->indexesMap as $index)
        {
            if($index['fieldsLengths'])
            {
                $this->createSimpleIndex($index['fields']);

            }
            else
            {
                $this->createIndex($index['fields'], $index['fieldsLengths']);
            }

        }
    }

    private function createIndex(array $fields, array $fieldsLengths = [])
    {
        $connection = Application::getConnection();
        $indexName = $this->generateIndexName($fields);
        //в $connection нет метода createIndex, который позволяет задавать длину полей, поэтому создадим дополнительное соединение
        $additionalConnection = new MysqliConnection($connection->getConfiguration());
        $additionalConnection->createIndex($this->tableName, $indexName, $fields, $fieldsLengths);
    }

    private function generateIndexName(array $fields):string
    {
        $indexName = 'index_';
        //отсортируем поля по алфавиту
        sort($fields);

        foreach ($fields as $field)
        {
            $indexName .= $field . '_';
        }
        return $indexName;
    }

    private function createSimpleIndex(array $fields)
    {
        $connection = Application::getConnection();
        $indexName = $this->generateIndexName($fields);
        $connection->createIndex($this->tableName, $indexName, $fields);
    }


}