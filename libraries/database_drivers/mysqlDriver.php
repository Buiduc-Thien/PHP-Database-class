<?
class mySqlDB extends database{
    protected $connection = null;
    protected $host = '';
    protected $user = '';
    protected $password = '';
    protected $name = '';
    protected $table = '';
    protected $stmt = null;
    protected $limit = '';
    protected $offset = '';


    public function __construct($config)
    {
        $this->host = $config['host'];
        $this->user = $config['user'];
        $this->password = $config['password'];
        $this->name = $config['name'];

        // connect DB
        $this->connect();
    }
    public function connect(){
        $this->connection = new mysqli($this->host, $this->user, $this->password, $this->name);
        if($this->connection->connect_errno){
            exit($this->connection->connect_error);
        }

    }

    public function table($tableName){
        $this->table = $tableName;
        return $this;
    }



    // reset data
    public function resetQuery(){
        $this->table = '';
        $this->limit = 15;
        $this->offset = 0;
    }

    // get data

    public function limit($limit){
        $this->limit = $limit;
        return $this;
    }
    public function offset($offset){
        $this->offset = $offset;
        return $this;
    }
    public function get(){
        $sql ="select * from $this->table limit ? offset ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param('ii', $this->limit, $this->offset);
        $stmt->execute();
        $this->resetQuery();
        $result = $stmt->get_result();
        $data = [];
        while($each = $result->fetch_object()){
            $data[] = $each;
        }
        return $data;
    }



    // insert
    public function insert($data = []){
        $fields = implode(',', array_keys($data));
        $values = array_values($data);
        $valueStr = implode(',', array_fill(0, count($data),'?'));
        $sql = "INSERT INTO $this->table($fields) VALUES ($valueStr)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param(str_repeat('s',count($data)), ...$values);
        $stmt->execute();
        $this->resetQuery();

    }
    // update
    public function update($id, $data){
        $keyValues = [];
        foreach($data as $key => $value){
            $keyValues[] = $key.'=?';
        }
        $setFields = implode(',', $keyValues);
        $values = array_values($data);
        $values[] = $id;


        $sql = "Update $this->table set $setFields where id = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param(str_repeat('s',count($data)).'i',...$values);
        $stmt->execute();
        $this->resetQuery();

    }


    // delete
    public function deleteId($id){
        $sql = "delete from $this->table where id = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param('i',$id);
        $stmt->execute();
        $this->resetQuery();
        return $this->connection->affected_rows;
        
    }
}