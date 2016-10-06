<?php


namespace AppBundle\FCMService;


class FCMMessage
{
    private $receiverId;
    private $notification ;
    private $data;

    /**
     * @return mixed
     */
    public function getNotification()
    {
        return $this->notification;
    }

    /**
     * @param mixed $notification
     */
    public function setNotification($notification)
    {
        $this->notification = $notification;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getReceiverId()
    {
        return $this->receiverId;
    }

    /**
     * @param mixed $receiverId
     */
    public function setReceiverId($receiverId)
    {
        $this->receiverId = $receiverId;
    }

    public function getMessage(){
        $message=array();
        if(is_array($this->notification))
            $message['notification']=$this->notification;
        if(is_array($this->data))
            $message['data']=$this->data;
        $message['to']=$this->receiverId;
        return json_encode($message);
    }

}