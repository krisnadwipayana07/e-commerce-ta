<!DOCTYPE html>
<html>
<head>
	<title>Sales Report</title>
</head>
<body>
	<style type="text/css">
		table {
			font-family: Arial, Helvetica, sans-serif;
			border-collapse: collapse;
  			width: 100%;
		}

		table td, table th{
			font-size: 10px;
			order: 1px solid #000;
  			padding: 8px;
		}

		table tr:nth-child(even) {
			background-color: #f2f2f2;
		}

		table th {
			padding-top: 12px;
			padding-bottom: 12px;
			text-align: left;
			background-color: #000;
			color: white;
		}
	</style>
	<center>
		<h1>Sales Report</h1>
	</center>
 
	<table>
		<tr>
			<th>No</th>
			<th>Customer Name</th>
			<th>Property Name</th>
			<th>Price</th>
			<th>Payment Method</th>
		</tr>
		@php $i=1 @endphp
		@foreach($data as $item)
		<tr>
			<td>{{ $i++ }}</td>
			<td>{{ $item->customer->name }}</td>
			<td>
				<ul>
				@foreach($item->transaction_detail as $td)
				<li>
					{{ $td->property->name }}
				</li>
				@endforeach
				</ul>
			</td>
			<td>{{ format_rupiah($item->total_payment) }}</td>
			<td>{{ $item->category_payment->name }}</td>
		</tr>
		@endforeach
	</table>
 
</body>
</html>