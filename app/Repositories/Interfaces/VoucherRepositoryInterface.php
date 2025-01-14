<?php

namespace App\Repositories\Interfaces;

interface VoucherRepositoryInterface
{
    public function create(array $data);
    public function getAllByCourse(int $id);
    public function getByCode($code);
    public function find(int $id);
    public function getAllVoucher($perPage);
    public function getById($id);
    public function update($id, $data);
    public function delete($id);
}
