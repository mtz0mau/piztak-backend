<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $customers = [
            [
                'firstname' => 'mauricio',
                'phone_number' => '2712553444'
            ],
            [
                'firstname' => 'edmundo',
                'phone_number' => '2341239687'
            ],
        ];

        foreach($customers as $customer) Customer::create($customer);

        // $url = 'http://127.0.0.1:7000/api/clientes?limit=2000';

        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, $url);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // $response = curl_exec($ch);
        // curl_close($ch);

        // $res = json_decode($response);
        // $customers = $res->data;

        // foreach($customers as $customer){
        //     $data = [
        //         'firstname' => strtolower($customer->firstname),
        //         'lastname' => strtolower($customer->lastname),
        //         'phone_number' => trim(str_replace(' ', '', $customer->phone_number)),
        //         'old_id' => intval($customer->id),
        //     ];

        //     // Validar numero
        //     if(strlen($data['phone_number']) !== 10)continue;
        //     // Validar que no hay registros con el mismo numero
        //     $previousCustomer = Customer::where(['phone_number' => $data['phone_number']])->first();
        //     if($previousCustomer) continue;

        //     $newCustomer = Customer::create($data);

        //     $url = 'http://127.0.0.1:7000/api/clientes/'.$customer->id.'/direcciones';

        //     $ch = curl_init();
        //     curl_setopt($ch, CURLOPT_URL, $url);
        //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //     $response = curl_exec($ch);
        //     curl_close($ch);

        //     $res = json_decode($response);
        //     $addresses = $res->data;
            
        //     foreach($addresses as $address){
        //         Address::create([
        //             'street' => strtolower($address->calle),
        //             'street_number' => $address->nExterior,
        //             'interior_number' => $address->nInterior,
        //             'references' => $address->referencia,
        //             'district_id' => $address->coloniaId,
        //             'customer_id' => $newCustomer->id,
        //         ]);
        //     }
        // }
    }
}

// Customer
// {"id":"13","firstname":"Carlos","lastname":"Maceda","email":"","phone_number":"2711874897","estado":"1","createAt":"0000-00-00 00:00:00","lastUpdate":"0000-00-00 00:00:00"}

// Addresses
// [{"direccionId":"975","estado":1,"createAt":"2023-04-21 11:29:12","lastUpdate":"2023-04-21 11:29:12","calle":"San Lazaro ","nExterior":"315","nInterior":"C","entreCalles":"","referencia":"comadre de gely","coloniaId":"1","colonia":"Colinas de San Jose","lat":"","lng":""}]