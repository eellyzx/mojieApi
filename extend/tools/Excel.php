<?php

namespace tools;

class Excel
{
    /**
     * 导出
     * @param $data
     * @param array $tableHeader
     * @param int $number
     * @param string $fileName
     */
    public static function reportCsv($data, $tableHeader = [], $number = 9000, $fileName = '')
    {
        $count = count($data);
        if (empty($fileName)) {
            $fileName = "reports" . date("YmdHis", time());
        }
        // 头部信息
        header("Content-type:text/csv");
        header('Content-Disposition:attachment;filename=' . $fileName . '.csv');
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        // 输出表头
        echo iconv('utf-8', 'gbk//TRANSLIT', '"' . implode('","', $tableHeader) . '"' . "\n");
        // 输出表体
        foreach ($data as $key => $value) {
            $outPut = [];
            foreach ($tableHeader as $field => $name) {
                $field = explode(',', $field);
                if (! empty($field[1])) {
                    $outPut[] = empty($value[$field[0]][$field[1]]) ? '' : $value[$field[0]][$field[1]];
                } else {
                    $outPut[] = $value[$field[0]];
                }
            }
            echo iconv('utf-8', 'gbk//TRANSLIT', '"' . implode('","', $outPut) . "\"\n");
        }
    }
}