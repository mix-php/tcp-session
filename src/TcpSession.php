<?php

namespace Mix\Tcp\Session;

use Mix\Core\Component\AbstractComponent;
use Mix\Core\Component\ComponentInterface;

/**
 * Class TcpSession
 * @package Mix\Tcp\Session
 * @author liu,jian <coder.keda@gmail.com>
 */
class TcpSession extends AbstractComponent
{

    /**
     * 协程模式
     * @var int
     */
    const COROUTINE_MODE = ComponentInterface::COROUTINE_MODE_REFERENCE;

    /**
     * 处理器
     * @var \Mix\Tcp\Session\TcpSessionHandlerInterface
     */
    public $handler;

    /**
     * 获取文件描述符
     * 为了实现可在任意子协程中使用，必须每次使用都提取当前的文件描述符
     * @return int
     */
    protected function getFileDescriptor()
    {
        // 设置fd
        if (\Mix::$app->isRunning('tcp')) {
            return \Mix::$app->tcp->fd;
        }
        if (\Mix::$app->isRunning('ws')) {
            return \Mix::$app->ws->fd;
        }
        if (\Mix::$app->isRunning('request')) {
            return \Mix::$app->request->fd;
        }
        return -1;
    }

    /**
     * 获取
     * @param null $key
     * @return mixed
     */
    public function get($key = null)
    {
        $fd = $this->getFileDescriptor();
        return $this->handler->get($fd, $key);
    }

    /**
     * 设置
     * @param $key
     * @param $value
     * @return bool
     */
    public function set($key, $value)
    {
        $fd = $this->getFileDescriptor();
        return $this->handler->set($fd, $key, $value);
    }

    /**
     * 删除
     * @param $key
     * @return bool
     */
    public function delete($key)
    {
        $fd = $this->getFileDescriptor();
        return $this->handler->delete($fd, $key);
    }

    /**
     * 清除
     * @return bool
     */
    public function clear()
    {
        $fd = $this->getFileDescriptor();
        return $this->handler->clear($fd);
    }

    /**
     * 判断是否存在
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        $fd = $this->getFileDescriptor();
        return $this->handler->has($fd, $key);
    }

}
