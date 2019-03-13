<?php

namespace Mix\Tcp\Session;

use Mix\Core\Component\AbstractComponent;
use Mix\Core\Component\ComponentInterface;

/**
 * Class TcpSession
 * @package Mix\Tcp\Session
 * @author LIUJIAN <coder.keda@gmail.com>
 */
class TcpSession extends AbstractComponent
{

    /**
     * 协程模式
     * @var int
     */
    public static $coroutineMode = ComponentInterface::COROUTINE_MODE_REFERENCE;

    /**
     * 处理器
     * @var \Mix\Tcp\Session\HandlerInterface
     */
    public $handler;

    /**
     * 文件描述符
     * @var int
     */
    public $fd;

    /**
     * 提取文件描述符
     * 为了支持 TcpSession 在全部的子协程中使用，所以需要每次使用都提取当前的文件描述符
     * @return bool
     */
    protected function extractFileDescriptor()
    {
        // 设置fd
        if (\Mix::$app->isRunning('tcp')) {
            $this->fd = \Mix::$app->tcp->fd;
            return true;
        }
        if (\Mix::$app->isRunning('ws')) {
            $this->fd = \Mix::$app->ws->fd;
            return true;
        }
        if (\Mix::$app->isRunning('request')) {
            $this->fd = \Mix::$app->request->fd;
            return true;
        }
        return false;
    }

    /**
     * 获取
     * @param null $key
     * @return mixed
     */
    public function get($key = null)
    {
        $this->extractFileDescriptor();
        return $this->handler->get($key);
    }

    /**
     * 设置
     * @param $key
     * @param $value
     * @return bool
     */
    public function set($key, $value)
    {
        $this->extractFileDescriptor();
        return $this->handler->set($key, $value);
    }

    /**
     * 删除
     * @param $key
     * @return bool
     */
    public function delete($key)
    {
        $this->extractFileDescriptor();
        return $this->handler->delete($key);
    }

    /**
     * 清除
     * @return bool
     */
    public function clear()
    {
        $this->extractFileDescriptor();
        return $this->handler->clear();
    }

    /**
     * 判断是否存在
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        $this->extractFileDescriptor();
        return $this->handler->has($key);
    }

}
