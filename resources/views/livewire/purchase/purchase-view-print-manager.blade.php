<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Purchase - {{ $purchase->number }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <!-- Core head -->
    @include('admin.partials.head', ['vendor' => isset($vendor) ? $vendor : []])
</head>

<body>
    @include('livewire.purchase.data-view-purchase')

    <script type="text/javascript">
        window.print();
        setTimeout("window.close();", 1000);
    </script>
</body>

</html>
