<?php

namespace  App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class BaseService implements IBaseService {
    public function fetch(Request $request){}

    public function update(array $data, Model $model){}

    public function store(array $data){}

    public function delete(Model $model){}
}