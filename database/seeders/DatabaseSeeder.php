<?php


namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Service;
use App\Models\Order;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Membuat 5 Master Layanan spesifik (menggunakan Sequence agar nama tidak acak)
        $services = Service::factory()
            ->count(10)
            ->state(new Sequence(
                ['name' => 'Cuci Kiloan', 'price_per_kg' => 7000, 'unit' => 'kg'],
                ['name' => 'Cuci Karpet', 'price_per_kg' => 15000, 'unit' => 'meter'],
                ['name' => 'Setrika Express', 'price_per_kg' => 6000, 'unit' => 'kg'],
                ['name' => 'Dry Cleaning', 'price_per_kg' => 20000, 'unit' => 'pcs'],
                ['name' => 'Cuci Selimut', 'price_per_kg' => 12000, 'unit' => 'pcs'],
            ))
            ->create();

        // 2. Membuat 10 Data Pelanggan dummy
        $customers = Customer::factory()->count(10)->create();

        // 3. Membuat 1 Transaksi Pesanan Lengkap untuk salah satu pelanggan acak
        $chosenCustomer = $customers->random();
        
        $order = Order::create([
            'customer_id' => $chosenCustomer->id,
            'invoice_code' => 'INV-' . strtoupper(Str::random(8)),
            'order_date' => now()->subDays(2),
            'completion_date' => now(),
            'status' => 'selesai',
            'total_price' => 0,
        ]);

        // 4. Memasukkan entri ke tabel pivot order_details (ambil 2 layanan acak untuk pesanan ini)
        $randomServices = $services->random(2);
        $totalPrice = 0;

        foreach ($randomServices as $service) {
            $qty = rand(2, 5); // Kuantitas acak
            $subtotal = $service->price_per_kg * $qty;
            $totalPrice += $subtotal;

            // Menempelkan ke pivot table order_details
            $order->services()->attach($service->id, [
                'qty' => $qty,
                'subtotal' => $subtotal
            ]);
        }

        // Update total_price final di tabel orders
        $order->update(['total_price' => $totalPrice]);
    }
}


