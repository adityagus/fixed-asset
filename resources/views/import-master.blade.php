<!DOCTYPE html>
<html>
<head>
    <title>Import Master Data</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1.5.10/css/pico.min.css">
</head>
<body>
<main class="container">
    <h2>Import Master Data from Excel</h2>
    @if(session('success'))
        <article style="color:green">{{ session('success') }}</article>
    @endif
    <form method="POST" action="{{ route('import.master') }}" enctype="multipart/form-data">
        @csrf
        <label>
            Excel File (.xlsx):
            <input type="file" name="file" required>
        </label>
        <button type="submit">Import</button>
    </form>
    <p>Sheet order: <b>mst_brg</b>, <b>mst_katbrg</b>, <b>mst_tipebrg</b>, <b>mst_merkbrg</b>, <b>mst_vendor</b></p>
</main>
</body>
</html>
