<?php
namespace app\model;
use think\Model;

class BaseModel extends Model
{
    protected $defaultField = '*';
    static protected $ins = [];
    /**
     * @return static
     */
    static public function getInstance(){
        $cName = get_called_class();
        if( !isset(static::$ins[$cName]) ){
            static::$ins[$cName] = new static;;
        }
        return static::$ins[$cName];
    }

    /**
     * 获取信息
     */
    public function getInfo($where, $field = '')
    {
        if(empty($field)){
            $field = $this->defaultField;
        }
        return $this->where($where)->field($field)->findOrEmpty();
    }

    public function updateInfo($where,$data)
    {
        return $this->update($data,$where);
    }

    /**
     * @param  $where
     * @param  mixed  $field
     *
     * @return mixed|void
     */
    public function getValueByField($where,$field){
        return $this->where($where)->value($field);
    }

    /**
     * @param $where
     * @param $field
     * @param  string  $key
     *
     * @return array
     */
    public function getColumnByField($where, $field, $key = ''){
        return $this->where($where)->column($field,$key);
    }
}
