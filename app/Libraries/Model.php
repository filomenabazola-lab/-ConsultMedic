<?php


// namespace App\models;

// use App\db\Database;
// use App\db\QueryBuilder;

// class Model extends Database
// {
//     public function find($id)
//     {
//         $sql = "SELECT * FROM {$this->table} WHERE {$this->primary_key}={$id}";
//         $this->query($sql);
//         return $this->result();
//     }

//     public function findBy($value,$field)
//     {

//         $sql = "SELECT * FROM {$this->table} WHERE {$field}=:{$field}";
//         $this->query($sql);
//         $this->bind(':'.$field,$value);
//         return $this->results();
//     }

//     public function findAll($limit=10)
//     {
//         $sql = "SELECT * FROM {$this->table} WHERE {$this->primary_key} >= 1 LIMIT $limit";
//         $this->query($sql);
//         return $this->results();
//     }

//     public function all()
//     {
//         $sql = "SELECT * FROM {$this->table}";
//         $this->query($sql);
//         return $this->results();
//     }


//     public function create(array $data)
//     {
//         try {
//             $sql = QueryBuilder::insert($data,$this->table);   
//             $this->query($sql);
//             foreach($data as $key => $value){
//                 $this->bind(':'.$key,$value);
//             }
//             return $this->execute($data);
//         } catch (\Exception $e) {
//             return $e->getMessage();
//         }
//     }

//     public function update(array $data,array $where=[])
//     {
//         try {    
//             $sql = QueryBuilder::update($data,$this->table,$where);
//             $this->query($sql);
//             foreach($data as $key => $value){
//                 $this->bind(':'.$key,$value);
//             }
//             return $this->execute();
//         } catch (\Exception $e) {
//             return $e->getMessage();
//         }
//     }

//     public function delete($id)
//     {
//         $sql = "DELETE FROM {$this->table} WHERE {$this->primary_key}=$id";
//         $this->query($sql);
//         return $this->execute();
//     }

//     public function paginate(int $page=1, int $itemsPerPage=10)
//     {
//         $start = ($page - 1)*$itemsPerPage;
//         $this->query("SELECT SQL_CALC_FOUND_ROWS * FROM $this->table LIMIT $start,$itemsPerPage; SELECT FOUND_ROWS() AS nrtotal;");
//         $results = $this->results();
//         return $results;
//     }
// }