<?php

namespace DJEM;

class EditorLoadFields
{
    private $model = null;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function model()
    {
        return $this->model;
    }

    private function params($params)
    {
        $result = [];
        foreach ($params as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $key => $value) {
                    $result[$key] = $value;
                }
            } else {
                $result[] = $value;
            }
        }
        return $result;
    }

    private function loadTypeRelation($key, $value, $callable = null)
    {
        if (!$this->model) {
            return;
        }

        $field = $value;
        if ($value instanceof \Closure) {
            $field = $key;
        }

        $relation = $this->model->{$field}();

        if ($callable instanceof \Closure) {
            $collection = $callable($relation);
        } else {
            $collection = $relation;
        }

        if ($value instanceof \Closure) {
            $this->model->{$field} = $value($collection);
        } else {
            $this->model->{$field} = $collection;
        }
    }

    public function one()
    {
        foreach ($this->params(func_get_args()) as $key => $relation) {
            $this->loadTypeRelation($key, $relation, function ($relation) {
                return $relation->first();
            });
        }
        return $this;
    }

    public function all()
    {
        foreach ($this->params(func_get_args()) as $key => $relation) {
            $this->loadTypeRelation($key, $relation, function ($relation) {
                return $relation->get();
            });
        }
        return $this;
    }

    public function select()
    {
        foreach ($this->params(func_get_args()) as $key => $relation) {
            $this->loadTypeRelation($key, $relation, function ($relation) {
                $value = $relation->select([ 'id', 'name' ])->first();
                if ($value) {
                    return [ 'value' => $value->id, 'text' => $value->name ];
                } else {
                    return (object) [];
                }
            });
        }
        return $this;
    }

    public function tag()
    {
        foreach ($this->params(func_get_args()) as $key => $relation) {
            $this->loadTypeRelation($key, $relation, function ($relation) {
                return $relation->select('id', 'name')->get()->map(function ($value) {
                    return [
                        'value' => $value->id,
                        'text'  => $value->name
                    ];
                });
            });
        }
        return $this;
    }
}
