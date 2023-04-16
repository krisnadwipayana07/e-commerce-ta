<?php

namespace App\Imports;

use App\Models\Admin;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AdminImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            $data = Admin::create([
                'name' => $row['name'],
                'address' => $row['address'],
                'phone_number' => $row['phone_number'],
                'instagram' => $row['instagram'],
                'username' => $row['username'],
                'password' => $row['username'],
            ]);
        }
    }
}
