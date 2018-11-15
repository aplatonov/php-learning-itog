<html>
<head></head>
<body>
<p>You received a message from PM helper:</p>

<p>
From: {{ $person_name }} ({{ $email }})
</p>
<hr>
<p>
  @foreach ($user_message as $messageLine)
    {{ $messageLine }}<br>
  @endforeach
</p>
<hr>
</body>
</html>