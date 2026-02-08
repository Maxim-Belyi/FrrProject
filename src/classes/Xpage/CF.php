<?php

namespace Xpage;

use Bitrix\Main\Type\DateTime;

class CF
{

    private const API_KEY = "24a3b5d59e488ccaa6213c7090eefca66f15877b";
    private const URL = "https://focus-api.kontur.ru/api3/";
    private string $inn;
    private string $type;
    private string $ogrn;
    private $egrulData;
    private $requisiteData;

    public function __construct(string $inn)
    {
        if (strlen($inn)===10) {
            $this->type='UL';
        } elseif (strlen($inn)===12) {
            $this->type='IP';
        } else {
            throw new \Exception('Неверный ИНН');
        }

        $this->inn = $inn;
        $this->getEgrulData();
        $this->getRequisiteData();
    }

    private static function request(string $type, array $params): array
    {
        $client = new \Bitrix\Main\Web\HttpClient();
        $params['key'] = self::API_KEY;
        $query = http_build_query($params);
        $res = $client->get(self::URL . "$type?$query");
        $status = $client->getStatus();
        $result = json_decode($res, 1);
        if (empty($result)) {
            if ($status === 200) {
                throw new \Exception('Данные не найдены');
            }
        }
        return $result;
    }

    private function getEgrulData():void
    {
        if ($this->egrulData) {
            return;
        }
        $this->egrulData = self::request('egrDetails', ['inn' => $this->inn])[0][$this->type];;

        if (!$this->egrulData) {
            throw new \Exception('Данные не найдены');
        }
    }

    private function getRequisiteData():void
    {
        if ($this->requisiteData) {
            return;
        }
        $requisiteData=self::request('req', ['inn' => $this->inn])[0];
        $this->ogrn=$requisiteData['ogrn'];
        $this->requisiteData = $requisiteData[$this->type];

        if (!$this->requisiteData) {
            throw new \Exception('Данные не найдены');
        }
    }

    public function getData():array
    {
        return [
            'egrulData'=>$this->egrulData,
            'RequisiteData'=>$this->requisiteData,
        ];
    }

    public function getMainOkved():array
    {
        $mainOkved=$this->egrulData['activities']['principalActivity'];

        if (!$mainOkved) {
            return [];
        }

        return [
            'name' => $mainOkved['text'],
            'code' =>$mainOkved['code'],
        ];
    }

    public function getLegalAddress(): string
    {
        if (!$addrData = $this->requisiteData['legalAddress']['parsedAddressRF']) {
            return '';
        }
        $types = [
            'regionName' => null,
            'city' => null,
            'street' => null,
            'house' => null,
            'flat' => 'кв.',

        ];
        $addressData[] = $addrData['zipCode'];
        foreach ($types as $fieldName => $prefix) {
            if (empty($addrData[$fieldName]['topoValue'])) {
                continue;
            }
            $tmp = ($prefix ?: $addrData[$fieldName]['topoShortName']) . " ";
            $tmp .= $addrData[$fieldName]['topoValue'];
            $addressData[] = $tmp;
        }
        return implode(', ', $addressData);
    }

    public function getHead(): array
    {
        if (!$this->requisiteData['heads']) {
            return [];
        }

        foreach ($this->requisiteData['heads'] as $head) {
            if ($head['position'] === "Директор") {
                return $head;
            }
        }
        return $this->requisiteData['heads'][0];
    }

    public function getName(): string
    {
        return $this->requisiteData['legalName']['readable']?:$this->requisiteData['fio'];
    }

    public function getKpp(): string
    {
        return $this->requisiteData['kpp'];
    }

    public function getOgrn(): string
    {
        return $this->ogrn;
    }

    public function getRegionCode(): string
    {
        return $this->egrulData['shortenedAddress']['regionCode']?:$this->requisiteData['legalAddress']['parsedAddressRF']['regionCode'];
    }

}