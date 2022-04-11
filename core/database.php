<?
abstract class database{
    abstract public function get();
    abstract public function insert($data);
    abstract public function update($id, $data);
    abstract public function deleteId($id);
}