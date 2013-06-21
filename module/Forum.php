<?php //-->
class Forum extends Eden_Class {
    /* Constants
    -------------------------------*/
    /* Public Properties
    -------------------------------*/
    /* Protected Properties
    -------------------------------*/
    protected $_mongo;
    protected $_collection;
    /* Private Properties
    -------------------------------*/
    /* Magic
    -------------------------------*/
    public function __construct() {
        if ($_SERVER['HTTP_HOST'] == 'forum.openovate.com'){
            define('DOCROOT', realpath(dirname(__FILE__)).'/');
            include(DOCROOT . 'mongo.inc');
            $this->_mongo = new Mongo($mongo);
        }
        else {
            $this->_mongo = new MongoClient();  
        }
        $this->_collection  = $this->_mongo->eden_forum;
    }
     
    /* Public Methods
    -------------------------------*/
    /**
     * Return model
     *
     * @param scalar|null
     * @param string
     * @return this
     */
    public function load($value, $column = NULL) {
        //argument testing
        User_Error::i()
            ->argument(1, 'scalar', 'array') //argument 1 must be a scalar or array
            ->argument(2, 'string', 'null'); //argument 1 must be a string or null
         
        if(is_array($value)) {
            return $this->set($value);
        }
         
        if(is_null($column)) {
            $meta = $this->_getMeta();
            $column = $meta['primary'];
        }
         
        $row = $this->_collection->getRow($this->_table, $column, $value);
         
        if(is_null($row)) {
            return $this;
        }
         
        return $this->set($row);
    }
    /*
    *   Selects all the topics
    *   @return array
    */
    public function getAllTopics(){

        $topic = $this->_collection->topics;
        $cursor = $topic->find()->sort( array('_id' => -1) );
        return iterator_to_array($cursor);
    }
    /*
        deletes the collection topics.
    */
    public function deleteAllTopics(){
        $topic = $this->_collection->topics;
        $topic->remove();

    }
    /*
        Creates a topic/thread
        @param title the subject of the topic string
        @param content the content of the topic string
        @return nothing
    */
    public function createTopic($title, $content){
        $topic = $this->_collection->topics;

        $document = array(
            "topic_title" => $title,
            "reply" => array(array(
                        "reply_title" => $title,
                        "reply_content" =>$content,
                        "reply_created" => date('Y-m-d h:i A'),
                        "reply_userid" => $_SESSION['uid'],
                        "reply_name" => $_SESSION['name'],
                        "reply_picture" => $_SESSION['user_picture']

                        )
                        )
            );
        $topic->insert($document);
    }
    /*
        Selects all the users
        @return array
    */
    public function checkEmail($email){
        $users = $this->_collection->users;
        $cursor = $users->find(array('user_email' => $email));
        return !$cursor->count();
    }
    /*
        Uploads the browsed file.
        @return boolean
    */
    public function uploadAvatar(){

        if ((($_FILES["myFile"]["type"] == "image/gif")
        || ($_FILES["myFile"]["type"] == "image/jpeg")
        || ($_FILES["myFile"]["type"] == "image/jpg")
        || ($_FILES["myFile"]["type"] == "image/pjpeg")
        || ($_FILES["myFile"]["type"] == "image/x-png")
        && ($_FILES["myFile"]["type"] != "")
        || ($_FILES["myFile"]["type"] == "image/png")))
        {
            move_uploaded_file($_FILES['myFile']['tmp_name'], 'assets/uploads/' . $_FILES['myFile']['name']);
            return true;
        }
        else{
            return false;
        }
    }
    /*
        Creates user
        @return array
    */
    public function createUser(){
        $users = $this->_collection->users;
        $this->uploadAvatar();
        $document = array(
            "user_firstname" => $_POST['firstname'],
            "user_lastname" => $_POST['lastname'],
            "user_email" => $_POST['email'],
            "user_password" => $_POST['password'],
            "user_picture" => (isset($_FILES['myFile']['name'])) ? $_FILES['myFile']['name'] : ""
            );
        $users->insert($document);
        return $document;
    }
     /*
        Check if theres a user with that email and password
        @param email
        @param password 
        @return array
    */
    public function checkUser($email, $password){
        $users = $this->_collection->users;
        $cursor = $users->find(array('user_email' => $email , 'user_password' => $password));
        return iterator_to_array($cursor);
    }
    /*
        Selects all the users
        @return array
    */
    public function selectUsers(){
        $users = $this->_collection->users;
        $cursor = $users->find();

        return iterator_to_array($cursor);
    }
    /*
        Deletes all the users
        @return array
    */
    public function deleteAllUsers(){
        $users = $this->_database->users;
        $users->remove();

    }
    /* Protected Methods
    -------------------------------*/
    /* Private Methods
    -------------------------------*/
}