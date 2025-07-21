<!DOCTYPE html>
<html>
<head>
    <title>Error</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: Arial, sans-serif; background: #f8fafc; color: #222; margin: 0; padding: 0; }
        .container { max-width: 480px; margin: 10vh auto; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px #0001; padding: 2rem; text-align: center; }
        h1 { font-size: 2.2rem; margin-bottom: 1rem; }
        p { color: #555; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Whoops, something went wrong.</h1>
        <p>{{ $errorMessage ?? 'An unexpected error occurred.' }}</p>
    </div>
</body>
</html>
