<?php
/**
 * @author Serebro http://github.com/serebro
 * @author Patsura Dmitry http://github.com/ovr <talk@dmtry.me>
 */

namespace Ovr\Phalcon\Elastic;

/**
 * Class ModelTrait
 * @package Ovr\Phalcon\Elastic
 */
trait ModelTrait
{
    protected static function di()
    {
        return \Phalcon\DI::getDefault();
    }
    
    /**
     * @return \Elastica\Client
     */
    public static function getConnection()
    {
        return self::di()->get('elastica');
    }
    
    /**
     * @return \Elastica\Type
     */
    public static function getStorage()
    {
        return static::getConnection()->getIndex(static::$index)->getType(static::$type);
    }
    
    /**
     * @param $id
     * @return \Elastica\Document
     */
    public static function findById($id)
    {
        return static::getStorage()->getDocument($id);
    }
    
    /**
     * @param $data
     * @return \Elastica\Response
     */
    public static function add($data)
    {
        $data['id'] = empty($data['id']) ? null : $data['id'];
        $data['synced'] = static::utcTime()->format(DATE_ISO8601);
        $doc = new \Elastica\Document($data['id'], $data);
        return static::getStorage()->addDocument($doc);
    }
    
    /**
     * @param $id
     * @return \Elastica\Response
     */
    public static function deleteById($id)
    {
        return static::getStorage()->deleteById($id);
    }
    
    /**
     * @param string $time
     * @return \DateTime
     */
    public static function utcTime($time = 'now')
    {
        if (is_int($time)) {
            $time = '@' . $time;
        }
        return new \DateTime($time, new \DateTimeZone('UTC'));
    }
} 
