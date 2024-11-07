<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Data Order</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<div class="container pb-5 pt-5">
    <div class="row">
        <div class="col-8 mx-auto">
           <div class="card">
               <div class="table-responsive">
                   <table class="table table-hover table-condensed">
                       <thead>
                       <tr>
                           <th>#</th>
                           <th>ID</th>
                           <th>Total Harga</th>
                           <th>Status</th>
                           <th></th>
                       </tr>
                       </thead>
                       <tbody>
                       @forelse($orders as $order)
                           <tr>
                               <td>{{ $loop->iteration }}</td>
                               <td>#{{ $order->order_id }}</td>
                               <td>Rp {{ number_format($order->total_price, 2, ',', '.') }}</td>
                               <td>{{ $order->status }}</td>
                               <td>
                                   <a href="{{ route('customer.orders.show', $order->id) }}" class="btn btn-primary">Detail</a>
                               </td>
                           </tr>
                       @empty
                            <tr>
                                 <td colspan="5">Tidak ada data</td>
                            </tr>
                       @endforelse
                       </tbody>
                   </table>
               </div>
           </div>
        </div>
    </div>
</div>
</body>
</html>
