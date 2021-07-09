<?php
namespace tools;
class ArrayHelper
{

    /**
     * 对象的值添加到数组
     *
     * @param array $array 需要添加元素的数组
     * @param string $name 数组的键名
     * @param string $value 数组值名
     *
     * @return void
     */
    public static function issetToData($array = [], $name, $value)
    {
        if(isset($value)){
            $array[$name] = $value;
        }
        return $array;
    }



    /**
     * 数组转换为字符串，主要用于把分隔符调整到第二个参数
     * @param  array  $arr  要连接的数组
     * @param  string $glue 分割符
     * @return string
     */
    public static function arrToStr($arr, $glue = ',')
    {
        return implode($glue, $arr);
    }

    /**
     * 将二维数组数组按某个键提取出来组成新的索引数组
     */
    public static function arrayExtract($array = [], $key = 'id')
    {
        $count = count($array);
        $new_arr = [];
        for($i = 0; $i < $count; $i++) {
            if (isset($array[$i]) && isset($array[$i][$key])) {
                $new_arr[] = $array[$i][$key];
            }
        }
        return $new_arr;
    }

    /**
     * 根据某个字段获取关联数组
     */
    public static function arrayExtractMap($array = [], $key = 'id'){
        $count = count($array);
        $new_arr = [];
        for($i = 0; $i < $count; $i++) {
            $new_arr[$array[$i][$key]] = $array[$i];
        }
        return $new_arr;
    }

    /**
     * 关联数组转索引数组
     */
    public static function relevanceArrToIndexArr($array)
    {
        $new_array = [];
        foreach ($array as $v)
        {
            $temp_array = [];
            foreach ($v as $vv)
            {
                $temp_array[] = $vv;
            }
            $new_array[] = $temp_array;
        }
        return $new_array;
    }
    /**
     * 在数组指定位置插入新元素
     * @param array $array
     * @param mixed $item
     * @param int $offset
     * @return mixed
     */
    public static function insert($array, $item, $offset){
        array_splice($array,$offset,0,[$item]);
        return $array;
    }
    /**
     * 替换数组指定位置的元素
     * @param array $array
     * @param mixed $item
     * @param int $offset
     * @return mixed
     */
    static public function replace($array, $item, $offset){
        array_splice($array,$offset,1,[$item]);
        return $array;
    }
    /**
     * 删除数组指定索引的元素
     * @param array $array
     * @param int $offset
     * @return mixed
     */
    static public function delete($array, $offset){
        array_splice($array,$offset,1);
        return $array;
    }

    /**
     * 把数组转换成Tree
     * @param array $array 要转换的数据集
     * @param string $pid parent标记字段
     * @param string $level level标记字段
     * @return array
     */
    public static function arrayToTree($array, $pk='id', $pid = 'parent_id', $child = 'child', $root = 0)
    {
        // 创建Tree
        $tree = [];
        if (!is_array($array)) {
            return [];
        }
        // 创建基于主键的数组引用
        $refer = [];
        foreach ($array as $key => $data) {
            $refer[$data[$pk]] =& $array[$key];
        }
        foreach ($array as $key => $data) {
            // 判断是否存在parent
            $parentId =  $data[$pid];
            if ($root == $parentId) {
                $tree[] =& $array[$key];
            } else if (isset($refer[$parentId])){
                is_object($refer[$parentId]) && $refer[$parentId] = $refer[$parentId]->toArray();
                $parent =& $refer[$parentId];
                $parent[$child][] =& $array[$key];
            }
        }
        return $tree;
    }


    /**
     * 二维数组根据某个字段排序
     * @param array $array 要排序的数组
     * @param string $keys   要排序的键字段
     * @param string $sort  排序类型  SORT_ASC     SORT_DESC
     * @return array 排序后的数组
     */
    static function arraySort($array, $keys, $sort = SORT_DESC) {
        $keysValue = [];
        foreach ($array as $k => $v) {
            $keysValue[$k] = $v[$keys];
        }
        array_multisort($keysValue, $sort, $array);
        return $array;
    }

    /**
     * 随机取值
     * @param $array  [['id' => 1,'value' => 10],['id' => 1,'value' => 30]]
     * @param string $field  用哪个字段统计概率
     */
    public static function randomValue($array,$field = 'value')
    {
        $min = 0;
        $max = 0;
        foreach ($array as &$value){
            $max = $max + $value[$field] * 10000;
            $value['minValue'] =  $min;
            $value['maxValue'] =  $max;
            $min = $max;
        }
        $rand = mt_rand(1,$max);
        foreach ($array as $item){
            if ($rand > $item['minValue'] && $rand <= $item['maxValue']){
                return $item;
            }
        }
    }

    /**
     *
     * 随机取值 指定总数
     *
     * @param $array  [['id' => 1,'value' => 10],['id' => 1,'value' => 30]]
     * @param  int  $total  奖励品数目
     * @param  string  $field  用哪个字段统计概率
     * @param  bool  $repeat 是否重复
     *
     * @return array
     */
    public static function randomValueByTotal($array,$total = 1, $field = 'value', $repeat = false)
    {
        $getRandKey = function ($array) use($field){
            $rand = mt_rand(1, end($array)['maxValue']);
            foreach ($array as $key => $item){
                if ($rand >= $item['minValue'] && $rand <= $item['maxValue']){
                    return $key;
                }
            }
        };

        $doArray = function ($array) use($field){
            $min = 0;
            $max = 0;
            foreach ($array as &$value){
                $max = $max + $value[$field];
                $value['minValue'] =  $min + 1;
                $value['maxValue'] =  $max;
                $min = $max;
            }
            return $array;
        };

        $list = $doArray($array);
        $return = [];
        for ($i=0; $i < $total; $i++){
            $key = $getRandKey($list);
            $return[] = $array[$key];

            // 不重复 则将已出现的值设为0且重新计算概率
            if (! $repeat){
                $list[$key][$field] = 0;
                $list = $doArray($list);
            }
        }
        return $return;
    }

    /**
     * 随机获得数组或字符串中的值
     *
     * @param $obj
     * @param  string  $total
     * @param  string  $delimiter
     *
     * @return mixed|string
     */
    public static function randomObjectValue($obj, $total = 1,$delimiter = ','){
        ! is_array($obj) && $obj = explode($delimiter,$obj);
        if ($total == 1){
            return $obj[array_rand($obj)];
        }

        $keys = array_rand($obj, $total);
        $newObj = [];
        foreach ($keys as $key){
            $newObj[] = $obj[$key];
        }
        return $newObj;
    }

    /**
     * 根据概率返回确认结果：1,0
     * @param $rate
     */
    public static function randomConfirm($rate)
    {
        $data = [
            [
                'id' => 1,
                'value' => $rate,
            ],
            [
                'id' => 0,
                'value' => (100 - $rate) > 0 ? 100 - $rate : 0,
            ],
        ];
        $res = self::randomValue($data);
        return $res['id'];
    }
}
