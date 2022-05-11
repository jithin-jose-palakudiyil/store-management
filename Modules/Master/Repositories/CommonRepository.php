<?php

namespace Modules\Master\Repositories;

use Illuminate\Database\Eloquent\Model;
use \Exception;
class CommonRepository implements RepositoryInterface
{
    // model property on class instances
    protected $model;
    public $return = null;
    public $response = null;
    // Constructor to bind model to repo
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    // Get all instances of model
    public function all()
    {
        return $this->model->all();
    }

    // create a new record in the database
    public function create(array $data)
    { 
        try{ $this->response= $this->model->create($data); } catch (Exception $ex) { $this->return = $ex->getMessage(); }
        $_return = ['error'=>$this->return,'response'=>$this->response];
        return $_return;
    }

    // update record in the database
    public function update(array $data, $record)
    {
        try{ $this->response= $record->update($data); } catch (Exception $ex) { $this->return = $ex->getMessage(); }
        $_return = ['error'=>$this->return,'response'=>$this->response];
        return $_return;
       
    }

    // remove record from the database
    public function delete($record,$save=null)
    {
        
        try{ $this->response = $record->delete(); 
        if($save==null){$record->save();} } catch (Exception $ex) { $this->return = $ex->getMessage(); }
        $_return = ['error'=>$this->return,'response'=>$this->response];
        return $_return;
       
//        return $this->model->destroy($id);
    }

    // show the record with the given id
    public function show($id)
    {
        return $this->model-findOrFail($id);
    }

    // Get the associated model
    public function getModel()
    {
        return $this->model;
    }

    // Set the associated model
    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    // Eager load database relationships
    public function with($relations)
    {
        return $this->model->with($relations);
    }
   
}