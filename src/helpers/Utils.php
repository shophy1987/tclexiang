<?php

namespace shophy\tclexiang\helpers;

class Utils
{
    static public function notEmptyStr($var)
    {
        return is_string($var) && ($var != "");
    }

    static public function checkNotEmptyStr($var, $name)
    {
        if (!self::notEmptyStr($var))
            throw new exceptions\ArgumentException("can not be empty string", $name);
    }

    static public function checkIsUInt($var, $name)
    {
        if (!(is_int($var) && $var >= 0))
            throw new exceptions\ArgumentException("need unsigned int", $name);
    }

    static public function checkNotEmptyArray($var, $name)
    {
        if (!is_array($var) || count($var) == 0) {
            throw new exceptions\ArgumentException("can not be empty array", $name);
        }
    }

    static public function checkArrayKeyAndUInt(&$array, $key)
    {
        if (!isset($array[$key]))
            throw new exceptions\ArgumentException("required parameters are missing", $key);
        if (!(is_int($array[$key]) && $array[$key] >= 0))
            throw new exceptions\ArgumentException("need unsigned int", $key);
    }

    static public function checkArrayKeyAndNotEmptyStr(&$array, $key)
    {
        if (!isset($array[$key]))
            throw new exceptions\ArgumentException("required parameters are missing", $key);
        if (!self::notEmptyStr($array[$key]))
            throw new exceptions\ArgumentException("can not be empty string", $key);
    }

    static public function checkArrayKeyAndNotEmptyArray(&$array, $key)
    {
        if (!isset($array[$key]))
            throw new exceptions\ArgumentException("required parameters are missing", $key);
        if (!notEmptyArray($array[$key]))
            throw new exceptions\ArgumentException("can not be empty array", $key);
    }

    static public function setIfNotNull($var, $name, &$args)
    {
        if (!is_null($var)) {
            $args[$name] = $var;
        }
    }

    static function notEmptyArray($var) 
    {
        if (!is_array($var))
            return false;

        foreach ($var as $_val) {
            if (is_array($_val) && !self::notEmptyArray($_val))
                return false;
            if (is_string($_val) && $_val == '')
                return false;
        }

        return true;
    }

    static public function arrayGet($array, $key, $default=null)
    {
        if (array_key_exists($key, $array))
            return $array[$key];
        return $default;
    } 

	/**
	 * 数组 转 对象
	 *
	 * @param array $arr 数组
	 * @return object
	 */
	static public function Array2Object($arr) {
		if (gettype($arr) != 'array') {
			return;
		}
		foreach ($arr as $k => $v) {
			if (gettype($v) == 'array' || getType($v) == 'object') {
				$arr[$k] = (object)self::Array2Object($v);
			}
		}

		return (object)$arr;
	}

	/**
	 * 对象 转 数组
	 *
	 * @param object $obj 对象
	 * @return array
	 */
	static public function Object2Array($object) { 
		if (is_object($object) || is_array($object)) {
            $array = array();
			foreach ($object as $key => $value) {
                if ($value == null) continue;
				$array[$key] = self::Object2Array($value);
			}
            return $array;
		}
		else {
			return $object;
		}
	}
    //数组转XML
    static public function Array2Xml($rootName, $arr)
    {
        $xml = "<".$rootName.">";
        foreach ($arr as $key=>$val) {
            if (is_numeric($val)) {
                $xml.="<".$key.">".$val."</".$key.">";
            } else {
                 $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</".$rootName.">";
        return $xml;
    }

    //将XML转为array
    static public function Xml2Array($xml)
    {    
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);        
        return $values;
    }

}
