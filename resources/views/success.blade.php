<!DOCTYPE html>
<html>
<head>
    <title>Redirecting...</title>
</head>
<body>
    <script>
        var data = @json($data);

        function redirectWithData() {
            var targetUrl = 'http://127.0.0.1:5500/dangnhap.html';

            var form = document.createElement('form');
            form.method = 'POST';
            form.action = targetUrl;

            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'data';
            input.value = JSON.stringify(data);

            form.appendChild(input);
            document.body.appendChild(form);

            form.submit();
        }

        window.onload = redirectWithData;
    </script>
</body>
</html>
