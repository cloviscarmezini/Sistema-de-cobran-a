<h2>Olá {{$account->client->name}}, você possui um novo título</h2>
<br>
<p>Número: {{$account->id}}</p>
<p>Valor: R${{number_format($account->value, 2, ',', '.')}}</p>
<p>Data de Vencimento: {{$account->expiration_date->format('d/m/Y')}}</p>
