<?php


namespace App\Repositories\Eloquent;


use App\Repositories\Contracts\RepositoryInterface;

class AbstractRepository implements RepositoryInterface
{
    protected $model;
    protected $class;
    public function __construct(Model $instance = null)
    {
        if (!$instance) {
            return $this->make();
        }
        $this->model = $instance;
    }

    public function all($columns = ['*'])
    {
        return $this->model->get($columns);
    }

    public function create(array $data)
    {
        if (!$this->validate($data)) {
            return $this->response();
        }

        return $this->model->create($data);
    }

    public function find($id, $columns = ['*'])
    {
        return $this->model->find($id, $columns);
    }

    public function delete($id)
    {
        return $this->model->destroy($id);
    }

    public function findBy($field, $value, $columns = ['*'])
    {
        return $this->model->where($field, $value)->first($columns);
    }

    public function paginate($perPage = 15, $columns = ['*'])
    {
        return $this->model->paginate($perPage, $columns);
    }

    public function reverse()
    {
        return $this->model->orderBy('id', 'DESC');
    }

    public function update(array $data, $id, $attribute = null)
    {
        if ($attribute === null) {
            $attribute = $this->model->getKeyName();
        }

        if (!$this->validate($data, $id)) {
            return $this->response();
        }

        $q = $this->model->where($attribute, $id);
        $q->update($data);

        return $q->first();
    }

    protected function make()
    {
        if ($this->class) {
            $this->model = new $this->class;
            return;
        }
        $class = get_class();
        $class = str_replace('Repository', '', $class);
        $this->model = new $class;

    }

    public function validate(array $input, $id = null)
    {
        return true;
    }
}

