<?php

namespace Xpage\Utils;

class TimeDebugger
{
    private        $data = [];
    private        $tmp  = [];
    private string $title;

    public function __construct(string $title)
    {
        $this->title = $title;
    }

    public function start(string $label)
    {
        $this->tmp[ $label ] = microtime(1);
    }

    public function stop(string $label)
    {
        $diff = round(microtime(1) - $this->tmp[ $label ], 3);
        if (!$this->data[ $label ])
        {
            $this->data[ $label ] = [
                'count' => 1,
                'time'  => $diff,
            ];
        }
        else
        {
            $this->data[ $label ]['count']++;
            $this->data[ $label ]['time']+=$diff;
        }
    }

    public function saveStats()
    {
        foreach ($this->data as  &$stats)
        {
            if($stats['count'] && $stats['time'])
            {
                $stats['average'] = round($stats['time']/$stats['count'],2);
            }
        }
        unset($stats);
        \Xpage\Tools::log($this->data,$this->title,'time_benchmark');
    }
}